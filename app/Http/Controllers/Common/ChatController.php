<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use App\Mail\SignUpMail;
use App\Models\AgencyModel;
use App\Models\CountryCodeModel;
use App\Models\EmailMasterModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Helper\Helper;
use Mockery\Exception;
use Session;
use Stripe;
use App\Models\NotificationMaster;
use App\Models\EmailSmsLog;
use App\Models\SettingModel;
use Yajra\DataTables\DataTables;
use App\Models\AgencyRelationModel;
use App\Models\ChatMessages;
use App\Models\ChatRooms;
use App\Models\ChatRoomUsers;
use App\Models\PropertyModel;
use Carbon\Carbon;
/**
 * Class UserController
 * @package App\Http\Controllers\Admin
 */
class ChatController extends Controller
{
	public function createGroup(Request $request)
	{
		extract($_POST);
		$settingData=SettingModel::where('user_id',Auth::user()->id)->get()->toArray();
		$settingData = array_column($settingData,'text_value','text_key');   
		if(isset($settingData['chat_first_auto_message']) && !empty($settingData['chat_first_auto_message'])){
			$firstMessage=$settingData['chat_first_auto_message'];
		}else{
			$firstMessage='Hi There! Lets Chat';
		}
		if(Auth::check() == true && !empty($property_id)){

			$propertyDetails=PropertyModel::where('id',$property_id)->first();
			$chatRooms=ChatRoomUsers::where('user_id',Auth::user()->id)->get();
			if(!empty($chatRooms) && count($chatRooms)>0){
				$roomIdExit=0;
				foreach ($chatRooms as $value) {
					$count=ChatRoomUsers::where('room_id',$value->room_id)->where('user_id',$propertyDetails->agent_id)->count();	
					if($count > 0){
						$roomIdExit=1;
					}				
					
				}
				if($roomIdExit==0){
					$chatRoom = new ChatRooms;
					$chatRoom->created_date = Carbon::now();
					$chatRoom->save();
					
					$chatRoomUser= new ChatRoomUsers;
					$chatRoomUser->room_id=$chatRoom->id;
					$chatRoomUser->user_id=Auth::user()->id;
					$chatRoomUser->save();

					$chatRoomUser= new ChatRoomUsers;
					$chatRoomUser->room_id=$chatRoom->id;
					$chatRoomUser->user_id=$propertyDetails->agent_id;
					$chatRoomUser->save();

					$chatRoomUser= new ChatRoomUsers;
					$chatRoomUser->room_id=$chatRoom->id;
					$chatRoomUser->user_id=$propertyDetails->agency_id;
					$chatRoomUser->save();
					$room_id=$chatRoom->id;
					$chatRoom = new ChatMessages;
					$chatRoom->from_id = $propertyDetails->agent_id;
					$chatRoom->room_id = $room_id;
					$chatRoom->body = $firstMessage;
					$chatRoom->message_type = '1';
					$chatRoom->created_date = Carbon::now();
			        $chatRoom->updated_date = Carbon::now();
			        if($chatRoom->save()){
			        	$otherMember = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','!=',Auth::user()->id)->get()->toArray();

			        	foreach ($otherMember as $key3 => $value3) 
			        	{
							$add = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','=',$value3['user_id'])->first();
							$add->unread_count = $add->unread_count+1;
							$add->updated_date = Carbon::now();
							$add->save();


							//Send Notification,Email and SMS to SP
							//$tokens = getUsertokens($value3['user_id']);
							$body_message = Auth::user()->first_name.' '.Auth::user()->last_name." have sent message.";
							$user_type=UserModel::where('id',$value3['user_id'])->first();
							if($user_type->user_type==1){
								$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value3['user_id'],'link'=>'','active_flag'=>0,'notification_desc'=>$body_message);
								sendNotification($notification);
							}
							if($user_type->user_type==2){
								$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value3['user_id'],'link'=>'','active_flag'=>0,'notification_desc'=>$body_message);
								sendNotification($notification);
							}
			        	}

			        	return response()->json(['code' => 200,'message'=>$firstMessage,'room_id'=>$room_id,'time'=>Carbon::now()]);
			        }else{
			        	return response()->json(['code' => 201,'message'=>"Something Went Wrong"]);
			        }
				}
			}else{
				$chatRoom = new ChatRooms;
				$chatRoom->created_date = Carbon::now();
				$chatRoom->save();

				$chatRoomUser= new ChatRoomUsers;
				$chatRoomUser->room_id=$chatRoom->id;
				$chatRoomUser->user_id=Auth::user()->id;
				$chatRoomUser->save();

				$chatRoomUser= new ChatRoomUsers;
				$chatRoomUser->room_id=$chatRoom->id;
				$chatRoomUser->user_id=$propertyDetails->agent_id;
				$chatRoomUser->save();

				$chatRoomUser= new ChatRoomUsers;
				$chatRoomUser->room_id=$chatRoom->id;
				$chatRoomUser->user_id=$propertyDetails->agency_id;
				$chatRoomUser->save();
				$room_id=$chatRoom->id;
				$chatRoom = new ChatMessages;
				$chatRoom->from_id = $propertyDetails->agent_id;
				$chatRoom->room_id = $room_id;
				$chatRoom->body = $firstMessage;
				$chatRoom->message_type = '1';
				$chatRoom->created_date = Carbon::now();
		        $chatRoom->updated_date = Carbon::now();
		        if($chatRoom->save()){
		        	$otherMember = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','!=',Auth::user()->id)->get()->toArray();

		        	foreach ($otherMember as $key3 => $value3) 
		        	{
						$add = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','=',$value3['user_id'])->first();
						$add->unread_count = $add->unread_count+1;
						$add->updated_date = Carbon::now();
						$add->save();


						//Send Notification,Email and SMS to SP
						//$tokens = getUsertokens($value3['user_id']);
						$body_message = Auth::user()->first_name.' '.Auth::user()->last_name." have sent message.";
						$user_type=UserModel::where('id',$value3['user_id'])->first();
						if($user_type->user_type==1){
							$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value3['user_id'],'link'=>'','active_flag'=>0,'notification_desc'=>$body_message);
							sendNotification($notification);
						}
						if($user_type->user_type==2){
							$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value3['user_id'],'link'=>'','active_flag'=>0,'notification_desc'=>$body_message);
							sendNotification($notification);
						}
		        	}

		        	return response()->json(['code' => 200,'message'=>$firstMessage,'room_id'=>$room_id,'time'=>Carbon::now()]);
		        }else{
		        	return response()->json(['code' => 201,'message'=>"Something Went Wrong"]);
		        }
			}
		}
		return response()->json(['code' => 201,'message'=>"Something Went Wrong"]);

	}
    public function total_unread_count(Request $request)
	{
		$input = $request->all();

    	try{    		
			$count = ChatRoomUsers::where('user_id','=',Auth::user()->id)->sum('unread_count');
			$data = [];
			$data['unread_count'] = $count;
	        return response()->json(['code' => 200,'message'=>"Unread Count","data"=>$data]);
		}catch(\Exception $e){
    		return response()->json(['code' => 201,'message'=>$e->getMessage()]);
    	}
	}
	public function send_chat_msg(Request $request)
    {
    	try {
    		$input = $request->all();
    		if(empty($_POST) && empty($_FILES['chat_img']['name'])){
		        return response()->json(['code' => 201,'message'=>'Something want wrong try again later']);
			}
			if($_FILES['chat_img']['name']!=''){
				$message_type='2';
			}else{
				$message_type='1';
			}
	

			if(empty($input['room_id']))
			{

				if(empty($input['property_id']))
				{
					/*$response['code']=201;
			        $response['message']='Something want wrong try again later';
			        $response['data']='';*/
			        return response()->json(['code' => 201,'message'=>'Something want wrong try again later']);
				}
				$propertyDetails=PropertyModel::where('id',$property_id)->first();
				if(empty($propertyDetails)){
					/*$response['code']=201;
			        $response['message']='Property Not Found';
			        $response['data']='';*/
			        return response()->json(['code' => 201,'message'=>'Property Not Found']);
				}
				$allSingleChatArray = ChatRoomUsers::where('user_id','=',Auth::user()->id)->pluck('room_id')->toArray();

				$roomExist = '';
				foreach ($allSingleChatArray as $key => $value) 
				{
					$get = ChatRoomUsers::where('room_id','=',$value)->where('user_id','=',$propertyDetails->agent_id)->first();
					if($get != null && !empty($get)){
						$roomExist = $get->room_id;
					}
				}
				if(empty($roomExist) || $roomExist == "")
				{
					$room_name = time();
					$room_name = md5($room_name);

					$chatRoom = new ChatRooms;
					$chatRoom->created_date = Carbon::now();
			        $chatRoom->updated_date = Carbon::now();
			        if($chatRoom->save()){
						
						$chatRoomUser= new ChatRoomUsers;
						$chatRoomUser->room_id=$chatRoom->id;
						$chatRoomUser->user_id=Auth::user()->id;
						$chatRoomUser->save();

						$chatRoomUser= new ChatRoomUsers;
						$chatRoomUser->room_id=$chatRoom->id;
						$chatRoomUser->user_id=$propertyDetails->agent_id;
						$chatRoomUser->save();

						$chatRoomUser= new ChatRoomUsers;
						$chatRoomUser->room_id=$chatRoom->id;
						$chatRoomUser->user_id=$propertyDetails->agency_id;
						$chatRoomUser->save();
			        }
			        $room_id = $chatRoom->id;
				}
				else
				{
					$room_id = $roomExist;
				}
			}
			else
			{
				$validator = Validator::make($request->all(), [
		          'room_id' => 'required',
			    ]);
				if ($validator->fails()) {
				      return response()->json(['code' => 201,'message'=>$validator->messages()->first()]);
				}
				$room_id = $input['room_id'];
			}

			if($message_type == '1')
			{
				$chatRoom = new ChatMessages;
				$chatRoom->from_id = Auth::user()->id;
				$chatRoom->room_id = $room_id;
				$chatRoom->body = $input['m'];
				$chatRoom->message_type = '1';
				$chatRoom->created_date = Carbon::now();
		        $chatRoom->updated_date = Carbon::now();
		        if($chatRoom->save()){
		        	$chat_message=$input['m'];
		        	$otherMember = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','!=',Auth::user()->id)->get()->toArray();

		        	foreach ($otherMember as $key3 => $value3) 
		        	{
						$add = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','=',$value3['user_id'])->first();
						$add->unread_count = $add->unread_count+1;
						$add->updated_date = Carbon::now();
						$add->save();
						///echo $add->unread_count,'';

						//Send Notification,Email and SMS to SP
						//$tokens = getUsertokens($value3['user_id']);
						$body_message = Auth::user()->first_name.' '.Auth::user()->last_name." have sent message.";
						$user_type=UserModel::where('id',$value3['user_id'])->first();
						if($user_type->user_type==1){
							$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value3['user_id'],'link'=>'/chat-list?room_id='.$room_id,'active_flag'=>0,'notification_desc'=>$body_message);
							sendNotification($notification);
						}
						if($user_type->user_type==2){
							$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value3['user_id'],'link'=>'/chat-list?room_id='.$room_id,'active_flag'=>0,'notification_desc'=>$body_message);
							sendNotification($notification);
							$add = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','=',$value3['user_id'])->first();
							if(Auth::user()->user_type==3 && $add->unread_count > 1 && $add->Is_send_email!=1){
								$agencyRelation=AgencyRelationModel::where('user_id',$value3['user_id'])->first();
								$agency_data=UserModel::where('id',$agencyRelation->agency_id)->with(['agency'])->first();
								$emailContent = EmailMasterModel::where('id',32)->first();
				                if(!empty($emailContent)){
				                    $subject = $emailContent->subject;
				                    $subject = str_replace('{{USERNAME}}',Auth::user()->first_name.' '.Auth::user()->last_name, $emailContent->subject);
				                    $messageContent = $emailContent->content;
				                    if(!empty($agency_data->agency->agency_name)){
				                        $messageContent = str_replace('{{AGENCYNAME}}',$agency_data->agency->agency_name, $messageContent);
				                    }
				                    if(!empty(Auth::user()->profile_img)){
						                $userLpgo="<br><div style='height: 80px;width: 80px;display: inline-flex; justify-content: center;align-items: center;border-radius: 100%;overflow: hidden;'><img src=".url('public/uploads/profile_photos/'.Auth::user()->profile_img)." style='height: auto;width: auto;max-width: 100%;max-height: 80px;'></div><br>";

						            }else{ 
						                $userLpgo="<br><div style='height: 80px;width: 80px;display: inline-flex; justify-content: center;align-items: center;border-radius: 100%;overflow: hidden;'><img src=".url('public/assets/images/ic_user_black.png')." style='height: auto;width: auto;max-width: 100%;max-height: 80px;'></div><br>";
						            }
						            $userName="<b>".Auth::user()->first_name.' '.Auth::user()->last_name."</b><br>";
				                    $messageContent = str_replace('{{USERNAME}}',$userName, $messageContent);
				                    $messageContent = str_replace('{{USERIMAGE}}',$userLpgo, $messageContent);
				                    $url="<a style='border: none;display: inline-block;padding: 6px 20px;text-align: center;background-color: #4E43FC;color: #fff; text-decoration: none;font-size: 16px;' href=".url('/agent/chat-list?room_id='.$room_id).">View Message</a>";
				                    $messageContent = str_replace('{{URL}}',$url, $messageContent);
				                    $messageContent='<div style="text-align:center">'.$messageContent.'</div>';
				                    $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency->agency_logo);
				                    $student_log = EmailSmsLog::create([
				                        'user_id' => $value3['user_id'],
				                        'subject' => $subject,
				                        'email_content' => $messageContent,
				                        'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS,
				                        'logo'=>$agency_logo,
				                    ]);
				                    ChatRoomUsers::where('room_id',$room_id)->where('user_id',$value3['user_id'])->update(['Is_send_email'=>'1']);
				                }

							}
						}
						if($user_type->user_type==3){
							$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value3['user_id'],'link'=>'/customer-chat-list?room_id='.$room_id,'active_flag'=>0,'notification_desc'=>$body_message);
							sendNotification($notification);
						}
						
						/*if(!empty($tokens) && count($tokens) > 0)
						{
							//increaseBadge($value3['user_id']);					
		                    $notification_data = [];
		                    $notification_data['title'] = "Message Sent";
		                    $notification_data['body'] = $body_message;
		                    $notification_data['type'] = "message-sent";
		                    $notification_data['badge'] = getBadge($value3['user_id']);
		                    sendPushNotification($tokens, "Message Sent", $body_message,$notification_data,$notification_data['badge']);
						}*/

		        	}

		        	return response()->json(['code' => 200,'message'=>$chat_message,'time'=>Carbon::now()]);
		        }else{
		        	return response()->json(['code' => 201,'message'=>"Something Went Wrong"]);
		        }
			}
			else if ($message_type == '2') 
			{
				$path=	public_path("uploads/chat/".$room_id);

				if(!is_dir($path)){
					mkdir($path, 0777, TRUE);
				}

                $validator = Validator::make($request->all(), [
		          'chat_img' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			    ]);
				if ($validator->fails()) {
				    return response()->json(['code' => 201,'message'=>$validator->messages()->first()]);
				}

				if(isset($input['chat_img']) && !empty($input['chat_img']) && $input['chat_img'] != null)
	            {
	            	$user_image_data = $input['chat_img'];
		  			$destinationPath = "public/uploads/chat/".$room_id;
		            $user_image = 'chat-image-'.time().'.'.$user_image_data->getClientOriginalExtension();
		            $user_image_data->move($destinationPath,$user_image);
		            $chat_message=$destinationPath.'/'.$user_image;
	            }

	            $chatRoom = new ChatMessages;
				$chatRoom->from_id = Auth::user()->id;
				$chatRoom->room_id = $room_id;
				$chatRoom->body = $chat_message;
				$chatRoom->message_type = '2';
				$chatRoom->created_date = Carbon::now();
		        $chatRoom->updated_date = Carbon::now();
		        if($chatRoom->save()){

		        	$otherMember1 = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','!=',Auth::user()->id)->get()->toArray();

		        	foreach ($otherMember1 as $key6 => $value6) 
		        	{
						$add = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','=',$value6['user_id'])->first();
						$add->unread_count = $add->unread_count+1;
						$add->updated_date = Carbon::now();
						$add->save();
						

						//Send Notification,Email and SMS to SP
						//$tokens = getUsertokens($value6['user_id']);
						
						$body_message = Auth::user()->first_name.' '.Auth::user()->last_name." have sent message.";
						$user_type=UserModel::where('id',$value6['user_id'])->first();
						if($user_type->user_type==1){
							$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value6['user_id'],'link'=>'/customer-chat-list?room_id='.$room_id,'active_flag'=>0,'notification_desc'=>$body_message);
							sendNotification($notification);
						}
						if($user_type->user_type==2){
							$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value6['user_id'],'link'=>'/chat-list?room_id='.$room_id,'active_flag'=>0,'notification_desc'=>$body_message);
							sendNotification($notification);

							$add = ChatRoomUsers::where('room_id','=',$room_id)->where('user_id','=',$value6['user_id'])->first();
							if(Auth::user()->user_type==3 && $add->unread_count > 1 && $add->Is_send_email!=1){
								$agencyRelation=AgencyRelationModel::where('user_id',$value6['user_id'])->first();
								$agency_data=UserModel::where('id',$agencyRelation->agency_id)->with(['agency'])->first();
								$emailContent = EmailMasterModel::where('id',32)->first();
				                if(!empty($emailContent)){
				                    $subject = $emailContent->subject;
				                    $subject = str_replace('{{USERNAME}}',Auth::user()->first_name.' '.Auth::user()->last_name, $emailContent->subject);
				                    $messageContent = $emailContent->content;
				                    if(!empty($agency_data->agency->agency_name)){
				                        $messageContent = str_replace('{{AGENCYNAME}}',$agency_data->agency->agency_name, $messageContent);
				                    }
				                    if(!empty(Auth::user()->profile_img)){
						                $userLpgo="<br><div style='height: 80px;width: 80px;display: inline-flex; justify-content: center;align-items: center;border-radius: 100%;overflow: hidden;'><img src=".url('public/uploads/profile_photos/'.Auth::user()->profile_img)." style='height: auto;width: auto;max-width: 100%;max-height: 80px;'></div><br>";

						            }else{ 
						                $userLpgo="<br><div style='height: 80px;width: 80px;display: inline-flex; justify-content: center;align-items: center;border-radius: 100%;overflow: hidden;'><img src=".url('public/assets/images/ic_user_black.png')." style='height: auto;width: auto;max-width: 100%;max-height: 80px;'></div><br>";
						            }
						            $userName="<b>".Auth::user()->first_name.' '.Auth::user()->last_name."</b><br>";
				                    $messageContent = str_replace('{{USERNAME}}',$userName, $messageContent);
				                    $messageContent = str_replace('{{USERIMAGE}}',$userLpgo, $messageContent);
				                    $url="<a style='border: none;display: inline-block;padding: 6px 20px;text-align: center;background-color: #4E43FC;color: #fff; text-decoration: none;font-size: 16px;' href=".url('/agent/chat-list?room_id='.$room_id).">View Message</a>";
				                    $messageContent = str_replace('{{URL}}',$url, $messageContent);
				                    $messageContent='<div style="text-align:center">'.$messageContent.'</div>';
				                    $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency->agency_logo);
				                    $student_log = EmailSmsLog::create([
				                        'user_id' => $value6['user_id'],
				                        'subject' => $subject,
				                        'email_content' => $messageContent,
				                        'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS,
				                        'logo'=>$agency_logo,
				                    ]);
				                    ChatRoomUsers::where('room_id',$room_id)->where('user_id',$value6['user_id'])->update(['Is_send_email'=>'1']);
				                }

							}
							
						}
						if($user_type->user_type==3){
							$notification=array('from_user_id'=>Auth::user()->id,'to_user_id'=>$value6['user_id'],'link'=>'/customer-chat-list?room_id='.$room_id,'active_flag'=>0,'notification_desc'=>$body_message);
							sendNotification($notification);
						}
						/*if(!empty($tokens) && count($tokens) > 0)
						{
							increaseBadge($value6['user_id']);					
		                    $notification_data = [];
		                    $notification_data['title'] = "Message Sent";
		                    $notification_data['body'] = $body_message;
		                    $notification_data['type'] = "message-sent";
		                    $notification_data['badge'] = getBadge($value6['user_id']);
		                    sendPushNotification($tokens, "Message Sent", $body_message,$notification_data,$notification_data['badge']);
						}*/

		        	}
		        	return response()->json(['code' => 200,'message'=>$chat_message,'time'=>Carbon::now()]);
		        }else{
		        	return response()->json(['code' => 201,'message'=>"Something Went Wrong"]);
		        }
			}

    	} catch (Exception $e) {
    		return response()->json(['code' => 201,'message'=>$e->getmessage()]);
    	}
    }
    public function getChatMessage(Request $request){
    	extract($_POST);
    	$current_room_id=$room_id;
    	ChatRoomUsers::where('room_id',$current_room_id)->where('user_id',Auth::user()->id)->update(['unread_count'=>0,'Is_send_email'=>0,'updated_date'=>Carbon::now()]);
        $chatMessages=ChatMessages::select('chat_messages.*','u.first_name','u.last_name','u.profile_img','u.user_type')->leftJoin('users as u','u.id','=','chat_messages.from_id')->where('room_id',$current_room_id)->get()->toArray();
        $html='';
    	if(!empty($chatMessages)){
            foreach($chatMessages as $message){
	            if($message['user_type']=='1'){
                    $agencyDetails=\App\Models\AgencyModel::where('user_id',$message['from_id'])->first();
                        if(!empty($agencyDetails->agency_logo)){ 
                            $chatUserLpgo=url('public/uploads/profile_photos/'.$agencyDetails->agency_logo);
                        }else{
                                $chatUserLpgo=url('public/assets/images/ic_user_black.png');
                            }
                             
                    }else{
                        if(!empty($message['profile_img'])){
                            $chatUserLpgo=url('public/uploads/profile_photos/'.$message['profile_img']);
                        }else{ 
                            $chatUserLpgo=url('public/assets/images/ic_user_black.png');
                        } 
                    }	
                	if($message['from_id']==Auth::user()->id){
                            $html.='<div class="rply">
                                <div class="msg_txt">
                                    <p>
                                        <span class="chat_name">'.$message['first_name'].' '.$message['last_name'].'</span><br>';
                                        if($message['message_type']=='2'){
                                            $html.='<img src="'.url('/'.$message['body']).'"><br>';
                                        }
                                        else{
                                            $html.=$message['body'].'<br>';
                                        }
                                        
                                        $html.='<span class="chat_time">'; $timezone = getCurrentUserTimeZone(Auth::User()->id);
                                            $start_time = changeTimeByTimezone($message['created_date'],$timezone);$html.=$start_time;
                                        $html.='</span>
                                    </p><br>
                                </div>
                                <div class="rply_profile">
                                    <img src="'.$chatUserLpgo.'">
                                </div>
                            </div>';
                        
                        }else{
                            $html.='<div class="msg">
                                    <div class="msg_profile">
                                        <img src="'.$chatUserLpgo.'">
                                    </div>
                                    <div class="msg_txt">
                                        <p>
                                            <span class="chat_name">'.$message['first_name'].' '.$message['last_name'].'</span><br>';
                                            if($message['message_type']=='2'){
                                                $html.='<img src="'.url('/'.$message['body']).'"><br>';
                                            }
                                            else{
                                            	$html.=$message['body'].'<br>';
                                            }
                                            $html.='<span class="chat_time">';
                                            $timezone = getCurrentUserTimeZone(Auth::User()->id);
                                            $start_time = changeTimeByTimezone($message['created_date'],$timezone); $html.=$start_time; $html.='</span>
                                        </p><br>
                                    </div>
                                </div>';    
                          
                    }
                }           
                    $html.='<div class="text-right">
                        <div class="open_list">
                            <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/msg_white.png">
                        </div>
                    </div>';
            }
        
        return response()->json(['code' => 200,'html'=>$html]);
    }
    public function getUnreadUserCount(Request $request){
    	extract($_POST);
    	if(!empty($room_id)){
    		ChatRoomUsers::where('room_id',$room_id)->where('user_id',Auth::user()->id)->update(['unread_count'=>0,'Is_send_email'=>0,'updated_date'=>Carbon::now()]);
    	}
    	$currentChatRooms=ChatRoomUsers::where('room_id',$room_id)->where('user_id',Auth::user()->id)->get()->toArray();
    	if(!empty($currentChatRooms) && count($currentChatRooms)>0){
    		foreach ($currentChatRooms as $key=>$value) {
                $otherMember = ChatRoomUsers::select('u.first_name','u.last_name','u.user_type')->where('room_id','=',$value['room_id'])->leftJoin('users as u','u.id','=','chat_room_user.user_id')->where('user_id','!=',Auth::user()->id)->get()->toArray();
                $name='';
                foreach ($otherMember as $user) {
                	if(Auth::user()->user_type==2){
                		if($user['user_type']!=1){
	                        $name.=$user['first_name'].' '.$user['last_name'].', ';
	                    }
                	}
                	elseif(Auth::user()->user_type==3){
                		if($user['user_type']!=1){
	                        $name.=$user['first_name'].' '.$user['last_name'].', ';
	                    }
                	}else{
                		$name.=$user['first_name'].' '.$user['last_name'].', ';
                	}
                    
                }
                $name=rtrim($name, ", ");
                $currentChatRooms[$key]['group_name']=$name;
            }
        }
    	$chatRooms=ChatRoomUsers::where('user_id',Auth::user()->id)->where('unread_count','>',0)->get()->toArray();

        if(!empty($chatRooms) && count($chatRooms)>0){

            foreach ($chatRooms as $key=>$value) {
                $otherMember = ChatRoomUsers::select('u.first_name','u.last_name','u.user_type')->where('room_id','=',$value['room_id'])->leftJoin('users as u','u.id','=','chat_room_user.user_id')->where('user_id','!=',Auth::user()->id)->get()->toArray();
                $name='';
                foreach ($otherMember as $user) {
                    if(Auth::user()->user_type==2){
                		if($user['user_type']!=1){
	                        $name.=$user['first_name'].' '.$user['last_name'].', ';
	                    }
                	}
                	elseif(Auth::user()->user_type==3){
                		if($user['user_type']!=1){
	                        $name.=$user['first_name'].' '.$user['last_name'].', ';
	                    }
                	}else{
                		$name.=$user['first_name'].' '.$user['last_name'].', ';
                	}
                }
                $name=rtrim($name, ", ");
                $chatRooms[$key]['group_name']=$name;
            }
        }
        $count = ChatRoomUsers::where('user_id','=',Auth::user()->id)->where('unread_count','>',0)->count();
        $chatRooms['total_unread_count']=$count;
        return response()->json(['data'=>$chatRooms,'currentChatRooms'=>$currentChatRooms]);
    }
}
