<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendLinkResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $resetLink;
    public $personalInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($resetLink, $personalInfo)
    {
        $this->resetLink = $resetLink;
        $this->personalInfo = $personalInfo;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Send Link Reset Password',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.send-link-reset-password',
            with: [
                'resetLink' => $this->resetLink,
                'personalInfo' => $this->personalInfo
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
