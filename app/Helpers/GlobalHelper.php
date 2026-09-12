<?php

use App\Libraries\Mqtt;
use App\SystemModels\Globals\Config;
use App\SystemModels\Globals\Upload;
use App\SystemModels\Globals\Log AS LogDB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

function isMobile(){
    $agent = new Jenssegers\Agent\Agent();
    return $agent->isMobile();
}

function getConfig($key=null){
    if($key){
        if($conf = Config::where('key', $key)->first()){
            return $conf->value;
        }
    }
    return null;
}

function fileUri($id, $localhost=false){
    if($id && $file = Upload::find($id)) {
        $uploadPath = config('filesystems.upload_dir');
        $uploadPath = $uploadPath ? $uploadPath.'/' : '';

        if(config('filesystems.upload_disk') == 'do_spaces'){
            $baseUrl = config('filesystems.disks.do_spaces.url');
            $basePath = config('filesystems.disks.do_spaces.root');
            $path = $baseUrl.'/'.$basePath.'/'.$uploadPath.$file->filename;
        }
        else if(config('filesystems.upload_disk') == 'gcs'){
            $baseUrl = config('filesystems.disks.gcs.storage_api_uri');
            $basePath = config('filesystems.disks.gcs.bucket').'/'.config('filesystems.disks.gcs.path_prefix');
            $path = $baseUrl.'/'.$basePath.'/'.$uploadPath.$file->filename;
        }
        else {
            if($localhost) $path = 'http://localhost/'.Storage::disk('public')->url($uploadPath.$file->filename);
            else $path = url(Storage::disk('public')->url($uploadPath.$file->filename));
        }
        return $path;
    }
    return null;
}

function glog($code,  $tag=null, $message=null){
    return LogDB::create([
        'code' => $code,
        'message' => $message,
        'tag' => $tag,
    ]);
}

function hasRoute($route=null, $or = true){
    $user = Auth::user();
    if(is_array($route)){
        $result = false;
        foreach ($route AS $val){
            if($or) {
                if($user->hasRoute($val)) return true;
            }
            else{
                if(!$user->hasRoute($val)) return false;
                else $result = true;
            }
        }
        return $result;
    }
    else if($user->hasRoute($route)) return true;
    return false;
}

function rangeWeek ($datestr) {
    date_default_timezone_set (date_default_timezone_get());
    $dt = strtotime ($datestr);
    return array (
        "start" => date ('N', $dt) == 1 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('last monday', $dt)),
        "end" => date('N', $dt) == 7 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('next sunday', $dt))
    );
}

function localFile($id){
    if($ufile = Upload::find($id)){
        $ufile = json_decode(json_encode($ufile));
        $ufile->pathfile = null;

        if(config('filesystems.upload_disk') == 'public') {
            $ufile->pathfile =  Storage::disk('public')->path("uploads/".$ufile->filename);
        }
        else{
            $tmpfile = Uuid::uuid1()->toString() . '.' . $ufile->extension;
            $tmppath = Storage::disk('local')->path("tmp/" . $tmpfile);
            $url = fileUri($ufile->id, true);
            if (Curl::to($url)->download($tmppath)) {
                $ufile->pathfile = $tmppath;
                return $ufile;
            }
            else unlink($tmppath);
        }
        return $ufile;
    }
    return null;
}

function qrcodeGenerate($text, $path=null){
    if($text){
        if(!$path){
            $uuid = Uuid::uuid1()->toString();
            $path = Storage::disk('local')->path("tmp/$uuid.svg");
        }
        QrCode::generate($text, $path);
        return $path;
    }
    return null;
}

function getHari($date){
    if($date){
        switch (date('N', strtotime($date))){
            case 1: return 'Senin';
            case 2: return 'Selasa';
            case 3: return 'Rabu';
            case 4: return 'Kamis';
            case 5: return 'Jumat';
            case 6: return 'Sabtu';
            case 7: return 'Minggu';
        }
    }
    return null;
}

function addDate($day=0, $date=null){
    $date = $date ? "$date 00:00:00" : date('Y-m-d H:i:s');
    return date('Y-m-d', strtotime("$day day", strtotime($date)));
}

function addDatetime($day=0, $date=null){
    $date = $date ? "$date 00:00:00" : date('Y-m-d H:i:s');
    return date('Y-m-d H:i:s', strtotime("$day day", strtotime($date)));
}

function tplBoxColor($text=null, $color=null, $cls='box-color'){
    if($text){
        return "<div class='$cls' style='background-color: #".$color."22; color: #$color;  border-color: #$color;'>$text</div>";
    }
    return '';
}

function tplBoxMap($lat=null, $long=null, $text='Buka Peta'){
    if($lat && $long){
        return "<table class='box-link-map' onclick='window.open(\"https://www.google.com/maps?q=$lat,$long\");'>
                    <tr>
                        <td><i class='icon bi bi-pin-map-fill'></i></td>
                        <td>
                            <div class='title'>$text</div>
                            <div class='subtitle'>Lat: $lat, Long: $long</div>
                        </td>
                    </tr>
                </table>";
    }
    return '';
}

function tplImageCircle($photo = null, $size = 40){
    $photo = $photo ? route('file', $photo) : asset('images/nouser.png');
    return "<div class='circle-photo' style='width: ".$size."px; height: ".$size."px; background-image: url(\"$photo\")'></div>";
}

function sendBroadcast($topic, $message){
    if($topic && $message){
        $response = Curl::to(config('site.socket_io_private_url'))
            ->withData(['topic' => $topic, 'message' => $message])
            ->asJson(true)
            ->post();
        if($response) return $response;
    }
    return null;
}

function mqttSirine($id, $payload){
    Mqtt::publish("cmnd/mfds/kab-bdg/sirine-station/$id/POWER", $payload);
}

function sendNotification($deviceToken, $message, $title=null) {
    $SERVER_API_KEY = config('site.fcm_server_key');
    $title = $title ?: config('app.name');

    $bodyMessage = [
        "title" => $title,
        "message" => $message
    ];

    if(is_array($deviceToken)) $data = ["registration_ids" => $deviceToken, "data" => $bodyMessage];
    else $data = ["to" => $deviceToken, "data" => $bodyMessage];

    $headers = ['Authorization: key=' . $SERVER_API_KEY, 'Content-Type: application/json'];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}


