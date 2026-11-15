<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PracticeQuiz;
use App\Models\PracticeQuestion;
use App\Models\PracticeSession;
use App\Models\PracticeSubject;
use App\Models\PracticeChapter;
use App\Models\categories;

class PracticeQuizAdminController extends Controller
{
    // =========================================================================
    // SUBJECTS MANAGEMENT
    // =========================================================================

    public function subjectsIndex()
    {
        $subjects = PracticeSubject::with('category')
            ->withCount(['chapters'])
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'DESC')
            ->get();

        foreach ($subjects as $s) {
            $s->levels_count = PracticeQuiz::whereIn('chapter_id', $s->chapters()->pluck('id'))->count();
            $s->questions_count = PracticeQuestion::whereIn('practice_quiz_id', PracticeQuiz::whereIn('chapter_id', $s->chapters()->pluck('id'))->pluck('id'))->count();
        }

        return view('cms.practice_quiz.subjects.index', compact('subjects'));
    }

    public function subjectCreate()
    {
        $categories = categories::orderBy('name', 'ASC')->get();
        return view('cms.practice_quiz.subjects.create', compact('categories'));
    }

    public function subjectStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
        ]);

        $slugBase = Str::slug($request->title);
        $slug = $slugBase ?: 'subject';
        $counter = 1;
        while (PracticeSubject::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'subject_' . time() . '_' . rand(100, 999) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/practice_subjects'), $imageName);
            $imagePath = 'uploads/practice_subjects/' . $imageName;
        }

        PracticeSubject::create([
            'category_id' => $request->category_id ?: null,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $imagePath,
            'sort_order' => $request->sort_order ?: 0,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('cms.practice_subjects')->with('status', 'Practice Subject created successfully!');
    }

    public function subjectEdit($id)
    {
        $subject = PracticeSubject::findOrFail($id);
        $categories = categories::orderBy('name', 'ASC')->get();
        return view('cms.practice_quiz.subjects.edit', compact('subject', 'categories'));
    }

    public function subjectUpdate(Request $request, $id)
    {
        $subject = PracticeSubject::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
        ]);

        if ($subject->title !== $request->title) {
            $slugBase = Str::slug($request->title);
            $slug = $slugBase ?: 'subject';
            $counter = 1;
            while (PracticeSubject::where('slug', $slug)->where('id', '!=', $subject->id)->exists()) {
                $slug = $slugBase . '-' . $counter++;
            }
            $subject->slug = $slug;
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'subject_' . time() . '_' . rand(100, 999) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/practice_subjects'), $imageName);
            $subject->image = 'uploads/practice_subjects/' . $imageName;
        }

        $subject->category_id = $request->category_id ?: null;
        $subject->title = $request->title;
        $subject->description = $request->description;
        $subject->sort_order = $request->sort_order ?: 0;
        $subject->is_active = $request->has('is_active') ? (bool)$request->is_active : true;
        $subject->save();

        return redirect()->route('cms.practice_subjects')->with('status', 'Practice Subject updated successfully.');
    }

    public function subjectDestroy($id)
    {
        $subject = PracticeSubject::findOrFail($id);
        $subject->delete();
        return redirect()->route('cms.practice_subjects')->with('status', 'Practice Subject deleted successfully.');
    }

    public function subjectToggle($id)
    {
        $subject = PracticeSubject::findOrFail($id);
        $subject->is_active = !$subject->is_active;
        $subject->save();
        return back()->with('status', 'Subject status changed successfully.');
    }

    // =========================================================================
    // CHAPTERS MANAGEMENT
    // =========================================================================

    public function chaptersIndex($subjectId)
    {
        $subject = PracticeSubject::with('category')->findOrFail($subjectId);
        $chapters = PracticeChapter::where('subject_id', $subject->id)
            ->withCount(['levels'])
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        foreach ($chapters as $ch) {
            $ch->questions_count = PracticeQuestion::whereIn('practice_quiz_id', $ch->levels()->pluck('id'))->count();
        }

        return view('cms.practice_quiz.chapters.index', compact('subject', 'chapters'));
    }

    public function chapterCreate($subjectId)
    {
        $subject = PracticeSubject::findOrFail($subjectId);
        $nextSortOrder = PracticeChapter::where('subject_id', $subject->id)->max('sort_order') + 1;
        return view('cms.practice_quiz.chapters.create', compact('subject', 'nextSortOrder'));
    }

    public function chapterStore(Request $request, $subjectId)
    {
        $subject = PracticeSubject::findOrFail($subjectId);

        $request->validate([
            'title' => 'required|string|max:255',
            'exam_duration_minutes' => 'required|integer|min:1|max:180',
            'exam_pass_mark_percent' => 'nullable|integer|min:0|max:100',
            'target_repetitions' => 'required|integer|min:1|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $slugBase = Str::slug($request->title);
        $slug = $slugBase ?: 'chapter';
        $counter = 1;
        while (PracticeChapter::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        $chapter = PracticeChapter::create([
            'subject_id' => $subject->id,
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'exam_duration_minutes' => (int)$request->exam_duration_minutes ?: 15,
            'exam_pass_mark_percent' => (int)($request->exam_pass_mark_percent ?? 0),
            'target_repetitions' => (int)$request->target_repetitions ?: 3,
            'sort_order' => $request->sort_order ?: 1,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('cms.practice_chapters', $subject->id)
            ->with('status', 'Chapter created successfully! Now you can add levels.');
    }

    public function chapterEdit($subjectId, $chapterId)
    {
        $subject = PracticeSubject::findOrFail($subjectId);
        $chapter = PracticeChapter::where('id', $chapterId)->where('subject_id', $subject->id)->firstOrFail();
        return view('cms.practice_quiz.chapters.edit', compact('subject', 'chapter'));
    }

    public function chapterUpdate(Request $request, $subjectId, $chapterId)
    {
        $subject = PracticeSubject::findOrFail($subjectId);
        $chapter = PracticeChapter::where('id', $chapterId)->where('subject_id', $subject->id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'exam_duration_minutes' => 'required|integer|min:1|max:180',
            'exam_pass_mark_percent' => 'nullable|integer|min:0|max:100',
            'target_repetitions' => 'required|integer|min:1|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        if ($chapter->title !== $request->title) {
            $slugBase = Str::slug($request->title);
            $slug = $slugBase ?: 'chapter';
            $counter = 1;
            while (PracticeChapter::where('slug', $slug)->where('id', '!=', $chapter->id)->exists()) {
                $slug = $slugBase . '-' . $counter++;
            }
            $chapter->slug = $slug;
        }

        $chapter->title = $request->title;
        $chapter->description = $request->description;
        $chapter->exam_duration_minutes = (int)$request->exam_duration_minutes ?: 15;
        $chapter->exam_pass_mark_percent = (int)($request->exam_pass_mark_percent ?? 0);
        $chapter->target_repetitions = (int)$request->target_repetitions ?: 3;
        $chapter->sort_order = $request->sort_order ?: 1;
        $chapter->is_active = $request->has('is_active') ? (bool)$request->is_active : true;
        $chapter->save();

        return redirect()->route('cms.practice_chapters', $subject->id)
            ->with('status', 'Chapter updated successfully.');
    }

    public function chapterDestroy($subjectId, $chapterId)
    {
        $chapter = PracticeChapter::where('id', $chapterId)->where('subject_id', $subjectId)->firstOrFail();
        $chapter->delete();
        return redirect()->route('cms.practice_chapters', $subjectId)->with('status', 'Chapter deleted successfully.');
    }

    public function chapterToggle($subjectId, $chapterId)
    {
        $chapter = PracticeChapter::where('id', $chapterId)->where('subject_id', $subjectId)->firstOrFail();
        $chapter->is_active = !$chapter->is_active;
        $chapter->save();
        return back()->with('status', 'Chapter status changed successfully.');
    }

    // =========================================================================
    // CHAPTER LEVELS MANAGEMENT
    // =========================================================================

    public function levelsIndex($chapterId)
    {
        $chapter = PracticeChapter::with('subject')->findOrFail($chapterId);
        $levels = PracticeQuiz::where('chapter_id', $chapter->id)
            ->withCount(['questions', 'sessions'])
            ->orderBy('sort_order', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        return view('cms.practice_quiz.levels.index', compact('chapter', 'levels'));
    }

    public function levelCreate($chapterId)
    {
        $chapter = PracticeChapter::with('subject')->findOrFail($chapterId);
        $nextSortOrder = PracticeQuiz::where('chapter_id', $chapter->id)->max('sort_order') + 1;
        return view('cms.practice_quiz.levels.create', compact('chapter', 'nextSortOrder'));
    }

    public function levelStore(Request $request, $chapterId)
    {
        $chapter = PracticeChapter::findOrFail($chapterId);

        $request->validate([
            'title' => 'required|string|max:255',
            'target_repetitions' => 'required|integer|min:1|max:20',
            'sort_order' => 'nullable|integer',
        ]);

        $slugBase = Str::slug($request->title);
        $slug = $slugBase ?: 'level';
        $counter = 1;
        while (PracticeQuiz::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        $level = PracticeQuiz::create([
            'chapter_id' => $chapter->id,
            'category_id' => optional($chapter->subject)->category_id,
            'title' => $request->title,
            'slug' => $slug,
            'sort_order' => $request->sort_order ?: 1,
            'description' => $request->description,
            'target_repetitions' => $request->target_repetitions ?: 3,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('cms.practice_quiz.questions', $level->id)
            ->with('status', 'Level created successfully! Now you can add questions.');
    }

    // =========================================================================
    // ALL PRACTICE QUIZZES (STANDALONE + LEVELS)
    // =========================================================================

    public function index()
    {
        $quizzes = PracticeQuiz::with(['category', 'chapter.subject'])
            ->withCount(['questions', 'sessions'])
            ->orderBy('id', 'DESC')
            ->get();

        return view('cms.practice_quiz.index', compact('quizzes'));
    }

    public function create()
    {
        $categories = categories::orderBy('name', 'ASC')->get();
        $subjects = PracticeSubject::with('activeChapters')->orderBy('title', 'ASC')->get();
        return view('cms.practice_quiz.create', compact('categories', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'target_repetitions' => 'required|integer|min:1|max:20',
            'category_id' => 'nullable|integer',
            'chapter_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
        ]);

        $slugBase = Str::slug($request->title);
        $slug = $slugBase ?: 'quiz';
        $counter = 1;
        while (PracticeQuiz::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        $quiz = PracticeQuiz::create([
            'category_id' => $request->category_id ?: null,
            'chapter_id' => $request->chapter_id ?: null,
            'title' => $request->title,
            'slug' => $slug,
            'sort_order' => $request->sort_order ?: 1,
            'description' => $request->description,
            'target_repetitions' => $request->target_repetitions ?: 3,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('cms.practice_quiz.questions', $quiz->id)
            ->with('status', 'Practice Quiz created successfully! Now you can add questions.');
    }

    public function edit($id)
    {
        $quiz = PracticeQuiz::with('chapter.subject')->findOrFail($id);
        $categories = categories::orderBy('name', 'ASC')->get();
        $subjects = PracticeSubject::with('activeChapters')->orderBy('title', 'ASC')->get();
        return view('cms.practice_quiz.edit', compact('quiz', 'categories', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $quiz = PracticeQuiz::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'target_repetitions' => 'required|integer|min:1|max:20',
            'category_id' => 'nullable|integer',
            'chapter_id' => 'nullable|integer',
            'sort_order' => 'nullable|integer',
        ]);

        if ($quiz->title !== $request->title) {
            $slugBase = Str::slug($request->title);
            $slug = $slugBase ?: 'quiz';
            $counter = 1;
            while (PracticeQuiz::where('slug', $slug)->where('id', '!=', $quiz->id)->exists()) {
                $slug = $slugBase . '-' . $counter++;
            }
            $quiz->slug = $slug;
        }

        $quiz->category_id = $request->category_id ?: null;
        $quiz->chapter_id = $request->chapter_id ?: null;
        $quiz->title = $request->title;
        $quiz->sort_order = $request->sort_order ?: 1;
        $quiz->description = $request->description;
        $quiz->target_repetitions = $request->target_repetitions ?: 3;
        $quiz->is_active = $request->has('is_active') ? (bool)$request->is_active : true;
        $quiz->save();

        return redirect()->route('cms.practice_quizzes')
            ->with('status', 'Practice Quiz updated successfully.');
    }

    public function destroy($id)
    {
        $quiz = PracticeQuiz::findOrFail($id);
        $quiz->delete();

        return redirect()->route('cms.practice_quizzes')
            ->with('status', 'Practice Quiz deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $quiz = PracticeQuiz::findOrFail($id);
        $quiz->is_active = !$quiz->is_active;
        $quiz->save();

        return back()->with('status', 'Status changed successfully.');
    }

    // =========================================================================
    // QUESTIONS BANK & CSV IMPORT
    // =========================================================================

    public function questionsIndex($id)
    {
        $quiz = PracticeQuiz::with(['category', 'chapter.subject'])->findOrFail($id);
        $questions = PracticeQuestion::where('practice_quiz_id', $id)->orderBy('id', 'ASC')->get();

        return view('cms.practice_quiz.questions', compact('quiz', 'questions'));
    }

    public function saveQuestions(Request $request, $id)
    {
        $quiz = PracticeQuiz::findOrFail($id);

        if (!empty($request->questions)) {
            $count = count($request->questions);

            for ($i = 0; $i < $count; $i++) {
                if (empty(trim($request->questions[$i]))) {
                    continue;
                }

                $qId = isset($request->q_id[$i]) ? $request->q_id[$i] : 0;
                $data = [
                    'practice_quiz_id' => $quiz->id,
                    'question' => $request->questions[$i],
                    'optn1' => $request->optn1[$i] ?? '',
                    'optn2' => $request->optn2[$i] ?? '',
                    'optn3' => $request->optn3[$i] ?? null,
                    'optn4' => $request->optn4[$i] ?? null,
                    'optn5' => $request->optn5[$i] ?? null,
                    'right_ans' => (int)($request->right_ans[$i] ?? 1),
                    'explanation' => $request->explanation[$i] ?? null,
                ];

                if ($qId > 0) {
                    PracticeQuestion::where('id', $qId)->where('practice_quiz_id', $quiz->id)->update($data);
                } else {
                    PracticeQuestion::create($data);
                }
            }

            // Sync total questions in active sessions
            $totalQ = PracticeQuestion::where('practice_quiz_id', $quiz->id)->count();
            PracticeSession::where('practice_quiz_id', $quiz->id)->update(['total_questions' => $totalQ]);

            return back()->with('status', 'Questions saved successfully!');
        }

        return back()->with('error', 'No questions provided.');
    }

    public function deleteQuestion($quizId, $questionId)
    {
        $question = PracticeQuestion::where('id', $questionId)
            ->where('practice_quiz_id', $quizId)
            ->first();

        if ($question) {
            $question->delete();

            $totalQ = PracticeQuestion::where('practice_quiz_id', $quizId)->count();
            PracticeSession::where('practice_quiz_id', $quizId)->update(['total_questions' => $totalQ]);

            return redirect()->route('cms.practice_quiz.questions', $quizId)->with('status', 'Question deleted successfully.');
        }

        return redirect()->route('cms.practice_quiz.questions', $quizId)->with('status', 'Question was already deleted.');
    }

    public function uploadCsv(Request $request, $id)
    {
        $quiz = PracticeQuiz::findOrFail($id);

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = fopen($request->file('csv_file')->getRealPath(), 'r');
        $inserted = 0;
        $rowNum = 0;

        while (($row = fgetcsv($file, 10000, ',')) !== false) {
            $rowNum++;
            if ($rowNum === 1 && (strtolower($row[0]) === 'question' || strtolower($row[0]) === 'quiz_id')) {
                continue;
            }

            $offset = 0;
            if (is_numeric($row[0]) && count($row) >= 8) {
                $offset = 1;
            }

            $questionText = trim($row[$offset] ?? '');
            if (empty($questionText)) {
                continue;
            }

            $optn1 = trim($row[$offset + 1] ?? '');
            $optn2 = trim($row[$offset + 2] ?? '');
            $optn3 = trim($row[$offset + 3] ?? '');
            $optn4 = trim($row[$offset + 4] ?? '');
            $optn5 = trim($row[$offset + 5] ?? '');
            $rightAns = (int)trim($row[$offset + 6] ?? '1');
            $explanation = trim($row[$offset + 7] ?? '');

            if (!empty($optn1) && !empty($optn2)) {
                PracticeQuestion::create([
                    'practice_quiz_id' => $quiz->id,
                    'question' => $questionText,
                    'optn1' => $optn1,
                    'optn2' => $optn2,
                    'optn3' => $optn3 ?: null,
                    'optn4' => $optn4 ?: null,
                    'optn5' => $optn5 ?: null,
                    'right_ans' => ($rightAns >= 1 && $rightAns <= 5) ? $rightAns : 1,
                    'explanation' => $explanation ?: null,
                ]);
                $inserted++;
            }
        }
        fclose($file);

        $totalQ = PracticeQuestion::where('practice_quiz_id', $quiz->id)->count();
        PracticeSession::where('practice_quiz_id', $quiz->id)->update(['total_questions' => $totalQ]);

        return back()->with('status', "Successfully imported {$inserted} questions from CSV!");
    }
}
