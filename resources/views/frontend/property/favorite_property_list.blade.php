@extends('frontend.layout.app_without_login')
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
<div class="fav_list_container">
    <div class="custom_container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <div class="path_link">
                        <a href="#" class="current_page">My Favourites</a>
                    </div>
                </div>
                <div class="col-sm-8 text-sm-right text-center">
                    <ul class="nav nav-tabs theme_text_tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true">List View</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="map-tab" data-toggle="tab" href="#map" role="tab" aria-controls="map" aria-selected="false">Map View</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="tab-content theme_text_tabs_content" id="myTabContent">
        <div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">
            <div class="custom_container">
                <div class="container-fluid">
                    @if(!empty($property_lists) && count($property_lists)>0)
                      <div class="property-listing-class">
                        <div class="row equal_height">
                          <?php $i = 1;?>
                            @foreach($property_lists as $property_list)
                            <?php 
                             $string = array(', ',',', ' ', '/', "'");
                            $replace   = array('-', '-', '-', '-', '-');
                            $address = str_replace($string, $replace, $property_list['address']);
                            $address = str_replace('--', '-', $address);
                            $url  = $slug.'/property-detail/'.$address.'-'. $property_list['id'];  ?>
                            
                                <div class="col-md-4 col-sm-6 equal_height_container" id="list_view_data">
                                    <div class="card-home" id="">
                                        <a href="{{url($url) }}" class="property_link">
                                          @if($property_list['agency_id']!=97)
                                            <div class="item" style="background-image: url('{{url('public/uploads/properties_images/'.$property_list['id'].'/'.$property_list['main_image'])}}')"></div>
                                          @else
                                          <div class="item" style="background-image: url('{{url('public/uploads/properties_images/16/159767026246e6cfde-fc64-4dc3-a4f0-dfe0216c849c.jpg')}}')"></div>
                                          @endif
                                        </a>
                                        
                                        
                                        
                                        @if(Auth::check() == true)
                                        <div class="middle-info fav_propery_box active">
                                            <a href="#" class="fav-property favUnfavitem" onclick="favProperty(this);" id="{{ $i }}"  property_id = "{{ $property_list['id'] }}"><i class="fas fa-heart"></i></a>
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
                        <div id="property_list_data">
                                {!! $property_lists->links() !!} 
                        </div>
                      </div>
                      <div class="property-not-found-class" style="display: none">
                        <p>No properties are marked as favourite.</p>
                      </div>
                  @else
                      <div class="property-listing-class">
                      </div>
                      <div class="property-not-found-class">
                        <p>No properties are marked as favourite.</p>
                      </div>
                  @endif
                  <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                  <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                  <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="DESC" />
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="map" role="tabpanel" aria-labelledby="map-tab">
            <div class="fav_map_div" style="height: 540px">
                @if(!empty($property_lists) && count($property_lists)>0)
                <div id="mapCanvas1"></div>
                @else
                <div class="custom_container">
                  <div class="container-fluid">
                    <div class="property-not-found-class">
                      <p>No properties are marked as favourite.</p>
                    </div>
                  </div>
                </div>
                @endif
            </div>
        </div>
    </div>   
    
</div>
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
@endpush
 @push('custom-scripts')
{{-- <script type="text/javascript" src="{{ url('public/assets/js/croppie.js') }}"></script>
<script type="text/javascript" src="{{ url('public/js/customer/profile/profile.js') }}"></script> --}}
<script type="text/javascript" src="{{ url('public/assets/js/croppie.js') }}"></script>
 <!-- <script type="text/javascript" async defer src="https://maps.googleapis.com/maps/api/js?key=<?=env('GOOGLE_MAP_API_KEY')?>=places&sensor=false"></script> -->
 <script type="text/javascript">
$(document).ready(function(){
   var base_url='<?= url('/') ?>';

 $(document).on('click', '.pagination a1', function(event){
  event.preventDefault();

  //var page = $(this).attr('href').split('page=')[1];
  var page = $(this).attr('href').split('page=')[1];
  console.log('page:'+$(this).data('url'));
  //var query = $('#serach').val();
  var query = '';
  $('li').removeClass('active');
  $(this).parent().addClass('active');
  
  
  var pageurl='<?= url('/'.$slug) ?>/favorite-properties-search?page='+page;
  if(pageurl!=window.location){
    window.history.pushState({path:pageurl},'',pageurl);  
  }
  var pageurl='<?= url('/'.$slug) ?>/favorite-properties-search?page='+page+'&methodType=ajax';
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
            $('.property-listing-class').html(response['html']);
            getFavMapMarker12();
            $('.property-not-found-class').css('display','none');
            $('.property-listing-class').css('display','block');
          }
        }
    });

  });
  getFavMapMarker12();
  function getFavMapMarker12(){
    
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '<?= url('/') ?>/getFavMapMarker',
        type: "post", //request type,
        data:{},
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
    	center: new google.maps.LatLng(0, 0),
	    zoom: 0,
	    minZoom: 5, 
	    maxZoom: 15,
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("mapCanvas1"), mapOptions);
    map.setTilt(45);
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
          var newbeds='<span><i class="fas fa-bed"></i>'+value['beds']+' Bad  </span>';
        }else{
          var newbeds='';
        }
        if(value['baths'] !='' && value['baths']!=null){
          var newbath='<span><i class="fas fa-bookmark"></i>'+value['baths']+' Baths  </span>';
        }else{
          var newbath='';
        }
        if(value['sq_feet']!=''){
          var newsqf='<span><i class="fas fa-cube"></i>'+value['sq_feet']+' SF  </span>';
        }else{
          var newsqf='';
        }
      if(value['agency_id']==97){
        var arraymarkerdescription=['<div class="info_content map_info_window"><a href="'+value['url']+'"><div class="map_propery_img"><img src="'+base_url+'/public/uploads/properties_images/16/159767026246e6cfde-fc64-4dc3-a4f0-dfe0216c849c.jpg"></div></a><div class="map_pro_detail"><p class="price">$'+value['price']+' <i class="fas fa-heart active" onclick="favProperty(this);" property_id = "'+value['id']+'"></i></p><a href="'+value['url']+'"><p>'+newbeds+newbath+newsqf+'</p><h3 class="title">'+value['address']+'</h3></a></div>' + '</div>']
      }else{
        var arraymarkerdescription=['<div class="info_content map_info_window"><a href="'+value['url']+'" ><div class="map_propery_img"><img src="'+base_url+'/public/uploads/properties_images/'+value['id']+'/'+value['main_image']+'"></div></a><div class="map_pro_detail"><p class="price">$'+value['price']+' <i class="fas fa-heart active" onclick="favProperty(this);" property_id = "'+value['id']+'"></i></p><a href="'+value['url']+'" ><p>'+newbeds+newbath+newsqf+'</p><h3 class="title">'+value['address']+'</h3></a></div>' + '</div>']
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
        //map.panToBounds(latlngbounds); 
        //this.setZoom(8);
    
        //console.log(count($all_property_lists));
    // Set zoom level
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
    	<?php if($all_property_lists==1){ ?>
        this.setZoom(14);
	    <?php }else{?>
	    	console.log('Map Zoom:'+map.getZoom());
	    	this.setZoom(map.getZoom() + 3);
	    <?php } ?>
        google.maps.event.removeListener(boundsListener);
    });
  
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
                window.setTimeout(function(){window.location.reload();
                        },2000);
            } else {
                swal('Canceled',response.message,'error');
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
#mapCanvas1 {
    width: 100%;
    height: 100%;
}
</style>
@endpush