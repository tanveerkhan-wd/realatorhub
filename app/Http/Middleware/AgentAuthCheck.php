<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class AgentAuthCheck {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        
        if (Auth::user() == 'null' || empty(Auth::user()) || Auth::user() == '' || Auth::user() == null || Auth::user()->user_type != '2') {
            return redirect('agency/login');
        }

        return $next($request);
    }

}
