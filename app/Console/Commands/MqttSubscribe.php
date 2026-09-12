<?php

namespace App\Console\Commands;

use App\Libraries\Mqtt;
use App\Models\Station;
use Illuminate\Console\Command;

class MqttSubscribe extends Command
{
    protected $signature = 'mqtt:subscribe';
    protected $description = 'Service MQTT Subscribe';
    protected $codeLog = 'MQTT_RECEIVED';

    public function __construct() {
        parent::__construct();
    }

    public function handle()
    {
        echo "\033[32mMqtt Subscribe Running...\033[0m \n";

        $mqtt = new Mqtt();
        $mqtt->subscribe("#", function($topic, $payload){
            $topics = explode('/', $topic);
            if($topics[5] == 'RESULT' && $topics[0] == 'stat' && $topics[1] == 'mfds' &&  $topics[2] == config('mqtt.topic') && $topics[3] =='sirine-station') {
                $id = $topics[4];
                if($station = Station::find($id)){
                    $status = json_decode($payload);
                    $station->update(['sirine_status' => ($status->POWER == 'ON') ? 1 : 0 ]);
                    sendBroadcast('station', 'reload');
                }
            }
        });
    }

}
