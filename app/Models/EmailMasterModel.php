<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;

use Illuminate\Database\Eloquent\Model;

class EmailMasterModel extends Model
{
    protected $table = 'email_master';
    public $timestamps = false;
}
