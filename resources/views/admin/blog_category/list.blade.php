@extends('admin.layout.app_with_login')
@section('title','Blog Category List')
@section('content')
<!-- 
View File for  List Blog Category
@package    Laravel
@subpackage View
@since      1.0
 -->
<section class="content">
    <div class="row new_added_div">
        <!-- left column -->
        <div class="col-md-12">
            
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

            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="header_bar">
            <div class="">
                <form id="search-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="box-header">
                                <h3 class="box-title current_page">Blog Category List</h3>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group "> 
                                <!-- <label>Status</label> -->
                                <select name="status" class="form-control select2" required="">
                                        <option value="">All</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                <div id="status_validate"></div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group "> 
                                <input class="form-control" placeholder="Search title" type="text" name="title" id="title">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4">
                            <div class="form-group "> 
                                <a href="{{ url('admin/blog-post-category/add') }}" class="btn btn-primary add-button" style="width: 100%;">Add</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" id="datatableData">
                  <thead>
                      <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
          </table>
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
<script type="text/javascript" src="{{ url('public/js/admin/blog_category/list.js') }}"></script>
<script type="text/javascript">
  setTimeout(function(){
    console.log($('.datatableData_info'));
    $('.datatableData_info').parent().parent().addClass('testClass');
  },2500)
  

  
</script>
@endpush