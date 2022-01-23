<?php

namespace App\Http\Middleware;

use App\Models\UserModel;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\SubscriptionModel;
use App\Models\AgencyModel;
use App\Models\AgencyRelationModel;
use Session;
class CheckAgency
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
        
        $current_uri_segment = request()->segments(0);
        $current_uri_segment=$current_uri_segment[0];
        if(!empty(Session::get('slug'))){
            $oldSlug=Session::get('slug');
            if($oldSlug!=$current_uri_segment){
                Auth::logout();
                Session::flush();
            }
        }
        $userDetails=AgencyModel::select('user_id')->where('slug',$current_uri_segment)->first();
        $users=UserModel::where('id',$userDetails->user_id)->first();
        if(empty($users)){
            return redirect('/'.$current_uri_segment.'/login');
        }
        if (Auth::check() && Auth::user()->user_type == '3') {
            $customerCount=AgencyRelationModel::where('agency_id',$userDetails->user_id)->where('user_id',Auth::user()->id)->count();
            if($customerCount==0){
                Auth::logout();
                Session::flush();
                return redirect('/'.$current_uri_segment.'/login');
            }
        }
        $today=date('Y-m-d H:i:s');
        $isPlanActive=SubscriptionModel::where('user_id',$userDetails->user_id)->where('status',1)->orWhere(function($query) use ($today){
                $query->where('status',SubscriptionModel::STATUS_CANCEL);
                $query->where('cancel_at', '>',$today);
            })->count();
        //echo "<pre>"; print_r($isPlanActive); exit;
        \Illuminate\Support\Facades\Session::put('agency_id',$userDetails->user_id);
        \Illuminate\Support\Facades\Session::put('slug',$current_uri_segment);
        if ($isPlanActive>0) {
            return $next($request);
        }
        abort(404);
    }
}
