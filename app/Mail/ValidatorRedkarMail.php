<?php

namespace App\Mail;

use App\Models\Redkar;
use Illuminate\Mail\Mailable;

class ValidatorRedkarMail extends Mailable
{
    private $redkar = null;

    public function __construct($redkar){
        $this->redkar = Redkar::find($redkar);
    }

    public function build(){
        if($redkar = $this->redkar) {
            $url = route('validation.email.api', ['uid' => $redkar->id, 'code' => sha1($redkar->email_activation_code)]);
            return $this->view('mails.user_email_validation', [
                'url' => $url,
                'redkar' => $redkar
            ]);
        }
    }
}
