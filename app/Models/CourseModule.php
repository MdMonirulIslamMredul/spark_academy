<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'chapter_id',
        'title',
        'link',
        'duration',
        'free_paid',
        'sort_order',
    ];

    /**
     * Parent course for this video.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * Parent chapter for this video.
     */
    public function chapter()
    {
        return $this->belongsTo(CourseChapter::class, 'chapter_id', 'id');
    }

    public static function updateModule($titles, $links, $free_paid, $courseId)
    {
        $existingModules = CourseModule::where('course_id', $courseId)->get();
        $submittedTitles = is_array($titles) ? $titles : [];

        foreach ($existingModules as $module) {
            if (!in_array($module->title, $submittedTitles)) {
                $module->delete();
            }
        }

        self::newModule($titles, $links, $free_paid, $courseId);
    }

    public static function newModule($titles, $links, $free_paid, $courseId)
    {
        if (is_array($titles) && is_array($links) && is_array($free_paid)) {
            $defaultChapter = CourseChapter::where('course_id', $courseId)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->first();

            if (!$defaultChapter && count($titles) > 0) {
                $defaultChapter = CourseChapter::create([
                    'course_id' => $courseId,
                    'title' => 'Chapter 1',
                    'sort_order' => 1,
                ]);
            }

            foreach ($titles as $key => $title) {
                if (isset($links[$key]) && isset($free_paid[$key])) {
                    $module = CourseModule::where('course_id', $courseId)
                        ->where('title', $title)
                        ->first();

                    if ($module) {
                        $module->link = $links[$key];
                        $module->free_paid = $free_paid[$key];
                        if (!$module->chapter_id && $defaultChapter) {
                            $module->chapter_id = $defaultChapter->id;
                        }
                        $module->save();
                    } else {
                        CourseModule::create([
                            'course_id' => $courseId,
                            'chapter_id' => optional($defaultChapter)->id,
                            'title' => $title,
                            'link' => $links[$key],
                            'free_paid' => $free_paid[$key],
                            'sort_order' => (int) $key + 1,
                        ]);
                    }
                }
            }
        }
    }
}
