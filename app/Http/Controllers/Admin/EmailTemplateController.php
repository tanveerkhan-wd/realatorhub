<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailMasterModel;
use Yajra\DataTables\DataTables;
use Validator;
class EmailTemplateController extends Controller
{
    public function index(){
        return view('admin.email-template.index');
    }
    public function emailAjax(Request $request)
    {
    	 /**
         * Used for master Blog List AJAX
         * @return redirect to Admin->Masters->Blog
         */
    	$input = $request->all();
        $users = EmailMasterModel::select('*')->orderby('id','asc');

        
        if(isset($input['title']) && !empty($input['title'])){
            $users = $users->where('title','LIKE','%'.$input['title'].'%');            
        }
        $table = Datatables::of($users)->make(true);
        return $table;
    }
    public function editemail(Request $request,$id)
    {
    	$input = $request->all();
    	 $data = EmailMasterModel::findorfail($id);
        return view('admin.email-template.edit',compact('data'));
    }

    public function editemailPost(Request $request,$id)
    {
    	/**
         * Used for master Blog Edit Save
         * @return redirect to Admin->Masters->Blog
         */
        $input = $request->all();        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'subject' => 'required',
            'content'=>'required'
        ]);
        
        if ($validator->fails()) {            
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $email = EmailMasterModel::findorfail($id);
        $paramcheck = explode(',',$email->parameters);
        if(!empty($email->parameters)){
            foreach ($paramcheck as $key => $value) 
            {
               $string = '{{'.$value.'}}';
               $pos = strpos($input['content'], $string);
               if ($pos !== false) 
               {

               }
               else
               {          
                return redirect()->back()
                            ->withErrors([$value.' Not Found in Content'])
                            ->withInput();
               }
            }
        }
        
        $email->title = $input['title'];
        $email->subject = $input['subject'];
        $email->content = $input['content'];
        if($email->save()){
            return redirect('admin/email-template')->with('success','Record Updated Successfully');
        }else{
            return redirect('admin/email-template')->withErrors(['Something Went Wrong Please Try Again.']);
        }
    }
}
