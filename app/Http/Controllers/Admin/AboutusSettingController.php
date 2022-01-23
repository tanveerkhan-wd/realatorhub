<?php
/**
* AboutusSettingController 
*
* This file is used for about us setting and about featured images
* 
* @package    Laravel
* @subpackage Controller
* @since      1.0
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Validator;

class AboutusSettingController extends Controller
{
    public function aboutUsSetting(Request $request)
    {
        /**
         * get all Data for about us setting in admin and send to view
         * @return array
         */ 
        $user_id = Auth::user()->id;
        $data = SettingModel::where('user_id',$user_id)->get()->toArray();
        return view('admin.setting.about',compact('data'));
    }

    public function saveaboutBelieve(Request $request)
    {
        /**
         * Save data for Believe in Admin->setting->about us 
         * @return redirect to Admin->setting->about us
         */ 
        $user_id = Auth::user()->id;
    	 $result = $request->all();
        //echo "<pre>"; print_r($result); exit;
        $message = 'Something went to wrong';
        $validator = Validator::make($request->all(), [
            'about_believe_title' => 'required',
            'about_beleive_title1' => 'required',
            'about_believe_description' => 'required',
        ]);

        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
         $submit_data =  array(
            'about_believe_title' => $result['about_believe_title'],
            'about_beleive_title1' => $result['about_beleive_title1'],
            'about_believe_description' => $result['about_believe_description'],
        );
         $updated_date = date('Y-m-d H:i:s');
    	 foreach($submit_data as $key=>$value) {
            SettingModel::updateOrCreate(
                ['text_key' => $key,'user_id' => $user_id],
                [ 'text_value' => $value,'type'=>'text', 'created_date' => $updated_date]
            );
        }

    	  return redirect('admin/about-us-setting?data=believeSection')->with('success','About us Believe Data Updated Successfully.');
    }

     public function saveaboutMission(Request $request)
     {
    	/**
         * Save data for Mission in Admin->setting->about us 
         * @return redirect to Admin->setting->about us
         */
        $user_id = Auth::user()->id;
         $result = $request->all();
        //echo "<pre>"; print_r($result); exit;
        $message = 'Something went to wrong';
        $validator = Validator::make($request->all(), [
            'about_mission_title' => 'required',
            'about_mission_desctiption1' => 'required',
            'about_mission_desctiption2' => 'required',
        ]);

        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $updated_date = date('Y-m-d H:i:s');
         $submit_data =  array(
            'about_mission_title' => $result['about_mission_title'],
            'about_mission_desctiption1' => $result['about_mission_desctiption1'],
            'about_mission_desctiption2' => $result['about_mission_desctiption2'],
        );
         foreach($submit_data as $key=>$value) {
            SettingModel::updateOrCreate(
                ['text_key' => $key,'user_id' => $user_id],
                [ 'text_value' => $value,'type'=>'text', 'created_date' => $updated_date]
            );
        }

    	  return redirect('admin/about-us-setting?data=missionSection')->with('success','About us Mission Data Updated Successfully.');
    }

    public function aboutsavemetaSetting(Request $request)
    {
        /**
         * Save data for Meta Data in Admin->setting->about us 
         * @return redirect to Admin->setting->about us
         */
        try{
            //echo "<pre>"; print_r($request->all()); exit;
            $input = $request->all();
            $date = date('Y-m-d H:i:s');
            $user_id = Auth::user()->id;

            if(isset($input['about_meta_seo_title']) && !empty($input['about_meta_seo_title'])){
                $logoCreateOrUpdate = SettingModel::updateOrCreate(
                    ['text_key' => 'about_meta_seo_title'],
                    ['user_id' => $user_id, 'text_value' => $input['about_meta_seo_title'],'created_date' => $date]
                );
            }
            if(isset($input['about_meta_description']) && !empty($input['about_meta_description'])){
                $logoCreateOrUpdate = SettingModel::updateOrCreate(
                    ['text_key' => 'about_meta_description'],
                    ['user_id' => $user_id, 'text_value' => $input['about_meta_description'],'created_date' => $date]
                );
            }
            return redirect('admin/about-us-setting?data=seoSettings')->with('success', 'About Us SEO Setting Updated Successfully.');
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('admin/about-us-setting?data=seoSettings')->with('error', $e->getMessage());
        }       
    }
}
