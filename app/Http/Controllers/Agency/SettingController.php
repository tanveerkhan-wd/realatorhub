<?php
/**
* SettingController 
*
* This file is used for save Home page Setting
* 
* @package    Laravel
* @subpackage Controller
* @since      1.0
*/

namespace App\Http\Controllers\Agency;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SettingModel;
use App\Models\Admin;
use Validator;
use Session;
class SettingController extends Controller
{
    protected $settingModel;
    public function __construct(SettingModel $settingModel)
    {
        $this->settingModel = $settingModel;
    }
    public function generalSettings(Request $request)
    {
        /**
         * Used for Setting Home page
         * @return redirect to Admin->Setting->home  Page
         */
    	$input = $request->all();
    	$keywords = "home_";
        $user_id=Session::get('user_id');
        $data = SettingModel::where('user_id',$user_id)->get()->toArray(); 
        //echo "<pre>"; print_r($data); exit;
    	return view('agency.settings.general_settings',compact('data'));
    }

    public function saveAboutAgency(Request $request)
    {
        /**
         * Used for Setting Home page images
         * @return redirect to Admin->Setting->home Page Images
         */
    	$input = $request->all();
        $user_id=Session::get('user_id');
        $date = date('Y-m-d H:i:s');
        if(isset($input['agency_text']) && !empty($input['agency_text'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'agency_text'],
                ['user_id' => $user_id, 'text_value' => $input['agency_text'],'created_date' => $date]
            );
        }
        if(isset($input['facebook']) && !empty($input['facebook'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'facebook'],
                ['user_id' => $user_id, 'text_value' => $input['facebook'],'created_date' => $date]
            );
        }else{
            $logoCreateOrUpdate = $this->settingModel->where('user_id',$user_id)->where('text_key','facebook')->update(
                ['text_value' => '']
            );
        }
        if(isset($input['twitter']) && !empty($input['twitter'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'twitter'],
                ['user_id' => $user_id, 'text_value' => $input['twitter'],'created_date' => $date]
            );
        }else{
            $logoCreateOrUpdate = $this->settingModel->where('user_id',$user_id)->where('text_key','twitter')->update(
                ['text_value' => '']
            );
        }
        if(isset($input['instagram']) && !empty($input['instagram'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'instagram'],
                ['user_id' => $user_id, 'text_value' => $input['instagram'],'created_date' => $date]
            );
        }else{
            $logoCreateOrUpdate = $this->settingModel->where('user_id',$user_id)->where('text_key','instagram')->update(
                ['text_value' => '']
            );
        }
        if(isset($input['linkedin']) && !empty($input['linkedin'])){
            
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'linkedin'],
                ['user_id' => $user_id, 'text_value' => $input['linkedin'],'created_date' => $date]
            );
        }else{
            $logoCreateOrUpdate = $this->settingModel->where('user_id',$user_id)->where('text_key','linkedin')->update(
                ['text_value' => '']
            );
        }
        if(isset($input['about_us_seo_title'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'about_us_seo_title'],
                ['user_id' => $user_id, 'text_value' => $input['about_us_seo_title'],'created_date' => $date]
            );
        }
        if(isset($input['about_us_seo_desc'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'about_us_seo_desc'],
                ['user_id' => $user_id, 'text_value' => $input['about_us_seo_desc'],'created_date' => $date]
            );
        }
        if (isset($input['agency_banner_image']) && $input['agency_banner_image'] != null && !empty($input['agency_banner_image'])) {
            $file = $request->file('agency_banner_image');
            $destinationPath = 'public/uploads/agency_about_banner';
            $logo = time() . $file->getClientOriginalName();
            $file->move($destinationPath, $logo);
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'agency_banner_image','user_id' => $user_id],
                [ 'text_value' => $logo,'type'=>'image', 'created_date' => $date]
            );
            @unlink(url('public/uploads/agency_about_banner' . $input['site_admin_logo']));
        }
         

          return redirect('agency/settings?data=aboutAgency')->with('success','About Agency Data Updated Successfully.');
    }
    public function saveDesignSetting(Request $request)
    {
        /**
         * Used for Setting Home page images
         * @return redirect to Admin->Setting->home Page Images
         */

        $input = $request->all();
        $user_id=Session::get('user_id');
        $date = date('Y-m-d H:i:s');
        //echo "<pre>"; print_r($input); exit;
        if(isset($input['font_type']) && !empty($input['font_type'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'font_type'],
                ['user_id' => $user_id, 'text_value' => $input['font_type'],'created_date' => $date]
            );
        }
        if(isset($input['font_color']) && !empty($input['font_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'font_color'],
                ['user_id' => $user_id, 'text_value' => $input['font_color'],'created_date' => $date]
            );
        }
        if(isset($input['date_format']) && !empty($input['date_format'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'date_format'],
                ['user_id' => $user_id, 'text_value' => $input['date_format'],'created_date' => $date]
            );
        }
        if(isset($input['time_format']) && !empty($input['time_format'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'time_format'],
                ['user_id' => $user_id, 'text_value' => $input['time_format'],'created_date' => $date]
            );
        }
        if(isset($input['button_color']) && !empty($input['button_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'button_color'],
                ['user_id' => $user_id, 'text_value' => $input['button_color'],'created_date' => $date]
            );
        }if(isset($input['btn_size']) && !empty($input['btn_size'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'btn_size'],
                ['user_id' => $user_id, 'text_value' => $input['btn_size'],'created_date' => $date]
            );
        }

        if(isset($input['button_text_color']) && !empty($input['button_text_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'button_text_color'],
                ['user_id' => $user_id, 'text_value' => $input['button_text_color'],'created_date' => $date]
            );
        }
        if(isset($input['header_text_color']) && !empty($input['header_text_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'header_text_color'],
                ['user_id' => $user_id, 'text_value' => $input['header_text_color'],'created_date' => $date]
            );
        }
        if(isset($input['header_hover_text']) && !empty($input['header_hover_text'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'header_hover_text'],
                ['user_id' => $user_id, 'text_value' => $input['header_hover_text'],'created_date' => $date]
            );
        }
        if(isset($input['footer_background_color']) && !empty($input['footer_background_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'footer_background_color'],
                ['user_id' => $user_id, 'text_value' => $input['footer_background_color'],'created_date' => $date]
            );
        }
        if(isset($input['footer_text_color']) && !empty($input['footer_text_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'footer_text_color'],
                ['user_id' => $user_id, 'text_value' => $input['footer_text_color'],'created_date' => $date]
            );
        }if(isset($input['footer_hover_text']) && !empty($input['footer_hover_text'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'footer_hover_text'],
                ['user_id' => $user_id, 'text_value' => $input['footer_hover_text'],'created_date' => $date]
            );
        }
        if(isset($input['menu_background_color']) && !empty($input['menu_background_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'menu_background_color'],
                ['user_id' => $user_id, 'text_value' => $input['menu_background_color'],'created_date' => $date]
            );
        }
        if(isset($input['menu_text_color']) && !empty($input['menu_text_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'menu_text_color'],
                ['user_id' => $user_id, 'text_value' => $input['menu_text_color'],'created_date' => $date]
            );
        }
        if(isset($input['menu_hover_color']) && !empty($input['menu_hover_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'menu_hover_color'],
                ['user_id' => $user_id, 'text_value' => $input['menu_hover_color'],'created_date' => $date]
            );
        }
        if(isset($input['property_display_row']) && !empty($input['property_display_row'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'property_display_row'],
                ['user_id' => $user_id, 'text_value' => $input['property_display_row'],'created_date' => $date]
            );
        }
        if(isset($input['property_alignment']) && !empty($input['property_alignment'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'property_alignment'],
                ['user_id' => $user_id, 'text_value' => $input['property_alignment'],'created_date' => $date]
            );
        }
        if (isset($input['property_map_pin']) && $input['property_map_pin'] != null && !empty($input['property_map_pin'])) {
            $file = $request->file('property_map_pin');
            $destinationPath = 'public/uploads/map_pin';
            $logo = time() . $file->getClientOriginalName();
            $file->move($destinationPath, $logo);
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'property_map_pin','user_id' => $user_id],
                [ 'text_value' => $logo,'type'=>'image', 'created_date' => $date]
            );
            @unlink(url('public/uploads/map_pin' . $input['property_map_pin']));
        }
         

          return redirect('agency/settings?data=designSettings')->with('success','Design Settings Data Updated Successfully.');
    }
    public function saveContactFormSettings(Request $request)
    {
        /**
         * Used for Setting Home page images
         * @return redirect to Admin->Setting->home Page Images
         */
        $input = $request->all();
        $user_id=Session::get('user_id');
        $date = date('Y-m-d H:i:s');
        if(isset($input['contact_form_title']) && !empty($input['contact_form_title'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'contact_form_title'],
                ['user_id' => $user_id, 'text_value' => $input['contact_form_title'],'created_date' => $date]
            );
        }
        if(isset($input['contact_form_background_color']) && !empty($input['contact_form_background_color'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'contact_form_background_color'],
                ['user_id' => $user_id, 'text_value' => $input['contact_form_background_color'],'created_date' => $date]
            );
        }
        if(isset($input['chat_first_auto_message']) && !empty($input['chat_first_auto_message'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'chat_first_auto_message'],
                ['user_id' => $user_id, 'text_value' => $input['chat_first_auto_message'],'created_date' => $date]
            );
        }
        if(isset($input['customer_message_send_time']) && !empty($input['customer_message_send_time'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'customer_message_send_time'],
                ['user_id' => $user_id, 'text_value' => $input['customer_message_send_time'],'created_date' => $date]
            );
        }
          return redirect('agency/settings?data=contactForm')->with('success','Contact Form Data Updated Successfully.');
    }
    public function saveSEOSettings(Request $request)
    {
        /**
         * Used for Setting Home page images
         * @return redirect to Admin->Setting->home Page Images
         */
        $input = $request->all();
        $user_id=Session::get('user_id');
        $date = date('Y-m-d H:i:s');
        if(isset($input['meta_title']) && !empty($input['meta_title'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'meta_title'],
                ['user_id' => $user_id, 'text_value' => $input['meta_title'],'created_date' => $date]
            );
        }
        if(isset($input['meta_description']) && !empty($input['meta_description'])){
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'meta_description'],
                ['user_id' => $user_id, 'text_value' => $input['meta_description'],'created_date' => $date]
            );
        }       

          return redirect('agency/settings?data=seoSetting')->with('success','SEO Settings Data Updated Successfully.');
    }


}   
