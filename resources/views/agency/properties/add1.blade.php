@extends('agency.layout.app_with_login')
@section('title','Property Add')
@section('content')
<style>
    .property_ul li{
        padding: 6px 20px;
    } 
    .tab-content{
        margin-top: 20px;
    }
    form .help-block {
        color: #ff0000;
    }
    .gallery img{
        height:50px;
        width:50px;
    }

</style>
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
<ul class="nav nav-tabs text-center property_ul">
    <li class="active"><a data-toggle="tab" href="#purpose">Purpose & Property Type</a></li>
    <li><a data-toggle="tab" href="#details">Details</a></li>
    <li><a data-toggle="tab" href="#address">Address & Description</a></li>
    <li><a data-toggle="tab" href="#images">Images</a></li>
    <li><a data-toggle="tab" href="#seo_setting">SEO Settings</a></li>
</ul>

<div class="tab-content">
    <div id="purpose" class="tab-pane fade in active">
        <form method="post" action="" id="purpose_form">
            <div class="form-group">
                <label>Purpose</label>
                <div class="form-row">
                    <div class="col-6">
                        <select class="form-control dropdown_control country_code" name="purpose" id="pt_purpose" required="">
                            <option value="">Select Purpose</option>
                            <option value="1" @if(!empty(Session::get('property_purpose')) && Session::get('property_purpose')=='1') selected="selected"@endif>Buy</option>
                            <option value="2" @if(!empty(Session::get('property_purpose')) && Session::get('property_purpose')=='2') selected="selected"@endif>Rent</option>
                        </select>
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Property Type</label>
                <div class="form-row">
                    <div class="col-6">
                        <select class="form-control dropdown_control country_code" name="type" id="pt_type" required="">
                            <option value="">Select Type</option>
                            <option value="1" @if(!empty(Session::get('property_type')) && Session::get('property_type')=='1') selected="selected"@endif>Single Homes</option>
                            <option value="2" @if(!empty(Session::get('property_type')) && Session::get('property_type')=='2') selected="selected"@endif>Multifamily</option>
                            <option value="3" @if(!empty(Session::get('property_type')) && Session::get('property_type')=='3') selected="selected"@endif>Commercial</option>
                            <option value="4" @if(!empty(Session::get('property_type')) && Session::get('property_type')=='4') selected="selected"@endif>Industrial</option>
                            <option value="5" @if(!empty(Session::get('property_type')) && Session::get('property_type')=='5') selected="selected"@endif>Lot</option>
                        </select>
                        <div id="pt_type_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Agent</label>
                <div class="form-row">
                    <div class="col-6">
                        <select class="form-control dropdown_control country_code" name="agent" id="pt_agent" required="">
                            <option value="">Select Agent Name</option>
                            @foreach($agents as $key=>$value)
                            <option value="{{$value->id}}" @if(!empty(Session::get('property_agent')) && Session::get('property_agent')==$value->id) selected="selected"@endif>{{$value->first_name}}</option>
                            @endforeach
                        </select>
                        <div id="pt_agent_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <a data-toggle="tab" href="#details" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="purpose_form_btn">Next</a>
            </div>
        </form>
    </div>
    <div id="details" class="tab-pane fade">
        <form id="details_form" method="POST">
            @if(Session::get('property_type')==1)
            <div class="form-group">
                <label>Beds</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="beds">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Baths</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="baths">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Sq. Ft.</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="sqft">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Price</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="price" placeholder="$ 000000">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <div class="col-6">
                        <label class="radio-inline"><input type="radio" name="occupied">Occupied</label>
                        <label class="radio-inline"><input type="radio" name="occupied">Vacant</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>HOA if applicable</label>
                <div class="form-row">
                    <div class="col-6">
                        <select class="form-control dropdown_control country_code" name="hoa" id="pt_hoa">
                            <option value="">Select Hoa</option>
                            <option value="0">Yes</option>
                            <option value="1">No</option>
                        </select>
                        <div id="pt_agent_validate"></div>
                    </div>
                </div>
            </div>
            @endif

            @if(Session::get('property_type')==2)
            <div class="form-group">
                <label>Unit Amount</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="unitamount" id="unitamount">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Total Living Sqft</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="total_living_sqft">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Lot Sqft</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="lot_sqft">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Annual Tax</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="annual_tax">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            @endif

            @if(Session::get('property_type')==3)
            <div class="form-group">
                <label>Unit Amount</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="unitamount_commercial" id="unitamount_commercial">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Total Commercial Space</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="total_commercial_space">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Lot Sqft</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="lot_sqft_commercial">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Annual Tax</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="annual_tax_commercial">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Zoning</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="zoning_commercial">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            @endif

            @if(Session::get('property_type')==4)
            <div class="form-group">
                <label>Total Industrial Space</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="total_industrial_space">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Lot Sqft</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="lot_sqft_industrial">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Annual Tax</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="annual_tax_industrail">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Zoning</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="zoning_industrail">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            @endif
            @if(Session::get('property_type')==4)
            <div class="form-group">
                <label>Lot Size Sqft</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="lot_sqft_lot">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Annual Tax</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="annual_tax_lot">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Zoning</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="zoning_lot">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            @endif
            <div class="form-group text-center">
                <a data-toggle="tab" href="#purpose" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="prv_details_btn">Previous</a>
            </div>
            <div class="form-group text-center">
                <a data-toggle="tab" href="#address" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="nxt_details_btn">Next</a>
            </div>
        </form>
    </div>
    <div id="address" class="tab-pane fade">
        <form id="address_form" method="POST">
            <div class="form-group">
                <div class="form-group">
                    <label for="address_address">Address</label>
                    <input type="text" id="address-input" name="address_address" class="form-control map-input">
                    <input type="hidden" name="address_latitude" id="address-latitude" value="0" />
                    <input type="hidden" name="address_longitude" id="address-longitude" value="0" />
                </div>
                <div id="address-map-container" style="width:100%;height:400px; ">
                    <div style="width: 100%; height: 100%" id="address-map"></div>
                </div>
                <!--            <label>Address</label>
                            <div class="form-row">
                                <div class="col-6">
                                    <input type="text" class="form-control" name="address">
                                    <div id="pt_purpose_validate"></div>
                                </div>
                            </div>-->
            </div>
            <div class="form-group">
                <label>Description</label>
                <div class="form-row">
                    <div class="col-6">
                        <textarea class="form-control" name="description"></textarea>
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <a data-toggle="tab" href="#details" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="prv_details_btn">Previous</a>
            </div>
            <div class="form-group text-center">
                <a data-toggle="tab" href="#images" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="nxt_details_btn">Next</a>
            </div>
        </form>
    </div>
    <div id="images" class="tab-pane fade">
        <form id="images_form" method="POST" enctype="multipart/form-data" action="{{route('agency.property.store')}}">
            {{ @csrf_field() }}
            <div class="form-group">
                <label for="agency_logo">Feature Image</label>
                <div class="upload-file-group">
                    <div class="choose_imd_box text-center">
                        <?php if (isset($agency_data->agency->agency_logo) && !empty($agency_data->agency->agency_logo)) { ?>
                            <img src="{{ url('public/uploads/profile_photos').'/'.$agency_data->agency->agency_logo }}" class="" id="agency_logo_image"><br>
                        <?php } else { ?> 
                            <div class="text-center">
                                <img src="{{ url('public/assets/')}}/images/ic_sad_emoji.png" id="agency_logo_image"><br>
                            </div>
                        <?php } ?>
                        <!-- <button class="theme-btn btn-color btn-text btn-size small_btn">Change</button> -->
                    </div>

                    <p class="file_upload_btn btn-color btn-text">
                        <input type="file" name="main_image" class="agency_logo file_control" id="upload_logo" onchange="readURL(this);">
                        Choose
                    </p>
                </div>
                <div id="agency_logo_validate"></div>
            </div>
            <div class="form-group">
                <label for="agency_logo">Multiple Images</label>
                <div class="upload-file-group">
                    <div class="choose_imd_box text-center">
                        <div class="gallery"></div>
                        <?php if (isset($agency_data->agency->agency_logo) && !empty($agency_data->agency->agency_logo)) { ?>
                            <img src="{{ url('public/uploads/profile_photos').'/'.$agency_data->agency->agency_logo }}" class="" id="agency_logo_image"><br>
                        <?php } else { ?> 
                            <div class="text-center">
                                <img src="{{ url('public/assets/')}}/images/ic_sad_emoji.png" id="multiple_image"><br>
                            </div>
                        <?php } ?>
                        <!-- <button class="theme-btn btn-color btn-text btn-size small_btn">Change</button> -->
                    </div>

                    <p class="file_upload_btn btn-color btn-text">
                        <input type="file" name="other_image[]" class="agency_logo file_control" id="gallery-photo-add" multiple>
                        Choose
                    </p>

                </div>
                <div id="agency_logo_validate"></div>
            </div>
            <div class="form-group text-center">
                <a data-toggle="tab" href="#address" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="prv_img_btn">Previous</a>
            </div>
            <div class="form-group text-center">
                <a data-toggle="tab" href="#seo_setting" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="nxt_img_btn">Next</a>
            </div>
        </form>
    </div>

    <div id="seo_setting" class="tab-pane fade @if(isset($images_add) == 'yes') active show  @endif in">
        <form id="seo_form" method="POST">
            <div class="form-group">
                <label>Title</label>
                <div class="form-row">
                    <div class="col-6">
                        <input type="text" class="form-control" name="seo_tiltle">
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <div class="form-row">
                    <div class="col-6">
                        <textarea class="form-control" name="seo_description"></textarea>
                        <div id="pt_purpose_validate"></div>
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                <a data-toggle="tab" href="#images" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="prv_seo_btn">Previous</a>
            </div>
            <div class="form-group text-center">
                <a href="#" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="add_seo_btn">Add</a>
            </div>
        </form>
    </div>

</div>
@endsection
@push('custom-scripts')

<script src="{{ url('public/admin/bower_components/ckeditor/ckeditor.js') }}"></script>
<!-- Include this Page Js -->
<script type="text/javascript">
                            $(function() {
                                // Multiple images preview in browser
                                var imagesPreview = function(input, placeToInsertImagePreview) {

                                    if (input.files) {
                                        var filesAmount = input.files.length;
                                        for (i = 0; i < filesAmount; i++) {
                                            var reader = new FileReader();
                                            reader.onload = function(event) {
                                                $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                                            }

                                            reader.readAsDataURL(input.files[i]);
                                        }
                                    }

                                };
                                $('#gallery-photo-add').on('change', function() {
                                    imagesPreview(this, 'div.gallery');
                                    $('#multiple_image').hide();
                                });
                            });
                            $(function() {
                                // Replace the <textarea id="editor1"> with a CKEditor
                                // instance, using default configuration.
                                CKEDITOR.replace('description')
                                //bootstrap WYSIHTML5 - text editor   
                            })
                            CKEDITOR.getFullHTMLContent = function(editor) {
                                var cnt = "";
                                editor.once('contentPreview', function(e) {
                                    cnt = e.data.dataValue;
                                    return false;
                                });
                                editor.execCommand('preview');
                                return cnt;
                            }
                            //config.allowedContent = true;
                            var base_url_route = '<?php echo url(''); ?>';
                            function readURL(input) {
                                if (input.files && input.files[0]) {
                                    var reader = new FileReader();
                                    reader.onload = function(e) {
                                        $('#agency_logo_image').attr('src', e.target.result);
                                    }

                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                            $("#purpose_form").validate({
                                ignore: [],
                                rules: {
                                    purpose: {required: true},
                                    type: {required: true},
                                    agent: {required: true},
                                },
                                messages: {
                                    purpose: {required: 'Please Select Purpose'},
                                    type: {required: 'Please Select Type'},
                                    agent: {required: 'Please Select Agent'},
                                },
                                errorElement: "em",
                                errorPlacement: function(error, element) {
                                    error.addClass("help-block");
                                    error.insertAfter(element);
                                },
                                highlight: function(element, errorClass, validClass) {
                                    $(element).parent("div").addClass("has-error").removeClass("has-success");
                                },
                                unhighlight: function(element, errorClass, validClass) {
                                    $(element).parent("div").addClass("has-success").removeClass("has-error");
                                }
                            });
                            $(document).ready(function() {
                                $('#purpose_form_btn').click(function() {
                                    if ($('#purpose_form').valid()) {
                                        var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
                                        $.ajax({
                                            type: "POST",
                                            url: "{{route('agency.property.store')}}",
                                            data: {'form': $("#purpose_form").serialize(), 'formname': 'purpose', _token: CSRF_TOKEN},
                                            success: function(msg) {
                                                if (msg == 'true') {
                                                    return true;
                                                } else {
                                                    return false;
                                                }
                                            }
                                        });
                                    } else {
                                        return false;
                                    }
                                });
                                $('#unitamount').change(function() {
                                    $('.appended_div').remove();
                                    var unit = $(this).val();
                                    for (i = 1; i <= unit; i++) {
                                        $(this).after('<div class="form-group appended_div"><label>Unit' + i + '</label><br><label>Baths</label><div class="form-row"><div class="col-6"><input type="text" class="form-control" name="baths' + i + '"></div></div><label>Beds</label><div class="form-row"><div class="col-6"><input type="text" class="form-control" name="beds' + i + '"></div></div></div></div>');
                                    }
                                });
                                $('#unitamount_commercial').change(function() {
                                    var unitcom = $(this).val();
                                    for (i = 1; i <= unitcom; i++) {
                                        $(this).after('<div class="form-group"><label>Unit' + i + '</label><br><label>Sqft</label><div class="form-row"><div class="col-6"><input type="text" class="form-control" name="sqft' + i + '"></div></div><label>Baths</label><div class="form-row"><div class="col-6"><input type="text" class="form-control" name="baths' + i + '"></div></div></div></div>');
                                    }
                                });
                                $('#nxt_details_btn').click(function() {
                                    var CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
                                    $.ajax({
                                        type: "POST",
                                        url: "{{route('agency.property.store')}}",
                                        data: {'form': $("#details_form").serialize(), 'formname': 'details', _token: CSRF_TOKEN},
                                        success: function(msg) {
                                            if (msg == 'true') {
                                                //return true;
                                            } else {
                                                return false;
                                            }
                                        }
                                    });
                                });

                                $('#nxt_img_btn').click(function() {
                                    $('#images_form').submit();
                                    //$('.nav-tabs a[href="#seo_setting"]').tab('show');
                                });
                            });</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjUOEq2i7-I5kV-L0W3XJiU8ZXE-ahSTc&libraries=places&callback=initialize" async defer></script>
<script src="{{url('public/js')}}/mapInput.js"></script>
@endpush