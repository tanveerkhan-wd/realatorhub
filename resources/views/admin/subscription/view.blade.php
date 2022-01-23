@extends('admin.layout.app_with_login')
@section('title','View Subscriptions')
@section('content')
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
                        <h3 class="box-title">View Subscription Plan</h3>
                        <button class="btn btn-danger pull-right" type="button"
                                onclick="window.location = '{{ url('admin/subscriptions') }}';">Back
                        </button>
                    </div>

                    <div class="box-body">
                        <!-- Start new plan Sectin -->
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                        <form id="add-plan-form" name="add-plan-form"  enctype="multipart/form-data">
                            {{ @csrf_field() }}
                            {{--<div class="row mt-10">--}}
                                <div class="form-group">
                                    <label>Plan Name<span class="asterisk red">*</span></label>
                                    <input type="text" class="form-control" placeholder="enter name" readonly="readonly" name="plan_name" value="{{$plan->plan_name}}">
                                </div>
                                <div class="form-group">
                                    <label>Fixed Price<span class="asterisk red">*</span></label>
                                    <input type="number" min="1"  class="form-control" placeholder="100" readonly="readonly" name="monthly_price" value="{{$plan->monthly_price}}">
                                </div>
                                <div class="form-group">
                                    <label>Fixed Price Allowed Agents<span class="asterisk red">*</span></label>
                                    <input type="number" min="1" class="form-control" placeholder="100" readonly="readonly" name="one_to_one_session_hours"  value="{{$plan->no_of_agent}}">
                                </div>
                                <div class="form-group">
                                    <label>Additional Agents Allowed<span class="asterisk red">*</span></label>
                                    <input type="number" min="1" class="form-control" placeholder="100" readonly="readonly"
                                           name="additional_agent"  value="{{$plan->additional_agent}}">
                                </div>
                                <div class="form-group">
                                    <label>Price Per Additional Agent<span class="asterisk red">*</span></label>
                                    <input type="number" min="1" class="form-control" placeholder="100" readonly="readonly"
                                           name="additional_agent_per_rate" value="{{$plan->additional_agent_per_rate}}">
                                </div>
                                <div class="form-group">
                                    <label>About The Plan<span class="asterisk red">*</span></label>
                                    <textarea class="form-control about_plan" id="about_plan" name="about_plan" readonly="readonly"
                                              placeholder="Type here…" rows="5" >{{ $plan->description}}</textarea>
                                </div>

                            </div>
                        </div>
                        </form>
                        <!-- End new plan Section -->
                    </div>

                    <div class="box-footer">

                    </div>


                </div>
            </div>
        </div>
    </section>
    <!-- End Content Body -->
@endsection
@push('custom-scripts')
<!-- Include this Page JS -->
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