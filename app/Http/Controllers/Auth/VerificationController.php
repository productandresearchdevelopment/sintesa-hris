<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SystemModels\Auth\User;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    protected $codeLifetime = 10;

    public function notice()
    {
        $userId = session('pending_verification_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired.');
        }

        $isCodeActive = $user->email_validation_code &&
            $user->email_validation_sent_at &&
            $user->email_validation_sent_at->gt(Carbon::now()->subMinutes($this->codeLifetime));

        if (!$isCodeActive) {
            $this->sendVerificationCode($user);
            $status = 'We have sent a new verification code to your email.';
        } else {
            $status = 'Enter the verification code sent to your email.';
        }

        return view('auth.verify-notice', compact('user', 'status'));
    }

    public function resend(Request $request)
    {
        $userId = session('pending_verification_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired.');
        }

        $this->sendVerificationCode($user);
        return back()->with('status', 'A new verification code has been sent to your email!');
    }

    public function check(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:10',
        ]);

        $userId = session('pending_verification_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Session expired.');
        }

        $expired = $user->email_validation_sent_at->lt(Carbon::now()->subMinutes($this->codeLifetime));
        if ($expired) {
            return back()->with('error', 'Your verification code has expired. Please resend a new one.');
        }

        if ($user->email_validation_code !== $request->code) {
            return back()->with('error', 'Invalid verification code.');
        }

        $user->email_validation_at = Carbon::now();
        $user->email_validation_code = null;
        $user->email_validation_sent_at = null;
        $user->save();

        session()->forget('pending_verification_user_id');

        return redirect()->route('login')->with('status', 'Email verified successfully! You can now login.');
    }

    protected function sendVerificationCode($user)
    {
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->email_validation_code = $code;
        $user->email_validation_sent_at = Carbon::now();
        $user->save();

        Mail::to($user->email)->send(new \App\Mail\EmailVerificationCodeMail($user));
    }
}
