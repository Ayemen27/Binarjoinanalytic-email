<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        // dd($verificationUrl);

        $template = mailTemplate('reset_password');

        $short_codes = [
            'link' => $resetUrl,
            'expiry_time' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
            'website_name'    => config('app.name'),
            'website_url'     => config('app.url'),
        ];

        $subject = replace_shortcodes($template->subject, $short_codes);
        $body = replace_shortcodes($template->body, $short_codes);

        return (new MailMessage)->subject($subject)
            ->markdown('emails.global', ['body' => $body]);


        // Generate the reset URL



    }
}