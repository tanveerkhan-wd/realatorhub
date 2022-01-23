@extends('admin.layout.app_with_login')
@section('title','Add Blog Category')
@section('content')	
<!-- 
View File for  Add Blog Category
@package    Laravel
@subpackage View
@since      1.0
 -->
 <!-- Content Body -->
    <section class="content">
        <div class="row new_added_div">
            <!-- left column -->
            <div class="col-md-12">
                <div class="path_link">
                    <a href="{{url('admin/blog-post-category')}}" class="parent_page">Blog Category > </a><a href="#" class="current_page">Add Blog Category</a>
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
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <!-- Start new plan Sectin -->
                                <form role="form" action="{{ url('admin/blog-post-category/add') }}" method="post" id="logo-form" multipart-form-data novalidate="" enctype="multipart/form-data">
                                    {{ @csrf_field() }}
                                    <div class="row mt-10">
                                        <div class="form-group ">
                                            <label>Title
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <input required="" class="form-control" type="text" name="title"> 
                                            <div id="title_validate"></div>
                                        </div>
                                    
                                        <div class="form-group ">
                                            <label>Status
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <select name="status" class="form-control select2" required="">
                                                <option value="">Please Select</option>
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                             <div id="status_validate"></div>
                                        </div>

                                        <div class="form-group text-center">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                            <button class="btn btn-danger" type="button" onclick="window.location = '{{ url('admin/blog-post-category') }}';">Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- End new plan Section -->
                            </div>
                            <div class="col-lg-2"></div>

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
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/admin/blog_category/add.js') }}"></script>
@endpush