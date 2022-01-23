@extends('admin.layout.app_with_login')
@section('title','Blog Comment')
@section('content')	
<!-- 
View File for  Comment List and View Comment
@package    Laravel
@subpackage View
@since      1.0
 -->    
<!-- Content Header (Page header) -->
<!-- <section class="content-header">
  <h1>
    Blog Comment
  </h1>
  <ol class="breadcrumb">
    <li class=""><a href="{{ url('admin/home') }}"><i class="fa fa-dashboard"></i> Dashboard></a></li>
    <li class="active"><i class="fa fa-picture-o"></i> Blog Comment</li>
  </ol>
</section> -->
<!-- End Content Header -->
<!-- Start Content Body -->
<section class="content">
    <!-- <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary box-solid">
                <div class="box-header">
                    <h3 class="box-title">Search</h3>
                </div>                   
                <div class="box-body">
                    <div class="row">
                        <form id="search-form">
                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <div class="form-group">
                                <input class="form-control" placeholder="Search title" type="text" name="title" id="title">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-4">
                              <div class="form-group ">                                     
                                  <select name="status" class="form-control select2" required="">
                                      <option value="">All</option>
                                      <option>Approved</option>
                                      <option>Disapproved</option>
                                  </select>
                                   <div id="status_validate"></div>
                              </div>
                          </div>
                          </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row">
        <div class="col-xs-12">
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
                <div class="box-header">
                    <div class="row">
                      <form id="search-form">
                          <div class="col-xs-12 col-md-3">
                              <h3 class="box-title">Blog Comment</h3>
                          </div>
                          <div class="col-xs-12 col-md-3"></div>
                          <div class="col-xs-12 col-md-3">
                              <div class="serach-button-container">
                                  <div class="form-group ">                                     
                                      <select name="status" class="form-control select2" required="">
                                          <option value="">All</option>
                                          <option>Approved</option>
                                          <option>Disapproved</option>
                                      </select>
                                      <div id="status_validate"></div>
                                  </div>
                                </div>
                          </div>
                          <div class="col-xs-12 col-md-3">
                              <div class="serach-button-container">
                                  <div class="form-group">
                                      <input class="form-control" placeholder="Search title" type="text" name="title" id="title">
                                  </div>
                              </div>
                          </div>
                      </form>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-hover" id="datatableData">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Blog Title</th>
                                  <th>User Name</th>
                                  <th>Status</th>
                                  <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Content Body -->

<!-- Modal -->
<div class="modal fade add_offer_poppup" id="incomplete_view_profile" tabindex="-1" role="dialog"
   aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

</div>
<!-- modal_end -->

@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/admin/blog_post/comment.js') }}"></script>
@endpush