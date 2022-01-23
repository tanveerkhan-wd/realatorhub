<?php $site_title=$seo_title->text_value;?>
@extends('frontend.home.layout.app')
@section('title',$site_title)
@section('content')

<section class="content-remove">
    <section class="hero-banner bg" id="home" style="background-image: url({{ url('public/uploads/setting/logobanner/'.$banner_image) }})"> 
        <div class="container">
            <div class="row  align-items-center">
                <div class="col-md-6  ">

                </div>
                <div class="col-md-6">
                    <div class="banner_content wow zoomIn slower">
                        <div>
                            <h1>{{$banner_heading}}</h1>
                            <p>{{$banner_desc}}</p>
                            <a href="{{route('agency.signup')}}" class="theme-btn btn-color btn-text btn-size auth_btn wow zoomIn slow" id="start_trial">START FREE TRIAL</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <section class="section feature-section" id="features">
        <div class="container">
            <div class="row equal_height">
                <div class="col-lg-3 col-md-6 col-12 equal_height_container">
                    <div class="feature_box wow zoomIn slow">
                        <div class="feature_top">
                            <div class="featute_img ">
                                <img src="{{ url('public/assets/images/ic_acency_regi.png') }}">
                            </div>
                            <div class="featute_value">
                                <h4>{{$agency_count}}+</h4>
                            </div>
                        </div>
                        <p class="feature_name">Registered Agencies</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 equal_height_container">
                    <div class="feature_box wow zoomIn slow">
                        <div class="feature_top">
                            <div class="featute_img">
                                <img src="{{ url('public/assets/images/ic_agent_regi.png') }}">
                            </div>
                            <div class="featute_value">
                                <h4>{{$agent_count}}+</h4>
                            </div>
                        </div>
                        <p class="feature_name">Registered Agents</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 equal_height_container">
                    <div class="feature_box wow zoomIn slow">
                        <div class="feature_top">
                            <div class="featute_img">
                                <img src="{{ url('public/assets/images/ic_live_properties.png') }}">
                            </div>
                            <div class="featute_value">
                                <h4>{{$property_count}}+</h4>
                            </div>
                        </div>
                        <p class="feature_name">Live Properties</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-12 equal_height_container">
                    <div class="feature_box wow zoomIn slow">
                        <div class="feature_top">
                            <div class="featute_img">
                                <img src="{{ url('public/assets/images/ic_leads_gene.png') }}">
                            </div>
                            <div class="featute_value">
                                <h4>{{$lead_count}}+</h4>
                            </div>
                        </div>
                        <p class="feature_name">Leads Generated</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section feature-section text-center grey_bg" id="features">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <h2 class="section_title wow zoomIn slow">{{$why_real['hom_why_realtor_hubs_heading']}}</h2>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="item wow fadeInRight">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <div class="img-div ">
                                            <div class="blue-icon">
                                                <img src="{{ url('public/uploads/setting/why_realtor_hubs/'.$why_real['hom_why_realtor_hubs_image_one']) }}">
                                            </div>		            		
                                        </div>
                                        <div class="text-div ">
                                            <h4>{{$why_real['hom_why_realtor_hubs_title_one']}}</h4>
                                            <span>{{$why_real['hom_why_realtor_hubs_description_one']}} </span>			            		
                                        </div>	
                                    </div>
                                </div>           	
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="item wow fadeInRight">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <div class="img-div ">
                                            <div class="blue-icon">
                                                <img src="{{ url('public/uploads/setting/why_realtor_hubs/'.$why_real['hom_why_realtor_hubs_image_two']) }}">
                                            </div>		            		
                                        </div>
                                        <div class="text-div ">
                                            <h4>{{$why_real['hom_why_realtor_hubs_title_two']}}</h4>
                                            <span>{{$why_real['hom_why_realtor_hubs_description_two']}}</span>			            		
                                        </div>	
                                    </div>
                                </div>           	
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="item wow fadeInRight">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <div class="img-div ">
                                            <div class="blue-icon">
                                                <img src="{{ url('public/uploads/setting/why_realtor_hubs/'.$why_real['hom_why_realtor_hubs_image_three']) }}">
                                            </div>		            		
                                        </div>
                                        <div class="text-div ">
                                            <h4>{{$why_real['hom_why_realtor_hubs_title_three']}}</h4>
                                            <span>{{$why_real['hom_why_realtor_hubs_description_three']}} </span>			            		
                                        </div>	
                                    </div>
                                </div>           	
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="item wow fadeInRight">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <div class="img-div ">
                                            <div class="blue-icon">
                                                <img src="{{ url('public/uploads/setting/why_realtor_hubs/'.$why_real['hom_why_realtor_hubs_image_four']) }}">
                                            </div>		            		
                                        </div>
                                        <div class="text-div ">
                                            <h4>{{$why_real['hom_why_realtor_hubs_title_four']}}</h4>
                                            <span>{{$why_real['hom_why_realtor_hubs_description_four']}}</span>			            		
                                        </div>	
                                    </div>
                                </div>           	
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="item wow fadeInRight">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <div class="img-div ">
                                            <div class="blue-icon">
                                                <img src="{{ url('public/uploads/setting/why_realtor_hubs/'.$why_real['hom_why_realtor_hubs_image_five']) }}">
                                            </div>		            		
                                        </div>
                                        <div class="text-div ">
                                            <h4>{{$why_real['hom_why_realtor_hubs_title_five']}}</h4>
                                            <span>{{$why_real['hom_why_realtor_hubs_description_five']}}</span>			            		
                                        </div>	
                                    </div>
                                </div>           	
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="item wow fadeInRight">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <div class="img-div ">
                                            <div class="blue-icon">
                                                <img src="{{ url('public/uploads/setting/why_realtor_hubs/'.$why_real['hom_why_realtor_hubs_image_six']) }}">
                                            </div>		            		
                                        </div>
                                        <div class="text-div ">
                                            <h4>{{$why_real['hom_why_realtor_hubs_title_six']}}</h4>
                                            <span>{{$why_real['hom_why_realtor_hubs_description_six']}} </span>			            		
                                        </div>	
                                    </div>
                                </div>           	
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </section>
    <section class="section pricing_plan" id="pricing">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <h2 class="section_title wow zoomIn slow">Pricing Plans</h2>
                    </div>

                        @php
                        $plan_count=count($subscription_plan->toArray());
                        @endphp
                        @if($plan_count<=3)
                    <div class="row justify-content-center">
                        @foreach($subscription_plan->toArray()  as $plan)
                        <div class="col-md-4">
                            <div class="subscription_box text-center subscription_box_space subscription_box_home">
                                <div class="subscription_box_header">
                                    <h2 class="subscription_title">{{$plan['plan_name']}}</h2>
                                    <h3>$ {{$plan['monthly_price']}} <span>/ Month</span></h3>
<!--                                    <button type="button" class="theme-btn btn-color btn-text btn-size blue_border_btn  select_plan" id="select_plan"> Select this Plan
                                    </button>-->
                                </div>

                                <div class="subscription_box_body">
                                    <p>{{$plan['description']}} </p>

                                    <p>Agents Limit</p>
                                    <h4>{{$plan['no_of_agent']}}</h4>
                                    <p>Additional Agents Allowed</p>
                                    <h4>Up to {{$plan['additional_agent']}}</h4>
                                    <p>Price Per Additional Agent</p>
                                    <h4>$ {{$plan['additional_agent_per_rate']}}</h4>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div> 
                    @endif
                    @if(!empty($subscription_plan->toArray() && $plan_count>3))
                    <div class="owl-carousel owl-theme homeslider feature_slider">
                        @foreach($subscription_plan->toArray()  as $plan)
                        <div class="item wow fadeInRight">

                            <div class="subscription_box text-center subscription_box_space subscription_box_home">
                                <div class="subscription_box_header">
                                    <h2 class="subscription_title">{{$plan['plan_name']}}</h2>
                                    <h3>$ {{$plan['monthly_price']}} <span>/ Month</span></h3>
<!--                                    <button type="button" class="theme-btn btn-color btn-text btn-size blue_border_btn  select_plan" id="select_plan"> Select this Plan
                                    </button>-->
                                </div>

                                <div class="subscription_box_body">
                                    <p>{{$plan['description']}} </p>

                                    <p>Agents Limit</p>
                                    <h4>{{$plan['no_of_agent']}}</h4>
                                    <p>Additional Agents Allowed</p>
                                    <h4>Up to {{$plan['additional_agent']}}</h4>
                                    <p>Price Per Additional Agent</p>
                                    <h4>$ {{$plan['additional_agent_per_rate']}}</h4>
                                </div>
                            </div>             

                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            <div class="text-center">
                <P CLASS="subscriptionPlans_bottom_text">"Try any plan free for 30 Days, If you don't want to continue the you can cancel at any time.</P>
                <!--  <P CLASS="subscriptionPlans_bottom_text">Start your free trial with any plan for 30 days.  You can cancel at anytime, no commitments.</P> -->
                <a href="{{route('agency.signup')}}" class="theme-btn btn-color btn-text btn-size auth_btn wow zoomIn slow" id="start_trial">Start Free Trial
                </a>
            </div>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class="about_vdo">
                        @if($video_url->type=='text')
                            <?php
                            $url =$video_url->text_value;
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

                            $youtube_id = $match[1];
                            ?>
                            <iframe class="youtube-banner" id="youtube" width="560" height="315" src="https://www.youtube.com/embed/{{$youtube_id}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @else
                        <video id="vid" muted="" style="min-width: 100%;min-height: 100%;width: auto;height: auto;" controls autoplay="">
                                <source src="{{url('public/uploads/setting/home_page_video/'.$video_url->text_value.'#t=1')}}" type="video/mp4">
                            </video>
                        <!--<img src="{{ url('public/assets/images/ic_play_btn.png') }}" class="ply_btn" onclick="playVid()">-->
                        @endif
<!--                        <video controls="" width="250">
                            <source src="/images/examples/flower.webm" type="video/webm">
                            <source src="/images/flower.mp4" type="video/mp4">
                            Sorry, your browser doesn't support embedded videos.
                        </video>-->
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>
    </section>
    <section class="blog-listing grey_bg" id="blog">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="section_title wow zoomIn slow">Blogs</h2>
                </div>
                <div class="col-12 text-right">
                    <a href="{{route('user.blog.home.list')}}" class="blue_btn wow zoomIn slow">View All <img src="{{ url('public/assets/images/ic_right_blue.png') }}" alt=""></a>
                </div>
            </div>
            <div class="row equal_height">
                <?php
            $allowedlimit = 75;
            ?>
                 @foreach($blog_data as $key => $value)
                <div class="col-lg-4 col-md-6 equal_height_container blog-card wow zoomIn slow">
                    <div class="card wow zoomIn slow">
                        <a data-slug="blog-detail/{{ $value['slug'] }}" href="{{ url('/blog-detail/'.$value['slug'])}}">
                            <div class="">
                                <div class="">
                                    <div class="card-top" style="background: url({{ url('public/uploads/blog_image/'.$value['image']) }}) center center no-repeat ;">					
                                        <span></span>
                                    </div>
                                    <div class="card-body">
                                        <h4>{{$value['title']}}</h4>
                                        @php
                                        $desc=(mb_strlen($value['description'] )>$allowedlimit) ? mb_substr(html_entity_decode(strip_tags($value['description'])),0,$allowedlimit).'....' : $value['description'] ;
                                        @endphp
                                        <p class="card-text">{!! $desc !!}  </p>
                                        <p class="card-date">{{date('d M y', strtotime($value['created_date'])) }}</p>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                 @endforeach
<!--                <div class="col-lg-4 col-md-6 equal_height_container blog-card wow zoomIn slow">
                    <div class="card wow zoomIn slow">
                        <a href="#x">
                            <div class="">
                                <div class="">
                                    <div class="card-top" style="background: url({{ url('public/assets/images/blog_img.png') }}) center center no-repeat ;">					
                                        <span></span>
                                    </div>
                                    <div class="card-body">
                                        <h4>Lorem ipsum dolor</h4>
                                        <p class="card-text">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
                                        <p class="card-date">24 Feb 20</p>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>-->
<!--                <div class="col-lg-4 col-md-6 equal_height_container blog-card wow zoomIn slow">
                    <div class="card wow zoomIn slow">
                        <a href="#x">
                            <div class="">
                                <div class="">
                                    <div class="card-top" style="background: url({{ url('public/assets/images/blog_img.png') }}) center center no-repeat ;">					
                                        <span></span>
                                    </div>
                                    <div class="card-body">
                                        <h4>Lorem ipsum dolor</h4>
                                        <p class="card-text">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
                                        <p class="card-date">24 Feb 20</p>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div>
                </div>-->

            </div>
        </div>
    </section>
</section>
@endsection
@push('custom-styles')
<meta property="og:title" content="{{ $seo_title->text_value}}">
<meta name="description" content="{{ $seo_desc->text_value }}">
<meta property="og:description" content="{{ $seo_desc->text_value }}">
<meta property="og:image" content="{{ url('public/uploads/setting/logobanner/'.$banner_image) }}">
@endpush
@push('custom-scripts')
<script>
    $('video').each(function(){
    if ($(this).is(":in-viewport")) {
        $(this)[0].play();
    } else {
        $(this)[0].pause();
    }
})
 </script>   
@endpush