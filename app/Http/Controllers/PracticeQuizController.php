<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\PracticeQuiz;
use App\Models\PracticeQuestion;
use App\Models\PracticeSession;
use App\Models\PracticeSessionQuestion;
use App\Models\PracticeSubject;
use App\Models\PracticeChapter;
use App\Models\PracticeChapterExamAttempt;
use App\Models\PracticeChapterExamAnswer;
use App\Models\PracticeChapterProgress;
use App\Models\categories;
use App\Models\User;

class PracticeQuizController extends Controller
{
    /**
     * Helper to get current logged in student ID.
     */
    protected function getStudentId()
    {
        $id = Cookie::get('user_id');
        if (!empty($id)) {
            return (int)$id;
        }
        if (auth()->check()) {
            return (int)auth()->id();
        }
        return null;
    }

    /**
     * Public listing of practice subjects, chapters & quizzes.
     */
    public function index(Request $request)
    {
        $userId = $this->getStudentId();
        $catId = $request->get('cat');
        $search = $request->get('q');

        // 1. Fetch Subjects with active Chapters & Levels
        $subjectsQuery = PracticeSubject::where('is_active', true)
            ->with(['category', 'activeChapters' => function ($q) {
                $q->withCount(['levels', 'questions']);
            }])
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc');

        if ($catId) {
            $subjectsQuery->where('category_id', $catId);
        }

        if ($search) {
            $subjectsQuery->where('title', 'like', "%{$search}%");
        }

        $subjects = $subjectsQuery->get();

        // Attach student completion metrics per subject
        if ($userId) {
            $allChapterProgress = PracticeChapterProgress::where('user_id', $userId)->get()->keyBy('chapter_id');
            foreach ($subjects as $subject) {
                $totalChapters = $subject->activeChapters->count();
                $completedChapters = 0;
                foreach ($subject->activeChapters as $ch) {
                    $prog = $allChapterProgress->get($ch->id);
                    $ch->user_progress = $prog;
                    if ($prog && $prog->isCompleted()) {
                        $completedChapters++;
                    }
                }
                $subject->total_chapters = $totalChapters;
                $subject->completed_chapters = $completedChapters;
                $subject->progress_percent = $totalChapters > 0 ? round(($completedChapters / $totalChapters) * 100) : 0;
            }
        }

        // 2. Fetch standalone / legacy quizzes for backward compatibility
        $quizzesQuery = PracticeQuiz::where('is_active', true)
            ->with('category')
            ->withCount('questions');

        if ($catId) {
            $quizzesQuery->where('category_id', $catId);
        }

        if ($search) {
            $quizzesQuery->where('title', 'like', "%{$search}%");
        }

        $quizzes = $quizzesQuery->orderBy('id', 'DESC')->paginate(12);

        if ($userId) {
            $userSessions = PracticeSession::where('user_id', $userId)
                ->whereIn('practice_quiz_id', $quizzes->pluck('id'))
                ->orderBy('id', 'desc')
                ->get();

            foreach ($quizzes as $quiz) {
                $quizSessions = $userSessions->where('practice_quiz_id', $quiz->id);
                $quiz->active_session = $quizSessions->firstWhere('status', 'in_progress');
                $quiz->last_completed_session = $quizSessions->where('status', 'completed')->first();
                $quiz->completed_count = $quizSessions->where('status', 'completed')->count();
                $quiz->user_session = $quiz->active_session ?: $quiz->last_completed_session;
            }
        }

        $categories = categories::orderBy('name', 'ASC')->get();

        return view('user.pages.practice_quiz.index', compact('subjects', 'quizzes', 'categories', 'catId', 'search'));
    }

    /**
     * Subject overview showing its chapters and progression roadmap.
     */
    public function subjectShow($slug)
    {
        $subject = PracticeSubject::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'activeChapters' => function ($q) {
                $q->with(['activeLevels' => function ($lq) {
                    $lq->withCount('questions');
                }])->withCount('questions');
            }])
            ->firstOrFail();

        $userId = $this->getStudentId();
        $chapterProgressMap = collect();
        if ($userId) {
            $chapterProgressMap = PracticeChapterProgress::where('user_id', $userId)
                ->whereIn('chapter_id', $subject->activeChapters->pluck('id'))
                ->get()
                ->keyBy('chapter_id');

            foreach ($subject->activeChapters as $ch) {
                $prog = $chapterProgressMap->get($ch->id);
                $ch->user_progress = $prog;

                // Completed levels count for this chapter
                $completedLevelsCount = PracticeSession::where('user_id', $userId)
                    ->whereIn('practice_quiz_id', $ch->activeLevels->pluck('id'))
                    ->where('status', 'completed')
                    ->distinct('practice_quiz_id')
                    ->count('practice_quiz_id');

                $ch->completed_levels_count = $completedLevelsCount;
                $ch->total_levels_count = $ch->activeLevels->count();
            }
        }

        return view('user.pages.practice_quiz.subject', compact('subject', 'chapterProgressMap'));
    }

    /**
     * Chapter Roadmap page: Levels (1..N) -> Timed Chapter Exam -> Mistake Recovery Game -> Chapter Complete.
     */
    public function chapterShow($slug)
    {
        $chapter = PracticeChapter::where('slug', $slug)
            ->where('is_active', true)
            ->with(['subject.category', 'activeLevels' => function ($q) {
                $q->withCount('questions');
            }])
            ->firstOrFail();

        $userId = $this->getStudentId();
        $progress = null;
        $completedLevelIds = [];
        $levelSessions = collect();
        $latestExamAttempt = null;
        $mistakeSession = null;

        if ($userId) {
            $progress = PracticeChapterProgress::firstOrCreate(
                ['user_id' => $userId, 'chapter_id' => $chapter->id],
                ['current_stage' => 'levels', 'highest_completed_level_order' => 0]
            );

            // Fetch completed sessions for active levels
            $levelSessions = PracticeSession::where('user_id', $userId)
                ->whereIn('practice_quiz_id', $chapter->activeLevels->pluck('id'))
                ->orderBy('id', 'desc')
                ->get();

            $completedLevelIds = $levelSessions->where('status', 'completed')
                ->pluck('practice_quiz_id')
                ->unique()
                ->toArray();

            // Fetch latest exam attempt
            $latestExamAttempt = PracticeChapterExamAttempt::where('user_id', $userId)
                ->where('chapter_id', $chapter->id)
                ->latest()
                ->first();

            // Fetch mistake session if exists
            $mistakeSession = PracticeSession::where('user_id', $userId)
                ->where('chapter_id', $chapter->id)
                ->where('session_type', 'mistake_game')
                ->latest()
                ->first();
        }

        // Determine unlocked status for each level sequentially
        $allPreviousCompleted = true;
        foreach ($chapter->activeLevels as $index => $level) {
            $level->is_completed = in_array($level->id, $completedLevelIds);
            $level->is_unlocked = $allPreviousCompleted;
            $level->active_session = $levelSessions->where('practice_quiz_id', $level->id)->firstWhere('status', 'in_progress');
            $level->last_session = $levelSessions->where('practice_quiz_id', $level->id)->first();

            if (!$level->is_completed) {
                $allPreviousCompleted = false;
            }
        }

        $allLevelsCompleted = $chapter->activeLevels->isNotEmpty() && (count($completedLevelIds) >= $chapter->activeLevels->count());
        $examUnlocked = $allLevelsCompleted;

        return view('user.pages.practice_quiz.chapter', compact(
            'chapter',
            'progress',
            'allLevelsCompleted',
            'examUnlocked',
            'latestExamAttempt',
            'mistakeSession'
        ));
    }

    /**
     * Overview / Start page for a practice quiz (standalone or level).
     */
    public function show($slug)
    {
        $quiz = PracticeQuiz::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'chapter.subject'])
            ->withCount('questions')
            ->firstOrFail();

        $userId = $this->getStudentId();
        $userSession = null;
        $pastCompletedSessions = collect();

        if ($userId) {
            $userSession = PracticeSession::where('user_id', $userId)
                ->where('practice_quiz_id', $quiz->id)
                ->orderByRaw("CASE WHEN status = 'in_progress' THEN 1 ELSE 2 END")
                ->latest()
                ->first();

            $pastCompletedSessions = PracticeSession::where('user_id', $userId)
                ->where('practice_quiz_id', $quiz->id)
                ->where('status', 'completed')
                ->latest('completed_at')
                ->get();
        }

        return view('user.pages.practice_quiz.show', compact('quiz', 'userSession', 'pastCompletedSessions'));
    }

    /**
     * Start or resume a practice session (Level or standalone quiz).
     */
    public function start(Request $request, $id)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return redirect()->route('user.login_or_signup')->with('notAllow', 'Please login to start your preparation quiz.');
        }

        $quiz = PracticeQuiz::where('id', $id)
            ->where('is_active', true)
            ->withCount('questions')
            ->firstOrFail();

        if ($quiz->questions_count === 0) {
            return back()->with('error', 'This quiz does not have any questions yet. Please try another one.');
        }

        // Sequential level locking check if this quiz belongs to a chapter
        if ($quiz->chapter_id) {
            $chapter = PracticeChapter::with('activeLevels')->find($quiz->chapter_id);
            if ($chapter) {
                $precedingLevels = $chapter->activeLevels->filter(function ($lvl) use ($quiz) {
                    return $lvl->sort_order < $quiz->sort_order;
                });

                if ($precedingLevels->isNotEmpty()) {
                    $completedPrecedingCount = PracticeSession::where('user_id', $userId)
                        ->whereIn('practice_quiz_id', $precedingLevels->pluck('id'))
                        ->where('status', 'completed')
                        ->distinct('practice_quiz_id')
                        ->count('practice_quiz_id');

                    if ($completedPrecedingCount < $precedingLevels->count()) {
                        return redirect()->route('practice_quiz.chapter', $chapter->slug)
                            ->with('error', 'এই লেভেলটি লক করা আছে। অনুগ্রহ করে আগের লেভেলগুলো শেষ করুন।');
                    }
                }
            }
        }

        $restartRequested = $request->has('restart');

        // Look for an existing in-progress session if not explicitly restarting
        $session = null;
        if (!$restartRequested) {
            $session = PracticeSession::where('user_id', $userId)
                ->where('practice_quiz_id', $quiz->id)
                ->where('status', 'in_progress')
                ->latest()
                ->first();
        }

        // Create a brand NEW session if none or restart requested
        if (!$session) {
            $session = PracticeSession::create([
                'user_id' => $userId,
                'practice_quiz_id' => $quiz->id,
                'chapter_id' => $quiz->chapter_id ?: null,
                'session_type' => 'level',
                'total_questions' => $quiz->questions_count,
                'mastered_questions' => 0,
                'total_attempts' => 0,
                'correct_attempts' => 0,
                'status' => 'in_progress',
            ]);

            // Seed question rows for this session
            $allQuizQIds = PracticeQuestion::where('practice_quiz_id', $quiz->id)->pluck('id')->toArray();
            $insertBatch = [];
            $now = Carbon::now();
            foreach ($allQuizQIds as $qId) {
                $insertBatch[] = [
                    'session_id' => $session->id,
                    'question_id' => $qId,
                    'correct_count' => 0,
                    'incorrect_count' => 0,
                    'is_mastered' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if (!empty($insertBatch)) {
                PracticeSessionQuestion::insert($insertBatch);
            }
        } else {
            // Sync question count if quiz was updated
            if ($session->total_questions !== $quiz->questions_count) {
                $session->total_questions = $quiz->questions_count;
                $session->save();
            }

            // Sync any missing questions
            $existingQIds = PracticeSessionQuestion::where('session_id', $session->id)->pluck('question_id')->toArray();
            $allQuizQIds = PracticeQuestion::where('practice_quiz_id', $quiz->id)->pluck('id')->toArray();
            $missingQIds = array_diff($allQuizQIds, $existingQIds);
            if (!empty($missingQIds)) {
                $insertBatch = [];
                $now = Carbon::now();
                foreach ($missingQIds as $qId) {
                    $insertBatch[] = [
                        'session_id' => $session->id,
                        'question_id' => $qId,
                        'correct_count' => 0,
                        'incorrect_count' => 0,
                        'is_mastered' => false,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                PracticeSessionQuestion::insert($insertBatch);
            }
        }

        return redirect()->route('practice_quiz.play', $session->id);
    }

    /**
     * The gamified play screen (Levels, standalone quizzes, or Mistake Games).
     */
    public function play($sessionId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return redirect()->route('user.login_or_signup')->with('notAllow', 'Please login to continue.');
        }

        $session = PracticeSession::where('id', $sessionId)
            ->with(['quiz.category', 'chapter.subject'])
            ->firstOrFail();

        // If this session belongs to another user
        if ($session->user_id !== $userId) {
            if ($session->practice_quiz_id) {
                $userSession = PracticeSession::where('practice_quiz_id', $session->practice_quiz_id)
                    ->where('user_id', $userId)
                    ->first();
                if ($userSession) {
                    return redirect()->route('practice_quiz.play', $userSession->id);
                }
                return redirect()->route('practice_quiz.start', $session->practice_quiz_id);
            }
            return redirect()->route('practice_quiz.index');
        }

        return view('user.pages.practice_quiz.play', compact('session'));
    }

    /**
     * API: Get the next unmastered question for the session.
     */
    public function apiGetNextQuestion(Request $request, $sessionId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $session = PracticeSession::where('id', $sessionId)
            ->with(['quiz', 'chapter'])
            ->firstOrFail();

        if ($session->user_id !== $userId) {
            if ($session->practice_quiz_id) {
                $session = PracticeSession::where('practice_quiz_id', $session->practice_quiz_id)
                    ->where('user_id', $userId)
                    ->with(['quiz', 'chapter'])
                    ->firstOrFail();
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        // Check unmastered questions
        $unmasteredQuestions = PracticeSessionQuestion::where('session_id', $session->id)
            ->where('is_mastered', false)
            ->get();

        if ($unmasteredQuestions->isEmpty()) {
            // All questions mastered! Mark session complete
            if ($session->status !== 'completed') {
                $session->status = 'completed';
                $session->completed_at = Carbon::now();
                $session->mastered_questions = $session->total_questions;
                $session->save();
            }

            // Sync Chapter Progress if chapter exists
            $chapterContext = $this->syncChapterProgressOnSessionComplete($session, $userId);

            $mistakesCount = PracticeSessionQuestion::where('session_id', $session->id)
                ->where('incorrect_count', '>', 0)
                ->count();

            return response()->json([
                'session_completed' => true,
                'stats' => [
                    'total_questions' => $session->total_questions,
                    'mastered_questions' => $session->mastered_questions,
                    'total_attempts' => $session->total_attempts,
                    'correct_attempts' => $session->correct_attempts,
                    'mistakes_count' => $mistakesCount,
                    'accuracy' => $session->total_attempts > 0 ? round(($session->correct_attempts / $session->total_attempts) * 100) : 100,
                ],
                'chapter_context' => $chapterContext,
            ]);
        }

        // Exclude previous question if there is more than 1 question left
        $excludeQId = (int)$request->query('exclude_id', 0);
        $candidates = $unmasteredQuestions;
        if ($candidates->count() > 1 && $excludeQId > 0) {
            $filtered = $candidates->filter(function ($item) use ($excludeQId) {
                return $item->question_id !== $excludeQId;
            });
            if ($filtered->isNotEmpty()) {
                $candidates = $filtered;
            }
        }

        // Randomly pick one from candidates
        $selectedSessionQ = $candidates->random();
        $question = PracticeQuestion::findOrFail($selectedSessionQ->question_id);
        $targetReps = $session->effective_target_repetitions;

        return response()->json([
            'session_completed' => false,
            'question' => [
                'id' => $question->id,
                'question' => $question->question,
                'image' => !empty($question->image) ? (str_starts_with($question->image, 'http') ? $question->image : asset($question->image)) : null,
                'optn1' => $question->optn1,
                'optn2' => $question->optn2,
                'optn3' => $question->optn3,
                'optn4' => $question->optn4,
                'optn5' => $question->optn5,
                'correct_count' => $selectedSessionQ->correct_count,
                'target_repetitions' => $targetReps,
            ],
            'session' => [
                'mastered_questions' => $session->mastered_questions,
                'total_questions' => $session->total_questions,
                'progress_percent' => $session->progress_percent,
                'target_repetitions' => $targetReps,
            ],
        ]);
    }

    /**
     * API: Submit answer for current question and evaluate.
     */
    public function apiSubmitAnswer(Request $request, $sessionId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'question_id' => 'required|integer',
            'selected_option' => 'required|integer|min:1|max:5',
        ]);

        $session = PracticeSession::where('id', $sessionId)
            ->with(['quiz', 'chapter'])
            ->firstOrFail();

        if ($session->user_id !== $userId) {
            if ($session->practice_quiz_id) {
                $session = PracticeSession::where('practice_quiz_id', $session->practice_quiz_id)
                    ->where('user_id', $userId)
                    ->with(['quiz', 'chapter'])
                    ->firstOrFail();
            } else {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        // Securely find session question record
        $sessionQ = PracticeSessionQuestion::where('session_id', $session->id)
            ->where('question_id', $request->question_id)
            ->firstOrFail();

        $question = PracticeQuestion::findOrFail($sessionQ->question_id);
        $targetReps = $session->effective_target_repetitions;
        $isCorrect = ((int)$question->right_ans === (int)$request->selected_option);

        // Update question stats
        if ($isCorrect) {
            $sessionQ->correct_count += 1;
            if ($sessionQ->correct_count >= $targetReps) {
                $sessionQ->is_mastered = true;
            }
        } else {
            $sessionQ->incorrect_count += 1;
            // Reset consecutive correct streak back to 0 so the student must answer correctly in a row
            $sessionQ->correct_count = 0;
        }

        $sessionQ->last_answered_at = Carbon::now();
        $sessionQ->save();

        // Update session stats
        $session->total_attempts += 1;
        if ($isCorrect) {
            $session->correct_attempts += 1;
        }

        $masteredCount = PracticeSessionQuestion::where('session_id', $session->id)
            ->where('is_mastered', true)
            ->count();

        $session->mastered_questions = $masteredCount;

        $isCompleted = false;
        $chapterContext = null;

        if ($masteredCount >= $session->total_questions && $session->total_questions > 0) {
            $session->status = 'completed';
            $session->completed_at = Carbon::now();
            $isCompleted = true;
            $chapterContext = $this->syncChapterProgressOnSessionComplete($session, $userId);
        }
        $session->save();

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'selected_option' => (int)$request->selected_option,
            'right_ans' => (int)$question->right_ans,
            'explanation' => $question->explanation ?: null,
            'correct_count' => $sessionQ->correct_count,
            'target_repetitions' => $targetReps,
            'question_mastered' => (bool)$sessionQ->is_mastered,
            'mastered_questions' => $session->mastered_questions,
            'total_questions' => $session->total_questions,
            'progress_percent' => $session->progress_percent,
            'session_completed' => $isCompleted,
            'chapter_context' => $chapterContext,
        ]);
    }

    /**
     * Helper to synchronize chapter progress when a session (level or mistake game) completes.
     */
    protected function syncChapterProgressOnSessionComplete(PracticeSession $session, $userId)
    {
        if (!$session->chapter_id) {
            return null;
        }

        $chapter = PracticeChapter::with(['activeLevels', 'subject'])->find($session->chapter_id);
        if (!$chapter) {
            return null;
        }

        $progress = PracticeChapterProgress::firstOrCreate(
            ['user_id' => $userId, 'chapter_id' => $chapter->id],
            ['current_stage' => 'levels', 'highest_completed_level_order' => 0]
        );

        if ($session->session_type === 'mistake_game') {
            $progress->current_stage = 'completed';
            $progress->completed_at = Carbon::now();
            $progress->save();

            return [
                'is_chapter_mistake_game' => true,
                'chapter_completed' => true,
                'chapter_slug' => $chapter->slug,
                'chapter_title' => $chapter->title,
                'subject_slug' => optional($chapter->subject)->slug,
                'roadmap_url' => route('practice_quiz.chapter', $chapter->slug),
            ];
        }

        // Level session completion
        $levelQuiz = $session->quiz;
        if ($levelQuiz && $levelQuiz->sort_order > $progress->highest_completed_level_order) {
            $progress->highest_completed_level_order = $levelQuiz->sort_order;
        }

        $activeLevels = $chapter->activeLevels;
        $completedLevelIds = PracticeSession::where('user_id', $userId)
            ->whereIn('practice_quiz_id', $activeLevels->pluck('id'))
            ->where('status', 'completed')
            ->pluck('practice_quiz_id')
            ->unique()
            ->toArray();

        $allLevelsCompleted = $activeLevels->isNotEmpty() && $activeLevels->every(function ($lvl) use ($completedLevelIds) {
            return in_array($lvl->id, $completedLevelIds);
        });

        // Find next level if any
        $nextLevel = $activeLevels->where('sort_order', '>', optional($levelQuiz)->sort_order)->first();

        if ($allLevelsCompleted) {
            if ($progress->current_stage === 'levels') {
                $progress->current_stage = 'exam';
            }
        }
        $progress->save();

        return [
            'is_level' => true,
            'chapter_slug' => $chapter->slug,
            'chapter_title' => $chapter->title,
            'all_levels_completed' => $allLevelsCompleted,
            'next_level' => $nextLevel ? [
                'id' => $nextLevel->id,
                'title' => $nextLevel->title,
                'sort_order' => $nextLevel->sort_order,
                'url' => route('practice_quiz.start', $nextLevel->id)
            ] : null,
            'exam_url' => route('practice_quiz.exam.start', $chapter->id),
            'roadmap_url' => route('practice_quiz.chapter', $chapter->slug),
        ];
    }

    /**
     * Start or resume a Timed Chapter Exam.
     */
    public function startChapterExam($chapterId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return redirect()->route('user.login_or_signup')->with('notAllow', 'Please login to take the chapter exam.');
        }

        $chapter = PracticeChapter::where('id', $chapterId)
            ->where('is_active', true)
            ->with(['activeLevels.questions'])
            ->firstOrFail();

        // Ensure all active levels are completed
        $activeLevels = $chapter->activeLevels;
        $completedLevelIds = PracticeSession::where('user_id', $userId)
            ->whereIn('practice_quiz_id', $activeLevels->pluck('id'))
            ->where('status', 'completed')
            ->pluck('practice_quiz_id')
            ->unique()
            ->toArray();

        $allCompleted = $activeLevels->isNotEmpty() && (count($completedLevelIds) >= $activeLevels->count());
        if (!$allCompleted) {
            return redirect()->route('practice_quiz.chapter', $chapter->slug)
                ->with('error', 'অধ্যায়ের সব লেভেল শেষ করার পর পরীক্ষা দেওয়া যাবে।');
        }

        // Check for active in-progress attempt
        $existingAttempt = PracticeChapterExamAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapter->id)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        if ($existingAttempt) {
            if ($existingAttempt->remainingSeconds() > 0) {
                return redirect()->route('practice_quiz.exam.take', $existingAttempt->id);
            } else {
                // Auto-submit expired attempt
                $this->finalizeExamAttempt($existingAttempt);
            }
        }

        // Gather all questions from all active levels in this chapter
        $allQuestions = collect();
        foreach ($activeLevels as $lvl) {
            $allQuestions = $allQuestions->concat($lvl->questions);
        }

        if ($allQuestions->isEmpty()) {
            return redirect()->route('practice_quiz.chapter', $chapter->slug)
                ->with('error', 'এই অধ্যায়ে এখনও কোনো প্রশ্ন যোগ করা হয়নি।');
        }

        // Shuffle questions
        $shuffled = $allQuestions->shuffle();
        $durationSeconds = max(60, ((int)$chapter->exam_duration_minutes) * 60);

        $attempt = PracticeChapterExamAttempt::create([
            'user_id' => $userId,
            'chapter_id' => $chapter->id,
            'total_questions' => $shuffled->count(),
            'correct_count' => 0,
            'wrong_count' => 0,
            'unanswered_count' => $shuffled->count(),
            'score_percent' => 0,
            'passed' => false,
            'duration_seconds' => $durationSeconds,
            'started_at' => Carbon::now(),
            'status' => 'in_progress',
        ]);

        $answerBatch = [];
        $now = Carbon::now();
        foreach ($shuffled->values() as $idx => $q) {
            $answerBatch[] = [
                'exam_attempt_id' => $attempt->id,
                'question_id' => $q->id,
                'selected_option' => null,
                'is_correct' => false,
                'question_order' => $idx + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        PracticeChapterExamAnswer::insert($answerBatch);

        // Update chapter progress
        PracticeChapterProgress::updateOrCreate(
            ['user_id' => $userId, 'chapter_id' => $chapter->id],
            ['current_stage' => 'exam', 'latest_exam_attempt_id' => $attempt->id]
        );

        return redirect()->route('practice_quiz.exam.take', $attempt->id);
    }

    /**
     * Timed Chapter Exam interface.
     */
    public function takeChapterExam($attemptId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return redirect()->route('user.login_or_signup');
        }

        $attempt = PracticeChapterExamAttempt::where('id', $attemptId)
            ->where('user_id', $userId)
            ->with(['chapter.subject', 'answers.question'])
            ->firstOrFail();

        if ($attempt->status === 'submitted') {
            return redirect()->route('practice_quiz.exam.result', $attempt->id);
        }

        $remainingSeconds = $attempt->remainingSeconds();
        if ($remainingSeconds <= 0) {
            $this->finalizeExamAttempt($attempt);
            return redirect()->route('practice_quiz.exam.result', $attempt->id);
        }

        return view('user.pages.practice_quiz.exam', compact('attempt', 'remainingSeconds'));
    }

    /**
     * AJAX autosave for single question answer during Timed Chapter Exam.
     */
    public function autosaveChapterExamAnswer(Request $request, $attemptId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'question_id' => 'required|integer',
            'selected_option' => 'nullable|integer|min:1|max:5',
        ]);

        $attempt = PracticeChapterExamAttempt::where('id', $attemptId)
            ->where('user_id', $userId)
            ->firstOrFail();

        if ($attempt->status !== 'in_progress') {
            return response()->json(['error' => 'Exam already submitted'], 400);
        }

        $answer = PracticeChapterExamAnswer::where('exam_attempt_id', $attempt->id)
            ->where('question_id', $request->question_id)
            ->first();

        if ($answer) {
            $answer->selected_option = $request->selected_option ? (int)$request->selected_option : null;
            $answer->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Submit and grade the Timed Chapter Exam.
     */
    public function submitChapterExam(Request $request, $attemptId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return redirect()->route('user.login_or_signup');
        }

        $attempt = PracticeChapterExamAttempt::where('id', $attemptId)
            ->where('user_id', $userId)
            ->with(['chapter', 'answers.question'])
            ->firstOrFail();

        if ($attempt->status === 'in_progress') {
            // If submitted with answers form payload, update answers
            if ($request->has('answers') && is_array($request->answers)) {
                foreach ($request->answers as $qId => $selectedOpt) {
                    $ans = $attempt->answers->firstWhere('question_id', (int)$qId);
                    if ($ans && !empty($selectedOpt)) {
                        $ans->selected_option = (int)$selectedOpt;
                        $ans->save();
                    }
                }
            }

            $this->finalizeExamAttempt($attempt);
        }

        return redirect()->route('practice_quiz.exam.result', $attempt->id);
    }

    /**
     * Internal helper to grade, compute score, and finalize an exam attempt.
     */
    protected function finalizeExamAttempt(PracticeChapterExamAttempt $attempt)
    {
        $attempt->load(['chapter', 'answers.question']);

        $correct = 0;
        $wrong = 0;
        $unanswered = 0;

        foreach ($attempt->answers as $ans) {
            $q = $ans->question;
            if (empty($ans->selected_option)) {
                $ans->is_correct = false;
                $unanswered++;
            } else {
                if ((int)$ans->selected_option === (int)$q->right_ans) {
                    $ans->is_correct = true;
                    $correct++;
                } else {
                    $ans->is_correct = false;
                    $wrong++;
                }
            }
            $ans->save();
        }

        $total = $attempt->total_questions > 0 ? $attempt->total_questions : $attempt->answers->count();
        $scorePercent = $total > 0 ? round(($correct / $total) * 100) : 0;
        $passMark = (int)$attempt->chapter->exam_pass_mark_percent;
        $passed = ($passMark <= 0 || $scorePercent >= $passMark);

        $attempt->correct_count = $correct;
        $attempt->wrong_count = $wrong;
        $attempt->unanswered_count = $unanswered;
        $attempt->score_percent = $scorePercent;
        $attempt->passed = $passed;
        $attempt->status = 'submitted';
        $attempt->submitted_at = Carbon::now();
        $attempt->save();

        // Update Chapter Progress
        $progress = PracticeChapterProgress::firstOrCreate(
            ['user_id' => $attempt->user_id, 'chapter_id' => $attempt->chapter_id],
            ['current_stage' => 'exam']
        );

        $progress->latest_exam_attempt_id = $attempt->id;

        // If 0 mistakes (100% correct), chapter is complete immediately!
        if (($wrong + $unanswered) === 0) {
            $progress->current_stage = 'completed';
            $progress->completed_at = Carbon::now();
        } else {
            // Requires Mistake Recovery Game
            $progress->current_stage = 'mistake_game';
        }
        $progress->save();

        return $attempt;
    }

    /**
     * Exam result & review screen.
     */
    public function chapterExamResult($attemptId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return redirect()->route('user.login_or_signup');
        }

        $attempt = PracticeChapterExamAttempt::where('id', $attemptId)
            ->where('user_id', $userId)
            ->with(['chapter.subject', 'answers.question'])
            ->firstOrFail();

        $mistakeCount = $attempt->wrong_count + $attempt->unanswered_count;

        return view('user.pages.practice_quiz.exam_result', compact('attempt', 'mistakeCount'));
    }

    /**
     * Start Chapter Mistake Game (Requirement: এরপর ভুল করলে শুধু ভুলের উপর গেইম , এর মাধ্যমেই অধ্যায় শেষ).
     */
    public function startChapterMistakeGame($chapterId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return redirect()->route('user.login_or_signup')->with('notAllow', 'Please login to continue.');
        }

        $chapter = PracticeChapter::where('id', $chapterId)->firstOrFail();

        // Find latest submitted exam attempt
        $latestAttempt = PracticeChapterExamAttempt::where('user_id', $userId)
            ->where('chapter_id', $chapter->id)
            ->where('status', 'submitted')
            ->latest()
            ->first();

        if (!$latestAttempt) {
            return redirect()->route('practice_quiz.chapter', $chapter->slug)
                ->with('error', 'প্রথমে অধ্যায়ের পরীক্ষাটি দিন।');
        }

        $mistakeQIds = $latestAttempt->mistakeQuestionIds();

        // If 0 mistakes made on exam, chapter is already completed
        if (empty($mistakeQIds)) {
            PracticeChapterProgress::updateOrCreate(
                ['user_id' => $userId, 'chapter_id' => $chapter->id],
                ['current_stage' => 'completed', 'completed_at' => Carbon::now()]
            );

            return redirect()->route('practice_quiz.chapter', $chapter->slug)
                ->with('status', 'অভিনন্দন! পরীক্ষায় কোনো ভুল ছিল না, অধ্যায়টি সফলভাবে সম্পন্ন হয়েছে!');
        }

        // Check if an in-progress mistake game session already exists for this exam attempt
        $session = PracticeSession::where('user_id', $userId)
            ->where('chapter_id', $chapter->id)
            ->where('session_type', 'mistake_game')
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        if (!$session) {
            // First level quiz ID as fallback if needed by foreign key
            $firstLevel = $chapter->activeLevels()->first();

            $session = PracticeSession::create([
                'user_id' => $userId,
                'practice_quiz_id' => optional($firstLevel)->id ?: null,
                'chapter_id' => $chapter->id,
                'session_type' => 'mistake_game',
                'chapter_exam_attempt_id' => $latestAttempt->id,
                'total_questions' => count($mistakeQIds),
                'mastered_questions' => 0,
                'total_attempts' => 0,
                'correct_attempts' => 0,
                'status' => 'in_progress',
            ]);

            $insertBatch = [];
            $now = Carbon::now();
            foreach ($mistakeQIds as $qId) {
                $insertBatch[] = [
                    'session_id' => $session->id,
                    'question_id' => $qId,
                    'correct_count' => 0,
                    'incorrect_count' => 0,
                    'is_mastered' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            PracticeSessionQuestion::insert($insertBatch);

            // Update chapter progress
            PracticeChapterProgress::updateOrCreate(
                ['user_id' => $userId, 'chapter_id' => $chapter->id],
                ['current_stage' => 'mistake_game', 'mistake_session_id' => $session->id]
            );
        }

        return redirect()->route('practice_quiz.play', $session->id);
    }

    /**
     * Restart / Reset a practice quiz session by starting a new attempt.
     */
    public function restartSession(Request $request, $sessionId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('user.login_or_signup');
        }

        $session = PracticeSession::where('id', $sessionId)->firstOrFail();
        $quiz = $session->quiz ?: PracticeQuiz::find($session->practice_quiz_id);

        if (!$quiz) {
            return redirect()->route('practice_quiz.index');
        }

        // CREATE A NEW PRACTICE SESSION to preserve previous session history!
        $newSession = PracticeSession::create([
            'user_id' => $userId,
            'practice_quiz_id' => $quiz->id,
            'chapter_id' => $session->chapter_id ?: $quiz->chapter_id,
            'session_type' => $session->session_type ?: 'level',
            'total_questions' => $quiz->questions()->count(),
            'mastered_questions' => 0,
            'total_attempts' => 0,
            'correct_attempts' => 0,
            'status' => 'in_progress',
        ]);

        // Seed new session question rows
        $allQuizQIds = PracticeQuestion::where('practice_quiz_id', $quiz->id)->pluck('id')->toArray();
        $insertBatch = [];
        $now = Carbon::now();
        foreach ($allQuizQIds as $qId) {
            $insertBatch[] = [
                'session_id' => $newSession->id,
                'question_id' => $qId,
                'correct_count' => 0,
                'incorrect_count' => 0,
                'is_mastered' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        if (!empty($insertBatch)) {
            PracticeSessionQuestion::insert($insertBatch);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'New practice session started! Previous history preserved.',
                'session_id' => $newSession->id,
            ]);
        }

        return redirect()->route('practice_quiz.play', $newSession->id)
            ->with('status', 'New practice session started! Previous history preserved.');
    }

    /**
     * Restart game targeting only the questions that were answered incorrectly in this session.
     */
    public function restartMistakesSession(Request $request, $sessionId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            return redirect()->route('user.login_or_signup');
        }

        $session = PracticeSession::where('id', $sessionId)->firstOrFail();
        $quiz = $session->quiz ?: PracticeQuiz::find($session->practice_quiz_id);

        // Find question IDs where incorrect_count > 0 in the previous session
        $mistakeQIds = PracticeSessionQuestion::where('session_id', $session->id)
            ->where('incorrect_count', '>', 0)
            ->pluck('question_id')
            ->toArray();

        // If no mistakes were made, fall back to all session questions
        if (empty($mistakeQIds)) {
            $mistakeQIds = PracticeSessionQuestion::where('session_id', $session->id)->pluck('question_id')->toArray();
        }

        $newSession = PracticeSession::create([
            'user_id' => $userId,
            'practice_quiz_id' => optional($quiz)->id ?: $session->practice_quiz_id,
            'chapter_id' => $session->chapter_id,
            'session_type' => $session->session_type ?: 'level',
            'chapter_exam_attempt_id' => $session->chapter_exam_attempt_id,
            'total_questions' => count($mistakeQIds),
            'mastered_questions' => 0,
            'total_attempts' => 0,
            'correct_attempts' => 0,
            'status' => 'in_progress',
        ]);

        $insertBatch = [];
        $now = Carbon::now();
        foreach ($mistakeQIds as $qId) {
            $insertBatch[] = [
                'session_id' => $newSession->id,
                'question_id' => $qId,
                'correct_count' => 0,
                'incorrect_count' => 0,
                'is_mastered' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        if (!empty($insertBatch)) {
            PracticeSessionQuestion::insert($insertBatch);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mistake review game started!',
                'session_id' => $newSession->id,
                'questions_count' => count($mistakeQIds),
            ]);
        }

        return redirect()->route('practice_quiz.play', $newSession->id)
            ->with('status', 'Mistake review game started!');
    }

    /**
     * Review all questions and explanations.
     */
    public function review($sessionId)
    {
        $userId = $this->getStudentId();
        if (!$userId) {
            return redirect()->route('user.login_or_signup');
        }

        $session = PracticeSession::where('id', $sessionId)
            ->where('user_id', $userId)
            ->with(['quiz.category', 'chapter.subject', 'sessionQuestions.question'])
            ->firstOrFail();

        return view('user.pages.practice_quiz.review', compact('session'));
    }
}
