<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PettyRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $reason;
    public $id;

    public function __construct($name, $email,$reason, $id)
    {
        $this->name = $name;
        $this->email = $email;
        $this->reason = $reason;
        $this->id = $id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'PettyCash Request',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.pettyrequest',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'reason' => $this->reason,
                'id' => $this->id,
            ],
        );
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
