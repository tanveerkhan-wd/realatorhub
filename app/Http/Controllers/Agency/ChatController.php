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
use App\Models\ChatMessages;
use App\Models\ChatRooms;
use App\Models\ChatRoomUsers;
use Carbon\Carbon;
/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class ChatController extends Controller
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
        return view('agency.chat.list',compact('addresses'));
    }
    public function agencyChatList(Request $request)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        //$addresses=PropertyLeadsModel::select('*')->where('agent_id',$agent_id)->groupBy('address')->get();
        if(isset($_GET['room_id']) && !empty($_GET['room_id'])){
            ChatRoomUsers::where('room_id',$_GET['room_id'])->where('user_id',Auth::user()->id)->update(['unread_count'=>0,'Is_send_email'=>0,'updated_date'=>Carbon::now()]);
        }
        $chatRooms=ChatRoomUsers::where('user_id',Auth::user()->id)->orderBy('updated_date','DESC')->get()->toArray();
        $current_room_id='';
        $chatMessages='';
        if(!empty($chatRooms) && count($chatRooms)>0){

            foreach ($chatRooms as $key=>$value) {
                $otherMember = ChatRoomUsers::select('u.first_name','u.last_name')->where('room_id','=',$value['room_id'])->leftJoin('users as u','u.id','=','chat_room_user.user_id')->where('user_id','!=',Auth::user()->id)->get()->toArray();
                $name='';
                foreach ($otherMember as $user) {
                    $name.=$user['first_name'].' '.$user['last_name'].', ';
                }
                $name=rtrim($name, ", ");
                $chatRooms[$key]['group_name']=$name;
            }
            $current_room_id=$chatRooms[0]['room_id'];
            $chatMessages=ChatMessages::select('chat_messages.*','u.first_name','u.last_name','u.profile_img','u.user_type')->leftJoin('users as u','u.id','=','chat_messages.from_id')->where('room_id',$current_room_id)->get()->toArray();
            ChatRoomUsers::where('room_id',$current_room_id)->where('user_id',Auth::user()->id)->update(['unread_count'=>0]);
        }
        $agent_id='';
        //echo "<pre>"; print_r($chatMessages); exit;
        return view('common.chat.list',compact('addresses','agent_id','chatMessages','chatRooms','current_room_id'));
    }
    public function chatList(Request $request,$agent_id)
    {
        /**
         * Used for Admin Login
         * @return redirect to Admin->Login
         */
        $input = $request->all();
        //$addresses=PropertyLeadsModel::select('*')->where('agent_id',$agent_id)->groupBy('address')->get();
        $chatRooms=ChatRoomUsers::where('user_id',$agent_id)->orderBy('updated_date','DESC')->get()->toArray();
        $current_room_id='';
        $chatMessages='';
        if(!empty($chatRooms) && count($chatRooms)>0){

            foreach ($chatRooms as $key=>$value) {
                $otherMember = ChatRoomUsers::select('u.first_name','u.last_name')->where('room_id','=',$value['room_id'])->leftJoin('users as u','u.id','=','chat_room_user.user_id')->where('user_id','!=',$agent_id)->get()->toArray();
                $name='';
                foreach ($otherMember as $user) {
                    $name.=$user['first_name'].' '.$user['last_name'].', ';
                }
                $name=rtrim($name, ", ");
                $chatRooms[$key]['group_name']=$name;
            }
            $current_room_id=$chatRooms[0]['room_id'];
            $chatMessages=ChatMessages::select('chat_messages.*','u.first_name','u.last_name','u.profile_img','u.user_type')->leftJoin('users as u','u.id','=','chat_messages.from_id')->where('room_id',$current_room_id)->get()->toArray();
            ChatRoomUsers::where('room_id',$current_room_id)->where('user_id',Auth::user()->id)->update(['unread_count'=>0,'Is_send_email'=>0,'updated_date'=>Carbon::now()]);
        }
        
        //echo "<pre>"; print_r($chatMessages); exit;
        return view('common.chat.list',compact('addresses','agent_id','chatMessages','chatRooms','current_room_id'));
        
        //return view('agency.chat.list',compact('addresses','agent_id'));
    }
    
}
