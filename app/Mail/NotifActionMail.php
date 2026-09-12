<?php

namespace App\Mail;

use App\Models\WorkOrders\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifActionMail extends Mailable
{
    use Queueable, SerializesModels;

    private $action = null;

    public function __construct($actionId){
        $this->actionId = Action::find($actionId);
    }

    public function build(){
        if($action = $this->actionId) {
            return $this->subject('SINTESA HRIS UPDATE WO ('.$action->wo->id.')')->view('mails.notification_action', [
                'action' => $action
            ]);
        }
    }
}
