<?php

namespace App\Http\Controllers\Admin;

use App\Models\SettingModel;
use App\Models\SubCategoryMaster;
use App\Models\FAQModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\DB;
use function App\Helpers\apiErrorResponse;
use function App\Helpers\apiSuccessResponse;

class FaqController extends Controller
{
    public function index(Request $request) {
        $keywords = "faq_";
        $data = SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray(); 
        $singleData = array_column($data,'text_value','text_key');
        
        if (request()->ajax()) {  
            return \View::make('admin.setting.faq.genral_setting', compact('singleData'))->renderSections();
        }
        
        return view('admin.setting.faq.genral_setting', compact('singleData'));
    }
    
    public function addGenralSetting(Request $request) {
        $message = 'Something went to wrong';
        $validator = Validator::make($request->all(), [
            'faq_title' => 'required',
            'faq_description' => 'required',
        ]);

        if ($validator->fails()) {       
            return apiErrorResponse($validator->errors()->first());     
        }

        $result = $request->all();

        if(count($result) > 0) {
            $data = array(
                'faq_title' => $result['faq_title'],
                'faq_description' => $result['faq_description']
            );

            foreach($data as $key=>$value) {
                SettingModel::where('text_key', $key)->update([
                    'text_value' => $value,
                ]);
            }

            $message = 'Faq genral setting details updated successfully.';
            $data = array();
            return apiSuccessResponse($data, $message);
        }

        return apiErrorResponse($message);
    }

      public function metaSection(Request $request) {
        $keywords = "faq_";
        $data = SettingModel::where('text_key','LIKE','%'.$keywords.'%')->get()->toArray(); 
        $singleData = array_column($data,'text_value','text_key');
        
        if (request()->ajax()) {  
            return \View::make('admin.setting.faq.meta_section', compact('singleData'))->renderSections();
        }
        
        return view('admin.setting.faq.meta_section', compact('singleData'));
    }

      


    public function addMetaSection(Request $request) {
        $message = 'Something went to wrong';
        $validator = Validator::make($request->all(), [
            'faq_meta_title' => 'required',
            'faq_meta_description' => 'required',
        ]);

        if ($validator->fails()) {       
            return apiErrorResponse($validator->errors()->first());     
        }

        $result = $request->all();

        if(count($result) > 0) {
            $data = array(
                'faq_meta_title' => $result['faq_meta_title'],
                'faq_meta_description' => $result['faq_meta_description']
            );

            foreach($data as $key=>$value) {
                SettingModel::where('text_key', $key)->update([
                    'text_value' => $value,
                ]);
            }

            $message = 'FAQs page Meta Section details updated successfully.';
            $data = array();
            return apiSuccessResponse($data, $message);
        }

        return apiErrorResponse($message);
    }

    public function list(Request $request) {
        if (request()->ajax()) {
            return \View::make('admin.faq.list')->renderSections();
        }
        return view('admin.faq.list');
    }

    public function ajax(Request $request) {
        $result = $request->all();
        $faq_data =  DB::table('faqs');
        if($result['title'] != '') {
            $faq_data = $faq_data->where(function ($user) use ($result) {
                $user->where('title', 'like', '%'.$result['title'].'%')
                ->orwhere('description', 'like', '%'.$result['title'].'%');
            });
        }

        if($result['status'] != '') {
            $faq_data = $faq_data->where('status',  $result['status']);
        }

        if($result['order'][0]['column'] == 0){
            $faq_data = $faq_data->orderby('id','desc');
        }
        
        $table = \Datatables::of($faq_data)->make(true);
        return $table;
    }

    public function add(Request $request) {
        $message = 'Something went to wrong.';
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {       
            return apiErrorResponse($validator->errors()->first());     
        }
       
        $result = $request->all();
        if(count($result) > 0) {
            $login_user_id = Auth::user()->id;
            $question = $result['question'];
            $answer = $result['answer'];
            $status = 'Active';
            $created_date = date('Y-m-d H:i:s');
            $created_by = $login_user_id;
            $updated_date = date('Y-m-d H:i:s');
            $updated_by = $login_user_id;

            $language_add = FAQModel::insert([
                'title' => $question,
                'description' => $answer,
                'status' => $status,
                'created_date' => $created_date,
                'created_by' => $created_by,
                'updated_date' => $updated_date,
                'updated_by' => $updated_by,
            ]);

            if($language_add) {
                $data = array();
                $message = 'Faq added successfully';
                return  apiSuccessResponse($data, $message);
            }
        }

        return apiErrorResponse($message);
    }

    public function edit(Request $request) {
        $message = 'Something went to wrong.';
        $result = $request->all();

        if(count($result) > 0) {
            $id = $result['id'];
          
            $faq_data = FAQModel::where('id', $id)->get()->toArray();

            if(count($faq_data) > 0) {
                $data = $faq_data;
                $message = 'Date retrived successfully';
                return  apiSuccessResponse($data, $message);                
            }
        }

        return apiErrorResponse($message);
    }

    public function update(Request $request) {
        $message = 'Something went to wrong.';
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {       
            return apiErrorResponse($validator->errors()->first());     
        }

        $result = $request->all();

        if(count($result) > 0) {
            $login_user_id = Auth::user()->id;
            $id = $result['faq_id'];
            $question = $result['question'];
            $answer = $result['answer'];
            $updated_date = date('Y-m-d H:i:s');
            $updated_by = $login_user_id;

            $faq_data = FAQModel::find($id);

            if($faq_data != NULL) {
                $faq_data->title = $question;
                $faq_data->description = $answer;
                $faq_data->updated_date = $updated_date;
                $faq_data->updated_by = $updated_by;
                
                if($faq_data->save()) {
                    $data = array();
                    $message = 'Faq updated successfully';
                    return  apiSuccessResponse($data, $message);
                }
            }
        }

        return apiErrorResponse($message);
    }

    public function delete(Request $request, $id) {
        $message = 'Something went to wrong.';
        $faq_data = FAQModel::find($id);

        if($faq_data != NULL) {
            $faq_data->delete();           
            $data = array();
            $message = 'Faq deleted successfully';

            return apiSuccessResponse($data, $message);
        }
        
        return apiErrorResponse($message);
    }

    public function statusUpdate(Request $request) {
        $message = 'Something went to wrong.';
        $result = $request->all();

        if(count($result) > 0) {
            $login_user_id = Auth::user()->id;
            $id = $result['id'];
            $status = $result['status'];
            $updated_date = date('Y-m-d H:i:s');
            $updated_by = $login_user_id;

            $language_data = FAQModel::find($id);

            if($language_data != NULL) {
                $language_data->status = $status;
                $language_data->updated_date = $updated_date;
                $language_data->updated_by = $updated_by;

                if($language_data->save()) {
                    $data = array();
                    $message = 'Status change successfuly.';
                    return  apiSuccessResponse($data, $message);
                }
            }
        }

        return apiErrorResponse($message);
    }

}