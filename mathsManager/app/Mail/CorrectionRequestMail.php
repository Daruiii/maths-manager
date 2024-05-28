<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class CorrectionRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $correctionRequest;

    public function __construct($correctionRequest)
    {
        $this->correctionRequest = $correctionRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $userName = User::find($this->correctionRequest->user_id)->name;
        return new Envelope(
            subject: 'Demande de correction de ' . $userName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $userName = User::find($this->correctionRequest->user_id)->name;
        return (new Content(
            view: 'vendor.notifications.correction_request',
        ))->with('correctionRequest', $this->correctionRequest)->with('userName', $userName);
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
