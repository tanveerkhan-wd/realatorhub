<?php

namespace App\Http\Controllers\Admin;

use App\Models\CMSModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

use function App\Helpers\apiErrorResponse;
use function App\Helpers\apiSuccessResponse;

class CmsController extends Controller
{
    public function list(Request $request) {
        if (request()->ajax()) {
            return \View::make('admin.cms.list')->renderSections();
        }
        return view('admin.cms.list');
    }

    public function ajax(Request $request) {
        $cms_Data = CMSModel::where('status', '1');
        $result = $request->all();
        if(!empty($result['title']) && $result['title'] != '') {
            $cms_Data = $cms_Data->where(function ($user) use ($result) {
                $user->where('title', 'like', '%'.$result['title'].'%')
                ->orwhere('description', 'like', '%'.$result['title'].'%');
            });
        }

        if($result['order'][0]['column'] == 1){
            $cms_Data = $cms_Data->orderBy('title', $result['order'][0]['dir']);   
        } elseif($result['order'][0]['column'] == 2){
            $cms_Data = $cms_Data->orderBy('description', $result['order'][0]['dir']);   
        } else {
            $cms_Data = $cms_Data->orderBy('id','desc'); 
        }
 
        $table = \Datatables::of($cms_Data)->make(true);
        return $table;
    }


    public function edit(Request $request, $id) {
        $cms_data = CMSModel::find($id);

        if (request()->ajax()) {  
            return \View::make('admin.cms.edit', compact('cms_data'))->renderSections();
        }
        
        return view('admin.cms.edit', compact('cms_data'));
    }

    public function update(Request $request) {
        $message = 'Something went to wrong';
        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {       
            return apiErrorResponse($validator->errors()->first());     
        }

        $result = $request->all();

        if(count($result) > 0) {
            $login_user_id = Auth::user()->id;
            $id = $result['id'];
            $title = $result['title'];
            $description = $result['description'];
            $updated_date = date('Y-m-d H:i:s');
            $updated_by = $login_user_id;
           
            $cms_data = CMSModel::find($id);

            if($cms_data != NULL) {
                $cms_data->title = $title;
                $cms_data->description = $description;
                $cms_data->updated_date = $updated_date;
                $cms_data->updated_by = $updated_by;

                if($cms_data->save()) {
                    $message = 'cms updated successfully.';
                    $data = array();
                    return apiSuccessResponse($data, $message);
                }    
           }
        }

        return apiErrorResponse($message);
    }

}
