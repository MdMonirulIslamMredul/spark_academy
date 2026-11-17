<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticeChapterExamAnswer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function attempt()
    {
        return $this->belongsTo(PracticeChapterExamAttempt::class, 'exam_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(PracticeQuestion::class, 'question_id');
    }
}
