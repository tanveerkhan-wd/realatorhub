<?php
/**
* BlogCategoryController 
*
* This file is used for Blog Category
* 
* @package    Laravel
* @subpackage Controller
* @since      1.0
*/

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BlogCategoryModel;
use Validator;
use Auth;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
class BlogCategoryController extends Controller
{
    public function blogcategoryList(Request $request)
    {
        /**
         * Used for master Blog Post Category
         * @return redirect to Admin->Masters->Blog Post Category
         */
    	 return view('admin.blog_category.list');
    }
    public function blogcategoryAjax(Request $request)
    {
        /**
         * Used for master Blog Post Category AJAX
         * @return redirect to Admin->Masters->Blog Post Category
         */
    	$input = $request->all();
        $users = BlogCategoryModel::select('*')->orderby('id','desc');

        
        if(isset($input['title']) && !empty($input['title'])){
            $users = $users->where('title','LIKE','%'.$input['title'].'%');            
        }
        if(isset($input['status']) && $input['status']!=''){
            $users = $users->where('status','=',$input['status']);            
        }

        $table = Datatables::of($users)->make(true);
        return $table;
    }

     public function addblogcategory(Request $request)
     {
        /**
         * Used for master Blog Post Category Add
         * @return redirect to Admin->Masters->Blog Post Category Add
         */
    	 return view('admin.blog_category.add');
     }

    public function addblogcategoryPost(Request $request)
    {
        /**
         * Used for master Blog Post Category Add Save 
         * @return redirect to Admin->Masters->Blog Post Category Save
         */
        $input = $request->all();        
        $validator = Validator::make($request->all(), [            
            'title' => 'required',
            'status' => 'required',
        ]);
        
        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $blogcategory = new BlogCategoryModel;
        $blogcategory->title = $input['title'];
        $blogcategory->slug = Str::slug($input['title']);
        $blogcategory->status = $input['status'];       
        $blogcategory->created_date = Carbon::now();
        $blogcategory->created_by = Auth::user()->id;
        if($blogcategory->save()){
            return redirect('admin/blog-post-category')->with('success','Category Added Successfully.');
        }else{
            return redirect('admin/blog-post-category')->withErrors(['Something Went Wrong Please Try Again.']);
        }
    }

     public function deleteblogcategoryPost(Request $request,$id)
     {
        /**
         * Used for master Blog Post Category delete
         * @return redirect to Admin->Masters->Blog Post Category
         */
        $input = $request->all();

        $delete = BlogCategoryModel::where('id','=',$id)->delete();

        return 1;
    }

    public function activeInactive(Request $request,$id)
    {
        /**
         * Used for master Blog Post Category Active inactive
         * @return redirect to Admin->Masters->Blog Post Category
         */
        $input = $request->all();
        $about = BlogCategoryModel::findorfail($id);
        $about->status = (isset($about->status) && $about->status == '1')?'0':'1';
        $about->save();
        return 1;
        exit;
    }

     public function editblogcategory(Request $request,$id)
     {
        /**
         * Used for master Blog Post Category Edit
         * @return redirect to Admin->Masters->Blog Post Category Edit Page
         */
        $input = $request->all();
        $data = BlogCategoryModel::findorfail($id);
        return view('admin.blog_category.edit',compact('data'));   
    }

    public function editblogcategoryPost(Request $request,$id)
    {
        /**
         * Used for master Blog Post Category AJAX Edit Save
         * @return redirect to Admin->Masters->Blog Post Category
         */
        $input = $request->all();        
        $validator = Validator::make($request->all(), [           
            'title' => 'required',
            'status' => 'required',
        ]);
        
        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $blogcategory = BlogCategoryModel::findorfail($id);
        $blogcategory->title = $input['title'];
        $blogcategory->slug = Str::slug($input['title']);
        $blogcategory->status = $input['status'];       
        if($blogcategory->save()){
            return redirect('admin/blog-post-category')->with('success','Category Updated Successfully.');
        }else{
            return redirect('admin/blog-post-category')->withErrors(['Something Went Wrong Please Try Again.']);
        }
    }
}
