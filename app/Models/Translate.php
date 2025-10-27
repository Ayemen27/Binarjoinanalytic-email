<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Translate extends Model
{
    use HasFactory;

    protected $fillable = ['collection', 'value', 'lang', 'type', 'key'];


    public function language()
    {
        return $this->belongsTo(Language::class, 'lang', 'code');
    }



    protected static function boot()
    {
        parent::boot();

        static::saved(function ($translation) {
            Cache::forget($translation->key . '_' . $translation->collection . '_' . $translation->lang);
        });
    }
}
