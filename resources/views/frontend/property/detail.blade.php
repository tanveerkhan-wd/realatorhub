@extends('frontend.layout.app_without_login')
<!-- @section('title','Properties Details') -->
@section('content')@section('content')
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
 }
 if(isset($general_settings['contact_form_title']) && !empty($general_settings['contact_form_title'])){
    $contact_form_title=$general_settings['contact_form_title'];
 }else{
    $contact_form_title='Contact Us';
 }
?>
<style type="text/css">
    @media(max-width: 767px){
        .bottom_footer {
            margin-bottom: 48px;
        }
    }
</style>
<input type="hidden" name="ListingId" value="{{ $property_data->latitude }}">
<input type="hidden" name="property_id" value="{{ $property_data->id }}">

<div class="property_detail fullpage_view" style="margin-top: 0px"> 
    <div class="property_slider_container">
    
        <div class="property_detail_slider">
            <!--Carousel Wrapper-->
            <div id="carousel-thumb" class="carousel slide carousel-fade carousel-thumbnails" data-ride="carousel">
                <!--Slides-->
                <div class="carousel-inner" role="listbox">
                    
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="{{url('public/uploads/properties_images/'.$property_data['id'].'/'.$property_data['main_image'])}}" alt="First slide">
                        </div>
                        @if(count($property_image_data) > 0) 
                        @foreach($property_image_data as $property_image_value)
                            <div class="carousel-item">
                                <img class="d-block w-100" src="{{url('public/uploads/properties_images/'.$property_data['id'].'/'.$property_image_value['image_name'])}}" alt="First slide">
                            </div>
                        @endforeach
                        @endif
                        <?php $youtebCount=1; ?>
                        @foreach($property_video_data as $property_video_value)
                            @if($property_video_value['type']==1)
                                <div class="carousel-item">
                                    <?php 
                                        $url=$property_video_value['video_link'];
                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

                                        $youtube_id = $match[1];
                                    ?>
                                    <iframe class="youtube-banner" id="youtube{{$youtebCount}}" width="560" height="315" src="https://www.youtube.com/embed/{{$youtube_id}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                                <?php $youtebCount++;?>
                            @else
                                <div class="carousel-item">
                                    <video id="vid" autoplay="false" loop="" muted="" style="min-width: 100%;min-height: 100%;width: auto;height: auto;">
                                        <source src="{{url('public/uploads/properties_video/'.$property_data['id'].'/'.$property_video_value['video_link'].'#t=1')}}" type="video/mp4">
                                    </video>
                                </div>
                            @endif
                        @endforeach
                </div>
                <!--/.Slides-->
                <!--Controls-->
                <a class="carousel-control-prev" href="#carousel-thumb" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel-thumb" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                <!--/.Controls-->
                <ol class="carousel-indicators">
                     <?php $sliderCount=0; ?>
                   
                        <li class="stope-video" data-target="#carousel-thumb" data-slide-to="{{$sliderCount}}" class="active"> <img class="d-block w-100" src="{{url('public/uploads/properties_images/'.$property_data['id'].'/'.$property_data['main_image'])}}"
                        class="img-fluid"></li>
                        <?php $sliderCount++; ?>
                        @if(count($property_image_data) > 0) 
                        @foreach($property_image_data as $property_image_value)
                            <li class="stope-video" data-target="#carousel-thumb" data-slide-to="{{$sliderCount}}"> <img class="d-block w-100" src="{{url('public/uploads/properties_images/'.$property_data['id'].'/'.$property_image_value['image_name'])}}"
                        class="img-fluid"></li>
                        <?php $sliderCount++; ?>
                        @endforeach
                        @endif
                        <?php $youtebCount=1; ?>
                        @foreach($property_video_data as $property_video_value)
                            @if($property_video_value['type']==1)
                                <li class="youtube" data-target="#carousel-thumb" data-count="{{$youtebCount}}" data-slide-to="{{$sliderCount}}">
                                    <?php 
                                        $url=$property_video_value['video_link'];
                                        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

                                        $youtube_id = $match[1];
                                    ?>
                                    <iframe  width="560" height="315" src="https://www.youtube.com/embed/{{$youtube_id}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </li>
                                <?php $youtebCount++; ?>
                            @else
                                <li class="stope-video" data-target="#carousel-thumb" data-slide-to="{{$sliderCount}}">
                                    <!-- <img class="d-block w-100" src="https://mdbootstrap.com/img/Photos/Others/Carousel-thumbs/img%20(121).jpg"
                                    class="img-fluid"> -->
                                    <video id="vid"  loop="" muted="" style="min-width: 100%;min-height: 100%;width: auto;height: auto;">
                                        <source src="{{url('public/uploads/properties_video/'.$property_data['id'].'/'.$property_video_value['video_link'].'#t=1')}}" type="video/mp4">
                                    </video>
                                </li>
                            @endif
                        <?php $sliderCount++; ?>
                        @endforeach
                    
                </ol>
            </div>
            <!--/.Carousel Wrapper-->
        </div>
    </div>
    <div class="property_detail_container">
        <section class="section overview_section">
            <div class="">
                <div class="container">
                    <div class="overview_data">
                        <p class="pro_desc"> {!! $property_data->description !!} </p>
                        <p class="addres">
                            <span class="price">${{ $property_data->price }}</span>
                            @if(Auth::check() == true)
                                @if(!empty($property_fav_data))
                                <span class="heart_icon"><a href="javascript:void(0);" class="favUnfavitem active" onclick="favProperty(this);" property_id = "{{ $property_data->id }}"><i class="fas fa-heart"></i></a></span>
                                @else
                                <span class="heart_icon"><a href="javascript:void(0);" class="favUnfavitem"><i class="fas fa-heart"></i></a></span>
                                @endif
                                
                            @else
                                <span class="heart_icon"><a href="javascript:void(0);" class="siginclickModel"><i class="fas fa-heart"></i></a></span>
                            @endif
                        </p>
                        <p class="city"> {{ $property_data->address}}</p>
                        <p class="bed_bath">
                            <span><i class="fas fa-bed"></i>BD</span>
                            <span><i class="fas fa-bookmark"></i>BA</span>
                            <span><i class="fas fa-cube"></i>0 SF</span>
                        </p>
                        <p class="property_info">
                            @if(!empty($property_data->beds))
                            <span>{{$property_data->beds }} Beds</span>
                            @endif
                            @if(!empty($property_data->baths))
                            <span>{{ $property_data->baths }} Baths</span>
                            @endif
                            @if(!empty($property_data['property_units']))
                                @foreach($property_data['property_units'] as $key => $unit)
                                    <span>Unit{{$key+1}}</span>
                                    @if($property_data->type==3)
                                        <span>{{$unit['sqft'] }} Sq. Ft</span>
                                    @else
                                        <span>{{$unit['sqft'] }} Beds</span>
                                    @endif
                                    <span>{{$unit['baths'] }} Baths</span>
                                @endforeach
                            @endif
                            @if(!empty($property_data->sq_feet))
                            <span>{{ $property_data->sq_feet }} Sq Ft</span>
                            @endif
                            @if(!empty($property_data->lot_sqft))
                            <span>{{ $property_data->lot_sqft }} Acres</span>
                            @endif
                            @if(!empty($property_data->annual_tax))
                            <span>Tax Annual Amount {{ $property_data->annual_tax}}</span>
                            @endif
                            @if(!empty($property_data->type))
                            <span>Property Type 
                                @if($property_data->type==1) Single Homes @elseif($property_data->type==2) Multifamily  @elseif($property_data->type==3) Commercial  @elseif($property_data->type==4) Industrial @elseif($property_data->type==5) Lot @endif</span>
                            @endif
                            @if(!empty($property_data->total_living_sqft))
                            <span>Total Living Sq Ft {{ $property_data->total_living_sqft}}</span>
                            @endif
                            @if(!empty($property_data->total_commercial_space))
                            <span>Total Commercial Space {{ $property_data->total_commercial_space}}</span>
                            @endif
                            @if(!empty($property_data->total_industrial_space))
                            <span>Total Industrial Space {{ $property_data->total_industrial_space}}</span>
                            @endif
                            @if(!empty($property_data->occupied))
                            <span>Occupied @if($property_data->occupied==1) Occupied @else Vacant @endif</span>
                            @endif
                            @if($property_data->hoa!='')
                            <span>HOA @if($property_data->hoa==1) Yes @else No @endif</span>
                            @endif
                            @if(!empty($property_data->zoning))
                            <span>Zoning {{ $property_data->zoning}}</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <section class="section map_section">
            <div class="container">
                <div class="row equal_height">
                    <div class="col-lg-8 col-md-6 equal_height_container p-0">
                        <div class="map_div">
                            <div id="mapCanvas"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 equal_height_container p-0">
                        <div class="contact_form_container form-bg">
                            <div class="container">
                                <form class="contact_form text-center" method="post" id="property_contact_form">
                                    <input type="hidden" name="property_address" value="{{ $property_data->address }}" >
                                    <input type="hidden" name="property_id" value="{{ $property_data->id }}" >
                                    
                                    <h5 class="contact_form_title">{{$contact_form_title}}</h5>
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" name="property_name" placeholder="Enter Full Name" class="form-control icon_control user_icon" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="property_email" placeholder="Enter email" class="form-control icon_control mail_icon" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="property_number" placeholder="Enter phone" class="form-control icon_control phone_icon" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Note</label>
                                        <textarea name="property_message" placeholder="Type here..." class="form-control icon_control"></textarea>
                                    </div>
                                    <div class="form-group">
                                    <button class="theme-btn btn-color btn-text btn-size filter_search_btn">Submit</button>
                                  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>  
</div>
<?php 
    $userLpgo='';
    if(Auth::check()==true){
        if(Auth::user()->user_type=='1'){

            $agencyDetails=\App\Models\AgencyModel::where('user_id',Auth::user()->id)->first();
            //echo "<pre>"; print_r($agencyDetails); exit;
            if(!empty($agencyDetails->agency_logo)){ 
                $userLpgo=url('public/uploads/profile_photos/'.$agencyDetails->agency_logo);
            }else{
                $userLpgo=url('public/assets/images/ic_user_black.png');
            } 
        }else{
            if(!empty(Auth::user()->profile_img)){
                $userLpgo=url('public/uploads/profile_photos/'.Auth::user()->profile_img);
            }else{ 
                $userLpgo=url('public/assets/images/ic_user_black.png');
            } 
        }
    }
    
?>
<?php 
    if(!empty($agentDetails->profile_img)){
        $agentImage=url('/public/uploads/profile_photos/'.$agentDetails->profile_img);
    }else{
        $agentImage=url('public/assets/images/ic_user_black.png');
    }
    $agentName=$agentDetails->first_name.' '.$agentDetails->last_name;
?>
@if(Auth::check() == true)
    @if(Auth::user()->user_type==3)
        <input type="hidden" name="user" id="user" value="{{Auth::user()->id}}">
        <div class="show-chat-box" data-id="1"><div class="chat_btn"><img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/ic_msg.png"></div></div>
        <script type="text/javascript">
            setTimeout(function () {
                createGroute();
            }, 10000);
        </script>
    @else
        <div class="chat-error" data-type="1"><div class="chat_btn"><img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/ic_msg.png"></div></div>
    @endif
@else
    <div class="chat-error" data-type="2"><div class="chat_btn"><img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/ic_msg.png"></div></div>
@endif
<input type="hidden" name="chat_box_type" value="1">
@if(Auth::check() == true)
<div class="chat-box" style="display: none;">
    <div class="inbox_content">
        <div class="chat_box_header">
            <div class="chat_box_header_top">
                <div class="inbox_img">
                    <img src="{{$agentImage}}">
                </div>
                <p class="name">{{$agentName}}</p>
            </div>
        </div>
        <div id="chat_area_{{$roomExist}}" class="chat_section slim-scroll">
            @if(!empty($chatList))
                @foreach($chatList as $message)
                    <?php 
                        if($message['user_type']=='1'){
                        $agencyDetails=\App\Models\AgencyModel::where('user_id',$message['from_id'])->first();
                            if(!empty($agencyDetails->agency_logo)){ 
                                $chatUserLpgo=url('public/uploads/profile_photos/'.$agencyDetails->agency_logo);
                            }else{
                                $chatUserLpgo=url('public/assets/images/ic_user_black.png');
                            } 
                        }else{
                            if(!empty($message['profile_img'])){
                                $chatUserLpgo=url('public/uploads/profile_photos/'.$message['profile_img']);
                            }else{ 
                                $chatUserLpgo=url('public/assets/images/ic_user_black.png');
                            } 
                        }
                    ?>
                    @if($message['from_id']==Auth::user()->id)
                        
                        <div class="rply">
                            <div class="msg_txt">
                                <p>
                                    <span class="chat_name">{{$message['first_name'].' '.$message['last_name']}}</span><br>
                                    @if($message['message_type']=='2')
                                        <img src="{{url('/'.$message['body'])}}"><br>
                                    @else
                                        {{$message['body']}}<br>
                                    @endif
                                    <span class="chat_time">
                                        <?php $timezone = getCurrentUserTimeZone(Auth::User()->id);
                                            $start_time = changeTimeByTimezone($message['created_date'],$timezone); echo $start_time;?></span>
                                </p><br>
                            </div>
                            <div class="rply_profile">
                                <img src="{{$chatUserLpgo}}">
                            </div>
                        </div> 
                    @else
                        <div class="msg">
                            <div class="msg_profile">
                                
                                <img src="{{$chatUserLpgo}}">
                            </div>
                            <div class="msg_txt">
                                <p>
                                    <span class="chat_name">{{$message['first_name'].' '.$message['last_name']}}</span><br>
                                    @if($message['message_type']=='2')
                                        <img src="{{url('/'.$message['body'])}}"><br>
                                    @else
                                    {{$message['body']}}<br>
                                    
                                    @endif
                                    <span class="chat_time"><?php $timezone = getCurrentUserTimeZone(Auth::User()->id);
                                            $start_time = changeTimeByTimezone($message['created_date'],$timezone); echo $start_time;?></span>
                                </p><br>
                            </div>
                        </div>

                           
                    @endif  
                @endforeach 
            @endif           
        </div>
        <div class="type_section">
            <form class="chat-form" method="post" id="chat-form" enctype="multipart/form-data" onsubmit="return false;">
                <input type="hidden" name="user" id="user" value="{{Auth::user()->id}}">
                <input type="hidden" name="to" id="to" value="">
                <input type="hidden" name="room_id" id="room_id" value="{{$roomExist}}">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input type="hidden" name="profile_image" id="profile_image" value="{{$userLpgo}}">
                <input type="hidden" name="user_name" id="user_name" value="{{Auth::user()->first_name.' '.Auth::user()->last_name}}">
                <input type="text" name="m" id="m" placeholder="Replay to {{$agentName}}">
                <div class="file_icon_div">
                    <div class="file_icon_img">
                        <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/attach.png">
                        <input type="file" name="chat_img" id="chat_img">
                    </div>
                </div>
                <button onclick="submitfunction()"><img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/message_white.png"></button>
            </form>
        </div>
    </div>
</div>
<input type="hidden" name="current_room_id" id="current_room_id" value="{{$roomExist}}">
<div class="modal fade auth_modal" id="image_popup" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="{{ url('public/assets/images/ic_close.png') }}" alt="close" class="img-close"></span>
                </button>
                <img class="image_popup_class" src="{{ url('public/front_end/') }}/assets/images/close.png" alt="close">
        </div>
        
    </div>  
</div>
<?php 
$timezone=getCurrentUserTimeZone(Auth::User()->id);
$timezone = trim($timezone);
?>
<script type="text/javascript">
    var timezone='{{$timezone}}';
</script>
@endif
<?php
    $site_title=$property_data->address.': Homes for Sale - Realtorhubs.com';
    $description='';
    
?>

<?php 
 if(!empty($property_map_pin)){
    $map_icon= url('/').'/public/uploads/map_pin/'.$property_map_pin;
 }else{
    $map_icon='';
 }
?>       
   
@endsection
 @push('custom-scripts')
 <script type="text/javascript">
     var image_url = '{{url("/")}}/';
     console.log(image_url);
 </script>
 <script type="text/javascript"> var current_room_id=$('#current_room_id').val(); </script>
 <script async src="https://platform-api.sharethis.com/js/sharethis.js#property=5d0097614351e90012650407&product="sticky-share-buttons"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.23/moment-timezone-with-data-2012-2022.min.js"></script>
 <script type="text/javascript" src="{{url('/public/js/common/chat/chat.js')}}"></script>

 <script type="text/javascript">
    
    $('.show-chat-box').on('click', function(ev) { 
        var chat_id=$("input[name=chat_box_type]").val(); 
        if(chat_id==1){
            $("input[name=chat_box_type]").val(2);
            $('.chat-box').css('display','block'); 
        }else{
            $("input[name=chat_box_type]").val(1);
            $('.chat-box').css('display','none'); 
        }
    });
    $('.chat-error').on('click', function(ev) { 
        var type=$(this).data('type'); 
        if(type==1){
            swal('Canceled','Please login with customer id','error');
        }
        if(type==2){
            swal('Canceled','Please login first','error');
        }
        
    });
    function createGroute(){
        var property_id='{{ $property_data->id }}'; 
        var from='{{$property_data->agent_id}}';
        var profile_image='{{$agentImage}}';
        var name='{{$agentName}}';
        var message_type='text';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
             url: "{{ url('/common/createGroup') }}",
            type: "post", //request type,
            dataType: 'json',
            data: {property_id: property_id},
            success: function(response) { 
                console.log(response.message);
                $("input[name=chat_box_type]").val(2);
                $('.chat-box').css('display','block');
                if(response.code==200){
                    $(".chat_section").attr("id", "chat_area_"+response.room_id);
                    $('#current_room_id').val(response.room_id);
                    current_room_id=response.room_id;
                    $('#room_id').val(response.room_id);
                    var responseMessage=response.message;
                    socket.emit('chatMessage', from, {msg:responseMessage,name:name,message_type:message_type,profile_image:profile_image});
                }
                $("#chat_area_"+current_room_id).scrollTop($('#chat_area_'+current_room_id)[0].scrollHeight);
                $(".chat_section").scrollTop($(".chat_section")[0].scrollHeight);
            }
        });
    }

     function initMap() {

    var map;
    var bounds = new google.maps.LatLngBounds();
    var mapOptions = {
        // zoom: 25,
        mapTypeId: 'roadmap'
    };
                    
    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
    map.setTilt(100);
    //Create LatLngBounds object.
    
    var markers = [
        <?php if(!empty($property_data)){ 
                    echo '["'.$property_data->address.'", '.$property_data->latitude.', '.$property_data->longitude.', "'.$map_icon.'"],'; 
            } 
            ?>
    ];
                        
    // Info window content
    var infoWindowContent = [
        <?php if(!empty($property_data)){ 
             ?>
                ['<div class="info_content"><p><?php echo $property_data->address; ?></p></div>'],
        <?php  
        } 
        ?>
    ];
        
    // Add multiple markers to map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    var latlngbounds = new google.maps.LatLngBounds()
    // Multiple markers location, latitude, and longitude
    // Place each marker on the map  
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
        google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));
        google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.close(map, marker);
            }
        })(marker, i));
        
         //Extend each marker's position in LatLngBounds object.
            latlngbounds.extend(marker.position);
        // Center the map to fit all markers on the screen
        //map.fitBounds(bounds);
        
    }
//Get the boundaries of the Map.
        var bounds = new google.maps.LatLngBounds();
 
        //Center map and adjust Zoom based on the position of all markers.
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);
    // Set zoom level
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });
}

// Load initialize function
google.maps.event.addDomListener(window, 'load', initMap);



$('#property_contact_form').submit(function (e) {
        e.preventDefault();
        $('.loader-outer-container').css('display', 'table');
        var formStatus = $(this).validate().form();
      if(formStatus==true){
        $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '<?= url('/'.$slug) ?>/property-contact-form', //the page containing php script
        type: "post", //request type,
        // dataType: 'json',
        data:$('#property_contact_form').serialize(),
        success: function(response) { 
            $('.loader-outer-container').css('display', 'none');
            if(response.status == true) {
                $('input[name=property_name]').val('');
                $('input[name=property_email]').val('');
                $('input[name=property_number]').val('');
                $('textarea[name=property_message]').val('');
                toastr.success(response.message);
            } else {
                toastr.error(response.message);
            }
        }
    });
    }
    
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
              $(input).attr("onclick", "").unbind("click");
                if(response.status == true) {
                    swal('Success',response.message,'success');               
                    if(response.css == true) {
                      $(input).addClass('active');
                    } else {
                        $(input).removeClass( "active" );
                    }
                } else {
                    swal('Canceled',response.message,'error');
                    $(input).removeClass( "active" );
                }
            }
        });
    }
$(document).ready(function(){
    
$("#property_contact_form").validate({
        rules: {
            property_name: {
                required: true,
            },
            property_email:{
                required: true,
                email: true,
            },
            property_number:{
                required: true,
            },
        },
        messages: {
            property_name: "Please enter name",
            property_number: "Please enter mobile number",
            property_email:{
                required:"Please enter email",
                email:"Please enter valide email",
            },
        },
    });
});
$(document).ready(function() {
  $('.youtube').on('click', function(ev) {
    var url = $(".youtube-banner").attr('src');
    url=url.replace("?autoplay=1", "");
    $('.youtube-banner').attr('src', url);
 	var count=$(this).data('count');
    $("#youtube"+count)[0].src += "?autoplay=1";
    ev.preventDefault();
 
  });
  $('.stope-video').on('click', function(ev) {
    var url = $(".youtube-banner").attr('src');
    url=url.replace("?autoplay=1", "");
    $('.youtube-banner').attr('src', url);
    ev.preventDefault();
 
  });
});
$(".msg_txt img").click(function(){
    var url = $(this).attr('src');
    $(".image_popup_class").attr('src', url);
    $('#image_popup').modal('show');
});
function image_popup(url){
    $(".image_popup_class").attr('src', url);
    $('#image_popup').modal('show');
}
$("#chat_area_"+current_room_id).scrollTop($('#chat_area_'+current_room_id)[0].scrollHeight);
    $(".chat_section").scrollTop($(".chat_section")[0].scrollHeight);
</script>
@endpush
@push('custom-styles')
<title><?php echo $site_title; ?></title>
<meta property="og:title" content="{{ $property_data['seo_title'] }}">
<meta name="description" content="{{ $property_data['seo_description'] }}">
<meta property="og:description" content="{{ $property_data['seo_description'] }}">
<style type="text/css">
#mapCanvas {
    width: 100%;
    height: 100%;
}
.property_detail_slider ol.carousel-indicators iframe {
    pointer-events: none;
}
</style>

@endpush