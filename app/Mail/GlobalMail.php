<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GlobalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;  // Email subject
    public $body;     // Email body content
    public $markdown; // Optional: whether to render the body using Markdown

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, $markdown = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->markdown = $markdown;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject // Use dynamic subject
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Content
     */
    public function content(): Content
    {

        if ($this->markdown) {
            return new Content(
                markdown: 'emails.global', // Use dynamic markdown view if provided
                with: ['body' => $this->body] // Pass the dynamic body to the view
            );
        } else {
            return new Content(
                view: 'emails.test', // Default view if markdown is not provided
                with: ['body' => $this->body] // Pass the dynamic body to the view
            );
        }
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
