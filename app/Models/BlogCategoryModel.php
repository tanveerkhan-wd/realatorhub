<?php
/**
* BlogCategoryModel 
*
* Model for Blog Category Table
* 
* @package    Laravel
* @subpackage Model
* @since      1.0
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategoryModel extends Model
{
    protected $table = 'blog_category';
    public $timestamps = false;
}
