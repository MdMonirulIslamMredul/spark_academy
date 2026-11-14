<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseModule;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CourseChaptersTest extends TestCase
{
    /** @var array<int, string> */
    protected $createdPublicImages = [];

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

    protected function tearDown(): void
    {
        foreach ($this->createdPublicImages as $relativePath) {
            $full = public_path($relativePath);
            if (file_exists($full)) {
                @unlink($full);
            }
        }

        parent::tearDown();
    }

    protected function createBaseSchema(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('logo')->nullable();
            $table->string('phone_1')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('messenger_link')->nullable();
            $table->string('facebook')->nullable();
            $table->text('footer_des')->nullable();
            $table->string('footer_credit')->nullable();
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('free_paid')->default('free');
            $table->integer('active')->default(1);
            $table->timestamps();
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('catID')->nullable();
            $table->string('exam_name')->nullable();
            $table->string('free_paid')->default('free');
            $table->integer('active')->default(1);
            $table->timestamps();
        });

        Schema::create('notice', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->integer('active')->default(1);
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });

        Schema::create('course_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('active')->default(1);
            $table->timestamps();
        });

        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('catID')->nullable();
            $table->string('name');
            $table->integer('price')->default(0);
            $table->text('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('active')->default(1);
            $table->integer('rank')->nullable();
            $table->timestamps();
        });

        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id');
            $table->text('title');
            $table->text('link');
            $table->text('free_paid');
            $table->timestamps();
        });

        Schema::create('courses_order', function (Blueprint $table) {
            $table->id();
            $table->integer('pid')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('status')->default('pending');
            $table->string('payment_status')->nullable();
            $table->timestamps();
        });

        DB::table('admins')->insert(['id' => 1, 'name' => 'Admin', 'email' => 'admin@example.com']);
        DB::table('company_settings')->insert(['id' => 1, 'company_name' => 'Spark Academy']);
        DB::table('course_categories')->insert(['id' => 1, 'name' => 'Science', 'active' => 1]);
        DB::table('users')->insert(['id' => 1, 'name' => 'Student One', 'email' => 'student@example.com', 'phone' => '01700000000']);
    }

    protected function runChapterMigration(): void
    {
        require_once database_path('migrations/2026_11_11_000001_create_course_chapters_table_and_update_course_modules.php');
        $migration = new \CreateCourseChaptersTableAndUpdateCourseModules();
        $migration->up();
    }

    public function test_migration_backfills_legacy_flat_course_modules_into_chapters(): void
    {
        $course1 = DB::table('courses')->insertGetId([
            'catID' => '1',
            'name' => 'Physics Legacy Course',
            'price' => 1500,
            'image' => 'images/legacy.jpg',
        ]);
        $course2 = DB::table('courses')->insertGetId([
            'catID' => '1',
            'name' => 'Math Legacy Course',
            'price' => 1200,
            'image' => 'images/legacy2.jpg',
        ]);

        DB::table('course_modules')->insert([
            ['course_id' => $course1, 'title' => 'অধ্যায়-০১ লেকচার ০১: পরিমাপ', 'link' => 'https://youtu.be/aaa', 'free_paid' => 'free'],
            ['course_id' => $course1, 'title' => 'অধ্যায়-০১ লেকচার ০২: ত্রুটি', 'link' => 'https://youtu.be/bbb', 'free_paid' => 'free'],
            ['course_id' => $course1, 'title' => 'অধ্যায়-০২ লেকচার ০১: ভেক্টর', 'link' => 'https://youtu.be/ccc', 'free_paid' => 'paid'],
            ['course_id' => $course2, 'title' => 'Intro Algebra Lesson', 'link' => 'https://youtu.be/ddd', 'free_paid' => 'paid'],
        ]);

        $this->runChapterMigration();

        $this->assertTrue(Schema::hasTable('course_chapters'));
        $this->assertTrue(Schema::hasColumns('course_modules', ['chapter_id', 'duration', 'sort_order']));

        $c1Chapters = CourseChapter::where('course_id', $course1)->orderBy('sort_order')->get();
        $this->assertCount(2, $c1Chapters);
        $this->assertSame('অধ্যায়-০১', $c1Chapters[0]->title);
        $this->assertSame('অধ্যায়-০২', $c1Chapters[1]->title);
        $this->assertCount(2, $c1Chapters[0]->videos);
        $this->assertCount(1, $c1Chapters[1]->videos);

        $c2Chapters = CourseChapter::where('course_id', $course2)->get();
        $this->assertCount(1, $c2Chapters);
        $this->assertSame('Chapter 1', $c2Chapters[0]->title);
        $this->assertSame(0, CourseModule::whereNull('chapter_id')->count());
    }

    public function test_admin_can_create_course_with_zero_chapters_and_with_nested_chapters_and_videos(): void
    {
        $this->runChapterMigration();

        // 1. Course with 0 chapters
        $responseEmpty = $this->withSession(['admin_id' => 1])->post(route('cms.add_course_confirm'), [
            'catID' => 1,
            'name' => 'Draft Course Without Chapters',
            'price' => 500,
            'image' => UploadedFile::fake()->image('draft.jpg', 800, 630),
            'description' => 'Draft description',
        ]);

        $responseEmpty->assertRedirect('/cms/course/list');
        $draftCourse = Course::where('name', 'Draft Course Without Chapters')->first();
        $this->assertNotNull($draftCourse);
        $this->createdPublicImages[] = $draftCourse->image;
        $this->assertCount(0, $draftCourse->chapters);

        // 2. Course with multiple chapters and videos
        $responseNested = $this->withSession(['admin_id' => 1])->post(route('cms.add_course_confirm'), [
            'catID' => 1,
            'name' => 'Structured Biology Course',
            'price' => 2500,
            'image' => UploadedFile::fake()->image('bio.jpg', 800, 630),
            'description' => 'Complete syllabus',
            'chapters' => [
                [
                    'title' => 'Chapter 1: Cell Structure',
                    'description' => 'Basics of cells',
                    'sort_order' => 1,
                    'videos' => [
                        [
                            'title' => 'Video 1.1: Animal Cell',
                            'link' => 'https://www.youtube.com/watch?v=vid111',
                            'free_paid' => 'free',
                            'duration' => '18 min',
                            'sort_order' => 1,
                        ],
                        [
                            'title' => 'Video 1.2: Plant Cell',
                            'link' => 'https://www.youtube.com/watch?v=vid112',
                            'free_paid' => 'paid',
                            'duration' => '22 min',
                            'sort_order' => 2,
                        ],
                    ],
                ],
                [
                    'title' => 'Chapter 2: Cell Division',
                    'description' => 'Mitosis and meiosis',
                    'sort_order' => 2,
                    'videos' => [],
                ],
            ],
        ]);

        $responseNested->assertRedirect('/cms/course/list');

        $course = Course::with('chapters.videos')->where('name', 'Structured Biology Course')->first();
        $this->assertNotNull($course);
        $this->createdPublicImages[] = $course->image;
        $this->assertCount(2, $course->chapters);
        $this->assertSame('Chapter 1: Cell Structure', $course->chapters[0]->title);
        $this->assertSame('Basics of cells', $course->chapters[0]->description);
        $this->assertCount(2, $course->chapters[0]->videos);
        $this->assertSame('18 min', $course->chapters[0]->videos[0]->duration);
        $this->assertSame('free', $course->chapters[0]->videos[0]->free_paid);
        $this->assertSame('paid', $course->chapters[0]->videos[1]->free_paid);
        $this->assertCount(0, $course->chapters[1]->videos);
    }

    public function test_validation_rejects_chapter_without_title_or_video_without_link(): void
    {
        $this->runChapterMigration();

        $resMissingChapterTitle = $this->withSession(['admin_id' => 1])->post(route('cms.add_course_confirm'), [
            'catID' => 1,
            'name' => 'Invalid Chapter Course',
            'price' => 1000,
            'image' => UploadedFile::fake()->image('c1.jpg', 800, 630),
            'chapters' => [
                [
                    'title' => '',
                    'sort_order' => 1,
                ],
            ],
        ]);
        $resMissingChapterTitle->assertSessionHasErrors('chapters.0.title');

        $resMissingVideoLink = $this->withSession(['admin_id' => 1])->post(route('cms.add_course_confirm'), [
            'catID' => 1,
            'name' => 'Invalid Video Course',
            'price' => 1000,
            'image' => UploadedFile::fake()->image('c2.jpg', 800, 630),
            'chapters' => [
                [
                    'title' => 'Valid Chapter',
                    'sort_order' => 1,
                    'videos' => [
                        [
                            'title' => 'Valid Video Name',
                            'link' => '',
                            'free_paid' => 'free',
                        ],
                    ],
                ],
            ],
        ]);
        $resMissingVideoLink->assertSessionHasErrors('chapters.0.videos.0.link');
    }

    public function test_admin_can_edit_sync_and_delete_course_chapters_and_videos(): void
    {
        $this->runChapterMigration();

        $course = Course::create([
            'catID' => '1',
            'name' => 'Chemistry 1st Paper',
            'price' => 2000,
            'image' => 'images/chem.jpg',
            'active' => 1,
        ]);

        $ch1 = CourseChapter::create(['course_id' => $course->id, 'title' => 'Old Chapter 1', 'sort_order' => 1]);
        $ch2 = CourseChapter::create(['course_id' => $course->id, 'title' => 'Old Chapter 2', 'sort_order' => 2]);

        $v1 = CourseModule::create([
            'course_id' => $course->id,
            'chapter_id' => $ch1->id,
            'title' => 'Old Video 1',
            'link' => 'https://youtu.be/old1',
            'duration' => '10 min',
            'free_paid' => 'free',
            'sort_order' => 1,
        ]);
        $v2 = CourseModule::create([
            'course_id' => $course->id,
            'chapter_id' => $ch2->id,
            'title' => 'Old Video 2',
            'link' => 'https://youtu.be/old2',
            'free_paid' => 'paid',
            'sort_order' => 1,
        ]);

        // Edit view loads nested structure
        $editPage = $this->withSession(['admin_id' => 1])->get('/editCourse/' . $course->id);
        $editPage->assertStatus(200);
        $editPage->assertSee('Old Chapter 1');
        $editPage->assertSee('Old Video 1');

        // Unrelated course whose chapters/videos must stay untouched
        $otherCourse = Course::create([
            'catID' => '1',
            'name' => 'Other Course',
            'price' => 900,
            'image' => 'images/other.jpg',
            'active' => 1,
        ]);
        $otherCh = CourseChapter::create(['course_id' => $otherCourse->id, 'title' => 'Other Chapter', 'sort_order' => 1]);
        CourseModule::create([
            'course_id' => $otherCourse->id,
            'chapter_id' => $otherCh->id,
            'title' => 'Other Video',
            'link' => 'https://youtu.be/other',
            'free_paid' => 'free',
            'sort_order' => 1,
        ]);

        // Atomic update: rename ch1 & v1, add new video to ch1, delete ch2 & v2, add ch3
        $updateRes = $this->withSession(['admin_id' => 1])->post(route('cms.update_course_confirm', $course->id), [
            'catID' => 1,
            'name' => 'Chemistry Updated',
            'price' => 2200,
            'rank' => 1,
            'description' => 'Updated desc',
            'chapters' => [
                [
                    'id' => $ch1->id,
                    'title' => 'Renamed Chapter 1',
                    'description' => 'Updated ch1 desc',
                    'sort_order' => 2,
                    'videos' => [
                        [
                            'id' => $v1->id,
                            'title' => 'Renamed Video 1',
                            'link' => 'https://youtu.be/new1',
                            'duration' => '15 min',
                            'free_paid' => 'paid',
                            'sort_order' => 1,
                        ],
                        [
                            'id' => '',
                            'title' => 'Brand New Video',
                            'link' => 'https://youtu.be/new2',
                            'duration' => '25 min',
                            'free_paid' => 'free',
                            'sort_order' => 2,
                        ],
                    ],
                ],
                [
                    'id' => '',
                    'title' => 'New First Chapter',
                    'description' => '',
                    'sort_order' => 1,
                    'videos' => [],
                ],
            ],
        ]);

        $updateRes->assertRedirect();
        $course->refresh();
        $chapters = $course->chapters()->with('videos')->get();

        $this->assertCount(2, $chapters);
        $this->assertSame('New First Chapter', $chapters[0]->title);
        $this->assertSame('Renamed Chapter 1', $chapters[1]->title);
        $this->assertNull(CourseChapter::find($ch2->id));
        $this->assertNull(CourseModule::find($v2->id));
        $this->assertCount(2, $chapters[1]->videos);
        $this->assertSame('Renamed Video 1', $chapters[1]->videos[0]->title);
        $this->assertSame('paid', $chapters[1]->videos[0]->free_paid);
        $this->assertSame('Brand New Video', $chapters[1]->videos[1]->title);

        // Clearing all chapters on update leaves other courses untouched
        $clearRes = $this->withSession(['admin_id' => 1])->post(route('cms.update_course_confirm', $course->id), [
            'catID' => 1,
            'name' => 'Chemistry Cleared',
            'price' => 2200,
            'rank' => 0,
        ]);
        $clearRes->assertRedirect();
        $this->assertSame(0, CourseChapter::where('course_id', $course->id)->count());
        $this->assertSame(0, CourseModule::where('course_id', $course->id)->count());
        $this->assertSame(1, CourseChapter::where('course_id', $otherCourse->id)->count());
        $this->assertSame(1, CourseModule::where('course_id', $otherCourse->id)->count());

        // Delete course cascades chapters and videos
        $delRes = $this->withSession(['admin_id' => 1])->delete(route('cms.delete.course', $course->id));
        $delRes->assertRedirect(route('cms.course.list'));
        $this->assertNull(Course::find($course->id));
        $this->assertSame(0, CourseChapter::where('course_id', $course->id)->count());
        $this->assertSame(0, CourseModule::where('course_id', $course->id)->count());
        $this->assertSame(1, CourseChapter::where('course_id', $otherCourse->id)->count());
    }

    public function test_cms_pages_and_course_showcase_render_and_preserve_old_chapters_on_validation_error(): void
    {
        $this->runChapterMigration();

        $createPage = $this->withSession(['admin_id' => 1])->get(route('cms.add_course'));
        $createPage->assertStatus(200);
        $createPage->assertSee('Course Curriculum (Chapters &amp; Videos)', false);
        $createPage->assertSee('Add Chapter');

        // Validation error (missing image) preserves nested chapters & videos in flashed old input
        $failedCreate = $this->withSession(['admin_id' => 1])
            ->from(route('cms.add_course'))
            ->post(route('cms.add_course_confirm'), [
                'catID' => 1,
                'name' => 'Course With Missing Image',
                'price' => 1500,
                'chapters' => [
                    [
                        'title' => 'Preserved Chapter Title',
                        'description' => 'Preserved description',
                        'sort_order' => 1,
                        'videos' => [
                            [
                                'title' => 'Preserved Video Name',
                                'link' => 'https://youtu.be/keepme',
                                'free_paid' => 'free',
                                'duration' => '14 min',
                                'sort_order' => 1,
                            ],
                        ],
                    ],
                ],
            ]);
        $failedCreate->assertRedirect(route('cms.add_course'));
        $failedCreate->assertSessionHasErrors('image');

        $repopulatedCreate = $this->withSession(['admin_id' => 1])->get(route('cms.add_course'));
        $repopulatedCreate->assertStatus(200);
        $repopulatedCreate->assertSee('Preserved Chapter Title');
        $repopulatedCreate->assertSee('Preserved Video Name');

        $course = Course::create([
            'catID' => '1',
            'name' => 'HSC-28 Physics 1st Paper',
            'price' => 3000,
            'image' => 'images/phys.jpg',
            'active' => 1,
        ]);
        $ch1 = CourseChapter::create(['course_id' => $course->id, 'title' => 'Chapter 1: Measurement', 'sort_order' => 1]);
        CourseModule::create([
            'course_id' => $course->id,
            'chapter_id' => $ch1->id,
            'title' => 'Lecture 1',
            'link' => 'https://youtu.be/p1',
            'free_paid' => 'free',
            'sort_order' => 1,
        ]);

        $listPage = $this->withSession(['admin_id' => 1])->get(route('cms.course.list'));
        $listPage->assertStatus(200);
        $listPage->assertSee('1 Chapters');
        $listPage->assertSee('1 Videos');

        $showcasePage = $this->get(route('course'));
        $showcasePage->assertStatus(200);
        $showcasePage->assertSee('১টি পূর্ণাঙ্গ অধ্যায়');
        $showcasePage->assertSee('Chapter 1: Measurement');
    }

    public function test_frontend_course_details_and_lesson_players_render_chapters_and_videos(): void
    {
        $this->runChapterMigration();

        $course = Course::create([
            'catID' => '1',
            'name' => 'Custom Calculus Course',
            'price' => 1800,
            'image' => 'images/calc.jpg',
            'description' => 'Master calculus step by step.',
            'active' => 1,
        ]);

        $ch1 = CourseChapter::create([
            'course_id' => $course->id,
            'title' => 'Limits & Continuity',
            'description' => 'Foundational limits concepts',
            'sort_order' => 1,
        ]);
        $ch2 = CourseChapter::create([
            'course_id' => $course->id,
            'title' => 'Differentiation',
            'description' => 'Derivatives and chain rule',
            'sort_order' => 2,
        ]);

        $freeVid = CourseModule::create([
            'course_id' => $course->id,
            'chapter_id' => $ch1->id,
            'title' => 'Intro to Limits',
            'link' => 'https://www.youtube.com/watch?v=freevid01',
            'duration' => '12 min',
            'free_paid' => 'free',
            'sort_order' => 1,
        ]);
        $paidVid = CourseModule::create([
            'course_id' => $course->id,
            'chapter_id' => $ch2->id,
            'title' => 'Chain Rule Deep Dive',
            'link' => 'https://www.youtube.com/watch?v=paidvid02',
            'duration' => '29 min',
            'free_paid' => 'paid',
            'sort_order' => 1,
        ]);

        // Course Details page displays real chapters & videos (not hardcoded physics chapters)
        $detailsRes = $this->get(route('course.detail', $course->id));
        $detailsRes->assertStatus(200);
        $detailsRes->assertSee('Limits &amp; Continuity', false);
        $detailsRes->assertSee('Differentiation');
        $detailsRes->assertSee('Intro to Limits');
        $detailsRes->assertSee('Chain Rule Deep Dive');
        $detailsRes->assertSee('12 min');
        $detailsRes->assertDontSee('নিউটনিয়ান বলবিদ্যা');

        // Guest (without login) sees all chapters & videos on free preview, plays free videos, and paid videos are locked without leaking links
        $guestFreeRes = $this->get(route('free.lession', ['id' => $course->id, 'chapter' => $ch1->id, 'video' => $freeVid->id]));
        $guestFreeRes->assertStatus(200);
        $guestFreeRes->assertSee('Limits &amp; Continuity', false);
        $guestFreeRes->assertSee('Intro to Limits');
        $guestFreeRes->assertSee('data-link="https://www.youtube.com/watch?v=freevid01"', false);
        $guestFreeRes->assertSee('Differentiation');
        $guestFreeRes->assertSee('Chain Rule Deep Dive');
        $guestFreeRes->assertSee('locked-video-link');
        $guestFreeRes->assertSee('Purchase Full Course');
        $guestFreeRes->assertDontSee('paidvid02');

        // Guest (without login) cannot access paid lesson player
        $guestPaidRes = $this->get(route('paid.lession', ['id' => $course->id, 'chapter' => $ch2->id, 'video' => $paidVid->id]));
        $guestPaidRes->assertRedirect(route('user.login_or_signup'));

        // Logged-in student can also watch free preview videos with paid chapters shown as locked
        $freeRes = $this->withCookies(['user_id' => '1', 'user_name' => 'Student One'])
            ->get(route('free.lession', ['id' => $course->id, 'chapter' => $ch1->id, 'video' => $freeVid->id]));
        $freeRes->assertStatus(200);
        $freeRes->assertSee('Intro to Limits');
        $freeRes->assertSee('Chain Rule Deep Dive');
        $freeRes->assertDontSee('paidvid02');

        // Course with zero free videos shows purchase overlay immediately on free preview
        $paidOnlyCourse = Course::create([
            'catID' => '1',
            'name' => 'Paid Only Course',
            'price' => 3000,
            'image' => 'images/paidonly.jpg',
            'active' => 1,
        ]);
        $poCh = CourseChapter::create(['course_id' => $paidOnlyCourse->id, 'title' => 'Locked Chapter 1', 'sort_order' => 1]);
        CourseModule::create([
            'course_id' => $paidOnlyCourse->id,
            'chapter_id' => $poCh->id,
            'title' => 'Locked Lecture 1',
            'link' => 'https://www.youtube.com/watch?v=secretPaid99',
            'free_paid' => 'paid',
            'sort_order' => 1,
        ]);
        $paidOnlyFreeRes = $this->get(route('free.lession', $paidOnlyCourse->id));
        $paidOnlyFreeRes->assertStatus(200);
        $paidOnlyFreeRes->assertSee('cp-locked-overlay show');
        $paidOnlyFreeRes->assertSee('Locked Lecture 1');
        $paidOnlyFreeRes->assertDontSee('secretPaid99');

        // Enrolled student accessing paid lesson player sees both chapters and deep-linked video active
        DB::table('courses_order')->insert([
            'pid' => $course->id,
            'course_id' => $course->id,
            'user_id' => 1,
            'status' => 'enrolled',
            'payment_status' => 'paid',
        ]);

        DB::table('company_settings')->where('id', 1)->update(['company_name' => 'Spark Academy - Emu Sir']);

        $paidRes = $this->withCookies(['user_id' => '1', 'user_name' => 'Student One'])
            ->get(route('paid.lession', ['id' => $course->id, 'chapter' => $ch2->id, 'video' => $paidVid->id]));
        $paidRes->assertStatus(200);
        $paidRes->assertSee('Limits &amp; Continuity', false);
        $paidRes->assertSee('Differentiation');
        $paidRes->assertSee('Intro to Limits');
        $paidRes->assertSee('Chain Rule Deep Dive');
        $paidRes->assertSee('data-video-id="' . $paidVid->id . '"', false);
        $paidRes->assertSee('active-lecture');
        $paidRes->assertSee('Spark Academy সুরক্ষিত');
        $paidRes->assertSee('Spark Academy • Student One');
        $paidRes->assertSee('id="youtubePlayerTarget"', false);

        // Course category page shows dynamic chapter and video counts
        $catRes = $this->get(route('cat', 1));
        $catRes->assertStatus(200);
        $catRes->assertSee('2 Chapters • 2 Videos');
    }
}
