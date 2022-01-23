@extends('admin.layout.app_with_login')
@section('title','Subscriptions')
@section('content')
    <!-- Content Body -->
    <!-- Content Body -->
    <section class="content">
        <div class="row new_added_div">
            <!-- left column -->
            <div class="col-md-12">
                <div class="path_link">
                    <a href="{{route('plans')}}" class="parent_page">Subscriptions > </a><a href="#" class="current_page">Add New Plan</a>
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
                                <form id="add-plan-form" name="add-plan-form"  enctype="multipart/form-data">
                                    {{ @csrf_field() }}
                                    <div class="row mt-10">
                                        <div class="form-group">
                                            <label>Plan Name<span class="asterisk red">*</span></label>
                                            <input type="text" class="form-control" placeholder="enter name" name="plan_name" value="">
                                        </div>
                                        <div class="form-group">
                                            <label>Fixed Price<span class="asterisk red">*</span></label>
                                            <input type="number" min="1"  class="form-control" placeholder="100" name="monthly_price" value="">
                                        </div>
                                        <div class="form-group">
                                            <label>Fixed Price Allowed Agents<span class="asterisk red">*</span></label>
                                            <input type="number" min="1" class="form-control" placeholder="100" name="no_of_agent" value="">
                                        </div>
                                        <div class="form-group">
                                            <label>Additional Agents Allowed<span class="asterisk red">*</span></label>
                                            <input type="number" min="1" class="form-control" placeholder="100" name="additional_agent" value="">
                                        </div>
                                        <div class="form-group">
                                            <label>Price Per Additional Agent<span class="asterisk red">*</span></label>
                                            <input type="number" min="1" class="form-control" placeholder="100" name="additional_agent_per_rate" value="">
                                        </div>
                                        <div class="form-group">
                                            <label>About The Plan<span class="asterisk red">*</span></label>
                                            <textarea class="form-control about_plan" id="about_plan" name="about_plan"
                                                      placeholder="Type here…" rows="5"></textarea>
                                        </div>

                                        <div class="form-group text-center">
                                            <input type="submit" value="Save" name="save" class="btn btn-primary save_plan" id="save_plan">
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
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/admin/subscription/add_plan.js') }}"></script>
@endpush
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style type="text/css">
    .mt-10 {
        margin-top: 20px;
    }
</style>
@endpush