<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable)
    {

        $verificationUrl = $this->verificationUrl($notifiable);

        // dd($verificationUrl);

        $template = mailTemplate('email_verification');

        $short_codes = [
            'link' => $verificationUrl,
            'website_name'    => config('app.name'),
            'website_url'     => config('app.url'),
        ];

        $subject = replace_shortcodes($template->subject, $short_codes);
        $body = replace_shortcodes($template->body, $short_codes);

        return (new MailMessage)->subject($subject)
            ->markdown('emails.global', ['body' => $body]);
    }
}
