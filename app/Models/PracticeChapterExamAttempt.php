<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PracticeChapterExamAttempt extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['started_at', 'submitted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapter()
    {
        return $this->belongsTo(PracticeChapter::class, 'chapter_id');
    }

    public function answers()
    {
        return $this->hasMany(PracticeChapterExamAnswer::class, 'exam_attempt_id')
            ->orderBy('question_order', 'asc');
    }

    public function mistakeAnswers()
    {
        return $this->hasMany(PracticeChapterExamAnswer::class, 'exam_attempt_id')
            ->where('is_correct', false)
            ->orderBy('question_order', 'asc');
    }

    public function mistakeQuestionIds()
    {
        return $this->mistakeAnswers()->pluck('question_id')->toArray();
    }

    public function remainingSeconds()
    {
        if ($this->status !== 'in_progress' || empty($this->started_at)) {
            return 0;
        }

        $elapsed = Carbon::now()->diffInSeconds($this->started_at);
        $remaining = (int) $this->duration_seconds - $elapsed;

        return max(0, $remaining);
    }
}
