<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseChapter extends Model
{
    use HasFactory;

    protected $table = 'course_chapters';

    protected $fillable = ['course_id', 'title', 'description', 'sort_order'];

    /**
     * Parent course for this chapter.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    /**
     * Videos belonging to this chapter ordered by display order.
     */
    public function videos()
    {
        return $this->hasMany(CourseModule::class, 'chapter_id', 'id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    /**
     * Free preview videos belonging to this chapter.
     */
    public function freeVideos()
    {
        return $this->hasMany(CourseModule::class, 'chapter_id', 'id')
            ->where('free_paid', 'free')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }
}

