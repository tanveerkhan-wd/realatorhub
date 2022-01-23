<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AgencyModel;
use Yajra\DataTables\DataTables;
use App\Models\CountryCodeModel;
use App\Models\UserModel;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SignUpMail;
use Illuminate\Support\Facades\Validator;
use App\Models\SettingModel;
use App\Models\PropertyModel;
use App\Models\SubscriptionModel;
use App\Models\AgencyRelationModel;
use App\Models\PropertyLeadsModel;
use App\Models\AgentModel;

class AgencyController extends Controller {

    public function index() {
        return view('admin.agencies.index');
    }

    public function datatableList(Request $request) {
        $input = $request->all();
        $data = AgencyModel::leftjoin('users', 'agency.user_id', '=', 'users.id')
                ->leftjoin(DB::raw('(SELECT id as subscriptionID,subscription_id,user_id, plan_id ,status FROM `subscriptions` GROUP BY user_id ORDER BY subscriptionID DESC) subscriptions'), function($join) {
                    $join->on('agency.user_id', '=', 'subscriptions.user_id');
                })->leftjoin('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')
                ->select('agency.id as agencyID', 'agency.agency_name', 'users.id as userID', 'users.first_name', 'users.last_name', 'users.email', 'users.phone', 'users.admin_status', 'users.user_status', 'users.created_at', 'subscriptions.plan_id', 'subscription_plans.plan_name', 'subscriptions.subscriptionID', 'subscriptions.status as subStatus', 'users.phone_code')
                ->orderby('agency.id', 'desc');
        $data->where('users.deleted_at', '=', NULL);

        if (isset($input['agency_status']) && $input['agency_status'] != '') {
            $data = $data->where('users.admin_status', '=', $input['agency_status']);
        }

        if (isset($input['agency_search']) && $input['agency_search'] != '') {
            $data = $data->where(function ($data) use ($input) {

                $data->where('agency.agency_name', 'LIKE', '%' . $input['agency_search'] . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $input['agency_search'] . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $input['agency_search'] . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $input['agency_search'] . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $input['agency_search'] . '%')
                        ->orWhere('subscription_plans.plan_name', 'LIKE', '%' . $input['agency_search'] . '%')
                ;
            });
        }
        return DataTables::of($data)
                        ->editColumn('first_name', function ($data) {
                            $fullname = '';
                            $fullname = $data->first_name . ' ' . $data->last_name;
                            return $fullname;
                        })
                        ->editColumn('plan_name', function ($data) {
                            $plan_name = '';
                            if ($data->subStatus == '2') {
                                $plan_name = 'Deactivated';
                            } else {
                                $plan_name = $data->plan_name;
                            }
                            return $plan_name;
                        })
                        ->editColumn('created_at', function ($data) {
                            return date('d-m-Y', strtotime($data->created_at));
                        })
                        ->editColumn('email', function ($data) {
                            return $data->email . '<br>(' . $data->phone_code . ' ' . $data->phone . ')';
                        })
                        ->editColumn('user_status', function ($data) {
                            $status = '';
                            if ($data->user_status == '0') {
                                $status = '<a href="javascript:void(0)" class="changeStatus inactiveClass redlink" title="Mark as Active" data-url="' . route('admin.agency.change.status', $data->userID) . '" data-status="1" data-type="user">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="changeStatus greenlink" style="color:green" data-url="' . route('admin.agency.change.status', $data->userID) . '" data-status="0" data-type="user">Active</a>';
                            }
                            return $status;
                        })
                        ->editColumn('status', function ($data) {
                            $status = '';
                            if ($data->admin_status == '0') {
                                $status = '<a href="javascript:void(0)" class="changeStatus inactiveClass redlink" title="Mark as Active" data-url="' . route('admin.agency.change.status', $data->userID) . '" data-status="1" data-type="admin">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="changeStatus greenlink" style="color:green" data-url="' . route('admin.agency.change.status', $data->userID) . '" data-status="0" data-type="admin">Active</a>';
                            }
                            return $status;
                        })
                        ->addColumn('action', function ($data) {
                            $str = '';
                            $str.='<a href="' . route('admin.agency.view', ['id' => $data->userID]) . '" class="action_icon btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>&nbsp;';
                            $str .= '<a href="' . route('admin.agency.edit', ['id' => $data->userID]) . '" title="Edit" class="table-edit action_icon"><img src="' . asset('public') . '/assets/images/ic_edit_white.png"></a>&nbsp;';

                            $str .= '<a href="javascript:void(0)" class="deleteData action_icon" title="Delete" " data-url="' . route('admin.agency.delete', $data->userID) . '" data-id="{{$data->userID}}"><i class="fa fa-trash-o"></i></a>';
                            return $str;
                        })
                        ->addIndexColumn()
                        ->rawColumns(['purpose', 'status', 'action', 'email', 'user_status'])
                        ->make(true);
    }

    public function activeInactive(Request $request, $id) {
        if ($request->type == 'user') {
            \App\Models\UserModel::where('id', $id)->update(['user_status' => $request->status]);
        } else {
            \App\Models\UserModel::where('id', $id)->update(['admin_status' => $request->status]);
        }
        if ($request->status == 1)
            return response()->json(['message' => 'Agency Activated Successfully.', 'code' => 200]);
        else
            return response()->json(['message' => 'Agency Deactivated Successfully.', 'code' => 200]);
    }

    public function deleteAgency(Request $request, $id) {
        /* echo $id;
          echo "<pre>"; print_r($_POST); exit; */
        $data = array('deleted_at' => now());
        extract($_POST);
        //$delete = Users::where('id','=',$id)->update($data);
        UserModel::where('id', $id)->update($data);
        $allAgent = AgencyRelationModel::where('agency_id', $id)->get();
        foreach ($allAgent as $agent) {
            if (!empty($agent->user_id) && $agent->user_type==1) {
                $agentDetail = UserModel::where('id', $agent->user_id)->get();
                UserModel::where('id', $agent->user_id)->delete();
                AgentModel::where('user_id', $agent->user_id)->delete();
                @unlink(url('public/uploads/profile_photos' . $agentDetail['profile_img']));
            }else{
                $agentDetail = UserModel::where('id', $agent->user_id)->get();
                UserModel::where('id', $agent->user_id)->delete();
                @unlink(url('public/uploads/profile_photos' . $agentDetail['profile_img']));
            }
        }
        AgencyRelationModel::where('agency_id', $id)->delete();
        $allProperties = PropertyModel::where('agency_id', $id)->get();
        foreach ($allProperties as $property) {
            if (!empty($property->id)) {
                deleteAdminProperty($property->id);
            }
        }
        removeAgencyChat($id);
        PropertyModel::where('agency_id', $id)->delete();
        PropertyLeadsModel::where('agency_id', $id)->delete();
        SubscriptionModel::where('user_id', $id)->update(['status' => SubscriptionModel::STATUS_CANCEL]);
        return response()->json(['message' => 'Agency Deleted Successfully.', 'code' => 200]);
    }

    public function edit($id) {
        $country_code = CountryCodeModel::where('calling_code', '!=', '')->get();
        $timezones = getTimeZones();
        $user_id = $id;
        $agency_data = UserModel::where('id', $user_id)->with(['agency'])->first();
        $data = SettingModel::where('user_id', $user_id)->get()->toArray();
        $data = array_column($data, 'text_value', 'text_key');
        return view('admin.agencies.edit', compact('country_code', 'timezones', 'agency_data', 'data'));
    }

    public function changeEmail(Request $request) {

        $input = $request->all();
        $id = $input['id'];
        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail($id);
        $emailcout = UserModel::where('email', $input['email'])->where('id', '!=', $id)->count();
        if ($emailcout > 0) {
            return response()->json(['message' => 'This email id is already used.', 'code' => 201]);
        }
        $userdata = UserModel::where('id', $id)->first();
        if ($userdata->email != $input['email']) {
            $otp = mt_rand(100000, 999999);
            $emailContent = \App\Models\EmailMasterModel::where('id', 8)->first();
            $messageContent = $emailContent->content;
            $subject = $emailContent->subject;
            $name = $user->first_name;
            $messageContent = str_replace('{{OTP}}', $otp, $emailContent->content);
            try {
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent));
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

    public function checkemail(Request $request) {
        extract($_GET);
        $emailcout = UserModel::where('email', $email)->where('id', '!=', $id)->count();
        if ($emailcout > 0) {
            echo 'false';
            exit;
        } else {
            echo 'true';
            exit;
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

    public function editMyProfilePost(Request $request) {
        try {
            $input = $request->all();
            $check = AgencyModel::where('slug', '=', $input['agency_slug'])->where('user_id', '!=', $input['id'])->count();
            if ($check > 0) {
                return redirect()->back()
                                ->withErrors('Slug Already Exist');
            }
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'agency_name' => 'required',
                        'agency_slug' => 'required',
                        'country_code' => 'required',
                        'timezone' => 'required',
                        'mobile_number' => 'required|min:9|max:11',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                                ->withErrors($validator)
                                ->withInput();
            }

            $userdata = UserModel::where('id', $input['id'])->first();

            if (isset($input['agency_logo']) && $input['agency_logo'] != null && !empty($input['agency_logo'])) {
                $file = $request->file('agency_logo');
                $destinationPath = 'public/uploads/profile_photos';
                $user_image = time() . $file->getClientOriginalName();
                $file->move($destinationPath, $user_image);
                $storeAgency = AgencyModel::where('user_id', $input['id'])->update([
                    'agency_logo' => $user_image,
                ]);
            }
            $storeUser = UserModel::where('id', $input['id'])->update([
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'user_name' => $input['agency_slug'],
                /* 'email'=>$input['email'], */
                'phone_code' => $input['country_code'],
                'phone' => $input['mobile_number'],
                'timezone' => $input['timezone'],
                'updated_by' => '0'
            ]);


            $storeAgency = AgencyModel::where('user_id', $input['id'])->update([
                'agency_name' => $input['agency_name'],
                'slug' => $input['agency_slug'],
            ]);

            if (isset($input['lead_email']) && !empty($input['lead_email'])) {
                $date = date('Y-m-d H:i:s');
                $logoCreateOrUpdate = SettingModel::updateOrCreate(
                                ['text_key' => 'lead_email'], ['user_id' => $input['id'], 'text_value' => $input['lead_email'], 'created_date' => $date]
                );
            }
            return redirect()->back()->with('success', 'Profile Updated Successfully');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function checkUniqueSlug(Request $request) {
        $check = AgencyModel::where('slug', '=', $request->slug)->where('user_id', '!=', $request->id)->count();
        if ($check > 0) {
            return response()->json(['message' => 'Slug Already Exist', 'code' => 201]);
        }
    }

    public function show($id) {
        $country_code = CountryCodeModel::where('calling_code', '!=', '')->get();
        $timezones = getTimeZones();
        $user_id = $id;
        $agency_data = UserModel::where('id', $user_id)->with(['agency'])->first();
        $data = SettingModel::where('user_id', $user_id)->get()->toArray();
        $data = array_column($data, 'text_value', 'text_key');
        //$subscriptionPlans=\App\Models\SubscriptionModel::leftjoin('subscription_plans','subscriptions.plan_id','=','subscription_plans.id')->where('subscriptions.user_id','=',$id)->get();
        return view('admin.agencies.view', compact('country_code', 'timezones', 'agency_data', 'data', 'subscriptionPlans'));
    }

    public function getAgencyProperty(Request $request) {
        $input = $request->all();
        $all_property = PropertyModel::leftJoin('users', 'properties.agent_id', '=', 'users.id')->leftJoin('agency', 'properties.agency_id', '=', 'agency.user_id')->where("is_delete", "=", "0")->where('agency_id', '=', $input['id'])
                ->select('properties.id', 'properties.purpose', 'properties.type', 'properties.agency_id', 'properties.address', 'properties.is_delete', 'properties.status', 'properties.created_at', 'users.first_name', 'users.email', 'users.last_name', 'agency.slug')
                ->orderby('properties.id', 'desc');
        if (isset($input['agent']) && !empty($input['agent'])) {
            $all_property = $all_property->where(function ($all_property) use ($input) {

                $all_property->where('users.email', 'LIKE', '%' . $input['agent'] . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $input['agent'] . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $input['agent'] . '%');
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
                        ->editColumn('status', function ($all_property) {
                            $status = '';
                            if ($all_property->status == '2') {
                                $status = '<a href="javascript:void(0)" class="inactiveClass redlink changepropertystatus" title="Mark as Active" data-url="' . route('admin.agency.property.change.status', $all_property->id) . '" data-status="1">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="greenlink changepropertystatus" style="color:green" data-url="' . route('admin.agency.property.change.status', $all_property->id) . '" data-status="2">Active</a>';
                            }
                            return $status;
                        })
                        ->addColumn('action', function ($all_property) {
                            $string = array(', ', ',', ' ', '/', "'");
                            $replace = array('-', '-', '-', '-', '-');
                            $address = str_replace($string, $replace, $all_property->address);
                            $address = str_replace('--', '-', $address);
                            $url = url('') . '/' . $all_property->slug . '/property-detail/' . $address . '-' . $all_property->id;
                            $str = '<a href="' . $url . '" class="viewData btn btn-primary btn-sm" target="_blank" style="color: blue;padding: 5px 10px;" data-url="" title="View" "=""><i class="fa fa-eye"></i></a>&nbsp;';
                            $str .= '<a href="' . route('admin.property.edit', ['id' => $all_property->id, 'aid' => $all_property->agency_id]) . '" title="Edit" class="table-edit action_icon"><img src="' . asset('public') . '/assets/images/ic_edit_white.png"></a>&nbsp;';
                            if ($all_property->is_delete == '0') {
                                $str .= '<a href="javascript:void(0)" class="deletePropertyData action_icon" title="Delete" data-id="' . $all_property->id . '" data-url="' . route('admin.property.delete') . '"><img src="' . asset('public') . '/assets/images/ic_delete.png"></a>';
                            }
                            /* $str .= '<a href="' . route('admin.property.edit', ['id'=>$all_property->id,'aid'=>$all_property->agency_id]) . '" title="Edit" class="table-edit action_icon"><img src="' . asset('public') . '/assets/images/ic_edit.png"></a>&nbsp;&nbsp;';
                              if ($all_property->is_delete == '0') {
                              $str .= '<a href="javascript:void(0)" class="deleteData action_icon" title="Delete" data-id="'.$all_property->id.'" data-url="'.route('admin.property.delete').'"><img src="' . asset('public') . '/assets/images/ic_delete.png"></a>';
                              } */
                            return $str;
                        })
                        ->addIndexColumn()
                        ->rawColumns(['purpose', 'status', 'action', 'id'])
                        ->make(true);
    }

    public function getAgencyTransaction(Request $request) {
        $input = $request->all();
        $transaction_data = \App\Models\SubscriptionModel::leftjoin('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')->where('subscriptions.user_id', '=', $request->id);
        if (isset($input['start_date']) && $input['start_date'] != '') {
            $transaction_data = $transaction_data->where('start_date', 'LIKE', '%' . date("Y-m-d", strtotime($input['start_date'])) . '%');
        }
        if (isset($input['end_date']) && $input['end_date'] != '') {
            $transaction_data = $transaction_data->where('end_date', 'LIKE', '%' . date("Y-m-d", strtotime($input['end_date'])) . '%');
        }
        return DataTables::of($transaction_data)
                        ->editColumn('start_date', function ($transaction_data) {
                            $newEndDate = date("d-m-Y", strtotime($transaction_data->start_date));
                            return $newEndDate;
                        })
                        ->editColumn('end_date', function ($transaction_data) {
                            $newEndDate = date("d-m-Y", strtotime($transaction_data->end_date));
                            return $newEndDate;
                        })
                        ->addIndexColumn()
                        ->make(true);
    }

    function getAgencyAgent(Request $request) {
        $input = $request->all();
        $users = UserModel::select('users.*', 'agent.agent_unique_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->leftJoin('agent', 'agent.user_id', '=', 'agency_relation.user_id')->where('agency_relation.user_type', 1)->where('agency_relation.agency_id', $input['id'])->orderby('users.id', 'desc');
        if (isset($input['agent_status']) && $input['agent_status'] != '') {
            $users = $users->where('admin_status', '=', $input['agent_status']);
        }
        if (isset($input['agent_all']) && $input['agent_all'] != '') {
            $users = $users->where(function ($users) use ($input) {
                $users->where('agent.agent_unique_id', 'LIKE', '%' . $input['agent_all'] . '%')
                        ->orWhere('users.first_name', 'LIKE', '%' . $input['agent_all'] . '%')
                        ->orWhere('users.last_name', 'LIKE', '%' . $input['agent_all'] . '%')
                        ->orWhere('users.email', 'LIKE', '%' . $input['agent_all'] . '%')
                        ->orWhere('users.phone', 'LIKE', '%' . $input['agent_all'] . '%');
            });
        }

        return DataTables::of($users)
                        ->editColumn('admin_status', function ($users) {
                            $status = '';
                            if ($users->admin_status == '0') {
                                $status = '<a href="javascript:void(0)" class="inactiveClass redlink changeagentstatus" title="Mark as Active" data-url="' . route('admin.agency.agent.change.status', $users->id) . '" data-status="1" data-type="admin">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="greenlink changeagentstatus" style="color:green" data-url="' . route('admin.agency.agent.change.status', $users->id) . '" data-status="0" data-type="admin">Active</a>';
                            }
                            return $status;
                        })
                        ->editColumn('user_status', function ($users) {
                            $status = '';
                            if ($users->user_status == '0') {
                                $status = '<a href="javascript:void(0)" class="inactiveClass redlink changeagentstatus" title="Mark as Active" data-url="' . route('admin.agency.agent.change.status', $users->id) . '" data-status="1" data-type="user">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="greenlink changeagentstatus" style="color:green" data-url="' . route('admin.agency.agent.change.status', $users->id) . '" data-status="0" data-type="user">Active</a>';
                            }
                            return $status;
                        })
                        ->addColumn('action', function ($users) {
                            $str = '';
                            //$str='<a href="'.route('admin.agency.agent.edit',$users->id).'" class="viewData btn btn-primary btn-sm" target="_blank" style="color: blue;padding: 5px 10px;" data-url="" title="View" "=""><i class="fa fa-eye"></i></a>&nbsp;';
                            $str .= '<a href=" ' . route('admin.agency.agent.edit', $users->id) . '" title="Edit" class="table-edit action_icon"><img src="' . asset('public') . '/assets/images/ic_edit_white.png"></a>&nbsp;';
                            $str .= '<a href="javascript:void(0)" style="color: red;padding: 5px 10px;" class="deleteAgentData action_icon btn-danger" title="Delete" data-id="' . $users->id . '" data-url="' . route('admin.agency.agent.delete', $users->id) . '"><i class="fa fa-trash-o"></i></a>';

                            return $str;
                        })
                        ->editColumn('created_at', function ($users) {
                            return date('d-m-Y', strtotime($users->created_at));
                        })
                        ->addIndexColumn()
                        ->rawColumns(['admin_status', 'user_status', 'action','created_at'])
                        ->make(true);
    }

    public function agentDelete(Request $request, $id) {
        /* echo $id;
          echo "<pre>"; print_r($_POST); exit; */
        $data = array('deleted_at' => now());
        extract($_POST);
        //$delete = Users::where('id','=',$id)->update($data);
        $agencyData = AgencyRelationModel::where('user_id', $id)->first();
        $agency_id = $agencyData->agency_id;
        $activeSubscription = SubscriptionModel::with(['plan'])
                ->where('user_id', $agency_id)
                ->where('status', SubscriptionModel::STATUS_ACTIVE)
                ->first();
        if ($activeSubscription->total_no_of_agent >= $activeSubscription->plan->no_of_agent) {
            $additional_agent = $activeSubscription->additional_agents_counts - 1;
            $additional_agent_price = $additional_agent * ($activeSubscription->plan->additional_agent_per_rate);
            $updateSubscriptions = SubscriptionModel::where('id', $activeSubscription->id)->update(
                    [
                        'additional_agents_counts' => $additional_agent,
                        'additional_agent_price' => $additional_agent_price,
                    ]
            );
        }
        $no_of_agent = $activeSubscription->total_no_of_agent - 1;

        $updateSubscriptions = SubscriptionModel::where('id', $activeSubscription->id)->update(
                [
                    'total_no_of_agent' => $no_of_agent,
                ]
        );
        $agentDetail = UserModel::where('id', $id)->first();
        //UserModel::where('id', $id)->delete();
        //AgentModel::where('user_id', $id)->delete();
        $data = array('deleted_at' => now());
        updateAgentChat($id,$agent,$agency_id);
        UserModel::where('id', $id)->update($data);
        //@unlink(url('public/uploads/profile_photos' . $agentDetail['profile_img']));
        PropertyModel::where('agent_id', $id)->update(['agent_id' => $agent]);
        PropertyLeadsModel::where('agent_id', $id)->update(['agent_id' => $agent]);
        return response()->json(['message' => 'Agent Deleted Successfully.', 'code' => 200]);
        return 1;
    }

    public function changePropertyStatus(Request $request, $id) {

        \App\Models\PropertyModel::where('id', $id)->update(['status' => $request->status]);

        if ($request->status == 1)
            return response()->json(['message' => 'Property Activated Successfully.', 'code' => 200]);
        else
            return response()->json(['message' => 'Property Deactivated Successfully.', 'code' => 200]);
    }

    public function changeAgentStatus(Request $request, $id) {
        if ($request->type == 'user') {
            \App\Models\UserModel::where('id', $id)->update(['user_status' => $request->status]);
        } else {
            \App\Models\UserModel::where('id', $id)->update(['admin_status' => $request->status]);
        }
        if ($request->status == 1)
            return response()->json(['message' => 'Agent Activated Successfully.', 'code' => 200]);
        else
            return response()->json(['message' => 'Agent Deactivated Successfully.', 'code' => 200]);
    }

    public function editAgent($id) {
        $user_id = $id;
        $agency_data = UserModel::where('id', '=', $user_id)->first();
        $country_code = CountryCodeModel::where('calling_code', '!=', '')->get();
        $timezones = getTimeZones();
        $data = SettingModel::where('user_id', $user_id)->get()->toArray();
        $data = array_column($data, 'text_value', 'text_key');
        $agent_id = \App\Models\AgentModel::where('user_id', '=', $user_id)->first();
        return view('admin.agent.edit', compact('country_code', 'timezones', 'agency_data', 'data', 'agent_id'));
    }

    public function changeAgentMail(Request $request) {
        $input = $request->all();
        $id = $input['id'];
        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail($id);
        $emailcout = UserModel::where('email', $input['email'])->where('id', '!=', $id)->count();
        if ($emailcout > 0) {
            return response()->json(['message' => 'This Email Address Is Already In Use.', 'code' => 201]);
        }
        $userdata = UserModel::where('id', $id)->first();
        if ($userdata->email != $input['email']) {
            $otp = mt_rand(100000, 999999);
            $emailContent = \App\Models\EmailMasterModel::where('id', 8)->first();
            $messageContent = $emailContent->content;
            $subject = 'Agent Edit Profile - Change Email OTP';
            $name = $user->first_name;
            $messageContent = str_replace('{{OTP}}', $otp, $emailContent->content);
            try {
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent));
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

    public function updateAgentProfile(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'country_code' => 'required',
                        'timezone' => 'required',
                        'mobile_number' => 'required|min:9|max:11',
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
                    'phone' => $input['mobile_number'],
                    'timezone' => $input['timezone'],
                    'profile_img' => $user_image,
                ];
            } else {
                $update_data = [
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'phone_code' => $input['country_code'],
                    'phone' => $input['mobile_number'],
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

    public function viewAgencyProperty($id) {
        return view('admin.agencies.property')->with('id', $id);
    }

    public function viewAgencySubscriptionPlan($id) {
        $subscriptionPlans = \App\Models\SubscriptionModel::leftjoin('subscription_plans', 'subscriptions.plan_id', '=', 'subscription_plans.id')->where('subscriptions.user_id', '=', $id)->where('subscriptions.status', '=', '1')->orderby('subscriptions.id', 'desc')->limit(1)->get();
        return view('admin.agencies.subscription')->with(['subscriptionPlans' => $subscriptionPlans, 'id' => $id]);
    }

    public function viewAgencyAgent($id) {
        return view('admin.agencies.agent')->with('id', $id);
    }

    public function viewAgencyTransaction($id) {
        return view('admin.agencies.transaction')->with('id', $id);
    }

    public function viewAgencyCustomer($id) {
        return view('admin.agencies.customer')->with('id', $id);
    }

    public function getAgencyCustomer(Request $request) {
        $input = $request->all();
        $users = UserModel::select('users.*', 'agency_relation.agency_id')->leftJoin('agency_relation', 'agency_relation.user_id', '=', 'users.id')->where('agency_relation.user_type', 2)->where('agency_relation.agency_id', $input['id'])->orderby('users.id', 'desc');
        if (isset($input['agent_status']) && $input['agent_status'] != '') {
            $users = $users->where('admin_status', '=', $input['agent_status']);
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
                        ->editColumn('admin_status', function ($users) {
                            $status = '';
                            if ($users->admin_status == '0') {
                                $status = '<a href="javascript:void(0)" class="inactiveClass redlink changeagentstatus" title="Mark as Active" data-url="' . route('admin.agency.customer.change.status', $users->id) . '" data-status="1" data-type="admin">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="greenlink changeagentstatus" style="color:green" data-url="' . route('admin.agency.customer.change.status', $users->id) . '" data-status="0" data-type="admin">Active</a>';
                            }
                            return $status;
                        })
                        ->editColumn('user_status', function ($users) {
                            $status = '';
                            if ($users->user_status == '0') {
                                $status = '<a href="javascript:void(0)" class="inactiveClass redlink changeagentstatus" title="Mark as Active" data-url="' . route('admin.agency.customer.change.status', $users->id) . '" data-status="1" data-type="user">In Active</a>';
                            } else {
                                $status = '<a title="Mark as Inactive" href="javascript:void(0)" class="greenlink changeagentstatus" style="color:green" data-url="' . route('admin.agency.customer.change.status', $users->id) . '" data-status="0" data-type="user">Active</a>';
                            }
                            return $status;
                        })
                        ->addColumn('action', function ($users) {
                            $str = '';
                            //$str='<a href="'.route('admin.agency.agent.edit',$users->id).'" class="viewData btn btn-primary btn-sm" target="_blank" style="color: blue;padding: 5px 10px;" data-url="" title="View" "=""><i class="fa fa-eye"></i></a>&nbsp;';
                            $str .= '<a href=" ' . route('admin.agency.customer.edit', ['id' => $users->id, 'agencyid' => $users->agency_id]) . '" title="Edit" class="table-edit action_icon"><img src="' . asset('public') . '/assets/images/ic_edit_white.png"></a>&nbsp;';
                            $str .= '<a href="javascript:void(0)" style="color: red;padding: 5px 10px;" class="deleteAgentData action_icon btn-danger" title="Delete" data-id="' . $users->id . '" data-url="' . route('admin.agency.customer.delete', $users->id) . '"><i class="fa fa-trash-o"></i></a>';

                            return $str;
                        })
                        ->editColumn('created_at', function ($users) {
                            return date('d-m-Y', strtotime($users->created_at));
                        })
                        ->addIndexColumn()
                        ->rawColumns(['admin_status', 'user_status', 'action', 'first_name','created_at'])
                        ->make(true);
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

    public function customerDelete(Request $request, $id) {
        $data = array('deleted_at' => now());
        UserModel::where('id', $id)->update($data);
        return response()->json(['message' => 'Customer Deleted Successfully.', 'code' => 200]);
    }

    public function editCustomer($id, $agency_id) {
        $user_id = $id;
        $agency_id = $agency_id;
        $agency_data = UserModel::where('id', '=', $user_id)->first();
        $country_code = CountryCodeModel::where('calling_code', '!=', '')->get();
        $timezones = getTimeZones();
        $data = SettingModel::where('user_id', $user_id)->get()->toArray();
        $data = array_column($data, 'text_value', 'text_key');
        //$agent_id = \App\Models\AgentModel::where('user_id', '=', $user_id)->first();
        return view('admin.customer.edit', compact('country_code', 'timezones', 'agency_data', 'data', 'agency_id'));
    }

    public function updateCustomerProfile(Request $request) {
        try {
            $input = $request->all();
            $validator = Validator::make($request->all(), [
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'country_code' => 'required',
                        'timezone' => 'required',
                        'mobile_number' => 'required|min:9|max:11',
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
                    'phone' => $input['mobile_number'],
                    'timezone' => $input['timezone'],
                    'profile_img' => $user_image,
                ];
            } else {
                $update_data = [
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'phone_code' => $input['country_code'],
                    'phone' => $input['mobile_number'],
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

    public function changeCustomerMail(Request $request) {
        $input = $request->all();
        $id = $input['id'];
        //echo "<pre>"; print_r($input); exit;
        $user = UserModel::findorfail($id);
        $emailcount = AgencyRelationModel::leftJoin('users as u', 'u.id', '=', 'agency_relation.user_id')->where('agency_relation.user_id', '!=', $request->id)->where('agency_relation.agency_id', $request->agencyid)->where('agency_relation.user_type', 2)->where('u.email', $input['email'])->count();
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
                $agency_data = AgencyModel::where('user_id', $request->agencyid)->first();
                $agency_logo = url('/public/uploads/profile_photos/' . $agency_data->agency_logo);
                Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent, $agency_logo));
                //Mail::to($input['email'])->send(new SignUpMail($subject, $messageContent));
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

    public function checkCustomerEmail(Request $request) {
        $input = $request->all();
        $emailcount = AgencyRelationModel::leftJoin('users as u', 'u.id', '=', 'agency_relation.user_id')->where('agency_relation.user_id', '!=', $request->id)->where('agency_relation.agency_id', $request->agency_id)->where('agency_relation.user_type', 2)->where('u.email', $input['email'])->count();
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

}
