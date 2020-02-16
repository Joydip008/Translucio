<?php

namespace App\Http\Middleware;

use Closure;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            if (Auth::User()->role_id == 2) {
                return $next($request);
            } else {
                return back();
            }
        }
        else {
            return redirect('/');
        }
    }
}
