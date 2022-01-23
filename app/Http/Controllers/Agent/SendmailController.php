<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\PropertyModel;
use Session;
use App\Models\EmailSmsLog;
use App\Models\PropertyUnit;
use Illuminate\Support\Facades\DB;

class SendmailController extends Controller {

    public function index() {
        return view('agent.sendmail.index');
    }

    public function getCustomerList(Request $request) {
        $input = $request->all();
        $agency_data = \App\Models\AgencyRelationModel::where('user_id', '=', Auth::user()->id)->first();
        $agency_id = $agency_data->agency_id;
        if (!empty($input['fav'])) {
            $users = \App\Models\FavProperty::select('property_fav_unfav.user_id', 'users.*', 'agency_relation.agency_id')
                            ->leftJoin('users', 'property_fav_unfav.user_id', '=', 'users.id')
                            ->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'property_fav_unfav.user_id')
                            ->where('agency_relation.user_type', 2)
                            ->where('agency_relation.agency_id', $agency_id)
                            ->where('users.deleted_at', '=', NULL)
                            ->groupBy('users.id')->orderby('users.id', 'desc');
        } else {
            $users = UserModel::select('users.*', 'agency_relation.agency_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->where('agency_relation.user_type', 2)->where('agency_relation.agency_id', $agency_id)->orderby('users.id', 'desc');
        }
        if (isset($input['agent_all']) && $input['agent_all'] != '') {
            $users = $users->where(function ($users) use ($input) {
                $users->where('users.first_name', 'LIKE', '%' . $input['agent_all'] . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $input['agent_all'] . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $input['agent_all'] . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $input['agent_all'] . '%');
            });
        }
        return DataTables::of($users)
                        ->editColumn('first_name', function ($users) {
                            $name = $users->first_name . ' ' . $users->last_name;
                            return $name;
                        })
                        ->editColumn('created_at', function ($users) {
                            return date('d-m-Y', strtotime($users->created_at));
                        })
                        ->editColumn('email', function ($users) {
                            return $email = $users->email . '<br>(' . $users->phone_code . ' ' . $users->phone . ')';
                        })
                        ->addColumn('action', function ($users) {
                            $str = '';
                            $str.='<div class="form-group form-check check_box"><input type="checkbox" class="form-check-input check_val general_customer" name="customer_chk" id="terms_agree" value="" data-val="' . $users->id . '"><label class="custom_checkbox"></label></div>';
                            return $str;
                        })
                        ->addIndexColumn()
                        ->rawColumns(['action', 'first_name', 'email', 'created_at'])
                        ->make(true);
    }

    public function getPropertyList(Request $request) {
        $data = \App\Models\UserModel::where('id', Auth::user()->id)->with(['agency'])->first();
        $slug = $data->agency['slug'];
        $input = $request->all();
        if (!empty($input['customer'])) {
            $customer_fav_property = \App\Models\FavProperty::select(DB::raw("group_concat(property_id) as fav_property"))->where('user_id', '=', $input['customer'])->get()->toArray();
            $fav_property = explode(",", $customer_fav_property[0]['fav_property']);

            $all_property = PropertyModel::leftJoin('users', 'properties.agent_id', '=', 'users.id')->leftJoin('agency', 'properties.agency_id', '=', 'agency.user_id')
                    ->whereIn('properties.id', $fav_property)
                    ->where("is_delete", "=", "0")->where('properties.agent_id', '=', Auth::user()->id)->where('properties.status','=','1')
                    ->select('properties.id', 'properties.purpose', 'properties.type', 'properties.address', 'properties.is_delete', 'properties.status', 'properties.created_at', 'users.first_name', 'users.email', 'users.last_name', 'agency.slug')
                    ->orderby('properties.id', 'desc');
        } else {
            $all_property = PropertyModel::leftJoin('users', 'properties.agent_id', '=', 'users.id')->leftJoin('agency', 'properties.agency_id', '=', 'agency.user_id')->where("is_delete", "=", "0")->where('agent_id', '=', Auth::user()->id)->where('properties.status','=','1')
                    ->select('properties.id', 'properties.purpose', 'properties.type', 'properties.address', 'properties.is_delete', 'properties.status', 'properties.created_at', 'users.first_name', 'users.email', 'users.last_name', 'agency.slug')
                    ->orderby('properties.id', 'desc');
        }
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
                            $string = array(', ', ',', ' ', '/', "'");
                            $replace = array('-', '-', '-', '-', '-');
                            $address = str_replace($string, $replace, $all_property->address);
                            $address = str_replace('--', '-', $address);
                            $url = url('') . '/' . $all_property->slug . '/property-detail/' . $address . '-' . $all_property->id;
                            return '<a href="' . $url . '" class="underline" target="_blank">' . $all_property->id . '</a>';
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
                        ->addColumn('action', function ($all_property) {
                            return '<div class="form-group form-check check_box"><input type="checkbox" class="form-check-input check_val general_property" name="property_chk" id="terms_agree" data-val="' . $all_property->id . '"><label class="custom_checkbox"></label></div>';
                        })
                        ->addIndexColumn()
                        ->rawColumns(['id', 'purpose', 'status', 'action'])
                        ->make(true);
    }

    public function sendGeneralEmail(Request $request) {
        $input = $request->all();
        if (!empty($input['customer']) && !empty($input['property']) && !empty($input['note'])) {
            $agency_rel_data = \App\Models\AgencyRelationModel::where('user_id', '=', Auth::user()->id)->first();
            $agency_id = $agency_rel_data->agency_id;
            $agency_data = \App\Models\AgencyModel::where('user_id', '=', $agency_id)->first();
            $agency_logo = url('public/uploads/profile_photos') . '/' . $agency_data->agency_logo;
            $property_list = PropertyModel::whereIn('id', $input['property'])->get();
            $html = '';
            $str = '';
            foreach ($property_list as $key => $property) {
                $sqft = '';
                if (!empty($property->sq_feet)) {
                    $sqft = $property->sq_feet;
                } else if (!empty($property->total_living_sqft)) {
                    $sqft = $property->total_living_sqft;
                } else if (!empty($property->lot_sqft)) {
                    $sqft = $property->lot_sqft;
                }
                $str.="<div style='background-color:#eee;padding:15px;border-radius:10px;margin:15px 0px;';><div style='text-align:center;margin-bottom:15px;'>";
                if(file_exists(url('public/uploads/properties_images') . '/' . $property->id . '/' . $property->main_image)){
                $str.="<img src=" . url('public/uploads/properties_images') . '/' . $property->id . '/' . $property->main_image . " id='agency_logo_image' style='max-height:300px;max-width:100%;'>";
                }else{
                    $str.="<img src=".url('/public/uploads/properties_images/16/159767026246e6cfde-fc64-4dc3-a4f0-dfe0216c849c.jpg')." style='max-height:300px;max-width:100%;'>";
                }
                $str.="</div><br>";
                if ($property->purpose == 1) {
                    $str.='<div style="font-family: arial;margin-bottom:3px;"><span style="display: inline-block;font-family: arial;">Buy </span>';
                } else {
                    $str.='<div style="font-family: arial;margin-bottom:3px;"><span style="display: inline-block;font-family: arial;">Rent </span>';
                }
                if ($property->type == 1) {
                    $str.='<span style="display: inline-block;font-family: arial;">&nbsp; | Single Homes</span>';
                } else if ($property->type == 2) {
                    $str.='<span style="display: inline-block;font-family: arial;">&nbsp; | Multifamily</span>';
                } else if ($property->type == 3) {
                    $str.='<span style="display: inline-block;font-family: arial;">&nbsp; | Commercial</span>';
                } else if ($property->type == 4) {
                    $str.='<span style="display: inline-block;font-family: arial;">&nbsp; | Industrial</span>';
                } else if ($property->type == 5) {
                    $str.='<span style="display: inline-block;font-family: arial;">&nbsp; |  Lot</span>';
                }
                if(!empty($property->price)){
                $str.="<span style='display: inline-block;font-family: arial;float:right;font-size:18px;'><b>$" . $property->price . '</b></span>';
                }
                $str.="</div><div style='display: inline-block;font-family: arial;margin-bottom:5px;color:#4e43fc;'>" . $property->address . '</div><br/><div>';
                

                if ($property->type == '2' || $property->type == '3') {
                    if (!empty($property->unit_amount)) {
                        $str.='<span style="display: inline-block;font-family: arial;">Unit Amount:' . $property->unit_amount . '</span><br>';
                        $property_unit = PropertyUnit::where('property_id', '=', $property->id)->get();
                        foreach ($property_unit as $key => $unit) {
                            if ($property->type == '2') {
                                if(!empty($unit->unit)){
                                $str.='<span style="display: inline-block;font-family: arial;"><img src='.url("/public/assets/images/ic_blue_ba.png").'> ' . $unit->unit . ' | &nbsp;</span>';
                                }
                                if(!empty($unit->baths)){
                                $str.='<span style="display: inline-block;font-family: arial;"><img src='.url("/public/assets/images/ic_blue_ba.png").'> ' . $unit->baths . ' | &nbsp;</span>';
                                }
                                if(!empty($unit->beds)){
                                $str.='<span style="display: inline-block;font-family: arial;"><img src='.url("/public/assets/images/ic_blue_bed.png").'> ' . $unit->beds . '  </span><br>';
                                }
                            } else {
                                if(!empty($unit->unit)){
                                $str.='<span style="display: inline-block;font-family: arial;"><img src='.url("/public/assets/images/ic_blue_ba.png").'> ' . $unit->unit . ' | &nbsp;</span>';
                                }
                                if(!empty($unit->sqft)){
                                $str.='<span style="display: inline-block;font-family: arial;"><img src='.url("/public/assets/images/ic__blue_sq.png").'> ' . $unit->sqft . ' | &nbsp;</span>';
                                }
                                if(!empty($unit->baths)){
                                $str.='<span style="display: inline-block;font-family: arial;"><img src='.url("/public/assets/images/ic_blue_ba.png").'> ' . $unit->baths . '  </span><br>';
                                }
                            }
                        }
                    }
                } else {
                    if(!empty($property->beds)){
                    $str.="<span style='display: inline-block;font-family: arial;'><img src=".url('/public/assets/images/ic_blue_bed.png')."> " . $property->beds . ' | &nbsp;</span>';
                    }
                    if(!empty($property->baths)){
                    $str.="<span style='display: inline-block;font-family: arial;'><img src=".url('/public/assets/images/ic_blue_ba.png')."> " . $property->baths . ' | &nbsp;</span>';
                    }
                    if (!empty($sqft)) {
                        $str.="<span style='display: inline-block;font-family: arial;'><img src=".url('/public/assets/images/ic__blue_sq.png')."> " . $sqft . '  </span>';
                    }
                }
                 $string = array(', ', ',', ' ', '/', "'");
                $replace = array('-', '-', '-', '-', '-');
                $address = str_replace($string, $replace, $property->address);
                $address = str_replace('--', '-', $address);
                $url = url('') . '/' . $agency_data->slug . '/property-detail/' . $address . '-' . $property->id;
                $str.='<span style="float:right;"><a href="' . $url . '" class="action_icon" target="_blank" style="display: inline-block;text-decoration: none;font-size: 16px;background-color: #4e43fc;padding: 5px 25px;color: #fff;border-radius: 4px;font-family: arial;">View</a></span></div>';
                $allowedlimit = 160;
                $desc=(mb_strlen($property->description )>$allowedlimit) ? mb_substr(strip_tags($property->description),0,$allowedlimit).'....' : $property->description ;
                if(!empty($desc)){
                $str.='<div style="display: inline-block;font-family: arial;margin-bottom:3px;">' . $desc . '</div></div>';
                }
               
                
                $str.='';
            }

            foreach ($input['customer'] as $key => $customer) {
                $html = '';
                $user_data = UserModel::where('id', '=', $customer)->first();
                $html.=$str;
                $html.='<div style="display: inline-block;font-family: arial;margin-bottom:3px;">' . $input['note'] . '</div>';
                if(!empty($input['fav'])){
                    $emailContent = \App\Models\EmailMasterModel::where('id', '=', 31)->first();
                }else{
                $emailContent = \App\Models\EmailMasterModel::where('id', '=', 30)->first();
                }
                $emailContent->content = str_replace('{{USERNAME}}', $user_data->first_name, $emailContent->content);
                $emailContent->content = str_replace('{{AGENCYNAME}}', $agency_data->agency_name, $emailContent->content);
                $messageContent = str_replace('{{MESSAGE}}', $html, $emailContent->content);
                $messageContentsend='<div style="font-family: arial;">'.$messageContent.'</div>';
                $student_log = EmailSmsLog::create([
                            'user_id' => $user_data->id,
                            'subject' => $emailContent->subject,
                            'email_content' => $messageContentsend,
                            'email_status' => EmailSmsLog::PENDING_EMAIL_STATUS,
                            'logo' => $agency_logo
                ]);
            }
            return response()->json(['message' => 'Email Sent Successfully.', 'code' => 200]);
        }
    }

}
