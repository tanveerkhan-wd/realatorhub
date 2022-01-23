<?php

namespace App\Http\Controllers\Common;

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
use App\Models\PropertyLeadsModel;
use Yajra\DataTables\DataTables;
use App\Models\AgencyRelationModel;
/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class CommonController extends Controller
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

    public function StripeWebhook(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            echo "<pre>";
            http_response_code(400);
            exit();
        }
        //echo "<pre>"; print_r($event); exit;
        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_failed':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // Then define and call a method to handle the successful payment intent.
                // handlePaymentIntentSucceeded($paymentIntent);
                $paymentIntent->customer_email;

                $agency_details=UserModel::where('email',$paymentIntent->customer_email)->orderBy('id','DESC')->with(['agency'])->first();
                $emailContent = EmailMasterModel::where('id', 12)->first();
            if(!empty($emailContent)){
                $subject = $emailContent->subject;
                $emailContent->content = str_replace('{{AGENCYNAME}}', $agency_details->agency->agency_name, $emailContent->content);
                $emailContent->content = str_replace('{{PLANNAME}}', $paymentIntent->lines->data[0]->price->nickname, $emailContent->content);

                $messageContent = $emailContent->content;
                $student_log = EmailSmsLog::create([
                    'user_id' => 69,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
                echo "<pre>"; print_r('done'); exit;
                break;
            case 'customer.subscription.trial_will_end':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                echo "<pre>"; print_r($paymentMethod); exit;
                break;
            // ... handle other event types
            case 'customer.subscription.trial_will_end':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // Then define and call a method to handle the successful attachment of a PaymentMethod.
                // handlePaymentMethodAttached($paymentMethod);
                echo "<pre>"; print_r($paymentMethod); exit;
                break;
            // ... handle other event types
            default:
                // Unexpected event type
                http_response_code(400);
                exit();
        }
        http_response_code(200);
    }
    public function listLeadsAjax(Request $request)
    {
        $input = $request->all();
        $leads = PropertyLeadsModel::select('property_leads.*','agency.slug')->leftJoin('agency','agency.user_id','=','property_leads.agency_id');
        if(Auth::user()->user_type==2){
            $leads =$leads->where('property_leads.agent_id',Auth::user()->id)->where('property_leads.is_deleted','0')->orderby('id','desc');
        }else{
            if(isset($input['agent_id']) && !empty($input['agent_id'])){
                $leads =$leads->where('property_leads.agent_id',$input['agent_id'])->where('property_leads.is_deleted','0')->orderby('id','desc');
            }else{
                $leads =$leads->where('property_leads.agency_id',Auth::user()->id)->where('property_leads.is_deleted','0')->orderby('id','desc');
            }
            
        }
        if(isset($input['title']) && !empty($input['title'])){
           $leads = $leads->where(function ($leads) use ($input) {
      
           $leads->where('property_leads.email','LIKE','%'.$input['title'].'%')
               ->orWhere('property_leads.customer_name','LIKE','%'.$input['title'].'%')
               ->orWhere('property_leads.address','LIKE','%'.$input['title'].'%')
               ->orWhere('property_leads.phone','LIKE','%'.$input['title'].'%');
      
         });          
        }
        if(isset($input['status']) && $input['status']!=''){
           $leads = $leads->where('property_leads.status','=',$input['status']);            
        }
        if(isset($input['address']) && $input['address']!=''){
           $leads = $leads->where('property_leads.address','LIKE','%'.$input['address'].'%');           
        }
      
        $table = Datatables::of($leads)
                ->editColumn('created_at', function ($leads) {
                    return date('d-m-Y', strtotime($leads->created_at));
                })
                ->rawColumns(['created_at'])
                ->make(true);
        return $table;
    }
    public function addNotes(Request $request){
        extract($_POST);
        try{
            $user_id=Auth::user()->id;
            if(Auth::user()->user_type==2){
                $leads = PropertyLeadsModel::where('id',$id)->where('agent_id',Auth::user()->id)->first();
            }else{
                $leads = PropertyLeadsModel::where('id',$id)->where('agency_id',Auth::user()->id)->first();
            }
            
            $date = date('Y-m-d H:i:s');
            if(!empty($leads)){
                $update = PropertyLeadsModel::where('id',$id)->update(['note'=>$note,'updated_at'=>$date]);
                return response()->json(['message' =>'Note Added Successfully.','code' => 200]);
            }
            else{
                return response()->json(['message' =>'Leads Not Found','code' => 201]);
            }
        }
        catch (Exception $e){
            return response()->json(['message' =>'Something Went Wrong Please Try Again.','code' => 201]);
        }
    }
    public function deleteLeads(Request $request,$id){
        try{
            $user_id=Auth::user()->id;
            if(Auth::user()->user_type==2){
                $leads = PropertyLeadsModel::where('id',$id)->where('agent_id',Auth::user()->id)->first();
            }else{
                $leads = PropertyLeadsModel::where('id',$id)->where('agency_id',Auth::user()->id)->first();
            }
            $date = date('Y-m-d H:i:s');
            if(!empty($leads)){
                $update = PropertyLeadsModel::where('id',$id)->update(['is_deleted'=>'1','updated_at'=>$date]);
                return response()->json(['message' =>'Lead Deleted Successfully','code' => 200]);
            }
            else{
                return response()->json(['message' =>'Leads Not Found','code' => 201]);
            }
        }
        catch (Exception $e){
            return response()->json(['message' =>'Something Went Wrong Please Try Again.','code' => 201]);
        }
    }
    public function changeStatus(Request $request){
        //echo "<pre>"; print_r($_POST); exit;
        extract($_POST);
        try{
            if(Auth::user()->user_type==2){
                $leads = PropertyLeadsModel::where('id',$id)->where('agent_id',Auth::user()->id)->first();
            }else{
                $leads = PropertyLeadsModel::where('id',$id)->where('agency_id',Auth::user()->id)->first();
            }
            $date = date('Y-m-d H:i:s');
            if(!empty($leads)){
                $update = PropertyLeadsModel::where('id',$id)->update(['status'=>$status,'updated_at'=>$date]);
                return response()->json(['message' =>'Lead Status Changed Successfully.','code' => 200]);
            }
            else{
                return response()->json(['message' =>'Leads Not Found','code' => 201]);
            }
        }
        catch (Exception $e){
            return response()->json(['message' =>'Something Went Wrong Please Try Again.','code' => 201]);
        }
    }
    public function getAgencyAgent(Request $request){
        //echo "<pre>"; print_r($_POST); exit;
        extract($_POST);
        $agencyData=AgencyRelationModel::where('user_id',$user_id)->first();
        $agentList=$this->userModel->select('users.*','agent.agent_unique_id')->leftJoin('agency_relation','agency_relation.user_id','=','users.id')->leftJoin('agent','agent.user_id','=','agency_relation.user_id')->where('users.id','!=',$user_id)->where('agency_relation.user_type',1)->where('agency_relation.agency_id',$agencyData->agency_id)->where('users.user_status',1)->orderby('users.id','desc')->get();
        $html='<option value="">Select Agent</option>';
        foreach($agentList as $agent){
            $html.='<option value="'.$agent->id.'">'.$agent->first_name.' '.$agent->last_name.'</option>';
        }
        return $html;
    }
    
}
