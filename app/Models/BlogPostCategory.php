<?php
/**
* BlogPostCategory 
*
* Model for Blog Post Category Table
* 
* @package    Laravel
* @subpackage Model
* @since      1.0
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPostCategory extends Model
{
    protected $table = 'blog_post_category';
    public $timestamps = false;
}
