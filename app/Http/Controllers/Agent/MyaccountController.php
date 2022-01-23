<?php

namespace App\Http\Controllers\Agent;

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

class MyaccountController extends Controller {

    public function index(Request $request) {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        $country_code = CountryCodeModel::where('calling_code', '!=', '')->get();
        $timezones = getTimeZones();
        $user_id = Auth::user()->id;
        $agency_data = UserModel::where('id', '=', $user_id)->first();
        $data = SettingModel::where('user_id', $user_id)->get()->toArray();
        $data = array_column($data, 'text_value', 'text_key');
        $agent_id = \App\Models\AgentModel::where('user_id', '=', $user_id)->first();
        //echo "<pre>"; print_r($agency_data->agency->agency_logo); exit;
        return view('agent.myaccount.myaccount', compact('country_code', 'timezones', 'agency_data', 'data', 'agent_id'));
    }

    public function editMyProfile(Request $request) {
        try {
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
              return redirect('agency/my-account')->withErrors(['This email id is already used.']);
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
            return redirect()->route('agent.my.account')->with('success', 'Profile Updated Successfully');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->route('agent.my.account')->withErrors([$e->getMessage()]);
        }
    }

    public function changeEmail(Request $request) {

        $input = $request->all();
        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail(Auth::user()->id);
        $emailcout = UserModel::where('email', $input['email'])->where('id', '!=', Auth::user()->id)->count();
        if ($emailcout > 0) {
            return response()->json(['message' => 'This Email Address Is Already In Use.', 'code' => 201]);
        }
        $userdata = UserModel::where('id', Auth::user()->id)->first();
        if ($userdata->email != $input['email']) {
            $otp = mt_rand(100000, 999999);
            $emailContent = EmailMasterModel::where('id', 8)->first();
            $messageContent = $emailContent->content;
            $subject = 'Agent Edit Profile - Change Email OTP';
            $name = $user->first_name;
//            $messageContent = str_replace('{{USER_NAME}}',$name , $emailContent->content);
            $messageContent = str_replace('{{OTP}}', $otp, $emailContent->content);
            try {
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent));
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
        if (isset($input['old_password']) && $input['old_password'] != null && !empty($input['old_password'])) {

            if (Hash::check($input['old_password'], Auth::user()->password)) {
                // The passwords match...
            } else {
                return redirect('agent/my-account?data=changePassword')
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

            Mail::to($user->email)->send(new ForgotPassword($subject, $messageContent));
            return redirect('agent/my-account?data=changePassword')->with('success', 'Password Updated Successful');
        } else {
            return redirect('agent/my-account?data=changePassword')
                            ->withErrors(['Something Wrong Please try again Later']);
        }
    }

}
