<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe;
use Yajra\DataTables\DataTables;
use App\Models\EmailMasterModel;
use App\Models\EmailSmsLog;
use App\Models\NotificationMaster;
use DB;


class SubscriptionController extends Controller
{
    protected $subscriptionPlanModel,$subscriptionModel,$user;

    public function __construct(SubscriptionPlanModel $subscriptionPlanModel,SubscriptionModel $subscriptionModel,
                                UserModel $user)
    {
        $this->subscriptionPlanModel = $subscriptionPlanModel;
        $this->subscriptionModel = $subscriptionModel;
        $this->user = $user;
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION'));
    }

    public function index(){
       /*$first_price = Stripe\Price::create([
            'nickname' => 'Monthly Base Fee1',
            'product' => 'prod_He2GZp9R8QCMyO',
            'unit_amount' => 500,
            'currency' => 'cad',
            'recurring' => [
                'interval' => 'month',
                'usage_type' => 'licensed',
            ],
        ]);
        $second_price = Stripe\Price::create([
            'nickname' => 'Per Car Monthly1',
            'product' => 'prod_He2GZp9R8QCMyO',
            'unit_amount' => 1500,
            'currency' => 'cad',
            'recurring' => [
                'interval' => 'month',
                'usage_type' => 'licensed',
            ],
        ]);*/
       /* Stripe\Subscription::create([
            'customer' => 'cus_HeKiK6fG84zPiN',
            'items' => [
                [
                    'price' => 'price_1H4mJNB10hCcq9iDDqmAv4QN',
                    'quantity' => 1,
                ],
                [
                    'price' => 'price_1H4mJMB10hCcq9iDwyRj27Um',
                    'quantity' => 3,
                ],
            ],
        ]);*/
        /**/
        /*$subscription = \Stripe\Subscription::create([
            'customer' => 'cus_4fdAW5ftNQow1a',
            'items' => [
                [
                    'price' => 'price_CBb6IXqvTLXp3f',
                ],
            ],
            'trial_end' => 1595332742,
        ]);*/
//        $retrive= Stripe\Product::retrieve('prod_He2GZp9R8QCMyO');
        return view('admin.subscription.index');
    }
    public function listPlansAjax(Request $request){
        $input = $request->all();
        $plans = $this->subscriptionPlanModel->select('subscription_plans.*','s.subscriptions_count')->leftJoin(DB::raw('(SELECT plan_id, COUNT(plan_id) as subscriptions_count FROM `subscriptions` where status=1 OR (status=2 AND cancel_at > now()) GROUP BY plan_id) as s'), 
        function($join)
        {
           $join->on('subscription_plans.id', '=', 's.plan_id');
        });
        //$plans = $this->subscriptionPlanModel->withCount(['subscriptionsTotal']);
        //$plans = $plans->where('is_deleted',SubscriptionPlanModel::IS_DELETED_NO);
        $plans = $plans->orderBy('subscription_plans.id', 'desc');
        $table = DataTables::of($plans)->make(true);
        return $table;
    }

    public function showPlan(Request $request,$id){
        try{
            $plan  = $this->subscriptionPlanModel->where('id',$id)->first();

            return view('admin.subscription.view',compact('plan') );

        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
        }
    }

    public function deletePlan(Request $request,$id){
        try {
            $plans = $this->subscriptionPlanModel->where('id', $id)->update(['is_deleted'=>SubscriptionPlanModel::IS_DELETED_YES]);
            $cancelSubscriptionData = $this->subscriptionModel
                ->where('plan_id',$id)->get();
            //echo "<pre>"; print_r($cancelSubscriptionData->toArray()); exit;
            if(!empty($cancelSubscriptionData)){
                foreach ($cancelSubscriptionData as $cancelData){
                    $subscription = Stripe\Subscription::retrieve($cancelData->subscription_id);
                    $subscription->delete();
                    $cancel_at = date('Y-m-d H:i:s',$subscription->canceled_at);

                    $this->subscriptionModel->where('id',$cancelData->id)->update([
                        'cancel_at' => $cancel_at,
                        'status'=>SubscriptionModel::STATUS_CANCEL
                    ]);
                    $emailContent = EmailMasterModel::where('id', '=', 21)->first();
                    $agency_details = UserModel::where('id',$cancelData->user_id)->with(['agency'])->first();
                    if(!empty($emailContent)){
                        $subject = $emailContent->subject;
                        $emailContent->content = str_replace('{{AGENCYNAME}}', $agency_details->agency->agency_name, $emailContent->content);

                        $messageContent = $emailContent->content;
                        $student_log = EmailSmsLog::create([
                            'user_id' => $cancelData->user_id,
                            'subject' => $subject,
                            'email_content' => $messageContent,
                            'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                        ]);

                    }
                    $notification=array('from_user_id'=>1,'to_user_id'=>$cancelData->user_id,'link'=>'/subscription','active_flag'=>0,'notification_desc'=>'Your active subscription has been deleted by admin please change your subscription plan to continue using the system');
                    //echo "<pre>"; print_r($notification); exit;
                    NotificationMaster::insert($notification);
                }
            }
            return response()->json(['message' => 'Subscription Plan Deleted Successfully', 'code' => 200]);
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return response()->json(['message' => $e->getMessage(), 'code' => 500]);
        }
    }

    public function subscription()
    {
        return view('admin.subscription.add');
    }

    public function storePlan(Request $request){
        try{
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                'plan_name' => 'required',
                'monthly_price' => 'required',
                'no_of_agent' => 'required',
                'additional_agent' => 'required',
                'additional_agent_per_rate' => 'required',
                'about_plan' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first(), 'code' => 301]);
            }

            $amount = $input['monthly_price'];


            $product = Stripe\Product::create([
                'name' => $input['plan_name'],
                'description'=>$input['about_plan']
            ]);
            $additional_price_agent= ( $input['additional_agent_per_rate']);

            $price_id_1 = Stripe\Price::create([
                'nickname' =>  $input['plan_name'],
                'product' => $product->id,
                'unit_amount' => ($input['monthly_price']*100),
                'currency' => 'cad',
                'recurring' => [
                    'interval' => 'month',
                    'usage_type' => 'licensed',
                ],
            ]);

            $price_id_2 = Stripe\Price::create([
                'nickname' =>  $input['plan_name'],
                'product' => $product->id,
                'unit_amount' => ($additional_price_agent*100),
                'currency' => 'cad',
                'recurring' => [
                    'interval' => 'month',
                    'usage_type' => 'licensed',
                ],
            ]);

            $storePlan = $this->subscriptionPlanModel->create([
                'plan_id' => $price_id_1->id,
                'additional_price_id'=>$price_id_2->id,
                'plan_name' => $input['plan_name'],
                'monthly_price'=>$amount,
                'no_of_agent'=>$input['no_of_agent'],
                'additional_agent'=>$input['additional_agent'],
                'additional_agent_per_rate'=>$input['additional_agent_per_rate'],
                'description'=>$input['about_plan']
            ]);

            return response()->json(['message' => 'Subscription Plan Added Successfully', 'code' => 200]);
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return response()->json(['message' => 'Something Went Wrong Please Try Again.', 'code' => 500]);
        }

    }


}
