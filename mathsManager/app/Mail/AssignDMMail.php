<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AssignDMMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $dm;

    public function __construct($dm)
    {
        $this->dm = $dm;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Un devoir maison vous a été assigné',
        );
    }

    public function content(): Content
    {
        $userName = User::find($this->dm->user_id)?->name ?? '';
        return (new Content(
            view: 'vendor.notifications.sheet_assigned',
        ))->with('dm', $this->dm)->with('userName', $userName);
    }

    public function attachments(): array
    {
        return [];
    }
}
