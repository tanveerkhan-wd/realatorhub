<?php $general_settings = getGeneralSettings(); 
?>
<footer>
    <div class="top_footer text-center footer-bg footer-text footer-hover-text">
        <div class="footer_link footer_social_link">
            <ul>
                @if(isset($general_settings['home_facebook_url_link'])&&!empty($general_settings['home_facebook_url_link']))
                <li>
                    <a href="{{$general_settings['home_facebook_url_link']}}" class="fb_icon"><i class="fab fa-facebook-f"></i></a>
                </li>
                @endif
                
                @if(isset($general_settings['home_twitter_url_link'])&&!empty($general_settings['home_twitter_url_link']))
                <li>
                    <a href="{{$general_settings['home_twitter_url_link']}}" class="twitter_icon"><i class="fab fa-twitter"></i></a>
                </li>
                @endif
                
                @if(isset($general_settings['home_instagram_url_link'])&&!empty($general_settings['home_instagram_url_link']))
                <li>
                    <a href="{{$general_settings['home_instagram_url_link']}}" class="insta_icon"><i class="fab fa-instagram"></i></a>
                </li>
                @endif
                
                @if(isset($general_settings['home_youtube_url_link'])&&!empty($general_settings['home_youtube_url_link']))
                <li>
                    <a href="{{$general_settings['home_youtube_url_link']}}" class="youtume_icon"><i class="fab fa-youtube"></i></a>
                </li>
                @endif
                
                @if(isset($general_settings['home_linkedin_url_link'])&&!empty($general_settings['home_linkedin_url_link']))
                <li>
                    <a href="{{$general_settings['home_linkedin_url_link']}}" class="linkedin_icon"><i class="fab fa-linkedin-in"></i></a>
                </li>
                @endif
            </ul>
        </div>
        <div class="footer_link">
            <ul>
                <li>
                    <a href="{{route('user.terms.condition')}}" class="@if(Request::url() == url('/terms-condition'))active @endif">Terms &amp; Conditions</a>
                </li>
                <li>
                    <a href="{{route('user.privacy.policy')}}" class="@if(Request::url() == url('/privacy-policy'))active @endif">Privacy Policy</a>
                </li>
                <li>
                    <a href="{{route('user.faqs')}}" class="@if(Request::url() == url('/faqs'))active @endif">FAQs</a>
                </li>
                <li>
                    <a href="{{route('user.contact.us')}}" class="@if(Request::url() == url('/contact-us'))active @endif">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="bottom_footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 text-md-left text-center">
                    <p>© <?= date('Y') ?> Digiteau. All Rights Reserved </p>
                </div>
                <div class="col-md-4 text-md-right text-center">
                    <p>Division of <a href="https://digiteau.com/">Digiteau</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>