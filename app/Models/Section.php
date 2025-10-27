<?php

namespace App\Models;

use App\Traits\LangFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory, LangFilter;

    protected $fillable = ['title', 'status', 'position', 'name', 'lang', 'type', 'content'];
}
