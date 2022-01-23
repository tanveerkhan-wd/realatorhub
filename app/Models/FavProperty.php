<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavProperty extends Model
{
    protected $table='property_fav_unfav';
    protected $fillable=['user_id','property_id','created_date'];
}
