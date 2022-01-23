<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserModel extends Model implements AuthenticatableContract
{
    use Authenticatable;
    protected $table = 'users';
    public $timestamps = false;
    protected $softDelete = true;
    protected $dates = [
        'deleted_at',
    ];
    use SoftDeletes;

    protected $fillable = ['first_name','last_name','user_name','email','password','phone_code','phone','timezone',
        'user_type','admin_status','user_status','email_verified','is_setup','verification_code','verification_time',
        'email_notification', 'push_notification','created_by','updated_by'];

    const AGENCY_USER_TYPE = 1;
    const ACTIVE_ADMIN_STATUS = 1;
    const EMAIL_VERIFIED_YES = 1;
    const EMAIL_VERIFIED_NO = 0;
    const IS_SETUP_NO = 0;
    const IS_SETUP_YES = 1;

    public function agency(){
        return $this->hasOne(AgencyModel::class,'user_id','id');
    }
}
