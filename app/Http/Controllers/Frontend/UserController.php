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
use App\Models\UserCardsModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\AgencyRelationModel;
use App\Helper\Helper;
use Mockery\Exception;
use Session;
use Stripe;
use App\Models\NotificationMaster;
use App\Models\EmailSmsLog;
use App\Models\SettingModel;
/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
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
    public function index(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $slug=Session::get('slug');
        $input = $request->all();
        if(Auth::check()){
            return redirect('/'.$slug.'/properties');
        }
        return view('frontend.login.login',compact('slug'));
    }

    public function signup(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        $country_code = CountryCodeModel::where('calling_code','!=','')->get();
        $timezones = getTimeZones();
        $slug=Session::get('slug');

        if(Auth::check()){
            return redirect('/'.$slug.'/properties');
        }
        return view('frontend.signup.signup',compact('country_code','timezones','slug'));
    }

    public  function signupPost(Request $request){
        try{
            $slug=Session::get('slug');
            $agency_id=Session::get('agency_id');
            $input = $request->all();

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'country_code' => 'required',
                'mobile_number' => 'required',
                'password' => 'required',
                'email' => 'required|email',
                'timezone' => 'required',
                'single_mobile_number'=>'min:9|max:11',
            ]);
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first(), 'code' => 201]);
            }
            $userCount=AgencyRelationModel::leftJoin('users as u','u.id','=','agency_relation.user_id')->where('agency_relation.agency_id',$agency_id)->where('agency_relation.user_type',2)->where('u.email',$input['email'])->count();
            if($userCount>0){
                return response()->json(['message' => 'The email address has been already taken.', 'code' => 201]);
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
            $otp = mt_rand(100000, 999999);
            $emailContent = $this->emailMasterModel->where('title', '=', 'Verification')->first();
            $messageContent = $emailContent->content;
            $subject = $emailContent->subject;
            $name=$input['first_name'].' '.$input['last_name'];
            $messageContent = str_replace('{{USER_NAME}}',$name , $emailContent->content);
            $messageContent = str_replace('{{OTP}}', $otp, $messageContent);
            try{
                $agency_data=AgencyModel::where('user_id',$agency_id)->first();
                $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency_logo);
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent,$agency_logo));

            }
            catch(\Swift_TransportException $e){
                \Log::error($e->getMessage());
                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            }
            catch (\Exception $e){
                \Log::error($e->getMessage());
                if($e->getCode() == 503) {
                    return response()->json(['message' => 'Email daily limit has been exceeded', 'code' => 201]);
                }
                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            }
            /*$file = $request->file('user_image');
            $destinationPath = 'public/uploads/profile_photos';
            $user_image = time().$file->getClientOriginalName();
            $file->move($destinationPath,$user_image);*/

            $storeUser = $this->userModel->create([
                'first_name'=>$input['first_name'],
                'last_name'=>$input['last_name'],
                'email'=>$input['email'],
                'password'=>Hash::make($input['password']),
                'phone_code'=>$input['country_code'],
                'phone'=>$input['single_mobile_number'],
                'timezone'=>$input['timezone'],
                'user_type'=>$input['user_type'],
                'admin_status'=>$input['admin_status'],
                'user_status'=>$input['user_status'],
                'email_verified'=>$input['email_verified'],
                'verification_code'=>$otp,
                'email_notification'=>$input['email_notification'],
                'push_notification'=>$input['push_notification'],
                'created_by'=>'0',
                 'updated_by'=>'0'
            ]);
            $user_id=$storeUser->id;
            $agencyRelation=new AgencyRelationModel();
            $agencyRelation->agency_id=$agency_id;
            $agencyRelation->user_id=$storeUser->id;
            $agencyRelation->user_type=2;
            $agencyRelation->save();
            $data = [];
            $data['user'] = $storeUser->id;
            \Illuminate\Support\Facades\Session::put('store_user_id',$storeUser->id);
            $remember = 0;
            if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'],'user_type'=>'3','deleted_at'=>NULL,'id'=>$storeUser->id],$remember))
            {
                return response()->json(['message' => 'Verify Email OTP', 'data' => $data, 'code' => 200]);

            }
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage(), 'data' => [], 'code' => 302]);
        }
    }



    public function emailVerification(Request $request){
        try{
            $slug=Session::get('slug');
            $user_id = Auth::user()->id;
            if(empty($user_id)){
                return redirect('signup');
            }
            if(Auth::user()->email_verified == UserModel::EMAIL_VERIFIED_YES){
                return redirect('/'.$slug.'/properties');
            }
            
            $user = $this->userModel->where('id',$user_id)->first();
           return view('frontend.signup.email_verification',compact('user','slug'));
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return view('frontend.signup.signup');
        }
    }

    public function postEmailVerification(Request $request){
        try{
            $slug=Session::get('slug');
            $user_id = Auth::user()->id;
            $email_otp = $request->get('email_otp');
            $user = $this->userModel->where('id',$user_id)->first();
            $date = date('Y-m-d H:i:s');
            if($user->verification_code == $email_otp){
                $update = $this->userModel->where('id',$user_id)->update(['email_verified'=>'1','verification_time'=>$date]);
//                return redirect('/agency/email-verification')->with('success', 'Email Verified Successfully');
                return redirect('/'.$slug.'/properties')->with('success', 'Email Verified Successfully');
            }
            else{
                return redirect('/'.$slug.'/email-verification')->with('error', 'OTP you entered is incorrect');
            }
        }
        catch (Exception $e){
            return redirect('/'.$slug.'/email-verification')->with('error', 'Something Went Wrong Please Try Again.');
        }
    }

    public function resendOTP(Request $request){
        try{
            
            if(Auth::check()){
                $user_id = Auth::user()->id;         
            }else{
                $user_id=Session::get('store_user_id');
            }
            $user = $this->userModel->where('id',$user_id)->first();
            $otp = mt_rand(100000, 999999);
            $emailContent = $this->emailMasterModel->where('title', '=', 'Verification')->first();
            $messageContent = $emailContent->content;
            $subject = $emailContent->subject;
            $name=$user->first_name;
//            $messageContent = str_replace('{{USER_NAME}}',$name , $emailContent->content);
            $messageContent = str_replace('{{OTP}}', $otp, $emailContent->content);
            try{
                $agency_data=AgencyModel::where('user_id',$agency_id)->first();
                $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency_logo);
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent,$agency_logo));
                $user = $this->userModel->where('id',$user_id)->update(['verification_code'=>$otp]);
            }
            catch(\Swift_TransportException $e){
                \Log::error($e->getMessage());
                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            }
            catch (\Exception $e){
                \Log::error($e->getMessage());
                if($e->getCode() == 503) {
                    return response()->json(['message' => 'Email daily limit has been exceeded', 'code' => 201]);
                }
                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            }
            return response()->json(['message' => 'OTP sent successfully.', 'code' => 200]);

        }
        catch (Exception $e){
            \Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage(), 'code' => 201]);
        }
    }
    public function loginPost(Request $request)
    {
        /**
         * Used for Admin Login Checking
         * @return redirect to Admin->Login
         */
        $agency_id=Session::get('agency_id');
        $slug=Session::get('slug');
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/'.$slug.'/login')
                ->withErrors($validator)
                ->withInput();
        }
        $emailCount=AgencyRelationModel::leftJoin('users as u','u.id','=','agency_relation.user_id')->where('agency_relation.agency_id',$agency_id)->where('agency_relation.user_type',2)->where('u.email',$input['email'])->count();
        //echo "<pre>"; print_r($userData->toArray()); exit;
        if($emailCount == 0){
            return redirect('/'.$slug.'/login')
                ->withErrors(['No such email found']);
        }
        $userData =AgencyRelationModel::leftJoin('users as u','u.id','=','agency_relation.user_id')->where('agency_relation.agency_id',$agency_id)->where('agency_relation.user_type',2)->where('u.email',$input['email'])->orderBy('u.id','DESC')->first();
        $remember = (isset($input['remember_me']) && isset($input['remember_me']) != '')?1:0;
        if (Hash::check($input['password'], $userData['password'])) {
            //echo "<pre>"; print_r($userData->toArray()); exit;
            if(!empty($userData)){
                if($userData->admin_status==0){
                    return redirect('/'.$slug.'/login')
                    ->withErrors(['Your account has been deactivated. Please contact the  agency to reactivate your account.']);
                }
                if($userData->user_type==3){
                    
                    $this->userModel->where('id',$userData['id'])->update(['user_status'=>1]);
                    Session::put('user_id', $userData['id']);
                    Session::put('store_user_id', $userData['id']);
                    Session::put('login_statua', 'True');
                    Session::put('user_type', $userData['user_type']);
                    Session::put('user_name', $userData['first_name'].' '.$userData['last_name']);
                    Session::put('profile_img', $userData['profile_img']);
                    Auth::logout();
                    if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'],'user_type'=>'3','id'=>$userData['id']],$remember))
                    {
                        return redirect('/'.$slug.'/properties');
                    }
                }
            }
            else{
                echo "string";
            }
        }
        else
        {

            return redirect('/'.$slug.'/login')
                ->withErrors(['Please enter valid password']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function forgotPass(Request $request)
    {
        /**
         * Used for Admin Forgot Password Page
         * @return redirect to Admin->Forgot Password page
         */
        if(Auth::check()){
            return redirect('/'.$slug.'/properties');
        }
        $slug=Session::get('slug');
            $agency_id=Session::get('agency_id');
        return view('frontend.login.forgot',compact('slug'));
    }
    public function forgotPasswordPost(Request $request)
    {
        /**
         * Used for Admin Forgot Password Check
         * @return redirect to Admin->Forgot Password Check
         */
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect('/'.$slug.'/forgotPass')
                ->withErrors($validator)
                ->withInput();
        }
        $user=AgencyRelationModel::leftJoin('users as u','u.id','=','agency_relation.user_id')->where('agency_relation.agency_id',$agency_id)->where('agency_relation.user_type',2)->where('u.email',$input['email'])->where('u.deleted_at',null)->orderBy('u.id','DESC')->count();
        //$user = $this->userModel->where('email','=',$input['email'])->count();
        if ($user == 0)
        {
            return redirect('/'.$slug.'/forgotPass')
                ->withErrors(['No such email found'])
                ->withInput();
        }
        else
        {
            $user = AgencyRelationModel::select('u.*')->leftJoin('users as u','u.id','=','agency_relation.user_id')->where('agency_relation.agency_id',$agency_id)->where('agency_relation.user_type',2)->where('u.email',$input['email'])->orderBy('u.id','DESC')->first();

            $name = $user->first_name.' '.$user->last_name;

            $num = mt_rand(100000,999999);
            UserModel::where('id',$user->id)->update(['verification_code'=>$num,'verification_time'=>date('Y-m-d H:i:s')]);

            $emailContent = $this->emailMasterModel->where('id','=',26)->first();
            $emailContent->content = str_replace('{{NAME}}',$name, $emailContent->content);
            $emailContent->content = str_replace('{{NUMBER}}',$num, $emailContent->content);
            $subject = $emailContent->subject;
            $messageContent = $emailContent->content;

            $agency_data=AgencyModel::where('user_id',$agency_id)->first();
            $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency_logo);
            Mail::to($user->email)->send(new ForgotPassword($subject, $messageContent,$agency_logo));
            Session::put('user_id', $user->id);
            Session::put('store_user_id', $user->id);
            return redirect('/'.$slug.'/otpverify')->with('success', 'Please enter the OTP you received on your Email');

            //return redirect('admin/otpverify',compact('user'));
        }
    }

    public function otpverify(Request $request)
    {
        /**
         * Used for Admin OTP Verify Screen
         * @return redirect to Admin->OTP Veriry Screen
         */
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        $input = $request->all();
        return view('frontend.login.otp_verify',compact('slug'));
    }

    public function otpverifyPost(Request $request)
    {
        /**
         * Used for Admin OTP verify check right or not
         * @return redirect to Admin->OTP Check
         */
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        $input = $request->all();

        $user = $this->userModel->where('verification_code','=',$input['otp'])->where('id','=',$input['user_id'])->first();
        if(!empty($user) && $user != null){
            return redirect('/'.$slug.'/changePassword');
        }else{
            return redirect()->back()->withErrors(['Please enter valid OTP']);
        }
    }
    public function changePassword($id)
    {
        /**
         * Used for Admin Profile Change Password View when forgot
         * @return redirect to Admin->Profile
         */
        //$input = $request->all();
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        
        return view('frontend.login.change_password',compact('slug'));
    }

    public function changePasswordPost(Request $request)
    {
        /**
         * Used for Admin Profile Change Password when forgot save
         * @return redirect to Admin->Profile
         */
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $user = $this->userModel->findorfail(Session::get('user_id'));
        $user->password = Hash::make($input['new_password']);
        $user->verification_code = null;
        if($user->save()){
            $request->session()->flush();
            $emailContent = $this->emailMasterModel->where('title','=',config('config.password_change'))->first();
            $subject = $emailContent->subject;
            $messageContent = $emailContent->content;

            $agency_data=AgencyModel::where('user_id',$agency_id)->first();
            $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency_logo);
            Mail::to($user->email)->send(new ForgotPassword($subject, $messageContent,$agency_logo));
            return redirect('/'.$slug.'/login')->with('success', 'Password Changed Successfully');
        }else{
            return redirect()->back()
                ->withErrors("Something Went Wrong Please Try Again.")
                ->withInput();
        }

    }
    public function logout(Request $request)
    {
        /**
         * Used for Admin Logout
         * @return redirect to Admin->Logout
         */
        $slug=Session::get('slug');
        Auth::logout();
        Session::flush();
        return redirect('/'.$slug.'/login');
    }
    
}
