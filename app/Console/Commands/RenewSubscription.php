<?php

namespace App\Console\Commands;

use App\Models\AgencyModel;
use App\Models\StudentModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Matrix\Exception;
use Stripe;
use App\Models\NotificationMaster;
use App\Models\EmailSmsLog;
use App\Models\SettingModel;
use App\Models\UserModel;
use App\Models\EmailMasterModel;

/**
 * Class RenewSubscription
 * @package App\Console\Commands
 */
class RenewSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'renew:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renew Subscription';

    /**
     * @var SubscriptionModel
     */
    /**
     * @var StudentModel|SubscriptionModel
     */
    protected $subscriptionModel,$agencyModel,$subscriptionPlanModel;

    /**
     * RenewSubscription constructor.
     * @param SubscriptionModel $subscriptionModel
     * @param StudentModel $studentModel
     * @param SubscriptionPlanModel $subscriptionPlanModel
     */
    public function __construct(SubscriptionModel $subscriptionModel,
                                AgencyModel $agencyModel,
                                SubscriptionPlanModel $subscriptionPlanModel)
    {
        $this->subscriptionModel = $subscriptionModel;
        $this->agencyModel = $agencyModel;
        $this->subscriptionPlanModel = $subscriptionPlanModel;
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION'));
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = Carbon::now();
        $subscription =  $this->subscriptionModel->with(['plan'])
            ->where('end_date','>=',$today)
            ->whereNull('cancel_at')
            ->where('status',SubscriptionModel::STATUS_ACTIVE)->get();

        foreach ($subscription as $active){
            $active->end_date;
            $end_date = Carbon::parse($active->end_date);
            $diff = $end_date->diffInMinutes($today);
            if($diff < 5)
            {

                if($active->plan->is_deleted==1){
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
                        if($additional_agent1<1){
                            $additional_agent1=0;
                        }
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
                                'additional_agents_counts'=>0,
                                'additional_agent_price'=>0,
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
                        echo "done";
                    }catch (\Exception $e){
                        echo "<pre>"; 
                        print_r($e->getMessage());
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
