<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationMaster;
use Yajra\DataTables\DataTables;
use Validator;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
    }
    public function index(){
        return view('agency.notification.index');
    }
    public function notificationAjax(Request $request)
    {

    	 /**
         * Used for master Blog List AJAX
         * @return redirect to Admin->Masters->Blog
         */
    	$input = $request->all();
        $users = NotificationMaster::select('*')->where('to_user_id',Auth::user()->id)->orderby('n_id','desc');

        
        /*if(isset($input['title']) && !empty($input['title'])){
            $users = $users->where('title','LIKE','%'.$input['title'].'%');            
        }*/
        $table = Datatables::of($users)->make(true);
        return $table;
    }
    public function deleteNotification(Request $request,$id)
    {
    	$input = $request->all();
    	 $data = NotificationMaster::where('n_id',$id)->delete();
       return redirect('agency/notifications')->with('success','Notification deleted Successfully');
    }

    public function readnotification(Request $request)
    {
        $input = $request->all();
        NotificationMaster::where('to_user_id','=',Auth::user()->id)->update(['active_flag'=>'1']);
        return response()->json(['message' =>"Readed",'code' => 200]);
    }
}
