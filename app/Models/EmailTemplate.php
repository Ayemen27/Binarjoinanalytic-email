<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'alias',
        'subject',
        'body',
        'shortcodes', // Include shortcodes in the fillable fields
        'status'
    ];
}
