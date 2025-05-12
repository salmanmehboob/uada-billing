<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\BSIPCandidate;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails, RedirectsUsers;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify($id, $hash)
    {
        $user = User::find($id);
        //        if ($user && hash_equals($hash, sha1($user->getEmailForVerification()))) {
        if ($user) {

            BSIPCandidate::where('email', $user->email)->update(['status' => 1]);
            $user->markEmailAsVerified();
            return redirect()->route('candidateDashboard')->with('success', 'Your email address has been verified. You can now login.');
        }

        return redirect('/login')->with('error', 'Invalid verification link.');
    }

//    public function show(Request $request)
//    {
//        return $request->user()->hasVerifiedEmail()
//            ? redirect($this->redirectPath())
//            : view('auth.verify', [
//                'pageTitle' => __('Account Verification')
//            ]);
//    }
}
