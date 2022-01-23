<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAgency
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
        //echo "<pre>"; print_r(Auth::user()->toArray()); exit;
        if(empty(Auth::user()) || Auth::user() == 'null')
        {
            return redirect('agency/login');
        }
        if (Auth::check() && Auth::user()->user_type != '1') {
            return redirect('agency/login');
        }
        if (Auth::check() && !empty(Auth::user()->deleted_at)) {
            return redirect('agency/login');
        }
        if (Auth::check() && Auth::user()->user_type == '1') {
            return $next($request);
        }
        return redirect('agency/login');
        abort(404);
    }
}
