@extends('admin.layout.app_with_login')
@section('title','Edit Blog')
@section('content')    
<!-- 
View File for  Edit Blog
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
                    <a href="{{url('admin/blog-post')}}" class="parent_page">Blog List > </a><a href="#" class="current_page">Edit Blog</a>
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
                                <form role="form" action="{{ url('admin/blog-post/edit/'.$data['id']) }}" method="post" id="logo-form"  enctype="multipart/form-data" data-parsley-validate>
                                    {{ @csrf_field() }}
                                    <div class="row mt-10">
                                        <div class="form-group ">
                                            <label>Title
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <input required="" value="{{ $data['title'] }}" class="form-control" type="text" name="title"> 
                                            <div id="title_validate"></div>
                                        </div>
                                    
                                        <div class="form-group ">
                                            <label>Blog Image
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <input onchange="readURL(this);" class="form-control" type="file" name="blog_image" id="blog_image" data-id="blog_image" accept="image/*"> 
                                            <div class="errorImage"></div>
                                            <div id="blog_image_validate"></div>
                                            <input type="hidden" name="old_image" value="{{ $data['image'] }}" >
                                            <img id="blog_image_img_url" class="old_image_pre mt-10" src="{{ url('public/uploads/blog_image/'.$data['image']) }}" width="100px" height="auto">

                                            
                                        </div>
                                    
                                        <div class="form-group ">
                                            <label>Blog Category
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <select multiple="multiple" id="blog_category" class="select2 form-control" name="blog_category[]" data-parsley-required
                                             data-parsley-errors-container="#blog-category-error-parsley">
                                                @foreach($blog_category as $key=>$val)
                                                    <option value="{{ $val['id'] }}" @if(in_array($val['id'],$blog_category_selected))
                                                    selected="selected" 
                                                    @endif>{{ $val['title'] }}</option>
                                                @endforeach
                                            </select>
                                             <span class="help-block error">
                                                <strong id="blog-category-error-parsley"></strong>
                                             </span>
                                        </div>
                                    
                                        <div class="form-group ">
                                            <label>Blog Description
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <textarea class="form-control" name="descripition" id="descripition">{{ $data['description'] }}</textarea>
                                        </div>
                                    
                                        <div class="form-group ">
                                            <label>SEO Title
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <input required="" class="form-control" value="{{ $data['seo_meta_title'] }}"  type="text" name="seo_title"> 
                                            <div id="seo_title_validate"></div>
                                        </div>
                                    
                                        <div class="form-group ">
                                            <label>SEO Description
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <input required="" class="form-control" value="{{ $data['seo_meta_description'] }}" type="text" name="seo_description"> 
                                            <div id="seo_description_validate"></div>
                                        </div>
                                    
                                        <div class="form-group ">
                                            <label>Slug
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <input readonly="" class="form-control" value="{{ $data['slug'] }}" type="text"> 
                                        </div>
                                    
                                        <div class="form-group ">
                                            <label>Status
                                            <span class="asterisk red">*</span>
                                            </label>
                                            <select name="status" class="form-control select2" data-parsley-required
                                             data-parsley-errors-container="#status-error-parsley">>
                                                <option value="">Please Select</option>
                                                 <option @if($data['status'] == '1')
                                                    selected="selected" 
                                                    @endif value="1">Active</option>
                                                    <option @if($data['status'] == '0')
                                                    selected="selected" 
                                                    @endif value="0">Inactive</option>
                                            </select>
                                            <span class="help-block error">
                                                <strong id="status-error-parsley"></strong>
                                             </span>
                                             <div id="status_validate"></div>
                                        </div>

                                        <div class="form-group text-center">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                            <button class="btn btn-danger" type="button" onclick="window.location = '{{ url('admin/blog-post') }}';">Cancel
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
<style type="text/css">
    ul.select2-selection__rendered {
    padding-right: 30px !important;
}

ul.select2-selection__rendered:after {
    content: "";
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border-top: 5px solid #333;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
}
</style>
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script src="{{ url('public/js') }}/parsley.min.js"></script>
<script src="{{ url('public/admin/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/admin/blog_post/edit.js') }}"></script>
<script type="text/javascript">
    function readURL(input) {
    debugger;
      if (input.files && input.files[0]) {
        var id=$(input).data("id");
      console.log(id);
          var reader = new FileReader();

          reader.onload = function (e) {
              $('#'+id+'_img_url').attr('src', e.target.result);

          }

          reader.readAsDataURL(input.files[0]);
      }
  }
</script>
@endpush