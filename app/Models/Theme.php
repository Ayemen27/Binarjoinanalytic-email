<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'logo',
        'dark_logo',
        'favicon',
        'unique_name',
        'version',
        'description',
        'image',
        'status',
        'custom_css',
        'custom_js',
        'demo',
        'colors', // Assuming it's a JSON object
        'images', // Assuming it's a JSON object
    ];

    protected $casts = [
        'colors' => 'json', // Cast "colors" column to JSON
        'images' => 'json', // Cast "images" column to JSON
    ];


    public function activateOneTheme()
    {
        // Deactivate all themes
        self::query()->update(['status' => 0]);

        // Activate this theme
        $this->update(['status' => 1]);
    }
}
