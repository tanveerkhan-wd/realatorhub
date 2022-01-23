<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyLeadsModel extends Model
{
    protected $table='property_leads';
    public $timestamps = false;
    protected $fillable=['agency_id','agent_id','user_id','property_id','address','customer_name','email','phone','note','message','status','is_delete'];
}
