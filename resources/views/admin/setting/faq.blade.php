@extends('admin.layout.app_with_login')
@section('title','FAQ Setting')
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
      <h3 class="box-title">FAQ Setting</h3>
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
              $_REQUEST['data'] = (isset($_REQUEST['data']) && !empty($_REQUEST['data']))?$_REQUEST['data']:'seoSettings';
                        @endphp

                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    
                                        <li class="nav-item @if($_REQUEST['data'] == 'seoSettings') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'seoSettings') active @endif" id="seoSettings-tab" data-toggle="tab" href="#seoSettings" role="tab" aria-controls="seoSettings" aria-selected="false">SEO Settings</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="myTabContent">
                                      
                                        <div class="tab-pane fade @if($_REQUEST['data'] == 'seoSettings') active in @endif in" id="seoSettings" role="tabpanel" aria-labelledby="seoSettings-tab">
                                            <div class="row mt-10">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                	<form method="POST" action="{{ url('admin/savefaqsetting') }}" enctype="multipart/form-data">
                        													{{ @csrf_field() }}		
                        														<div class="row mt-10">
                        															<div class="form-group">
                        																<label>Meta Title</label>
                        																<textarea required class="form-control" name="faq_meta_title" id="faq_meta_title">@if(isset($data['faq_meta_title']) && !empty($data['faq_meta_title'])){{ $data['faq_meta_title']  }}@endif</textarea>
                        															</div>
                        															<div class="form-group">
                        																<label>Meta Description</label>
                        																<textarea required class="form-control" name="faq_meta_description" id="faq_meta_description">@if(isset($data['faq_meta_description']) && !empty($data['faq_meta_description'])){{ $data['faq_meta_description']  }}@endif</textarea>
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