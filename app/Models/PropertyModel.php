<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyModel extends Model
{
    protected $table='properties';
    protected $fillable=[
        'purpose','type','agency_id','agent_id','address','latitude','longitude','city','state','country','zipcode','main_image','description','beds','baths',
        'sq_feet','total_living_sqft','total_commercial_space','total_industrial_space','price','annual_tax','occupied','hoa','unit_amount','lot_sqft',
        'zoning','seo_title','seo_description','is_delete','created_by','updated_by','manual_address','address_type'
    ];
}
