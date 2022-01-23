<?php

namespace App\Console\Commands;

use App\Models\SubscriptionModel;
use Illuminate\Console\Command;

class CancelSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cancel Subscription';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $subscriptionModel;
    public function __construct(SubscriptionModel $subscriptionModel)
    {
        $this->subscriptionModel=$subscriptionModel;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //cancel subscription
        $today = date('Y-m-d H:i:s');
        $cancelSubscription = $this->subscriptionModel->whereNotNull('cancel_at')
            ->where('status',SubscriptionModel::STATUS_ACTIVE)->get();
        if(!empty($cancelSubscription)){
            foreach ($cancelSubscription as $cancel){
                  if($cancel->cancel_at <= $today){
                      $updateSubscription = $this->subscriptionModel
                          ->where('id',$cancel->id)
                          ->update(['status'=>SubscriptionModel::STATUS_CANCEL]);
                  }
            }
        }



    }
}
