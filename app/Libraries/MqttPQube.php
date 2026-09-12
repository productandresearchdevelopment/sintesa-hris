<?php


namespace App\Libraries;


class MqttPQube
{
    public static function resetToken($device){
        Mqtt::publish($device, json_encode(['action' => 'token_clear']));
    }

    public static function updateDevice($device){
        Mqtt::publish($device, json_encode(['action' => 'updated']));
    }

    public static function updateAllDevice($owner){
        Mqtt::publish($owner, json_encode(['action' => 'updated']));
    }

    public static function requestLog($device){
        Mqtt::publish($device, json_encode(['action' => 'request_log']));
    }

    public static function closeAlert($device){
        Mqtt::publish($device, json_encode(['action' => 'close_alert']));
    }

    public static function resetVpn($device){
        Mqtt::publish($device, json_encode(['action' => 'restart_vpn']));
    }

    public static function command($device, $message){
        Mqtt::publish($device, json_encode(['action' => 'command', 'message' => $message]));
    }

    public static function read($device, $message){
        Mqtt::publish($device, json_encode(['action' => 'read', 'message' => $message]));
    }
}
