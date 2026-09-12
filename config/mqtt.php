<?php

return [
    'topic'     => env('MQTT_TOPIC', ''),
    'host'      => env('MQTT_HOST', ''),
    'username'  => env('MQTT_USERNAME', ''),
    'password'  => env('MQTT_PASSWORD', ''),
    'port'      => env('MQTT_PORT', '1883'),
    'timeout'   => (int) env('MQTT_TIMEOUT', 10),
    'qos'       => env('MQTT_QOS', 0),
    'client_id' => env('MQTT_CLIENT_ID', 'WEBSERVER'),
    'debuging' => env('MQTT_DEBUGING', false),
];
