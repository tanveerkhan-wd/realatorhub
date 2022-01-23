<?php

namespace App\Http\Controllers\Admin;

use App\Models\SettingModel;
use App\Models\SubCategoryMaster;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

use function App\Helpers\apiErrorResponse;
use function App\Helpers\apiSuccessResponse;

class ContactUsController extends Controller
{
    public function index(Request $request) {
        $keywords = "contact_us_";
        $data = SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray(); 
        $singleData = array_column($data,'text_value','text_key');
        
        if (request()->ajax()) {  
            return \View::make('admin.setting.contact_us.genral_setting', compact('singleData'))->renderSections();
        }
        
        return view('admin.setting.contact_us.genral_setting', compact('singleData'));
    }
    
    public function addGenralSetting(Request $request) {
        $message = 'Something went to wrong';
        $validator = Validator::make($request->all(), [
            'contact_us_heading' => 'required',
            'contact_us_sub_heading' => 'required',
            'contact_us_email' => 'required|email', 
        ]);

        if ($validator->fails()) {       
            return apiErrorResponse($validator->errors()->first());     
        }

        $result = $request->all();

        if(count($result) > 0) {
            $data = array(
                'contact_us_heading' => $result['contact_us_heading'],
                'contact_us_sub_heading' => $result['contact_us_sub_heading'],
                'contact_us_email' => $result['contact_us_email']
            );

            foreach($data as $key=>$value) {
                SettingModel::where('text_key', $key)->update([
                    'text_value' => $value,
                ]);
            }

            $message = 'Contact us genral setting details updated successfully.';
            $data = array();
            return apiSuccessResponse($data, $message);
        }

        return apiErrorResponse($message);
    }

      public function metaSection(Request $request) {
        $keywords = "contact_us_";
        $data = SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray(); 
        $singleData = array_column($data,'text_value','text_key');
        
        if (request()->ajax()) {  
            return \View::make('admin.setting.contact_us.meta_section', compact('singleData'))->renderSections();
        }
        
        return view('admin.setting.contact_us.meta_section', compact('singleData'));
    }

      


    public function addMetaSection(Request $request) {
        $message = 'Something went to wrong';
        $validator = Validator::make($request->all(), [
            'contact_us_meta_title' => 'required',
            'contact_us_meta_description' => 'required',
        ]);

        if ($validator->fails()) {       
            return apiErrorResponse($validator->errors()->first());     
        }

        $result = $request->all();

        if(count($result) > 0) {
            $data = array(
                'contact_us_meta_title' => $result['contact_us_meta_title'],
                'contact_us_meta_description' => $result['contact_us_meta_description']
            );

            foreach($data as $key=>$value) {
                SettingModel::where('text_key', $key)->update([
                    'text_value' => $value,
                ]);
            }

            $message = 'Contact us page Meta Section details updated successfully.';
            $data = array();
            return apiSuccessResponse($data, $message);
        }

        return apiErrorResponse($message);
    }
}