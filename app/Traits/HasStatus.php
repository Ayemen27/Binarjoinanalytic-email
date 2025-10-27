<?php

namespace App\Traits;

trait HasStatus
{
    public static function getStatusLabels()
    {
        $labels = [];

        if (defined('static::STATUS_DRAFT')) {
            $labels[static::STATUS_DRAFT] = translate('Draft', 'general');
        }
        if (defined('static::STATUS_PUBLISHED')) {
            $labels[static::STATUS_PUBLISHED] = 'Published';
            translate('Published', 'general');
        }
        if (defined('static::STATUS_COMPLETE')) {
            $labels[static::STATUS_COMPLETE] = translate('Complete', 'general');
        }
        if (defined('static::STATUS_PENDING')) {
            $labels[static::STATUS_PENDING] = translate('Pending', 'general');
        }
        if (defined('static::STATUS_CANCELED')) {
            $labels[static::STATUS_CANCELED] = translate('Canceled', 'general');
        }
        if (defined('static::STATUS_REJECTED')) {
            $labels[static::STATUS_REJECTED] = translate('Rejected', 'general');
        }
        if (defined('static::STATUS_SUBSCRIBED')) {
            $labels[static::STATUS_SUBSCRIBED] = translate('Subscribed', 'general');
        }
        if (defined('static::STATUS_NOT_SUBSCRIBED')) {
            $labels[static::STATUS_NOT_SUBSCRIBED] = translate('Not Subscribed', 'general');
        }

        if (defined('static::STATUS_ACTIVE')) {
            $labels[static::STATUS_ACTIVE] = translate('Active', 'general');
        }
        if (defined('static::STATUS_INACTIVE')) {
            $labels[static::STATUS_INACTIVE] = translate('Inactive', 'general');
        }

        if (defined('static::STATUS_APPROVED')) {
            $labels[static::STATUS_APPROVED] =  translate('Approved', 'general');
        }

        return $labels;
    }

    public static function getStatusColors()
    {
        $colors = [];

        if (defined('static::STATUS_DRAFT')) {
            $colors[static::STATUS_DRAFT] = 'secondary';
        }
        if (defined('static::STATUS_PUBLISHED')) {
            $colors[static::STATUS_PUBLISHED] = 'green';
        }
        if (defined('static::STATUS_COMPLETE')) {
            $colors[static::STATUS_COMPLETE] = 'primary';
        }
        if (defined('static::STATUS_PENDING')) {
            $colors[static::STATUS_PENDING] = 'orange';
        }
        if (defined('static::STATUS_CANCELED')) {
            $colors[static::STATUS_CANCELED] = 'red';
        }
        if (defined('static::STATUS_REJECTED')) {
            $colors[static::STATUS_REJECTED] = 'red';
        }
        if (defined('static::STATUS_SUBSCRIBED')) {
            $colors[static::STATUS_SUBSCRIBED] = 'info';
        }
        if (defined('static::STATUS_NOT_SUBSCRIBED')) {
            $colors[static::STATUS_NOT_SUBSCRIBED] = 'dark';
        }

        if (defined('static::STATUS_ACTIVE')) {
            $colors[static::STATUS_ACTIVE] = 'green';
        }
        if (defined('static::STATUS_INACTIVE')) {
            $colors[static::STATUS_INACTIVE] = 'orange';
        }

        if (defined('static::STATUS_APPROVED')) {
            $colors[static::STATUS_APPROVED] = 'green';
        }




        return $colors;
    }

    public function getStatusLabel()
    {
        $labels = static::getStatusLabels();
        return $labels[$this->status] ?? translate('Unknown', 'general');
    }

    public function getStatusColor()
    {
        $colors = static::getStatusColors();
        return $colors[$this->status] ?? 'secondary';
    }
}