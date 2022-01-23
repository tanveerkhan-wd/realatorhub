@extends('admin.layout.app_with_login')
@section('title','About us Setting')
@section('content')
<!-- 
View File for About us Setting
@package    Laravel
@subpackage View
@since      1.0
 -->
@php
        $data = array_column($data,'text_value','text_key');
        @endphp
<!-- Content Body -->
<section class="content">
<div class="row new_added_div">
<!-- left column -->
<div class="col-md-12">
  <div class="box-header">
      <h3 class="box-title">About us Setting</h3>
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
      @if(\Session::has('error'))
          <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <ul>
                  <li>{!! \Session::get('error') !!}</li>
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
              $_REQUEST['data'] = (isset($_REQUEST['data']) && !empty($_REQUEST['data']))?$_REQUEST['data']:'believeSection';
                        @endphp

                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item @if($_REQUEST['data'] == 'believeSection') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'believeSection') active @endif" id="logo-banner-data-tab" data-toggle="tab" href="#believeSection" role="tab" aria-controls="believeSection" aria-selected="true" aria-expanded="true">Believe</a>
                                        </li>
                                        <li class="nav-item @if($_REQUEST['data'] == 'missionSection') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'missionSection') active @endif" id="missionSection-tab" data-toggle="tab" href="#missionSection" role="tab" aria-controls="missionSection" aria-selected="false">Mission</a>
                                        </li>
                                        <li class="nav-item @if($_REQUEST['data'] == 'seoSettings') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'seoSettings') active @endif" id="seoSettings-tab" data-toggle="tab" href="#seoSettings" role="tab" aria-controls="seoSettings" aria-selected="false">SEO Settings</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade @if($_REQUEST['data'] == 'believeSection') active in @endif in" id="believeSection" role="tabpanel" aria-labelledby="logo-banner-data-tab">	
                                            <div class="row mt-10">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                	<!-- Start Believe Sectin -->
												<form method="POST" action="{{ url('admin/saveaboutBelieve') }}" enctype="multipart/form-data">
												{{ @csrf_field() }}									
													<div class="row mt-10">
														<div class="form-group">
															<label>Heading*</label>
															<textarea required class="form-control" name="about_believe_title" id="about_believe_title">@if(isset($data['about_believe_title']) && !empty($data['about_believe_title'])){{ $data['about_believe_title']  }}@endif</textarea>
														</div>
														<div class="form-group">
															<label>Title*</label>
															<textarea required class="form-control" name="about_beleive_title1" id="about_beleive_title1">@if(isset($data['about_beleive_title1']) && !empty($data['about_beleive_title1'])){{ $data['about_beleive_title1']  }}@endif</textarea>
														</div>
														<div class="form-group">
															<label>Description*</label>
															<textarea required class="form-control" name="about_believe_description" id="about_believe_description">@if(isset($data['about_believe_description']) && !empty($data['about_believe_description'])){{ $data['about_believe_description']  }}@endif</textarea>
														</div>
														<div class="form-group text-center">
															<input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data">
														</div>
													</div>
												</form>
												<!-- End Believe Section -->
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                        <div class="general_tab_design tab-pane fade @if($_REQUEST['data'] == 'missionSection') active in @endif in" id="missionSection" role="tabpanel" aria-labelledby="missionSection-tab">
                                            <div class="row mt-10">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                <!-- Start Missoin Sectin -->
												<form method="POST" action="{{ url('admin/saveaboutMission') }}" enctype="multipart/form-data">
												{{ @csrf_field() }}									
													<div class="row mt-10">
														<div class="form-group">
															<label>Title*</label>
															<textarea required class="form-control" name="about_mission_title" id="about_mission_title">@if(isset($data['about_mission_title']) && !empty($data['about_mission_title'])){{ $data['about_mission_title']  }}@endif</textarea>
														</div>
														<div class="form-group">
															<label>Mission Description 1*</label>
															<textarea required rows="5" class="form-control" name="about_mission_desctiption1" id="about_mission_desctiption1">@if(isset($data['about_mission_desctiption1']) && !empty($data['about_mission_desctiption1'])){{ $data['about_mission_desctiption1']  }}@endif</textarea>
														</div>
														<div class="form-group">
															<label>Mission Description 2*</label>
															<textarea required rows="5" class="form-control" name="about_mission_desctiption2" id="about_mission_desctiption2">@if(isset($data['about_mission_desctiption2']) && !empty($data['about_mission_desctiption2'])){{ $data['about_mission_desctiption2']  }}@endif</textarea>
														</div>
														<div class="form-group text-center">
															<input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data">
														</div>
													</div>
												</form>
												<!-- End Missoin Section -->
                                                </div>
                                                <div class="col-md-1"></div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade @if($_REQUEST['data'] == 'seoSettings') active in @endif in" id="seoSettings" role="tabpanel" aria-labelledby="seoSettings-tab">
                                            <div class="row mt-10">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                	<form method="POST" action="{{ url('admin/aboutsavemetaSetting') }}" enctype="multipart/form-data">
													{{ @csrf_field() }}		
														<div class="row mt-10">
															<div class="form-group">
																<label>Meta Title</label>
																<textarea rows="5" required class="form-control" name="about_meta_seo_title" id="about_meta_seo_title">@if(isset($data['about_meta_seo_title']) && !empty($data['about_meta_seo_title'])){{ $data['about_meta_seo_title']  }}@endif</textarea>
															</div>
															<div class="form-group">
																<label>Meta Description</label>
																<textarea rows="5" required class="form-control" name="about_meta_description" id="about_meta_description">@if(isset($data['about_meta_description']) && !empty($data['about_meta_description'])){{ $data['about_meta_description']  }}@endif</textarea>
															</div>
															<div class="form-group text-center">
																<input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data">
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

<!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
	.mt-10{
		margin-top: 20px;
	}
</style>
@endpush
@push('custom-scripts')
<!-- Include this Page Js -->
  <script type="text/javascript" src="{{ url('public/js/admin/setting/about.js') }}"></script>
@endpush