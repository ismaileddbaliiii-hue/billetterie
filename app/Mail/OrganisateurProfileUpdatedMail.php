<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganisateurProfileUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $oldData;

    public function __construct(User $user, array $oldData)
    {
        $this->user = $user;
        $this->oldData = $oldData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✏️ Votre profil a été modifié',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.organisateurs.profile-updated',
        );
    }
}