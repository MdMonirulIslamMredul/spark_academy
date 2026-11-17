<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeChapterProgress extends Model
{
    use HasFactory;

    protected $table = 'practice_chapter_progress';

    protected $guarded = ['id'];

    protected $dates = ['completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chapter()
    {
        return $this->belongsTo(PracticeChapter::class, 'chapter_id');
    }

    public function latestExamAttempt()
    {
        return $this->belongsTo(PracticeChapterExamAttempt::class, 'latest_exam_attempt_id');
    }

    public function mistakeSession()
    {
        return $this->belongsTo(PracticeSession::class, 'mistake_session_id');
    }

    public function isCompleted()
    {
        return $this->current_stage === 'completed';
    }
}
