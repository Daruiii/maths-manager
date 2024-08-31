<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AssignSheetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $exercisesSheet;

    public function __construct($exercisesSheet)
    {
        $this->exercisesSheet = $exercisesSheet;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $userName = User::find($this->exercisesSheet->user_id)->name;
        return new Envelope(
            subject: "Une fiche d'exercices vous a été assigné",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $userName = User::find($this->exercisesSheet->user_id)->name;
        return (new Content(
            view: 'vendor.notifications.sheet_assigned',
        ))->with('ds', $this->exercisesSheet)->with('userName', $userName);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}