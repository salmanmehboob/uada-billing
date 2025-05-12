<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminMiddleware
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
            if (Auth::user()->status == 0) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your Account has been Suspended');
            }
        }
        return $next($request);
    }
}
