@extends('admin.layout.app_with_login')
@section('title','Edit CMS Image')
@section('content')
<!-- 
View File for  Edit CMS Page
@package    Laravel
@subpackage View
@since      1.0
 -->
<!-- Content Header (Page header) -->
<!-- <section class="content-header">
  <h1>
    Edit CMS
  </h1>
  <ol class="breadcrumb">
    <li class=""><a href="{{ url('admin/home') }}"><i class="fa fa-dashboard"></i> Dashboard></a></li>
    <li><a href="{{ url('admin/cms') }}"><i class="fa fa-picture-o"></i> CMS List</a></li>
    <li class="active"><i class="fa fa-picture-o"></i> Edit CMS </li>
  </ol>
</section> -->
<!-- End Content Header -->
<!-- Start Content Body -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid white_box">
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
                <div class="box-header">
                    <h3 class="box-title">Edit CMS</h3>
                </div>

                 <div class="box-body ">
                    <form role="form" action="{{ url('admin/cms/edit/'.$data['id']) }}" method="post" id="logo-form" novalidate="" enctype="multipart/form-data">
                    {{ @csrf_field() }}
                    <input type="hidden" name="_method" value="POST">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>CMS title
                                    <span class="asterisk red">*</span>
                                    </label>                                        
                                    <input required="" class="form-control" type="text" name="title" value="{{ $data['title'] }}"> 
                                    <div id="title_validate"></div>
                                </div>
                            
                                <div class="form-group ">
                                    <label>Description
                                    <span class="asterisk red">*</span>
                                    </label>
                                    <textarea required="" id="description" name="description" rows="10" cols="80">{{ $data['description'] }}</textarea>
                                    <div id="description_validate"></div>
                                </div>
                            
                                <div class="form-group ">
                                    <label>SEO Meta Title
                                    <span class="asterisk red">*</span>
                                    </label>                                        
                                    <input required="" class="form-control" type="text" name="seo_meta_title" accept="image/*" value="{{ $data['seo_meta_title'] }}"> 
                                    <div id="seo_meta_title_validate"></div>
                                </div>
                            
                                <div class="form-group ">
                                    <label>SEO Meta Description
                                    <span class="asterisk red">*</span>
                                    </label>                                        
                                    <input required="" class="form-control" type="text" name="seo_meta_description" accept="image/*" value="{{ $data['seo_meta_description'] }}"> 
                                    <div id="seo_meta_description_validate"></div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-danger" type="button" onclick="window.location = '{{ url('admin/cms') }}';">Cancel
                        </button>
                    </div>
                </form>
                 </div>


            </div>
        </div>
    </div>
</section>
<!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script src="{{ url('public/admin/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/admin/cms/edit.js') }}"></script>
@endpush