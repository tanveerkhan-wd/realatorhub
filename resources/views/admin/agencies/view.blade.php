@extends('admin.layout.app_with_login')
@section('title','Agency View')
@section('content')
<!-- 
View File for  List Credits
@package    Laravel
@subpackage View
@since      1.0
-->

@if ($errors->any())                       
<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (\Session::has('success'))
<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <ul>
        <li>{!! \Session::get('success') !!}</li>
    </ul>
</div>
@endif
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">	
            <ul class="nav nav-tabs">
                <li class="active"><a href="{{route('admin.agency.view',$agency_data->id)}}">Profile</a></li>
                <li><a href="{{route('admin.agency.view.property',$agency_data->id)}}">Properties</a></li>
                <li><a href="{{route('admin.agency.view.subscription.plan',$agency_data->id)}}">Subscription Plans</a></li>
                <li><a href="{{route('admin.agency.view.transaction',$agency_data->id)}}">Transactions</a></li>
                <li><a href="{{route('admin.agency.view.agent',$agency_data->id)}}">Agents</a></li>
                <li><a href="{{route('admin.agency.view.customer',$agency_data->id)}}">Customers</a></li>
            </ul>

            <div class="tab-content">
                <div id="agencyProfile" class="">
                    <form id="sign_up_form" method="POST" action="{{route('admin.agency.edit.profile')}}" enctype="multipart/form-data">
                        {{ @csrf_field() }}	
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="upload-file-group profile_pic_box">
                                        <div class="choose_imd_box text-center">
                                            <?php if (isset($agency_data->agency->agency_logo) && !empty($agency_data->agency->agency_logo)) { ?>
                                                <img src="{{ url('public/uploads/profile_photos').'/'.$agency_data->agency->agency_logo }}" class="" id="agency_logo_image"><br>
                                            <?php } ?> 

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="id" class="form-control" value="@if(isset($agency_data->id)){{$agency_data->id}} @endif">
                                    <label for="first_name">First Name</label>
                                    <input type="text"  pattern="\w+" name="first_name" class="form-control"
                                           id="first_name" aria-describedby="emailHelp" placeholder="Enter First Name" value="@if(isset($agency_data->first_name)){{$agency_data->first_name}}@endif" readonly="">
                                    <span class="form-control-feedback"></span>
                                    <div id="first_name_validate"></div>
                                </div>

                                <div class="form-group">
                                    <label for="last_name">Last Name</label>
                                    <input type="text"  pattern="\w+" name="last_name" class="form-control"
                                           id="last_name" aria-describedby="emailHelp" placeholder="Enter Last Name" value="@if(isset($agency_data->last_name)){{$agency_data->last_name}}@endif" readonly="">
                                    <span class="form-control-feedback"></span>
                                    <div id="last_name_validate"></div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="" id="changeEmailButton">Email ID</label>
                                    <input type="email"  name="old_email" class="form-control" id="email" placeholder="Enter Email Id" value="@if(isset($agency_data->email)){{$agency_data->email}} @endif" readonly="">
                                    <div id="email_validate"></div>
                                </div>
                                <div class="form-group">
                                    <label>Phone*</label>
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <input type="text"  name="country_code" class="form-control" value="@if(isset($agency_data->phone_code)){{$agency_data->phone_code}} @endif" readonly="">
                                        </div>
                                        <div class="col-xs-8">
                                            <input  type="text"  name="mobile_number" class="form-control" id="mobile_number" aria-describedby="emailHelp" placeholder="Enter Number" value="@if(isset($agency_data->phone)){{$agency_data->phone}} @endif" readonly="">
                                            <div id="mobile_number_validate"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="agency_name">Agency Name</label>
                                    <input type="text"  pattern="\w+" name="agency_name" class="form-control"
                                           id="agency_name" aria-describedby="emailHelp" placeholder="Enter Agency Name" value="@if(isset($agency_data->agency['agency_name'])){{$agency_data->agency['agency_name']}}@endif" readonly="">
                                    <span class="form-control-feedback"></span>
                                    <div id="agency_name_validate"></div>
                                </div>
                                <div class="form-group">
                                    <label for="agency_slug" class="w-100">Slug</label>
                                    <div class="agency_url_div">
                                        <label for="agency_slug">Realtorhubs.com/</label>
                                        <input type="text"  pattern="\w+" name="agency_slug" class="form-control"
                                               id="agency_slug" aria-describedby="emailHelp" placeholder="Enter agency slug" value="@if(isset($agency_data->agency['slug'])){{$agency_data->agency['slug']}}@endif" readonly="">
                                    </div>
                                    <span class="form-control-feedback"></span>
                                    <div id="agency_slug_validate"></div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Lead Email</label>
                                    <input type="email"  name="lead_email" class="form-control" id="lead_email" placeholder="" value="@if(isset($data['lead_email'])){{$data['lead_email']}} @endif" readonly="">
                                    <div id="email_validate"></div>  
                                </div>
                                <div class="form-row">
                                    <div class="form-group ">
                                        <label>Timezone</label>
                                        <select class="select2 form-control icon_control dropdown_control timezone" name="timezone" id="timezone" disabled="">
                                            <option value="">Select timezone</option>
                                            @foreach($timezones as $timeZone)
                                            <option value="{{$timeZone->id}}" @if($agency_data->timezone==$timeZone->id) selected @endif>{{trim($timeZone->timezone)}}</option>
                                            @endforeach
                                        </select>
                                        <div id="timezone_validate"></div>    
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- End Content Body -->

<!-- modal_end -->

@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
