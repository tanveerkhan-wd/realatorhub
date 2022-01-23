<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Models\EmailMasterModel;
use App\Models\SubscriptionModel;
use App\Models\UserModel;
use App\Models\CountryCodeModel;
use App\Models\AgencyRelationModel;
use App\Models\AgentModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\SignUpMail;
use App\Helper\Helper;
use Hash;
use Session;
use Yajra\DataTables\DataTables;
use App\Models\EmailSmsLog;
use App\Models\PropertyModel;
use App\Models\PropertyLeadsModel;
/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class LeadsController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    protected $userModel,$emailMasterModel;


    /**
     * UserController constructor.
     * @param UserModel $userModel
     * @param EmailMasterModel $emailMasterModel
     */
    public function __construct(UserModel $userModel, EmailMasterModel $emailMasterModel)
    {
        $this->userModel = $userModel;
        $this->emailMasterModel = $emailMasterModel;

    }

    public function index(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        if(Auth::user()->user_type==2){
            $addresses=PropertyLeadsModel::select('*')->where('agent_id',Auth::user()->id)->groupBy('address')->get();
        }else{
            $addresses=PropertyLeadsModel::select('*')->where('agency_id',Auth::user()->id)->groupBy('address')->get();
        }
        
        return view('common.lead.list',compact('addresses'));
    }
    public function agentLeads(Request $request,$agent_id)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        $addresses=PropertyLeadsModel::select('*')->where('agent_id',$agent_id)->groupBy('address')->get();
        
        return view('common.lead.list',compact('addresses','agent_id'));
    }
    
}
