<?php $notification=get_notification();
  if(!empty($notification)){
    $notificationCount=count($notification);
  }else{
    $notificationCount=0;
  }
 ?>
<nav class="navbar navbar-expand top_menu fixed-top dashboard_header" id="main_nvigation">
  <div class="container-fluid">
      <div class="logo_img">
          <a href="{{route('agency.home')}}">
            <?php  ?>
            @if(!empty($adminDetails['website_logo']))
            <img src="{{  url('public/uploads/common_settings').'/'.$adminDetails['website_logo'] }}" id="main_wb_logo" alt="logo">
              @else
             <img src="{{ url('public/admin/dist/img/CashGuru.png') }}" id="main_wb_logo" alt="logo">
             @endif
            
          </a>
      </div>

      @if(\Illuminate\Support\Facades\Auth::user()->is_setup == \App\Models\UserModel::IS_SETUP_YES)
      <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
              <li class="nav-item ">
                  <a class="nav-link nav_icon_box" href="{{url('agency/chat-list')}}">
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
                  <a href="#" class="dropdown-toggle nav-link nav_icon_box read_all_notifications_admin" data-toggle="dropdown" aria-expanded="false">
                    <img src="{{url('public/assets/images/ic_bell.png') }}">
                    <span class="label label-warning noti_counter">{{ countNotification(Auth::user()->id) }}</span>
                  </a>
                  <ul class="dropdown-menu noti_menu">
                    <?php 
                      if(!empty($notification) && count($notification)>0){
                          foreach ($notification as $notification_value) {
                              if(!empty($notification_value)){
                              $url = url('/agency').$notification_value->link;
                              }
                              else{
                                  $url = url('/agency');
                              }
                            //$notification_desc=json_decode($notification_value->notification_desc);
                            echo '<li><a href="'.$url.'"><p class="body">'.$notification_value->notification_desc.'</p></a></li>';
                          }
                      }else{
                          echo '<li><p class="body">No new Notification found</p></li>';
                      }
                      
                    ?>
                    <li class="footer"><a href="{{ route('notification-listing') }}" class="theme-btn btn-color btn-text">View all</a></li>
                  </ul>
            </li>
          </ul>
      </div>
      @endif

  <ul class="navbar-nav droup_middle">
        <li class="dropdown profile_drop_div">
      <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <div class="user_pic_box">
          <div class="user_pic">
            <?php 
            if(!empty($userDetails->agency->agency_logo)){ ?>
              <img src="{{url('public/uploads/profile_photos/'.$userDetails->agency->agency_logo)}}" class="user-image" alt="User Image">
            <?php }else{ ?>
              <img src="{{url('public/assets/images/ic_user.png')}}" class="user-image" alt="User Image">
            <?php }
            ?>
            
          </div>
        </div>
        {{$userDetails->first_name.' '.$userDetails->last_name}}
        </button>
      <div class="dropdown-menu sett_drop" aria-labelledby="dropdownMenu2">
        @if(\Illuminate\Support\Facades\Auth::user()->is_setup == \App\Models\UserModel::IS_SETUP_YES)
        <a href="{{url('agency/my-account')}}">
            <button class="dropdown-item">
              <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="sett_drop_ic"> 
              <img src="{{url('public/assets/images/ic_setting.png')}}" class="sett_drop_hover_ic"> My Profile
            </button>
        </a>
        <a href="{{url('/'.$userDetails->agency->slug.'/properties')}}" target="_blank">
            <button class="dropdown-item">
              <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="sett_drop_ic"> 
              <img src="{{url('public/assets/images/ic_setting.png')}}" class="sett_drop_hover_ic"> Visit Website
            </button>
        </a>
        @endif
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
      <li class="@if(Request::url() == url('agency/home'))active @endif">
          <a href="{{url('agency/home')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_dashboard_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_dashboard.png')}}" class="active_menu_icon">
            </span>
            <span>Dashboard</span>
          </a>
      </li>
        <li class="@if(Request::url() == route('agency.property.list'))active @endif">
          <a href="{{route('agency.property.list')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_property.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_property_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Properties</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/leads'))active @endif">
          <a href="{{url('agency/leads')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_leads_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_leads_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Leads</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/agent'))active @elseif(Request::url() == url('agency/agent/add'))active @elseif(Request::url() == url('agency/agent/edit/{id}'))active @endif">
          <a href="{{url('agency/agent')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_agent_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_agent_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Agents</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/customer-list'))active @endif">
          <a href="{{route('agency.customer.list')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_customer_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_customer_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Customers</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/sendmail'))active @endif">
          <a href="{{route('agency.send.mail')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_send_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_send.png')}}" class="active_menu_icon">
            </span>
            <span>Send Email</span>
          </a>
        </li>
        <li class="@if(strpos( trim(Request::url()),trim(url('agency/subscription'))) !== false)active @endif">
          <a href="{{route('agency.subscription')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_subscribe_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_subscribe.png')}}" class="active_menu_icon">
            </span>
            <span>My Subscriptions</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/settings'))active @endif">
          <a href="{{url('agency/settings')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_setting.png')}}" class="active_menu_icon">
            </span>
            <span>Settings</span>
          </a>
        </li>
  </ul>
  </div>
</section>