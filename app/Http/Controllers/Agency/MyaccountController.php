<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Mail\SignUpMail;
use App\Models\AgencyModel;
use App\Models\CountryCodeModel;
use App\Models\EmailMasterModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Helper\Helper;
use Mockery\Exception;
use Session;
use Stripe;
use App\Models\SettingModel;
use App\Models\NotificationMaster;
use App\Models\EmailSmsLog;
/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class MyaccountController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
    */
    protected $userModel,$emailMasterModel,$agencyModel,$subscriptionPlanModel,$subscriptionModel;


    /**
     * UserController constructor.
     * @param UserModel $userModel
     * @param EmailMasterModel $emailMasterModel
     * @param AgencyModel $agencyModel
     */
    public function __construct(UserModel $userModel, EmailMasterModel $emailMasterModel,
                                AgencyModel $agencyModel,SubscriptionPlanModel $subscriptionPlanModel,
                                SubscriptionModel $subscriptionModel)
    {
        $this->userModel = $userModel;
        $this->emailMasterModel = $emailMasterModel;
        $this->agencyModel = $agencyModel;
        $this->subscriptionPlanModel = $subscriptionPlanModel;
        $this->subscriptionModel = $subscriptionModel;
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION'));

    }

    public function index(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        $country_code = CountryCodeModel::where('calling_code','!=','')->get();
        $timezones = getTimeZones();
        $user_id=Auth::user()->id;
        $agency_data=$this->userModel->where('id',$user_id)->with(['agency'])->first();
        $data = SettingModel::where('user_id',$user_id)->get()->toArray();
        $data = array_column($data,'text_value','text_key');
        $is_plane_active = $this->subscriptionModel
            ->with(['plan'])
            ->where('user_id',$user_id)
            ->where('status',SubscriptionModel::STATUS_ACTIVE)
            ->count();
        //echo "<pre>"; print_r($agency_data->agency->agency_logo); exit;
        return view('agency.myaccount.myaccount',compact('country_code','timezones','agency_data','data','is_plane_active'));
    }

    public  function editMyProfilePost(Request $request){
        try{
            $input = $request->all();

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'agency_name' => 'required',
                'agency_slug' => 'required',
                'country_code' => 'required',
                'timezone' => 'required',
                'mobile_number'=>'required|min:9|max:11',
            ]);
            if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
            }
            /*$emailcout=$this->userModel::where('email', $input['email'])->where('id','!=',$input['id'])->count();
            if($emailcout > 0){
                return redirect('agency/my-account')->withErrors(['This email address is already used.']);
            }*/

            $userdata=$this->userModel::where('id',$input['id'])->first();
            /*if($userdata->email!=$input['email']){
                $otp = mt_rand(100000, 999999);
                $emailContent = $this->emailMasterModel->where('title', '=', 'Verification')->first();
                $messageContent = $emailContent->content;
                $subject = $emailContent->subject;
                $name=$input['first_name'];
    //            $messageContent = str_replace('{{USER_NAME}}',$name , $emailContent->content);
                $messageContent = str_replace('{{OTP}}', $otp, $emailContent->content);
                try{
                    Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent));
                }
                catch(\Swift_TransportException $e){
                    return redirect('agency/my-account')->withErrors([$e->getMessage()]);
                    //return response()->json(['message' => $e->getMessage(), 'code' => 201]);
                }
                catch (\Exception $e){
                    if($e->getCode() == 503) {
                        return redirect('agency/my-account')->withErrors(['Email daily limit has been exceeded']);
                    }
                    return redirect('agency/my-account')->withErrors([$e->getMessage()]);
                }
            }*/
            if (isset($input['agency_logo']) && $input['agency_logo'] != null && !empty($input['agency_logo'])) {
                $file = $request->file('agency_logo');
                $destinationPath = 'public/uploads/profile_photos';
                $user_image = time().$file->getClientOriginalName();
                $file->move($destinationPath,$user_image);
                $storeAgency = $this->agencyModel->where('user_id',$input['id'])->update([
                    'agency_logo'=>$user_image,
                ]);
            }
            $storeUser = $this->userModel->where('id',$input['id'])->update([
                'first_name'=>$input['first_name'],
                'last_name'=>$input['last_name'],
                'user_name'=>$input['agency_slug'],
                /*'email'=>$input['email'],*/
                'phone_code'=>$input['country_code'],
                'phone'=>$input['mobile_number'],
                'timezone' => $input['timezone'],
                 'updated_by'=>'1'
            ]);

            
            $storeAgency = $this->agencyModel->where('user_id',$input['id'])->update([
                'agency_name'=>$input['agency_name'],
                'slug'=>$input['agency_slug'],
            ]);
            
            if(isset($input['lead_email']) && !empty($input['lead_email'])){
                $date = date('Y-m-d H:i:s');
                $logoCreateOrUpdate = SettingModel::updateOrCreate(
                    ['text_key' => 'lead_email'],
                    ['user_id' => $input['id'], 'text_value' => $input['lead_email'],'created_date' => $date]
                );
            }
            return redirect('agency/my-account')->with('success','Profile Updated Successfully');
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('agency/my-account')->withErrors([$e->getMessage()]);
        }
    }


    public function changePassword(Request $request)
    {
        /**
         * Used for Admin Profile Change Password when forgot save
         * @return redirect to Admin->Profile
         */
        $input = $request->all();
        if(isset($input['old_password']) && $input['old_password'] != null && !empty($input['old_password']))
        {    

            if (Hash::check($input['old_password'], Auth::user()->password)) {
                // The passwords match...
            }else{
                 return redirect('agency/my-account?data=changePassword')
                        ->withErrors(['Current password does not match.']);
            }
        }
        $user = $this->userModel->findorfail(Auth::user()->id);
        if(isset($input['new_password']) && $input['new_password'] != null && !empty($input['new_password']))
        {
            $user->password = Hash::make($input['new_password']);
        }   
        if($user->save()){
            $emailContent = $this->emailMasterModel->where('title','=',config('config.password_change'))->first();
            $subject = $emailContent->subject;
            $messageContent = $emailContent->content;

            Mail::to($user->email)->send(new ForgotPassword($subject,$messageContent));
            return redirect('agency/my-account?data=changePassword')->with('success', 'Password Updated Successful');

        }else{
            return redirect('agency/my-account?data=changePassword')
                        ->withErrors(['Something Went Wrong Please Try Again.']);
        }

    }
    public function deactive_account(Request $request)
    {
        $input = $request->all();
        $user = UserModel::where('id',Auth::user()->id)->with(['agency'])->first();
        $admin = UserModel::findorfail(1);
        if(!empty($user))
        {
            $user->user_status = 0;
            if($user->save()){
                //$notificatin_desc=array('title'=>'Agency Deactivate Account','body'=>$user->first_name.' '.$user->last_name.' has deactived your account.');
                $notification=array('from_user_id'=>$user->id,'to_user_id'=>1,'link'=>'/agencies','active_flag'=>0,'notification_desc'=>$user->first_name.' '.$user->last_name.' has deactived your account.');
                //echo "<pre>"; print_r($notification); exit;
                NotificationMaster::insert($notification);
                $emailContent = $this->emailMasterModel->where('id',6)->first();
                if(!empty($emailContent)){
                    $subject = $emailContent->subject;
                    if(!empty($user->agency->agency_name)){
                        $emailContent->content = str_replace('{{USERNAME}}',$user->agency->agency_name, $emailContent->content);
                    }
                    
                    $messageContent = $emailContent->content;
                    $student_log = EmailSmsLog::create([
                        'user_id' => Auth::user()->id,
                        'subject' => $subject,
                        'email_content' => $messageContent,
                        'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                    ]);

                }
                $emailContent = $this->emailMasterModel->where('id',19)->first();
                if(!empty($emailContent)){
                    $subject = $emailContent->subject;
                    if(!empty($user->agency->agency_name)){
                        $emailContent->content = str_replace('{{AGENCYNAME}}',$user->agency->agency_name, $emailContent->content);
                    }
                    
                    $messageContent = $emailContent->content;
                    $student_log = EmailSmsLog::create([
                        'user_id' => 1,
                        'subject' => $subject,
                        'email_content' => $messageContent,
                        'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                    ]);

                }
                Auth::logout();
                Session::flush();
                return response()->json(['message' =>'Your account has been deactivated. Please contact the Realtor Hubs team to reactivate your account.','code' => 200]);
            }else{
                return response()->json(['message' =>'Something Went Wrong Please Try Again.','code' => 201]);
            }   
        }else{
            return response()->json(['message' =>'Something Went Wrong Please Try Again.','code' => 201]);
        }
    }
    public function changeEmail(Request $request){

        $input = $request->all();
        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail(Auth::user()->id);
        $emailcout=$this->userModel::where('email', $input['email'])->where('id','!=',Auth::user()->id)->count();
        if($emailcout > 0){
            return response()->json(['message' =>'This email address is already used.','code' => 201]);
        }
        $userdata=$this->userModel::where('id',Auth::user()->id)->first();
        if($userdata->email!=$input['email']){
            $otp = mt_rand(100000, 999999);
            $emailContent = $this->emailMasterModel->where('id',8)->first();
            $messageContent = $emailContent->content;
            $subject = $emailContent->subject;
            $name=$user->first_name;
//            $messageContent = str_replace('{{USER_NAME}}',$name , $emailContent->content);
            $messageContent = str_replace('{{OTP}}', $otp, $emailContent->content);
            try{
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent));
                $this->userModel->where('id',Auth::user()->id)->update(['verification_code'=>$otp]);
                return response()->json(['message' =>'OTP Resent Successfully.','code' => 200]);
            }
            catch(\Swift_TransportException $e){
                
                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            }
            catch (\Exception $e){
                if($e->getCode() == 503) {
    
                    return response()->json(['message' =>'Email daily limit has been exceeded','code' => 201]);
                }
                return response()->json(['message' =>$e->getMessage(),'code' => 201]);
            }
        }
    }
    public function checkemail(Request $request){
        extract($_GET);
        $emailcout=$this->userModel::where('email', $email )->where('id','!=',Auth::user()->id)->count();
        if($emailcout > 0){
            echo 'false';
            exit;
        }
        else {
            echo 'true';
            exit;
        }
    }
    public function postEmailVerification(Request $request){
        extract($_POST);
        try{
            $user_id=Auth::user()->id;
            $user = $this->userModel->where('id',$user_id)->first();
            $date = date('Y-m-d H:i:s');
            if($user->verification_code == $email_otp){
                $update = $this->userModel->where('id',$user_id)->update(['email'=>$email,'verification_time'=>$date]);
//                return redirect('/agency/email-verification')->with('success', 'Email Verified Successfully');
                return response()->json(['message' =>'Email Verified Successfully','code' => 200]);
            }
            else{
                return response()->json(['message' =>'OTP you entered is incorrect','code' => 201]);
            }
        }
        catch (Exception $e){
            return response()->json(['message' =>'Something Went Wrong Please Try Again.','code' => 201]);
        }
    }
}
