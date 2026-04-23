<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCreatedAccountCredentials extends Mailable
{
    use SerializesModels;

    public function __construct(
        public User $user,
        public string $username,
        public string $password,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Credentials',
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.account-credentials',
            with: [
                'user' => $this->user,
                'username' => $this->username,
                'password' => $this->password,
            ],
        );
    }
}
