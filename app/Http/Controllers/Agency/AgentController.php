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
class AgentController extends Controller
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
         $user_id=Auth::user()->id;
        $input = $request->all();
        $agentList=$this->userModel->select('users.*','agent.agent_unique_id')->leftJoin('agency_relation','agency_relation.user_id','=','users.id')->leftJoin('agent','agent.user_id','=','agency_relation.user_id')->where('agency_relation.user_type',1)->where('agency_relation.agency_id',$user_id)->where('users.user_status',1)->orderby('users.id','desc')->get();
        //echo "<pre>"; print_r($agentList->toArray()); exit;
        return view('agency.agent.list',compact('agentList'));
    }
    public function listAgentAjax(Request $request)
    {
        $input = $request->all();
        $user_id=Session::get('user_id');
        $users = $this->userModel->select('users.*','agent.agent_unique_id')->leftJoin('agency_relation','agency_relation.user_id','=','users.id')->leftJoin('agent','agent.user_id','=','agency_relation.user_id')->where('agency_relation.user_type',1)->where('agency_relation.agency_id',$user_id)->orderby('users.id','desc');
        if(isset($input['name']) && !empty($input['name'])){
           $users = $users->where(function ($users) use ($input) {
      
           $users->where('users.email', 'LIKE','%'.$input['name'].'%')
               ->orWhere('users.first_name','LIKE','%'.$input['name'].'%')
               ->orWhere('users.last_name','LIKE','%'.$input['name'].'%')
               ->orWhere('phone','LIKE','%'.$input['name'].'%');
      
         });          
        }
        if(isset($input['status']) && $input['status']!=''){
           $users = $users->where('users.user_status','=',$input['status']);            
        }
        //echo $users->toSql();
        /*if(isset($input['name']) && !empty($input['name'])){
           $users = $users->where(function ($users) use ($input) {
      
           $users->where('email', 'LIKE','%'.$input['name'].'%')
               ->orWhere('first_name','LIKE','%'.$input['name'].'%')
               ->orWhere('last_name','LIKE','%'.$input['name'].'%')
               ->orWhere('city','LIKE','%'.$input['name'].'%')
               ->orWhere('phone','LIKE','%'.$input['name'].'%');
      
         });          
        }
        if(isset($input['city']) && !empty($input['city'])){
            $users = $users->where('city','=',$input['city']);
        }
        if(isset($input['status']) && !empty($input['status'])){
            if($input['status'] == 2){
              $sta = 0;
            }else{
              $sta = 1; 
            }
           $users = $users->where('status','=',$sta);            
        }*/
      
        $table = Datatables::of($users)
                 ->editColumn('created_at', function ($users) {
                return date('d-m-Y', strtotime($users->created_at));
                })
                 ->rawColumns(['created_at'])
                ->make(true);
        return $table;
    }
    public function addAgent(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        $country_code = CountryCodeModel::where('calling_code','!=','')->get();
        return view('agency.agent.add',compact('country_code'));
    }
    public function addAgentPost(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        try
        {
            $user_id=Session::get('user_id');
            $input = $request->all();

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'agent_id' => 'required',
                'country_code' => 'required',
                'single_mobile_number' => 'required',
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
            } 
            $userCount=UserModel::where('email',$input['email'])->where('deleted_at',NULL)->count();
            if($userCount>0){
                return response()->json(['message' => 'The emal has been already taken.', 'code' => 201]);
            }
            $agetcount=AgentModel::where('agent_unique_id', $input['agent_id'])->count();
            if($agetcount > 0){
                return redirect('agency/agent/add')->withErrors(['This Agent id is already used.']);
            }
            $activeSubscription = SubscriptionModel::with(['plan'])
                ->where('user_id',Auth::User()->id)
                ->where('status',SubscriptionModel::STATUS_ACTIVE)
                ->first();
            $total_agent = $activeSubscription->plan->no_of_agent + $activeSubscription->plan->additional_agent;
            if($activeSubscription->total_no_of_agent >= $total_agent){
                return redirect('agency/agent/add')->withErrors(['Please Upgrade your subscription for add additional agent.']);
            }
            $otp = mt_rand(100000, 999999);  
            $password=randomPassword();
            $storeUser = $this->userModel->create([
                'first_name'=>$input['first_name'],
                'last_name'=>$input['last_name'],
                'user_name'=>$input['first_name'].' '.$input['last_name'],
                'email'=>$input['email'],
                'password'=>Hash::make($password),
                'phone_code'=>$input['country_code'],
                'phone'=>$input['single_mobile_number'],
                'user_type'=>2,
                'admin_status'=>1,
                'user_status'=>1,
                'email_verified'=>1,
                'verification_code'=>$otp,
                'email_notification'=>1,
                'push_notification'=>1,
                'created_by'=>1,
                'updated_by'=>'0'
            ]);
            $emailContent = $this->emailMasterModel->where('id', '=', 5)->first();
            $name=$input['first_name'];
            if(!empty($emailContent)){
            $link='<a href='.route('agency.change.password',encrypt($storeUser->id)).' style="background-color: #4e43fc;padding: 4px 10px;text-decoration:none;color:#fff;">Set Password</a>';
            $messageContent = str_replace('{{PASSWORD}}', $link, $emailContent->content);
                //Mail::to($input['email'])->send(new SignUpMail($emailContent->subject, $messageContent));
                //$messageContent = $emailContent->content;
                $subject = $emailContent->subject;
    //            $messageContent = str_replace('{{USER_NAME}}',$name , $emailContent->content);
               // $messageContent = str_replace('{{PASSWORD}}', $password, $emailContent->content);
                $student_log = EmailSmsLog::create([
                    'user_id' => $storeUser->id,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
            if($activeSubscription->total_no_of_agent >= $activeSubscription->plan->no_of_agent){
                $additional_agent  = $activeSubscription->additional_agents_counts + 1;
                $additional_agent_price = $additional_agent * ($activeSubscription->plan->additional_agent_per_rate);
                $updateSubscriptions = SubscriptionModel::where('id',$activeSubscription->id)->update(
                    [
                        'additional_agents_counts'=>$additional_agent,
                        'additional_agent_price'=>$additional_agent_price,
                    ]
                );
            }
            $total_no_of_agent = $activeSubscription->total_no_of_agent + 1;


            $updateSubscriptions = SubscriptionModel::where('id',$activeSubscription->id)->update(
                [
                    'total_no_of_agent'=>$total_no_of_agent,
                ]
            );

            $data = [];
            $data['user'] = $storeUser->id;
            $agencyRelation=new AgencyRelationModel();
            $agencyRelation->agency_id=$user_id;
            $agencyRelation->user_id=$storeUser->id;
            $agencyRelation->user_type=1;
            $agencyRelation->save();

            $agent = new AgentModel();
            $agent->user_id=$storeUser->id;
            $agent->agent_unique_id=$input['agent_id'];
            $agent->save();
            return redirect('agency/agent/add')->with('success','Agent Added Successfully.');
            //\Illuminate\Support\Facades\Session::put('store_user_id',$storeUser->id);
            //return response()->json(['message' => 'Verify email code', 'data' => $data, 'code' => 200]);
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('agency/agent/add')->withErrors([$e->getMessage()]);
        }
    }
    public function editAgent(Request $request,$id)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        $country_code = CountryCodeModel::where('calling_code','!=','')->get();
        $user_data=$this->userModel->select('users.*','agent.agent_unique_id')->leftJoin('agent','agent.user_id','=','users.id')->where('users.id',$id)->first();
        return view('agency.agent.edit',compact('country_code','user_data'));
    }
    public function editAgentPost(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        try
        {
            $user_id=Session::get('user_id');
            $input = $request->all();

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'country_code' => 'required',
                'single_mobile_number' => 'required',
            ]);
            if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
            } 
            $agetcount=AgentModel::where('agent_unique_id', $input['agent_id'])->where('user_id','!=',$input['id'])->count();
            if($agetcount > 0){
                return redirect('agency/agent/edit/'.$input['id'])->withErrors(['This Agent id is already used.']);
            }
            /*$emailcout=$this->userModel::where('email', $input['email'])->where('id','!=',$input['id'])->count();
            if($emailcout > 0){
                return redirect('agency/agent/edit/'.$input['id'])->withErrors(['This email id is already used.']);
            }*/
            
            $storeUser = $this->userModel->where('id',$input['id'])->update([
                'first_name'=>$input['first_name'],
                'last_name'=>$input['last_name'],
                'user_name'=>$input['first_name'].' '.$input['last_name'],
                'phone_code'=>$input['country_code'],
                'phone'=>$input['single_mobile_number'],
            ]);

            /*$agencyRelation=AgencyRelationModel::find($input['id']);
            $agencyRelation->agency_id=$user_id;
            $agencyRelation->user_id=$storeUser->id;
            $agencyRelation->user_type=$user_id;
            $agencyRelation->save();*/
            $agent = AgentModel::where('user_id',$input['id'])->first();
            $agent->agent_unique_id=$input['agent_id'];
            $agent->save();
            return redirect('agency/agent/edit/'.$input['id'])->with('success','Agent Edited Successfully.');
            //\Illuminate\Support\Facades\Session::put('store_user_id',$storeUser->id);
            //return response()->json(['message' => 'Verify email code', 'data' => $data, 'code' => 200]);
        }
        catch (\Exception $e){
            \Log::error($e->getMessage());
            return redirect('agency/agent/edit/'.$input['id'])->withErrors([$e->getMessage()]);
        }
    }
    public function delete(Request $request,$id){
        /*echo $id;
        echo "<pre>"; print_r($_POST); exit;*/
        $data = array('deleted_at' => now());
        extract($_POST);
        //$delete = Users::where('id','=',$id)->update($data);
        $activeSubscription = SubscriptionModel::with(['plan'])
            ->where('user_id',Auth::User()->id)
            ->where('status',SubscriptionModel::STATUS_ACTIVE)
            ->first();
        if($activeSubscription->total_no_of_agent >= $activeSubscription->plan->no_of_agent){
            $additional_agent  = $activeSubscription->additional_agents_counts - 1;
            $additional_agent_price = $additional_agent * ($activeSubscription->plan->additional_agent_per_rate);
            $updateSubscriptions = SubscriptionModel::where('id',$activeSubscription->id)->update(
                [
                    'additional_agents_counts'=>$additional_agent,
                    'additional_agent_price'=>$additional_agent_price,
                ]
            );
        }
        $no_of_agent =$activeSubscription->total_no_of_agent - 1;

        $updateSubscriptions = SubscriptionModel::where('id',$activeSubscription->id)->update(
            [
                'total_no_of_agent'=>$no_of_agent,

            ]
        );
        $data = array('deleted_at' => now());
        //$delete = Users::where('id','=',$id)->update($data);
        $agentRelation=AgencyRelationModel::where('user_id',$id)->first();
        updateAgentChat($id,$agent,$agentRelation->agency_id);
        UserModel::where('id', $id)->update($data);


        /*UserModel::where('id', $id)->delete();
        AgentModel::where('user_id', $id)->delete();*/
        PropertyModel::where('agent_id', $id)->update(['agent_id'=>$agent]);
        PropertyLeadsModel::where('agent_id', $id)->update(['agent_id'=>$agent]);
        return response()->json(['message' =>'Agent Deleted Successfully.','code' => 200]);
        return 1;
    }
    public function changeEmail(Request $request){

        $input = $request->all();

        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail($input['id']);
        $emailcout=$this->userModel::where('email', $input['email'])->where('id','!=',$input['id'])->count();
        if($emailcout > 0){
            return response()->json(['message' =>'This Email Address Is Already In Use.','code' => 201]);
        }
        $userdata=$this->userModel::where('id',$input['id'])->first();
        if($userdata->email!=$input['email']){
            $this->userModel->where('id',$input['id'])->update(['email'=>$input['email']]);
            return response()->json(['message' =>'Email Changed Successfully.','code' => 200]);
        }else{
            return response()->json(['message' =>'Somthing want roung.','code' => 201]);
        }
    }
    public function activeInactive(Request $request,$id){

        $input = $request->all();

        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail($id);
        $userdata=$this->userModel::where('id',$id)->first();
        $agency = UserModel::where('id',Auth::user()->id)->with(['agency'])->first();
        if($userdata->user_status==1){
            $this->userModel->where('id',$id)->update(['user_status'=>0]);
            $emailContent = $this->emailMasterModel->where('id', '=', 9)->first();
                //Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent));
            if(!empty($emailContent)){
                $messageContent = $emailContent->content;
                $subject = $emailContent->subject;
    //            $messageContent = str_replace('{{USER_NAME}}',$name , $emailContent->content);
                $emailContent->content = str_replace('{{AGENTNAME}}', $userdata->first_name.' '.$userdata->last_name, $emailContent->content);
                $messageContent = str_replace('{{AGENCYNAME}}', $agency->agency->agency_name, $emailContent->content);
                $student_log = EmailSmsLog::create([
                    'user_id' => $id,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
            return response()->json(['message' =>'Agent Account Deactivated Successfully.','code' => 200]);
        }else{
            $this->userModel->where('id',$id)->update(['user_status'=>1]);
            return response()->json(['message' =>'Agent Account Activated Successfully.','code' => 200]);
        }
    }
    
    public function show($id){
        return view('agency.agent.view')->with('agentid',$id);
    }
    public function getAgentPropertyList(Request $request){
        $input = $request->all();
        $all_property = PropertyModel::leftJoin('users', 'properties.agent_id', '=', 'users.id')->leftJoin('agency', 'properties.agency_id', '=', 'agency.user_id')->where("is_delete", "=", "0")->where('agent_id','=',$input['agentid'])
                ->select('properties.id','properties.purpose','properties.type','properties.address','properties.is_delete','properties.status','properties.created_at','users.first_name','users.email','users.last_name','agency.slug')
                ->orderby('properties.id','desc');
        if(isset($input['agent']) && !empty($input['agent'])){
           $all_property = $all_property->where(function ($all_property) use ($input) {
      
           $all_property->where('users.email', 'LIKE','%'.$input['agent'].'%')
               ->orWhere('users.first_name','LIKE','%'.$input['agent'].'%')
               ->orWhere('users.last_name','LIKE','%'.$input['agent'].'%');
      
         });          
        }
        if (isset($input['property_all_search']) && $input['property_all_search'] != '') {
           $all_property = $all_property->where(function ($all_property) use ($input) {

                $all_property->where('properties.address', 'LIKE', '%' . $input['property_all_search'] . '%');
            });
        }
        if(isset($input['property_type']) && $input['property_type']!=''){
           $all_property = $all_property->where('properties.type','=',$input['property_type']);            
        }
        if(isset($input['property_purpose']) && $input['property_purpose']!=''){
           $all_property = $all_property->where('properties.purpose','=',$input['property_purpose']);            
        }
        if(isset($input['property_status']) && $input['property_status']!=''){
           $all_property = $all_property->where('properties.status','=',$input['property_status']);            
        }
        return DataTables::of($all_property)
                 ->editColumn('id', function ($all_property) {
                            $string = array(', ',',', ' ', '/', "'");
                            $replace   = array('-', '-', '-', '-', '-');
                            $address = str_replace($string, $replace, $all_property->address);
                            $address = str_replace('--', '-', $address);
                            $url  = url('').'/'.$all_property->slug.'/property-detail/'.$address.'-'. $all_property->id;
                                return '<a href="'.$url.'" class="underline" target="_blank">'.$all_property->id.'</a>';
                            })
                        ->editColumn('purpose', function ($all_property) {
                            $purpose = '';
                            if ($all_property->purpose == '1') {
                                $purpose = 'Buy';
                            } else {
                                $purpose = 'Rent';
                            }
                            return $purpose;
                        })
                        ->editColumn('created_at', function ($all_property) {
                            return date('d-m-Y', strtotime($all_property->created_at));
                        })
                        ->editColumn('type', function ($all_property) {
                            $type = $all_property->type;
                            switch ($type) {
                                case "1":
                                    $pt_type= "Single Homes";
                                    break;
                                case "2":
                                    $pt_type="Multifamily";
                                    break;
                                case "3":
                                    $pt_type="Commercial";
                                    break;
                                case "4":
                                    $pt_type="Industrial";
                                    break;
                                case "5":
                                    $pt_type="Lot";
                                    break;
                                default:
                                    $pt_type='';
                            }
                            return $pt_type;
                        })
                         ->editColumn('status', function ($all_property) {
                            $status = '';
                            if ($all_property->status == '2') {
                                $status = '<a href="javascript:void(0)" class="changeStatus inactiveClass redlink" title="Mark as Active" data-url="'.route('agency.property.change.status',$all_property->id).'" data-status="1">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="changeStatus greenlink" style="color:green" data-url="'.route('agency.property.change.status',$all_property->id).'" data-status="2">Active</a>';
                            }
                            return $status;
                        })
                        ->addColumn('action', function ($all_property) {
                            $string = array(', ',',', ' ', '/', "'");
                            $replace   = array('-', '-', '-', '-', '-');
                            $address = str_replace($string, $replace, $all_property->address);
                            $address = str_replace('--', '-', $address);
                            $url  = url('').'/'.$all_property->slug.'/property-detail/'.$address.'-'. $all_property->id;
                            $str='<a href="'.$url.'" class="action_icon" target="blank"><img src="' . asset('public') . '/assets/images/ic_view.png"></a>';
                            $str.= '<a href="'.route('agency.property.edit',$all_property->id).'" title="Edit" class="table-edit action_icon"><img src="'.asset('public').'/assets/images/ic_edit.png"></a>&nbsp;&nbsp;';
                           if ($all_property->is_delete == '0') {
                                $str .= '<a href="javascript:void(0)" class="deleteData action_icon" title="Delete" data-id="'.$all_property->id.'" data-url="'.route('agency.property.delete').'"><img src="' . asset('public') . '/assets/images/ic_delete.png"></a>';
                            } 
                            return $str;
                        })
                        ->addIndexColumn()
                        ->rawColumns(['purpose','status', 'action','id'])
                        ->make(true);
    }
}
