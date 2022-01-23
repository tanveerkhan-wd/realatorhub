<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyVideo extends Model
{
    protected $table='property_videos';
    protected $fillable=['property_id','type','video_link'];
}
