<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'category_id',
        'text',
        'type',
        'difficulty',
        'points',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(QuestionCategory::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
