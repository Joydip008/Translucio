<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class CheckIfUserAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      
        if (Auth::user()->role_id!=2) {
            //Auth::logout();
           // if($request->route()->getName()=='admin_dashboard')
            
            return redirect()->route('admin_dashboard')->with('error','Unauthorized Access');
        }

        return $next($request);
    }
}
