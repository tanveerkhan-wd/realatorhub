<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyOtherImages extends Model
{
    protected $table='property_other_images';
    protected  $fillable=['property_id','image_name'];
}
