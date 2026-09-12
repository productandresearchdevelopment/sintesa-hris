<?php

namespace App\Jobs;

use App\Libraries\Qfest\SendNotificationAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $actionId;

    public function __construct($actionId){
        $this->actionId = $actionId;
    }

    public function handle() {
        if($this->actionId) {
            SendNotificationAction::send($this->actionId);
        }
    }
}
