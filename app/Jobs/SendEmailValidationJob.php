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

class SendEmailValidationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user = null;

    public function __construct($user){
        $this->user = User::find($user);
    }

    public function handle() {
        if($user = $this->user) {
            if($email = $user->email) {
                Mail::to($email)->send(new ValidatorMail($user->id));
            }
        }
    }
}
