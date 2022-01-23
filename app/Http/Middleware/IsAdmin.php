<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
        if(empty(Auth::user()) || Auth::user() == 'null')
        {
            return redirect('admin/login');
        }
        if (Auth::check() && Auth::user()->user_type == '0') {
            return $next($request);
        }
        else{
            return redirect('admin/login');
            }
        return redirect('admin/login');
    }
}
