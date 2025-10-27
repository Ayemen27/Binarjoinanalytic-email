<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unique_name',
        'tag',
        'logo',
        'cover',
        'url',
        'creator',
        'short_description',
        'description',
        'guide',
        'action',
        'code',
        'version',
        'license',
        'status',
        'is_featured',
    ];


    protected $casts = [
        'code' => 'object',
    ];



    // Function to get active plugins
    public function isActive()
    {
        return $this->status === 1;
    }

    // Function to find a plugin by its unique name
    public static function getPluginByName($uniqueName)
    {
        return self::where('unique_name', $uniqueName)
            ->where('status', 1)
            ->first();
    }
}
