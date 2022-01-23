@extends('frontend.layout.app_for_property')
@section('title','Properties List')
@section('content')@if ($errors->any())
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
<style type="text/css">

</style>
<?php $general_settings = getGeneralSettings(); 
 if(isset($general_settings['property_display_row']) && !empty($general_settings['property_display_row'])){
    $property_display_row=$general_settings['property_display_row'];
 }else{
    $property_display_row=1;
 }
 if(isset($general_settings['property_alignment']) && !empty($general_settings['property_alignment'])){
    $property_alignment=$general_settings['property_alignment'];
 }else{
    $property_alignment='left';
 }
 if(isset($general_settings['property_map_pin']) && !empty($general_settings['property_map_pin'])){
    $property_map_pin=$general_settings['property_map_pin'];
 }else{
    $property_map_pin='';
 } ?>
<div class="map_property_detail fullpage_view" style="margin-top: 0px">	
	<div class="filter_section">
		<div class="container-fluid">
			<div class="row ">
				
				<div class="col-12">
          	<div class="filter_button_div mobile_view">
          		<button type="button" class="btn-color btn-text list_view data_view_btn filter_btn"  id="list_view"><i class="fas fa-list"></i></button>
      			<button type="button" class="btn-color btn-text map_view data_view_btn filter_btn deactive" id="map_view"><i class="fas fa-map-marked-alt"></i></button>
						<button type="button" class="btn-color btn-text filter_btn" id="filter_btn"><i class="fas fa-filter" ></i></button>
					</div>
          <form class=""  method="get" action="<?= url('/'.$slug) ?>/properties-search" autocomplete="off">
            <div class="filter_box">
              <div class="search_box form_control">
                <input type="text" name="address" id="address" class="form-control icon_control search_control" placeholder="City, State, Country" value="@if(isset($_GET['address']) && !empty($_GET['address'])){{$_GET['address']}}@endif">
                <div id="addressList"></div>
                <!-- <input type="hidden" name="city" id="city" value="@if(isset($_GET['city']) && !empty($_GET['city'])){{$_GET['city']}}@endif">
                <input type="hidden" name="latitude" id="latitude" value="@if(isset($_GET['latitude']) && !empty($_GET['latitude'])){{$_GET['latitude']}}@endif">
                <input type="hidden" name="longitude" id="longitude" value="@if(isset($_GET['longitude']) && !empty($_GET['longitude'])){{$_GET['longitude']}}@endif">
                <input type="hidden" name="state" id="state_short_name" value="@if(isset($_GET['state']) && !empty($_GET['state'])){{$_GET['state']}}@endif">
                <input type="hidden" name="country" id="state" value="@if(isset($_GET['country']) && !empty($_GET['country'])){{$_GET['country']}}@endif">
                <input type="hidden" name="zipcode" id="zipcode" value="@if(isset($_GET['zipcode']) && !empty($_GET['zipcode'])){{$_GET['zipcode']}}@endif"> -->
                <!-- <input type="hidden" name="lat" value="@if(isset($_GET['lat']) && !empty($_GET['lat'])) {{$_GET['lat']}} @endif"> -->
              </div>
              <div class="slider_box">
                <input type="text" class="form-control icon_control bg_doller price" name="min_price" placeholder="Minimum Price" value="@if(isset($_GET['min_price']) && !empty($_GET['min_price'])){{$_GET['min_price']}}@endif">
              </div>
              <div class="slider_box">
                <input type="text" class="form-control icon_control bg_doller price" name="max_price" placeholder="Maximum Price" value="@if(isset($_GET['max_price']) && !empty($_GET['max_price'])){{$_GET['max_price']}}@endif">
              </div>
              <!-- <div class="slider_box">
                <input type="text" class="form-control icon_control" name="min_sq" placeholder="Minimum Sq" value="@if(isset($_GET['min_sq']) && !empty($_GET['min_sq'])){{$_GET['min_sq']}}@endif">
              </div>
              <div class="slider_box">
                <input type="text" class="form-control icon_control" name="max_sq" placeholder="Maximum Sq" value="@if(isset($_GET['max_sq']) && !empty($_GET['max_sq'])){{$_GET['max_sq']}}@endif">
              </div> -->
              <div class="bed_box">
                <ul>
                  <li>Beds</li>
                  <li><input type="checkbox" class="bed-list" name="beds" value="0" @if(isset($_GET['beds']) && $_GET['beds'] == 0 && $_GET['beds']!='') checked @endif><span>0</span></li>
                  <li><input type="checkbox" class="bed-list" name="beds" value="1" @if(isset($_GET['beds']) && $_GET['beds']==1) checked @endif><span>1</span></li>
                  <li><input type="checkbox" class="bed-list" name="beds" value="2" @if(isset($_GET['beds']) && $_GET['beds']==2) checked @endif><span>2</span></li>
                  <li><input type="checkbox" class="bed-list" name="beds" value="3" @if(isset($_GET['beds']) && $_GET['beds'] == 3 ) checked @endif><span>3+</span></li>
                </ul>
              </div>
              <div class="bed_box">
                <ul>
                  <li>Baths</li>
                  <li><input type="checkbox" class="bath-list" name="baths" value="0" @if(isset($_GET['baths']) && $_GET['baths']==0 && $_GET['baths']!='') checked @endif><span>0</span></li>
                  <li><input type="checkbox" class="bath-list" name="baths" value="1" @if(isset($_GET['baths']) && $_GET['baths']==1) checked @endif><span>1</span></li>
                  <li><input type="checkbox" class="bath-list" name="baths" value="2" @if(isset($_GET['baths']) && $_GET['baths']==2) checked @endif><span>2</span></li>
                  <li><input type="checkbox" class="bath-list" name="baths" value="3" @if(isset($_GET['baths']) && $_GET['baths']==3) checked @endif><span>3+</span></li>
                </ul>
              </div>
              <div class="dropdown_box">
                <select class="form-control icon_control dropdown_control" name="property_type">
                  <option value="" disabled selected="" hidden="">Type</option> 
                  <option value="1" @if(isset($_GET['property_type']) && $_GET['property_type']=='1') selected @endif>Single Homes</option>
                  <option value="2" @if(isset($_GET['property_type']) && $_GET['property_type']=='2') selected @endif >Multifamily</option>
                  <option value="3" @if(isset($_GET['property_type']) && $_GET['property_type']=='3') selected @endif >Commercial</option>
                  <option value="4" @if(isset($_GET['property_type']) && $_GET['property_type']=='4') selected @endif >Industrial </option>
                  <option value="5" @if(isset($_GET['property_type']) && $_GET['property_type']=='5') selected @endif >Lot </option>  
                </select>
              </div>
              <div class="dropdown_box">
                <select class="form-control icon_control dropdown_control" name="purpose">
                  <option value="" disabled selected="" hidden="">Purpose</option> 
                  <option value="1" @if(isset($_GET['purpose']) && $_GET['purpose']=='1') selected @endif>Buy</option>
                  <option value="2" @if(isset($_GET['purpose']) && $_GET['purpose']=='2') selected @endif >Rent</option>
                </select>
              </div>
              <!-- <div class="dropdown_box">
                <select class="form-control icon_control dropdown_control" name="status">
                  <option value="">Property Status</option> 
                  <option value="Active" @if(isset($_GET['status']) && $_GET['status']=='Active') selected @endif >Active</option>
                  <option value="Active Under Contract" @if(isset($_GET['status']) && $_GET['status']=='Active Under Contract') selected @endif>Active Under Contract</option>
                  <option value="Canceled" @if(isset($_GET['status']) && $_GET['status']=='Residential') selected @endif>Canceled</option>
                  <option value="Closed" @if(isset($_GET['status']) && $_GET['status']=='Canceled') selected @endif>Closed</option>
                  <option value="Coming Soon" @if(isset($_GET['status']) && $_GET['status']=='Coming Soon') selected @endif>Coming Soon</option>
                  <option value="Delete" @if(isset($_GET['status']) && $_GET['status']=='Delete') selected @endif>Delete</option>
                  <option value="Expired" @if(isset($_GET['status']) && $_GET['status']=='Expired') selected @endif>Expired</option>  
                  <option value="Hold" @if(isset($_GET['status']) && $_GET['status']=='Hold') selected @endif>Hold</option>
                  <option value="Incomplete" @if(isset($_GET['status']) && $_GET['status']=='Incomplete') selected @endif>Incomplete</option>
                  <option value="Pending" @if(isset($_GET['status']) && $_GET['status']=='Pending') selected @endif>Pending</option>    
                  <option value="Withdrawn" @if(isset($_GET['status']) && $_GET['status']=='Withdrawn') selected @endif>Withdrawn</option>
                </select>
              </div> -->
              <div class="search_btn">
                <button class="theme-btn btn-color btn-text btn-size filter_search_btn">Search</button>
              </div>
            </div>
          </form>
				</div>
              
            	
            </div>           
		</div>
	</div>
  
    <div class="property_view_section">
        <div class="container-fluid ">
          @if(!empty($property_lists) && count($property_lists)>0)
        		<div class="property-listing-class row equal_height <?php if($property_alignment=='right'){echo 'flex-md-row-reverse';} ?>">
        			<div class="equal_height_container list_view_data  <?php if($property_display_row==3){echo 'col-lg-8 ';}elseif($property_display_row==2){echo 'col-lg-6 ';}else{echo 'col-lg-4 ';}?>" id="list_view_data">
                        <div>
                            <div class="scrollbar slim-scroll">
                                <div id="property_list_data">
                                    <div class="row equal_height" >
                                  <?php $i = 1;?>
                                    @foreach($property_lists as $property_list)
                                    <?php 
                                     $string = array(', ',',', ' ', '/', "'");
                                    $replace   = array('-', '-', '-', '-', '-');
                                    $address = str_replace($string, $replace, $property_list['address']);
                                    $address = str_replace('--', '-', $address);
                                    $url  = $slug.'/property-detail/'.$address.'-'. $property_list['id'];  ?>
                                    
                                        <div class="<?php if($property_display_row==3){echo 'col-lg-4';}elseif($property_display_row==2){echo 'col-lg-6';}else{echo 'col-md-12';}?> equal_height_container">
                                            <div class="card-home" id="">
                                                <a href="{{url($url) }}" class="property_link">
                                                  @if($property_list['agency_id']!=97)
                                                    <div class="item" style="background-image: url('{{url('public/uploads/properties_images/'.$property_list['id'].'/'.$property_list['main_image'])}}')"></div>
                                                  @else
                                                  <div class="item" style="background-image: url('{{url('public/uploads/properties_images/16/159767026246e6cfde-fc64-4dc3-a4f0-dfe0216c849c.jpg')}}')"></div>
                                                  @endif
                                                </a>
                                                
                                                
                                                
                                                @if(Auth::check() == true)
                                                <div class="middle-info fav_propery_box <?php if($property_list['fav_property'] == true) { echo 'active';} ?>">
                                                    <a href="#" class="fav-property favUnfavitem" onclick="favProperty(this);" id="{{ $i }}"  property_id = "{{ $property_list['id'] }}" ListingId= "{{ $property_list['ListingId'] }}"><i class="fas fa-heart"></i></a>
                                                </div>
                                                @endif
                                               
                                                <div class="bottom-info">
                                                    <div class="middle-info price">${{$property_list['price']}}</div>
                                                    <p class="property_name"><a href="#">{{$property_list['address']}} </a></p>
                                                    @if(!empty($property_list['beds']))
                                                    <span><i class="fas fa-bed"></i>
                                                        <!-- <img src="{{ url('public/assets/images/ic_bed.png') }}"> -->{{$property_list['beds']}} BD</span>
                                                    @endif
                                                    @if(!empty($property_list['baths']))
                                                    <span>
                                                        <i class="fas fa-bookmark"></i>
                                                        <!-- <img src="{{ url('public/assets/images/ic_bath.png') }}"> -->
                                                        {{$property_list['baths']}} BA</span>
                                                    @endif
                                                    @if(!empty($property_list['sq_feet']))
                                                    <span>
                                                        <!-- <i class="fas fa-layer-group"></i> -->
                                                        <i class="fas fa-cube"></i>
                                                        <!-- <img src="{{ url('public/assets/images/ic_layers.png') }}"> -->{{$property_list['sq_feet']}} SF</span>
                                                    @endif
                                                </div>
                                            </div> 
                                        </div>
                                    
                                    <?php $i++; ?>
                                    @endforeach
                                    </div> 
                                    {!! $property_lists->links() !!} 
                                </div>
                                
                                
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />        
                          	</div>
                        </div>                   
        			</div>
        			<div class="p-0 equal_height_container map_view_data <?php if($property_display_row==3){echo 'col-lg-4';}elseif($property_display_row==2){echo 'col-lg-6';}else{echo 'col-lg-8';}?>" id="map_view_data">
                        <div class="map_div"> 
            				<!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.822550124305!2d72.49658671496726!3d22.99355188496775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b2857ef695d%3A0x111d8e2b0b7e034b!2sTechnource!5e0!3m2!1sen!2sin!4v1586329700242!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
                            <div id="mapCanvas"></div>
                        </div>
        			</div>
        		</div>
            <div class="property-not-found-class" style="display: none;">
              <p>Data not found</p>
            </div>
          @else
              <div class="property-not-found-class">
                <p>Data not found</p>
              </div>
             
             <div class="property-listing-class row equal_height <?php if($property_alignment=='right'){echo 'flex-md-row-reverse';} ?>" style="display: none;">
              <div class="col-md-6 equal_height_container list_view_data  <?php if($property_display_row==3){echo 'col-lg-8 col-md-6';}elseif($property_display_row==2){echo 'col-lg-6 col-md-6';}else{echo 'col-lg-4 col-md-6';}?>" id="list_view_data">
                        <div>
                            <div class="scrollbar slim-scroll">
                                <div id="property_list_data">
                                </div>
                                
                                
                                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />        
                            </div>
                        </div>                   
              </div>
              <div class="col-md-6 p-0 equal_height_container map_view_data <?php if($property_display_row==3){echo 'col-lg-4 col-md-6';}elseif($property_display_row==2){echo 'col-lg-6 col-md-6';}else{echo 'col-lg-8 col-md-6';}?>" id="map_view_data">
                        <div class="map_div"> 
                    <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3672.822550124305!2d72.49658671496726!3d22.99355188496775!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e9b2857ef695d%3A0x111d8e2b0b7e034b!2sTechnource!5e0!3m2!1sen!2sin!4v1586329700242!5m2!1sen!2sin" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> -->
                            <div id="mapCanvas"></div>
                        </div>
              </div>
            </div>
          @endif
        </div>
    </div>
  
</div>
<?php //echo "<pre>"; print_r($general_settings); exit; ?>
					
 <?php 
 if(!empty($property_map_pin)){
    $map_icon= url('/').'/public/uploads/map_pin/'.$property_map_pin;
 }else{
    $map_icon='';
 }
  ?>
@endsection
@push('custom-css')
<?php
    $getSettingData = getMenu();
?>
{{--<title>{{ $getSettingData['home_meta_title'] }}</title>
<meta property="og:title" content="{{ $getSettingData['home_meta_title'] }}">
<meta name="description" content="{{ $home_meta_data['home_meta_description'] }}">
<meta property="og:description" content="{{ $home_meta_data['home_meta_description'] }}">--}}
@endpush
 @push('custom-scripts')
{{-- <script type="text/javascript" src="{{ url('public/assets/js/croppie.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/customer/profile/profile.js') }}"></script> --}}
<script type="text/javascript" src="{{ url('public/assets/js/croppie.js') }}"></script>
 <!-- <script type="text/javascript" async defer src="https://maps.googleapis.com/maps/api/js?key=<?=env('GOOGLE_MAP_API_KEY')?>=places&sensor=false"></script> -->
 <script type="text/javascript">
  var base_url='<?= url('/') ?>';
$(document).on("input", ".price", function() {
    this.value = this.value.replace(/\D/g,'');
});
$('.bath-list').on('change', function() {
    $('.bath-list').not(this).prop('checked', false);  
});
$('.bed-list').on('change', function() {
    $('.bed-list').not(this).prop('checked', false);  
});
$(document).ready(function(){
   $(document).on('click', '.sorting', function(){
  var column_name = $(this).data('column_name');
  var order_type = $(this).data('sorting_type');
  var reverse_order = '';
  if(order_type == 'asc')
  {
   $(this).data('sorting_type', 'desc');
   reverse_order = 'desc';
   clear_icon();
   $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-bottom"></span>');
  }
  if(order_type == 'desc')
  {
   $(this).data('sorting_type', 'asc');
   reverse_order = 'asc';
   clear_icon
   $('#'+column_name+'_icon').html('<span class="glyphicon glyphicon-triangle-top"></span>');
  }
  $('#hidden_column_name').val(column_name);
  $('#hidden_sort_type').val(reverse_order);
  var page = $('#hidden_page').val();
  var query = $('#serach').val();
  fetch_data(page, reverse_order, column_name, query);
 });

 $(document).on('click', '.pagination a', function(event){
  event.preventDefault();

  //var page = $(this).attr('href').split('page=')[1];
  var page = $(this).attr('href').split('page=')[1];
  console.log('page:'+$(this).data('url'));
  //var query = $('#serach').val();
  var query = '';
  $('li').removeClass('active');
  $(this).parent().addClass('active');
  
  var address12=$('input[name=address]').val();
  var min_price=$('input[name=min_price]').val();
  var max_price=$('input[name=max_price]').val();
  var beds=$('input[name=beds]:checked').val();
  var city=$('input[name=city]').val();
  var state=$('input[name=state]').val();
  var latitude=$('input[name=latitude]').val();
  var longitude=$('input[name=longitude]').val();
  var country=$('input[name=country]').val();
  var zipcode=$('input[name=zipcode]').val();
  var min_sq=$('input[name=min_sq]').val();
  var max_sq=$('input[name=max_sq]').val();
  if(!beds){
    beds='';
  }
  var baths=$('input[name=baths]:checked').val();
  if(!baths){
    baths='';
  }
  var property_type=$('select[name=property_type]').val();
  if(property_type==null){
    property_type='';
  }
  
  var purpose=$('select[name=purpose]').val();
if(purpose==null){
    purpose='';
  }
  var status=$('select[name=status]').val();
  /*window.location.href = '<?= url('/') ?>/properties-search?page='+page+'&address='+address12+'&latitude='+latitude+'&longitude='+longitude+'&city='+city+'&state='+state+'&country='+country+'&zipcode='+zipcode+'&min_price='+min_price+'&max_price='+max_price+'&beds='+beds+'&baths='+baths+'&property_type='+property_type;*/
  //window.location.href = '<?= url('/'.$slug) ?>/properties-search?page='+page+'&address='+address12+'&min_price='+min_price+'&max_price='+max_price+'&beds='+beds+'&baths='+baths+'&property_type='+property_type+'&purpose='+purpose;
  var pageurl='<?= url('/'.$slug) ?>/properties-search?page='+page+'&address='+address12+'&latitude='+latitude+'&longitude='+longitude+'&city='+city+'&state='+state+'&country='+country+'&zipcode='+zipcode+'&min_price='+min_price+'&max_price='+max_price+'&beds='+beds+'&baths='+baths+'&property_type='+property_type+'&purpose='+purpose;
  if(pageurl!=window.location){
    window.history.pushState({path:pageurl},'',pageurl);  
  }
  var pageurl='<?= url('/'.$slug) ?>/properties-search?page='+page+'&methodType=ajax&address='+address12+'&latitude='+latitude+'&longitude='+longitude+'&city='+city+'&state='+state+'&country='+country+'&zipcode='+zipcode+'&min_price='+min_price+'&max_price='+max_price+'&beds='+beds+'&baths='+baths+'&property_type='+property_type+'&purpose='+purpose;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: pageurl,
        type: "get", //request type,
        dataType: 'json',
        success: function(response) {
          
          if(response['is_empty']<1){
            $('.property-not-found-class').css('display','block');
            $('.property-listing-class').css('display','none');
          }else{
            $('#property_list_data').html(response['html']);
            getMapMarker12();
            $('.property-not-found-class').css('display','none');
            $('.property-listing-class').css('display','flex');
          }
          
          //getMapMarker12();
          //google.maps.event.addDomListener(window, 'load', initMap(response));
        }
    });

  });
  $(document).on('click', '.filter_search_btn', function(event){
  event.preventDefault();

  //var query = $('#serach').val();
  var query = '';
  $('li').removeClass('active');
  $(this).parent().addClass('active');
  
  var address12=$('input[name=address]').val();
  var min_price=$('input[name=min_price]').val();
  var max_price=$('input[name=max_price]').val();
  var beds=$('input[name=beds]:checked').val();
  var city=$('input[name=city]').val();
  var state=$('input[name=state]').val();
  var latitude=$('input[name=latitude]').val();
  var longitude=$('input[name=longitude]').val();
  var country=$('input[name=country]').val();
  var zipcode=$('input[name=zipcode]').val();
  var min_sq=$('input[name=min_sq]').val();
  var max_sq=$('input[name=max_sq]').val();
  if(!beds){
    beds='';
  }
  var baths=$('input[name=baths]:checked').val();
  if(!baths){
    baths='';
  }
  var property_type=$('select[name=property_type]').val();
  if(property_type==null){
    property_type='';
  }
  
  var purpose=$('select[name=purpose]').val();
if(purpose==null){
    purpose='';
  }
  var status=$('select[name=status]').val();
  /*window.location.href = '<?= url('/') ?>/properties-search?page='+page+'&address='+address12+'&latitude='+latitude+'&longitude='+longitude+'&city='+city+'&state='+state+'&country='+country+'&zipcode='+zipcode+'&min_price='+min_price+'&max_price='+max_price+'&beds='+beds+'&baths='+baths+'&property_type='+property_type;*/
  //window.location.href = '<?= url('/'.$slug) ?>/properties-search?page='+page+'&address='+address12+'&min_price='+min_price+'&max_price='+max_price+'&beds='+beds+'&baths='+baths+'&property_type='+property_type+'&purpose='+purpose;
  var pageurl='<?= url('/'.$slug) ?>/properties-search?address='+address12+'&latitude='+latitude+'&longitude='+longitude+'&city='+city+'&state='+state+'&country='+country+'&zipcode='+zipcode+'&min_price='+min_price+'&max_price='+max_price+'&beds='+beds+'&baths='+baths+'&property_type='+property_type+'&purpose='+purpose;
  if(pageurl!=window.location){
    window.history.pushState({path:pageurl},'',pageurl);  
  }
  var pageurl='<?= url('/'.$slug) ?>/properties-search?&methodType=ajax&address='+address12+'&latitude='+latitude+'&longitude='+longitude+'&city='+city+'&state='+state+'&country='+country+'&zipcode='+zipcode+'&min_price='+min_price+'&max_price='+max_price+'&beds='+beds+'&baths='+baths+'&property_type='+property_type+'&purpose='+purpose;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: pageurl,
        type: "get", //request type,
        dataType: 'json',
        success: function(response) {
          if(response['is_empty']<1){
            $('.property-not-found-class').css('display','block');
            $('.property-listing-class').css('display','none');
          }else{
            $('#property_list_data').html(response['html']);
            
            $('.property-not-found-class').css('display','none');
            $('.property-listing-class').css('display','flex');
            getMapMarker12();
          }
          
          //google.maps.event.addDomListener(window, 'load', initMap(response));
        }
    });

  });
  getMapMarker12();
  function getMapMarker12(){
    var address12=$('input[name=address]').val();
    var min_price=$('input[name=min_price]').val();
    var max_price=$('input[name=max_price]').val();
    var min_sq=$('input[name=min_sq]').val();
    var max_sq=$('input[name=max_sq]').val();
    var beds=$('input[name=beds]:checked').val();
    /*var city=$('input[name=city]').val();
    var state=$('input[name=state]').val();
    var latitude=$('input[name=latitude]').val();
    var longitude=$('input[name=longitude]').val();
    var country=$('input[name=country]').val();
    var zipcode=$('input[name=zipcode]').val();*/
    if(!beds){
      beds='';
    }
    var baths=$('input[name=baths]:checked').val();
    if(!baths){
      baths='';
    }
    var property_type=$('select[name=property_type]').val();
    if(property_type==null){
      property_type='';
    }
    
    var purpose=$('select[name=purpose]').val();
    if(purpose==null){
      purpose='';
    }
    var status=$('select[name=status]').val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '<?= url('/') ?>/getMapMarker',
        type: "post", //request type,
        data:{address:address12,min_price:min_price,max_price:max_price,min_sq:min_sq,max_sq:max_sq,beds:beds,baths:baths,property_type:property_type,status:status,purpose:purpose},
        dataType: 'json',
        success: function(response) {
          google.maps.event.addDomListener(window, 'load', initMap(response));
        }
    });
  }
function initMap(response) {
    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
    map.setTilt(100);
    //Create LatLngBounds object.
    var newmarkerarray=[];
    var newmarkerarraydescription=[];
    var map_icon='<?= $map_icon ?>';
    $.each(response, function( key, value ) {
      var arraymarker=[value['address'],value['latitude'],value['longitude'],map_icon];
      //console.log( "value: " + value['Latitude'] );
      //console.log(arraymarker);
      newmarkerarray.push(arraymarker);
      if(value['beds']!='' && value['beds']!=null){
          var newbeds='<span><i class="fas fa-bed"></i>'+value['beds']+' Bad  |  </span>';
        }else{
          var newbeds='';
        }
        if(value['baths'] !='' && value['baths']!=null){
          var newbath='<span><i class="fas fa-bookmark"></i>'+value['baths']+' Baths  |  </span>';
        }else{
          var newbath='';
        }
        if(value['sq_feet']!=''){
          var newsqf='<span><i class="fas fa-cube"></i>'+value['sq_feet']+' SF  |  </span>';
        }else{
          var newsqf='';
        }
      if(value['agency_id']==97){
        
        var arraymarkerdescription=['<a href="'+value['url']+'"><div class="info_content map_info_window"><div class="map_propery_img"><img src="'+base_url+'/public/uploads/properties_images/16/159767026246e6cfde-fc64-4dc3-a4f0-dfe0216c849c.jpg"></div><div class="map_pro_detail"><p class="price">$'+value['price']+'</p><h3 class="title">'+value['address']+'</h3><p>'+newbeds+newbath+newsqf+'</p></div>' + '</div></a>']
      }else{
        var arraymarkerdescription=['<a href="'+value['url']+'" ><div class="info_content map_info_window"><div class="map_propery_img"><img src="'+base_url+'/public/uploads/properties_images/'+value['id']+'/'+value['main_image']+'"></div><div class="map_pro_detail"><p class="price">$'+value['price']+'</p><h3 class="title">'+value['address']+'</h3><p>'+newbeds+newbath+newsqf+'</p></div>' + '</div></a>']
      }
      
      //console.log( "value: " + value['Latitude'] );
      newmarkerarraydescription.push(arraymarkerdescription);

    });
    //var newArray = $.merge($.merge([], cString), test);
    var markers = newmarkerarray;
                        
    // Info window content
    var infoWindowContent = newmarkerarraydescription;
        
    // Add multiple markers to map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    var latlngbounds = new google.maps.LatLngBounds()
    // Multiple markers location, latitude, and longitude
    // Place each marker on the map  
    let clusterMarkers = [];
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        marker = new google.maps.Marker({
            position: position,
            map: map,
            icon: markers[i][3],
            title: markers[i][0]
        });
        
        // Add info window to marker    
        /*google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
            clusterMarkers.push(marker);
        })(marker, i));
        google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.close(map, marker);
            }
        })(marker, i));*/
        google.maps.event.addListener(marker, 'click', (function(marker, i) {

                return function() {
                    infoWindow.setContent(infoWindowContent[i][0]);
                    infoWindow.open(map, marker);
                }

                clusterMarkers.push(marker);

            })(marker, i));

            google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {

                return function() {

                    infoWindow.setContent(infoWindowContent[i][0]);

                    infoWindow.open(map, marker);

                }

                //clusterMarkers.push(marker);

            })(marker, i));
        clusterMarkers.push(marker);
         //Extend each marker's position in LatLngBounds object.
            latlngbounds.extend(marker.position);
        // Center the map to fit all markers on the screen
        //map.fitBounds(bounds);

        
    }
    var markerCluster = new MarkerClusterer(map, clusterMarkers,
          { imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'
        });
//Get the boundaries of the Map.
        var bounds = new google.maps.LatLngBounds();
 
        //Center map and adjust Zoom based on the position of all markers.
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
    <?php if($all_property_lists==1){ ?>
        //console.log(count($all_property_lists));
    // Set zoom level
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });
  <?php } ?>
}
// Load initialize function

});


$(document).ready(function(){
  

 function clear_icon()
 {
  $('#id_icon').html('');
  $('#post_title_icon').html('');
 }

 $(document).on('keyup', '#serach', function(){
  var query = $('#serach').val();
  var column_name = $('#hidden_column_name').val();
  var sort_type = $('#hidden_sort_type').val();
  var page = $('#hidden_page').val();
  fetch_data(page, sort_type, column_name, query);
 });

});

function favProperty(input) {
    let property_id = $(input).attr('property_id');
    let id = $(input).attr('id');
    $('#pageLoadingDiv').css('display','table');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
         url: '{{ url("/".$slug."/fav-unfav-Property") }}', //the page containing php script
        type: "post", //request type,
        dataType: 'json',
        data: {property_id: property_id},
        success: function(response) {
          $('#pageLoadingDiv').css('display','none');
            if(response.status == true) {
                swal('Success',response.message,'success');               
                if(response.css == true) {
                  $(input).parent().addClass('active');
                } else {
                    $(input).parent().removeClass( "active" );
                }
                
            } else {
                swal('Canceled',response.message,'error');
                $(input).parent().removeClass( "active" );
            }
        }
    });
}
$(document).ready(function(){

 $('#address').keyup(function(){ 
        var query = $(this).val();
        if(query != '')
        {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '<?= url('/') ?>/fetchAddress',
                method:"POST",
                data:{query:query},
                success:function(data){
                    $('#addressList').fadeIn();  
                    $('#addressList').html(data);
                }
            });
        }
    });

    $(document).on('click', '#addressList ul li', function(){  
        $('#address').val($(this).text());  
        $('#addressList').fadeOut();  
    });
});
 </script>
@endpush
@push('custom-styles')
<!-- <link link rel="stylesheet" type="text/css" href="{{ url('public/front_end/assets/css/croppie.min.css') }}">  -->
<style type="text/css">
#mapCanvas {
    width: 100%;
    height: 100%;
}
</style>
@endpush