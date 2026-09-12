<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\SystemModels\Auth\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;


class LoginController extends Controller
{

    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function loginForm()
    {
        $view = isMobile() ? 'auth.login-mobile' : 'auth.login';
        return view($view);
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $user = User::where('username', $username)->first();

        if (!$user) {
            throw ValidationException::withMessages(['login' => 'Undefined username']);
        }

        if (!password_verify($password, $user->password)) {
            throw ValidationException::withMessages(['login' => 'Wrong password']);
        }

        // if (is_null($user->email_validation_at)) {
        //     session(['pending_verification_user_id' => $user->id]);
        //     return redirect()->route('verification.notice');
        // }

        Auth::login($user);
        return redirect()->route('main');
    }
}
