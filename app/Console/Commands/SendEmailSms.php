<?php

namespace App\Console\Commands;

use App\Mail\SignUpMail;
use App\Models\EmailSmsLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Matrix\Exception;
use Stripe;
use Twilio\Rest\Client;

/**
 * Class RenewSubscription
 * @package App\Console\Commands
 */
class SendEmailSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email-sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Email SMS';

    /**
     * @var
     */
    /**
     * @var
     */
    protected $userModel,$emailSmsLogModel;

    /**
     * SendEmailSms constructor.
     * @param User $userModel
     * @param EmailSmsLog $emailSmsLogModel
     */
    const  EMAIL_ATTEMPT = '3';
    const  SMS_ATTEMPT = '3';
    public function __construct(User $userModel, EmailSmsLog $emailSmsLogModel)
    {
        $this->userModel = $userModel;
        $this->emailSmsLogModel = $emailSmsLogModel;
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
        $lockFile = "EmailSmsLog.log";
        $f = fopen($lockFile, 'w') or die('Cannot create lock file');
        if (flock($f, LOCK_EX | LOCK_NB)) {
        } else {
            die('stopped');
        }
        $email_sms_log = $this->emailSmsLogModel
            ->where('email_status',EmailSmsLog::SUCCESS_EMAIL_STATUS)
            ->get();

        /*foreach ($email_sms_log as $log){
            $deleteRecord = EmailSmsLog::where('id',$log->id)->delete();
        }*/

        $email_sms_log = $this->emailSmsLogModel
                              ->where('email_status',EmailSmsLog::PENDING_EMAIL_STATUS)
                             ->get();
        foreach ($email_sms_log as $log){
            $user = $this->userModel->where('id',$log->user_id)->first();

            //send email to pending email status
            $mewEmailStatus=EmailSmsLog::where('id',$log->id)->first();
            if($mewEmailStatus->email_status == EmailSmsLog::PENDING_EMAIL_STATUS){
                if($log->email_status == EmailSmsLog::PENDING_EMAIL_STATUS ){
                    try{
                        if(!empty($log->email_id)){
                            Mail::to($log->email_id)->send(new SignUpMail($log->subject, $log->email_content,$log->logo));
                        }else{
                            Mail::to($user->email)->send(new SignUpMail($log->subject, $log->email_content,$log->logo));
                        }
                        
                        $log_email_counter = $log->email_attempt + 1;
                        $updateLog = $this->emailSmsLogModel
                            ->where('id',$log->id)
                            ->update(['email_status'=>EmailSmsLog::SUCCESS_EMAIL_STATUS,'email_attempt'=>$log_email_counter]);
                        //$deleteLog = $this->emailSmsLogModel->where('id',$log->id)->delete();
                    }
                    catch (\Exception $e){
                        $log_email_counter = $log->email_attempt + 1;
                        $updateLog = $this->emailSmsLogModel
                            ->where('id',$log->id)
                            ->update(['email_error_message'=>$e->getMessage(),'email_attempt'=>$log_email_counter]);
                    }
                }
            }
            //delete email sms log when sms status and email status are success.
            if($log->email_status == EmailSmsLog::SUCCESS_EMAIL_STATUS){
                //$deleteLog = $this->emailSmsLogModel->where('id',$log->id)->delete();
            }
            //more than 3 times attempt failed then email status should be failed
            if($log->email_attempt >= SendEmailSms::EMAIL_ATTEMPT ){
                $updateLog = $this->emailSmsLogModel
                    ->where('id',$log->id)
                    ->update(['email_status'=>EmailSmsLog::FAILED_EMAIL_STATUS]);
            }


        }


    }
}
