<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LangFilter;


class Menu extends Model
{
    use HasFactory, LangFilter;

    protected $fillable = ['name', 'icon', 'url', 'lang', 'position', 'parent', 'type', 'is_external'];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent')->orderBy('position');
    }
}
