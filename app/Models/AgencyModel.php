<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyModel extends Model
{
    protected $table = 'agency';
    public $timestamps = false;

    protected $fillable = ['user_id','agency_name','agency_logo','slug','stripe_customer_id'];

    public function user(){
        return $this->belongsTo(UserModel::class,'user_id','id');
    }
    public function agent(){
        return $this->hasMany(Age::class,'plan_id','id')->where('status',SubscriptionModel::STATUS_ACTIVE);
    }
}