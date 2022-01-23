<?php

namespace App\Http\Controllers\Admin;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class HomeController extends Controller
{
    public function index(Request $request) {
        $user_id = Auth::user()->id;
        $data = SettingModel::where('user_id',$user_id)->get()->toArray();
        //echo "<pre>"; print_r($data); exit;
        // $category_data = CategoryMaster::get()->toArray();
        return view('admin.setting.home.logo_and_banner', compact('data'));
    } 

    public function addLogobanner(Request $request) {
    	$result = $request->all();
    	//echo "<pre>"; print_r($result); exit;
        $message = 'Something went to wrong';
        $validator = Validator::make($request->all(), [
            'home_website_banner' => 'mimes:jpg,jpeg,png',
            'home_banner_heading' => 'required',
            // 'home_facebook_url_link' => 'required',
            // 'home_twitter_url_link' => 'required',
            // 'home_linkedin_url_link' => 'required',
            // 'home_instagram_url_link' => 'required',
        ]);

        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        if(count($result) > 0) {    
            $login_user_id = Auth::user()->id;
            $submit_data =  array(
                'home_banner_heading' => $result['home_banner_heading'],
                'home_video_url_type' => $result['home_video_url_type'],
                'home_banner_description' => $result['home_banner_description'],
                'home_facebook_url_link' =>  $result['home_facebook_url_link'],
                'home_twitter_url_link' =>  $result['home_twitter_url_link'],
                'home_linkedin_url_link' =>  $result['home_linkedin_url_link'],
                'home_instagram_url_link' =>  $result['home_instagram_url_link'],
                'home_youtube_url_link' =>  $result['home_youtube_url_link'],
            );

            $updated_date = date('Y-m-d H:i:s');
            $keywords = "home_";
            $setting_home_data = SettingModel::where('user_id',1)->where('text_key','LIKE','%'.$keywords.'%')->get()->toArray(); 
            $single_setting_home_Data = array_column($setting_home_data,'text_value','text_key');


            if(array_key_exists('home_website_banner', $result)) {
                $home_website_banner = $result['home_website_banner'];
                $destinationPath = public_path().'/uploads/setting/logobanner/';
                $home_website_banner_name = "websitebanner".uniqid().Auth::user()->id.'.'.$home_website_banner->getClientOriginalExtension();
                $home_website_banner->move($destinationPath,$home_website_banner_name);
                @unlink(url('public/uploads/setting/logobanner/'.$single_setting_home_Data['home_website_banner']));
                SettingModel::updateOrCreate(
                    ['text_key' => 'home_website_banner','user_id' => 1],
                    [ 'text_value' => $home_website_banner_name,'type'=>'image', 'created_date' => $updated_date]
                );
            }
            if($result['home_video_url_type']==2){
            	if (isset($result['home_video_url']) && $result['home_video_url'] != null && !empty($result['home_video_url'])) {
	            	$home_website_banner = $result['home_video_url'];
	                $destinationPath = public_path().'/uploads/setting/home_page_video/';
	                $home_website_banner_name = "homepagevideo".uniqid().Auth::user()->id.'.'.$home_website_banner->getClientOriginalExtension();
	                $home_website_banner->move($destinationPath,$home_website_banner_name);
	                @unlink(url('public/uploads/setting/home_page_video/'.$single_setting_home_Data['home_video_url']));
	                SettingModel::updateOrCreate(
	                    ['text_key' => 'home_video_url','user_id' => 1],
	                    [ 'text_value' => $home_website_banner_name,'type'=>'image', 'created_date' => $updated_date]
	                );
	            }
            }else{
            	if (isset($result['home_video_url']) && $result['home_video_url'] != null && !empty($result['home_video_url'])) {
	            	SettingModel::updateOrCreate(
	                    ['text_key' => 'home_video_url','user_id' => 1],
	                    [ 'text_value' => $result['home_video_url'],'type'=>'text', 'created_date' => $updated_date]
	                );
	            }
            }
            
            foreach($submit_data as $key=>$value) {
                SettingModel::updateOrCreate(
                    ['text_key' => $key,'user_id' => 1],
                    [ 'text_value' => $value,'type'=>'text', 'created_date' => $updated_date]
                );
            }
            return redirect('admin/settings?data=home_banner_data')->with('success', 'Home banner detail and social icon updated successfully.');
        }

        return apiErrorResponse($message);
    }
    public function addWhyRealtorHubs(Request $request) {
        $message = 'Something went to wrong';
        $validator = Validator::make($request->all(), [
            'hom_why_realtor_hubs_heading' => 'required',
            'hom_why_realtor_hubs_image_one' => 'mimes:jpg,jpeg,png',
            'hom_why_realtor_hubs_title_one' => 'required',
            'hom_why_realtor_hubs_description_one' => 'required',
            'hom_why_realtor_hubs_image_two' => 'mimes:jpg,jpeg,png',
            'hom_why_realtor_hubs_title_two' => 'required',
            'hom_why_realtor_hubs_description_two' => 'required',
            'hom_why_realtor_hubs_image_three' => 'mimes:jpg,jpeg,png',
            'hom_why_realtor_hubs_title_three' => 'required',
            'hom_why_realtor_hubs_description_three' => 'required',
            'hom_why_realtor_hubs_image_four' => 'mimes:jpg,jpeg,png',
            'hom_why_realtor_hubs_title_four' => 'required',
            'hom_why_realtor_hubs_description_four' => 'required',
            'hom_why_realtor_hubs_image_five' => 'mimes:jpg,jpeg,png',
            'hom_why_realtor_hubs_title_five' => 'required',
            'hom_why_realtor_hubs_description_five' => 'required',
            'hom_why_realtor_hubs_image_six' => 'mimes:jpg,jpeg,png',
            'hom_why_realtor_hubs_title_six' => 'required',
            'hom_why_realtor_hubs_description_six' => 'required',
        ]);

        if ($validator->fails()) {            
            return redirect('admin/settings?data=whyRealtorHubs')
                        ->withErrors($validator)
                        ->withInput();
        }

        $result = $request->all();

        if(count($result) > 0) {    
            $login_user_id = Auth::user()->id;
            $submit_data =  array(
                'hom_why_realtor_hubs_heading' => $result['hom_why_realtor_hubs_heading'],
                'hom_why_realtor_hubs_title_one' =>  $result['hom_why_realtor_hubs_title_one'],
                'hom_why_realtor_hubs_description_one' =>  $result['hom_why_realtor_hubs_description_one'],
                'hom_why_realtor_hubs_title_two' =>  $result['hom_why_realtor_hubs_title_two'],
                'hom_why_realtor_hubs_description_two' =>  $result['hom_why_realtor_hubs_description_two'],
                'hom_why_realtor_hubs_title_three' =>  $result['hom_why_realtor_hubs_title_three'],
                'hom_why_realtor_hubs_description_three' =>  $result['hom_why_realtor_hubs_description_three'],
                'hom_why_realtor_hubs_title_four' =>  $result['hom_why_realtor_hubs_title_four'],
                'hom_why_realtor_hubs_description_four' =>  $result['hom_why_realtor_hubs_description_four'],
                'hom_why_realtor_hubs_title_five' =>  $result['hom_why_realtor_hubs_title_five'],
                'hom_why_realtor_hubs_description_five' =>  $result['hom_why_realtor_hubs_description_five'],
                'hom_why_realtor_hubs_title_six' =>  $result['hom_why_realtor_hubs_title_six'],
                'hom_why_realtor_hubs_description_six' =>  $result['hom_why_realtor_hubs_description_six'],
            );

            $updated_date = date('Y-m-d H:i:s');
            $user_id=Auth::user()->id;
            $setting_home_data = SettingModel::where('user_id',$user_id)->get()->toArray(); 
            $single_setting_home_Data = array_column($setting_home_data,'text_value','text_key');
        
            if(array_key_exists('hom_why_realtor_hubs_image_one', $result)) {

                $image = $result['hom_why_realtor_hubs_image_one'];
                $destinationPath = public_path().'/uploads/setting/why_realtor_hubs/';
                $image_name = "whyrealtorhubs".uniqid().Auth::user()->id.'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath,$image_name);
                @unlink(url('public/uploads/setting/why_realtor_hubs/'.$single_setting_home_Data['hom_why_realtor_hubs_image_one']));
                SettingModel::updateOrCreate(
                    ['text_key' => 'hom_why_realtor_hubs_image_one','user_id' => 1],
                    [ 'text_value' => $image_name,'type'=>'image', 'created_date' => $updated_date]
                );
            }

            if(array_key_exists('hom_why_realtor_hubs_image_two', $result)) {
                $image = $result['hom_why_realtor_hubs_image_two'];
                $destinationPath = public_path().'/uploads/setting/why_realtor_hubs/';
                $image_name = "whyrealtorhubs".uniqid().Auth::user()->id.'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath,$image_name);
                @unlink(url('public/uploads/setting/why_realtor_hubs/'.$single_setting_home_Data['hom_why_realtor_hubs_image_two']));
                SettingModel::updateOrCreate(
                    ['text_key' => 'hom_why_realtor_hubs_image_two','user_id' => 1],
                    [ 'text_value' => $image_name,'type'=>'image', 'created_date' => $updated_date]
                );
            }
            if(array_key_exists('hom_why_realtor_hubs_image_three', $result)) {
                $image = $result['hom_why_realtor_hubs_image_three'];
                $destinationPath = public_path().'/uploads/setting/why_realtor_hubs/';
                $image_name = "whyrealtorhubs".uniqid().Auth::user()->id.'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath,$image_name);
                @unlink(url('public/uploads/setting/why_realtor_hubs/'.$single_setting_home_Data['hom_why_realtor_hubs_image_three']));
                SettingModel::updateOrCreate(
                    ['text_key' => 'hom_why_realtor_hubs_image_three','user_id' => 1],
                    [ 'text_value' => $image_name,'type'=>'image', 'created_date' => $updated_date]
                );
            }
            if(array_key_exists('hom_why_realtor_hubs_image_four', $result)) {
                $image = $result['hom_why_realtor_hubs_image_four'];
                $destinationPath = public_path().'/uploads/setting/why_realtor_hubs/';
                $image_name = "whyrealtorhubs".uniqid().Auth::user()->id.'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath,$image_name);
                @unlink(url('public/uploads/setting/why_realtor_hubs/'.$single_setting_home_Data['hom_why_realtor_hubs_image_four']));
                SettingModel::updateOrCreate(
                    ['text_key' => 'hom_why_realtor_hubs_image_four','user_id' => 1],
                    [ 'text_value' => $image_name,'type'=>'image', 'created_date' => $updated_date]
                );
            }
            if(array_key_exists('hom_why_realtor_hubs_image_five', $result)) {
                $image = $result['hom_why_realtor_hubs_image_five'];
                $destinationPath = public_path().'/uploads/setting/why_realtor_hubs/';
                $image_name = "whyrealtorhubs".uniqid().Auth::user()->id.'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath,$image_name);
                @unlink(url('public/uploads/setting/why_realtor_hubs/'.$single_setting_home_Data['hom_why_realtor_hubs_image_five']));
                SettingModel::updateOrCreate(
                    ['text_key' => 'hom_why_realtor_hubs_image_five','user_id' => 1],
                    [ 'text_value' => $image_name,'type'=>'image', 'created_date' => $updated_date]
                );
            }
            if(array_key_exists('hom_why_realtor_hubs_image_six', $result)) {
                $image = $result['hom_why_realtor_hubs_image_six'];
                $destinationPath = public_path().'/uploads/setting/why_realtor_hubs/';
                $image_name = "whyrealtorhubs".uniqid().Auth::user()->id.'.'.$image->getClientOriginalExtension();
                $image->move($destinationPath,$image_name);
                @unlink(url('public/uploads/setting/why_realtor_hubs/'.$single_setting_home_Data['hom_why_realtor_hubs_image_six']));
                SettingModel::updateOrCreate(
                    ['text_key' => 'hom_why_realtor_hubs_image_six','user_id' => 1],
                    [ 'text_value' => $image_name,'type'=>'image', 'created_date' => $updated_date]
                );
            }
            foreach($submit_data as $key=>$value) {
                SettingModel::updateOrCreate(
                    ['text_key' => $key,'user_id' => $user_id],
                    [ 'text_value' => $value,'type'=>'text', 'created_date' => $updated_date]
                );
            }
          
            $message = 'Home page why realtor hubs detail updated successfully.';
            return redirect('admin/settings?data=whyRealtorHubs')->with('success', $message);
        }
    }

     
    public function addSEOSetting(Request $request)
    {
        try{
        	//echo "<pre>"; print_r($request->all()); exit;
            $input = $request->all();
            $date = date('Y-m-d H:i:s');
            $user_id = Auth::user()->id;

            if(isset($input['home_seo_title']) && !empty($input['home_seo_title'])){
                $logoCreateOrUpdate = SettingModel::updateOrCreate(
                    ['text_key' => 'home_seo_title'],
                    ['user_id' => $user_id, 'text_value' => $input['home_seo_title'],'created_date' => $date]
                );
            }
            if(isset($input['home_seo_description']) && !empty($input['home_seo_description'])){
                $logoCreateOrUpdate = SettingModel::updateOrCreate(
                    ['text_key' => 'home_seo_description'],
                    ['user_id' => $user_id, 'text_value' => $input['home_seo_description'],'created_date' => $date]
                );
            }
            return redirect('admin/settings?data=seo-settings')->with('success', 'Home Page SEO Setting Updated Successfully.');
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('admin/settings?data=seo-settings')->with('error', $e->getMessage());
        }
    }
}