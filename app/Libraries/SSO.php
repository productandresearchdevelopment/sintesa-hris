<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Auth;

class SSO
{

    protected $basePath;
    protected $bearer;

    public function __construct()
    {
        $this->basePath = env('SSO_BASE_PATH', 'https://sso.sintesa-hris.com/api/');
        $this->bearer = env('SSO_BEARER');
    }

    public function checkSession($urlCallback)
    {
        $key = Str::uuid();
        Cookie::queue('session_key', $key);
        return redirect($this->basePath . "check/session?url_callback=$urlCallback&key=$key");
    }

    public function checkToken($token)
    {
        $response = Curl::to($this->basePath . "check/token")->withData(["token" => $token])
            ->asJson()
            ->withBearer($this->bearer)
            ->withTimeout(120)
            ->returnResponseObject()
            ->post();

        return $response->content ?? $response;
    }

    public function setSession($data, $user, $urlCallback)
    {
        $user->apps = $data->apps;
        Auth::guard()->login($user);
        //Put User apps in session, last till logout
        Session::put('apps', $data->apps);
        Session::put('token_sso', $data->token);
        Session::save();
        return redirect($this->basePath . "set/session?url_callback=" . $urlCallback . "&token=" . $data->token . "&uid=" . $data->uid);
    }

    public function login($username, $password, $user = null)
    {
        $response = Curl::to($this->basePath . 'login')->withData(['username' => $username, 'password' => $password])
            ->asJson()
            ->withBearer($this->bearer)
            ->withTimeout(120)
            ->returnResponseObject()
            ->post();

        return $response->content ?? $response;
    }

    public function logout($urlCallback)
    {
        Session::flush();
        return redirect($this->basePath . "logout?url_callback=" . $urlCallback);
    }
}
