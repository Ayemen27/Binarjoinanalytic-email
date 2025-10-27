<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'avatar',
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


    public function sendPasswordResetNotification($token)
    {
        try {
            $this->notify(new \App\Notifications\AdminResetPasswordNotification($token));
        } catch (\Exception $e) {
            $msg = "The SMTP server is not configured. Please review your SMTP server settings and ensure that a proper SMTP server is set up.";
            sendNotification($msg, 'error', true, null, route('admin.settings.smtp'));
        }
    }
}
