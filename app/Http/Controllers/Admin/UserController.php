<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Models\EmailMasterModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        $input = $request->all();
        if(Auth::check() && Auth::user()->user_type == '0'){
            return redirect('admin/home');
        }
        return view('admin.login.login');
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
            return redirect('/admin/login')
                ->withErrors($validator)
                ->withInput();
        }
        $emailCount =$this->userModel->where('user_type','=','0')->where('email','=',$input['email'])->count();
        if($emailCount == 0){
            return redirect('admin/login')
                ->withErrors(['No Such Email Found']);
        }

        $remember = (isset($input['remember_me']) && isset($input['remember_me']) != '')?1:0;
        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password'],'user_type'=>'0'],$remember))
        {
            return redirect('admin/home');
        }
        else
        {
            return redirect('admin/login')
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
        return view('admin.login.forgot');
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
            return redirect('/admin/forgotPass')
                ->withErrors($validator)
                ->withInput();
        }

        $user = $this->userModel->where('email','=',$input['email'])->count();
        if ($user == 0)
        {
            return redirect('/admin/forgotPass')
                ->withErrors(['No Such Email Found'])
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


            $emailContent = $this->emailMasterModel->where('title','=','Admin Forgot Password')->first();
            $emailContent->content = str_replace('{{NAME}}',$name, $emailContent->content);
            $emailContent->content = str_replace('{{NUMBER}}',$num, $emailContent->content);
            $subject = $emailContent->subject;
            $messageContent = $emailContent->content;

            Mail::to($user->email)->send(new ForgotPassword($subject,$messageContent));
            Session::put('user_id', $user->id);
            return redirect('admin/otpverify')->with('success', 'Please enter the OTP you received on your Email.');

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
        return view('admin.login.otp_verify');
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
            return redirect('admin/changePassword');
        }else{
            return redirect()->back()->withErrors(['Please enter valid OTP']);
        }
    }

    public function changePassword(Request $request)
    {
        /**
         * Used for Admin Profile Change Password View when forgot
         * @return redirect to Admin->Profile
         */
        $input = $request->all();
        return view('admin.login.change_password');
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

        $user = $this->userModel->findorfail(Session::get('user_id'));
        $user->password = Hash::make($input['new_password']);
        $user->verification_code = null;

        if($user->save()){
            $request->session()->flush();
            $emailContent = $this->emailMasterModel->where('title','=',config('config.password_change'))->first();
            $subject = $emailContent->subject;
            $messageContent = $emailContent->content;

            Mail::to($user->email)->send(new ForgotPassword($subject,$messageContent));
            return redirect('admin/login')->with('success', 'Password Changed Successfully');
        }else{
            return redirect()->back()
                ->withErrors("Something Went Wrong Please Try Again.")
                ->withInput();
        }

    }

    public function profile(Request $request)
    {
        /**
         * Used for Admin Profile
         * @return redirect to Admin->Profile
         */
        return view('admin.dashboard.profile');
    }

    public function changeProfile(Request $request)
    {
        $input = $request->all();
        $image = $request->image;

        list($type, $image) = explode(';', $image);
        list(, $image)      = explode(',', $image);
        $image = base64_decode($image);
        $image_name= time().Auth::user()->id.'.png';
        $path = public_path('uploads/user_profile/'.$image_name);

        file_put_contents($path, $image);
        @unlink(url('public/uploads/user_profile'.$input['old_user_image']));

        $user = $this->userModel->findorfail(Auth::user()->id);
        $user->profile_img = $image_name;
        if($user->save()){
            return response()->json(['message' =>'Profile picture updated','code' => 200,'image_path'=>$path]);
            return response()->json(['message' =>'Profile picture updated','code' => 200]);
        }else{
            //return response()->json(['status'=>false]);
            return response()->json(['message' =>'Something Went Wrong Please Try Again.','code' => 201]);
        }
    }

    public function profilePost(Request $request)
    {
        /**
         * Used for Admin Profile Data Save
         * @return redirect to Admin->Profile
         */
        $input = $request->all();
        $emailcout=$this->userModel::where('email',  $input['email'])->where('id','!=',Auth::user()->id)->count();
        if($emailcout > 0){
            return redirect('admin/profile')->withErrors(['This email address is already used.']);
        }
        
        $user = $this->userModel->findorfail(Auth::user()->id);
        $user->user_name = trim($input['name']);
        $user->timezone = $input['timezone'];
        $user->email = $input['email'];
        //$user->user_image = $user_image;
        if($user->save()){
            return redirect('admin/profile')->with('success', 'Profile Updated Successful');

        }else{
            return redirect('admin/profile')
                ->withErrors(['Something Went Wrong Please Try Again.']);
        }
    }

    public function changePasswordPostAdmin(Request $request)
    {
        /**
         * Used for Admin Profile Change Password
         * @return redirect to Admin->Profile
         */
        $input = $request->all();
        if(isset($input['old_password']) && $input['old_password'] != null && !empty($input['old_password']))
        {

            if (Hash::check($input['old_password'], Auth::user()->password)) {
                // The passwords match...
            }else{
                return redirect('admin/profile?data=change-password')
                    ->withErrors(['Old Password Does not matched']);
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
            return redirect('admin/profile?data=change-password')->with('success', 'Password Updated Successfully.');

        }else{
            return redirect('admin/profile?data=change-password')
                ->withErrors(['Something Went Wrong Please Try Again.']);
        }

    }

    public function logout(Request $request)
    {
        /**
         * Used for Admin Logout
         * @return redirect to Admin->Logout
         */
        Auth::logout();
        return redirect('admin/login');
    }
}
