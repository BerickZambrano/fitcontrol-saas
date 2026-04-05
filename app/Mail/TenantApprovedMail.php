<?php

namespace App\Mail;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TenantApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Tenant $tenant) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Tu solicitud fue aprobada 🎉');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tenant-approved',
            with: [
                'url' => url('/register-admin/' . $this->tenant->register_token),
                'tenant' => $this->tenant,
            ]
        );
    }
}