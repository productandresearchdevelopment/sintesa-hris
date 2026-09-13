<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\SSO;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\SystemModels\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Str;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';
    protected $sso;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->sso = new SSO();
    }

    public function username()
    {
        return 'username';
    }

    public function checkSession($request)
    {
        $data = [];
        if ($request->input('username')) $data['username'] = $request->input('username');
        if ($request->input('password')) $data['password'] = $request->input('password');
        if ($request->input('token')) $data['token'] = $request->input('token');

        $user = $request->input('username') ? User::with('role')->where(['username' => $data['username']])->first() : null;
        $ssoBasePath = env('SSO_BASE_PATH', 'https://sso.sintesa-hris.com/api/');
        $response = Curl::to($ssoBasePath . 'login')->withData($data)
            ->asJson()
            ->withBearer('abab')
            ->withTimeout(120)
            ->returnResponseObject()
            ->post();

        if ($user) {
            Auth::guard()->login($user);
            $key = Str::uuid();
            Cookie::queue('session_key', $key);
            $url = $request->url();
            return redirect($ssoBasePath . 'sso?url=' . $url . "&key=$key&token=" . $response->data->token_sso);
        } else {
            if (isset($data['password'])) {
                return $this->sendFailedLoginResponse($request, $response->message);
            } else {
                if (isMobile()) return view('auth.login-mobile');
                else return view('auth.login');
            }
        }
    }

    public function loginForm(Request $request)
    {
        if (env('SSO_ENABLED', false)) {
            if ($sessionKey = $request->input('key')) {
                if (Cookie::get('session_key') == $sessionKey) {

                    if ($token = $request->input('token')) {
                        $response = $this->sso->checkToken($token);
                        if (isset($response->success)) {
                            $user = User::where(['username' => $response->data->username])->first();
                            $url = $request->url();
                            return $this->sso->setSession($response->data, $user, $url);
                        }
                    }
                    if (isMobile()) return view('auth.login-mobile');
                    else return view('auth.login');
                }
                abort(403, "Invalid Key");
            } else {
                $route = route('login');
                return $this->sso->checkSession($route);
            }
        }

        if (isMobile()) return view('auth.login-mobile');
        else return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $username = $request->username;
        $password = $request->password;

        $user = User::where('username', $username)->first();

        if (!$user) {
            return $this->sendFailedLoginResponse($request, 'Username tidak terdaftar');
        }

        if (env('SSO_ENABLED', false)) {
            if (is_null($user->email_validation_at)) {
                session(['pending_verification_user_id' => $user->id]);
                return redirect()->route('verification.notice');
            }

            $response = $this->sso->login($username, $password, $user);
            if (isset($response->success) && $response->success) {
                $url = url("/");
                return $this->sso->setSession($response->data, $user, $url);
            } else {
                if (isset($response->message)) {
                    $rawMessage = $response->message;
                    $message = match (true) {
                        str_contains(strtolower($rawMessage), 'password') => 'Password salah',
                        str_contains(strtolower($rawMessage), 'user') => 'Username tidak ditemukan',
                        default => 'Gagal login, silakan coba lagi'
                    };
                    return $this->sendFailedLoginResponse($request, $message);
                }
            }

            $rawMessage = data_get($response, 'content.message', '');
            $message = match (true) {
                str_contains(strtolower($rawMessage), 'password') => 'Password salah',
                str_contains(strtolower($rawMessage), 'user') => 'Username tidak ditemukan',
                default => 'Gagal login, silakan coba lagi'
            };

            return $this->sendFailedLoginResponse($request, $message);
        }

        // Direct DB Authentication
        if (!Hash::check($password, $user->password) && !password_verify($password, $user->password)) {
            return $this->sendFailedLoginResponse($request, 'Password salah');
        }

        if (is_null($user->email_validation_at)) {
            session(['pending_verification_user_id' => $user->id]);
            return redirect()->route('verification.notice');
        }

        Auth::guard()->login($user);
        return redirect()->intended($this->redirectTo);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $user->update(['last_module' => null, 'last_url' => null]);
        }
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if (env('SSO_ENABLED', false)) {
            $url = url("/");
            return $this->sso->logout($url);
        }

        return redirect()->route('login.form');
    }

    protected function sendFailedLoginResponse(Request $request, $message)
    {
        throw ValidationException::withMessages(['login' => $message]);
    }
}
