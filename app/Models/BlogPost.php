<?php
/**
* BlogPost 
*
* Model for Blog Post Table
* 
* @package    Laravel
* @subpackage Model
* @since      1.0
*/


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $table = 'blog_post';
    public $timestamps = false;
    protected $fillable=['title','slug','image','description','seo_meta_title','seo_meta_description','status','is_deleted','created_date','updated_date'];
}
