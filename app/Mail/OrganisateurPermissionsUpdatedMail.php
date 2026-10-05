<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrganisateurPermissionsUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $changedPermissions;

    public function __construct(User $user, array $changedPermissions)
    {
        $this->user = $user;
        $this->changedPermissions = $changedPermissions;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔑 Vos permissions ont été mises à jour',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.organisateurs.permissions-updated',
        );
    }
}