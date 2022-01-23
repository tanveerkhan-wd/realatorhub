<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\PropertyModel;
use App\Models\PropertyLeadsModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

    public function index(Request $request) {
        $userData = Session::all();
        $property_count = PropertyModel::where('agent_id', '=', Auth::user()->id)->count();
        $property_lead_count = PropertyLeadsModel::where('agent_id', '=', Auth::user()->id)->where('is_deleted','=','0')->count();

        $leads_data = PropertyLeadsModel::select(DB::raw("COUNT(*) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->where('agent_id', '=', Auth::user()->id)
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
                ->where('agent_id', '=', Auth::user()->id)
                ->groupBy('year')
                ->get();
        $lead_year_count = json_encode($graph_data, JSON_NUMERIC_CHECK);
        return view('agent.dashboard.dashboard')->with(['total_property'=>$property_count,'total_lead'=>$property_lead_count,'lead_year_count'=>$lead_year_count,'year_list'=>$year_list]);
    }

    public function ajaxLeadChart(Request $request) {
        $input = $request->all();
         $leads_data = PropertyLeadsModel::select(DB::raw("COUNT(*) as total"), DB::raw("MONTH(created_at) as month_num"))
                        ->where('agent_id', '=', Auth::user()->id)
                        ->whereYear('created_at', $input['year'])
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

       return $lead_year_count = json_encode($graph_data, JSON_NUMERIC_CHECK);
    }

}
