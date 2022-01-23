<?php $notification=get_notification();
  if(!empty($notification)){
    $notificationCount=count($notification);
  }else{
    $notificationCount=0;
  }
  $get_agent_agency_data=get_agent_agency_data();
  //echo "<pre>"; print_r($get_agent_agency_data); exit;
 ?>
<nav class="navbar navbar-expand top_menu fixed-top dashboard_header" id="main_nvigation">
  <div class="container-fluid">
      <div class="logo_img">
          <a href="{{route('agent.home')}}">
            <?php  ?>
            @if(!empty($adminDetails['website_logo']))
            <img src="{{  url('public/uploads/common_settings').'/'.$adminDetails['website_logo'] }}" id="main_wb_logo" alt="logo">
              @else
             <img src="{{ url('public/admin/dist/img/CashGuru.png') }}" id="main_wb_logo" alt="logo">
             @endif
            <!-- <img src="https://cdn.shopify.com/s/files/1/0348/6243/2388/files/Digiteau_Logo_White_Bold_180x.png?v=1583265518" id="main_wb_logo" alt="logo"> -->
          </a>
      </div>

      <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
              <li class="nav-item ">
                  <a class="nav-link nav_icon_box" href="{{url('agent/chat-list')}}">
                    <img src="{{url('public/assets/images/ic_chat.png') }}">
                    <?php $allchatCount= getChatUnreadCount(); ?>
                    
                    <span id="header_user_chat_count">
                      @if($allchatCount > 0)
                      <span class="label label-warning noti_counter">{{ $allchatCount }}</span>
                      @endif
                    </span>
                  </a>
              </li>
              <!-- <li class="nav-item">
                  <a class="nav-link nav_icon_box" href="#">
                    <img src="{{url('public/assets/images/ic_bell.png') }}">
                    <span class="noti_counter">0</span>
                  </a>
              </li> -->
              <li class="dropdown notifications-menu nav-item">
                  <a href="#" class="dropdown-toggle nav-link nav_icon_box read_all_notifications_agent" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{url('public/assets/images/ic_bell.png') }}">
                    <span class="label label-warning noti_counter">{{ countNotification(Auth::user()->id) }}</span>
                  </a>
                  <ul class="dropdown-menu noti_menu">
                    <?php 
                      if(!empty($notification) && count($notification)>0){
                          foreach ($notification as $notification_value) {
                              if(!empty($notification_value)){
                              $url = url('/agent').$notification_value->link;
                              }
                              else{
                                  $url = url('/agent');
                              }
                            //$notification_desc=json_decode($notification_value->notification_desc);
                            echo '<li><a href="'.$url.'"><p class="body">'.$notification_value->notification_desc.'</p></a></li>';
                          }
                      }else{
                          echo '<li><p class="body">No new Notification found</p></li>';
                      }
                      
                    ?>
                    <li class="footer"><a href="{{ route('notification.listing.agent') }}" class="theme-btn btn-color btn-text">View all</a></li>
                  </ul>
            </li>
          </ul>
      </div>

  <ul class="navbar-nav droup_middle">
        <li class="dropdown profile_drop_div">
      <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="user_pic_box">
          <div class="user_pic">
            <?php 
            if(!empty(\Illuminate\Support\Facades\Auth::user()->profile_img)){ ?>
              <img src="{{url('public/uploads/profile_photos/'.\Illuminate\Support\Facades\Auth::user()->profile_img)}}" class="user-image" alt="User Image">
            <?php }else{ ?>
              <img src="{{url('public/assets/images/ic_user.png')}}" class="user-image" alt="User Image">
            <?php }
            ?>
            
          </div>
        </div>
        {{$userDetails->first_name.' '.$userDetails->last_name}}
        </button>
      <div class="dropdown-menu sett_drop" aria-labelledby="dropdownMenu2">
        <a href="{{route('agent.my.account')}}">
            <button class="dropdown-item">
              <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="sett_drop_ic"> 
              <img src="{{url('public/assets/images/ic_setting.png')}}" class="sett_drop_hover_ic"> My Profile
            </button>
        </a>
        <a href="{{url('/'.$get_agent_agency_data->agency->slug.'/properties')}}" target="_blank">
            <button class="dropdown-item">
              <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="sett_drop_ic"> 
              <img src="{{url('public/assets/images/ic_setting.png')}}" class="sett_drop_hover_ic"> Visit Website
            </button>
        </a>
        <a href="{{url('agency/logout')}}">
            <button class="dropdown-item" type="button">
              <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="sett_drop_ic">
              <img src="{{url('public/assets/images/ic_setting.png')}}" class="sett_drop_hover_ic"> Sign Out 
            </button>
          </a>            
      </div>
    </li>
  </ul>
  <a href="#x" id="slide" class="navbar-toggle"><i class="fas fa-bars mobile_menu_icon"></i></a>
  </div>
</nav>

<!-- Mobile menu -->
<section>
  <div class="sideoff-off">
    <div  class="navbar-header"><a  class="navbar-toggle mobile-menu-style dash-navbar-toggle" id="slideclose"><i class="fas fa-times"></i></a></div>
    <ul class="res-navbar-nav ml-auto res-dash-nav">
    <li class="@if(Request::url() == url('agent/home'))active @endif">
      <a href="{{route('agent.home')}}">
        <span class="menu_icon">
          <img src="{{url('public/assets/images/ic_dashboard_grey.png')}}" class="menu_icon">
          <img src="{{url('public/assets/images/ic_dashboard.png')}}" class="active_menu_icon">
        </span>
        <span>Dashboard</span>
      </a>
    </li>
    <li class="@if(Request::url() == route('agent.property.list'))active @endif">
      <a href="{{route('agent.property.list')}}">
        <span class="menu_icon">
          <img src="{{url('public/assets/images/ic_property.png')}}" class="menu_icon">
          <img src="{{url('public/assets/images/ic_property_white.png')}}" class="active_menu_icon">
        </span>
        <span>My Properties</span>
      </a>
    </li>
    <li class="@if(Request::url() == url('agent/leads'))active @endif">
      <a href="{{url('agent/leads')}}">
        <span class="menu_icon">
          <img src="{{url('public/assets/images/ic_leads_grey.png')}}" class="menu_icon">
           <img src="{{url('public/assets/images/ic_leads_white.png')}}" class="active_menu_icon">
        </span>
        <span>My Leads</span>
      </a>
    </li>
    <li class="@if(Request::url() == url('agent/sendmail'))active @endif">
      <a href="{{route('agent.send.mail')}}">
        <span class="menu_icon">
          <img src="{{url('public/assets/images/ic_send_grey.png')}}" class="menu_icon">
           <img src="{{url('public/assets/images/ic_send.png')}}" class="active_menu_icon">
        </span>
        <span>Send Email</span>
      </a>
    </li>
  </ul>
  </div>
</section>