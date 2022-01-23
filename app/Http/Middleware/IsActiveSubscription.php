<?php

namespace App\Http\Middleware;

use App\Models\SubscriptionModel;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsActiveSubscription
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
            $user_id = Auth::User()->id;
            $activeSubscription = SubscriptionModel::with(['plan'])
                ->where('user_id',$user_id)
                ->where('status',SubscriptionModel::STATUS_ACTIVE)
                ->first();
                if(!empty($activeSubscription)){
                    return $next($request);
                }
                else{
                    return redirect(route('agency.subscription'));
                }

        }
        abort(404);
    }
}
