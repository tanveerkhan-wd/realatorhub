<?php
use Illuminate\Filesystem\Filesystem;
use App\Models\NotificationMaster;
use App\Models\ChatMessages;
use App\Models\ChatRooms;
use App\Models\ChatRoomUsers;
use Carbon\Carbon;
/**
* Helper
*
* This file is used for making helper function.This is return data without creating object.
* 
* @package    Laravel
* @subpackage Helper Function 
* @since      1.0
*/
if (!function_exists('checkAgencyLogin'))
{
    /**
     * check Agency Login   
     * @return array
     */ 
    function checkAgencyLogin()
    {
        $user_id=Auth::user()->id;
        $user_type=Auth::user()->user_type;
        $url=url('');
        if(!empty($user_id)){
            $data = \App\Models\UserModel::where('id',$user_id)->where('user_type',$user_type)->first();
            if(empty( $data)){
                header("Location:".$url."/agency/logout");
                exit;
            }else{
                return $data;
            }
        }else{
            header("Location:".$url."/agency/logout");
            exit;
        }
        
    }
}
if (!function_exists('get_notification'))
{
    /**
     * check Agency Login   
     * @return array
     */ 
    function get_notification()
    {
        $user_id=Auth::user()->id;
        $data = \App\Models\NotificationMaster::where('to_user_id',$user_id)->where('active_flag','=','0')->orderBy('n_id','desc')->get();
        return $data;       
    }
}
if (!function_exists('getDateTimeFormate'))
{
    function getDateTimeFormate($user_id='',$dateTime)
    {
        $date_format='';
        $time_format='';
        if($user_id!=''){
            $data = \App\Models\SettingModel::where('user_id',$user_id)->get()->toArray();
            $singleData = array_column($data,'text_value','text_key');
            if(empty($singleData['date_format'])){
                $data = \App\Models\SettingModel::where('user_id',1)->get()->toArray();
                $singleData = array_column($data,'text_value','text_key');
                $date_format=$singleData['date_format'];
            }else{
                $date_format=$singleData['date_format'];
            }
            if(empty($singleData['time_format'])){
                $data = \App\Models\SettingModel::where('user_id',1)->get()->toArray();
                $singleData = array_column($data,'text_value','text_key');
                $time_format=$singleData['time_format'];
            }else{
                $time_format=$singleData['time_format'];
            }
        }else{
            $data = \App\Models\SettingModel::where('user_id',1)->get()->toArray();
            $singleData = array_column($data,'text_value','text_key');
            $date_format=$singleData['date_format'];
            $time_format=$singleData['time_format'];
        }
        $date_time=date($date_format.' '.$time_format ,strtotime($dateTime));
        
        
        return $date_time;
        exit;       
    }
}
if (!function_exists('getDateFormate'))
{
    function getDateFormate($user_id='',$dateTime)
    {
        $date_format='';
        $time_format='';
        if($user_id!=''){
            $data = \App\Models\SettingModel::where('user_id',$user_id)->get()->toArray();
            $singleData = array_column($data,'text_value','text_key');
            if(empty($singleData['date_format'])){
                $data = \App\Models\SettingModel::where('user_id',1)->get()->toArray();
                $singleData = array_column($data,'text_value','text_key');
                $date_format=$singleData['date_format'];
                
            }else{
                $date_format=$singleData['date_format'];
            }
        }else{
            $data = \App\Models\SettingModel::where('user_id',1)->get()->toArray();
            $singleData = array_column($data,'text_value','text_key');
            $date_format=$singleData['date_format'];
            $time_format=$singleData['time_format'];
        }
        $date_time=date('d-m-Y',strtotime($dateTime));
        return $date_time;
        exit;       
    }
}
if (!function_exists('getMenu')) 
{
    /**
     * get Menu Data from this logo favicon get   
     * @return array
     */ 
    function getMenu()
    {
        $keywords = "home_";
        $data = \App\Models\SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray();   
        $singleData = array_column($data,'text_value','text_key');
        return $singleData;
        exit; 
    }
}
if (!function_exists('getTimeZones'))
{
    /**
     * create Application id
     * @return integer
     */
    function getTimeZones()
    {
        $timezones = \App\Models\TimezoneModel::where('status','Active')->get();
        // $timezone_array = explode(' ', $user->timezone);
        return $timezones;
    }
}
if (!function_exists('getGeneralSettings')) 
{
    /**
     * get Menu Data from this logo favicon get   
     * @return array
     */ 
    function getGeneralSettings($user_id='')
    {
        if(empty($user_id)){
            $user_id=Session::get('user_id');
            $user_type=Session::get('user_type');
            $agency_id = Session::get('agency_id');
            if(!empty($agency_id)){
                $user_id=$agency_id;
            }
        }
        $data = \App\Models\SettingModel::where('user_id',$user_id)->get()->toArray(); 
        if(empty($data)){
            $data = \App\Models\SettingModel::where('user_id',1)->get()->toArray();
        }  
        $singleData = array_column($data,'text_value','text_key');
        return $singleData;
        exit; 
    }
}
if (!function_exists('getSettings'))
{
    /**
     * get Menu Data from this logo favicon get
     * @return array
     */
    function getSettings($user_id='')
    {
//        $keywords = "home_";
        $data = \App\Models\SettingModel::where('user_id',1)->get()->toArray();
        $singleData = array_column($data,'text_value','text_key');
        return $singleData;
        exit;
    }
}
if (!function_exists('getSEO')) 
{
    /**
     * get seo title and description
     * @return array
     */ 
    function getSEO($slug)
    {
        $data = \App\Models\SEOModel::where('slug',$slug)->first();   
        return $data;
    }
}
if (!function_exists('login_user_data')) 
{
    /**
     * get Menu Data from this logo favicon get   
     * @return array
     */ 
    function login_user_data() {
        $user_id = Session::get('user_id');

        $data = \App\Models\Users::where('id',$user_id)->first();

        return $data;
    }
}
if (!function_exists('get_agency_data')) 
{
    /**
     * get Menu Data from this logo favicon get   
     * @return array
     */ 
    function get_agency_data() {
        $agency_id = Session::get('agency_id');

        $data = \App\Models\UserModel::where('id',$agency_id)->with(['agency'])->first();

        return $data;
    }
}
if (!function_exists('get_agent_agency_data')) 
{
    /**
     * get Menu Data from this logo favicon get   
     * @return array
     */ 
    function get_agent_agency_data() {
        $agentRelation=\App\Models\AgencyRelationModel::where('user_id',Auth::user()->id)->first();
        $agency_id = $agentRelation->agency_id;

        $data = \App\Models\UserModel::where('id',$agency_id)->with(['agency'])->first();

        return $data;
    }
}
if (!function_exists('them_color')) 
{
    function them_color($color){

    }

}
if (!function_exists('getCurrentUserTimeZone'))
{
    /**
     * create Application id
     * @return integer
     */
    function getCurrentUserTimeZone($user_id)
    {
        $user = App\Models\UserModel::select('users.id','timezone_list.timezone','timezone_list.title')->leftjoin('timezone_list','timezone_list.id','=','users.timezone')->where('users.id','=',$user_id)->first();
        // $timezone_array = explode(' ', $user->timezone);
        return trim($user->title);
    }
}
if (!function_exists('changeTimeByTimezone'))
{
    function changeTimeByTimezone($time,$timezone)
    {
        $timezone = trim($timezone);
        $datetime = new \DateTime($time);
        $la_time = new \DateTimeZone($timezone);
        $datetime->setTimezone($la_time);
        return $datetime->format('Y-m-d H:i');
    }
}
if (!function_exists('randomPassword'))
{
    /**
     * create Application id
     * @return integer
     */
    function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
if (!function_exists('sendNotificationWeb'))
{
    function sendNotificationWeb($user_id,$message="",$title = "Studyn8",$action="https://www.studym8.com.au") 
    {
        $tokenget = \App\Models\UserToken::where('user_id','=',$user_id)->first();
        if(!empty($tokenget) && $tokenget != null)
        {
            $token = $tokenget->token;
            $API_ACCESS_KEY = 'AAAAxkwII2A:APA91bG1sYilfIAjQq3BKp3gAHUr0Jt1De5_Do6OWSZD_uQVZSPiYEpIuIJBfW1P_wg5sm5GaRWcsRJTWANwOb-lbdj_enliVGLPM59RQqH_FAcGu1ump-pAjNwCOFfpK7p8e8FfEPBu';
            $url = "https://fcm.googleapis.com/fcm/send";
            // Server key from Firebase Console define( 'API_ACCESS_KEY', 'AAAA----FE6F' );
            $data = array("to" => $token, "notification" => array( "title" => $title, "body" => $message,"icon" => "icon.png", "click_action" => $action)); 
            $data_string = json_encode($data); 
            //echo "The Json Data : ".$data_string; 
            $headers = array ( 'Authorization: key=' . $API_ACCESS_KEY, 'Content-Type: application/json' ); 
            $ch = curl_init(); curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' ); 
            curl_setopt( $ch,CURLOPT_POST, true ); 
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers ); 
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true ); 
            curl_setopt( $ch,CURLOPT_POSTFIELDS, $data_string); 
            $result = curl_exec($ch); 
            curl_close ($ch); 
            return 1;
            //echo "<p>&nbsp;</p>"; 
            //echo "The Result : ".$result;
        }
    }
}
if (!function_exists('countNotification'))
{
    /**
     * create Application id
     * @return integer
     */
    function countNotification($id)
    {

        $count = \App\Models\NotificationMaster::
        where('to_user_id','=',$id)->where('active_flag','=','0')
            ->count();
        return $count;
    }
}
if (!function_exists('deleteProperty'))
{
    /**
     * create Application id
     * @return integer
     */
    function deleteProperty($agency_id,$propertyId)
    {
        $poeprty=\App\Models\PropertyModel::where('agency_id',$agency_id)->where('id',$propertyId)->first();
        if(!empty($poeprty)){
            \App\Models\PropertyModel::where('agency_id',$agency_id)->where('id',$propertyId)->delete();
            \App\Models\PropertyLeadsModel::where('property_id',$propertyId)->delete();
            \App\Models\PropertyOtherImages::where('property_id',$propertyId)->delete();
            \App\Models\PropertyVideo::where('property_id',$propertyId)->delete();
            \App\Models\PropertyUnit::where('property_id',$propertyId)->delete();
            $directory='uploads/properties_images/'.$propertyId.'/';
            if (File::exists(public_path($directory))){
                File::deleteDirectory(public_path($directory));
            }
            $directory='uploads/properties_video/'.$propertyId.'/';
            if (File::exists(public_path($directory))){
                File::deleteDirectory(public_path($directory));
            }
            $message['message']='Property Deleted Successfully.';
            $message['code']=200;
            return $message;
        }else{
            $message['message']='Something Went Wrong Please Try Again.';
            $message['code']=200;
            return $message;
        } 
    }
}
if (!function_exists('deleteAdminProperty'))
{
    /**
     * create Application id
     * @return integer
     */
    function deleteAdminProperty($propertyId)
    {
        $poeprty=\App\Models\PropertyModel::where('id',$propertyId)->first();
        if(!empty($poeprty)){
            \App\Models\PropertyModel::where('id',$propertyId)->delete();
            \App\Models\PropertyLeadsModel::where('property_id',$propertyId)->delete();
            \App\Models\PropertyOtherImages::where('property_id',$propertyId)->delete();
            \App\Models\PropertyVideo::where('property_id',$propertyId)->delete();
            \App\Models\PropertyUnit::where('property_id',$propertyId)->delete();
            $directory='uploads/properties_images/'.$propertyId.'/';
            if (File::exists(public_path($directory))){
                File::deleteDirectory(public_path($directory));
            }
            $directory='uploads/properties_video/'.$propertyId.'/';
            if (File::exists(public_path($directory))){
                File::deleteDirectory(public_path($directory));
            }
            $message['message']='Property Deleted Successfully.';
            $message['code']=200;
            return $message;
        }else{
            $message['message']='Something Went Wrong Please Try Again.';
            $message['code']=200;
            return $message;
        } 
    }
}
if (!function_exists('updateAgentChat'))
{
    /**
     * create Application id
     * @return integer
     */
    function updateAgentChat($fromAgentId,$toAgentId,$agency_id)
    {
        $ChatRoomUsers=ChatRoomUsers::where('user_id',$fromAgentId)->get();
        if(!empty($ChatRoomUsers) && count($ChatRoomUsers)>0){
            foreach ($ChatRoomUsers as $room_id) {
                $customer=ChatRoomUsers::select('chat_room_user.*','u.user_type')->leftJoin('users as u','u.id','=','chat_room_user.user_id')->where('chat_room_user.room_id',$room_id->room_id)->where('u.user_type',3)->get()->toArray();
                $roomIdExit=0;
                foreach ($customer as $value) {
                    $count=ChatRoomUsers::where('room_id',$value['room_id'])->where('user_id',$toAgentId)->count();   
                    if($count == 0){
                        
                        $chatRoom = new ChatRooms;
                        $chatRoom->created_date = Carbon::now();
                        $chatRoom->save();
                        
                        $chatRoomUser= new ChatRoomUsers;
                        $chatRoomUser->room_id=$chatRoom->id;
                        $chatRoomUser->user_id=$value['user_id'];
                        $chatRoomUser->save();

                        $chatRoomUser= new ChatRoomUsers;
                        $chatRoomUser->room_id=$chatRoom->id;
                        $chatRoomUser->user_id=$toAgentId;
                        $chatRoomUser->save();

                        $chatRoomUser= new ChatRoomUsers;
                        $chatRoomUser->room_id=$chatRoom->id;
                        $chatRoomUser->user_id=$agency_id;
                        $chatRoomUser->save();
                    }   
                }
            }
        } 
    }
}
if (!function_exists('removeAgencyChat'))
{
    /**
     * create Application id
     * @return integer
     */
    function removeAgencyChat($agency_id)
    {
        $ChatRoom=ChatRoomUsers::where('user_id',$agency_id)->get();
        foreach ($ChatRoom as $value) {
            ChatRooms::where('id',$value->room_id)->delete();
            ChatRoomUsers::where('room_id',$value->room_id)->delete();
            ChatMessages::where('room_id',$value->room_id)->delete();
            $directory='uploads/chat/'.$value->room_id.'/';
            if (File::exists(public_path($directory))){
                File::deleteDirectory(public_path($directory));
            }
        }
    }
}
function encryptValue($value) {
    $inputKey = '12345678901234561234567890123456';

    $blockSize = 256;
    $aes = new \AES($value, $inputKey, $blockSize);
    $enc = $aes->encrypt();
    return base64_encode($enc);
}

function decyptValue($value) {

    $inputKey = '12345678901234561234567890123456';
    $blockSize = 256;
    $aes = new \AES(base64_decode($value), $inputKey, $blockSize);
    $enc = $aes->decrypt();
    return ($enc);
}
function getUserTypeCount($type){
    $get_user_type_count=  \App\Models\UserModel::where('user_type','=',$type)->count();
    return (!empty($get_user_type_count))?$get_user_type_count:0;
}

function getLiveProperties(){
    $get_property=  App\Models\PropertyModel::all()->count();
    return (!empty($get_property))?$get_property:0;
}
function getLeadsGenerated(){
    $get_leads=  App\Models\PropertyLeadsModel::all()->count();
     return (!empty($get_leads))?$get_leads:0;
}
function getPricingPlans(){
    $subscriptionPlans = \App\Models\SubscriptionPlanModel::withCount(['subscriptions'])
                ->where('is_deleted',\App\Models\SubscriptionPlanModel::IS_DELETED_NO)->orderBy('monthly_price','ASC')->get();
    return (!empty($subscriptionPlans))?$subscriptionPlans:'';

}
function getSettingsHome($key){
    $setting=  App\Models\SettingModel::where('text_key','=',$key)->first();
    $setting_textvalue=$setting->text_value;
    return (!empty($setting_textvalue))?$setting_textvalue:'';
}
function getCmsSettingDetail($id){
    $cms_data=  App\Models\CMSModel::where('id','=',$id)->where('status','=','1')->first();
     return (!empty($cms_data))?$cms_data:'';
}
function getWhyRealtorHub(){
    $key='hom_why_realtor_hubs_';
    $setting=  App\Models\SettingModel::where('text_key','LIKE','%'.$key.'%')->get()->toArray();
    $whyData = array_column($setting,'text_value','text_key');
    return (!empty($whyData))?$whyData:'';
}
if (!function_exists('sendNotification'))
{
    function sendNotification($notification)
    {
        NotificationMaster::insert($notification);
    }
}
if (!function_exists('getChatUnreadCount'))
{
    function getChatUnreadCount()
    {
        $unreadCount=0;
        if(Auth::check() == true){
            $unreadCount=App\Models\ChatRoomUsers::where('user_id','=',Auth::user()->id)->where('unread_count','>',0)->count();
            //echo "<pre>"; print_r($unreadCount); exit;
            if(!empty($unreadCount)){
                $unreadCount=$unreadCount;
            }            
        }
        return $unreadCount;
    }
}