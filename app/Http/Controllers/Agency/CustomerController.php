<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\CountryCodeModel;
use App\Models\AgencyRelationModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignUpMail;
use App\Models\SettingModel;
use App\Models\AgencyModel;

class CustomerController extends Controller {

    public function index() {
        return view('agency.customer.list');
    }

    public function customerAjaxList(Request $request) {
        $input = $request->all();
        $users = UserModel::select('users.*', 'agency_relation.agency_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->where('agency_relation.user_type', 2)->where('agency_relation.agency_id', Auth::user()->id)->orderby('users.id', 'desc');
        if (isset($input['agent_status']) && $input['agent_status'] != '') {
            //$users = $users->where('user_status', '=', $input['agent_status']);
            $users = $users->where(function ($users) use ($input) {
                $users->where('user_status', '=', $input['agent_status'])
                        ->orWhere('admin_status', '=', $input['agent_status']);
            });
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
                        ->editColumn('user_status', function ($users) {
                            $status = '';
                            if ($users->user_status == '0') {
                                $status = '<a href="javascript:void(0)" class="inactiveClass redlink changeagentstatus" title="Mark as Active" data-url="' . route('agency.customer.change.status', $users->id) . '" data-status="1" data-type="user">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="greenlink changeagentstatus" style="color:green" data-url="' . route('agency.customer.change.status', $users->id) . '" data-status="0" data-type="user">Active</a>';
                            }
                            return $status;
                        })
                        ->editColumn('admin_status', function ($users) {
                            $status = '';
                            if ($users->admin_status == '0') {
                                $status = '<a href="javascript:void(0)" class="inactiveClass redlink changeagentstatus" title="Mark as Active" data-url="' . route('agency.customer.change.status', $users->id) . '" data-status="1" data-type="admin">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="greenlink changeagentstatus" style="color:green" data-url="' . route('agency.customer.change.status', $users->id) . '" data-status="0" data-type="admin">Active</a>';
                            }
                            return $status;
                        })
                        ->addColumn('action', function ($users) {
                            $str = '';
                            //$str='<a href="'.route('admin.agency.agent.edit',$users->id).'" class="viewData btn btn-primary btn-sm" target="_blank" style="color: blue;padding: 5px 10px;" data-url="" title="View" "=""><i class="fa fa-eye"></i></a>&nbsp;';
                            $str = '<a href="' . route('agency.customer.view', $users->id) . '" class="action_icon"><img src="' . asset('public') . '/assets/images/ic_view.png"></a>';
                            $str .= '<a href="' . route('agency.customer.edit', $users->id) . '" title="Edit" class="table-edit action_icon"><img src="' . asset('public') . '/assets/images/ic_edit.png"></a>&nbsp;&nbsp;';
                            $str .= '<a href="javascript:void(0)" class="deleteData action_icon" title="Delete" data-id="' . $users->id . '" data-url="' . route('agency.customer.delete', $users->id) . '"><img src="' . asset('public') . '/assets/images/ic_delete.png"></a>';
                            return $str;
                        })
                         ->editColumn('created_at', function ($users) {
                                return date('d-m-Y', strtotime($users->created_at));
                            })
                        ->addIndexColumn()
                        ->rawColumns(['user_status', 'action', 'first_name', 'admin_status','created_at'])
                        ->make(true);
    }

    public function view($id) {
        $user_data = UserModel::where('id', '=', $id)->first();
        return view('agency.customer.view')->with('user_data', $user_data);
    }

    public function edit(Request $request, $id) {
        $input = $request->all();
        $country_code = CountryCodeModel::where('calling_code', '!=', '')->get();
        $user_data = UserModel::where('id', '=', $id)->first();
        $timezones = getTimeZones();
        $data = SettingModel::where('user_id', $id)->get()->toArray();
        $data = array_column($data, 'text_value', 'text_key');
        return view('agency.customer.edit', compact('country_code', 'user_data', 'timezones', 'data'));
    }

    public function checkCustomerEmail(Request $request) {
        $input = $request->all();
        $emailcount = AgencyRelationModel::leftJoin('users as u', 'u.id', '=', 'agency_relation.user_id')->where('agency_relation.user_id', '!=', $request->id)->where('agency_relation.agency_id', Auth::user()->id)->where('agency_relation.user_type', 2)->where('u.email', $input['email'])->count();
        if ($emailcount > 0) {
            echo 'false';
            exit;
        }
        $emailCountAgency = UserModel::where('user_type', '=', '1')->where('email', '=', $input['email'])->count();
        $emailCountAgent = UserModel::where('user_type', '=', '2')->where('email', '=', $input['email'])->count();
        //echo "<pre>"; print_r($userData->toArray()); exit;
        if ($emailCountAgency > 0) {
            echo 'false';
            exit;
        }
        if ($emailCountAgent > 0) {
            echo 'false';
            exit;
        }
        echo 'true';
    }

    public function changeCustomerMail(Request $request) {
        $input = $request->all();
        $id = $input['id'];
        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail($id);
        $emailcount = AgencyRelationModel::leftJoin('users as u', 'u.id', '=', 'agency_relation.user_id')->where('agency_relation.user_id', '!=', $request->id)->where('agency_relation.agency_id', Auth::user()->id)->where('agency_relation.user_type', 2)->where('u.email', $input['email'])->count();
        if ($emailcount > 0) {
            return response()->json(['message' => 'This email id is already used.', 'code' => 201]);
        }
        $emailCountAgency = UserModel::where('user_type', '=', '1')->where('email', '=', $input['email'])->count();
        if ($emailCountAgency > 0) {
            return response()->json(['message' => 'This email id is already used.', 'code' => 201]);
        }
        $emailCountAgent = UserModel::where('user_type', '=', '2')->where('email', '=', $input['email'])->count();
        if ($emailCountAgent > 0) {
            return response()->json(['message' => 'This email id is already used.', 'code' => 201]);
        }
//        $emailcout = UserModel::where('email', $input['email'])->where('id', '!=', $id)->count();
//        if ($emailcout > 0) {
//            return response()->json(['message' => 'This email id is already used.', 'code' => 201]);
//        }
        $input = $request->all();
        $id = $request->id;
        $userdata = UserModel::where('id', $id)->first();
        if ($userdata->email != $input['email']) {
            $otp = mt_rand(100000, 999999);
            $emailContent = \App\Models\EmailMasterModel::where('id', 8)->first();
            $messageContent = $emailContent->content;
            $subject = 'Customer Edit Profile - Change Email OTP';
            $name = $user->first_name;
            $messageContent = str_replace('{{OTP}}', $otp, $emailContent->content);
            try {
                //Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent));
                $agency_data=AgencyModel::where('user_id',Auth::user()->id)->first();
                $agency_logo=url('/public/uploads/profile_photos/'.$agency_data->agency_logo);
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent,$agency_logo));
                UserModel::where('id', $id)->update(['verification_code' => $otp]);
                return response()->json(['message' => 'OTP Resend Successfully.', 'code' => 200]);
            } catch (\Swift_TransportException $e) {

                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            } catch (\Exception $e) {
                if ($e->getCode() == 503) {

                    return response()->json(['message' => 'Email daily limit has been exceeded', 'code' => 201]);
                }
                return response()->json(['message' => $e->getMessage(), 'code' => 201]);
            }
        }
    }

    public function postEmailVerification(Request $request) {
        extract($_POST);
        try {
            $user_id = $request->id;
            $user = UserModel::where('id', $user_id)->first();
            $date = date('Y-m-d H:i:s');
            if ($user->verification_code == $email_otp) {
                $update = UserModel::where('id', $user_id)->update(['email' => $email, 'verification_time' => $date]);
                return response()->json(['message' => 'Email Verified Successfully.', 'code' => 200]);
            } else {
                return response()->json(['message' => 'OTP You Entered Is Incorrect', 'code' => 201]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Something Went Wrong', 'code' => 201]);
        }
    }

    public function update(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'country_code' => 'required',
                        'timezone' => 'required',
                        'single_mobile_number' => 'required|min:9|max:11',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            }
            /* $emailcout=$this->userModel::where('email', $input['email'])->where('id','!=',$input['id'])->count();
              if($emailcout > 0){
              return redirect('agency/my-account')->withErrors(['This email id is already used.']);
              } */
            if (isset($input['agency_logo']) && $input['agency_logo'] != null && !empty($input['agency_logo'])) {
                $file = $request->file('agency_logo');
                $destinationPath = 'public/uploads/profile_photos';
                $user_image = time() . $file->getClientOriginalName();
                $file->move($destinationPath, $user_image);
                $update_data = [
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'phone_code' => $input['country_code'],
                    'phone' => $input['single_mobile_number'],
                    'timezone' => $input['timezone'],
                    'profile_img' => $user_image,
                ];
            } else {
                $update_data = [
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'phone_code' => $input['country_code'],
                    'phone' => $input['single_mobile_number'],
                    'timezone' => $input['timezone'],
                ];
            }
            UserModel::where('id', '=', $input['id'])->update($update_data);
            return redirect()->back()->with('success', 'Profile Updated Successfully');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function changeCustomerStatus(Request $request, $id) {
        if ($request->type == 'user') {
            \App\Models\UserModel::where('id', $id)->update(['user_status' => $request->status]);
        } else {
            \App\Models\UserModel::where('id', $id)->update(['admin_status' => $request->status]);
        }
        if ($request->status == 1)
            return response()->json(['message' => 'Customer Activated Successfully.', 'code' => 200]);
        else
            return response()->json(['message' => 'Customer Deactivated Successfully.', 'code' => 200]);
    }

    public function destroy(Request $request, $id) {
        $data = array('deleted_at' => now());
        UserModel::where('id', $id)->update($data);
        return response()->json(['message' => 'Customer Deleted Successfully.', 'code' => 200]);
    }

    public function viewFavouriteProperty($id) {
        return view('agency.customer.fav_property')->with('id', $id);
    }

    public function getCustomerFavouriteProperty(Request $request) {
        $input = $request->all();
        if (!empty($input['userid'])) {
            $all_property = \App\Models\FavProperty::select('property_fav_unfav.*', 'properties.*')
                    ->leftJoin('properties', 'property_fav_unfav.property_id', '=', 'properties.id')
                    ->where('user_id', '=', $input['userid']);
            if (isset($input['property_all_search']) && $input['property_all_search'] != '') {
                $all_property = $all_property->where(function ($all_property) use ($input) {

                    $all_property->where('properties.address', 'LIKE', '%' . $input['property_all_search'] . '%');
                });
            }
            if (isset($input['property_type']) && $input['property_type'] != '') {
                $all_property = $all_property->where('properties.type', '=', $input['property_type']);
            }
            if (isset($input['property_purpose']) && $input['property_purpose'] != '') {
                $all_property = $all_property->where('properties.purpose', '=', $input['property_purpose']);
            }
//            if (isset($input['property_status']) && $input['property_status'] != '') {
//                $all_property = $all_property->where('properties.status', '=', $input['property_status']);
//            }
            return DataTables::of($all_property)
                            ->editColumn('id', function ($all_property) {
                                $agency_slug = \App\Models\AgencyModel::where('user_id', '=', Auth::user()->id)->first();
                                $string = array(', ', ',', ' ', '/', "'");
                                $replace = array('-', '-', '-', '-', '-');
                                $address = str_replace($string, $replace, $all_property->address);
                                $address = str_replace('--', '-', $address);
                                $url = url('') . '/' . $agency_slug->slug . '/property-detail/' . $address . '-' . $all_property->id;
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
                            ->editColumn('created_date', function ($all_property) {
                                return date('d-m-Y', strtotime($all_property->created_date));
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
//                            ->editColumn('status', function ($all_property) {
//                                $status = '';
//                                if ($all_property->status == '2') {
//                                    $status = '<a href="javascript:void(0)" class="changeStatus inactiveClass redlink" title="Mark as Active" data-url="' . route('agency.property.change.status', $all_property->id) . '" data-status="1">In Active</a>';
//                                } else {
//                                    $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="changeStatus greenlink" style="color:green" data-url="' . route('agency.property.change.status', $all_property->id) . '" data-status="2">Active</a>';
//                                }
//                                return $status;
//                            })
//                            ->addColumn('action', function ($all_property) {
//                                $agency_slug = \App\Models\AgencyModel::where('user_id', '=', Auth::user()->id)->first();
//                                $string = array(', ', ',', ' ', '/', "'");
//                                $replace = array('-', '-', '-', '-', '-');
//                                $address = str_replace($string, $replace, $all_property->address);
//                                $address = str_replace('--', '-', $address);
//                                $url = url('') . '/' . $agency_slug->slug . '/property-detail/' . $address . '-' . $all_property->id;
//                                $str = '<a href="' . $url . '" class="action_icon" target="blank"><img src="' . asset('public') . '/assets/images/ic_view.png"></a>';
//                                $str.= '<a href="' . route('agency.property.edit', $all_property->id) . '" title="Edit" class="table-edit action_icon"><img src="' . asset('public') . '/assets/images/ic_edit.png"></a>&nbsp;&nbsp;';
//                                $str .= '<a href="javascript:void(0)" class="deleteData action_icon" title="Delete" data-id="' . $all_property->id . '" data-url="' . route('agency.customer.property.unfav') . '"><img src="' . asset('public') . '/assets/images/ic_delete.png"></a>';
//                                return $str;
//                            })
                            ->addIndexColumn()
                            //->rawColumns(['purpose', 'status', 'action', 'id'])
                            ->rawColumns(['purpose','id'])
                            ->make(true);
        }
    }

    public function unfavouriteProperty(Request $request) {
        $input = $request->all();
        if (!empty($input['userid']) && !empty($input['propertyId'])) {
            $data = \App\Models\FavProperty::where('user_id', '=', $input['userid'])->where('property_id', '=', $input['propertyId'])->delete();
            return response()->json(['message' => 'Property Successfully Removed From Favourite', 'code' => 200]);
        } else {
            return response()->json(['message' => 'Something Went Wrong Please Try Again', 'code' => 200]);
        }
    }

}
