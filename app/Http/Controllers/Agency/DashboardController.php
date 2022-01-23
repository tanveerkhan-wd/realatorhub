<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\UserModel;
use App\Models\PropertyModel;
use App\Models\PropertyLeadsModel;
use Session;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller {

    public function home(Request $request) {

        $userData = Session::all();
        //echo "<pre>"; print_r($userData);exit;
        //FOR AGENCY DASHBOARD COUNT
        $property_count = PropertyModel::where('agency_id', '=', Auth::user()->id)->count();
        $property_lead_count = PropertyLeadsModel::where('agency_id', '=', Auth::user()->id)->where('is_deleted','=','0')->count();
        $agent_count = UserModel::select('users.*', 'agent.agent_unique_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')->where('agency_relation.user_type', 1)->where('agency_relation.agency_id', Auth::user()->id)->count();
        $customer_count = UserModel::select('users.*', 'agent.agent_unique_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')->where('agency_relation.user_type', 2)->where('agency_relation.agency_id', Auth::user()->id)->count();

        //------- FOR AGENCY LEADS GRAPH DATA START-------
        $leads_data = PropertyLeadsModel::select(DB::raw("COUNT(*) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->where('agency_id', '=', Auth::user()->id)
                        ->whereYear('created_at', date('Y'))
                        ->where('is_deleted','=','0')
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($leads_data)) {
            $month_data = array_column($leads_data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }

        $year_list = PropertyLeadsModel::select(DB::raw("YEAR(created_at) as year"))
                ->where('agency_id', '=', Auth::user()->id)
                ->groupBy('year')
                ->get();
        $lead_year_count = json_encode($graph_data, JSON_NUMERIC_CHECK);

        //------- FOR AGENCY LEADS GRAPH DATA END-------
        //------- FOR AGENCY CUSTOMER GRAPH DATA START-------
        $customer_data = UserModel::select('users.*', 'agent.agent_unique_id', DB::raw("MONTH(users.created_at) as month_num"), DB::raw("COUNT(*) as total"))
                        ->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')
                        ->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')
                        ->where('agency_relation.user_type', 2)
                        ->where('agency_relation.agency_id', Auth::user()->id)
                        ->whereYear('created_at', date('Y'))
                        ->get()->toArray();
        $cus_graph_data = [];
        if (!empty($customer_data)) {
            $month_cus_data = array_column($customer_data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_cus_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($cus_graph_data, $month_cus_data[$i]);
                } else {
                    array_push($cus_graph_data, 0);
                }
            }
        }


        $customer_year_list = UserModel::select('users.*', 'agent.agent_unique_id', DB::raw("YEAR(users.created_at) as year"))
                ->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')
                ->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')
                ->where('agency_relation.user_type', 2)
                ->where('agency_relation.agency_id', Auth::user()->id)
                ->groupBy('year')
                ->get();
        $customer_year_count = json_encode($cus_graph_data, JSON_NUMERIC_CHECK);

        //------- FOR AGENCY CUSTOMER GRAPH DATA END-------



        return view('agency.dashboard.dashboard')->with(['total_property' => $property_count, 'total_lead' => $property_lead_count, 'total_agent' => $agent_count, 'total_customer' => $customer_count, 'lead_year_count' => $lead_year_count, 'year_list' => $year_list, 'customer_year_list' => $customer_year_list, 'customer_year_count' => $customer_year_count]);
    }

    public function ajaxLeadsChart(Request $request) {
        $input = $request->all();
        $leads_data = PropertyLeadsModel::select(DB::raw("COUNT(*) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->where('agency_id', '=', Auth::user()->id)
                        ->whereYear('created_at', $input['year'])
                        ->where('is_deleted','=','0')
                        ->groupBy('month_num')
                        ->get()->toArray();
        $graph_data = [];
        if (!empty($leads_data)) {
            $month_data = array_column($leads_data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_data)) {
                    array_push($graph_data, $month_data[$i]);
                } else {
                    array_push($graph_data, 0);
                }
            }
        }

        return $lead_year_count = json_encode($graph_data, JSON_NUMERIC_CHECK);
    }

    public function ajaxCustomerChart(Request $request) {
        $input = $request->all();
        $customer_data = UserModel::select('users.*', 'agent.agent_unique_id', DB::raw("MONTH(users.created_at) as month_num"), DB::raw("COUNT(*) as total"))
                        ->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')
                        ->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')
                        ->where('agency_relation.user_type', 2)
                        ->where('agency_relation.agency_id', Auth::user()->id)
                        ->whereYear('created_at', $input['year'])
                        ->get()->toArray();
        $cus_graph_data = [];
        if (!empty($customer_data)) {
            $month_cus_data = array_column($customer_data, 'total', 'month_num');
            for ($i = 1; $i <= 12; $i++) {
                if (array_key_exists($i, $month_cus_data)) {
                    //$graph_data[$i]=$month_data[$i];
                    array_push($cus_graph_data, $month_cus_data[$i]);
                } else {
                    array_push($cus_graph_data, 0);
                }
            }
        }

       return  $customer_year_count = json_encode($cus_graph_data, JSON_NUMERIC_CHECK);
    }

}
