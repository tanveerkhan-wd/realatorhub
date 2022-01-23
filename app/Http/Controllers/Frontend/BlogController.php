<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\BlogCategoryModel;
use App\Models\BlogPostCategory;
use Validator;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Models\SettingModel;

class BlogController extends Controller {

    public function index(Request $request) {
        $blog_data = BlogPost::where('status', '=', '1')
                ->where('is_deleted', '=', '0')
                ->orderBy('id', 'desc')
                ->paginate(3);

        $total_page = 0;
        $start_per_page = 0;

        if (!empty($blog_data)) {
            $total_page = $blog_data->lastPage();
        }

        if ($total_page > 1) {
            $start_per_page = 2;
        }
        $keywords = "blog_meta_";
        $blog_data_seo_all=  SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray();
        $blog_data_seo = array_column($blog_data_seo_all,'text_value','text_key');

        $blog_category_data = BlogCategoryModel::where('status', '1')->get()->toArray();
        if (request()->ajax()) {
            return \View::make('frontend.home.blog.blog_list', compact('blog_data', 'blog_category_data', 'total_page', 'start_per_page','blog_data_seo'))->renderSections();
        }
        return view('frontend.home.blog.blog_list', compact('blog_data', 'blog_category_data', 'total_page', 'start_per_page','blog_data_seo'));
    }

    public function getBlogList(Request $request) {
        $result = $request->all();

        $blog_data = BlogPostCategory::select('blog_post.*')
                ->join('blog_post', 'blog_post.id', '=', 'blog_post_category.blog_id')
                ->where('blog_post_category.category_id', '=', $result['blog_category_id'])
                ->where('blog_post.status', '1')->where('blog_post.is_deleted', '=', '0')
                ->orderBy('blog_post.id', 'desc')
                ->paginate(3);
        
        $view = view('frontend.home.blog.blog_list_ajax', compact('blog_data'))->render();
        $response['status'] = true;
        $response['message'] = 'Data retrived successfully.';
        $response['view'] = $view;
        return $response;
    }

    public function getloadmoreList(Request $request) {
        $result = $request->all();
        if (!empty($result['current_blog_category_id'])) {
             $blog_data = BlogPostCategory::select('blog_post.*')
                ->join('blog_post', 'blog_post.id', '=', 'blog_post_category.blog_id')
                ->where('blog_post_category.category_id', '=', $result['current_blog_category_id'])
                ->where('blog_post.status', '1')->where('blog_post.is_deleted', '=', '0')
                ->orderBy('blog_post.id', 'desc')
                ->paginate(3);
        } else {
            $blog_data = BlogPost::where('status', '=', '1')
                    ->where('is_deleted', '=', '0')
                    ->orderBy('id', 'desc')
                    ->paginate(3);
        }


        $view = view('frontend.home.blog.loadmore', compact('blog_data'))->render();
        $response['status'] = true;
        $response['message'] = 'Data retrived successfully.';
        $response['view'] = $view;
        return $response;
    }

    public function blogDetail(Request $request, $slug) {
        $blog_detail_data = BlogPost::where('slug', '=', $slug)->first();
        if (request()->ajax()) {
            return \View::make('frontend.home.blog.blog_detail', compact('blog_detail_data'))->renderSections();
        }
        return view('frontend.home.blog.blog_detail', compact('blog_detail_data'));
    }

}
