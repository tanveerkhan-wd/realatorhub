@extends('agent.layout.app_with_login')
@section('title','Property Add')
@section('content')
<style>
    form .help-block {
        color: #ff0000;
    }
    .gallery img{
        height:50px;
        width:50px;
    }
    .disabled a{
        cursor: not-allowed;
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
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">	
            <form method="POST" id="property_form" action="{{route('agent.property.store')}}" enctype="multipart/form-data">
                {{ @csrf_field() }}
                <ul class="nav nav-tabs text-center property_ul step_tab">
                    <li class="active"><a data-toggle="tab" href="#purpose">Purpose & Property Type</a></li>
                    <li class="disabled property_tab2"><a data-toggle="tab" href="#details">Details</a></li>
                    <li class="disabled property_tab3"><a data-toggle="tab" href="#address">Address & Description</a></li>
                    <li class="disabled property_tab4"><a data-toggle="tab" href="#images">Images & Videos</a></li>
                    <li class="disabled property_tab5"><a data-toggle="tab" href="#seo_setting">SEO Settings</a></li>
                </ul>
                <div class="tab-content step_tab_content">
                    <div id="purpose" class="tab-pane fade in active">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>Select Purpose</label>
                                    <select class="form-control dropdown_control country_code" name="purpose" id="pt_purpose" required="">
                                        <option value="">Select Purpose</option>
                                        <option value="1">Buy</option>
                                        <option value="2">Rent</option>
                                    </select>
                                    <div id="pt_purpose_validate" class="validation_msg"></div>
                                </div>
                                <div class="form-group">
                                    <label>Select Property Type</label>
                                    <select class="form-control dropdown_control country_code" name="type" id="pt_type" required="">
                                        <option value="">Select Type</option>
                                        <option value="1">Single Homes</option>
                                        <option value="2">Multifamily</option>
                                        <option value="3">Commercial</option>
                                        <option value="4">Industrial</option>
                                        <option value="5">Lot</option>
                                    </select>
                                    <div id="pt_type_validate" class="validation_msg"></div>
                                </div>
                                <div class="form-group">
                                    <label>Select Agent Name</label>
                                    <select class="form-control dropdown_control country_code" name="agent" id="pt_agent" required="">
                                        <option value="{{Auth::user()->id}}" selected="selected">{{Auth::user()->first_name}}</option>
                                    </select>
                                    <input type="hidden" name="agency_id" value="{{$agency_id->agency_id}}">
                                    <input type="hidden" name="agent" value="{{Auth::user()->id}}">
                                    <div id="pt_agent_validate" class="validation_msg"></div>
                                </div>
                                <div class="form-group text-center">
                                    <a data-toggle="tab" href="#details" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="purpose_form_btn">Next</a>
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                    </div>
                    <div id="details" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div id="single_family">
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Beds</label>
                                                <input type="number" class="form-control" name="beds" id="single_beds">
                                                <div id="pt_single_beds_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Baths</label>
                                                <input type="number" class="form-control" name="baths" id="single_baths">
                                                <div id="pt_single_baths_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Sq. Ft.</label>
                                                <input type="number" class="form-control" name="sqft" id="single_sqft">
                                                <div id="pt_single_sqft_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label>Price</label>
                                            <input type="number" class="form-control doller_control" name="price" placeholder="000000" id="single_price">
                                            <div id="pt_single_price_validate" class="validation_msg"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check custom_check_div w-auto">
                                            <input class="form-check-input" type="radio" name="occupied" checked id="" value="1">
                                            <label class="custom_radio"></label>
                                            <label class="form-check-label" for="">Occupied</label>
                                        </div>
                                        <div class="form-check custom_check_div w-auto">
                                            <input class="form-check-input" type="radio" name="occupied" id="" value="2">
                                            <label class="custom_radio"></label>
                                            <label class="form-check-label" for="">Vacant</label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>HOA if applicable</label>
                                        <select class="form-control dropdown_control country_code" name="hoa" id="single_hoa">
                                            <option value="">Select Hoa</option>
                                            <option value="0">Yes</option>
                                            <option value="1">No</option>
                                        </select>
                                        <div id="pt_single_hoa_validate" class="validation_msg"></div>
                                    </div>
                                </div>

                                <div id="multi_family">
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Unit Amount</label>
                                                <input type="number" class="form-control" name="unitamount" id="unitamount">
                                                <div id="pt_unitamount_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label>Total Living Sqft</label>
                                            <input type="number" class="form-control" name="total_living_sqft" id="multi_living_sqft">
                                            <div id="pt_multiliving_sqft_validate" class="validation_msg"></div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label>Lot Sqft</label>
                                                <input type="number" class="form-control" name="lot_sqft" id="multi_lot_sqft">
                                                <div id="pt_multi_lot_sqft_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label>Annual Tax</label>
                                            <input type="number" class="form-control doller_control" name="annual_tax" id="multi_annual_tax">
                                            <div id="pt_multi_annual_tax_validate" class="validation_msg"></div>     
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <label>Price</label>
                                            <input type="number" class="form-control doller_control" name="multi_price" placeholder="000000" id="multi_price">
                                            <div id="pt_multi_price_validate" class="validation_msg"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="commerical_family">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Unit Amount</label>
                                                <input type="number" class="form-control" name="unitamount_commercial" id="unitamount_commercial">
                                                <div id="pt_unit_amount_commercial_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Total Commercial Space</label>
                                            <input type="number" class="form-control" name="total_commercial_space" id="commercial_space">
                                            <div id="pt_commercial_space_validate" class="validation_msg"></div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Lot Sqft</label>
                                                    <input type="number" class="form-control" name="lot_sqft_commercial" id="commercial_lot_sqft">
                                                    <div id="pt_commercial_lot_sqft_validate" class="validation_msg"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Annual Tax</label>
                                                    <input type="number" class="form-control doller_control" name="annual_tax_commercial" id="commercial_annual_tax">
                                                    <div id="pt_commercial_annual_tax_validate" class="validation_msg"></div>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Zoning</label>
                                        <input type="text" class="form-control" name="zoning_commercial" id="commercial_zoning">
                                        <div id="pt_commercial_zoning_validate" class="validation_msg"></div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <label>Price</label>
                                            <input type="number" class="form-control doller_control" name="commercial_price" placeholder="000000" id="commercial_price">
                                            <div id="pt_commercial_price_validate" class="validation_msg"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="industrail_family">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Total Industrial Space</label>
                                                <input type="number" class="form-control" name="total_industrial_space" id="industrial_space">
                                                <div id="pt_industrial_space_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lot Sqft</label>
                                                <input type="number" class="form-control" name="lot_sqft_industrial" id="industrail_lot_sqft">
                                                <div id="pt_industrail_lot_sqft_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">   
                                            <div class="form-group">
                                                <label>Annual Tax</label>
                                                <input type="number" class="form-control doller_control" name="annual_tax_industrail" id="industrail_tax">
                                                <div id="pt_annual_industrail_tax_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"> 
                                            <div class="form-group">
                                                <label>Zoning</label>
                                                <input type="text" class="form-control" name="zoning_industrail" id="industrail_zoning">
                                                <div id="pt_industrail_zoning_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <label>Price</label>
                                            <input type="number" class="form-control doller_control" name="industrial_price" placeholder="000000" id="industrial_price">
                                            <div id="pt_industrial_price_validate" class="validation_msg"></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="lot_family">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Lot Size Sqft</label>
                                                <input type="number" class="form-control" name="lot_sqft_lot" id="lot_sqft">
                                                <div id="pt_lot_sqft_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Annual Tax</label>
                                                <input type="number" class="form-control doller_control" name="annual_tax_lot" id="lot_annual_tax">
                                                <div id="pt_lot_annual_tax_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Zoning</label>
                                        <input type="text" class="form-control" name="zoning_lot" id="lot_zoning">
                                        <div id="pt_lot_zoning_validate" class="validation_msg"></div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <label>Price</label>
                                            <input type="number" class="form-control doller_control" name="lot_price" placeholder="000000" id="lot_price">
                                            <div id="pt_lot_price_validate" class="validation_msg"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center two_btns">
                                    <a data-toggle="tab" href="#purpose" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="prv_details_btn">Previous</a>
                                    <a data-toggle="tab" href="#address" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="nxt_details_btn">Next</a>
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                    </div>
                    <div id="address" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <div class="form-group">
<!--                                        <label for="address_address">Address</label>
                                        <input type="text" id="address-input" name="address_address" class="form-control map-input">
                                        <div id="pt_address_validate"></div>-->
                                        <label for="address_address">Address</label><br>
                                        <small>You can enter address manually or select from google. You can also drag map pin to exact position.</small>
                                         <div class="form-group">
                                        <div class="form-check custom_check_div w-auto">
                                            <input class="form-check-input chk_radio" type="radio" name="address_type" checked id="" value="1">
                                            <label class="custom_radio"></label>
                                            <label class="form-check-label" for="">From Google</label>
                                        </div>
                                        <div class="form-check custom_check_div w-auto">
                                            <input class="form-check-input chk_radio" type="radio" name="address_type" id="" value="2">
                                            <label class="custom_radio"></label>
                                            <label class="form-check-label" for="">Manual</label>
                                        </div>
                                    </div>
                                        <div class="from_google_div">
                                            <div class="form-group">
                                                <input type="text" id="address-input" name="address_address" class="form-control map-input">
                                                <div id="pt_address_validate" class="validation_msg"></div>
                                            </div>
                                        </div>
                                        <div class="from_manual"></div>
                                        <input type="hidden" name="address_latitude" id="address-latitude" value="0" />
                                        <input type="hidden" name="address_longitude" id="address-longitude" value="0" />
                                        <input type="hidden" name="address_country" id="address-country" value="0" />
                                        <input type="hidden" name="address_state" id="address-state" value="0" />
                                        <input type="hidden" name="address_city" id="address-city" value="0" />
                                        <input type="hidden" name="address_zipcode" id="address-zipcode" value="0" />

                                        <div id="address-map-container" style="width:100%;height:400px; ">
                                            <div style="width: 100%; height: 100%" id="address-map"></div>
                                        </div>
                                    </div>
                                    <!--<small>You can enter address manually or select from google. You can also drag map pin to exact position.</small>-->
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description"></textarea>
                                    <div id="pt_desc_validate" class="validation_msg"></div>
                                </div>
                                <div class="form-group text-center two_btns">
                                    <a data-toggle="tab" href="#details" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="prv_address_btn">Previous</a>
                                    <a data-toggle="tab" href="#images" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="nxt_address_btn">Next</a>
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                    </div>
                    <div id="images" class="tab-pane fade">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label for="agency_logo" class="w-100">Feature Image</label>
                                    <div class="squar_file_box">
                                        <?php if (isset($agency_data->agency->agency_logo) && !empty($agency_data->agency->agency_logo)) { ?>
                                            <img src="{{ url('public/uploads/profile_photos').'/'.$agency_data->agency->agency_logo }}" class="" id="agency_logo_image"><br>
                                        <?php } else { ?> 
                                            <div class="text-center">
                                                <img src="{{ url('public/assets/')}}/images/add_img.png" id="agency_logo_image">
                                            </div>
                                        <?php } ?>

                                        <input type="file" name="main_image" class="agency_logo file_control" id="upload_logo" onchange="readURL(this);" accept="image/*">
                                    </div>
                                    <div id="agency_logo_validate" class="validation_msg"></div>
                                </div>
                                <div class="form-group">
                                    <label for="agency_logo">Multiple Images</label>
                                    <div class='multi_img_wrap'>
                                        <div class="squar_file_box_add clearfix">
                                            <button class="btn  img_add_more theme-btn btn-color small_btn" type="button">Add Image</button>
                                            <div class="squar_file_box">
                                                 <input type="file" name="other_image[]" class="image_added agency_logo file_control" id="gallery-photo-add" accept="image/*" onchange="readURLIMG(this);">
                                                 <img src="{{ url('public/assets/')}}/images/add_img.png" id="agency_logo_image" class="preview_image">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="pt_logo_validate" class="validation_msg"></div>
                                </div>
                                <div class='form-group'>
                                    <label for="agency_logo">Videos</label>
                                    <div class='youtube_link_wrap'>
                                        <div class='row'>
                                            <div class=" col-8 col-md-10">
                                                <div class='row'>
                                                    <div class="form-group col-md-4">
                                                        <select class="form-control video_type_sel dropdown_control" name="youtube_link">
                                                            <option value="1">Youtube Link</option>
                                                            <option value="2">Upload</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-8 video_input_div">
                                                        <input autocomplete="off" name="youtube[]" type="url" placeholder="Youtube Link" class="form-control youtube_valid_url youtube_div"/>
                                                    </div>
                                                </div>
                                            </div> 

                                            <div class="col-4 col-md-2">
                                                <button class="btn  list_add_button btn-color theme-btn small_btn" type="button">Add Video</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-center two_btns">
                                    <a data-toggle="tab" href="#address" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="prv_img_btn">Previous</a>
                                    <a data-toggle="tab" href="#seo_setting" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="nxt_img_btn">Next</a>
                                </div>  
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                    </div>

                    <div id="seo_setting" class="tab-pane fade in">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="seo_tiltle" id="seo_title">
                                    <small>SEO Title tags are displayed on search engine results pages (SERPs) as the clickable headline for a given result, and are important for usability, SEO, and social sharing. </small>
                                    <div id="pt_seo_title"></div>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="seo_description" id="seo_desc"></textarea>
                                    <small>The SEO description tag in HTML is the 160 character snippet used to summarise a web page's content. Search engines sometimes use these snippets in search results to let visitors know what a page is about before they click on it.</small>
                                    <div id="pt_seo_desc"></div>
                                </div>
                                <div class="form-group text-center two_btns">
                                    <a data-toggle="tab" href="#images" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="prv_seo_btn">Previous</a>
                                    <input type="submit" value="Add" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="add_seo_btn">
                                </div>
                            </div>
                            <div class="col-lg-2"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('custom-scripts')
<script src="{{ url('public/admin/bower_components/ckeditor/ckeditor.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{$mapkey}}&libraries=places&callback=initialize" async defer></script>
<script src="{{url('public/js')}}/mapInput.js"></script>
<script type="text/javascript">
                                        $('a[href="#purpose"]').click();
                                        $('.disabled').click(function() {
                                            return false;
                                        });
                                        $(document).ready(function() {
                                            $('#single_family').show();
                                            $('#multi_family,#commerical_family,#industrail_family,#lot_family').hide();
                                            $('#pt_type').change(function() {
                                                var type = $(this).val();
                                                if (type == '1') {
                                                    $('#single_family').show();
                                                    $('#multi_family,#commerical_family,#industrail_family,#lot_family').hide();
                                                }
                                                if (type == '2') {
                                                    $('#multi_family').show();
                                                    $('#single_family,#commerical_family,#industrail_family,#lot_family').hide();
                                                }
                                                if (type == '3') {
                                                    $('#commerical_family').show();
                                                    $('#multi_family,#single_family,#industrail_family,#lot_family').hide();
                                                }
                                                if (type == '4') {
                                                    $('#industrail_family').show();
                                                    $('#multi_family,#commerical_family,#single_family,#lot_family').hide();
                                                }
                                                if (type == '5') {
                                                    $('#lot_family').show();
                                                    $('#multi_family,#commerical_family,#industrail_family,#single_family').hide();
                                                }
                                            });
                                            $('#unitamount').change(function() {
                                                $('.appended_div').remove();
                                                var unit = $(this).val();
                                                for (i = 1; i <= unit; i++) {
                                                    $(this).after('<div class="form-group appended_div"><label>Unit' + i + '</label><br><div class="form-row"><div class="col-6"><label>Baths</label><input type="number" class="form-control" name="baths' + i + '"></div><div class="col-6"><label>Beds</label><input type="number" class="form-control" name="beds' + i + '"></div></div></div>');
                                                }
                                            });
                                            $('#unitamount_commercial').change(function() {
                                                $('.appended_div').remove();
                                                var unitcom = $(this).val();
                                                for (i = 1; i <= unitcom; i++) {
                                                    $(this).after('<div class="form-group appended_div"><label>Unit' + i + '</label><br><div class="form-row"><div class="col-6"><label>Sqft</label><input type="number" class="form-control" name="sqft' + i + '"></div><div class="col-6"><label>Baths</label><input type="number" class="form-control" name="baths' + i + '"></div></div></div>');
                                                }
                                            });
                                             $('#purpose_form_btn').click(function() {
                                                 var purpose = $('#pt_purpose').val();
                                                 var type = $('#pt_type').val();
                                                 var agent = $('#pt_agent').val();
                                                 $('#pt_purpose_validate').text('');
                                                 $('#pt_type_validate').text('');
                                                 $('#pt_agent_validate').text('');
                                                 if (purpose == '' || purpose == 'null') {
                                                     $('#pt_purpose_validate').text('Please Select Purpose');
                                                     return false;
                                                 }
                                                 if (type == '' || type == 'null') {
                                                     $('#pt_type_validate').text('Please Select Type');
                                                     return false;
                                                 }
                                                 if (agent == '' || agent == 'null') {
                                                     $('#pt_agent_validate').text('Please Select Agent');
                                                     return false;
                                                 }
                                                 $('.property_tab2').removeClass("disabled");
                                                 $('.property_tab2').addClass("active");
                                             });
                                             $('#nxt_details_btn').click(function() {
                                                 var type = $('#pt_type').val();
                                                 $('#pt_single_beds_validate').text('');
                                                 $('#pt_single_baths_validate').text('');
                                                 $('#pt_single_sqft_validate').text('');
                                                 $('#pt_single_price_validate').text('');
                                                 $('#pt_single_hoa_validate').text('');
                                                 $('#pt_unitamount_validate').text('');
                                                 $('#pt_multiliving_sqft_validate').text('');
                                                 $('#pt_multi_lot_sqft_validate').text('');
                                                 $('#pt_multi_annual_tax_validate').text('');
                                                 $('#pt_unit_amount_commercial_validate').text('');
                                                 $('#pt_commercial_space_validate').text('');
                                                 $('#pt_commercial_lot_sqft_validate').text('');
                                                 $('#pt_commercial_annual_tax_validate').text('');
                                                 $('#pt_commercial_zoning_validate').text('');
                                                 $('#pt_industrial_space_validate').text('');
                                                 $('#pt_industrial_space_validate').text('');
                                                 $('#pt_annual_industrail_tax_validate').text('');
                                                 $('#pt_industrail_zoning_validate').text('');
                                                 $('#pt_lot_sqft_validate').text('');
                                                 $('#pt_lot_annual_tax_validate').text('');
                                                 $('#pt_lot_zoning_validate').text('');
                                                 $('#pt_multi_price_validate').text('');
                                                 $('#pt_commercial_price_validate').text('');
                                                 $('#pt_industrial_price_validate').text('');
                                                 $('#pt_lot_price_validate').text('');
                                                 if (type == '1') {
                                                     var beds = $('#single_beds').val();
                                                     var baths = $('#single_baths').val();
                                                     var sqft = $('#single_sqft').val();
                                                     var price = $('#single_price').val();
                                                     var hoa = $('#single_hoa').val();
                                                     if (beds == '') {
                                                         $('#pt_single_beds_validate').text('Please Enter Beds');
                                                         return false;
                                                     }
                                                     if (baths == '') {
                                                         $('#pt_single_baths_validate').text('Please Enter Baths');
                                                         return false;
                                                     }
                                                     if (sqft == '') {
                                                         $('#pt_single_sqft_validate').text('Please Enter Sqft');
                                                         return false;
                                                     }
                                                     if (price == '') {
                                                         $('#pt_single_price_validate').text('Please Enter Price');
                                                         return false;
                                                     }
                                                     if (hoa == '') {
                                                         $('#pt_single_hoa_validate').text('Please Select Hoa');
                                                         return false;
                                                     }
                                                 }
                                                 if (type == '2') {
                                                     var unitamount = $('#unitamount').val();
                                                     var multi_living_sqft = $('#multi_living_sqft').val();
                                                     var multi_lot_sqft = $('#multi_lot_sqft').val();
                                                     var multi_living_sqft = $('#multi_living_sqft').val();
                                                     var multi_annual_tax = $('#multi_annual_tax').val();
                                                     var price = $('#multi_price').val();
                                                     if (unitamount == '') {
                                                         $('#pt_unitamount_validate').text('Please Enter Unit');
                                                         return false;
                                                     }
                                                     if (multi_living_sqft == '') {
                                                         $('#pt_multiliving_sqft_validate').text('Please Enter Living Sqft');
                                                         return false;
                                                     }
                                                     if (multi_lot_sqft == '') {
                                                         $('#pt_multi_lot_sqft_validate').text('Please Enter Lot Sqft');
                                                         return false;
                                                     }
                                                     if (multi_annual_tax == '') {
                                                         $('#pt_multi_annual_tax_validate').text('Please Enter Annual Tax');
                                                         return false;
                                                     }
                                                     if (price == '') {
                                                        $('#pt_multi_price_validate').text('Please Enter Price');
                                                         return false;
                                                     }
                                                 }
                                                 if (type == '3') {
                                                     var unitamount_commercial = $('#unitamount_commercial').val();
                                                     var commercial_space = $('#commercial_space').val();
                                                     var commercial_lot_sqft = $('#commercial_lot_sqft').val();
                                                     var commercial_annual_tax = $('#commercial_annual_tax').val();
                                                     var commercial_zoning = $('#commercial_zoning').val();
                                                     var price = $('#commercial_price').val();
                                                     if (unitamount_commercial == '') {
                                                         $('#pt_unit_amount_commercial_validate').text('Please Enter Unit');
                                                         return false;
                                                     }
                                                     if (commercial_space == '') {
                                                         $('#pt_commercial_space_validate').text('Please Enter Total Commercial Space');
                                                         return false;
                                                     }
                                                     if (commercial_lot_sqft == '') {
                                                         $('#pt_commercial_lot_sqft_validate').text('Please Enter Lot sqft');
                                                         return false;
                                                     }
                                                     if (commercial_annual_tax == '') {
                                                         $('#pt_commercial_annual_tax_validate').text('Please Enter Annual tax');
                                                         return false;
                                                     }
                                                     if (commercial_zoning == '') {
                                                         $('#pt_commercial_zoning_validate').text('Please Enter Commercial Zoning');
                                                         return false;
                                                     }
                                                     if (price == '') {
                                                         $('#pt_commercial_price_validate').text('Please Enter Price');
                                                         return false;
                                                     }

                                                 }
                                                 if (type == '4') {
                                                     var industrial_space = $('#industrial_space').val();
                                                     var industrail_lot_sqft = $('#industrail_lot_sqft').val();
                                                     var industrail_tax = $('#industrail_tax').val();
                                                     var industrail_zoning = $('#industrail_zoning').val();
                                                     var price = $('#industrial_price').val();
                                                     if (industrial_space == '') {
                                                         $('#pt_industrial_space_validate').text('Please Enter Total Industrial Space');
                                                         return false;
                                                     }
                                                     if (industrail_lot_sqft == '') {
                                                         $('#pt_industrial_space_validate').text('Please Enter Lot Sqft');
                                                         return false;
                                                     }
                                                     if (industrail_tax == '') {
                                                         $('#pt_annual_industrail_tax_validate').text('Please Enter Tax');
                                                         return false;
                                                     }
                                                     if (industrail_zoning == '') {
                                                         $('#pt_industrail_zoning_validate').text('Please Enter Zoning');
                                                         return false;
                                                     }
                                                     if (price == '') {
                                                         $('#pt_industrial_price_validate').text('Please Enter Price');
                                                         return false;
                                                     }
                                                 }
                                                 if (type == '5') {
                                                     var lot_sqft = $('#lot_sqft').val();
                                                     var lot_annual_tax = $('#lot_annual_tax').val();
                                                     var lot_zoning = $('#lot_zoning').val();
                                                     var price = $('#lot_price').val();
                                                     if (lot_sqft == '') {
                                                         $('#pt_lot_sqft_validate').text('Please Enter Lot Sqft');
                                                         return false;
                                                     }
                                                     if (lot_annual_tax == '') {
                                                         $('#pt_lot_annual_tax_validate').text('Please Enter Annual tax');
                                                         return false;
                                                     }
                                                     if (lot_zoning == '') {
                                                         $('#pt_lot_zoning_validate').text('Please Enter Zoning');
                                                         return false;
                                                     }
                                                     if (price == '') {
                                                         $('#pt_lot_price_validate').text('Please Enter Price');
                                                         return false;
                                                     }
                                                 }
                                                 $('.property_tab3').removeClass("disabled");
                                                 $('.property_tab3').addClass("active");
                                             });

                                             $('#nxt_address_btn').click(function() {
                                                 var address = $('#address-input').val();
                                                  var chk_val = $('input[name="address_type"]:checked').val(); 
                                                 if (address == '' || address == 'null') {
                                                     if(chk_val==1){
                                                                $('#pt_address_validate').text('Please Enter Address');
                                                                }else{
                                                                  $('#pt_address_validate').text('Please Enter Postal Code');
                                                                }
                                                     return false;
                                                 }
                                                 $('.property_tab4').addClass("active");
                                             });
                                             $('#nxt_img_btn').click(function() {
                                                 var upload_logo = $('#upload_logo').val();
                                                 if (upload_logo == '' || upload_logo == 'null') {
                                                     $('#agency_logo_validate').text('Please Upload Image');
                                                     return false;
                                                 }
                                                 $('.property_tab5').removeClass("disabled");
                                                 $('.property_tab5').addClass("active");
                                             });
                                             $('#add_seo_btn').click(function() {
                                                 var seo_title = $('#seo_title').val();
                                                 var seo_desc = $('#seo_desc').val();
                                                 if (seo_title == '' || seo_title == 'null') {
                                                     $('#pt_seo_title').text('Please Enter Seo Title');
                                                     return false;
                                                 }
                                                 if (seo_desc == '' || seo_desc == 'null') {
                                                     $('#pt_seo_desc').text('Please Enter Seo Description');
                                                     return false;
                                                 }
                                             });
                                        });                                        
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
                                        function readURLIMG(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();
                                                reader.onload = function(e) {
                                                     $(input).next('.preview_image').attr('src', e.target.result).css('display','block');
                                                      //$('#agency_logo_image').attr('src', e.target.result);
                                                      console.log(this);
                                                      console.log($(this).closest());
                                                }

                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }    

                                        $('.list_add_button').click(function() {
                                            var newHtml = "<div class='row'><div class='col-8 col-md-10'><div class='row'><div class='form-group col-md-4'><select class='form-control video_type_sel dropdown_control'><option value='1'>Youtube Link</option><option value='2'>Upload</option></select></div><div class='form-group col-md-8 video_input_div'><input autocomplete='off' name='youtube[]' type='url' placeholder='Youtube Link' class='form-control youtube_valid_url youtube_div'/></div></div></div><div class='col-2 col-md-2'><button class='btn btn-danger small_btn list_remove_btn' type='button'>Remove Video</button></div></div></div>";
                                            $('.youtube_link_wrap').append(newHtml);
                                        });
                                        $('.youtube_link_wrap').on('click', '.list_remove_btn', function() {
                                            $(this).closest('div.row').remove();
                                        });
                                        $('.upload_add_button').click(function() {
                                            var newHtml = "<div class='row'><div class='col-8 col-md-10'><div class='row'><div class='form-group col-md-4'><select class='form-control dropdown_control'><option value='2'>Upload</option></select></div><div class='form-group col-md-8'><input name='upload_video[]' type='file' class='form-control video_upload_input' accept='video/*'/></div></div></div><div class='col-2 col-md-2'><button class='btn btn-danger upload_remove_btn' type='button'>-</button></div></div></div>";
                                            $('.upload_wrap').append(newHtml);
                                        });
                                        $('.upload_wrap').on('click', '.upload_remove_btn', function() {
                                            $(this).closest('div.row').remove();
                                        });

                                        $(".youtube_link_wrap").on('change', '.video_upload_input', function() {
                                            var file_size = this.files[0].size;
                                            var fileExtension = ['mp4', 'mov', 'ogg', 'qt', 'flv', 'ts', '3gp', 'avi'];
                                             $(this).parent().prev('.uplod_vid_name').val(this.files[0].name);
                                            if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
                                                $(this).after("<div class='video_error'>Allow Format:mp4,mov,ogg,qt,flv,ts,3gp,avi</div>");
                                                return false;
                                            }

                                            if (file_size > 26214400) {
                                                $(this).after("<div class='video_error'>File size is greater than 25MB</div>");
                                                return false;
                                            }
                                            $(this).next('.video_error').remove();
                                            return true;
                                        });
                                        $(function() {
                                            $('#property_form').submit(function() {
                                                $('.loader-outer-container').css('display', 'table');
                                                return true;
                                            });
                                        });

                                        $('.youtube_link_wrap').on('change', '.youtube_valid_url', function() {
                                            var url = $(this).val();
                                            if (url != undefined || url != '') {
                                                var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
                                                var match = url.match(regExp);
                                                if (match && match[2].length == 11) {

                                                }
                                                else {
                                                    $(this).next('.youtube_error').remove();
                                                    $(this).after("<div class='youtube_error'>Please Add Youtube Link</div>");
                                                    return false;
                                                }
                                                $(this).next('.youtube_error').remove();
                                            }
                                        });
                                        $('input[type=number]').on('keydown', function(event) {
                                            return event.keyCode !== 69
                                        });
                                        $('#prv_details_btn,#nxt_details_btn,#purpose_form_btn,#prv_address_btn,#nxt_address_btn,#prv_img_btn,#nxt_img_btn,#prv_seo_btn').click(function() {
                                            $(this).removeClass('active');
                                        });
                                         $('.youtube_link_wrap').on('change', '.video_type_sel', function() {
                                            var video_type = $(this).val();
                                            if (video_type == 2) {
                                                var newHtml = "<div class='upload-file-group'><input type='text' name='' class='form-control upload_control upload_file upload_div uplod_vid_name' readonly='' placeholder='Upload here'><button class='file_upload_btn btn-color'><input type='file' name='upload_video[]' class='agency_logo file_control video_upload_input upload_div' id='upload_logo' accept='video/*'><img src='{{ url('public/assets/')}}/images/ic_upload.png'></button></div><small class='inst_video'>Allowed Format:mp4, mov, ogg, qt, flv,ts,3gp,avi</small><br class='inst_video'><small class='inst_video'>Allowed Size:25mb</small>";
                                                $(this).parent().next('div.video_input_div').find('.youtube_div').remove();
                                                $(this).parent().next('div.video_input_div').append(newHtml);
                                                $('.youtube_error').remove();
                                            }else{
                                                 var newHtml = '<input autocomplete="off" name="youtube[]" type="url" placeholder="Youtube Link" class="form-control youtube_valid_url youtube_div"/>';
                                                $(this).parent().next('div.video_input_div').find('.upload_div').remove();
                                                $(this).parent().next('div.video_input_div').find('.file_upload_btn').remove();
                                                $(this).parent().next('div.video_input_div').find('.inst_video').remove();
                                                $(this).parent().next('div.video_input_div').append(newHtml);
                                            }
                                        });

                                    $(document).on('click','.add_more',function(){
                                       var abc = $(this).parent().clone();
                                       $('.parent_div').append(abc);
                                    });

                                //$(document).on('click','.image_added',function(){
                                $('.multi_img_wrap').on('click', '.image_added', function() {
                                   if ($(this).files && input.files[0]) {
                                    var reader = new FileReader();

                                    reader.onload = function(e) {
                                    $(this).next('.preview_image').attr('src', e.target.result).css('display','block');
                                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                                  }
                                   }
                                });
                                $('.img_add_more').click(function() {
                                            //var newHtml = "<div class='row'><div class='col-xs-7 col-sm-7 col-md-7'><div class='row'><div class='form-group col-md-8'><input type='file' name='other_image[]' class='form-control image_added' id='gallery-photo-add' accept='image/*' onchange='readURLIMG(this);'><img class='preview_image' style='display:none'></div></div></div><div class='col-xs-1 col-sm-1 col-md-1'><button class='btn btn-danger img_remove_btn' type='button'>-</button></div></div></div>";
                                            var newHtml = '<div class="squar_file_box_add clearfix"><button class="btn btn-danger img_remove_btn theme-btn small_btn" type="button">Remove Image</button><div class="squar_file_box"><input type="file" name="other_image[]" class="image_added agency_logo file_control" id="gallery-photo-add" accept="image/*" onchange="readURLIMG(this);"><img src="{{ url("public/assets/")}}/images/add_img.png" id="agency_logo_image" class="preview_image"></div></div>';
                                            $('.multi_img_wrap').append(newHtml);
                                });
                                $('.multi_img_wrap').on('click', '.img_remove_btn', function() {
                                            $(this).closest('div.squar_file_box_add').remove();
                                 });
                                 $(document).on('click','.chk_radio',function(){
                                                      var chk_val = $('input[name="address_type"]:checked').val(); 
                                                      if(chk_val==1){
                                                          $('.from_google_div').html('<div class="form-group"><input type="text" id="address-input" name="address_address" class="form-control map-input"><div id="pt_address_validate" class="validation_msg"></div></div>');
                                                          $('.from_manual').html('');
                                                          initialize();
                                                      }else{
                                                          $('.from_manual').html('<div class="form-group"><input type="text" id="address-input" name="address_address" class="form-control map-input" placeholder="Search Postal Code"><div id="pt_address_validate" class="validation_msg"></div></div><div class="form-group"><input type="text" id="manual_address" name="manual_address" class="form-control" placeholder="Enter Address"></div>');
                                                          $('.from_google_div').html('');
                                                          initialize();
                                                      }
                                                    });
                            
                                
</script>
@endpush