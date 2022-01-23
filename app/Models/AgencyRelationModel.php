<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyRelationModel extends Model
{
    protected $table = 'agency_relation';
    protected $guarded = ['id'];
    public $timestamps = false;

    protected $fillable = ['agency_id','user_id','user_type'];

    Const AGENY_USER_TYPE = '1';


    public function user(){
        return $this->belongsTo(UserModel::class,'user_id','id');
    }
}
