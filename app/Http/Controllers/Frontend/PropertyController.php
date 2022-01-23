<?php
/**
* HomeController 
*
* This file is used for ckeck list Page
* 
* @package    Laravel
* @subpackage Controller
* @since      1.0
*/

namespace App\Http\Controllers\Frontend;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\PropertyModel;
use App\Models\PropertyUnit;
use App\Models\PropertyOtherImages;
use App\Models\AgencyModel;
use App\Models\EmailMasterModel;
use App\Models\EmailSmsLog;
use App\Models\NotificationMaster;
use App\Models\PropertyLeadsModel;
use App\Models\UserModel;
use App\Models\PropertyVideo;
use App\Models\FavProperty;
use App\Models\AgencyRelationModel;
use Validator;
use Route;
use App\User;
use Session;
use Auth;

use DB;

class PropertyController extends Controller
{
    public function index(Request $request) {
        $keywords = "home_";
        $data = SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray(); 
        $data = array_column($data,'text_value','text_key'); 
        //echo "<pre>"; print_r($data); exit;
        return view('frontend.property.properties_page',compact('data'));
    }
    public function propertiesList(Request $request) {
        /*$data = AgencyModel::leftjoin('users', 'agency.user_id', '=', 'users.id')
                ->leftjoin(DB::raw('(SELECT id as subscriptionID,subscription_id,user_id, plan_id FROM `subscriptions` GROUP BY user_id ORDER BY subscriptionID DESC) subscriptions'), 
                function($join)
                {
                   $join->on('agency.user_id', '=', 'subscriptions.user_id');
                })->leftjoin('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
                ->select('agency.id as agencyID','agency.agency_name', 'users.first_name', 'users.last_name', 'users.email', 'users.phone', 'subscriptions.plan_id', 'subscription_plans.plan_name','subscriptions.subscriptionID')
                ->orderby('agency.id','asc')->get()->toArray();
                echo "<pre>"; print_r($data); exit;*/
        $agency_id = Session::get('agency_id');
        $slug = Session::get('slug');
        if(isset($address) && !empty($address)){
            //echo $address;
            //exit;
           /* $lat_long=$this->get_lat_long($address);
            $property_lists=PropertyModel::selectRaw("id,address,price,beds,baths,sq_feet,latitude,longitude,city,state,status,type,purpose,IF(address = '$address',1,0) as is_address_matched, (3959 * acos (cos ( radians('" . $lat_long['lat'] . "') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('" . $lat_long['long'] . "') ) + sin ( radians('" . $lat_long['lat'] . "') ) * sin( radians( latitude ) ) ) ) AS distance");
            
            //echo "<pre>"; print_r($lat_long); exit;
            $property_lists = $property_lists->whereRaw("((3959 * acos (cos ( radians('" . $lat_long['lat'] . "') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('" . $lat_long['long'] . "') ) + sin ( radians('" . $lat_long['lat'] . "') ) * sin( radians( latitude ) ) ) ) <= '5') ");
            if(isset($lat_long['city']) && !empty($lat_long['city'])){
                $city=$lat_long['city'];
                $zipcode=$lat_long['zipcode'];
                $property_lists = $property_lists->where(function($query) use ($city,$zipcode){
                    $query->where('city', $city);
                    $query->orWhere('zipcode', $zipcode);
                });
            }
            if(isset($lat_long['state']) && !empty($lat_long['state'])){
                $property_lists = $property_lists->where('state',$lat_long['state']);
            }
            
            $property_lists = $property_lists->orderBy('distance', 'ASC')->groupBy('id');*/
            $property_lists=PropertyModel::where('agency_id',$agency_id)->where('status',1)->where(function($query) use ($address){
                $query->where('address', 'LIKE', "%{$address}%");
                $query->orWhere('city', 'LIKE', "%{$address}%");
                $query->orWhere('state', 'LIKE', "%{$address}%");
                $query->orWhere('country', 'LIKE', "%{$address}%");
            })->groupBy('id');
        }else{
            $property_lists=PropertyModel::where('agency_id',$agency_id)->orderBy('id', 'DESC');
        }
        //$property_lists=$property_lists->where('agency_id',$agency_id);
        if(isset($min_price) && !empty($min_price) && isset($max_price) && !empty($max_price)){
            $property_lists= $property_lists->where('price', '>=', $min_price)->where('price', '<=', $max_price);
        }else{
            if(isset($min_price) && !empty($min_price)){
                $property_lists= $property_lists->where('price', $min_price);
            }
            if(isset($max_price) && !empty($max_price)){
                $property_lists= $property_lists->where('price', $max_price);
            }
        }
        if(isset($min_sq) && !empty($min_sq) && isset($max_sq) && !empty($max_sq)){
            $property_lists= $property_lists->where('sq_feet', '>=', $min_sq)->where('sq_feet', '<=', $max_sq);
        }else{
            if(isset($min_sq) && !empty($min_sq)){
                $property_lists= $property_lists->where('sq_feet', $min_sq);
            }
            if(isset($max_sq) && !empty($max_sq)){
                $property_lists= $property_lists->where('sq_feet', $max_sq);
            }
        }
        
        if(isset($beds) && $beds!=''){
            if($beds==3){
                $property_lists= $property_lists->where('beds','>=', $beds);
            }else{
                $property_lists= $property_lists->where('beds',$beds);
            }
            
        }
        if(isset($baths) && $baths!=''){
            if($baths==3){
                $property_lists= $property_lists->where('baths','>=', $baths);
            }else{
                $property_lists= $property_lists->where('baths', $baths);
            }
            
        }
        if(isset($property_type) && !empty($property_type)){
            $property_lists= $property_lists->where('type', $property_type);
        }
        if(isset($purpose) && !empty($purpose)){
            $property_lists= $property_lists->where('purpose', $purpose);
        }
        $property_lists= $property_lists->where('status', 1);
        $all_property_lists=$property_lists->get()->count();
        $property_lists=$property_lists->paginate(10);
        foreach($property_lists as $key=>$value) {
            if(Auth::check() == true) {
                $user_id = Auth::user()->id;
                $property_lists[$key]['fav_property'] = false;
                $mls_property_fav_data = FavProperty::where('property_id', $value['id'])->where('user_id', $user_id)->first();
                if(!empty($mls_property_fav_data)){
                    $property_lists[$key]['fav_property'] = true;
                }             
            }
        }
        //echo $all_property_lists; exit;
        //$property_lists=$property_lists;
        //echo "<pre>"; print_r($property_lists->toArray()); exit;
        //$property_lists='';
        
        return view('frontend.property.list', compact('property_lists','all_property_lists','slug'));
    }
    public function properties_search(Request $request) {
    	//echo "<pre>"; print_r($_GET); exit;
        /*$current_uri = request()->segments();
        echo "<pre>"; print_r($current_uri); exit;*/
        extract($_GET);
        $agency_id = Session::get('agency_id');
        $slug = Session::get('slug');
        $property_lists=PropertyModel::where('agency_id',$agency_id);
        if(isset($address) && !empty($address)){
            //echo $address;
            //exit;
            /*$property_lists=PropertyModel::where(function($query) use ($address){
                $query->where('address', 'LIKE', "%{$address}%");
                $query->orWhere('city', 'LIKE', "%{$address}%");
                $query->orWhere('state', 'LIKE', "%{$address}%");
                $query->orWhere('country', 'LIKE', "%{$address}%");
            })->groupBy('id');*/
            /*$lat_long=$this->get_lat_long($address);
            //echo "<pre>"; print_r($lat_long); exit;
            $property_lists=PropertyModel::selectRaw("id,address,price,beds,baths,sq_feet,latitude,longitude,city,state,status,type,purpose,IF(address = '$address',1,0) as is_address_matched, (3959 * acos (cos ( radians('" . $lat_long['lat'] . "') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('" . $lat_long['long'] . "') ) + sin ( radians('" . $lat_long['lat'] . "') ) * sin( radians( latitude ) ) ) ) AS distance");
            
            //echo "<pre>"; print_r($lat_long); exit;
            $property_lists = $property_lists->whereRaw("((3959 * acos (cos ( radians('" . $lat_long['lat'] . "') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('" . $lat_long['long'] . "') ) + sin ( radians('" . $lat_long['lat'] . "') ) * sin( radians( latitude ) ) ) ) <= '5') ");
            if(isset($lat_long['city']) && !empty($lat_long['city'])){
                $city=$lat_long['city'];
                $zipcode=$lat_long['zipcode'];
                $property_lists = $property_lists->where(function($query) use ($city,$zipcode){
                    $query->where('city', $city);
                    $query->orWhere('zipcode', $zipcode);
                });
            }
            if(isset($lat_long['state']) && !empty($lat_long['state'])){
                $property_lists = $property_lists->where('state',$lat_long['state']);
            }
            
            $property_lists = $property_lists->orderBy('distance', 'ASC')->groupBy('id');*/

        $property_lists=PropertyModel::where('agency_id',$agency_id)->where('status',1)->where(function($query) use ($address){
                $query->where('address', 'LIKE', "%{$address}%");
                $query->orWhere('city', 'LIKE', "%{$address}%");
                $query->orWhere('state', 'LIKE', "%{$address}%");
                $query->orWhere('country', 'LIKE', "%{$address}%");
            })->groupBy('id');
        }else{
            $property_lists=PropertyModel::where('agency_id',$agency_id)->orderBy('id', 'DESC');
        }
        //$property_lists=$property_lists->where('agency_id',$agency_id);
        if(isset($min_price) && !empty($min_price) && isset($max_price) && !empty($max_price)){
        	//echo "string";exit;
            $property_lists= $property_lists->where('price', '>=', $min_price)->where('price', '<=', $max_price);
        }else{
            if(isset($min_price) && !empty($min_price)){
                $property_lists= $property_lists->where('price', $min_price);
            }
            if(isset($max_price) && !empty($max_price)){
                $property_lists= $property_lists->where('price', $max_price);
            }
        }
        if(isset($min_sq) && !empty($min_sq) && isset($max_sq) && !empty($max_sq)){
            $property_lists= $property_lists->where('sq_feet', '>=', $min_sq)->where('sq_feet', '<=', $max_sq);
        }else{
            if(isset($min_sq) && !empty($min_sq)){
                $property_lists= $property_lists->where('sq_feet', $min_sq);
            }
            if(isset($max_sq) && !empty($max_sq)){
                $property_lists= $property_lists->where('sq_feet', $max_sq);
            }
        }
        if(isset($beds) && $beds!=''){
            if($beds==3){
                $property_lists= $property_lists->where('beds','>=', $beds);
            }else{
                $property_lists= $property_lists->where('beds',$beds);
            }
            
        }
        if(isset($baths) && $baths!=''){
            if($baths==3){
                $property_lists= $property_lists->where('baths','>=', $baths);
            }else{
                $property_lists= $property_lists->where('baths', $baths);
            }
            
        }
        if(isset($property_type) && !empty($property_type)){
            $property_lists= $property_lists->where('type', $property_type);
        }
        if(isset($purpose) && !empty($purpose)){
            $property_lists= $property_lists->where('purpose', $purpose);
        }
        $property_lists= $property_lists->where('status', 1);
        $all_property_lists=$property_lists->get()->count();
        $property_lists=$property_lists->paginate(10);
        $general_settings = getGeneralSettings(); 
        if(isset($general_settings['property_display_row']) && !empty($general_settings['property_display_row'])){
            $property_display_row=$general_settings['property_display_row'];
        }else{
            $property_display_row=1;
        }
        foreach($property_lists as $key=>$value) {
            if(Auth::check() == true) {
                $user_id = Auth::user()->id;
                $property_lists[$key]['fav_property'] = false;
                $mls_property_fav_data = FavProperty::where('property_id', $value['id'])->where('user_id', $user_id)->first();
                if(!empty($mls_property_fav_data)){
                    $property_lists[$key]['fav_property'] = true;
                } 
                         
            }
        }
        if(isset($methodType) && $methodType=='ajax'){
            $html='<div class="row equal_height" >';
            $i = 1;
            foreach($property_lists as $property_list){
                                    
                $string = array(', ',',', ' ', '/', "'");
                $replace   = array('-', '-', '-', '-', '-');
                $address = str_replace($string, $replace, $property_list['address']);
                $address = str_replace('--', '-', $address);
                $url  = $slug.'/property-detail/'.$address.'-'. $property_list['id'];
                                        
                
                if($property_display_row==3){ $col='col-lg-4';}elseif($property_display_row==2){$col='col-lg-6';}else{$col='col-md-12';}
                if($property_list['agency_id']!=97){
                    $imageUrl=url('public/uploads/properties_images/'.$property_list['id'].'/'.$property_list['main_image']);
                }else{
                    $imageUrl=url('public/uploads/properties_images/16/159767026246e6cfde-fc64-4dc3-a4f0-dfe0216c849c.jpg');
                }
                $html.='<div class="'.$col.' equal_height_container">
                            <div class="card-home" id="">
                                <a href="'.url($url).'" class="property_link">
                                    <div class="item" style="background-image: url('.$imageUrl.')"></div>
                                </a>';            
                            if(Auth::check() == true){
                            $html.='<div class="middle-info fav_propery_box';if($property_list['fav_property'] == true) { $html.=' active';} $html.='">
                                <a href="#" class="fav-property favUnfavitem" onclick="favProperty(this);" id="'. $i .'"  property_id = "'. $property_list['id'] .'" ListingId= "'. $property_list['ListingId'] .'"><i class="fas fa-heart"></i></a>
                            </div>';
                            }
                                                   
                            $html.='<div class="bottom-info">
                                <div class="middle-info price">$'.$property_list['price'].'</div>
                                <p class="property_name"><a href="#">'.$property_list['address'].'</a></p>';
                                if(!empty($property_list['beds'])){
                                    $html.='<span><i class="fas fa-bed"></i>
                                    '.$property_list['beds'].'BD</span>';
                                }
                                if(!empty($property_list['baths'])){
                                    $html.='<span>
                                    <i class="fas fa-bookmark"></i>'.$property_list['baths'].'BA</span>';
                                }
                                if(!empty($property_list['sq_feet'])){
                                    $html.='<span>
                                    <i class="fas fa-cube"></i>'.$property_list['sq_feet'].'SF</span>';
                                }
                            $html.='</div>
                        </div> 
                    </div>';
                                        
                $i++;
            }
            $html.='</div>'.$property_lists->links();
            $data['html']=$html;
            if(count($property_lists)>0){
                $data['is_empty']=1;
            }else{
                $data['is_empty']=0;
            }
            
            return json_encode($data);
            
        }else{
            return view('frontend.property.list', compact('property_lists','all_property_lists','slug'));
        }
        
        //echo count($all_property_lists);exit;
        //return view('frontend.property.list', compact('property_lists','all_property_lists','slug'));
    }
    public function propertyDetails(Request $request ) {
        $uri = $request->path();
        $uri_array = explode('-', $uri);
        $property_id = end($uri_array);
        $agency_id=Session::get('agency_id');
        $slug = Session::get('slug');
        //echo "<pre>"; print_r($list_id); exit;
        /*$mls_property_fav_data = '';
        if(Auth::check() == true) {
            $user_id = Auth::user()->id;
            $mls_property_fav_data = PropertyModel::where('user_id', $user_id)->where('ListingId', $list_id)->first();
        }*/
        
        $keywords = "home_";
        $property_data = PropertyModel::where('id', $property_id)->where('agency_id',$agency_id)->where('status',1)->first();
        if(empty($property_data)){
            return redirect('/'.$slug.'/properties');
        }
        if($property_data->type=='2' || $property_data->type=='3'){
            $property_data['property_units'] = PropertyUnit::where('property_id', $property_id)->get()->toArray();
        }else{
            $property_data['property_units'] ='';
        }   
        $property_image_data = PropertyOtherImages::where('property_id', $property_id)->orderBy('id','ASC')->get()->toArray();
        $property_video_data = PropertyVideo::where('property_id', $property_id)->orderBy('id','ASC')->get()->toArray();
        if(Auth::user()){
            $property_fav_data=FavProperty::where('property_id',$property_id)->where('user_id',Auth::user()->id)->count();
        }else{
            $property_fav_data='';
        }
        
        /*echo "<pre>"; print_r($property_image_data);
        echo "<pre>"; print_r($property_video_data); exit;*/
        return view('frontend.property.detail', compact('property_data', 'property_image_data','property_fav_data','property_video_data','slug'));
    }
    public function getMapMarker(Request $request) {
        
        $agency_id = Session::get('agency_id');
        $slug = Session::get('slug');
        extract($_POST);
        if(isset($address) && !empty($address)){
            //echo $address;
            //exit;
            $property_lists=PropertyModel::where('agency_id',$agency_id)->where('status',1)->where(function($query) use ($address){
                $query->where('address', 'LIKE', "%{$address}%");
                $query->orWhere('city', 'LIKE', "%{$address}%");
                $query->orWhere('state', 'LIKE', "%{$address}%");
                $query->orWhere('country', 'LIKE', "%{$address}%");
            })->groupBy('id');
        }else{
            $property_lists=PropertyModel::where('agency_id',$agency_id)->orderBy('id', 'DESC');
        }
        if(isset($min_price) && !empty($min_price) && isset($max_price) && !empty($max_price)){
        	//echo "string";exit;
            $property_lists= $property_lists->where('price', '>=', $min_price)->where('price', '<=', $max_price);
        }else{
            if(isset($min_price) && !empty($min_price)){
                $property_lists= $property_lists->where('price', $min_price);
            }
            if(isset($max_price) && !empty($max_price)){
                $property_lists= $property_lists->where('price', $max_price);
            }
        }
        if(isset($min_sq) && !empty($min_sq) && isset($max_sq) && !empty($max_sq)){
            $property_lists= $property_lists->where('sq_feet', '>=', $min_sq)->where('sq_feet', '<=', $max_sq);
        }else{
            if(isset($min_sq) && !empty($min_sq)){
                $property_lists= $property_lists->where('sq_feet', $min_sq);
            }
            if(isset($max_sq) && !empty($max_sq)){
                $property_lists= $property_lists->where('sq_feet', $max_sq);
            }
        }
        if(isset($beds) && $beds!=''){
            if($beds==3){
                $property_lists= $property_lists->where('beds','>=', $beds);
            }else{
                $property_lists= $property_lists->where('beds',$beds);
            }
            
        }
        if(isset($baths) && $baths!=''){
            if($baths==3){
                $property_lists= $property_lists->where('baths','>=', $baths);
            }else{
                $property_lists= $property_lists->where('baths', $baths);
            }
            
        }
        if(isset($property_type) && !empty($property_type)){
            $property_lists= $property_lists->where('type', $property_type);
        }
        if(isset($purpose) && !empty($purpose)){
            $property_lists= $property_lists->where('purpose', $purpose);
        }
        $property_lists= $property_lists->where('status', 1);
        $all_property_lists=$property_lists->get()->toArray();
        $i=0;
        foreach ($all_property_lists as $value) {
            $string = array(', ',',', ' ', '/', "'");
            $replace   = array('-', '-', '-', '-', '-');
            $address = str_replace($string, $replace, $value['address']);
            $address = str_replace('--', '-', $address);
            $url  = url('').'/'.$slug.'/property-detail/'.$address.'-'. $value['id'];
            $all_property_lists[$i]['url']=$url;
            if(empty($value['baths'])){
                $value['baths']='';
            }
            if(empty($value['beds'])){
                $value['beds']='';
            }
            $i++;
        }
        //echo "<pre>"; print_r($all_property_lists);exit;
        return json_encode($all_property_lists);
    }
    public function propertyContactForm(Request $request) {
        $response = array();
        $response['status'] = false;
        $response['message'] = 'Something Went Wrong Please Try Again.';

        $result = $request->all();
        /*echo "<pre>"; print_r($result); exit;*/
        $property_customer_name = $result['property_name'];
        $property_email = $result['property_email'];
        $property_number = $result['property_number'];
        $property_address = $result['property_address'];
        $property_id = $result['property_id'];
        $property_message = $result['property_message'];
        if($property_customer_name!='' && $property_email!='' && $property_number!='' && $property_address!='' && $property_id!='') {
            
            $property_details = PropertyModel::where('id',$property_id)->first();
            if(!empty(Auth::user()->id)){
                if(Auth::user()->id==$property_details->agency_id || Auth::user()->id==$property_details->agent_id){
                    $response['status'] = false;
                    $response['message'] = 'You can not contact on your own property.';
                    return $response;
                }
            }
            $setting=getGeneralSettings($property_details->agency_id);
            if(!empty($setting['lead_email'])){
                $lead_email=$setting['lead_email'];
            }else{
                $lead_email='';
            }
            /*echo $lead_email;
            echo "<pre>"; print_r($setting); exit;*/
            $propertyLids = PropertyLeadsModel::create([
                'agency_id' => $property_details->agency_id,
                'agent_id' => $property_details->agent_id,
                'property_id' => $property_details->id,
                'address' => $property_details->address,
                'customer_name' => $property_customer_name,
                'email' => $property_email,
                'phone' => $property_number,
                'message' => $property_message,
            ]);
            $emailContent = EmailMasterModel::where('id', 23)->first();
            $emailContentAgent = EmailMasterModel::where('id', 24)->first();
            $agency_details = UserModel::where('id',$property_details->agency_id)->with(['agency'])->first();
            $agent_details = UserModel::where('id',$property_details->agent_id)->first();
            if(!empty($emailContent)){
                $emailContent->content = str_replace('{{USERNAME}}',  $agency_details->agency->agency_name, $emailContent->content);
                $emailContent->content = str_replace('{{PROPERTYID}}', $property_id, $emailContent->content);
                $emailContent->content = str_replace('{{PROPERTYADDRESS}}', $property_address, $emailContent->content);
                $emailContent->content = str_replace('{{NAME}}', $property_customer_name, $emailContent->content);
                $emailContent->content = str_replace('{{EMAIL}}', $property_email,  $emailContent->content);
                $emailContent->content = str_replace('{{PHONE}}',$property_number, $emailContent->content);
                $emailContent->content = str_replace('{{MESSAGE}}', $property_message, $emailContent->content);
                
                $subject = $emailContent->subject;
                $messageContent = $emailContent->content;
                $emailContent->content = str_replace('{{USERNAME}}',  $agency_details->agency->agency_name, $emailContent->content);
                $student_log = EmailSmsLog::create([
                    'user_id' => $property_details->agency_id,
                    'email_id'=>$lead_email,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);
                $emailContentAgent->content = str_replace('{{USERNAME}}',  $agent_details->first_name.' '.$agent_details->last_name, $emailContentAgent->content);
                $emailContentAgent->content = str_replace('{{PROPERTYID}}', $property_id, $emailContentAgent->content);
                $emailContentAgent->content = str_replace('{{PROPERTYADDRESS}}', $property_address, $emailContentAgent->content);
                $emailContentAgent->content = str_replace('{{NAME}}', $property_customer_name, $emailContentAgent->content);
                $emailContentAgent->content = str_replace('{{EMAIL}}', $property_email,  $emailContentAgent->content);
                $emailContentAgent->content = str_replace('{{PHONE}}',$property_number, $emailContentAgent->content);
                $emailContentAgent->content = str_replace('{{MESSAGE}}', $property_message, $emailContentAgent->content);
                
                $subject = $emailContentAgent->subject;
                $messageContent = $emailContentAgent->content;
                $student_log = EmailSmsLog::create([
                    'user_id' => $property_details->agent_id,
                    'subject' => $subject,
                    'email_content' => $messageContent,
                    'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS
                ]);

            }
          $notification=array('from_user_id'=>1,'to_user_id'=>$property_details->agency_id,'link'=>'/leads','active_flag'=>0,'notification_desc'=>'You have a new lead from property contact form.'); 
          NotificationMaster::insert($notification);
          $notification=array('from_user_id'=>1,'to_user_id'=>$property_details->agent_id,'link'=>'/leads','active_flag'=>0,'notification_desc'=>'You have a new lead from property contact form.'); 
          NotificationMaster::insert($notification);
            $response['status'] = true;
            $response['message'] = 'Thank you contacting us we will get back to you in 24-48 hours.';

            return $response;
        }
        return $response;
    }
    public function favunfavProperty(Request $request) {
        $response = array();
        $response['message'] = 'Something went to wrong';
        $response['status'] = false;
        $response['css'] = false;

        $result = $request->all();
        if(Auth::user()->user_type=='0' || Auth::user()->user_type=='1' || Auth::user()->user_type=='2'){
            $response['message'] = 'You can not mark your own property as favourite.';
            $response['status'] = false;
            $response['css'] = false;

            return $response;  
        }
        if($result['property_id']!='') {
            $user_id = Auth::user()->id;
            $user=Auth::user();
            $property_id = $result['property_id'];
            $relationData=AgencyRelationModel::where('user_id',$user_id)->first();
            $propertData=PropertyModel::where('id',$result['property_id'])->first();
            if($relationData->agency_id!=$propertData->agency_id){
                $response['message'] = 'Something went to wrong';
                $response['status'] = false;
                $response['css'] = false;
                return $response;
            }

            $fav_unfav_data = FavProperty::where('user_id', $user_id)->where('property_id', $property_id)->first();

            if(empty($fav_unfav_data)) {
                FavProperty::insert([
                    'user_id' => $user_id,
                    'property_id' => $property_id,
                ]);
                $notification=array('from_user_id'=>$user->id,'to_user_id'=>$propertData->agency_id,'link'=>'/customer/view/fav/'.$user_id,'active_flag'=>0,'notification_desc'=>$user->first_name.' '.$user->last_name.' has marked your property as favourite.');
                sendNotification($notification);
                $notification=array('from_user_id'=>$user->id,'to_user_id'=>$propertData->agent_id,'link'=>'/property/property-list','active_flag'=>0,'notification_desc'=>$user->first_name.' '.$user->last_name.' has marked your property as favourite.');
                //echo "<pre>"; print_r($notification); exit;
                sendNotification($notification);
                $response['message'] = 'Property is favorite successfully';
                $response['status'] = true;
                $response['css'] = true;

                return $response;

            }
            $notification=array('from_user_id'=>$user->id,'to_user_id'=>$propertData->agency_id,'link'=>'/customer/view/fav/'.$user_id,'active_flag'=>0,'notification_desc'=>$user->first_name.' '.$user->last_name.' has marked your property as unfavourite.');
            sendNotification($notification);
            $notification=array('from_user_id'=>$user->id,'to_user_id'=>$propertData->agent_id,'link'=>'/property/property-list','active_flag'=>0,'notification_desc'=>$user->first_name.' '.$user->last_name.' has marked your property as unfavourite.');
            sendNotification($notification);
            FavProperty::where('user_id', $user_id)->where('property_id', $property_id)->delete();

            $response['message'] = 'Property is unfavorite successfully.';
            $response['status'] = true;
            $response['css'] = false;

            return $response;            
        }

        return $response;
    }
    public function favoritePropertyList(Request $request) {
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        $property_lists=FavProperty::select('p.*')->leftjoin('properties as p','p.id','=','property_fav_unfav.property_id')->where('p.agency_id',$agency_id)->where('property_fav_unfav.user_id',Auth::user()->id);
        $property_lists= $property_lists->where('p.status', 1);
        $all_property_lists=$property_lists->get()->count();
        $property_lists=$property_lists->paginate(9);
        return view('frontend.property.favorite_property_list',compact('property_lists','all_property_lists','slug','agency_id'));
    }
    public function fav_properties_search(Request $request) {
        //echo "<pre>"; print_r($_GET); exit;
        /*$current_uri = request()->segments();
        echo "<pre>"; print_r($current_uri); exit;*/
        extract($_GET);
        $slug=Session::get('slug');
        $agency_id=Session::get('agency_id');
        $property_lists=FavProperty::select('p.*')->leftjoin('properties as p','p.id','=','property_fav_unfav.property_id')->where('p.agency_id',$agency_id)->where('property_fav_unfav.user_id',Auth::user()->id);
        $property_lists= $property_lists->where('p.status', 1);
        $all_property_lists=$property_lists->get()->count();
        $property_lists=$property_lists->paginate(9);
        foreach($property_lists as $key=>$value) {
            if(Auth::check() == true) {
                $user_id = Auth::user()->id;
                $property_lists[$key]['fav_property'] = false;
                $mls_property_fav_data = FavProperty::where('property_id', $value['id'])->where('user_id', $user_id)->first();
                if(!empty($mls_property_fav_data)){
                    $property_lists[$key]['fav_property'] = true;
                } 
                         
            }
        }
        if(isset($methodType) && $methodType=='ajax'){
            $html='<div class="row equal_height" >';
            $i = 1;
            foreach($property_lists as $property_list){
 
                $string = array(', ',',', ' ', '/', "'");
                $replace   = array('-', '-', '-', '-', '-');
                $address = str_replace($string, $replace, $property_list['address']);
                $address = str_replace('--', '-', $address);
                $url  = $slug.'/property-detail/'.$address.'-'. $property_list['id'];  
                            
                if($property_list['agency_id']!=97){
                    $imageUrl=url('public/uploads/properties_images/'.$property_list['id'].'/'.$property_list['main_image']);
                }else{
                    $imageUrl=url('public/uploads/properties_images/16/159767026246e6cfde-fc64-4dc3-a4f0-dfe0216c849c.jpg');
                }
                $html.='<div class="col-md-4 col-sm-6 equal_height_container" id="list_view_data">
                            <div class="card-home" id="">
                                <a href="'.url($url).'" class="property_link">
                                    <div class="item" style="background-image: url('.$imageUrl.')"></div>
                                </a>';            
                            if(Auth::check() == true){
                            $html.='<div class="middle-info fav_propery_box';if($property_list['fav_property'] == true) { $html.=' active';} $html.='">
                                <a href="#" class="fav-property favUnfavitem" onclick="favProperty(this);" id="'. $i .'"  property_id = "'. $property_list['id'] .'" ListingId= "'. $property_list['ListingId'] .'"><i class="fas fa-heart"></i></a>
                            </div>';
                            }
                                                   
                            $html.='<div class="bottom-info">
                                <div class="middle-info price">$'.$property_list['price'].'</div>
                                <p class="property_name"><a href="#">'.$property_list['address'].'</a></p>
                                <span><i class="fas fa-bed"></i>
                                    '.$property_list['beds'].'BD</span>
                                <span>
                                    <i class="fas fa-bookmark"></i>'.$property_list['baths'].'BA</span>
                                <span>
                                    <i class="fas fa-cube"></i>'.$property_list['sq_feet'].'SF</span>
                            </div>
                        </div> 
                    </div>';
                                        
                $i++;             
                
            }
            $html.='</div><div id="property_list_data">'.$property_lists->links().'</div>';
            $data['html']=$html;
            if(count($property_lists)>0){
                $data['is_empty']=1;
            }else{
                $data['is_empty']=0;
            }
            
            return json_encode($data);
            
        }else{
            return view('frontend.property.favorite_property_list', compact('property_lists','all_property_lists','slug'));
        }
        
        //echo count($all_property_lists);exit;
        //return view('frontend.property.list', compact('property_lists','all_property_lists','slug'));
    }
    public function getFavMapMarker(Request $request) {
        
        $agency_id = Session::get('agency_id');
        $slug = Session::get('slug');
        extract($_POST);
        $property_lists=FavProperty::select('p.*')->leftjoin('properties as p','p.id','=','property_fav_unfav.property_id')->where('p.agency_id',$agency_id)->where('property_fav_unfav.user_id',Auth::user()->id);
        $property_lists= $property_lists->where('p.status', 1);
        $all_property_lists=$property_lists->get()->toArray();
        $i=0;
        foreach ($all_property_lists as $value) {
            $string = array(', ',',', ' ', '/', "'");
            $replace   = array('-', '-', '-', '-', '-');
            $address = str_replace($string, $replace, $value['address']);
            $address = str_replace('--', '-', $address);
            $url  = url('').'/'.$slug.'/property-detail/'.$address.'-'. $value['id'];
            $all_property_lists[$i]['url']=$url;
            $i++;
            if(empty($value['baths'])){
                $value['baths']='';
            }
            if(empty($value['beds'])){
                $value['beds']='';
            }
        }
        //echo "<pre>"; print_r($all_property_lists);exit;
        return json_encode($all_property_lists);
    }
    function fetchAddress(Request $request)
    {
        $agency_id=Session::get('agency_id');
        if($request->get('query'))
        {
            $address = $request->get('query');
            $addresses = PropertyModel::select('address')->where(function($query) use ($address){
                $query->where('address', 'LIKE', "%{$address}%");
                $query->orWhere('city', 'LIKE', "%{$address}%");
                $query->orWhere('state', 'LIKE', "%{$address}%");
                $query->orWhere('country', 'LIKE', "%{$address}%");
            })->where('agency_id',$agency_id)->groupBy('id')->get();
            $citys = PropertyModel::select('city')->where(function($query) use ($address){
                $query->where('address', 'LIKE', "%{$address}%");
                $query->orWhere('city', 'LIKE', "%{$address}%");
                $query->orWhere('state', 'LIKE', "%{$address}%");
                $query->orWhere('country', 'LIKE', "%{$address}%");
            })->where('agency_id',$agency_id)->groupBy('city')->get();
            $states = PropertyModel::select('state')->where(function($query) use ($address){
                $query->where('address', 'LIKE', "%{$address}%");
                $query->orWhere('city', 'LIKE', "%{$address}%");
                $query->orWhere('state', 'LIKE', "%{$address}%");
                $query->orWhere('country', 'LIKE', "%{$address}%");
            })->where('agency_id',$agency_id)->groupBy('state')->get();
            $countrys = PropertyModel::select('country')->where(function($query) use ($address){
                $query->where('address', 'LIKE', "%{$address}%");
                $query->orWhere('city', 'LIKE', "%{$address}%");
                $query->orWhere('state', 'LIKE', "%{$address}%");
                $query->orWhere('country', 'LIKE', "%{$address}%");
            })->where('agency_id',$agency_id)->groupBy('country')->get();
            $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
            foreach($addresses as $address)
            {
                $output .= '
                <li><a href="#">'.$address->address.'</a></li>
                ';
            }
            foreach($citys as $city)
            {
                $output .= '
                <li><a href="#">'.$city->city.'</a></li>
                ';
            }
            foreach($states as $state)
            {
                $output .= '
                <li><a href="#">'.$state->state.'</a></li>
                ';
            }
            /*foreach($countrys as $country)
            {
                $output .= '
                <li><a href="#">'.$country->country.'</a></li>
                ';
            }*/
            $output .= '</ul>';
            echo $output;
        }
    }
    public function get_lat_long($address){
        $data = \App\Models\SettingModel::where('user_id',1)->get()->toArray();
        $singleData = array_column($data,'text_value','text_key');
        $prepAddr = str_replace(' ','+',$address);
        $apiKey =$singleData['google_map_api_key']; // Google maps now requires an API key.

        $geocode=file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&libraries=places&sensor=false&key='.$apiKey);

        // We convert the JSON to an array
        $output= json_decode($geocode);
        //echo "<pre>"; print_r($output); exit;
        $lat = '';
        $long = '';
        $city = '';
        $state = '';
        $country = '';
        $zipcode = '';
        if(!empty($output->results[0])){
            foreach ($output->results[0]->address_components as $value) {
                if($value->types[0]=='locality'){
                    $city=$value->long_name;
                }
                if($value->types[0]=='administrative_area_level_1'){
                    $state=$value->short_name;
                }
                if($value->types[0]=='country'){
                    $country=$value->short_name;
                }
                if($value->types[0]=='postal_code'){
                    $zipcode=$value->long_name;
                }
                
            }
            $lat = $output->results[0]->geometry->location->lat;
            $long = $output->results[0]->geometry->location->lng;
        }else{
            $lat = '';
            $long = '';
            $city = '';
            $state = '';
            $country = '';
            $zipcode = '';
        }
        

        return array('lat'=>$lat,'long'=>$long,'city'=>$city,'state'=>$state,'country'=>$country,'zipcode'=>$zipcode);
    }
}