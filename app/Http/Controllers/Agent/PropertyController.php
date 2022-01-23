<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Session;
use App\Models\PropertyModel;
use Validator;
use Yajra\DataTables\DataTables;
use App\Models\PropertyUnit;
use App\Models\PropertyOtherImages;
use Auth;
use App\Models\NotificationMaster;
use App\Models\PropertyVideo;
use App\Models\AgencyRelationModel;
class PropertyController extends Controller {

    public function index() {
        $user_id = Session::get('user_id');
        //$users = UserModel::select('users.*', 'agent.agent_unique_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')->where('agency_relation.user_type', 1)->where('agency_relation.agency_id', $user_id)->orderby('users.id', 'desc')->get();
        return view('agent.properties.list');
    }

    public function datatableList(Request $request) {
        $input = $request->all();
        $all_property = PropertyModel::leftJoin('users', 'properties.agent_id', '=', 'users.id')->leftJoin('agency', 'properties.agency_id', '=', 'agency.user_id')->where("is_delete", "=", "0")->where('agent_id', '=', Session::get('user_id'))
                ->select('properties.id', 'properties.purpose', 'properties.type', 'properties.address', 'properties.is_delete', 'properties.status', 'properties.created_at','users.first_name', 'users.email', 'users.last_name','agency.slug')
                ->orderby('properties.id','desc');
        if (isset($input['agent']) && !empty($input['agent'])) {
            $all_property = $all_property->where(function ($all_property) use ($input) {

                $all_property->where('users.email', 'LIKE', '%' . $input['agent'] . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $input['agent'] . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $input['agent'] . '%');
            });
        }
         if (isset($input['property_all_search']) && $input['property_all_search'] != '') {
           $all_property = $all_property->where(function ($all_property) use ($input) {

                $all_property->where('properties.address', 'LIKE', '%' . $input['property_all_search'] . '%')
                        ->orWhere('properties.id', 'LIKE', '%' . $input['property_all_search'] . '%')
                      ;
            });
        }
        if (isset($input['property_type']) && $input['property_type'] != '') {
            $all_property = $all_property->where('properties.type', '=', $input['property_type']);
        }
        if (isset($input['property_purpose']) && $input['property_purpose'] != '') {
            $all_property = $all_property->where('properties.purpose', '=', $input['property_purpose']);
        }
        if (isset($input['property_status']) && $input['property_status'] != '') {
            $all_property = $all_property->where('properties.status', '=', $input['property_status']);
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
                                    $pt_type = "Single Homes";
                                    break;
                                case "2":
                                    $pt_type = "Multifamily";
                                    break;
                                case "3":
                                    $pt_type = "Commercial";
                                    break;
                                case "4":
                                    $pt_type = "Industrial";
                                    break;
                                case "5":
                                    $pt_type = "Lot";
                                    break;
                                default:
                                    $pt_type = '';
                            }
                            return $pt_type;
                        })
                        ->editColumn('status', function ($all_property) {
                            $status = '';
                            if ($all_property->status == '2') {
                                $status = '<a href="javascript:void(0)" class="changeStatus inactiveClass redlink" title="Mark as Active" data-url="' . route('agent.property.change.status', $all_property->id) . '" data-status="1">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="changeStatus greenlink" style="color:green" data-url="' . route('agent.property.change.status', $all_property->id) . '" data-status="2">Active</a>';
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
                            $str .= '<a href="' . route('agent.property.edit', $all_property->id) . '" title="Edit" class="table-edit action_icon"><img src="' . asset('public') . '/assets/images/ic_edit.png"></a>&nbsp;&nbsp;';
                            if ($all_property->is_delete == '0') {
                                $str .= '<a href="javascript:void(0)" class="deleteData action_icon" title="Delete" data-id="'.$all_property->id.'" data-url="'.route('agent.property.delete').'"><img src="' . asset('public') . '/assets/images/ic_delete.png"></a>';
                            }
                            return $str;
                        })
                        ->addIndexColumn()
                        ->rawColumns(['id','purpose', 'status', 'action'])
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $user_id = Session::get('user_id');
        $data = \App\Models\SettingModel::where('user_id', '1')->where('text_key', '=', 'google_map_api_key')->first();
        $key = (!empty($data->text_value)) ? $data->text_value : '';
        //$users = UserModel::select('users.*', 'agent.agent_unique_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')->where('agency_relation.user_type', 1)->where('agency_relation.agency_id', $user_id)->orderby('users.id', 'desc')->get();
        $agency_id = \App\Models\AgencyRelationModel::where('user_id', '=', $user_id)->first();
        return view('agent.properties.add')->with(['agency_id' => $agency_id, 'mapkey' => $key]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $v = Validator::make($request->all(), [
                    'purpose' => 'required',
                    'type' => 'required',
                    'agent' => 'required',
                    'upload_video.*' => 'mimetypes:video/mp4,video/quicktime,video/ogg,video/x-flv,video/MP2T,video/3gpp,video/avi | max:25000'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors());
        }
        $ins_arry = [
            'purpose' => $request->purpose,
            'type' => $request->type,
            'agent_id' => $request->agent,
            'agency_id' => $request->agency_id,
            //'address' => $request->address_address,
             'address_type' => $request->address_type,
            'description' => $request->description,
            'seo_title' => $request->seo_tiltle,
            'seo_description' => $request->seo_description,
            'latitude' => round($request->address_latitude, 6),
            'longitude' => round($request->address_longitude, 6),
            'city' => $request->address_city,
            'state' => $request->address_state,
            'country' => $request->address_country,
            'zipcode' => $request->address_zipcode,
            'is_delete' => '0',
            'created_by' => Session::get('user_id')
        ];
         if(!empty($request->address_type)){
            if($request->address_type==1){
               $ins_arry['address'] =$request->address_address;
            }else{
               $ins_arry['address'] =$request->manual_address.' '.$request->address_address; 
               $ins_arry['manual_address']=$request->manual_address;
            }
        }

        if ($request->type == '1') {
            $ins_arry['beds'] = $request->beds;
            $ins_arry['baths'] = $request->baths;
            $ins_arry['sq_feet'] = $request->sqft;
            $ins_arry['price'] = $request->price;
            $ins_arry['occupied'] = $request->occupied;
            $ins_arry['hoa'] = $request->hoa;
        }
        if ($request->type == '2') {
            $ins_arry['total_living_sqft'] = $request->total_living_sqft;
            $ins_arry['lot_sqft'] = $request->lot_sqft;
            $ins_arry['annual_tax'] = $request->annual_tax;
            $ins_arry['unit_amount'] = $request->unitamount;
            $ins_arry['price'] = $request->multi_price;
        }
        if ($request->type == '3') {
            $ins_arry['unit_amount'] = $request->unitamount_commercial;
            $ins_arry['total_commercial_space'] = $request->total_commercial_space;
            $ins_arry['lot_sqft'] = $request->lot_sqft_commercial;
            $ins_arry['annual_tax'] = $request->annual_tax_commercial;
            $ins_arry['zoning'] = $request->zoning_commercial;
            $ins_arry['price'] = $request->commercial_price;
        }
        if ($request->type == '4') {
            $ins_arry['total_industrial_space'] = $request->total_industrial_space;
            $ins_arry['lot_sqft'] = $request->lot_sqft_industrial;
            $ins_arry['annual_tax'] = $request->annual_tax_industrail;
            $ins_arry['zoning'] = $request->zoning_industrail;
            $ins_arry['price'] = $request->industrial_price;
        }
        if ($request->type == '5') {
            $ins_arry['lot_sqft'] = $request->lot_sqft_lot;
            $ins_arry['annual_tax'] = $request->annual_tax_lot;
            $ins_arry['zoning'] = $request->zoning_lot;
            $ins_arry['price'] = $request->lot_price;
        }

        $input = $request->all();
        if (isset($input['main_image']) && $input['main_image'] != null && !empty($input['main_image'])) {
            $file = $request->main_image;
            $image_name = time() . $file->getClientOriginalName();
            //$path_info_img = pathinfo($imgname);
            //$user_image = time() . '.' . $path_info_img['extension'];
            $ins_arry['main_image'] = $image_name;
        }
        $ins = PropertyModel::create($ins_arry);
        if ($request->type == '2') {
            $baths_total = 0;
            $beds_total = 0;
            for ($i = 1; $i <= $request->unitamount; $i++) {
                $baths = "baths" . $i;
                $beds = "beds" . $i;
                $ins_array_unit = [
                    'property_id' => $ins->id,
                    'unit' => $i,
                    'baths' => $request->$baths,
                    "beds" => $request->$beds
                ];
                $baths_total = $request->$baths + $baths_total;
                $beds_total = $request->$beds + $beds_total;
                \App\Models\PropertyUnit::create($ins_array_unit);
            }
            $bath_arr = ['baths' => $baths_total, 'beds' => $beds_total];
            PropertyModel::where('id', '=', $ins->id)->update($bath_arr);
        }
        if ($request->type == '3') {
            $baths_total = 0;
            for ($i = 1; $i <= $request->unitamount_commercial; $i++) {
                $baths = "baths" . $i;
                $sqft = "sqft" . $i;
                $ins_array_unit = [
                    'property_id' => $ins->id,
                    'unit' => $i,
                    'baths' => $request->$baths,
                    "sqft" => $request->$sqft
                ];
                $baths_total = $request->$baths + $baths_total;
                \App\Models\PropertyUnit::create($ins_array_unit);
            }
            $bath_arr = ['baths' => $baths_total];
            PropertyModel::where('id', '=', $ins->id)->update($bath_arr);
        }


        if (isset($input['main_image']) && $input['main_image'] != null && !empty($input['main_image'])) {
            $destinationPath = 'public/uploads/properties_images/';
            if (!file_exists('public/uploads/properties_images/' . $ins->id)) {
                mkdir('public/uploads/properties_images/' . $ins->id, 0777, true);
            }

            $file->move($destinationPath . $ins->id, $image_name);
            if (isset($input['other_image'])) {
                foreach ($input['other_image'] as $key => $value) {
                    $other_image = time() . $value->getClientOriginalName();
                    $value->move($destinationPath . $ins->id, $other_image);
                    $other_img_arr = [
                        'property_id' => $ins->id,
                        'image_name' => $other_image
                    ];
                    \App\Models\PropertyOtherImages::create($other_img_arr);
                }
            }
        }
        if (isset($input['upload_video']) && !empty($input['upload_video'])) {
            if (!file_exists('public/uploads/properties_video/' . $ins->id)) {
                mkdir('public/uploads/properties_video/' . $ins->id, 0777, true);
            }
            foreach ($input['upload_video'] as $key => $value) {
                if(!empty($value)){
                $destinationPath = 'public/uploads/properties_video/';
                $video = time() . $value->getClientOriginalName();
                $value->move($destinationPath . $ins->id, $video);
                $video_arr = [
                    'property_id' => $ins->id,
                    'video_link' => $video,
                    'type' => '2'
                ];
                PropertyVideo::create($video_arr);
                }
            }
        }
        if (isset($input['youtube']) && !empty($input['youtube'])) {
            foreach ($input['youtube'] as $key => $value) {
                if(!empty($value)){
                $video_arr = [
                    'property_id' => $ins->id,
                    'video_link' => $value,
                    'type' => '1'
                ];
                PropertyVideo::create($video_arr);
                }
            }
        }
        $notification = array('from_user_id' => Session::get('user_id'), 'to_user_id' => $request->agency_id, 'link' => '/property-list', 'active_flag' => 0, 'notification_desc' => Auth::user()->first_name . ' ' . Auth::user()->last_name . 'Created New Property.');
        NotificationMaster::insert($notification);
        return redirect()->route('agent.property.list')->with('success', 'Property Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $user_id = Session::get('user_id');
        $data = \App\Models\SettingModel::where('user_id', '1')->where('text_key', '=', 'google_map_api_key')->first();
        $key = (!empty($data->text_value)) ? $data->text_value : '';
        //$users = UserModel::select('users.*', 'agent.agent_unique_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')->where('agency_relation.user_type', 1)->where('agency_relation.agency_id', $user_id)->orderby('users.id', 'desc')->get();
        $property_data = PropertyModel::where('id', '=', $id)->first();
        if($property_data->agent_id!=$user_id){
            return redirect()->route('agent.property.list')->withErrors('You Do Not Have Access To This Property');
        }
        $multiple_image = PropertyOtherImages::where('property_id', '=', $property_data->id)->get();
        $property_video = PropertyVideo::where('property_id', '=', $property_data->id)->get();
        $property_unit = '';
        if ($property_data->type == '2' || $property_data->type == '3') {
            $property_unit = PropertyUnit::where('property_id', '=', $property_data->id)->get();
        }
        return view('agent.properties.edit')->with(['property' => $property_data, 'mapkey' => $key, 'property_unit' => $property_unit, 'other_image' => $multiple_image, 'property_videos' => $property_video]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
         $rules = [
            'purpose' => 'required',
            'address_address' => 'required',
            'seo_tiltle' => 'required',
            'upload_video_new.*' => 'mimetypes:video/mp4,video/quicktime,video/ogg,video/x-flv,video/MP2T,video/3gpp,video/avi | max:25000'
        ];
        $message = [
            'purpose.required' => 'Please Select Purpose',
            'address_address.required' => 'Please Enter Address',
            'seo_tiltle.required' => 'Please Enter Seo Title',
            'upload_video_new.*.mimes' => 'Please Upload only mp4,mov,ogg,qt,flv,ts,3gp,avi',
            'upload_video_new.*.max' => 'Maximum upload size alloed 25mb',
        ];
        $v = Validator::make($request->all(), $rules,$message);
        $ins_arry = [
            'purpose' => $request->purpose,
            //'type' => $request->type,
            //'agent_id' => $request->agent,
            //'agency_id' => Session::get('user_id'),
            //'address' => $request->address_address,
            'address_type' => $request->address_type,
            'description' => $request->description,
            'seo_title' => $request->seo_tiltle,
            'seo_description' => $request->seo_description,
            'latitude' => $request->address_latitude,
            'longitude' => $request->address_longitude,
            'city' => $request->address_city,
            'state' => $request->address_state,
            'country' => $request->address_country,
            'zipcode' => $request->address_zipcode,
            'updated_by' => Session::get('user_id')
        ];
         if(!empty($request->address_type)){
            if($request->address_type==1){
               $ins_arry['address'] =$request->address_address;
               $ins_arry['manual_address']='';
            }else{
               $ins_arry['address'] =$request->manual_address.' '.$request->address_address; 
               $ins_arry['manual_address']=$request->manual_address;
            }
        }
        if ($request->type == '1') {
            $ins_arry['beds'] = $request->beds;
            $ins_arry['baths'] = $request->baths;
            $ins_arry['sq_feet'] = $request->sqft;
            $ins_arry['price'] = $request->price;
            $ins_arry['occupied'] = $request->occupied;
            $ins_arry['hoa'] = $request->hoa;
        }
        if ($request->type == '2') {
            $ins_arry['total_living_sqft'] = $request->total_living_sqft;
            $ins_arry['lot_sqft'] = $request->lot_sqft;
            $ins_arry['annual_tax'] = $request->annual_tax;
            $ins_arry['unit_amount'] = $request->unitamount;
            $ins_arry['price'] = $request->multi_price;
        }
        if ($request->type == '3') {
            $ins_arry['unit_amount'] = $request->unitamount_commercial;
            $ins_arry['total_commercial_space'] = $request->total_commercial_space;
            $ins_arry['lot_sqft'] = $request->lot_sqft_commercial;
            $ins_arry['annual_tax'] = $request->annual_tax_commercial;
            $ins_arry['zoning'] = $request->zoning_commercial;
            $ins_arry['price'] = $request->commercial_price;
        }
        if ($request->type == '4') {
            $ins_arry['total_industrial_space'] = $request->total_industrial_space;
            $ins_arry['lot_sqft'] = $request->lot_sqft_industrial;
            $ins_arry['annual_tax'] = $request->annual_tax_industrail;
            $ins_arry['zoning'] = $request->zoning_industrail;
            $ins_arry['price'] = $request->industrial_price;
        }
        if ($request->type == '5') {
            $ins_arry['lot_sqft'] = $request->lot_sqft_lot;
            $ins_arry['annual_tax'] = $request->annual_tax_lot;
            $ins_arry['zoning'] = $request->zoning_lot;
            $ins_arry['price'] = $request->lot_price;
        }

        $input = $request->all();
        if (isset($input['main_image']) && $input['main_image'] != null && !empty($input['main_image'])) {
            $file = $request->main_image;
            $image_name = time() . $file->getClientOriginalName();
            //$path_info_img = pathinfo($imgname);
            //$user_image = time() . '.' . $path_info_img['extension'];
            $ins_arry['main_image'] = $image_name;
        }
        $ins = PropertyModel::where('id', '=', $id)->update($ins_arry);
        if ($request->type == '2') {
            PropertyUnit::where('property_id', '=', $id)->delete();
            $baths_total = 0;
            $beds_total = 0;
            for ($i = 1; $i <= $request->unitamount; $i++) {
                $baths = "baths" . $i;
                $beds = "beds" . $i;
                $ins_array_unit = [
                    'property_id' => $id,
                    'unit' => $i,
                    'baths' => $request->$baths,
                    "beds" => $request->$beds
                ];
                $baths_total = $request->$baths + $baths_total;
                $beds_total = $request->$beds + $beds_total;
                PropertyUnit::create($ins_array_unit);
            }
            $bath_arr = ['baths' => $baths_total, 'beds' => $beds_total];
            PropertyModel::where('id', '=', $id)->update($bath_arr);
        }
        if ($request->type == '3') {
            $baths_total = 0;
            PropertyUnit::where('property_id', '=', $id)->delete();
            for ($i = 1; $i <= $request->unitamount_commercial; $i++) {
                $baths = "baths" . $i;
                $sqft = "sqft" . $i;
                $ins_array_unit = [
                    'property_id' => $id,
                    'unit' => $i,
                    'baths' => $request->$baths,
                    "sqft" => $request->$sqft
                ];
                $baths_total = $request->$baths + $baths_total;
                \App\Models\PropertyUnit::create($ins_array_unit);
            }
            $bath_arr = ['baths' => $baths_total];
            PropertyModel::where('id', '=', $id)->update($bath_arr);
        }


        if (isset($input['main_image']) && $input['main_image'] != null && !empty($input['main_image'])) {
            $destinationPath = 'public/uploads/properties_images/';
            if (!file_exists('public/uploads/properties_images/' . $id)) {
                mkdir('public/uploads/properties_images/' . $id, 0777, true);
            }

            $file->move($destinationPath . $id, $image_name);
        }
        if (isset($input['other_image'])) {
            $destinationPath = 'public/uploads/properties_images/';
            foreach ($input['other_image'] as $key => $value) {
                $other_image = time() . $value->getClientOriginalName();
                $value->move($destinationPath . $id, $other_image);
                $other_img_arr = [
                    'property_id' => $id,
                    'image_name' => $other_image
                ];
                \App\Models\PropertyOtherImages::create($other_img_arr);
            }
        }

        if (isset($input['youtube'])) {
            foreach ($input['youtube'] as $key => $value) {
                $video_arr = [
                    'video_link' => $value,
                ];
                PropertyVideo::where('id', '=', $key)->update($video_arr);
            }
        }
        if (isset($input['existing_youtube'])) {
            foreach ($input['existing_youtube'] as $key => $value) {
                PropertyVideo::where('id', '=', $value)->delete();
            }
        }
        
        if (isset($input['upload_video_new']) && !empty($input['upload_video_new'])) {
            if (!file_exists('public/uploads/properties_video/' . $id)) {
                mkdir('public/uploads/properties_video/' . $id, 0777, true);
            }
            foreach ($input['upload_video_new'] as $key => $value) {
                if(!empty($value)){
                $destinationPath = 'public/uploads/properties_video/';
                $video = time() . $value->getClientOriginalName();
                $value->move($destinationPath . $id, $video);
                $video_arr = [
                    'property_id' => $id,
                    'video_link' => $video,
                    'type' => '2'
                ];
                PropertyVideo::create($video_arr);
                }
            }
        }
        if (isset($input['youtube_new']) && !empty($input['youtube_new'])) {
            foreach ($input['youtube_new'] as $key => $value) {
                if(!empty($value)){
                $video_arr = [
                    'property_id' => $id,
                    'video_link' => $value,
                    'type' => '1'
                ];
                PropertyVideo::create($video_arr);
                }
            }
        }
        return redirect()->route('agent.property.list')->with('success', 'Property Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function activeInactive(Request $request, $id) {
        PropertyModel::where('id', $id)->update(['status' => $request->status]);
        if ($request->status == 1)
            return response()->json(['message' => 'Property Activated Successfully.', 'code' => 200]);
        else
            return response()->json(['message' => 'Property Deactivated Successfully.', 'code' => 200]);
    }

    public function removeImage(Request $request) {
        PropertyOtherImages::where('id', '=', $request->other)->delete();
        if ($request->image != '' && file_exists('public/uploads/properties_images/' . $request->property . '/')) {
            unlink('public/uploads/properties_images/' . $request->property . '/' . $request->image);
        }
        return response()->json(['message' => 'Image Removed Successfully.', 'code' => 200]);
    }
    
    public function removeVideo(Request $request){
        
        PropertyVideo::where('id', '=', $request->id)->delete();
        if ($request->video != '' && file_exists('public/uploads/properties_video/' . $request->property_id . '/')) {
            unlink('public/uploads/properties_video/' . $request->property_id . '/' . $request->video);
        }
        return response()->json(['message' => 'Video Removed Successfully.', 'code' => 200]);
    }
    public function propertyDelete(Request $request){
        //echo "<pre>"; print_r($_POST); exit;
        extract($_POST);
        $user_id=Auth::user()->id;
        $agencyRelation=AgencyRelationModel::where('user_id',$user_id)->first();
        if(!empty($agencyRelation)){
           $message = deleteProperty($agencyRelation->agency_id,$propertyId);
            return response()->json(['message' => $message['message'], 'code' => $message['code']]); 
        }else{
            return response()->json(['message' => 'Somthing want worng', 'code' => 300]);
        }
        
    }
}
