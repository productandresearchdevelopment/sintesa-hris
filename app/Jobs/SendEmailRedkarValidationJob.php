<?php

namespace App\Jobs;

use App\Mail\ValidatorMail;
use App\SystemModels\Auth\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailRedkarValidationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $redkar = null;

    public function __construct($redkar){
        $this->redkar = User::find($redkar);
    }

    public function handle() {
        if($redkar = $this->redkar) {
            if($email = $redkar->email) {
                Mail::to($email)->send(new ValidatorMail($redkar->id));
            }
        }
    }
}
