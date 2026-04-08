<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssignTDMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $td;

    public function __construct($td)
    {
        $this->td = $td;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Une fiche d\'exercices vous a été assignée',
        );
    }

    public function content(): Content
    {
        $userName = User::find($this->td->user_id)?->name ?? '';
        return (new Content(
            view: 'vendor.notifications.sheet_assigned',
        ))->with('td', $this->td)->with('userName', $userName);
    }

    public function attachments(): array
    {
        return [];
    }
}
