<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

//use Auth;

class MentorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $host = $request->getHost();
            if ($host != env('MENTOR_SUBDOMAIN')) {
                return redirect()->route('login')->with('error', 'Sorry, you are not authorized to access this domain.');
            }

            if (!Auth::user()->hasAnyRole(['Mentorship'])) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Sorry, you are not authorized to access this domain.');
            }
            if (Auth::user()->status == 0) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your Account has been Suspended');
            }
        }
        return $next($request);
    }
}
