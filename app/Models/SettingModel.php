<?php
/**
* SettingModel 
*
* Model for Settings Table
* 
* @package    Laravel
* @subpackage Model
* @since      1.0
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    public $timestamps = false;
    protected $fillable = ['user_id','text_key','text_value','type','created_date','updated_date'];

}
