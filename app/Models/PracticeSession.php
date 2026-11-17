<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeSession extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function quiz()
    {
        return $this->belongsTo(PracticeQuiz::class, 'practice_quiz_id');
    }

    public function chapter()
    {
        return $this->belongsTo(PracticeChapter::class, 'chapter_id');
    }

    public function examAttempt()
    {
        return $this->belongsTo(PracticeChapterExamAttempt::class, 'chapter_exam_attempt_id');
    }

    public function getDisplayTitleAttribute()
    {
        if ($this->session_type === 'mistake_game') {
            if ($this->chapter) {
                return $this->chapter->title . ' - ভুলের উপর স্পেশাল গেম';
            }
            return 'ভুলের উপর স্পেশাল গেম';
        }

        if ($this->quiz) {
            return $this->quiz->title;
        }

        if ($this->chapter) {
            return $this->chapter->title . ' Practice';
        }

        return 'Preparation Quiz';
    }

    public function getEffectiveTargetRepetitionsAttribute()
    {
        if ($this->quiz && !empty($this->quiz->target_repetitions)) {
            return (int)$this->quiz->target_repetitions;
        }

        if ($this->chapter && !empty($this->chapter->target_repetitions)) {
            return (int)$this->chapter->target_repetitions;
        }

        return 3;
    }

    public function sessionQuestions()
    {
        return $this->hasMany(PracticeSessionQuestion::class, 'session_id');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getProgressPercentAttribute()
    {
        if ($this->total_questions == 0) {
            return 0;
        }
        return min(100, round(($this->mastered_questions / $this->total_questions) * 100));
    }

    public function getAccuracyPercentAttribute()
    {
        if ($this->total_attempts == 0) {
            return 100;
        }
        return round(($this->correct_attempts / $this->total_attempts) * 100);
    }
}
