<?php 
  if(Auth::user()->user_type==2){ 
    $layoutName= 'agent.layout.app_with_login';
  }
  elseif(Auth::user()->user_type==3){
    $layoutName= 'frontend.layout.app_for_property';
  }
  else{ 
    $layoutName= 'agency.layout.app_with_login';
  }
?>
@extends($layoutName)
@section('title','Chat List')
@section('content')
<!-- 
View File for  List Credits
@package    Laravel
@subpackage View
@since      1.0
 -->
<style type="text/css">
    .side_content{
        padding: 0px 0px 0px;
    }
    @media (max-width: 767px){
        .side_content {
            padding: 80px 0 0px;
        }
    }
</style>
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
<?php 
    if(!empty($agent_id)){ ?>
    <div class="container-fluid">
        <div class="path_link">
            <a href="{{url('agency/agent')}}" class="">My Agents > </a> <a href="#" class="current_page">Chat List</a>
        </div>
        <div class=""> 
              <ul class="nav nav-tabs text-center theme_tab" id="myTab" role="tablist" style="margin-bottom: 40px;">
                  <li class="nav-item ">
                      <a class="nav-link "  id="agentProperty-tab" href="{{url('/agency/agent/view/'.$agent_id)}}"  >Agent Properties</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="designSettings-tab" href="{{url('/agency/agent/leads/'.$agent_id)}}" >Agent Leads</a>
                  </li>
                  <li class="nav-item active">
                      <a class="nav-link active" id="contactForm-tab" href="{{url('/agency/agent/chat-list/'.$agent_id)}}">Agent Chat Threads</a>
                  </li>
              </ul> 

        </div>
        <input type="hidden" name="agent_id" value="{{$agent_id}}">
    </div>
<?php }

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
?>
<div class="dash_sidebar_sidecontent">
    <div class="dash_side_content p-0">
        <div class="inbox_container">
            <div class="inbox_sidebar open">
                <div class="serchbar">
                    <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search" class="search_control">
                </div>
                <ul class="slim-scroll" id="myUL">

                    @foreach($chatRooms as $key=>$chatRoom)
                        <li class="chat-group-link @if($key==0) active @endif" id="chat_group_link_id_{{$chatRoom['room_id']}}" data-roomid="{{$chatRoom['room_id']}}" data-groupname="{{$chatRoom['group_name']}}">
                            <a href="javascript:void(0)">
                                <!-- <div class="inbox_img">
                                    <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                                </div> -->
                                @if($chatRoom['unread_count']>0)
                                    <p class="name have_msg">{{$chatRoom['group_name']}} </p><span class="msg_counter">{{$chatRoom['unread_count']}}</span>
                                @else
                                    <p class="name">{{$chatRoom['group_name']}}</p>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="inbox_content">
                <div id="chat_area_{{$current_room_id}}" class="chat_section slim-scroll">
                    @if(!empty($chatMessages))
                        @foreach($chatMessages as $message)
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
                                            <span class="chat_time"><?php $timezone = getCurrentUserTimeZone(Auth::User()->id);
                                            $start_time = changeTimeByTimezone($message['created_date'],$timezone);echo $start_time;?></span>
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
                <div class="text-right">
                    <div class="open_list">
                        <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/msg_white.png">
                    </div>
                </div>
                <div class="type_section">
                    <form class="chat-form" method="post" id="chat-form" enctype="multipart/form-data" onsubmit="return false;">
                        <input type="hidden" name="user" id="user" value="{{Auth::user()->id}}">
                        <input type="hidden" name="to" id="to" value="">
                        <input type="hidden" name="room_id" id="room_id" value="{{$current_room_id}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" name="profile_image" id="profile_image" value="{{$userLpgo}}">
                        <input type="hidden" name="user_name" id="user_name" value="{{Auth::user()->first_name.' '.Auth::user()->last_name}}">
                        <input type="text" name="m" id="m" placeholder="Replay to @if(!empty($chatRooms[0]['group_name'])){{$chatRooms[0]['group_name']}}@endif">
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
    </div>
</div>   
<input type="hidden" name="current_room_id" id="current_room_id" value="{{$current_room_id}}">
<!-- <div class="modal fade auth_modal" id="image_popup" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal_container">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><img src="{{ url('public/front_end/') }}/assets/images/close.png" alt="close" class="img-close"></span>
                </button>
                
            </div>
        </div>
    </div>
</div> -->
<!-- End Content Body -->
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
@endsection
@push('custom-styles')

@endpush
@push('custom-scripts')
<script type="text/javascript">
    $(".msg_txt img").click(function(){
        var url = $(this).attr('src');
        $(".image_popup_class").attr('src', url);
        $('#image_popup').modal('show');
    });
    function image_popup(url){
        $(".image_popup_class").attr('src', url);
        $('#image_popup').modal('show');
    }
    $('.open_list').on('click', function(){
        debugger;
        $('.inbox_sidebar').addClass('open',1000);

    });
    /*$('.inbox_sidebar ul').on('click', function(){
        $('.inbox_sidebar').removeClass('open',2000);
    });*/
     var image_url = '{{url("/")}}/';
     var current_room_id=$("#current_room_id").val();
     console.log(image_url);
     $(".chat-group-link").click(function() {
        $('.chat-group-link').removeClass('active');
        $(this).addClass('active');
        var room_id=$(this).data('roomid');
        var groupname=$(this).data('groupname');
        $('#current_room_id').val(room_id);
        current_room_id=room_id;
        $('#room_id').val(room_id);
        $('.slim-scroll').prepend($(this));
        $('.inbox_sidebar').removeClass('open');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
             url: "{{ url('/common/getChatMessage') }}",
            type: "post", //request type,
            dataType: 'json',
            data: {room_id: room_id},
            success: function(response) { 
                $("#m").attr("placeholder", "Replay to "+groupname);
                $("#chat_group_link_id_"+room_id).html('<a href=""><p class="name">'+groupname+'</p></a>');
                $(".chat_section").attr("id", "chat_area_"+room_id);
                $(".chat_section").html(response.html);                
            }
        });
    });
    
    function myFunction() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
    $("#chat_area_"+current_room_id).scrollTop($('#chat_area_'+current_room_id)[0].scrollHeight);
    $(".chat_section").scrollTop($(".chat_section")[0].scrollHeight);
 </script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.23/moment-timezone-with-data-2012-2022.min.js"></script>
 <script type="text/javascript" src="{{url('/public/js/common/chat/chat.js')}}"></script>
@endpush