<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'randomize_questions' => 'boolean',
        'randomize_options' => 'boolean',
        'is_active' => 'boolean',
        'is_simulation' => 'boolean',
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

    public function wave()
    {
        return $this->belongsTo(Wave::class);
    }

    public function sessions()
    {
        return $this->hasMany(ExamSession::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class);
    }
}
