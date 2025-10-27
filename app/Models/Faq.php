<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LangFilter;

class Faq extends Model
{
    use HasFactory, LangFilter;

    protected $fillable = ['title', 'content', 'lang', 'position', 'translate_id'];
}
