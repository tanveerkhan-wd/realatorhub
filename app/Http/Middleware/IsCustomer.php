<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Session;

class IsCustomer
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
        $slug=Session::get('slug');
        if(empty(Auth::user()) || Auth::user() == 'null')
        {
            return redirect('/'.$slug.'/login');
        }
        if (Auth::check() && Auth::user()->user_type == '3') {
            return $next($request);
        }
        else{
            return redirect('/'.$slug.'/login');
            }
        return redirect('/'.$slug.'/login');
    }
}
