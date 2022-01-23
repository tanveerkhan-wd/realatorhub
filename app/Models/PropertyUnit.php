<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyUnit extends Model
{
    protected $table='property_unit';
    protected  $fillable=['property_id','unit','beds','baths','sqft'];
}
