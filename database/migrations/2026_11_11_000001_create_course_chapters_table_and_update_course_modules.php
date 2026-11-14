<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCourseChaptersTableAndUpdateCourseModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('course_chapters')) {
            Schema::create('course_chapters', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('course_id')->index();
                $table->string('title');
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('course_modules')) {
            Schema::table('course_modules', function (Blueprint $table) {
                if (!Schema::hasColumn('course_modules', 'chapter_id')) {
                    $table->unsignedBigInteger('chapter_id')->nullable()->after('course_id')->index();
                }
                if (!Schema::hasColumn('course_modules', 'duration')) {
                    $table->string('duration', 100)->nullable()->after('link');
                }
                if (!Schema::hasColumn('course_modules', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('free_paid');
                }
            });

            $this->backfillExistingCourseModules();
        }
    }

    /**
     * Group legacy flat course_modules rows into course_chapters.
     *
     * @return void
     */
    protected function backfillExistingCourseModules()
    {
        $unlinked = DB::table('course_modules')
            ->whereNull('chapter_id')
            ->orderBy('course_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        if ($unlinked->isEmpty()) {
            return;
        }

        $now = Carbon::now();

        DB::transaction(function () use ($unlinked, $now) {
            foreach ($unlinked->groupBy('course_id') as $courseId => $modules) {
                $maxOrder = (int) DB::table('course_chapters')
                    ->where('course_id', $courseId)
                    ->max('sort_order');

                $chapterOrder = $maxOrder + 1;
                $chapterIdsByKey = [];
                $videoOrderByChapter = [];

                foreach ($modules as $module) {
                    $title = trim((string) ($module->title ?? ''));
                    if (preg_match('/^(অধ্যায়[\s\-_–:]*[০-৯0-9]+|Chapter[\s\-_–:]*[0-9]+)/iu', $title, $matches)) {
                        $chapterTitle = trim($matches[1]);
                        $groupKey = mb_strtolower($chapterTitle);
                    } else {
                        $chapterTitle = 'Chapter 1';
                        $groupKey = '__default__';
                    }

                    if (!isset($chapterIdsByKey[$groupKey])) {
                        $existing = DB::table('course_chapters')
                            ->where('course_id', $courseId)
                            ->where('title', $chapterTitle)
                            ->first();

                        if ($existing) {
                            $chapterId = $existing->id;
                            $currentMaxVideo = (int) DB::table('course_modules')
                                ->where('chapter_id', $chapterId)
                                ->max('sort_order');
                            $videoOrderByChapter[$chapterId] = $currentMaxVideo + 1;
                        } else {
                            $chapterId = DB::table('course_chapters')->insertGetId([
                                'course_id' => $courseId,
                                'title' => $chapterTitle,
                                'description' => null,
                                'sort_order' => $chapterOrder++,
                                'created_at' => $now,
                                'updated_at' => $now,
                            ]);
                            $videoOrderByChapter[$chapterId] = 1;
                        }

                        $chapterIdsByKey[$groupKey] = $chapterId;
                    }

                    $targetChapterId = $chapterIdsByKey[$groupKey];

                    DB::table('course_modules')
                        ->where('id', $module->id)
                        ->update([
                            'chapter_id' => $targetChapterId,
                            'sort_order' => $videoOrderByChapter[$targetChapterId]++,
                            'updated_at' => $now,
                        ]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('course_modules')) {
            Schema::table('course_modules', function (Blueprint $table) {
                $columns = [];
                foreach (['chapter_id', 'duration', 'sort_order'] as $column) {
                    if (Schema::hasColumn('course_modules', $column)) {
                        $columns[] = $column;
                    }
                }
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        Schema::dropIfExists('course_chapters');
    }
}

