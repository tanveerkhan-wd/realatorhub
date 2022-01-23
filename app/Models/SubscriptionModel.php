<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SubscriptionModel extends Model
{
    protected $table='subscriptions';
    protected $guarded = ['id'];
    protected $fillable = ['user_id','plan_id','subscription_id','start_date','end_date','cancel_at',
        'total_no_of_agent','additional_agent','total_amount','base_price','additional_agents_counts','additional_agent_price','payment_type','invoice_url','status'];
    public $timestamps = true;

    const STATUS_ACTIVE = '1';
    const STATUS_CANCEL = '2';
    const STATUS_COMPLETED = '3';

    public function plan(){
        return $this->belongsTo(SubscriptionPlanModel::class,'plan_id','id');
    }
    public function user(){
        return $this->belongsTo(UserModel::class,'user_id','id');
    }

}
