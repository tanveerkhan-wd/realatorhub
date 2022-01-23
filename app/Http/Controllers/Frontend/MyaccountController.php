<?php

namespace App\Http\Controllers\Frontend;

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
use App\Models\AgencyRelationModel;

class MyaccountController extends Controller {

    protected $userModel,$emailMasterModel;


    /**
     * UserController constructor.
     * @param UserModel $userModel
     * @param EmailMasterModel $emailMasterModel
     */
    public function __construct(UserModel $userModel, EmailMasterModel $emailMasterModel)
    {
        $this->userModel = $userModel;
        $this->emailMasterModel = $emailMasterModel;


    }

    public function index(Request $request) {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $agency_id=Session::get('agency_id');
        $slug=Session::get('slug');
        $input = $request->all();
        $country_code = CountryCodeModel::where('calling_code', '!=', '')->get();
        $timezones = getTimeZones();
        $user_id = Auth::user()->id;
        $customer_data = UserModel::where('id', '=', $user_id)->first();
        $data = SettingModel::where('user_id', $user_id)->get()->toArray();
        $data = array_column($data, 'text_value', 'text_key');
        //echo "<pre>"; print_r($agency_data->agency->agency_logo); exit;
        return view('frontend.myaccount.myaccount', compact('country_code', 'timezones', 'customer_data', 'data','slug'));
    }

    public function editMyProfile(Request $request) {
        try {
            $agency_id=Session::get('agency_id');
            $slug=Session::get('slug');
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'country_code' => 'required',
                        'timezone' => 'required',
                        'mobile_number' => 'required|min:9|max:11',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            }
            /* $emailcout=$this->userModel::where('email', $input['email'])->where('id','!=',$input['id'])->count();
              if($emailcout > 0){
              return redirect('agency/customer-my-account')->withErrors(['This email id is already used.']);
              } */
            if (isset($input['agency_logo']) && $input['agency_logo'] != null && !empty($input['agency_logo'])) {
                $file = $request->file('agency_logo');
                $destinationPath = 'public/uploads/profile_photos';
                $user_image = time() . $file->getClientOriginalName();
                $file->move($destinationPath, $user_image);
                $update_data = [
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'phone_code' => $input['country_code'],
                    'phone' => $input['mobile_number'],
                    'timezone'=>$input['timezone'],
                    'profile_img' => $user_image,
                ];
            } else {
                $update_data = [
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'phone_code' => $input['country_code'],
                    'phone' => $input['mobile_number'],
                    'timezone'=>$input['timezone'],
                ];
            }
            UserModel::where('id', '=', $input['id'])->update($update_data);
            return redirect('/'.$slug.'/customer-my-account')->with('success', 'Profile Updated Successfully');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect('/'.$slug.'/customer-my-account')->withErrors([$e->getMessage()]);
        }
    }

    public function changeEmail(Request $request) {
        $agency_id=Session::get('agency_id');
        $slug=Session::get('slug');
        $input = $request->all();
        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail(Auth::user()->id);
        $emailcout=AgencyRelationModel::leftJoin('users as u','u.id','=','agency_relation.user_id')->where('agency_relation.agency_id',$agency_id)->where('agency_relation.user_type',2)->where('u.email',$input['email'])->where('u.id', '!=', Auth::user()->id)->count();
        //$emailcout = UserModel::where('email', $input['email'])->where('id', '!=', Auth::user()->id)->count();
        if ($emailcout > 0) {
            return response()->json(['message' => 'This Email Address Is Already In Use.', 'code' => 201]);
        }
        $emailCountAgency =$this->userModel->where('user_type','=','1')->where('email','=',$input['email'])->count();
        $emailCountAgent =$this->userModel->where('user_type','=','2')->where('email','=',$input['email'])->count();
        //echo "<pre>"; print_r($userData->toArray()); exit;
        if($emailCountAgency>0){
            return response()->json(['message' => 'The email address has been already taken in agency.', 'code' => 201]);
        }
        if($emailCountAgent>0){
            return response()->json(['message' => 'The email address has been already taken in agent.', 'code' => 201]);
        }
        $userdata = UserModel::where('id', Auth::user()->id)->first();
        if ($userdata->email != $input['email']) {
            $otp = mt_rand(100000, 999999);
            $emailContent = EmailMasterModel::where('id', 27)->first();
            $messageContent = $emailContent->content;
            $subject=$emailContent->subject;
            $name = $user->first_name.' '.$user->last_name;
            $messageContent=$emailContent->content;
            $messageContent = str_replace('{{USER_NAME}}',$name , $messageContent);
            $messageContent = str_replace('{{OTP}}', $otp, $messageContent);
            try {
                $agency_data=AgencyModel::where('user_id',$agency_id)->first();
                $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency_logo);
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent,$agency_logo));
                UserModel::where('id', Auth::user()->id)->update(['verification_code' => $otp]);
                return response()->json(['message' => 'OTP Resend Successfully.', 'code' => 200]);
            } catch (\Swift_TransportException $e) {

                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            } catch (\Exception $e) {
                if ($e->getCode() == 503) {

                    return response()->json(['message' => 'Email daily limit has been exceeded', 'code' => 201]);
                }
                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            }
        }
    }

    public function postEmailVerification(Request $request) {
        extract($_POST);
        try {
            $agency_id=Session::get('agency_id');
            $slug=Session::get('slug');
            $user_id = Auth::user()->id;
            $user = UserModel::where('id', $user_id)->first();
            $date = date('Y-m-d H:i:s');
            if ($user->verification_code == $email_otp) {
                $update = UserModel::where('id', $user_id)->update(['email' => $email, 'verification_time' => $date]);
//                return redirect('/agency/email-verification')->with('success', 'Email Verify Successfully');
                return response()->json(['message' => 'Email Verify Successfully', 'code' => 200]);
            } else {
                return response()->json(['message' => 'OTP you entered is incorrect', 'code' => 201]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Something Went Wrong', 'code' => 201]);
        }
    }

    public function changePassword(Request $request) {
        /**
         * Used for Admin Profile Change Password when forgot save
         * @return redirect to Admin->Profile
         */
        $input = $request->all();
        $agency_id=Session::get('agency_id');
        $slug=Session::get('slug');
        if (isset($input['old_password']) && $input['old_password'] != null && !empty($input['old_password'])) {

            if (Hash::check($input['old_password'], Auth::user()->password)) {
                // The passwords match...
            } else {
                return redirect('/'.$slug.'/customer-my-account?data=changePassword')
                                ->withErrors(['Old Password Does not matched']);
            }
        }
        $user = UserModel::findorfail(Auth::user()->id);
        if (isset($input['new_password']) && $input['new_password'] != null && !empty($input['new_password'])) {
            $user->password = Hash::make($input['new_password']);
        }
        if ($user->save()) {
            $emailContent = EmailMasterModel::where('title', '=', config('config.password_change'))->first();
            $subject = $emailContent->subject;
            $messageContent = $emailContent->content;
            $agency_data=AgencyModel::where('user_id',$agency_id)->first();
            $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency_logo);
            Mail::to($user->email)->send(new ForgotPassword($subject, $messageContent,$agency_logo));
            return redirect('/'.$slug.'/customer-my-account?data=changePassword')->with('success', 'Password Updated Successful');
        } else {
            return redirect('/'.$slug.'/customer-my-account?data=changePassword')
                            ->withErrors(['Something Wrong Please try again Later']);
        }
    }
    public function deactive_account(Request $request)
    {
        $input = $request->all();
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        $user = UserModel::where('id',Auth::user()->id)->first();
        $agency_data = UserModel::where('id',$agency_id)->with(['agency'])->first();
        $admin = UserModel::findorfail(1);
        if(!empty($user))
        {
            $user->user_status = 0;
            if($user->save()){
                //$notificatin_desc=array('title'=>'Agency Deactivate Account','body'=>$user->first_name.' '.$user->last_name.' has deactived your account.');
                $notification=array('from_user_id'=>$user->id,'to_user_id'=>$agency_id,'link'=>'/customer-list','active_flag'=>0,'notification_desc'=>$user->first_name.' '.$user->last_name.' has deactivated account.');
                //echo "<pre>"; print_r($notification); exit;
                NotificationMaster::insert($notification);
                $emailContent = $this->emailMasterModel->where('id',28)->first();
                if(!empty($emailContent)){
                    $subject = $emailContent->subject;
                    $messageContent = $emailContent->content;
                    if(!empty($agency_data->agency->agency_name)){
                        $messageContent = str_replace('{{AGENCYNAME}}',$agency_data->agency->agency_name, $messageContent);
                    }
                    $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency->agency_logo);
                    $messageContent = str_replace('{{USERNAME}}',$user->first_name.' '.$user->last_name, $messageContent);
                    
                    $student_log = EmailSmsLog::create([
                        'user_id' => Auth::user()->id,
                        'subject' => $subject,
                        'email_content' => $messageContent,
                        'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS,
                        'logo'=>$agency_logo,
                    ]);

                }
                $emailContent = $this->emailMasterModel->where('id',29)->first();
                if(!empty($emailContent)){
                    $subject = $emailContent->subject;
                    if(!empty($user->agency->agency_name)){
                        $emailContent->content = str_replace('{{USERNAME}}',$user->first_name.' '.$user->last_name, $emailContent->content);
                    }
                    $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency->agency_logo);
                    $messageContent = $emailContent->content;
                    $student_log = EmailSmsLog::create([
                        'user_id' => 1,
                        'subject' => $subject,
                        'email_content' => $messageContent,
                        'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS,
                        'logo'=>$agency_logo,
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
    public function checkemail(Request $request){
        extract($_GET);
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        $emailcout=AgencyRelationModel::leftJoin('users as u','u.id','=','agency_relation.user_id')->where('agency_relation.agency_id',$agency_id)->where('agency_relation.user_type',2)->where('u.email',$email)->where('u.id', '!=', Auth::user()->id)->count();
        //$emailcout = UserModel::where('email', $input['email'])->where('id', '!=', Auth::user()->id)->count();
        $emailCountAgency =UserModel::where('user_type','=','1')->where('email','=',$email)->count();
        $emailCountAgent =UserModel::where('user_type','=','2')->where('email','=',$email)->count();
        if ($emailcout > 0) {
            echo 'false';
            exit;
        }
        elseif($emailCountAgency>0){
            echo 'false';
            exit;
        }
        elseif($emailCountAgent>0){
            echo 'false';
            exit;
        }
        else {
            echo 'true';
            exit;
        }
    }
}
