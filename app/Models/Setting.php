<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];



    protected static function boot()
    {
        parent::boot();

        // Clear cache only for the specific key when a setting is updated or created
        static::saved(function ($setting) {
            Cache::forget('setting_' . $setting->key);
        });
    }
}
