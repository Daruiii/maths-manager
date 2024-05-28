<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AssignDSMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    protected $ds;

    public function __construct($ds)
    {
        $this->ds = $ds;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $userName = User::find($this->ds->user_id)->name;
        return new Envelope(
            subject: 'Un devoir surveillé vous a été assigné',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $userName = User::find($this->ds->user_id)->name;
        return (new Content(
            view: 'vendor.notifications.ds_assigned',
        ))->with('ds', $this->ds)->with('userName', $userName);
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