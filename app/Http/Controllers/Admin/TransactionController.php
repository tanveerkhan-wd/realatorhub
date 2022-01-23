<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgencyModel;
use App\Models\SubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    protected  $subscriptionModel,$agencyModel,$subscriptionPlanModel;
    public function __construct(SubscriptionModel $subscriptionModel,
                                SubscriptionPlanModel $subscriptionPlanModel,
                                AgencyModel $agencyModel)
    {
        $this->subscriptionModel=$subscriptionModel;
        $this->agencyModel=$agencyModel;
        $this->subscriptionPlanModel=$subscriptionPlanModel;
    }

    public function index(){
        $agency = $this->agencyModel
            ->whereHas('user', function($q)  {
                $q->where('admin_status',UserModel::ACTIVE_ADMIN_STATUS);
            })
            ->get();
        $plans = $this->subscriptionPlanModel->where('is_deleted',SubscriptionPlanModel::IS_DELETED_NO)->get();
        return view('admin.transactions.index',compact('agency','plans'));
    }
    public function listTransactionAjax(Request $request)
    {
        try {
            $input = $request->all();
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $search_agency = $request->get('search_agency');
            $search_plan = $request->get('search_plan');
            /*$subscriptionData =$this->subscriptionModel->select('subscriptions.*','agency.agency_name','subscription_plans.plan_name','subscription_plans.monthly_price','subscription_plans.additional_agent','subscription_plans.additional_agent_per_rate','subscription_plans.description')->leftJoin('subscriptions.user_id','=','agency.user_id')
                ->leftJoin('subscriptions.plan_id','=','subscription_plans.id');*/
            $subscriptionData =$this->subscriptionModel->select('subscriptions.*','agency.agency_name')->leftJoin('agency','subscriptions.user_id','=','agency.user_id')
                ->with(['plan']);
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
            $subscriptionData = $subscriptionData->orderByDesc('subscriptions.id');
            $table = DataTables::of($subscriptionData)->make(true);
            return $table;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
        }
    }
}
