<?php $general_settings = getGeneralSettings(); 
//echo "<pre>"; print_r($general_settings); exit;
?>
<footer>
    <div class="agency_top_footer top_footer text-center footer-bg footer-text footer-hover-text">
        <div class="agency_logo_footer text-center">
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
        <div class="footer_link footer_social_link mb-0">
            <ul>
                @if(isset($general_settings['facebook'])&&!empty($general_settings['facebook']))
                <li>
                    <a href="{{$general_settings['facebook']}}" class="fb_icon"><i class="fab fa-facebook-f"></i></a>
                </li>
                @endif
                @if(isset($general_settings['twitter'])&&!empty($general_settings['twitter']))
                <li>
                    <a href="{{$general_settings['twitter']}}" class="twitter_icon"><i class="fab fa-twitter"></i></a>
                </li>
                @endif
                @if(isset($general_settings['instagram'])&&!empty($general_settings['instagram']))
                <li>
                    <a href="{{$general_settings['instagram']}}" class="insta_icon"><i class="fab fa-instagram"></i></a>
                </li>
                @endif
                @if(isset($general_settings['linkedin'])&&!empty($general_settings['linkedin']))
                <li>
                    <a href="{{$general_settings['linkedin']}}" class="linkedin_icon"><i class="fab fa-linkedin-in"></i></a>
                </li>
                @endif
            </ul>
        </div>
        
        <!-- <div class="footer_link">
            <ul>
                <li>
                    <a href="#">Search</a>
                </li>
                <li>
                    <a href="#">Catalog</a>
                </li>
                <li>
                    <a href="#">Privacy Policy</a>
                </li>
                <li>
                    <a href="#">Terms & Conditions</a>
                </li>
            </ul>
        </div> -->
    </div>
    <div class="bottom_footer ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>© 2020 Digiteau. All Rights Reserved </p>
                </div>
            </div>
        </div>
    </div>
</footer>