<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class CheckIfAdminAuthenticated
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
      
        if (!Auth::user()->is_admin()) {
           // Auth::logout();
          // if($request->route()->getName()=='home')
            return redirect('/home')->with('error','Unauthorized Access');
        }

        return $next($request);
    }
}
