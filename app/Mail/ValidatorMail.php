<?php

namespace App\Mail;

use App\SystemModels\Auth\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Hash;

class ValidatorMail extends Mailable
{
    private $user = null;

    public function __construct($user){
        $this->user = User::find($user);
    }

    public function build(){
        if($user = $this->user) {
            $url = route('validation.email.api', ['uid' => $user->id, 'code' => sha1($user->email_validation_code)]);
            return $this->view('mails.user_email_validation', [
                'url' => $url,
                'user' => $user
            ]);
        }
    }
}
