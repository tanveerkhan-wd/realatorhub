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
use Validator;
use Route;
use App\User;
use Session;
use Auth;

use DB;

class CMSController extends Controller
{
    public function aboutus(Request $request) {
        $agency_id=Session::get('agency_id');
        $data = \App\Models\SettingModel::where('user_id',$agency_id)->get()->toArray();
        $singleData = array_column($data,'text_value','text_key');
        //echo "<pre>"; print_r($singleData); exit;
        return view('frontend.cms.aboutus',compact('singleData'));
    }
}