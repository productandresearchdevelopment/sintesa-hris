<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SystemModels\Auth\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $view = isMobile() ? 'auth.passwords.reset-mobile' : 'auth.passwords.reset';
        return view($view, ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->input('email'))
            ->where('remember_token', $request->input('token'))
            ->first();

        if (!$user || Carbon::now()->diffInMinutes($user->email_validation_sent_at) > 15) {
            return redirect()->back()->withErrors(['token' => 'Invalid or expired token.'])->withInput();
        }

        $user->password = bcrypt($request->input('password'));
        $user->email_validation_code = null;
        $user->email_validation_sent_at = null;
        $user->remember_token = null;
        $user->save();

        return redirect('/login')->with('status', 'Password has been successfully reset!');
    }
}
