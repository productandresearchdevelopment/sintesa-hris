<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendLinkResetPassword;
use App\SystemModels\Auth\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail as Mailer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        $view = isMobile() ? 'auth.passwords.email-mobile' : 'auth.passwords.email';
        return view($view);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'User tidak ditemukan.'])->withInput();
        }

        if (is_null($user->email_validation_at)) {
            return redirect()->back()->withErrors(['email' => 'Email belum diverifikasi. Silakan verifikasi email terlebih dahulu.'])->withInput();
        }

        $token = Str::random(60);
        $user->email_validation_code = $token;
        $user->remember_token = $token;
        $user->email_validation_sent_at = Carbon::now();
        $user->save();

        $resetLink = url('/password/reset/' . $token . '?email=' . $user->email);

        Mailer::to($user->email)->send(new SendLinkResetPassword($resetLink, $user));

        return redirect()->back()->with('status', 'Password reset link telah dikirim ke email Anda!');
    }
}
