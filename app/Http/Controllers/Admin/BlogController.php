<?php
/**
* BlogController 
*
* This file is used for Blog in Admin->Masters->blog
* 
* @package    Laravel
* @subpackage Controller
* @since      1.0
*/

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BlogCategoryModel;
use App\Models\BlogPost;
use App\Models\BlogPostCategory;
use Validator;
use Auth;
use Carbon\Carbon;
use DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function blogpostList(Request $request)
    {
         /**
         * Used for master Blog List
         * @return redirect to Admin->Masters->Blog
         */
    	 return view('admin.blog_post.list');
    }
    public function blogpostAjax(Request $request)
    {
        /**
         * Used for master Blog List AJAX
         * @return redirect to Admin->Masters->Blog
         */
    	$input = $request->all();
        $users = BlogPost::select('*')->orderby('id','desc');

        
        if(isset($input['title']) && !empty($input['title'])){
            $users = $users->where('title','LIKE','%'.$input['title'].'%');            
        }
        if(isset($input['status']) && $input['status']!=''){
            $users = $users->where('status','=',$input['status']);            
        }

        $table = Datatables::of($users)->make(true);
        return $table;
    }

     public function addblogpost(Request $request)
     {
        /**
         * Used for master Blog Add get all active blog category and send to add page
         * @return redirect to Admin->Masters->Blog
         */
     	 $blog_category = BlogCategoryModel::where('status','1')->get()->toArray();
    	 return view('admin.blog_post.add',compact('blog_category'));
    }

    public function addblogpostPost(Request $request)
    {
        /**
         * Used for master Blog Add Save
         * @return redirect to Admin->Masters->Blog
         */
        $input = $request->all();        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
            'seo_title' => 'required',
            'seo_description'=>'required',
            'blog_image'=>'required',
            'blog_category'=>'required',	
        ]);
        
        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
       
        if(isset($input['blog_image']) && $input['blog_image'] != null && !empty($input['blog_image'])){
            
            $file = $request->file('blog_image');
            $destinationPath = 'public/uploads/blog_image';
            $blog_image = time().$file->getClientOriginalName();
            $file->move($destinationPath,$blog_image);
        }

        $blog = new BlogPost;
        $blog->title = $input['title'];        
        $blog->slug = '';        
        $blog->status = $input['status'];        
        $blog->description = (isset($input['descripition']) && !empty($input['descripition']))?$input['descripition']:'';
        $blog->image =$blog_image;
        $blog->seo_meta_title = $input['seo_title'];
        $blog->seo_meta_description = $input['seo_description'];
        $blog->created_date = Carbon::now();
        if($blog->save()){

            $count = BlogPost::where('slug','=',Str::slug($input['title']))->count();
            if($count == 0){
                $slug = Str::slug($input['title']);
            }else{
                $slug = Str::slug($input['title']).'-'.$blog->id;
            }

            $blog->slug =  $slug;
            $blog->save();
        	foreach ($input['blog_category'] as $key => $value) 
        	{
        		$blogCategory = new BlogPostCategory;
	        	$blogCategory->blog_id = $blog['id'];
	        	$blogCategory->category_id	 = $value;
	        	$blogCategory->save();
        	}

        	

            return redirect('admin/blog-post')->with('success','Blog Added Successfully.');
        }else{
            return redirect('admin/blog-post')->withErrors(['Something Went Wrong Please Try Again.']);
        }
    }

     public function deleteblogpostPost(Request $request,$id)
     {
        /**
         * Used for master Blog Delete
         * @return redirect to Admin->Masters->Blog
         */
        $input = $request->all();
        $delete = BlogPost::where('id','=',$id)->delete();
        \DB::table('blog_post_category')->where('blog_id',$id)->delete();
        return 1;
    }

    public function activeInactive(Request $request,$id)
    {
        /**
         * Used for master Blog Active Inactive
         * @return redirect to Admin->Masters->Blog
         */
        $input = $request->all();
        $about = BlogPost::findorfail($id);
        $about->status = (isset($about->status) && $about->status == '1')?'0':'1';
        $about->save();
        return 1;
        exit;
    }

     public function editblogpost(Request $request,$id)
     {
        /**
         * Used for master Blog Edit get all blogcategory and detail
         * @return redirect to Admin->Masters->Blog
         */
        $input = $request->all();
        $blog_category = BlogCategoryModel::get()->toArray();
        $blog_category_selected = BlogPostCategory::where('blog_id','=',$id)->pluck('category_id')->toArray();
        $data = BlogPost::findorfail($id);
        return view('admin.blog_post.edit',compact('data','blog_category','blog_category_selected'));   
    }

    public function editblogpostPost(Request $request,$id)
    {
        /**
         * Used for master Blog Edit Save
         * @return redirect to Admin->Masters->Blog
         */
        $input = $request->all();        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
            'seo_title' => 'required',
            'seo_description'=>'required',            
            'blog_category'=>'required',    
        ]);
        
        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
       
        if(isset($input['blog_image']) && $input['blog_image'] != null && !empty($input['blog_image'])){
            
            $file = $request->file('blog_image');
            $destinationPath = 'public/uploads/blog_image';
            $blog_image = time().$file->getClientOriginalName();
            $file->move($destinationPath,$blog_image);
        }else{
            $blog_image = $input['old_image'];
        }

        $blog = BlogPost::findorfail($id);
        $blog->title = $input['title'];        
        $blog->status = $input['status'];        
        $blog->description = (isset($input['descripition']) && !empty($input['descripition']))?$input['descripition']:'';
        $blog->image =$blog_image;
        $blog->seo_meta_title = $input['seo_title'];
        $blog->seo_meta_description = $input['seo_description'];
        if($blog->save()){
           
            $count = BlogPost::where('id','!=',$id)->where('slug','=',Str::slug($input['title']))->count();
            if($count == 0){
                $slug = Str::slug($input['title']);
            }else{
                $slug = Str::slug($input['title']).'-'.$blog->id;
            }

            $blog->slug =  $slug;
            $blog->save();

             \DB::table('blog_post_category')->where('blog_id',$id)->delete();
            foreach ($input['blog_category'] as $key => $value) 
            {
                $blogCategory = new BlogPostCategory;
                $blogCategory->blog_id = $blog['id'];
                $blogCategory->category_id   = $value;
                $blogCategory->save();
            }

            return redirect('admin/blog-post')->with('success','Blog Updated Successfully.');
        }else{
            return redirect('admin/blog-post')->withErrors(['Something Went Wrong Please Try Again.']);
        }
    }
}
