@extends('admin.layout.app_with_login')
@section('title','Profile')
@section('content')
    <!-- Start Content Body -->
    <section class="content">
        <div class="row">
            <!-- left column -->


            <div class="col-md-12">
                <div class="box-header">
                    <h3 class="box-title">Profile</h3>
                </div>
                <!-- general form elements -->
                <div class="box box-primary box-solid">

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

                    @php
                        $_REQUEST['data'] = (isset($_REQUEST['data']) && !empty($_REQUEST['data']))?$_REQUEST['data']:'profile';
                    @endphp



                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item @if($_REQUEST['data'] == 'profile') active @endif">
                                        <a class="nav-link @if($_REQUEST['data'] == 'profile') active @endif" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true" aria-expanded="true">Edit Profile</a>
                                    </li>
                                    <li class="nav-item @if($_REQUEST['data'] == 'change-password') active @endif">
                                        <a class="nav-link @if($_REQUEST['data'] == 'change-password') active @endif" id="change-password-tab" data-toggle="tab" href="#change-password" role="tab" aria-controls="change-password" aria-selected="false">Change Password</a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade @if($_REQUEST['data'] == 'profile') active in @endif in" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <!-- Start Believe Sectin -->
                                        <div class="row mt-10">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <div class="text-center">
                                                    <div class="profile_pic_box">
                                                        <div class="profile_pic">

                                                            @php
                                                                $profile_pic = url('public/images/default-user-image.png');
                                                                if(!empty(Auth::user()->profile_img) && Auth::user()->profile_img != '' && Auth::user()->profile_img != null){
                                                                  $profile_pic = url('public/uploads/user_profile/'.Auth::user()->profile_img);
                                                                }
                                                            @endphp
                                                            <img src="{{ $profile_pic }}">
                                                        </div>
                                                        <div class="edit_icon">
                                                            <img src="{{ url('public/images') }}/ic_pencil.png">
                                                            <input type="file" name="" class="user_image_profile" id="upload_profile">
                                                            <input type="hidden" name="old_user_image" id="old_user_image" value="{{ Auth::user()->profile_img }}">
                                                        </div>
                                                    </div>
                                                    <div id="upload-demo" style=""></div>
                                                </div>
                                                <div class="text-center">
                                                    <button class="upload-result btn btn-primary">Upload Image</button>
                                                </div>

                                                <form method="POST" action="{{ url('admin/profile') }}" enctype="multipart/form-data" id="loginForm" name="loginForm">
                                                    {{ @csrf_field() }}
                                                    <div class="row mt-10">
                                                        <div class="form-group text-center" style="display: none;">
                                                            <!-- <label>Logo</label> -->
                                                            <input type="hidden" name="old_user_image" value="{{ Auth::user()->profile_img }}">
                                                            <div class="profile_pic_box">
                                                                <div class="profile_pic">
                                                                    <img src="{{ url('public/uploads/user_profile').'/'.Auth::user()->profile_img }}">
                                                                </div>
                                                                <div class="edit_icon">
                                                                    <label class="la la-edit" style="cursor: pointer;">
                                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                        <input style="display: none" type="file" name="user_image" id="user_image" accept="image/*">
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="errorImage"></div>
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Name</label>
                                                            <textarea required class="form-control" name="name" id="name">{{ Auth::user()->user_name }}</textarea>
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Email address</label>
                                                            <textarea  class="form-control" name="email" id="email">{{ Auth::user()->email }}</textarea>
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Timezone</label>

                                                            <select class="select2 form-control icon_control dropdown_control" name="timezone" id="timezone">
                                                                @foreach(getTimeZones() as $timeZone)
                                                                    <option value="{{$timeZone->id}}" {{($timeZone->id==Auth::user()->timezone)?'selected':'' }}>{{trim($timeZone->timezone)}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="form-group text-center">
                                                            <input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-3"></div>
                                        </div>
                                        <!-- End Believe Section -->
                                    </div>

                                    <div class="tab-pane fade @if($_REQUEST['data'] == 'change-password') active in @endif in" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                                        <div class="row mt-10">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <!-- Start Believe Sectin -->
                                                <form method="POST" action="{{ url('admin/change-password') }}" enctype="multipart/form-data" id="change-password-form" name="change-password-form">
                                                    {{ @csrf_field() }}
                                                    <div class="row mt-10">
                                                        <div class="form-group text-center lock_image">
                                                            <img src="{{ url('public/admin/dist/img/lock.png') }}">
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Old Password</label>
                                                            <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Old Password" required="">
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>New Password</label>
                                                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="New Password">
                                                        </div>
                                                        <div class="form-group ">
                                                            <label>Confirm Password</label>
                                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
                                                        </div>
                                                        <div class="form-group text-center">
                                                            <input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data1" id="save_home_banner_data1">
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="col-md-3"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link link rel="stylesheet" type="text/css" href="{{ url('public/css/croppie.min.css') }}">
<style type="text/css">

    div#upload-demo {
        height: 160px;
        width: 160px;
        display: inline-block;
        vertical-align: top;
        margin: 0 20px;
        border: 1px solid #4e43fc;
    }

    div#upload-demo .cr-viewport.cr-vp-circle {
        height: 160px !important;
        width: 160px !important;
        box-shadow: 0 0 2000px 2000px rgba(78, 67, 252, 0.4);
    }

    div#upload-demo .cr-boundary {
        width: 160px !important;
        height: 160px !important;
    }

    div#upload-demo img.cr-image {
        opacity: 0;
    }

    button.upload-result {
        margin-top: 30px;
    }
    .edit_icon input[type="file"]{
        top: 0;
    }
</style>
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/croppie.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/admin/dashboard/profile.js') }}"></script>
@endpush