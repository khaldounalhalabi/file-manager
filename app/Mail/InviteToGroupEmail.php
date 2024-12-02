<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteToGroupEmail extends Mailable
{
    private string $receiverEmail;
    private string $senderName;
    private string $senderEmail;
    private string $groupName;
    private string $token;
    private string $password;

    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(string $receiverEmail, string $senderName, string $senderEmail, string $groupName, string $token, string $password)
    {
        $this->receiverEmail = $receiverEmail;
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->groupName = $groupName;
        $this->token = $token;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to: [
                new Address($this->receiverEmail),
            ],
            replyTo: [
                new Address($this->senderEmail, $this->senderName),
            ],
            subject: "$this->senderName send you an invitation invitation to his files group",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invitation',
            with: [
                'sender_name' => $this->senderName,
                'group_name' => $this->groupName,
                'url' => route('v1.web.public.customer.accept.invitation') . "?invitation_token=$this->token",
                'password' => $this->password,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
