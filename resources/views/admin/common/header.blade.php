<?php $notification=get_notification();
  if(!empty($notification)){
    $notificationCount=count($notification);
  }else{
    $notificationCount=0;
  }
 ?>
 <style type="text/css">
   li.dropdown.notifications-menu ul.dropdown-menu li a {
      display: inline-block;
      white-space: pre-line;
  }
 </style>
<header class="main-header">
  <!-- Logo -->
  <a href="{{ url('admin/home') }}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini">relatorhubs</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg">
       @if(!empty($website_logo))
        <img src="{{  url('public/uploads/common_settings').'/'.$website_logo }}" class="logo_img">
        @else
       <img src="{{ url('public/admin/dist/img/CashGuru.png') }}" class="logo_img">
       @endif
    </span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav notification-bell-class">

        <li class="dropdown notifications-menu">
              <a href="#" class="dropdown-toggle read_all_notifications_admin" data-toggle="dropdown" aria-expanded="false" >
                <i class="fa fa-bell-o fa-2x"></i>
                <span class="label label-warning noti_counter">{{ countNotification(Auth::user()->id) }}</span>
          </a>
              <ul class="dropdown-menu">                
                
                <?php 
                  if(!empty($notification) && count($notification)>0){
                      foreach ($notification as $notification_value) {
                          if(!empty($notification_value)){
                          $url = url('/admin').$notification_value->link;
                          }
                          else{
                              $url = url('/admin');
                          }
                        //$notification_desc=json_decode($notification_value->notification_desc);
                        echo '<li><a href="'.$url.'"><p class="body">'.$notification_value->notification_desc.'</p></a></li>';
                      }
                  }else{
                      echo '<li><p class="body">No new Notification found</p></li>';
                  }
                  
                ?>
                <li class="footer"><a href="{{ url('/admin/notifications') }}">View all</a></li>
              </ul>
        </li>

       <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">

            @if(!empty(\Illuminate\Support\Facades\Auth::User()->profile_img))
            <div class="nav_profile user-image">
              <img src="{{ url('public/uploads/user_profile/'.\Illuminate\Support\Facades\Auth::User()->profile_img) }}" class="" >
            </div>

            @else
            <div class="nav_profile logo_img">
              <img src="{{ url('/public/images/default-user-image.png') }}" class="">
            </div>
            @endif
{{--            <img src="{{ url('public/uploads/user_profile/').'/'.Auth::user()->user_image }}" class="user-image" alt="User Image">--}}
            <span class="">{{ \Illuminate\Support\Facades\Auth::user()->user_name }}
            <img src="{{ url('public/admin/dist/img/down_dark_arrow.png') }}">
            </span>
          </a>
          <ul class="dropdown-menu">

            <!-- User image -->
            <!-- <li class="user-header">
{{--              <img src="{{ url('public/uploads/user_profile/').'/'.Auth::user()->user_image }}" class="img-circle" alt="User Image">--}}

              <p>
                {{ Auth::user()->user_name }}
              </p>
            </li> -->
            <!-- Menu Footer-->
            <li class="">
              <div class="">
                <a href="{{ url('admin/profile') }}" class="btn  btn-flat">
                  <img src="{{ url('public/admin/dist/img/user.png') }}" class="user_menu_icon">
                  <img src="{{ url('public/admin/dist/img/user_green.png') }}" class="user_menu_hover_icon">
                My Account</a>
              </div>
              <div class="">
                <a href="{{ url('admin/logout') }}" class="btn  btn-flat">
                <img src="{{ url('public/admin/dist/img/ic_logout.png') }}" class="user_menu_icon">
                <img src="{{ url('public/admin/dist/img/ic_logout_green.png') }}" class="user_menu_hover_icon">
              Sign out</a>
              </div>
            </li>
          </ul>
        </li>

        


        <!-- Control Sidebar Toggle Button -->
       <!--  <li>
          <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li> -->
      </ul>
    </div>
  </nav>
  <!-- Header Navbar End -->
</header>