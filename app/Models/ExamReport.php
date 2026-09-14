<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamReport extends Model
{
    protected $fillable = [
        'exam_id',
        'proctor_name',
        'supervisor_name',
        'present_count',
        'absent_count',
        'notes',
        'village',
        'district',
        'reference_number',
        'exam_materials',
        'committee_name',
        'witness_1',
        'witness_2',
        'witness_3',
        'witness_4',
        'witness_5',
        'witness_6',
        'witness_7',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
