
<?php $agencydata=get_agency_data(); ?>
       
<div class="main_header property_main_header header-hover-text">
    <div class="overlay" onclick="closeOverlay()"></div>
    <nav class="navbar navbar-expand-lg top_menu property_nav header-bg header-text header-hover-text" id="main_nvigation">
        <div class="container-fluid">
            <div class="logo_img">
                <a href="#">
                    @if(isset($agencyDeatils->agency['agency_logo']) && !empty($agencyDeatils->agency['agency_logo']))
                        <img src="{{  url('public/uploads/profile_photos').'/'.$agencyDeatils->agency['agency_logo'] }}">
                        @elseif(!empty($adminDetails['website_logo']))
                        <img src="{{  url('public/uploads/common_settings').'/'.$adminDetails['website_logo'] }}">
                        @else
                        <img src="{{ url('public/admin/dist/img/realtorhubs.png') }}" id="main_wb_logo" alt="logo">
                    @endif
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link  " href="{{url('/'.$agencydata->agency['slug'].'/properties')}}">Search For Properties</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link  " href="{{url('/'.$agencydata->agency['slug'].'/about-us')}}">About</a>
                    </li>
                 @if(\Illuminate\Support\Facades\Auth::user())
                </ul>
            </div>
           
            <ul class="navbar-nav droup_middle last_nav ml-auto">
                <li>
                    <?php 
                            $userDetails=\Illuminate\Support\Facades\Auth::user(); ?>
                    @if($userDetails->user_type=='1')
                    <a class="nav-link nav_icon_box" href="{{url('/agency/chat-list')}}">
                    @elseif($userDetails->user_type=='2')
                    <a class="nav-link nav_icon_box" href="{{url('/agent/chat-list')}}">
                    @else
                    <a class="nav-link nav_icon_box" href="{{url('/'.$slug.'/customer-chat-list')}}">
                    @endif
                    <img src="{{url('public/assets/images/ic_chat_black.png')}}">
                    <?php $allchatCount= getChatUnreadCount(); ?>
                    <span id="header_user_chat_count">
                      @if($allchatCount > 0)
                      <span class="label label-warning noti_counter">{{ $allchatCount }}</span>
                      @endif
                    </span>
                    </a>
                    
                </li>
                <li>
                    <a class="nav-link" href="#"><img src="{{url('public/assets/images/ic_bell_black.png')}}"></a>
                </li>
                
                <li class="dropdown profile_drop_div">
                    <div class="nav-link">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <div class="user_pic_box">
                          <div class="user_pic">
                            <?php 
                            $userDetails=\Illuminate\Support\Facades\Auth::user();
                            if($userDetails->user_type=='1'){
                            $agencyDetails=\App\Models\AgencyModel::where('user_id',$userDetails->id);
                                if(!empty($agencyDetails->agency_logo)){ ?>
                                  <img src="{{url('public/uploads/profile_photos/'.$agencyDetails->agency_logo)}}" class="user-image" alt="User Image" style="width: 50px">
                                <?php }else{ ?>
                                  <img src="{{url('public/assets/images/ic_user_black.png')}}" class="user-image" alt="User Image" style="width: 50px">
                                <?php } 
                            }else{
                               if(!empty($userDetails->profile_img)){ ?>
                              <img src="{{url('public/uploads/profile_photos/'.$userDetails->profile_img)}}" class="user-image" alt="User Image" style="width: 50px">
                            <?php }else{ ?>
                              <img src="{{url('public/assets/images/ic_user_black.png')}}" class="user-image" alt="User Image" style="width: 50px">
                            <?php } 
                            }
                            ?>
                            
                          </div>
                        </div>
                        {{$userDetails->first_name.' '.$userDetails->last_name}}
                        </button>
                        <div class="dropdown-menu sett_drop" aria-labelledby="dropdownMenu2">
                            @if($userDetails->user_type=='1')
                            <a href="{{url('/agency/my-account')}}">
                            @elseif($userDetails->user_type=='2')
                            <a href="{{url('/agent/my-account')}}">
                            @else
                            <a href="{{url('/'.$slug.'/customer-my-account')}}">
                            @endif
                                <button class="dropdown-item">
                                  <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="sett_drop_ic"> 
                                  <img src="{{url('public/assets/images/ic_setting.png')}}" class="sett_drop_hover_ic"> My Profile
                                </button>
                            </a>
                            @if($userDetails->user_type=='3')
                            <a href="{{url('/'.$slug.'/favorite-property-list')}}">
                                <button class="dropdown-item">
                                  <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="sett_drop_ic"> 
                                  <img src="{{url('public/assets/images/ic_setting.png')}}" class="sett_drop_hover_ic"> My Favourites
                                </button>
                            </a>
                            @endif
                            @if($userDetails->user_type=='1')
                            <a href="{{url('/agency/logout')}}">
                            @elseif($userDetails->user_type=='2')
                            <a href="{{url('/agent/logout')}}">
                            @else
                            <a href="{{url('/'.$slug.'/logout')}}">
                            @endif
                                <button class="dropdown-item" type="button">
                                  <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="sett_drop_ic">
                                  <img src="{{url('public/assets/images/ic_setting.png')}}" class="sett_drop_hover_ic"> Sign Out 
                                </button>
                            </a>            
                        </div>
                    </div>
                </li>
            </ul>
            <a href="javascript:void(0);" id="slide" class="navbar-toggle"><i class="fas fa-bars mobile_menu_icon"></i></a>
        </div>
                @else
                    <li class="nav-item">
                        <a class="nav-link login_link" href="{{url('/'.$slug.'/login')}}" >Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link sign_link " href="{{url('/'.$slug.'/signup')}}">Signup</a>
                    </li>
                </ul>
            </div> 
            <a href="javascript:void(0);" id="slide" class="navbar-toggle"><i class="fas fa-bars mobile_menu_icon"></i></a>
        </div>
                @endif                  
                
            
        
    </nav>
    <section>
        <div class="sideoff-off">
            <div class="navbar-header"><a class="navbar-toggle mobile-menu-style" id="slideclose"><i class="fas fa-times"></i></a></div>
            <ul class="ml-auto responsive_menu">
                <li class="nav-item">
                    <a class="nav-link  " href="{{url('/'.$agencydata->agency['slug'].'/properties')}}">Search For Properties</a>
                </li>
                <li class="nav-item">
                <a class="nav-link  " href="{{url('/'.$agencydata->agency['slug'].'/about-us')}}">About</a>
                </li>
                @if(\Illuminate\Support\Facades\Auth::user())
                
                @else
                <li class="nav-item">
                    <a class="nav-link login_link" href="{{url('/'.$slug.'/login')}}" >Login</a>
                    <a class="nav-link sign_link " href="{{url('/'.$slug.'/signup')}}">Signup</a>
                </li>
                @endif 
            </ul>
        </div>
    </section>
    <!-- <div class="top_menu">
        <div class="middel_header header-text">
            <div class="container-fluid">
                <div class="middel_header_inner">
                    <a href="#x" id="slide" class="navbar-toggle">
                        <i class="fas fa-bars mobile_menu_icon bar_icon"></i>
                        
                    </a>
                    <a  class="navbar-toggle mobile-menu-style dash-navbar-toggle" id="slideclose"><i class="fas fa-times"></i></a>
                    <div class="header_logo">
                        @if(isset($agencyDeatils->agency['agency_logo']) && !empty($agencyDeatils->agency['agency_logo']))
                        <img src="{{  url('public/uploads/profile_photos').'/'.$agencyDeatils->agency['agency_logo'] }}">
                        @elseif(!empty($adminDetails['website_logo']))
                        <img src="{{  url('public/uploads/common_settings').'/'.$adminDetails['website_logo'] }}">
                        @else
                        <img src="{{ url('public/admin/dist/img/realtorhubs.png') }}" id="main_wb_logo" alt="logo">
                        @endif
                    </div>
                    <div class="header_searchbar web_header_searchbar">
                        <ul class="res-navbar-nav ml-auto res-dash-nav">
                            <li class="nav-item " >
                                <a class="nav-link" href="#" style="color: #000 !important">Search For Properties</a>
                                <a class="nav-link" href="#" style="color: #000 !important">About</a>
                                <a class="nav-link" href="#" style="color: #000 !important">Login/Signup</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>        
    </div> -->
    <!-- <section>
        
    </section> -->
</div>