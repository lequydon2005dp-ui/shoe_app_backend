<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Yêu cầu đặt lại mật khẩu',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.password-reset',
            with: ['url' => $this->url],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}