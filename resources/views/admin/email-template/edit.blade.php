@extends('admin.layout.app_with_login')
@section('title','Edit Email')
@section('content')
<!-- 
View File for  Edit Email
@package    Laravel
@subpackage View
@since      1.0
 -->
 <!-- Start Content Body -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="">
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
                <div class="path_link">
                <a href="{{ url('admin/email-template') }}" class="parent_page">Email Template > </a><a href="#" class="current_page">Edit</a>
                </div>

                 <div class="">
                    <form role="form" action="{{ url('admin/email-template/edit/'.$data['id']) }}" method="post" id="logo-form" novalidate="" enctype="multipart/form-data">
                    {{ @csrf_field() }}
                    <input type="hidden" name="_method" value="POST">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Title
                                    <span class="asterisk red">*</span>
                                    </label>
                                    <input required="" class="form-control" value="{{ $data['title'] }}" type="text" name="title" readonly=""> 
                                    <div id="title_validate"></div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                         <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Subject
                                    <span class="asterisk red">*</span>
                                    </label>
                                    <input required="" class="form-control" value="{{ $data['subject'] }}" type="text" name="subject"> 
                                    <div id="subject_validate"></div>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label>Content
                                    <span class="asterisk red">*</span>
                                    </label>
                                   <textarea required="" id="content" name="content" rows="10" cols="80">{{ $data['content'] }}</textarea>
                                    <div id="content_validate"></div>
                                    @if(!empty($data['parameters']))
                                    <br><strong><p>Note : Please do not remove {{ $data['parameters'] }}</p></strong>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>

                    <div class="box-footer text-center">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-danger" type="button" onclick="window.location = '{{ url('admin/email-template') }}';">Cancel
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
<!-- Include this Page JS-->
<script src="{{ url('public/admin/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/admin/email/edit.js') }}"></script>
<script type="text/javascript">
    CKEDITOR.getFullHTMLContent = function(editor){
    var cnt = "";
    editor.once('contentPreview', function(e){
      cnt = e.data.dataValue;
      return false;
    });
    editor.execCommand('preview');
    
    return cnt;
  }
  config.allowedContent = true;
</script>
@endpush