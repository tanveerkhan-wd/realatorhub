@extends('agency.layout.app_with_login')
@section('title','Customer Profile')
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
    <div class="path_link">
        <a href="#" class="parrent_page">My Customers </a> > <a href="#" class="current_page">View</a>
    </div>
    <div class="row">
        <div class="col-lg-12">	
            <ul class="nav nav-tabs text-center theme_tab" id="myTab" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" id="agentProperty-tab" data-toggle="tab" href="#agentProperty" role="tab" aria-controls="agentProperty" aria-selected="false">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="designSettings-tab" href="{{route('agency.customer.view.fav.property',$user_data->id)}}" >Favourite Properties</a>
                </li>
            </ul>
            <div class="tab-content theme_tab_content" id="myTabContent">
                <div class="tab-pane active in show" id="agentProperty" role="tabpanel" aria-labelledby="agentProperty-tab">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="upload-file-group profile_pic_box">
                                            <div class="choose_imd_box text-center">
                                                <?php if (isset($user_data->profile_img) && !empty($user_data->profile_img)) { ?>
                                                    <img src="{{ url('public/uploads/profile_photos').'/'.$user_data->profile_img }}" class="" id="agency_logo_image"><br>
                                                <?php } else { ?>
                                                    <img src="{{ url('public/assets/')}}/images/ic_sad_emoji.png" id="agency_logo_image"><br>
                                                <?php } ?> 

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Customer Name</label>
                                        <input type="text" name="first_name" placeholder="Enter first name" class="form-control" required="" value="{{$user_data->first_name.' '.$user_data->last_name}}" readonly=""> 
                                        <div id="first_name_validate"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" name="first_name" placeholder="Enter first name" class="form-control" required="" value="{{$user_data->email}}" readonly="">
                                        <div id="first_name_validate"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="first_name" placeholder="Enter first name" class="form-control" required="" value="{{$user_data->phone_code.' - '.$user_data->phone}}" readonly="">
                                        <div id="first_name_validate"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
