<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailActivationLink extends Mailable
{
    use Queueable, SerializesModels;

    public $activationLink;
    public $user;

    public function __construct($activationLink, $user)
    {
        $this->activationLink = $activationLink;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Activate Your Email Address')
            ->view('emails.activate-email')
            ->with([
                'activationLink' => $this->activationLink,
                'user' => $this->user,
            ]);
    }
}
