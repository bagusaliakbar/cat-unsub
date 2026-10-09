<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVerification extends Model
{
    protected $fillable = [
        'token',
        'document_type',
        'title',
        'category_name',
        'total_questions',
        'mode',
        'printed_by',
        'checksum',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'total_questions' => 'integer',
    ];
}
