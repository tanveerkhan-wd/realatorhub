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

class ContactusSettingController extends Controller
{
    public function contactUsSetting(Request $request)
    {
        /**
         * get all Data for about us setting in admin and send to view
         * @return array
         */ 
        $user_id = Auth::user()->id;
        $data = SettingModel::where('user_id',$user_id)->get()->toArray();
        return view('admin.setting.contact',compact('data'));
    }

    public function saveContactUs(Request $request)
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
            'contact_us_heading' => 'required',
            'contact_us_sub_heading' => 'required',
            'contact_us_email' => 'required',
        ]);

        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
         $submit_data =  array(
            'contact_us_sub_heading' => $result['contact_us_sub_heading'],
            'contact_us_heading' => $result['contact_us_heading'],
            'contact_us_email' => $result['contact_us_email'],
        );
         $updated_date = date('Y-m-d H:i:s');
    	 foreach($submit_data as $key=>$value) {
            SettingModel::updateOrCreate(
                ['text_key' => $key,'user_id' => $user_id],
                [ 'text_value' => $value,'type'=>'text', 'created_date' => $updated_date]
            );
        }

    	  return redirect('admin/contact-us-setting?data=contcatSection')->with('success','Contcat Us Data Updated Successfully.');
    }

    public function saveContactMetaSetting(Request $request)
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

            if(isset($input['contact_us_meta_title']) && !empty($input['contact_us_meta_title'])){
                $logoCreateOrUpdate = SettingModel::updateOrCreate(
                    ['text_key' => 'contact_us_meta_title'],
                    ['user_id' => $user_id, 'text_value' => $input['contact_us_meta_title'],'created_date' => $date]
                );
            }
            if(isset($input['contact_us_meta_description']) && !empty($input['contact_us_meta_description'])){
                $logoCreateOrUpdate = SettingModel::updateOrCreate(
                    ['text_key' => 'contact_us_meta_description'],
                    ['user_id' => $user_id, 'text_value' => $input['contact_us_meta_description'],'created_date' => $date]
                );
            }
            return redirect('admin/contact-us-setting?data=seoSettings')->with('success', 'Contact Us SEO Setting Updated Successfully.');
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('admin/contact-us-setting?data=seoSettings')->with('error', $e->getMessage());
        }       
    }
}
