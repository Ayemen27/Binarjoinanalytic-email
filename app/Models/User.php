<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Lobage\Planify\Traits\HasSubscriptions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasSubscriptions;


    const STATUS_ = 0;
    const STATUS_PUBLISHED = 1;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'facebook_id',
        'google_id',
        'status',
        'password',
        'avatar',
        'country',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFullName()
    {
        return trim("{$this->firstname} {$this->lastname}");
    }


    public function getName()
    {
        if ($this->firstname && $this->lastname) {
            return $this->firstname . ' ' . $this->lastname;
        } elseif ($this->email) {
            $emailUsername = explode('@', $this->email);
            return $emailUsername[0];
        }
    }



    public function messages()
    {
        return $this->hasMany(Message::class);
    }



    public function sendEmailVerificationNotification()
    {
        if (getSetting('enable_verification')) {
            try {
                $this->notify(new \App\Notifications\CustomVerifyEmail());
            } catch (\Exception $e) {
                $msg = "The SMTP server is not configured. Please review your SMTP server settings and ensure that a proper SMTP server is set up.";
                sendNotification($msg, 'error', true, null, route('admin.settings.smtp'));
            }
        }
    }

    public function sendPasswordResetNotification($token)
    {
        try {
            $this->notify(new \App\Notifications\CustomResetPassword($token));
        } catch (\Exception $e) {
            $msg = "The SMTP server is not configured. Please review your SMTP server settings and ensure that a proper SMTP server is set up.";
            sendNotification($msg, 'error', true, null, route('admin.settings.smtp'));
        }
    }



    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Delete all related subscriptions when the user is deleted
            $user->subscriptions()->delete();
        });
    }
}
