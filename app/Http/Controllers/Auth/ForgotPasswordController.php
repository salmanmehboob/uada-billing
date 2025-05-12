<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgotMail;
use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

    public function ForgetPassword()
    {
        return view('auth.forget_password');
    }

    public function ForgetPasswordStore(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        session(['email' => $request->email]);
        Mail::to($request->email)->send(new ForgotMail($token));
        return back()->with('success', 'We have emailed your password reset link!');
    }

    public function ResetPassword($token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }

    public function ResetPasswordStore(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required'
        ]);

        $update = DB::table('password_resets')
            ->where('email', '=', $request->email)
            ->where('token', '=', $request->token)
            ->first();

//        dd($update);
        if (!$update) {
            return back()->withInput()->with('error', 'Invalid token! Kindly check your browser where you already login');
        }

        User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        // Delete password_resets record
        DB::table('password_resets')->where(['email' => $request->email])->delete();

        session()->regenerate();
        return redirect('/login')->with('success', 'Your password has been successfully changed!');
    }
}
