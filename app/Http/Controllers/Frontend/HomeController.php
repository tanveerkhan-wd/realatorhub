<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SettingModel;
use Validator;
use App\Models\EmailMasterModel;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use App\Models\BlogPost;
use App\Models\EmailSmsLog;
class HomeController extends Controller
{
    public function index(){
        $getAgency=getUserTypeCount(1);
        $getAgent=getUserTypeCount(2);
        $all_property=getLiveProperties();
        $all_lead=getLeadsGenerated();
        $subscription_plan= getPricingPlans();
        $banner_heading=getSettingsHome('home_banner_heading');
        $banner_image=  getSettingsHome('home_website_banner');
        $video_url=  SettingModel::where('text_key','=','home_video_url')->first();
        $seo_desc=  SettingModel::where('text_key','=','home_seo_description')->first();
        $seo_title=  SettingModel::where('text_key','=','home_seo_title')->first();
        $banner_desc=  getSettingsHome('home_banner_description');
        $why_real_hub=getWhyRealtorHub();
         $blog_data = BlogPost::where('status', '=', '1')
                ->where('is_deleted', '=', '0')
                ->orderBy('id', 'desc')
                ->paginate(3);
        return view('frontend.home.index')->with([
            'agency_count'=>$getAgency,
            'agent_count'=>$getAgent,
            'property_count'=>$all_property,
            'lead_count'=>$all_lead,
            'subscription_plan'=>$subscription_plan,
            'banner_heading'=>$banner_heading,
            'banner_image'=>$banner_image,
            'banner_desc'=>$banner_desc,
            'why_real'=>$why_real_hub,
            'video_url'=>$video_url,
            'blog_data'=>$blog_data,
            'seo_title'=>$seo_title,
            'seo_desc'=>$seo_desc
            ]);
    }
    public function aboutUs(){
        $keywords = "about_";
        $contact_data_all=  SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray();
        $contact_data = array_column($contact_data_all,'text_value','text_key');
        return view('frontend.home.cms.aboutus')->with('singleData',$contact_data);
    }
    
    public function contactUs(){
        $keywords = "contact_us_";
        $contact_data_all=  SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray();
        $contact_data = array_column($contact_data_all,'text_value','text_key');
        return view('frontend.home.cms.contactus')->with('contact_data',$contact_data);
    }
    public function termsCondition(){
        $terms_data=getCmsSettingDetail(1);
        return view('frontend.home.cms.termscondition')->with('terms_data',$terms_data);
    }
    public function getFaqs(){
        $keywords = "faq_meta_";
        $contact_data_all=  SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray();
        $contact_data = array_column($contact_data_all,'text_value','text_key');
        $get_faqs=  \App\Models\FAQModel::where('status','=','1')->orderBy('sort_order','asc')->get();
         return view('frontend.home.cms.faq')->with(['get_faqs'=>$get_faqs,'faq_meta'=>$contact_data]);
    }
    public function privacyPolicy(){
        $privacy_data=getCmsSettingDetail(2);
         return view('frontend.home.cms.privacypolicy')->with('privacy_data',$privacy_data);
    }
    public function addContactUS(Request $request) {
        $message = 'Something went to wrong';
           
        $validator = Validator::make($request->all(), [
            // 'g-recaptcha-response' => 'required|captcha',
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        if ($validator->fails()) {       
            return apiErrorResponse($validator->errors()->first());     
        }
        $result = $request->all();   
        if(count($result) > 0) {
            $admin_contact_us_email_data = SettingModel::where('text_key', 'contact_us_email')->first();
            $admin_contact_us_email=$admin_contact_us_email_data->text_value;
            $emailContent = EmailMasterModel::where('title', 'Contact Us')->first();     
            $emailContent->content = str_replace('{{NAME}}', 'Admin', $emailContent->content);
            $emailContent->content = str_replace('{{CONTACTNAME}}', $result['name'], $emailContent->content);
            $emailContent->content = str_replace('{{CONTACTEMAIL}}', $result['email'], $emailContent->content);
            $emailContent->content = str_replace('{{MESSAGE}}', $result['message'], $emailContent->content);    
            $subject = $emailContent->subject;
            $message = $emailContent->content;
                $data=  \App\Models\UserModel::where('user_type','=','0')->first();
            if(empty($admin_contact_us_email)){
                $admin_contact_us_email=$data->email;
            }
           
            //Mail::to($admin_contact_us_email->text_value)->send(new ForgotPassword($subject,$message)); 
            $student_log = EmailSmsLog::create([
                    'user_id' => $data->id,
                    'email_id'=>$admin_contact_us_email,
                    'subject' => $subject,
                    'email_content' => $message,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);
            
            $message = 'Your inquiry has been submitted successfully..';
            $data= array();
            return response()->json(['message' => $message, 'success' => true]);
        }

       return response()->json(['message' => 'Something Went Wrong', 'code' => 201]);
    }
}
