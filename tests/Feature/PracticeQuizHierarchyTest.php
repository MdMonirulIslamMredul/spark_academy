<?php

namespace Tests\Feature;

use App\Models\PracticeSubject;
use App\Models\PracticeChapter;
use App\Models\PracticeQuiz;
use App\Models\PracticeQuestion;
use App\Models\PracticeSession;
use App\Models\PracticeSessionQuestion;
use App\Models\PracticeChapterExamAttempt;
use App\Models\PracticeChapterExamAnswer;
use App\Models\PracticeChapterProgress;
use App\Models\User;
use App\Models\categories;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PracticeQuizHierarchyTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);
        DB::purge('sqlite');
        DB::reconnect('sqlite');

        $this->createBaseSchema();
    }

    protected function createBaseSchema(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->longText('image')->nullable();
            $table->integer('active')->default(1);
            $table->timestamps();
        });

        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('practice_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('practice_subjects')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->integer('exam_duration_minutes')->default(15);
            $table->integer('exam_pass_mark_percent')->default(0);
            $table->integer('target_repetitions')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('practice_quizzes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('sort_order')->default(1);
            $table->text('description')->nullable();
            $table->integer('target_repetitions')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('practice_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practice_quiz_id')->constrained('practice_quizzes')->onDelete('cascade');
            $table->text('question');
            $table->string('image')->nullable();
            $table->text('optn1');
            $table->text('optn2');
            $table->text('optn3')->nullable();
            $table->text('optn4')->nullable();
            $table->text('optn5')->nullable();
            $table->tinyInteger('right_ans');
            $table->text('explanation')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('practice_quiz_id')->nullable();
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->string('session_type', 50)->default('level');
            $table->unsignedBigInteger('chapter_exam_attempt_id')->nullable();
            $table->integer('total_questions')->default(0);
            $table->integer('mastered_questions')->default(0);
            $table->integer('total_attempts')->default(0);
            $table->integer('correct_attempts')->default(0);
            $table->string('status')->default('in_progress');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_session_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('practice_sessions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('practice_questions')->onDelete('cascade');
            $table->integer('correct_count')->default(0);
            $table->integer('incorrect_count')->default(0);
            $table->boolean('is_mastered')->default(false);
            $table->timestamp('last_answered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('practice_chapter_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained('practice_chapters')->onDelete('cascade');
            $table->integer('total_questions')->default(0);
            $table->integer('correct_count')->default(0);
            $table->integer('wrong_count')->default(0);
            $table->integer('unanswered_count')->default(0);
            $table->integer('score_percent')->default(0);
            $table->boolean('passed')->default(false);
            $table->integer('duration_seconds')->default(900);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->string('status', 50)->default('in_progress');
            $table->timestamps();
        });

        Schema::create('practice_chapter_exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_attempt_id')->constrained('practice_chapter_exam_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('practice_questions')->onDelete('cascade');
            $table->tinyInteger('selected_option')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('question_order')->default(0);
            $table->timestamps();
        });

        Schema::create('practice_chapter_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained('practice_chapters')->onDelete('cascade');
            $table->string('current_stage', 50)->default('levels');
            $table->integer('highest_completed_level_order')->default(0);
            $table->unsignedBigInteger('latest_exam_attempt_id')->nullable();
            $table->unsignedBigInteger('mistake_session_id')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'chapter_id']);
        });
    }

    protected function actingAsUser(User $user)
    {
        return $this->withCredentials()->withCookies([
            'user_id' => (string)$user->id,
            'user_name' => $user->name,
        ]);
    }

    public function test_cms_can_create_subject_chapter_and_levels()
    {
        // 1. Create Subject as logged-in admin
        $resSub = $this->withSession(['admin_id' => 1])->post(route('cms.practice_subject.store'), [
            'title' => 'Physics 1st Paper',
            'sort_order' => 1,
        ]);
        $resSub->assertRedirect(route('cms.practice_subjects'));
        $this->assertDatabaseHas('practice_subjects', ['title' => 'Physics 1st Paper']);

        $subject = PracticeSubject::first();

        // 2. Create Chapter
        $resChap = $this->withSession(['admin_id' => 1])->post(route('cms.practice_chapter.store', $subject->id), [
            'title' => 'Chapter 1: Vectors',
            'sort_order' => 1,
            'exam_duration_minutes' => 15,
            'target_repetitions' => 3,
        ]);
        $resChap->assertRedirect(route('cms.practice_chapters', $subject->id));
        $this->assertDatabaseHas('practice_chapters', [
            'title' => 'Chapter 1: Vectors',
            'subject_id' => $subject->id,
            'target_repetitions' => 3,
        ]);

        $chapter = PracticeChapter::first();

        // 3. Create Level 1, 2, 3
        $resLvl1 = $this->withSession(['admin_id' => 1])->post(route('cms.practice_chapter.level.store', $chapter->id), [
            'title' => 'Level 1: Basic Vectors',
            'sort_order' => 1,
            'target_repetitions' => 3,
        ]);
        $resLvl1->assertRedirect();
        $this->assertDatabaseHas('practice_quizzes', [
            'title' => 'Level 1: Basic Vectors',
            'chapter_id' => $chapter->id,
            'sort_order' => 1,
            'target_repetitions' => 3,
        ]);
    }

    public function test_sequential_level_locking()
    {
        $user = User::create(['name' => 'Test Student', 'email' => 'student@test.com']);

        $subject = PracticeSubject::create(['title' => 'Math', 'slug' => 'math']);
        $chapter = PracticeChapter::create([
            'subject_id' => $subject->id,
            'title' => 'Algebra',
            'slug' => 'algebra',
            'target_repetitions' => 3,
        ]);

        $level1 = PracticeQuiz::create([
            'chapter_id' => $chapter->id,
            'title' => 'Level 1',
            'slug' => 'lvl-1',
            'sort_order' => 1,
        ]);
        PracticeQuestion::create([
            'practice_quiz_id' => $level1->id,
            'question' => '1+1=?',
            'optn1' => '2',
            'optn2' => '3',
            'right_ans' => 1,
        ]);

        $level2 = PracticeQuiz::create([
            'chapter_id' => $chapter->id,
            'title' => 'Level 2',
            'slug' => 'lvl-2',
            'sort_order' => 2,
        ]);
        PracticeQuestion::create([
            'practice_quiz_id' => $level2->id,
            'question' => '2+2=?',
            'optn1' => '4',
            'optn2' => '5',
            'right_ans' => 1,
        ]);

        // Attempt starting Level 2 before Level 1 is completed -> should be locked and redirected
        $response = $this->actingAsUser($user)->get(route('practice_quiz.start', $level2->id));
        $response->assertRedirect(route('practice_quiz.chapter', $chapter->slug));
        $response->assertSessionHas('error');

        // Now complete Level 1
        $session1 = PracticeSession::create([
            'user_id' => $user->id,
            'practice_quiz_id' => $level1->id,
            'chapter_id' => $chapter->id,
            'session_type' => 'level',
            'total_questions' => 1,
            'mastered_questions' => 1,
            'status' => 'completed',
        ]);

        // Now starting Level 2 should succeed and redirect to play screen
        $resLvl2 = $this->actingAsUser($user)->get(route('practice_quiz.start', $level2->id));
        $resLvl2->assertRedirect();
        $this->assertDatabaseHas('practice_sessions', [
            'user_id' => $user->id,
            'practice_quiz_id' => $level2->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_three_consecutive_correct_rule()
    {
        $user = User::create(['name' => 'Streak Tester', 'email' => 'streak@test.com']);

        $quiz = PracticeQuiz::create([
            'title' => 'Streak Quiz',
            'slug' => 'streak-quiz',
            'target_repetitions' => 3,
        ]);
        $q = PracticeQuestion::create([
            'practice_quiz_id' => $quiz->id,
            'question' => 'Is Earth round?',
            'optn1' => 'Yes',
            'optn2' => 'No',
            'right_ans' => 1,
        ]);

        $session = PracticeSession::create([
            'user_id' => $user->id,
            'practice_quiz_id' => $quiz->id,
            'total_questions' => 1,
            'status' => 'in_progress',
        ]);
        $sessionQ = PracticeSessionQuestion::create([
            'session_id' => $session->id,
            'question_id' => $q->id,
            'correct_count' => 0,
            'is_mastered' => false,
        ]);

        // 1st correct answer -> streak becomes 1
        $res1 = $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $session->id), [
            'question_id' => $q->id,
            'selected_option' => 1,
        ]);
        $res1->assertJson(['is_correct' => true, 'correct_count' => 1, 'question_mastered' => false]);

        // 2nd correct answer -> streak becomes 2
        $res2 = $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $session->id), [
            'question_id' => $q->id,
            'selected_option' => 1,
        ]);
        $res2->assertJson(['is_correct' => true, 'correct_count' => 2, 'question_mastered' => false]);

        // 3rd answer is WRONG -> streak resets to 0!
        $res3 = $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $session->id), [
            'question_id' => $q->id,
            'selected_option' => 2,
        ]);
        $res3->assertJson(['is_correct' => false, 'correct_count' => 0, 'question_mastered' => false]);
        $this->assertDatabaseHas('practice_session_questions', [
            'id' => $sessionQ->id,
            'correct_count' => 0,
            'incorrect_count' => 1,
            'is_mastered' => 0,
        ]);

        // Now answer 3 times consecutively correct:
        $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $session->id), ['question_id' => $q->id, 'selected_option' => 1]);
        $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $session->id), ['question_id' => $q->id, 'selected_option' => 1]);
        $resFinal = $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $session->id), ['question_id' => $q->id, 'selected_option' => 1]);

        $resFinal->assertJson([
            'is_correct' => true,
            'correct_count' => 3,
            'question_mastered' => true,
            'session_completed' => true,
        ]);
    }

    public function test_chapter_exam_flow_and_mistakes_recovery()
    {
        $user = User::create(['name' => 'Exam Student', 'email' => 'exam@test.com']);

        $subject = PracticeSubject::create(['title' => 'Biology', 'slug' => 'biology']);
        $chapter = PracticeChapter::create([
            'subject_id' => $subject->id,
            'title' => 'Cell Structure',
            'slug' => 'cell-structure',
            'exam_duration_minutes' => 10,
            'target_repetitions' => 3,
        ]);

        // Level 1
        $lvl1 = PracticeQuiz::create(['chapter_id' => $chapter->id, 'title' => 'L1', 'slug' => 'l1', 'sort_order' => 1]);
        $q1 = PracticeQuestion::create(['practice_quiz_id' => $lvl1->id, 'question' => 'Q1', 'optn1' => 'A', 'optn2' => 'B', 'right_ans' => 1]);
        PracticeSession::create(['user_id' => $user->id, 'practice_quiz_id' => $lvl1->id, 'chapter_id' => $chapter->id, 'status' => 'completed']);

        // Level 2
        $lvl2 = PracticeQuiz::create(['chapter_id' => $chapter->id, 'title' => 'L2', 'slug' => 'l2', 'sort_order' => 2]);
        $q2 = PracticeQuestion::create(['practice_quiz_id' => $lvl2->id, 'question' => 'Q2', 'optn1' => 'A', 'optn2' => 'B', 'right_ans' => 1]);
        PracticeSession::create(['user_id' => $user->id, 'practice_quiz_id' => $lvl2->id, 'chapter_id' => $chapter->id, 'status' => 'completed']);

        // Start Chapter Exam (both levels are completed)
        $startExamRes = $this->actingAsUser($user)->get(route('practice_quiz.exam.start', $chapter->id));
        $startExamRes->assertRedirect();

        $attempt = PracticeChapterExamAttempt::where('user_id', $user->id)->first();
        $this->assertNotNull($attempt);
        $this->assertEquals(2, $attempt->total_questions);

        // Submit Exam: Answer Q1 right, Q2 wrong
        $submitRes = $this->actingAsUser($user)->post(route('practice_quiz.exam.submit', $attempt->id), [
            'answers' => [
                $q1->id => 1, // Right
                $q2->id => 2, // Wrong
            ],
        ]);
        $submitRes->assertRedirect(route('practice_quiz.exam.result', $attempt->id));

        $attempt->refresh();
        $this->assertEquals(1, $attempt->correct_count);
        $this->assertEquals(1, $attempt->wrong_count);
        $this->assertEquals(50, $attempt->score_percent);

        // Chapter progress should be 'mistake_game'
        $progress = PracticeChapterProgress::where('user_id', $user->id)->where('chapter_id', $chapter->id)->first();
        $this->assertEquals('mistake_game', $progress->current_stage);

        // Start Mistake Recovery Game
        $mistakeRes = $this->actingAsUser($user)->get(route('practice_quiz.mistake_game.start', $chapter->id));
        $mistakeRes->assertRedirect();

        // Mistake game session should contain only the 1 wrong question (Q2)
        $mistakeSession = PracticeSession::where('user_id', $user->id)
            ->where('session_type', 'mistake_game')
            ->where('status', 'in_progress')
            ->first();
        $this->assertNotNull($mistakeSession);
        $this->assertEquals(1, $mistakeSession->total_questions);

        $mistakeSessionQ = PracticeSessionQuestion::where('session_id', $mistakeSession->id)->first();
        $this->assertEquals($q2->id, $mistakeSessionQ->question_id);

        // Answer Q2 three times consecutively correct to finish the chapter
        $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $mistakeSession->id), ['question_id' => $q2->id, 'selected_option' => 1]);
        $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $mistakeSession->id), ['question_id' => $q2->id, 'selected_option' => 1]);
        $finalRes = $this->actingAsUser($user)->postJson(route('practice_quiz.api.submit', $mistakeSession->id), ['question_id' => $q2->id, 'selected_option' => 1]);

        $finalRes->assertJson([
            'session_completed' => true,
            'chapter_context' => [
                'chapter_completed' => true,
            ],
        ]);

        // Chapter should now be officially completed!
        $progress->refresh();
        $this->assertEquals('completed', $progress->current_stage);
        $this->assertNotNull($progress->completed_at);
    }

    public function test_perfect_exam_completes_chapter_immediately()
    {
        $user = User::create(['name' => 'Genius Student', 'email' => 'genius@test.com']);

        $subject = PracticeSubject::create(['title' => 'Chemistry', 'slug' => 'chem']);
        $chapter = PracticeChapter::create(['subject_id' => $subject->id, 'title' => 'Periodic Table', 'slug' => 'periodic-table']);

        $lvl1 = PracticeQuiz::create(['chapter_id' => $chapter->id, 'title' => 'L1', 'slug' => 'chem-l1', 'sort_order' => 1]);
        $q1 = PracticeQuestion::create(['practice_quiz_id' => $lvl1->id, 'question' => 'Q1', 'optn1' => 'A', 'optn2' => 'B', 'right_ans' => 1]);
        PracticeSession::create(['user_id' => $user->id, 'practice_quiz_id' => $lvl1->id, 'chapter_id' => $chapter->id, 'status' => 'completed']);

        $this->actingAsUser($user)->get(route('practice_quiz.exam.start', $chapter->id));
        $attempt = PracticeChapterExamAttempt::where('user_id', $user->id)->first();

        // Submit 100% correct answers
        $this->actingAsUser($user)->post(route('practice_quiz.exam.submit', $attempt->id), [
            'answers' => [$q1->id => 1],
        ]);

        $attempt->refresh();
        $this->assertEquals(0, $attempt->wrong_count);
        $this->assertEquals(100, $attempt->score_percent);

        // Progress immediately marks chapter as completed
        $progress = PracticeChapterProgress::where('user_id', $user->id)->where('chapter_id', $chapter->id)->first();
        $this->assertEquals('completed', $progress->current_stage);
        $this->assertNotNull($progress->completed_at);
    }
}
