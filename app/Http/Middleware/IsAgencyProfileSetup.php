<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAgencyProfileSetup
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
            return redirect('agency/login');
        }
        if (Auth::check() && Auth::user()->user_type == '1') {
            if(Auth::user()->email_verified == UserModel::EMAIL_VERIFIED_NO){
                return redirect('agency/email-verification');
            }
            else if(Auth::user()->is_setup == UserModel::IS_SETUP_NO){
                return redirect('agency/subscription-plans');
            }
            else {
                return $next($request);
            }
        }
        abort(404);
    }
}
