<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FitControlMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $body;

    public function __construct(string $nombre, string $body)
    {
        $this->nombre = $nombre;
        $this->body = $body;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject ?? 'FitControl',
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.fitcontrol',
        );
    }
}
