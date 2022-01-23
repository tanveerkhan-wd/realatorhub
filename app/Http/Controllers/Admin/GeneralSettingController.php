<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class GeneralSettingController extends Controller
{
    protected $settingModel;
    public function __construct(SettingModel $settingModel)
    {
        $this->settingModel = $settingModel;
    }

    public function index(){

        $settingsData = array(
          config('config.general_setting.admin_logo'),
          config('config.general_setting.website_logo'),
          config('config.general_setting.email_logo'),
          config('config.general_setting.favicon_icon'),
          config('config.general_setting.seo_title'),
          config('config.general_setting.seo_description'),
          config('config.smtp_settings.mail_driver'),
          config('config.smtp_settings.mail_host'),
          config('config.smtp_settings.google_map_api_key'),
          config('config.smtp_settings.mail_port'),
          config('config.smtp_settings.mail_encryption'),
          config('config.smtp_settings.mail_username'),
          config('config.smtp_settings.mail_from_address'),
          config('config.smtp_settings.mail_password'),
          config('config.design_settings.font_type'),
          config('config.design_settings.font_color'),
          config('config.design_settings.date_format'),
          config('config.design_settings.time_format'),
          config('config.design_settings.button_color'),
          config('config.design_settings.button_text_color'),
          config('config.design_settings.header_text_color'),
          config('config.design_settings.header_hover_text'),
          config('config.design_settings.btn_size'),
          config('config.design_settings.footer_background_color'),
          config('config.design_settings.footer_text_color'),
          config('config.design_settings.footer_hover_text'),
          config('config.design_settings.menu_background_color'),
          config('config.design_settings.menu_text_color'),
          config('config.design_settings.menu_hover_color'),
           //'header_background_color'
        

        );
        $user_id = Auth::user()->id;
        //$data = $this->settingModel->whereIn('text_key',$settingsData)->where('user_id',$user_id)->get()->toArray();
        $data = $this->settingModel->where('user_id',$user_id)->get()->toArray();
        //echo "<pre>"; print_r($data); exit;
        return view('admin.general-settings.index',compact('data'));
    }

    public function saveLogoAndVideoData(Request $request)
    {
        try {
            $input = $request->all();
            $user_id = Auth::user()->id;
            $date = date('Y-m-d H:i:s');
            if (isset($input['site_admin_logo']) && $input['site_admin_logo'] != null && !empty($input['site_admin_logo'])) {
                $file = $request->file('site_admin_logo');
                $destinationPath = 'public/uploads/common_settings';
                $logo = time() . $file->getClientOriginalName();
                $file->move($destinationPath, $logo);
                $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                    ['text_key' => 'admin_logo','user_id' => $user_id],
                    [ 'text_value' => $logo,'type'=>'image', 'created_date' => $date]
                );
                @unlink(url('public/front_end/assets/images' . $input['site_admin_logo']));
            }
            if (isset($input['website_logo']) && $input['website_logo'] != null && !empty($input['website_logo'])) {
                $file = $request->file('website_logo');
                $destinationPath = 'public/uploads/common_settings';
                $logo = time() . $file->getClientOriginalName();
                $file->move($destinationPath, $logo);
                $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                    ['text_key' => config('config.general_setting.website_logo'),'user_id' => $user_id],
                    [ 'text_value' => $logo,'type'=>'image', 'created_date' => $date]
                );
                @unlink(url('public/front_end/assets/images' . $input['website_logo']));
            }
            if (isset($input['email_logo']) && $input['email_logo'] != null && !empty($input['email_logo'])) {
                $file = $request->file('email_logo');
                $destinationPath = 'public/uploads/common_settings';
                $logo = time() . $file->getClientOriginalName();
                $file->move($destinationPath, $logo);
                $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                    ['text_key' => 'email_logo','user_id' => $user_id],
                    [ 'text_value' => $logo,'type'=>'image', 'created_date' => $date]
                );
                @unlink(url('public/front_end/assets/images' . $input['email_logo']));
            }
            if (isset($input['site_favicon_logo']) && $input['site_favicon_logo'] != null && !empty($input['site_favicon_logo'])) {
                $file = $request->file('site_favicon_logo');
                $destinationPath = 'public/uploads/common_settings';
                $logo = time() . $file->getClientOriginalName();
                $file->move($destinationPath, $logo);
                $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                    ['text_key' => config('config.general_setting.favicon_icon'),'user_id' => $user_id],
                    [ 'text_value' => $logo,'type'=>'image','created_date' => $date]
                );
                @unlink(url('public/front_end/assets/images' . $input['site_favicon_logo']));
            }

            return redirect('admin/general-settings?data=logo_banner_data')->with('success', 'General Setting Updated Successfully.');
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('admin/general-settings?data=logo_banner_data')->with('error', 'Something went wrong');
        }

    }

    public function storeSeoSetting(Request $request)
    {
        try{
            $input = $request->all();
            $date = date('Y-m-d H:i:s');
            $user_id = Auth::user()->id;

            if(isset($input['seo_title']) && !empty($input['seo_title'])){
                $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                    ['text_key' => config('config.general_setting.seo_title')],
                    ['user_id' => $user_id, 'text_value' => $input['seo_title'],'created_date' => $date]
                );
            }
            if(isset($input['seo_description']) && !empty($input['seo_description'])){
                $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                    ['text_key' => config('config.general_setting.seo_description')],
                    ['user_id' => $user_id, 'text_value' => $input['seo_description'],'created_date' => $date]
                );
            }
            return redirect('admin/general-settings?data=seo-settings')->with('success', 'SEO Settings Data Updated Successfully.');
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('admin/general-settings?data=seo-settings')->with('error', $e->getMessage());
        }
    }

    public function storeDesignSetting(Request $request)
    {
       try {
           $input = $request->all();
           $user_id = Auth::User()->id;
           $date = date('Y-m-d H:i:s');
           //echo "<pre>"; print_r($input); exit;
           if (!empty($input['font_type'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.font_type'),'user_id' => $user_id],
                       ['text_value' => $input['font_type'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['font_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.font_color'),'user_id' => $user_id],
                       ['text_value' => $input['font_color'],
                           'created_date' => $date, 'type' => 'text']
                   );

           }
           if (!empty($input['date_format'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.date_format'),'user_id' => $user_id],
                       [ 'text_value' => $input['date_format'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['time_format'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.time_format'),'user_id' => $user_id],
                       [ 'text_value' => $input['time_format'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['button_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.button_color'),'user_id' => $user_id],
                       ['text_value' => $input['button_color'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['btn_size'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => 'btn_size','user_id' => $user_id],
                       [ 'text_value' => $input['btn_size'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['button_text_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.button_text_color'),'user_id' => $user_id],
                       [ 'text_value' => $input['button_text_color'],
                           'created_date' => $date, 'type' => 'text']
                   );

           }
           if (!empty($input['header_background_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => 'header_background_color','user_id' => $user_id],
                       ['text_value' => $input['header_background_color'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['header_text_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => 'header_text_color','user_id' => $user_id],
                       [ 'text_value' => $input['header_text_color'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['header_hover_text'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => 'header_hover_text','user_id' => $user_id],
                       [ 'text_value' => $input['header_hover_text'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['footer_hover_text'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => 'footer_hover_text','user_id' => $user_id],
                       [ 'text_value' => $input['footer_hover_text'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['footer_background_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.footer_background_color'),'user_id' => $user_id],
                       [ 'text_value' => $input['footer_background_color'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['footer_text_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.footer_text_color'),'user_id' => $user_id],
                       [ 'text_value' => $input['footer_text_color'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['menu_background_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.menu_background_color'),'user_id' => $user_id],
                       ['text_value' => $input['menu_background_color'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['menu_text_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.menu_text_color'),'user_id' => $user_id],
                       [ 'text_value' => $input['menu_text_color'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }
           if (!empty($input['menu_hover_color'])) {
               $update = $this->settingModel
                   ->updateOrCreate(
                       ['text_key' => config('config.design_settings.menu_hover_color'),'user_id' => $user_id],
                       ['text_value' => $input['menu_hover_color'],
                           'created_date' => $date, 'type' => 'text']
                   );
           }


           return redirect('admin/general-settings?data=design-settings')->with('success', 'Design Settings Updated Successfully.');
       }
       catch(\Exception $e){
           \Log::eror($e->getMessage());
           return redirect('admin/general-settings?data=design-settings')->with('error',$e->getMessage());

       }
    }

    public function storeSmtpSettings(Request $request){
        try{
            $input = $request->all();
            $date = date('Y-m-d H:i:s');
            $user_id = Auth::user()->id;
            if(!empty($input['google_map_api_key'])) {
                $storeGoogleMapApiKey = $this->settingModel
                    ->updateOrCreate(
                        ['text_key' => config('config.smtp_settings.google_map_api_key')],
                        ['user_id' => $user_id, 'text_value' => $input['google_map_api_key'], 'created_date' => $date, 'type' => 'text']
                    );
                Config::set('services.google.map_key',$input['google_map_api_key']);
            }
            $storeMailDriver = $this->settingModel
                ->updateOrCreate(
                    ['text_key'=> config('config.smtp_settings.mail_driver'),'user_id' => $user_id],
                    ['text_value' => $input['mail_driver'],'created_date' => $date,'type'=>'text']
                );
            $storeMailHost = $this->settingModel
                ->updateOrCreate(
                    ['text_key'=> config('config.smtp_settings.mail_host'),'user_id' => $user_id],
                    ['text_value' => $input['mail_host'],'type'=>'text','created_date' => $date]
                );
            $storeMailPort = $this->settingModel
                ->updateOrCreate(
                    ['text_key'=> config('config.smtp_settings.mail_port'),'user_id' => $user_id],
                    ['text_value' => $input['mail_port'],'type'=>'text','created_date' => $date]
                );
            if(!empty($input['mail_encryption'])) {
                $storeMailEncryption = $this->settingModel
                    ->updateOrCreate(
                        ['text_key' => config('config.smtp_settings.mail_encryption'),'user_id' => $user_id],
                        ['text_value' => $input['mail_encryption'], 'type' => 'text', 'created_date' => $date]
                    );
            }
            $storeMailUsername = $this->settingModel
                ->updateOrCreate(
                    ['text_key'=> config('config.smtp_settings.mail_username'),'user_id' => $user_id],
                    ['text_value' => $input['mail_username'],'type'=>'text','created_date' => $date]
                );
            $storeMailFromName = $this->settingModel
                ->updateOrCreate(
                    ['text_key'=> config('config.smtp_settings.mail_from_address'),'user_id' => $user_id],
                    ['text_value' => $input['mail_from_address'],'type'=>'text','created_date' => $date]
                );
            $pwd =  Crypt::encrypt($input['mail_password']);
            $storeMailPassword = $this->settingModel
                ->updateOrCreate(
                    ['text_key'=> config('config.smtp_settings.mail_password'),'user_id' => $user_id],
                    ['text_value' => $pwd,'type'=>'text','created_date' => $date]
                );

            Config::set('mail.driver', $input['mail_driver']);
            Config::set('mail.host', $input['mail_host']);
            Config::set('mail.port', $input['mail_port']);
            Config::set('mail.username', $input['mail_username']);
            Config::set('mail.password', $input['mail_password']);
            Config::set('mail.encryption', $input['mail_encryption']);
            Config::set('mail.from.name', $input['mail_from_address']);


            return redirect('admin/general-settings?data=smtp-settings')->with('success','SMTP Settings Updated Successfully.');
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('admin/general-settings?data=smtp-settings')->with('error',$e->getMessage());
        }
    }

}
