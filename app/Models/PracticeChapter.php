<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeChapter extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subject()
    {
        return $this->belongsTo(PracticeSubject::class, 'subject_id');
    }

    public function levels()
    {
        return $this->hasMany(PracticeQuiz::class, 'chapter_id')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    public function activeLevels()
    {
        return $this->hasMany(PracticeQuiz::class, 'chapter_id')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'asc');
    }

    public function questions()
    {
        return $this->hasManyThrough(
            PracticeQuestion::class,
            PracticeQuiz::class,
            'chapter_id',       // Foreign key on practice_quizzes table...
            'practice_quiz_id', // Foreign key on practice_questions table...
            'id',               // Local key on practice_chapters table...
            'id'                // Local key on practice_quizzes table...
        );
    }

    public function examAttempts()
    {
        return $this->hasMany(PracticeChapterExamAttempt::class, 'chapter_id');
    }

    public function progressForUser($userId)
    {
        return $this->hasOne(PracticeChapterProgress::class, 'chapter_id')->where('user_id', $userId);
    }
}
