<?php

namespace App\Models;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory, HasStatus;

    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;


    const TYPE_FREE = 0;
    const TYPE_PREMIUM = 1;
    const TYPE_CUSTOM = 2;



    public static function getTypeLabels()
    {
        $labels = [];

        $labels[static::TYPE_CUSTOM] = 'Custom';
        $labels[static::TYPE_FREE] = 'Free';
        $labels[static::TYPE_PREMIUM] = 'Premium';
        return $labels;
    }

    public static function getTypeColors()
    {
        $colors = [];

        $colors[static::TYPE_CUSTOM] = 'purple';
        $colors[static::TYPE_FREE] = 'orange';
        $colors[static::TYPE_PREMIUM] = 'lime';

        return $colors;
    }

    public function getTypeLabel()
    {
        $labels = static::getTypeLabels();
        return $labels[$this->type] ?? 'Unknown';
    }

    public function getTypeColor()
    {
        $colors = static::getTypeColors();
        return $colors[$this->type] ?? 'secondary';
    }



    protected $fillable = ['domain', 'status', 'type', 'user_id'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}