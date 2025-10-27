<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class BlogPost extends Model
{
    use HasFactory, Sluggable, HasStatus;

    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'image',
        'views',
        'category_id',
        'meta_description',
        'meta_title',
        'lang',
        'status',
        'small_image',
        'tags'
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePopular($query)
    {
        return $query->where("lang", getCurrentLang())->where("status", 1)->orderBy(getSetting('popular_post_order_by'), 'desc')->take(getSetting('total_popular_posts_home'));
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function incrementViewCount()
    {
        $this->increment('views');
    }

    public function relatedPosts($limit = 5)
    {
        return BlogPost::whereHas('tags', function ($query) {
            $query->whereIn('name', $this->tags->pluck('name'));
        })->where('id', '<>', $this->id)->limit($limit)->get();
    }
}
