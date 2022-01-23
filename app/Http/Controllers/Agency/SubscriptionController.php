<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\AgencyModel;
use App\Models\AgencyRelationModel;
use App\Models\AgentModel;
use App\Models\EmailMasterModel;
use App\Models\EmailSmsLog;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\UserCardsModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Exception;
use Stripe;
use Carbon\Carbon;
use App\Models\NotificationMaster;
use App\Models\SettingModel;
//use App\Models\EmailSmsLog;
//use App\Models\EmailMasterModel;
/**
 * Class SubscriptionController
 * @package App\Http\Controllers\Agency
 */
class SubscriptionController extends Controller
{
    /**
     * @var SubscriptionPlanModel
     */
    protected $subscriptionPlanModel,$subscriptionModel,$agencyModel,$agencyRelationModel,
        $useCardsModel,$userModel,$settingModel;


    /**
     * SubscriptionController constructor.
     * @param SubscriptionPlanModel $subscriptionPlanModel
     * @param SubscriptionModel $subscriptionModel
     * @param AgentModel $agentModel
     */
    public function __construct(SubscriptionPlanModel $subscriptionPlanModel,
                                SubscriptionModel $subscriptionModel,
                                AgencyModel $agencyModel,
                                UserModel $userModel,
                                AgencyRelationModel $agencyRelationModel,UserCardsModel $useCardsModel, SettingModel $settingModel)
    {
        $this->subscriptionPlanModel = $subscriptionPlanModel;
        $this->subscriptionModel = $subscriptionModel;
        $this->agencyModel = $agencyModel;
        $this->agencyRelationModel = $agencyRelationModel;
        $this->useCardsModel = $useCardsModel;
        $this->userModel = $userModel;
        $this->settingModel = $settingModel;
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){

        $user_id = Auth::user()->id;
        $today = date('Y-m-d H:i:s');
        $free_trial_count = $this->subscriptionModel->where('user_id',$user_id)->count();
        $free_trial = (!empty($free_trial_count) && $free_trial_count == 1)?'1':'0';
        $subscriptionPlans = $this->subscriptionPlanModel->withCount(['subscriptions'])
            ->orderBy('monthly_price','ASC')->get();
        $activeSubscription = $this->subscriptionModel
            ->with(['plan'])
            ->where('user_id',$user_id)
            //->where('status',SubscriptionModel::STATUS_ACTIVE)
            ->orderBy('id','DESC')->first();

        $renew_date= '';
        $total_price= 0;
        $cancelSubscription = [];
        $expiry_date= date('d-m-Y');
        $enableUpgradeDowngrade = 0;
        $userCards = $this->useCardsModel->where('user_id',$user_id)->count();
        $notFoundUserCard = 0;
        if($userCards > 0){
            $notFoundUserCard = 1;
        }

        if(!empty($activeSubscription)){
            $timezone = getCurrentUserTimeZone(Auth::User()->id);
            $start_time = changeTimeByTimezone($activeSubscription->end_date,$timezone);
            //$start_time = $activeSubscription->end_date;
            $renew_date = date('Y-m-d',strtotime($start_time));
            $total_price =(($activeSubscription->base_price)+(($activeSubscription->additional_agents_counts )*($activeSubscription->additional_agent_price)));
            $current_date = date('Y-m-d');
            $upgrade_date = date("Y-m-d", strtotime("-1 days", strtotime($activeSubscription->end_date)));
            if($current_date == $renew_date){
                $enableUpgradeDowngrade = 1;
            }
            if($current_date == $upgrade_date){
                $enableUpgradeDowngrade = 1;
            }
        }
            $cancelSubscription = $this->subscriptionModel->with('plan')->where('user_id',$user_id)
//            ->where('status',SubscriptionModel::STATUS_CANCEL)
                ->where('cancel_at','>=',$today)
                ->first();
            $currentPlanCancelled = 0;
            if(!empty($cancelSubscription)){
                $timezone = getCurrentUserTimeZone(Auth::User()->id);
                $start_time = changeTimeByTimezone($cancelSubscription->cancel_at,$timezone);
                $expiry_date = getDateFormate(Auth::User()->id,$start_time);
                //echo $expiry_date; exit;
                $currentPlanCancelled = 1;
            }


        return view('agency.subscription.index',compact('subscriptionPlans','cancelSubscription','notFoundUserCard',
            'free_trial','activeSubscription','currentPlanCancelled','renew_date','total_price','expiry_date','enableUpgradeDowngrade'));
    }

    public function transaction(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            return view('agency.subscription.transaction');
        }catch (\Exception $e){
            \Log::error($e->getMessage());

        }

    }

    public function transactionAjax(Request $request){
        try {
            $input = $request->all();
            $user_id = Auth::user()->id;
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $subscriptionData =$this->subscriptionModel
                ->with(['plan','user.agency']);
            if (!empty($start_date) && !empty($end_date)) {
                $subscriptionData = $subscriptionData
                    ->whereBetween('start_date', [$start_date, $end_date]);
            }
            if(!empty($search_plan)){
                $subscriptionData = $subscriptionData->where('plan_id',$search_plan);
            }
            if(!empty($search_agency)){
                $subscriptionData = $subscriptionData->where('user_id',$search_agency);
            }
            $subscriptionData = $subscriptionData->where('user_id',$user_id)->orderByDesc('subscriptions.id');
            $table = \DataTables::of($subscriptionData)->make(true);
            return $table;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
    }

    public function upgradeSubscription(Request $request){
        //echo "<pre>"; print_r($request->all()); exit;
        try{
            $today = date('Y-m-d H:i:s');
            $plan_id = $request->get('plan_id');
            $user_id = Auth::user()->id;
            $active_subscription_id = $request->get('active_subscription_id');

            $plan = $this->subscriptionPlanModel->where('id',$plan_id)->first();
            $agency =$this->agencyModel->where('user_id',$user_id)->first();
            $additional_agent_agency = $this->agencyRelationModel->with('user')->where('agency_id',$user_id)
                ->where('user_type',AgencyRelationModel::AGENY_USER_TYPE)
                ->whereHas('user', function($q)  {
                    $q->whereNull('deleted_at');
                })
                ->count();
            $total_agency_agenct_count = $additional_agent_agency;
            if($plan->no_of_agent > $total_agency_agenct_count){
                $additional_agent = 0;
                $additional_agent_total_amount=0;
            }
            else{
                $additional_agent = $total_agency_agenct_count - $plan->no_of_agent;
                $additional_agent_total_amount = $additional_agent * $plan->additional_agent_per_rate;
            }
            $total_amount=$plan->monthly_price+$additional_agent_total_amount;
            if(empty($additional_agent)) {
                $subscription = Stripe\Subscription::create([
                    'customer' => $agency->stripe_customer_id,
                    'items' => [
                        [
                            'price' => $plan->plan_id,
                            'quantity' => 1,
                        ],

                    ],
                ]);
            }
            else{
                $subscription = Stripe\Subscription::create([
                    'customer' => $agency->stripe_customer_id,
                    'items' => [
                        [
                            'price' => $plan->plan_id,
                            'quantity' => 1,
                        ],
                        [
                            'price' => $plan->additional_price_id,
                            'quantity' => $additional_agent,
                        ],

                    ],
                ]);
            }
            $invoice = Stripe\Invoice::retrieve($subscription->latest_invoice);
            /*$invoice = Stripe\Invoice::retrieve($subscription->latest_invoice);
            echo "<pre>"; print_r($invoice); exit;*/
            $start_date = date('Y-m-d H:i:s',$subscription->current_period_start);
            $end_date = date('Y-m-d H:i:s',$subscription->current_period_end);
            $storeSubscriptions = $this->subscriptionModel->create(
                [
                    'user_id'=>$user_id,
                    'plan_id'=>$plan_id,
                    'subscription_id'=>$subscription->id,
                    'start_date'=>$start_date,
                    'end_date'=>$end_date,
                    'total_no_of_agent'=>$total_agency_agenct_count,
                    'additional_agent'=>$additional_agent,
                    'total_amount'=>$total_amount,
                    'base_price'=>$plan->monthly_price,
                    'additional_agents_counts'=>0,
                    'additional_agent_price'=>0,
                    'invoice_url'=>$invoice->hosted_invoice_url,
                ]
            );
           /* if(!empty($active_subscription_id)){
                $remove_subscription = Stripe\Subscription::retrieve($active_subscription->subscription_id);
                $remove_subscription->delete();
                $cancel_at = date('Y-m-d H:i:s');

                $this->subscriptionModel->where('id',$active_subscription->id)->update([
                    'cancel_at' => $cancel_at,
                    'status'=>SubscriptionModel::STATUS_CANCEL
                ]);
            }*/

            $cancelSubscription = $this->subscriptionModel->with('plan')
                ->where('user_id',$user_id)
                ->where('id','!=',$storeSubscriptions->id)
//                ->where('cancel_at','>=',$today)
                ->where('status',SubscriptionModel::STATUS_ACTIVE)
                ->get();
            if(!empty($cancelSubscription)){
                foreach ($cancelSubscription as $c_subscription) {
                    $remove_subscription = Stripe\Subscription::retrieve($c_subscription->subscription_id);
                    $remove_subscription->delete();
                    $cancel_at = date('Y-m-d H:i:s');

                    $this->subscriptionModel->where('id', $c_subscription->id)->update([
                        'cancel_at' => $cancel_at,
                        'status' => SubscriptionModel::STATUS_CANCEL
                    ]);
                }
            }
            $emailContent = EmailMasterModel::where('id', 20)->first();
            $agency_details = UserModel::where('id',Auth::user()->id)->with(['agency'])->first();
            if(!empty($emailContent)){
                $subject = $emailContent->subject;
                $emailContent->content = str_replace('{{AGENCYNAME}}', $agency_details->agency->agency_name, $emailContent->content);

                $messageContent = $emailContent->content;
                $student_log = EmailSmsLog::create([
                    'user_id' => Auth::user()->id,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
          $notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>1,'link'=>'/admin/transactions','active_flag'=>0,'notification_desc'=>$agency_details->agency->agency_name.' is subscribed to '.$plan->plan_name.' plan');
                //echo "<pre>"; print_r($notification); exit;
                NotificationMaster::insert($notification);
//        $message_noti = $student->full_name.' has Cancelled plan '.$subscription->plan->plan_name;
//        createNotification('1','1', $message_noti,'admin/subscriptions');
//        sendNotificationWeb($student->id,$message_noti);

            return response()->json(['message' => 'Your plan is upgraded successfully.', 'data' => '', 'code' => 200]);

        }
        catch (\Exception $exception){
            \Log::error($exception->getMessage());
            return response()->json(['message' => $exception->getMessage(), 'data' => '', 'code' => 500]);
        }
    }


    public function cancelSubscription(Request $request){
        try{
            $plan_id = $request->get('plan_id');
            $subscription_id = $request->get('active_subscription_id');
            if (!empty($subscription_id)){
                $subscription = $this->subscriptionModel->where('id', $subscription_id)->first();
                $cancel = $this->cancel($subscription->subscription_id,$subscription_id);
            }
            return response()->json(['message' => 'Your plan is cancelled successfully.', 'data' => '', 'code' => 200]);
        }
        catch (\Exception $exception){
            \Log::error($exception->getMessage());
            return response()->json(['message' => $exception->getMessage(), 'data' => '', 'code' => 500]);
        }
    }

    public function cancel($subscription_id,$id){
        $updateSubscrition = Stripe\Subscription::update($subscription_id, [
            ['cancel_at_period_end' => true]
        ]);

        $subscription = Stripe\Subscription::retrieve($subscription_id);
        $cancel_at = date('Y-m-d H:i:s',$subscription->cancel_at);

        $subscription = $this->subscriptionModel->where('id', $id)->update([
            'cancel_at' => $cancel_at,
//            'status'=>SubscriptionModel::STATUS_CANCEL
        ]);
    }

    public function paymentSetting(Request $request){
        try {

            $user_id = Auth::user()->id;
            $userCards = $this->useCardsModel->where('user_id',$user_id)->get();
            return view('agency.subscription.payment-setting',compact('userCards'));
        }
        catch (\Exception $exception){
            \Log::error($exception->getMessage());
        }
    }

    public function addCardModal(Request $request)
    {
        try {
            $data = [];
            $modelView = view('agency.subscription.card-modal')->render();
            $data['view'] = $modelView;
            return response()->json(['message' => 'Please add your card.', 'data' => $data, 'code' => 200]);
        }
        catch (Exception $exception){
            \Log::error($exception->getMessage());
        }

    }

    public function storeCard(Request $request){
        try {
            $stripe_id = $request->get('stripe_id');
            $card_name = $request->get('name');
            $user_id = Auth::User()->id;
            $user = $this->userModel->with('agency')->where('id',$user_id)->first();
            try {
                $stripe_customer_id = $user->agency->stripe_customer_id;
                if (empty($stripe_customer_id)) {
                    $resCustomer = Stripe\Customer::create([
                        'description' => $user->name . ' #' . $user->id,
                        'email' => $user->email
                    ]);

                    $stripe_customer_id = $resCustomer->id;
                    $user = $this->agencyModel->where('user_id', $user_id)->update(['stripe_customer_id' => $stripe_customer_id]);

                }

                $stripe = Stripe\PaymentMethod::retrieve(
                    $stripe_id
                );
                Stripe\Customer::update($stripe_customer_id, [
                    'invoice_settings' => [
                        'default_payment_method' => $stripe_id
                    ]
                ]);


                $updateCard = $this->useCardsModel->updateOrCreate(
                    ['user_id'=>Auth::user()->id],
                    ['stripe_id'=>$stripe_id,'card_last_four'=>$stripe->card->last4]);

                $old_card = $this->useCardsModel->where('user_id',Auth::user()->id)->where('stripe_id','!=',$stripe_id)->first();
                if(!empty($old_card)) {
                    $payment_method = Stripe\PaymentMethod::retrieve(
                        $old_card->stripe_id
                    );
                    $payment_method->detach();
                    $updateCustomer = Stripe\PaymentMethod::delete($old_card->stripe_id);
                    $this->useCardsModel->where('id',$old_card->id)->delete();
                }

                return response()->json(
                    [
                        'success' => true,
                        'status' => 200,
                        'data' => [
                        ],
                        'message' => 'Your card has been updated successfully.'
                    ]
                );
            } catch (Stripe\Error\Card $e) {
                \Log::error($e->getMessage());
                $error_message = $e->getMessage();
            } catch (Stripe\Error\InvalidRequest $e) {
                \Log::error($e->getMessage());
                $error_message = $e->getMessage();
            } catch (Stripe\Error\ApiConnection $e) {
                \Log::error($e->getMessage());
                $error_message = $e->getMessage();
            } catch (Stripe\Error\Base $e) {
                \Log::error($e->getMessage());
                $error_message = $e->getMessage();
            } catch (Exception $e) {
                \Log::error($e->getMessage());
                $error_message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => $error_message
            ], 400);
        }catch (\Exception $exception){
            \Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => $exception->getMessage()
            ], 400);
        }
    }

    public function deleteCard(Request $request){
        $user = Auth::guard()->user();
        $user_id = Auth::User()->id;
        $card_id = $request->get('card_id');
        try {
            if (!empty($card_id)) {
                $cardInfo = $this->useCardsModel->where([
                    ['user_id', '=', $user_id],
                    ['stripe_id', '=', $card_id],
                ])->first();
                if (isset($cardInfo)) {
                    $payment_method = Stripe\PaymentMethod::retrieve(
                        $cardInfo->stripe_id
                    );
                    $payment_method->detach();

                    try {

                        $this->useCardsModel->where([
                            ['user_id', '=', $user_id],
                            ['stripe_id', '=', $card_id],
                        ])->delete();

                        $subscription = $this->subscriptionModel
                            ->with(['plan'])
                            ->where('user_id',$user_id)
                            ->where('status',SubscriptionModel::STATUS_ACTIVE)
                            ->first();

                        $cancel = $this->cancel($subscription->subscription_id,$subscription->id);


                        return response()->json(
                            [
                                'success' => true,
                                'status' => 200,
                                'message' => 'Your card has been deleted successfully.'
                            ]
                        );
                    } catch (Stripe\Error\Card $e) {
                        $error_message = $e->getMessage();
                    } catch (Stripe\Error\InvalidRequest $e) {
                        $error_message = $e->getMessage();
                    } catch (Stripe\Error\ApiConnection $e) {
                        $error_message = $e->getMessage();
                    } catch (Stripe\Error\Base $e) {
                        $error_message = $e->getMessage();
                    } catch (Exception $e) {
                        $error_message = $e->getMessage();
                    }
                } else {
                    $error_message = 'Invalid Card Id';
                }
            } else {
                $error_message = 'Something Went Wrong Please Try Again.';
            }

            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => $error_message
            ], 400);
        }
        catch(\Matrix\Exception $exception){
            \Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
                'status' => 400,
                'message' => 'Something Went Wrong Please Try Again.'
            ], 400);
        }
    }

    public function activeSubscription(Request $request){
        try{
            $subscription_id =$request->get('active_subscription_id');
            $plan_id =$request->get('plan_id');
            $subscription = $this->subscriptionModel->where('id',$subscription_id)->first();

             $updateSubscrition = Stripe\Subscription::update($subscription->subscription_id, [
                    ['cancel_at_period_end' => false]
                ]);

                $subscription = $this->subscriptionModel->where('id', $subscription_id)->update([
                    'cancel_at' => NULL,
                    'status'=>SubscriptionModel::STATUS_ACTIVE
                ]);
            return response()->json([
                'success' => false,
                'code' => 200,
                'message' => 'Your plan is activated successfully.'
            ], 200);
        }
        catch (Exception $exception){
            \Log::error($exception->getMessage());
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
    public function test(Request $request)
    {
        /*$date = date('Y-m-d H:i:s');
        $user_id=112;
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
            echo "done";
            exit;*/
        echo $today = Carbon::now();
        $subscription =  $this->subscriptionModel->with(['plan'])
            ->where('end_date','>=',$today)
            ->whereNull('cancel_at')
            ->where('status',SubscriptionModel::STATUS_ACTIVE)->orderBy('id','DESC')->get();
            //echo "<pre>"; print_r($subscription->toArray());exit;
        foreach ($subscription as $active){
            $active->end_date;
            $end_date = Carbon::parse($active->end_date);
            $diff = $end_date->diffInMinutes($today);
            if($diff < 5)
            {
                
                if($active->plan->is_deleted==1){
                    exit;
                    $subscription = Stripe\Subscription::retrieve($active->subscription_id);
                        $subscription->delete();
                        $cancel_at = date('Y-m-d H:i:s',$subscription->canceled_at);
                        $this->subscriptionModel->where('id',$active->id)->update([
                            'cancel_at' => $cancel_at,
                            'status'=>SubscriptionModel::STATUS_CANCEL
                        ]);
                        $agency_details=UserModel::where('id',$active->user_id)->orderBy('id','DESC')->with(['agency'])->first();
                        $emailContent = EmailMasterModel::where('id', 12)->first();
                        if(!empty($emailContent)){
                            $subject = $emailContent->subject;
                            $emailContent->content = str_replace('{{AGENCYNAME}}', $agency_details->agency->agency_name, $emailContent->content);
                            $emailContent->content = str_replace('{{PLANNAME}}', $active->plan['plan_name'], $emailContent->content);

                            $messageContent = $emailContent->content;
                            $student_log = EmailSmsLog::create([
                                'user_id' =>$active->user_id,
                                'subject' => $subject,
                                'email_content' => $messageContent,
                                'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                            ]);

                        }
                        $notification=array('from_user_id'=>1,'to_user_id'=>1,'link'=>'/transactions','active_flag'=>0,'notification_desc'=>$agency_details->agency->agency_name.' payment has failed.');
                        //echo "<pre>"; print_r($notification); exit;
                        NotificationMaster::insert($notification);
                        $notification=array('from_user_id'=>1,'to_user_id'=>$active->user_id,'link'=>'/subscription/transactions','active_flag'=>0,'notification_desc'=>$agency_details->agency->agency_name.' payment has failed.');
                        //echo "<pre>"; print_r($notification); exit;
                        NotificationMaster::insert($notification);
                }else{
                    echo $active->user_id;
                    $agency = $this->agencyModel->where('user_id',$active->user_id)->first();
                    $subscription_id = $active->subscription_id;
                    try {
                        $additional_agent = $active->additional_agents_count;
                        $additional_agent_price = $additional_agent * $active->plan->additional_agent_per_rate;
                        if(!empty($active->additional_agent_price)) {
                            $total_amount = ($active->additional_agent_price) + ($active->base_price);
                        }
                        else{
                            $total_amount = $active->base_price;
                        }
                        if(empty($additional_agent)) {
                            $subscription = Stripe\Subscription::create([
                                'customer' => $agency->stripe_customer_id,
                                'items' => [
                                    [
                                        'price' => $active->plan->plan_id,
                                        'quantity' => 1,
                                    ],

                                ],
                            ]);
                        }
                        else{
                            $subscription = Stripe\Subscription::create([
                                'customer' => $agency->stripe_customer_id,
                                'items' => [
                                    [
                                        'price' => $active->plan->plan_id,
                                        'quantity' => 1,
                                    ],
                                    [
                                        'price' => $active->plan->additional_price_id,
                                        'quantity' => $additional_agent,
                                    ],

                                ],
                            ]);
                        }
                        $invoice = Stripe\Invoice::retrieve($subscription->latest_invoice);
                        $start_date = date('Y-m-d H:i:s',$subscription->current_period_start);
                        $end_date = date('Y-m-d H:i:s',$subscription->current_period_end);
                        $additional_agent1 =$active->total_no_of_agent-$active->plan->no_of_agent;
                        $storeSubscriptions = $this->subscriptionModel->create(
                            [
                                'user_id'=>$active->user_id,
                                'plan_id'=>$active->plan_id,
                                'subscription_id'=>$subscription->id,
                                'start_date'=>$start_date,
                                'end_date'=>$end_date,
                                'total_no_of_agent'=>$active->total_no_of_agent,
                                'additional_agent'=>$additional_agent1,
                                'total_amount'=>$total_amount,
                                'base_price'=>$active->plan->monthly_price,
                                'additional_agents_counts'=>$additional_agent1,
                                'additional_agent_price'=>$additional_agent_price,
                                'invoice_url'=>$invoice->hosted_invoice_url,
                            ]
                        );
                        $update_status = $this->subscriptionModel
                            ->where('id',$active->id)
                            ->update(['status'=>SubscriptionModel::STATUS_COMPLETED]);
                        $agency_details=UserModel::where('id',$active->user_id)->orderBy('id','DESC')->with(['agency'])->first();
                        $emailContent = EmailMasterModel::where('id', 22)->first();
                        if(!empty($emailContent)){
                            $subject = $emailContent->subject;
                            $emailContent->content = str_replace('{{AGENCYNAME}}', $agency_details->agency->agency_name, $emailContent->content);
                            $emailContent->content = str_replace('{{PLANNAME}}', $active->plan['plan_name'], $emailContent->content);

                            $messageContent = $emailContent->content;
                            $student_log = EmailSmsLog::create([
                                'user_id' =>$active->user_id,
                                'subject' => $subject,
                                'email_content' => $messageContent,
                                'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                            ]);

                        }
                        $notification=array('from_user_id'=>1,'to_user_id'=>1,'link'=>'/transactions','active_flag'=>0,'notification_desc'=>$agency_details->agency->agency_name.' Subscription has been renewed.');
                        //echo "<pre>"; print_r($notification); exit;
                        NotificationMaster::insert($notification);
                        $notification=array('from_user_id'=>1,'to_user_id'=>$active->user_id,'link'=>'/subscription/transactions','active_flag'=>0,'notification_desc'=>'Your Subscription has been renewed.');
                        //echo "<pre>"; print_r($notification); exit;
                        NotificationMaster::insert($notification);
                        echo "Done";
                    }catch (\Exception $e){
                        echo "<pre>"; 
                        print_r($e->getMessage());
                        echo "Hello"; exit;
                        $subscription = Stripe\Subscription::retrieve($active->subscription_id);
                        $subscription->delete();
                        $cancel_at = date('Y-m-d H:i:s',$subscription->canceled_at);
                        $this->subscriptionModel->where('id',$active->id)->update([
                            'cancel_at' => $cancel_at,
                            'status'=>SubscriptionModel::STATUS_CANCEL
                        ]);
                        $agency_details=UserModel::where('id',$active->user_id)->orderBy('id','DESC')->with(['agency'])->first();
                        $emailContent = EmailMasterModel::where('id', 12)->first();
                        if(!empty($emailContent)){
                            $subject = $emailContent->subject;
                            $emailContent->content = str_replace('{{AGENCYNAME}}', $agency_details->agency->agency_name, $emailContent->content);
                            $emailContent->content = str_replace('{{PLANNAME}}', $active->plan['plan_name'], $emailContent->content);

                            $messageContent = $emailContent->content;
                            $student_log = EmailSmsLog::create([
                                'user_id' =>$active->user_id,
                                'subject' => $subject,
                                'email_content' => $messageContent,
                                'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                            ]);

                        }
                        $notification=array('from_user_id'=>1,'to_user_id'=>1,'link'=>'/transactions','active_flag'=>0,'notification_desc'=>$agency_details->agency->agency_name.' payment has failed.');
                        //echo "<pre>"; print_r($notification); exit;
                        NotificationMaster::insert($notification);
                        $notification=array('from_user_id'=>1,'to_user_id'=>$active->user_id,'link'=>'/subscription/transactions','active_flag'=>0,'notification_desc'=>$agency_details->agency->agency_name.' payment has failed.');
                        //echo "<pre>"; print_r($notification); exit;
                        NotificationMaster::insert($notification);
                    }
                }
                


            }


        }
        

    }

}
