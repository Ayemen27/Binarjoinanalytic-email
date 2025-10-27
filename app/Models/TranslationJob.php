<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslationJob extends Model
{
    protected $fillable = [
        'job_id',
        'total_chunks',
        'processed_chunks',
        'success_count',
        'error_count',
        'status',
        'total_characters',
        'results',
        'message'
    ];


    protected $casts = [
        'results' => 'array',
    ];
}
