<?php

namespace App\Libraries;

use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class Mqtt
{
    protected $server;
    protected $port;
    protected $username;
    protected $password;
    protected $timeout;
    protected $qos;
    protected $clientId;

    public function __construct(){
        $this->host     = config('mqtt.host');
        $this->port     = config('mqtt.port');
        $this->username = config('mqtt.username');
        $this->password = config('mqtt.password');
        $this->timeout  = config('mqtt.timeout');
        $this->qos      = config('mqtt.qos');
        $this->clientId = config('mqtt.client_id');
        $this->debuging = config('mqtt.debuging');
    }

    public function connect() {
        if($this->debuging) echo "$this->host, $this->port, $this->clientId, $this->username, $this->password, $this->timeout, $this->qos \n";

        $setting = (new ConnectionSettings())
            ->setUsername($this->username)
            ->setPassword($this->password)
            ->setKeepAliveInterval($this->timeout)
            ->setLastWillQualityOfService($this->qos);

        return $setting;
    }

    public function subscribe($topic, $action, $clientId=null, $loop = true) {
        $conn = $this->connect();
        $this->clientId = $clientId ?: $this->clientId;
        $mqtt = new MqttClient($this->host, $this->port, $this->clientId);
        try{
            $mqtt->connect($conn);
            $mqtt->subscribe($topic, function ($topic, $message) use ($action) {
                if($this->debuging) {
                    echo "\n\033[32m".date('Y-m-d H:i:s')."\033[0m\n";
                    echo "\033[32mTOPIC: \033[0m".$topic."\n";
                    echo "\033[32mMESSAGE: \033[0m".$message."\n";
                    echo "\033[32m-------------------------------------------------- \033[0m\n";
                }
                if($action) $action($topic, $message);
            }, $this->qos);
            if($loop) $mqtt->loop(true);
        }
        catch (\Exception $e){
            echo "\033[32mERROR: \033[0m\n";
            echo "\033[32mHOST: \033[0m".$this->host."\n";
            echo "\033[32mUSER: \033[0m".$this->username."\n";
        }

    }

    public function published($topic, $payload, $clientId=null)
    {
        $conn = $this->connect();
        $this->clientId = $clientId ?: $this->clientId;
        $mqtt = new MqttClient($this->host, $this->port, $this->clientId);
        $mqtt->connect($conn);
        $mqtt->publish($topic, $payload, 0);
        $mqtt->disconnect();
    }

    public static function publish($topic, $payload, $clientId=null)
    {
        $mqtt = new Mqtt();
        $mqtt->published($topic, $payload, $clientId."-PUB");
        return true;
    }

}
