<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePracticeHierarchyAndChapterExamTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Practice Subjects Table
        if (!Schema::hasTable('practice_subjects')) {
            Schema::create('practice_subjects', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->nullable()->index();
                $table->string('title');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Practice Chapters Table
        if (!Schema::hasTable('practice_chapters')) {
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
        }

        // 3. Update practice_quizzes for hierarchy (each level in a chapter is a practice_quiz)
        if (Schema::hasTable('practice_quizzes')) {
            Schema::table('practice_quizzes', function (Blueprint $table) {
                if (!Schema::hasColumn('practice_quizzes', 'chapter_id')) {
                    $table->unsignedBigInteger('chapter_id')->nullable()->after('category_id')->index();
                }
                if (!Schema::hasColumn('practice_quizzes', 'sort_order')) {
                    $table->integer('sort_order')->default(1)->after('slug');
                }
            });
        }

        // 4. Update practice_sessions to support chapter-wide and mistake-only games
        if (Schema::hasTable('practice_sessions')) {
            Schema::table('practice_sessions', function (Blueprint $table) {
                if (!Schema::hasColumn('practice_sessions', 'chapter_id')) {
                    $table->unsignedBigInteger('chapter_id')->nullable()->after('practice_quiz_id')->index();
                }
                if (!Schema::hasColumn('practice_sessions', 'session_type')) {
                    $table->string('session_type', 50)->default('level')->after('chapter_id');
                }
                if (!Schema::hasColumn('practice_sessions', 'chapter_exam_attempt_id')) {
                    $table->unsignedBigInteger('chapter_exam_attempt_id')->nullable()->after('session_type')->index();
                }
            });

            if (DB::getDriverName() === 'mysql') {
                try {
                    DB::statement('ALTER TABLE practice_sessions MODIFY practice_quiz_id BIGINT UNSIGNED NULL');
                } catch (\Throwable $e) {
                    // Ignore if already nullable or constrained
                }
            }
        }

        // 5. Practice Chapter Exam Attempts Table
        if (!Schema::hasTable('practice_chapter_exam_attempts')) {
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
                $table->string('status', 50)->default('in_progress'); // 'in_progress', 'submitted'
                $table->timestamps();
            });
        }

        // 6. Practice Chapter Exam Answers Table
        if (!Schema::hasTable('practice_chapter_exam_answers')) {
            Schema::create('practice_chapter_exam_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('exam_attempt_id')->constrained('practice_chapter_exam_attempts')->onDelete('cascade');
                $table->foreignId('question_id')->constrained('practice_questions')->onDelete('cascade');
                $table->tinyInteger('selected_option')->nullable();
                $table->boolean('is_correct')->default(false);
                $table->integer('question_order')->default(0);
                $table->timestamps();
            });
        }

        // 7. Practice Chapter Progress Table
        if (!Schema::hasTable('practice_chapter_progress')) {
            Schema::create('practice_chapter_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('chapter_id')->constrained('practice_chapters')->onDelete('cascade');
                $table->string('current_stage', 50)->default('levels'); // 'levels', 'exam', 'mistake_game', 'completed'
                $table->integer('highest_completed_level_order')->default(0);
                $table->unsignedBigInteger('latest_exam_attempt_id')->nullable();
                $table->unsignedBigInteger('mistake_session_id')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'chapter_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('practice_chapter_progress');
        Schema::dropIfExists('practice_chapter_exam_answers');
        Schema::dropIfExists('practice_chapter_exam_attempts');

        if (Schema::hasTable('practice_sessions')) {
            Schema::table('practice_sessions', function (Blueprint $table) {
                if (Schema::hasColumn('practice_sessions', 'chapter_exam_attempt_id')) {
                    $table->dropColumn('chapter_exam_attempt_id');
                }
                if (Schema::hasColumn('practice_sessions', 'session_type')) {
                    $table->dropColumn('session_type');
                }
                if (Schema::hasColumn('practice_sessions', 'chapter_id')) {
                    $table->dropColumn('chapter_id');
                }
            });
        }

        if (Schema::hasTable('practice_quizzes')) {
            Schema::table('practice_quizzes', function (Blueprint $table) {
                if (Schema::hasColumn('practice_quizzes', 'sort_order')) {
                    $table->dropColumn('sort_order');
                }
                if (Schema::hasColumn('practice_quizzes', 'chapter_id')) {
                    $table->dropColumn('chapter_id');
                }
            });
        }

        Schema::dropIfExists('practice_chapters');
        Schema::dropIfExists('practice_subjects');
    }
}
