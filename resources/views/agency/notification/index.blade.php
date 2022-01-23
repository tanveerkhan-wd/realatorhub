@extends('agency.layout.app_with_login')
@section('title','Agent List')
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
        <a href="{{url('agency/agent')}}" class="current_page">Notifications</a>
    </div>
    <div class="header_bar">
      <div class="">
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover" id="datatableData">
          <thead>
              <tr>
                  <th>Notification List</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
          </tbody>
      </table>
    </div>
      
</div>
<!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<style type="text/css">
  .form-group.auto-width .btn-primary{
    width: 190px;
  }
</style>
@endpush
@push('custom-scripts')
<script type="text/javascript">
  var base_url='<?php echo url(''); ?>'
</script>
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/agency/notification/list.js') }}"></script>
<script type="text/javascript">
  setTimeout(function(){
    console.log($('.datatableData_info'));
    $('.datatableData_info').parent().parent().addClass('testClass');
  },2500)
  

  
</script>
@endpush