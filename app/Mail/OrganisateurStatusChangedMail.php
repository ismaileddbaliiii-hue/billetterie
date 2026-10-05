<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganisateurStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $newStatus;

    public function __construct(User $user, bool $newStatus)
    {
        $this->user = $user;
        $this->newStatus = $newStatus;
    }

    public function envelope(): Envelope
    {
        $subject = $this->newStatus 
            ? '✅ Votre compte a été activé' 
            : '⚠️ Votre compte a été suspendu';
        
        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.organisateurs.status-changed',
        );
    }
}