<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Stripe\Subscription;

class SubscriptionPlanModel extends Model
{
    protected $table='subscription_plans';
    protected $guarded = ['id'];
    protected $fillable = ['plan_id','additional_price_id','plan_name','monthly_price',
        'no_of_agent','additional_agent','additional_agent_per_rate','description' ];
    public $timestamps = true;
    Const IS_DELETED_YES = '1';
    Const IS_DELETED_NO = '0';
    public function subscriptions(){
        return $this->hasMany(SubscriptionModel::class,'plan_id','id')->where('status',SubscriptionModel::STATUS_ACTIVE);
    }
    public function subscriptionsTotal(){
        $today=date('Y-m-d H:i:s');
        return $this->hasMany(SubscriptionModel::class,'plan_id')->where('status',SubscriptionModel::STATUS_ACTIVE)->orWhere(function($query) use ($today){
                $query->where('status',SubscriptionModel::STATUS_CANCEL);
                $query->where('cancel_at', '>',$today);
            });
    }


}
