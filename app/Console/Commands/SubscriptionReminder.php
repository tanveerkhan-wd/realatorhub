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
use App\Models\EmailMasterModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
/**
 * Class RenewSubscription
 * @package App\Console\Commands
 */
class SubscriptionReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscription Reminder';

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
        $date = date("Y-m-d", strtotime("+5 day"));
        $newdate = date("d-m-Y", strtotime("+5 day"));
        $subscription =  $this->subscriptionModel->with(['plan'])
            ->whereDate('end_date',$date)
            ->whereNull('cancel_at')
            ->where('status',SubscriptionModel::STATUS_ACTIVE)->get();
        //echo "<pre>"; print_r($subscription->toArray()); exit;
        foreach ($subscription as $active){
            $user = UserModel::where('id',$active->user_id)->with(['agency'])->first();
            $emailContent = EmailMasterModel::where('id',11)->first();
            if(!empty($emailContent)){
                $subject = $emailContent->subject;
                if(!empty($user->agency->agency_name)){
                    $emailContent->content = str_replace('{{AGENCYNAME}}',$user->agency->agency_name, $emailContent->content);
                    $emailContent->content = str_replace('{{PLANNAME}}',$active->plan['plan_name'], $emailContent->content);
                    $emailContent->content = str_replace('{{DATE}}',$newdate, $emailContent->content);
                }
                
                $messageContent = $emailContent->content;
                $student_log = EmailSmsLog::create([
                    'user_id' => $active->user_id,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
            $notification=array('from_user_id'=>1,'to_user_id'=>$user->id,'link'=>'/agency/subscription','active_flag'=>0,'notification_desc'=>'Your current plan will be automatically deactivated on '.$newdate);
                //echo "<pre>"; print_r($notification); exit;
                NotificationMaster::insert($notification);
        }
        $date = date("Y-m-d", strtotime("+1 day"));
        $newdate = date("d-m-Y", strtotime("+5 day"));
        $subscription =  $this->subscriptionModel->with(['plan'])
            ->whereDate('end_date',$date)
            ->whereNull('cancel_at')
            ->where('status',SubscriptionModel::STATUS_ACTIVE)->get();
        //echo "<pre>"; print_r($subscription->toArray()); exit;
        foreach ($subscription as $active){
            $user = UserModel::where('id',$active->user_id)->with(['agency'])->first();
            $emailContent = EmailMasterModel::where('id',11)->first();
            if(!empty($emailContent)){
                $subject = $emailContent->subject;
                if(!empty($user->agency->agency_name)){
                    $emailContent->content = str_replace('{{AGENCYNAME}}',$user->agency->agency_name, $emailContent->content);
                    $emailContent->content = str_replace('{{PLANNAME}}',$active->plan['plan_name'], $emailContent->content);
                    $emailContent->content = str_replace('{{DATE}}',$newdate, $emailContent->content);
                }
                
                $messageContent = $emailContent->content;
                $student_log = EmailSmsLog::create([
                    'user_id' => $active->user_id,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
            $notification=array('from_user_id'=>1,'to_user_id'=>$user->id,'link'=>'/agency/subscription','active_flag'=>0,'notification_desc'=>'Your current plan will be automatically deactivated on '.$newdate);
                //echo "<pre>"; print_r($notification); exit;
                NotificationMaster::insert($notification);
        }
        echo "done";

    }
}
