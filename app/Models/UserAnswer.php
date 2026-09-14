<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = [
        'exam_session_id',
        'question_id',
        'option_id',
        'answer_text',
        'is_doubtful',
        'options_order'
    ];

    protected $casts = [
        'is_doubtful' => 'boolean',
        'options_order' => 'array'
    ];

    public function examSession()
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
