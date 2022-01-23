@extends('admin.layout.app_with_login')
@section('title','Email Template')
@section('content')
    <!-- Content Body -->
    <section class="content">
        <div class="row new_added_div">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box-header">
                    <h3 class="box-title">Email Template</h3>
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

                </div>
                <div class="row">
                    <div class="box-header">
                        <div class="row">
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-hover" id="datatableData" width="100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Content Body -->
@endsection
@push('custom-scripts')
<script type="text/javascript" src="{{ url('/public/js/admin/email/list.js') }}"></script>
<!-- Include this Page JS -->
@endpush
@push('custom-styles')

<style type="text/css">
    .mt-10{
        margin-top: 20px;
    }
</style>
@endpush