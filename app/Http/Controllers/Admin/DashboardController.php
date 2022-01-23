<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionModel;
use Illuminate\Support\Facades\DB;
use App\Models\UserModel;

class DashboardController extends Controller {

    public function home(Request $request) {
        $allproperty=getLiveProperties();
        $allagency=\App\Models\UserModel::where('user_type','=',1)->withTrashed()->count();
        $plandata=$subscriptionPlans = \App\Models\SubscriptionPlanModel::withCount(['subscriptions'])->get();
        $plan=$plandata->count();
        $total_revenue_data = SubscriptionModel::where('payment_type','!=',0)->sum('total_amount');
        
        // ------ FOR REVENUE CHART DATA START   ----------//
        $data = SubscriptionModel::select(DB::raw("SUM(total_amount) as total"), DB::raw("MONTH(start_date) as month_num"))
                        ->whereYear('start_date', date('Y'))
                        ->where('payment_type','!=',0)
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($data)) {
            $month_data = array_column($data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }
        $year_list = SubscriptionModel::select(DB::raw("YEAR(start_date) as year"))
                ->groupBy('year')
                ->get();
        $revenue_total = json_encode($graph_data, JSON_NUMERIC_CHECK);
        // ------ FOR REVENUE CHART DATA END   ----------//
        // ------ FOR AGENCY CHART DATA START   ----------//
        $agencydata = \App\Models\UserModel::select(DB::raw("COUNT(*) as total"), DB::raw("MONTH(created_at) as month_num"))
                ->where('user_type', '=', '1')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month_num')
                ->withTrashed()
                ->get()
                ->toArray();
        $agencygraph_data = [];
        if (!empty($agencydata)) {
            $agencymonth_data = array_column($agencydata, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $agencymonth_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($agencygraph_data, $agencymonth_data[$i]);
                } else {
                    array_push($agencygraph_data, 0);
                }
            }
        }
        $agency_year_list = UserModel::select(DB::raw("YEAR(created_at) as year"))
                ->groupBy('year')
                ->get();
        $agency_total = json_encode($agencygraph_data, JSON_NUMERIC_CHECK);
        // ------ FOR AGENCY CHART DATA END   ----------//
        return view('admin.dashboard.dashboard')->with(['revenue_total' => $revenue_total, 'year_list' => $year_list, 'agency_total' => $agency_total, 'agency_year_list' => $agency_year_list,'plan'=>$plan,'allagency'=>$allagency,'allproperty'=>$allproperty,'total_revenue'=>$total_revenue_data]);
    }

    public function ajaxRevenueChart(Request $request) {
        $input = $request->all();
        $data = SubscriptionModel::select(DB::raw("SUM(total_amount) as total"), DB::raw("MONTH(start_date) as month_num"))
                        ->where('payment_type','!=',0)
                        ->whereYear('start_date', $input['year'])
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($data)) {
            $month_data = array_column($data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }
        return $revenue_total = json_encode($graph_data, JSON_NUMERIC_CHECK);
    }

    public function ajaxAgencyChart(Request $request) {
        $input = $request->all();
        $agencydata = \App\Models\UserModel::select(DB::raw("COUNT(*) as total"), DB::raw("MONTH(created_at) as month_num"))
                ->where('user_type', '=', '1')
                ->whereYear('created_at', $input['year'])
                ->groupBy('month_num')
                ->withTrashed()
                ->get()
                ->toArray();
        $agencygraph_data = [];
        if (!empty($agencydata)) {
            $agencymonth_data = array_column($agencydata, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $agencymonth_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($agencygraph_data, $agencymonth_data[$i]);
                } else {
                    array_push($agencygraph_data, 0);
                }
            }
        }
        return $agency_total = json_encode($agencygraph_data, JSON_NUMERIC_CHECK);
    }

}
