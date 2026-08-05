<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $roleLabel,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Your OJT Tracker account has been approved');
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.account-approved',
            with: ['name' => $this->name, 'roleLabel' => $this->roleLabel],
        );
    }
}
