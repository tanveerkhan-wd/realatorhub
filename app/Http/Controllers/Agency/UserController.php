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
use App\Models\UserCardsModel;
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
    protected $userModel,$emailMasterModel,$agencyModel,$subscriptionPlanModel,$subscriptionModel,$userCardsModel,$settingModel;


    /**
     * UserController constructor.
     * @param UserModel $userModel
     * @param EmailMasterModel $emailMasterModel
     * @param AgencyModel $agencyModel
     */
    public function __construct(UserModel $userModel, EmailMasterModel $emailMasterModel,
                                AgencyModel $agencyModel,SubscriptionPlanModel $subscriptionPlanModel,
                                SubscriptionModel $subscriptionModel,UserCardsModel $userCardsModel,SettingModel $settingModel)
    {
        $this->userModel = $userModel;
        $this->emailMasterModel = $emailMasterModel;
        $this->agencyModel = $agencyModel;
        $this->subscriptionPlanModel = $subscriptionPlanModel;
        $this->subscriptionModel = $subscriptionModel;
        $this->userCardsModel = $userCardsModel;
        $this->settingModel = $settingModel;
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
        if(Auth::check() && Auth::user()->user_type == '1'){
            return redirect('agency/home');
        }
        return view('agency.login.login');
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

        if(Auth::check() && Auth::user()->user_type == '1'){
            return redirect('agency/home');
        }
        return view('agency.signup.signup',compact('country_code','timezones'));
    }

    public  function signupPost(Request $request){
        try{
            $input = $request->all();

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'agency_name' => 'required',
                'agency_slug' => 'required',
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
            
            $userCount=UserModel::where('email',$input['email'])->count();
            if($userCount>0){
                return response()->json(['message' => 'The email address has been already taken.', 'code' => 201]);
            }
            $slugCount=AgencyModel::where('slug',$input['agency_slug'])->count();
            if($slugCount>0){
                return response()->json(['message' => 'The slug has been already taken.', 'code' => 201]);
            }
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
            $file = $request->file('user_image');
            $destinationPath = 'public/uploads/profile_photos';
            $user_image = time().$file->getClientOriginalName();
            $file->move($destinationPath,$user_image);

            $storeUser = $this->userModel->create([
                'first_name'=>$input['first_name'],
                'last_name'=>$input['last_name'],
                'user_name'=>$input['agency_slug'],
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
             $resCustomer = Stripe\Customer::create([
                        'description' => $storeUser->name . ' #' . $storeUser->id,
                        'email' => $storeUser->email
             ]);
        $user_id=$storeUser->id;
        $date = date('Y-m-d H:i:s');
        //echo "<pre>"; print_r($input); exit;
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'font_type'],
                ['user_id' => $user_id, 'text_value' => 'OpenSans','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'font_color'],
                ['user_id' => $user_id, 'text_value' => '#000','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'button_color'],
                ['user_id' => $user_id, 'text_value' => '#4e43fc','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'btn_size'],
                ['user_id' => $user_id, 'text_value' => '20px','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'button_text_color'],
                ['user_id' => $user_id, 'text_value' => '#ffffff','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'header_text_color'],
                ['user_id' => $user_id, 'text_value' => '#ffffff','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'header_hover_text'],
                ['user_id' => $user_id, 'text_value' => '#dde3fb','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'header_background_color'],
                ['user_id' => $user_id, 'text_value' => '#000','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'footer_background_color'],
                ['user_id' => $user_id, 'text_value' => '#ffffff','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'footer_text_color'],
                ['user_id' => $user_id, 'text_value' => '#181818','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'footer_hover_text'],
                ['user_id' => $user_id, 'text_value' => '#0056d6','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'menu_background_color'],
                ['user_id' => $user_id, 'text_value' => '#ffffff','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'menu_text_color'],
                ['user_id' => $user_id, 'text_value' => '#000','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'menu_hover_color'],
                ['user_id' => $user_id, 'text_value' => '#4e43fc','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'contact_form_title'],
                ['user_id' => $user_id, 'text_value' => 'Contact Agent','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'contact_form_background_color'],
                ['user_id' => $user_id, 'text_value' => '#ffffff','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'chat_first_auto_message'],
                ['user_id' => $user_id, 'text_value' => 'Hi There! Lets Chat','created_date' => $date]
            );
            $logoCreateOrUpdate = $this->settingModel->updateOrCreate(
                ['text_key' => 'customer_message_send_time'],
                ['user_id' => $user_id, 'text_value' => '1','created_date' => $date]
            );
             if($input['user_type'] == UserModel::AGENCY_USER_TYPE ){
                $storeAgency = $this->agencyModel->create([
                    'user_id'=>$storeUser->id,
                    'agency_name'=>$input['agency_name'],
                    'agency_logo'=>$user_image,
                    'slug'=>$input['agency_slug'],
                    'stripe_customer_id'=>$resCustomer->id
                ]);
            }
            $data = [];
            $data['user'] = $storeUser->id;
            \Illuminate\Support\Facades\Session::put('store_user_id',$storeUser->id);
            $remember = 0;
            if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'],'user_type'=>'1','deleted_at'=>NULL],$remember))
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
            $user_id = Auth::user()->id;
            if(empty($user_id)){
                return redirect('agency/signup');
            }
            if(Auth::user()->is_setup == UserModel::IS_SETUP_YES){
                return redirect('agency/home');
            }
            if(Auth::user()->email_verified == UserModel::EMAIL_VERIFIED_YES){
                return redirect('agency/subscription-plans');
            }

            $user = $this->userModel->where('id',$user_id)->first();
           return view('agency.signup.email_verification',compact('user'));
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return view('agency.signup.signup');
        }
    }

    public function postEmailVerification(Request $request){
        try{
            $user_id = Auth::user()->id;
            $email_otp = $request->get('email_otp');
            $user = $this->userModel->where('id',$user_id)->first();
            $date = date('Y-m-d H:i:s');
            if($user->verification_code == $email_otp){
                $update = $this->userModel->where('id',$user_id)->update(['email_verified'=>'1','verification_time'=>$date]);
//                return redirect('/agency/email-verification')->with('success', 'Email Verified Successfully');
                return redirect('/agency/subscription-plans')->with('success', 'Email Verified Successfully');
            }
            else{
                return redirect('/agency/email-verification')->with('error', 'OTP you entered is incorrect');
            }
        }
        catch (Exception $e){
            return redirect('/agency/email-verification')->with('error', 'Something Went Wrong Please Try Again.');
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
                Mail::to($user->email)->send(new SignUpMail($subject, $messageContent));
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

    public function subscriptionPlans(Request $request){
        try{
            $user_id = Auth::user()->id;
            if(empty($user_id)){
                return redirect('agency/signup');
            }
            if(Auth::user()->email_verified == UserModel::EMAIL_VERIFIED_NO){
                return redirect('agency/email-verification');
            }
            if(Auth::user()->is_setup == UserModel::IS_SETUP_YES){
                return redirect('agency/home');
            }
            $subscriptionPlans = $this->subscriptionPlanModel->withCount(['subscriptions'])
                ->where('is_deleted',SubscriptionPlanModel::IS_DELETED_NO)->orderBy('monthly_price','ASC')->get();

            return view('agency.signup.subscription_plans',compact('subscriptionPlans'));
        }
        catch (\Exception $e){
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function postSubscriptionPlan(Request $request){
        try{
            $plan = $request->get('selected_plan');
            if(empty($plan)){
                return redirect('agency/subscription-plans');
            }
            $plan_id = $plan[0];
            Session::put('plan_id',$plan_id);
            return response()->json(['message' => 'Subscription Plan', 'data'=>$plan_id,'code' => 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'Subscription Plan', 'data'=>[],'code' => 500]);
        }
    }

    public function paymentDetail(Request $request){
        try {
            $plan_id = Session::get('plan_id');
            if(empty($plan_id)){
                return redirect('agency/subscription-plans');
            }
            $user_id = Auth::user()->id;
            if(empty($user_id)){
                return redirect('agency/signup');
            }
            if(Auth::user()->email_verified == UserModel::EMAIL_VERIFIED_NO){
                return redirect('agency/email-verification');
            }
            if(Auth::user()->is_setup == UserModel::IS_SETUP_YES){
                return redirect('agency/home');
            }
            return view('agency.signup.payment_detail',compact('plan_id'));
        }
        catch (\Exception  $e){
            return redirect()->back();
        }
    }
    public  function createSetupIntent(Request $request){
//        $user = Auth::User();
        $user_id = Auth::user()->id;
        $user = $this->userModel->with('agency')->where('id',$user_id)->first();
        $stripe_customer_id = $user->agency->stripe_customer_id;
        if (!$stripe_customer_id) {
            $resCustomer = Stripe\Customer::create([
                'description' => $user->name . ' #' . $user->id,
                'email' => $user->email
            ]);

            $stripe_customer_id = $resCustomer->id;
            $user = $this->agencyModel->where('user_id', $user_id)->update(['stripe_customer_id' => $stripe_customer_id]);
        }
        $setupIntent = Stripe\SetupIntent::create([
            'customer' => $stripe_customer_id
        ]);
        return response()->json($setupIntent);
    }

    public function storeSubscription(Request $request){
        try{
            $user_id = Auth::user()->id;
            $payment_method_id = $request->get('payment_method_id');
            $last4 = $request->get('last4');
            $customer_id = $request->get('customer_id');
            $plan_id = $request->get('plan_id');

            $payment_method = Stripe\PaymentMethod::retrieve(
                $payment_method_id
            );
            $payment_method->attach([
                'customer' => $customer_id,
            ]);
            Stripe\Customer::update($customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $payment_method_id
                ]
            ]);
            $plan = $this->subscriptionPlanModel->where('id',$plan_id)->first();
            $date = date('Y-m-d H:i:s',strtotime('+30 days'));
            $current_time = strtotime($date);
           $subscription = Stripe\Subscription::create([
                'customer' => $customer_id,
                'items' => [
                    [
                        'price' => $plan->plan_id,
                        'quantity' => 1,
                    ],

                ],
               'trial_end' => $current_time,
            ]);
           
            $subscription = Stripe\Subscription::retrieve($subscription->id);
            $invoice = Stripe\Invoice::retrieve($subscription->latest_invoice);
            //echo "<pre>"; print_r($invoice->hosted_invoice_url); print_r($invoice); exit;
            $start_date = date('Y-m-d H:i:s',$subscription->current_period_start);
            $end_date = date('Y-m-d H:i:s',$current_time);
            $storeSubscriptions = $this->subscriptionModel->create(
                [
                    'user_id'=>$user_id,
                    'plan_id'=>$plan_id,
                    'subscription_id'=>$subscription->id,
                    'start_date'=>$start_date,
                    'end_date'=>$end_date,
                    'total_no_of_agent'=>'0',
                    'additional_agent'=>'0',
                    'total_amount'=>$plan->monthly_price,
                    'base_price'=>$plan->monthly_price,
                    'additional_agents_counts'=>0,
                    'additional_agent_price'=>'0',
                    'payment_type'=>0,
                    'invoice_url'=>$invoice->hosted_invoice_url,
                ]
            );
            $storeCard =$this->userCardsModel->create([
                'user_id'=>$user_id,
                'stripe_id'=>$payment_method_id,
                'card_last_four'=>$last4
            ]);
            $update = $this->userModel->where('id',$user_id)->update(['is_setup'=>'1']);
            return response()->json(['message' => 'successfully store agent', 'data'=>[],'code' => 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => $e->getMessage(), 'data'=>$e->getMessage(),'code' => 500]);
        }
    }

    public function completeAgencySignup(Request $request){
        try{

            $user_id = Auth::user()->id;
            if(empty($user_id)){
                return redirect('agency/signup');
            }
            $user = UserModel::where('id',Auth::user()->id)->with(['agency'])->first();
            $emailContent = $this->emailMasterModel->where('id',10)->first();
            if(!empty($emailContent)){
                $subject = $emailContent->subject;
                if(!empty($user->agency->agency_name)){
                    $emailContent->content = str_replace('{{AGENCYNAME}}',$user->agency->agency_name, $emailContent->content);
                }
                
                $messageContent = $emailContent->content;
                $student_log = EmailSmsLog::create([
                    'user_id' => Auth::user()->id,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
            $notification=array('from_user_id'=>$user->id,'to_user_id'=>1,'link'=>'/agencies','active_flag'=>0,'notification_desc'=>$user->first_name.' '.$user->last_name.' profile created successfully');
                //echo "<pre>"; print_r($notification); exit;
                NotificationMaster::insert($notification);
            return view('agency.signup.thank_you');
        }
        catch (\Exception $e){
            return redirect()->back();
        }
    }

    public function signupDashboard(Request $request){
        try{
            $user_id = Auth::user()->id;
            $user = $this->userModel->where('id',$user_id)->first();
            Session::put('user_id', $user->id);
            Session::put('login_statua', 'True');
            Session::put('user_type', $user->user_type);
            Session::put('user_name', $user->first_name.' '.$user->last_name);
            Session::put('profile_img', $user->profile_img);
            return redirect('agency/home');

        }
        catch (Exception $e){
            Log::error($e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function loginPost(Request $request)
    {
        /**
         * Used for Admin Login Checking
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/agency/login')
                ->withErrors($validator)
                ->withInput();
        }
        $emailCount =$this->userModel->where('user_type','=','1')->where('email','=',$input['email'])->count();
        $emailCountAgent =$this->userModel->where('user_type','=','2')->where('email','=',$input['email'])->count();
        $userData =$this->userModel->where('user_type','=','1')->where('email','=',$input['email'])->orderBy('id','DESC')->first();
        $userDataAgent =$this->userModel->where('user_type','=','2')->where('email','=',$input['email'])->orderBy('id','DESC')->first();
        //echo "<pre>"; print_r($userData->toArray()); exit;
        if($emailCount == 0 && $emailCountAgent==0){
            return redirect('agency/login')
                ->withErrors(['No such email found']);
        }

        $remember = (isset($input['remember_me']) && isset($input['remember_me']) != '')?1:0;
        if (Hash::check($input['password'], $userData['password'])||Hash::check($input['password'], $userDataAgent['password'])) {
            if(!empty($userData)){
                if($userData->admin_status==0 &&$userData->user_type==1){
                    return redirect('agency/login')
                    ->withErrors(['Your account has been deactivated. Please contact the Realtor Hubs team to reactivate your account.']);
                }
                if($userData->user_type==1){
                $this->userModel->where('id',$userData['id'])->update(['user_status'=>1]);
                Session::put('user_id', $userData['id']);
                Session::put('store_user_id', $userData['id']);
                Session::put('login_statua', 'True');
                Session::put('user_type', $userData['user_type']);
                Session::put('user_name', $userData['first_name'].' '.$userData['last_name']);
                Session::put('profile_img', $userData['profile_img']);
                if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'],'user_type'=>'1','deleted_at'=>NULL],$remember))
                {
                    return redirect('agency/home');
                }
                }
            }  else {
                 if($userDataAgent->user_status==0 &&$userDataAgent->user_type==2){
                    return redirect('agency/login')
                    ->withErrors(['Your account is Inactivated by Agency.']);
                 }
                 if($userDataAgent->admin_status==0 &&$userDataAgent->user_type==2){
                    return redirect('agency/login')
                    ->withErrors(['Your account is Inactivated by Admin.']);
                 }
                //$this->userModel->where('id', $userDataAgent['id'])->update(['user_status' => 2]);
                Session::put('user_id', $userDataAgent['id']);
                Session::put('store_user_id', $userDataAgent['id']);
                Session::put('login_statua', 'True');
                Session::put('user_type', $userDataAgent['user_type']);
                Session::put('user_name', $userDataAgent['first_name'] . ' ' . $userDataAgent['last_name']);
                Session::put('profile_img', $userDataAgent['profile_img']);
                if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'], 'user_type' => '2','deleted_at'=>NULL], $remember)) {
                    return redirect()->route('agent.home');
                }
            }
        }
        else
        {

            return redirect('agency/login')
                ->withErrors(['Please enter valid password']);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

//    public function home(Request $request)
//    {
//
//        $userData=Session::all();
//        //echo "<pre>"; print_r($userData);exit;
//        return view('agency.dashboard.dashboard');
//    }
    public function forgotPass(Request $request)
    {
        /**
         * Used for Admin Forgot Password Page
         * @return redirect to Admin->Forgot Password page
         */
        if(Auth::check() && Auth::user()->user_type == '1'){
            return redirect('agency/home');
        }
        return view('agency.login.forgot');
    }
    public function forgotPasswordPost(Request $request)
    {
        /**
         * Used for Admin Forgot Password Check
         * @return redirect to Admin->Forgot Password Check
         */
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return redirect('/agency/forgotPass')
                ->withErrors($validator)
                ->withInput();
        }

        $user = $this->userModel->where('email','=',$input['email'])->count();
        if ($user == 0)
        {
            return redirect('/agency/forgotPass')
                ->withErrors(['No such email found'])
                ->withInput();
        }
        else
        {
            $user = $this->userModel->where('email','=',$input['email'])->first();
            $name = $user->name;

            $num = mt_rand(100000,999999);
            $user->verification_code = $num;
            $user->verification_time = date('Y-m-d H:i:s');
            $user->save();


            $emailContent = $this->emailMasterModel->where('title','=','Agency Forgot Password')->first();
            $emailContent->content = str_replace('{{NAME}}',$name, $emailContent->content);
            $emailContent->content = str_replace('{{NUMBER}}',$num, $emailContent->content);
            $subject = $emailContent->subject;
            $messageContent = $emailContent->content;

            Mail::to($user->email)->send(new ForgotPassword($subject,$messageContent));
            Session::put('user_id', $user->id);
            Session::put('store_user_id', $user->id);
            return redirect('agency/otpverify')->with('success', 'Please enter the OTP you received on your Email');

            //return redirect('admin/otpverify',compact('user'));
        }
    }

    public function otpverify(Request $request)
    {
        /**
         * Used for Admin OTP Verify Screen
         * @return redirect to Admin->OTP Veriry Screen
         */
        $input = $request->all();
        return view('agency.login.otp_verify');
    }

    public function otpverifyPost(Request $request)
    {
        /**
         * Used for Admin OTP verify check right or not
         * @return redirect to Admin->OTP Check
         */
        $input = $request->all();

        $user = $this->userModel->where('verification_code','=',$input['otp'])->where('id','=',$input['user_id'])->first();
        if(!empty($user) && $user != null){
            return redirect()->route('agency.change.password','slug');
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
        if($id=='slug'){
            $decid='';
        }else{
            $decid= decrypt($id);
        }
        if(!empty($decid)){
            $created_pwd=  UserModel::where('id','=',$decid)->first();
            $already_created=$created_pwd->created_password;
            if($already_created==1){
                return redirect()->route('agency.login.page');
            }
        }
        
            return view('agency.login.change_password')->with('id',$decid);
    }

    public function changePasswordPost(Request $request)
    {
        /**
         * Used for Admin Profile Change Password when forgot save
         * @return redirect to Admin->Profile
         */
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        if(!empty(Session::get('user_id'))){
            $user = $this->userModel->findorfail(Session::get('user_id'));
        }else{
            $user = $this->userModel->findorfail($request->user_id_agent);
        }
        $user->password = Hash::make($input['new_password']);
        $user->verification_code = null;
        if(!empty($request->user_id_agent)){
            $user->created_password = 1;
        }
        if($user->save()){
            $request->session()->flush();
            $emailContent = $this->emailMasterModel->where('title','=',config('config.password_change'))->first();
            $subject = $emailContent->subject;
            $messageContent = $emailContent->content;
            if(!empty($request->user_id_agent)){
                $user = UserModel::where('id',$request->user_id_agent)->first();                
                $emailContent = $this->emailMasterModel->where('id',18)->first();
            if(!empty($emailContent)){
                $subject = $emailContent->subject;
                if(!empty($user->first_name)){
                    $emailContent->content = str_replace('{{AGENTNAME}}',$user->first_name, $emailContent->content);
                }
                
                $messageContent = $emailContent->content;
                $student_log = EmailSmsLog::create([
                    'user_id' => $request->user_id_agent,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
            $agency_id=  \App\Models\AgencyRelationModel::where('user_id','=',$request->user_id_agent)->first();
            $notification=array('from_user_id'=>$user->id,'to_user_id'=>$agency_id->agency_id,'link'=>'/agent','active_flag'=>0,'notification_desc'=>$user->first_name.' '.$user->last_name.' has been added successfully');
                //echo "<pre>"; print_r($notification); exit;
                NotificationMaster::insert($notification);
                $update = $this->userModel->where('id',$request->user_id_agent)->update(['is_setup'=>'1']);
                return redirect('agency/login')->with('success', 'Password Changed Successfully');
                
            }else{
            Mail::to($user->email)->send(new ForgotPassword($subject,$messageContent));
            return redirect('agency/login')->with('success', 'Password Changed Successfully');
            }
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
        Auth::logout();
        Session::flush();
        return redirect('agency/login');
    }
    public function checkUniqueSlug(Request $request){
        //echo "<pre>"; print_r($_GET); exit;
        //echo Auth::user()->id; exit;
        extract($_GET);
        if(!empty(Auth::user()->id)){
            $check=  AgencyModel::where('slug','=',$agency_slug)->where('user_id','!=',Auth::user()->id)->count();  
        }else{
            $check=  AgencyModel::where('slug','=',$$agency_slug)->count();
        }
        
        if($check >0){
            return 'false';
        }else{
            return 'true';
        }
    }
}
