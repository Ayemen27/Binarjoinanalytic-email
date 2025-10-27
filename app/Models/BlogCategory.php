<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class BlogCategory extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['name', 'slug', 'lang'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function posts()
    {
        return $this->hasMany(BlogPost::class, "category_id", 'id');
    }

    public function countPosts()
    {
        return $this->posts()->count();
    }


    //Lobage_Team
}
