@extends('admin.layout.app_with_login')
@section('title','Home Page Setting')
@section('content')
    @php
        $data = array_column($data,'text_value','text_key');
        @endphp
<!-- Content Body -->
<section class="content">
<div class="row new_added_div">
<!-- left column -->
<div class="col-md-12">
  <div class="box-header">
      <h3 class="box-title">Home Page Settings</h3>
  </div>
  <!-- general form elements -->
  <div class="box box-primary box-solid">
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
      @if(\Session::has('error'))
          <div class="alert alert-danger">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <ul>
                  <li>{!! \Session::get('error') !!}</li>
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

          @php
              $_REQUEST['data'] = (isset($_REQUEST['data']) && !empty($_REQUEST['data']))?$_REQUEST['data']:'home_banner_data';
                        @endphp

                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item @if($_REQUEST['data'] == 'home_banner_data') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'home_banner_data') active @endif" id="logo-banner-data-tab" data-toggle="tab" href="#home_banner_data" role="tab" aria-controls="home_banner_data" aria-selected="true" aria-expanded="true">Logo & Banner</a>
                                        </li>
                                        <li class="nav-item @if($_REQUEST['data'] == 'whyRealtorHubs') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'whyRealtorHubs') active @endif" id="whyRealtorHubs-tab" data-toggle="tab" href="#whyRealtorHubs" role="tab" aria-controls="whyRealtorHubs" aria-selected="false">Why Realtor Hubs</a>
                                        </li>
                                        <li class="nav-item @if($_REQUEST['data'] == 'seo-settings') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'seo-settings') active @endif" id="seo-settings-tab" data-toggle="tab" href="#seo-settings" role="tab" aria-controls="seo-settings" aria-selected="false">SEO Settings</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade @if($_REQUEST['data'] == 'home_banner_data') active in @endif in" id="home_banner_data" role="tabpanel" aria-labelledby="logo-banner-data-tab">
                                            <!-- Start logo_banner_data Sectin -->
                                            <div class="row mt-10">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                <form method="POST" action="{{url('/admin/settings/add-logo-banner')}}" enctype="multipart/form-data">
                                                  {{ @csrf_field() }}
                                                  <div class="form-group ">
                                                    <label>Website Banner
                                                    <span class="asterisk red">*</span>
                                                    </label>
                                                    <input id="home_website_banner" onchange="readURL(this);"  class="form-control" data-id="home_website_banner" type="file" name="home_website_banner" accept="image/*"> 
                                                    <div class="errorImage"></div>
                                                    <div id="home_website_banner_validate"></div>
                                                    @if(isset($data['home_website_banner']) && !empty($data['home_website_banner']))
                                                    <img id="home_website_banner_img_url" src="{{ url('/public/uploads/setting/logobanner/'.$data['home_website_banner'])  }}" alt="your image"  width="auto" style="max-width:100%;"/>
                                                    @else
                                                    <img id="home_website_banner_img_url" src="{{ url('/public/uploads/user_profile/user.png')  }}" alt="your image"  width="auto" style="max-width:100%;"/>
                                                    @endif
                                                  </div>
                                                  <div class="form-group ">
                                                      <label>Banner Heading: 
                                                     </label>
                                                    <input  class="form-control" type="text" id="home_banner_heading" name="home_banner_heading" value="@if(isset($data['home_banner_heading']) && !empty($data['home_banner_heading'])){{ $data['home_banner_heading'] }}@endif"> 
                                                    <div id="home_banner_heading_validate"></div>
                                                  </div>
                                                  <div class="form-group ">
                                                      <label>Banner Heading Description: 
                                                     </label>
                                                    <input  class="form-control" type="text" id="home_banner_description" name="home_banner_description" value="@if(isset($data['home_banner_description']) && !empty($data['home_banner_description'])){{ $data['home_banner_description'] }}@endif"> 
                                                    <div id="home_banner_description"></div>
                                                  </div>
                                                  <div class='form-group'>
                                                    <label for="agency_logo">Videos</label>
                                                    <div class='youtube_link_wrap'>
                                                        <div class='row'>
                                                            <div class=" col-md-12">
                                                                <div class='row'>
                                                                    <div class="form-group col-md-4">
                                                                        <select class="form-control video_type_sel dropdown_control" name="home_video_url_type">
                                                                            <option value="1" @if(isset($data['home_video_url_type']) && !empty($data['home_video_url_type']) && $data['home_video_url_type']==1) selected @endif>Youtube Link</option>
                                                                            <option value="2" @if(isset($data['home_video_url_type']) && !empty($data['home_video_url_type']) && $data['home_video_url_type']==2) selected @endif>Upload</option>
                                                                        </select>
                                                                    </div>
                                                                    
                                                                    @if(isset($data['home_video_url_type']) && !empty($data['home_video_url_type']) && $data['home_video_url_type']==2) 
                                                                      <div class="form-group col-md-8 video_input_div">
                                                                      
                                                                      <div class="upload-file-group"><input type="text" name="" class="form-control upload_control upload_file upload_div uplod_vid_name" readonly="" placeholder="Upload here"><button class="file_upload_btn btn-color"><input type="file" name="home_video_url" class="agency_logo file_control video_upload_input upload_div" id="upload_logo" accept="video/*"><img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/ic_upload.png"></button><small class="inst_video">Allowed Format:mp4, mov, ogg, qt, flv,ts,3gp,avi</small><br class="inst_video"><small class="inst_video">Allowed Size:25mb</small></div>
                                                                    </div>
                                                                    @else
                                                                    <div class="form-group col-md-8 video_input_div">
                                                                      <input autocomplete="off" name="home_video_url" type="url" placeholder="Youtube Link" class="form-control youtube_valid_url youtube_div" value="@if(isset($data['home_video_url']) && !empty($data['home_video_url'])){{ $data['home_video_url'] }}@endif" />
                                                                    </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                 @if(isset($data['home_video_url_type']) && !empty($data['home_video_url_type']) && $data['home_video_url_type']==2) 
                                                <div class="form-group uploaded_video">
                                                    <video width="100" height="100" controls>
                                                        <source src="{{ url('public/uploads/setting/home_page_video').'/'.$data['home_video_url']}}" type="video/mp4">
                                                    </video>
                                                  </div>
                                                  @endif
                                                  <div class="form-group ">
                                                    <label>Facebook URL Link: 
                                                    </label>
                                                    <input  class="form-control"  type="text" id="home_facebook_url_link" name="home_facebook_url_link" value="@if(isset($data['home_facebook_url_link']) && !empty($data['home_facebook_url_link'])){{ $data['home_facebook_url_link'] }}@endif"> 
                                                    <div id="home_facebook_url_link_validate"></div>
                                                  </div>
                                                  <div class="form-group ">
                                                    <label>Twitter URL Link: 
                                                    </label>
                                                    <input  class="form-control"  type="text" id="home_twitter_url_link" name="home_twitter_url_link" value="@if(isset($data['home_twitter_url_link']) && !empty($data['home_twitter_url_link'])){{  $data['home_twitter_url_link'] }}@endif"> 
                                                    <div id="home_twitter_url_link_validate"></div>
                                                  </div>
                                                  <div class="form-group ">
                                                    <label>Linkedin URL Link: 
                                                    </label>
                                                    <input  class="form-control" type="text" id="home_linkedin_url_link" name="home_linkedin_url_link" value="@if(isset($data['home_linkedin_url_link']) && !empty($data['home_linkedin_url_link'])){{ $data['home_linkedin_url_link']  }}@endif"> 
                                                    <div id="home_linkedin_url_link_validate"></div>
                                                  </div>
                                                  <div class="form-group ">
                                                    <label>Instagram URL Link: 
                                                    </label>
                                                    <input  class="form-control"  type="text" id="home_instagram_url_link" name="home_instagram_url_link" value="@if(isset($data['home_instagram_url_link']) && !empty($data['home_instagram_url_link'])){{ $data['home_instagram_url_link']  }}@endif"> 
                                                    <div id="home_instagram_url_link_validate"></div>
                                                  </div> 
                                                  <div class="form-group ">
                                                    <label>Youtube URL Link: 
                                                    </label>
                                                    <input  class="form-control"  type="text" id="home_youtube_url_link" name="home_youtube_url_link" value="@if(isset($data['home_youtube_url_link']) && !empty($data['home_youtube_url_link'])){{ $data['home_youtube_url_link']  }}@endif"> 
                                                    <div id="home_youtube_url_link_validate"></div>
                                                  </div>  
                                                  <div class="form-group text-center">
                                                      <input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data" >
                                                  </div> 
                                                        
                                                
                                                  </form>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                            <!-- End logo_banner_data Section -->
                                        </div>

                                        <div class="tab-pane fade @if($_REQUEST['data'] == 'seo-settings') active in @endif in" id="seo-settings" role="tabpanel" aria-labelledby="seo-settings-tab">
                                            <div class="row mt-10">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <form method="POST" action="{{url('/admin/settings/add-seosetting')}}"
                                                          enctype="multipart/form-data">
                                                        {{ @csrf_field() }}
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <input type="text" required="" class="form-control" name="home_seo_title" id="home_seo_title" value="@if(isset($data['home_seo_title']) && !empty($data['home_seo_title'])){{ $data['home_seo_title']  }}@endif">
                                                        </div>
                                                        <div class="form-group">
                                                            <label> Description</label>
                                                            <textarea required="" class="form-control" name="home_seo_description" id="home_seo_description">@if(isset($data['home_seo_description']) && !empty($data['home_seo_description'])){{ $data['home_seo_description']  }}@endif</textarea>
                                                        </div>
                                                        <div class="form-group text-center">
                                                            <input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data" >
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>

                                        <div class="general_tab_design tab-pane fade @if($_REQUEST['data'] == 'whyRealtorHubs') active in @endif in" id="whyRealtorHubs" role="tabpanel" aria-labelledby="whyRealtorHubs-tab">
                                            <div class="row mt-10">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <!-- Start Home Header Logo Banner -->
                                                    <form method="POST" action="{{url('/admin/settings/add-why-realtor-hubs')}}" enctype="multipart/form-data">
                                                        {{ @csrf_field() }}
                                                        <div class=" mt-10">
                                                           
                                                          <div class="form-group ">
                                                            <label>Heading
                                                            </label>
                                                            <input  class="form-control"  type="text" id="hom_why_realtor_hubs_heading" name="hom_why_realtor_hubs_heading" value="@if(isset($data['hom_why_realtor_hubs_heading']) && !empty($data['hom_why_realtor_hubs_heading'])){{ $data['hom_why_realtor_hubs_heading'] }}@endif"> 
                                                            <div id="about_us_why_us_heading_validate"></div>
                                                          </div>
                                                          <div class="form-group ">
                                                              <label>Image 1 </label>
                                                              <input onchange="readURL(this);" class="form-control" type="file" name="hom_why_realtor_hubs_image_one" id="hom_why_realtor_hubs_image_one" data-id="hom_why_realtor_hubs_image_one" accept="image/*"> 
                                                              <div class="errorImage"></div>
                                                              <div id="about_us_why_us_image_one_validate"></div>
                                                              @if(isset($data['hom_why_realtor_hubs_image_one']) && !empty($data['hom_why_realtor_hubs_image_one']))
                                                                
                                                                <img id="hom_why_realtor_hubs_image_one_img_url" src="{{ url('/public/uploads/setting/why_realtor_hubs/'.$data['hom_why_realtor_hubs_image_one'])  }}" alt="your image"  width="100" height="100"/>
                                                              @else
                                                                <img id="hom_why_realtor_hubs_image_one_img_url" src="{{ url('/public/uploads/user_profile/user.png')  }}" alt="your image"  width="100" height="100"/>
                                                              @endif 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Title 1: 
                                                            </label>
                                                            <input  class="form-control" type="text" id="hom_why_realtor_hubs_title_one" name="hom_why_realtor_hubs_title_one" value="@if(isset($data['hom_why_realtor_hubs_title_one']) && !empty($data['hom_why_realtor_hubs_title_one'])){{ $data['hom_why_realtor_hubs_title_one'] }}@endif"> 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Description 1: 
                                                            </label>
                                                            <textarea class="form-control" type="text" id="hom_why_realtor_hubs_description_one" name="hom_why_realtor_hubs_description_one">@if(isset($data['hom_why_realtor_hubs_description_one']) && !empty($data['hom_why_realtor_hubs_description_one'])){{ $data['hom_why_realtor_hubs_description_one'] }}@endif</textarea>
                                                          </div>
                                                          <div class="form-group ">
                                                              <label>Image 2 </label>
                                                              <input onchange="readURL(this);" class="form-control" type="file" name="hom_why_realtor_hubs_image_two" id="hom_why_realtor_hubs_image_two" data-id="hom_why_realtor_hubs_image_two" accept="image/*">
                                                              @if(isset($data['hom_why_realtor_hubs_image_two']) && !empty($data['hom_why_realtor_hubs_image_two']))
                                                                
                                                                <img id="hom_why_realtor_hubs_image_two_img_url" src="{{ url('/public/uploads/setting/why_realtor_hubs/'.$data['hom_why_realtor_hubs_image_two'])  }}" alt="your image"  width="100" height="100"/>
                                                              @else
                                                                <img id="hom_why_realtor_hubs_image_two_img_url" src="{{ url('/public/uploads/user_profile/user.png')  }}" alt="your image"  width="100" height="100"/>
                                                              @endif 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Title 2: 
                                                            </label>
                                                            <input  class="form-control" type="text" id="hom_why_realtor_hubs_title_two" name="hom_why_realtor_hubs_title_two" value="@if(isset($data['hom_why_realtor_hubs_title_two']) && !empty($data['hom_why_realtor_hubs_title_two'])){{ $data['hom_why_realtor_hubs_title_two'] }}@endif"> 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Description 2: 
                                                            </label>
                                                            <textarea class="form-control" type="text" id="hom_why_realtor_hubs_description_two" name="hom_why_realtor_hubs_description_two">@if(isset($data['hom_why_realtor_hubs_description_two']) && !empty($data['hom_why_realtor_hubs_description_two'])){{ $data['hom_why_realtor_hubs_description_two'] }}@endif</textarea>
                                                          </div>
                                                          <div class="form-group ">
                                                              <label>Image 3 </label>
                                                              <input onchange="readURL(this);" class="form-control" type="file" name="hom_why_realtor_hubs_image_three" id="hom_why_realtor_hubs_image_three" data-id="hom_why_realtor_hubs_image_three" accept="image/*">
                                                              @if(isset($data['hom_why_realtor_hubs_image_three']) && !empty($data['hom_why_realtor_hubs_image_three']))
                                                                
                                                                <img id="hom_why_realtor_hubs_image_three_img_url" src="{{ url('/public/uploads/setting/why_realtor_hubs/'.$data['hom_why_realtor_hubs_image_three'])  }}" alt="your image"  width="100" height="100"/>
                                                              @else
                                                                <img id="hom_why_realtor_hubs_image_three_img_url" src="{{ url('/public/uploads/user_profile/user.png')  }}" alt="your image"  width="100" height="100"/>
                                                              @endif 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Title 3: 
                                                            </label>
                                                            <input  class="form-control" type="text" id="hom_why_realtor_hubs_title_three" name="hom_why_realtor_hubs_title_three" value="@if(isset($data['hom_why_realtor_hubs_title_three']) && !empty($data['hom_why_realtor_hubs_title_three'])){{ $data['hom_why_realtor_hubs_title_three'] }}@endif"> 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Description 3: 
                                                            </label>
                                                            <textarea class="form-control" type="text" id="hom_why_realtor_hubs_description_three" name="hom_why_realtor_hubs_description_three">@if(isset($data['hom_why_realtor_hubs_description_three']) && !empty($data['hom_why_realtor_hubs_description_three'])){{ $data['hom_why_realtor_hubs_description_three'] }}@endif</textarea>
                                                          </div>                         
                                                          <div class="form-group ">
                                                              <label>Image 4 </label>
                                                              <input onchange="readURL(this);" class="form-control" type="file" name="hom_why_realtor_hubs_image_four" id="hom_why_realtor_hubs_image_four" data-id="hom_why_realtor_hubs_image_four" accept="image/*">
                                                              @if(isset($data['hom_why_realtor_hubs_image_four']) && !empty($data['hom_why_realtor_hubs_image_four']))
                                                                
                                                                <img id="hom_why_realtor_hubs_image_four_img_url" src="{{ url('/public/uploads/setting/why_realtor_hubs/'.$data['hom_why_realtor_hubs_image_four'])  }}" alt="your image"  width="100" height="100"/>
                                                              @else
                                                                <img id="hom_why_realtor_hubs_image_four_img_url" src="{{ url('/public/uploads/user_profile/user.png')  }}" alt="your image"  width="100" height="100"/>
                                                              @endif 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Title 4: 
                                                            </label>
                                                            <input  class="form-control" type="text" id="hom_why_realtor_hubs_title_four" name="hom_why_realtor_hubs_title_four" value="@if(isset($data['hom_why_realtor_hubs_title_four']) && !empty($data['hom_why_realtor_hubs_title_four'])){{ $data['hom_why_realtor_hubs_title_four'] }}@endif"> 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Description 4: 
                                                            </label>
                                                            <textarea class="form-control" type="text" id="hom_why_realtor_hubs_description_four" name="hom_why_realtor_hubs_description_four">@if(isset($data['hom_why_realtor_hubs_description_four']) && !empty($data['hom_why_realtor_hubs_description_four'])){{ $data['hom_why_realtor_hubs_description_four'] }}@endif</textarea>
                                                          </div>
                                                          <div class="form-group ">
                                                              <label>Image 5 </label>
                                                              <input onchange="readURL(this);" class="form-control" type="file" name="hom_why_realtor_hubs_image_five" id="hom_why_realtor_hubs_image_five" data-id="hom_why_realtor_hubs_image_five" accept="image/*">
                                                              @if(isset($data['hom_why_realtor_hubs_image_five']) && !empty($data['hom_why_realtor_hubs_image_five']))
                                                                
                                                                <img id="hom_why_realtor_hubs_image_five_img_url" src="{{ url('/public/uploads/setting/why_realtor_hubs/'.$data['hom_why_realtor_hubs_image_five'])  }}" alt="your image"  width="100" height="100"/>
                                                              @else
                                                                <img id="hom_why_realtor_hubs_image_five_img_url" src="{{ url('/public/uploads/user_profile/user.png')  }}" alt="your image"  width="100" height="100"/>
                                                              @endif 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Title 5: 
                                                            </label>
                                                            <input  class="form-control" type="text" id="hom_why_realtor_hubs_title_five" name="hom_why_realtor_hubs_title_five" value="@if(isset($data['hom_why_realtor_hubs_title_five']) && !empty($data['hom_why_realtor_hubs_title_five'])){{ $data['hom_why_realtor_hubs_title_five'] }}@endif"> 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Description 5: 
                                                            </label>
                                                            <textarea class="form-control" type="text" id="hom_why_realtor_hubs_description_five" name="hom_why_realtor_hubs_description_five">@if(isset($data['hom_why_realtor_hubs_description_five']) && !empty($data['hom_why_realtor_hubs_description_five'])){{ $data['hom_why_realtor_hubs_description_five'] }}@endif</textarea>
                                                          </div>
                                                          <div class="form-group ">
                                                              <label>Image 6 </label>
                                                              <input onchange="readURL(this);" class="form-control" type="file" name="hom_why_realtor_hubs_image_six" id="hom_why_realtor_hubs_image_six" data-id="hom_why_realtor_hubs_image_six" accept="image/*">
                                                              @if(isset($data['hom_why_realtor_hubs_image_six']) && !empty($data['hom_why_realtor_hubs_image_six']))
                                                                
                                                                <img id="hom_why_realtor_hubs_image_six_img_url" src="{{ url('/public/uploads/setting/why_realtor_hubs/'.$data['hom_why_realtor_hubs_image_six'])  }}" alt="your image"  width="100" height="100"/>
                                                              @else
                                                                <img id="hom_why_realtor_hubs_image_six_img_url" src="{{ url('/public/uploads/user_profile/user.png')  }}" alt="your image"  width="100" height="100"/>
                                                              @endif 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Title 6: 
                                                            </label>
                                                            <input  class="form-control" type="text" id="hom_why_realtor_hubs_title_six" name="hom_why_realtor_hubs_title_six" value="@if(isset($data['hom_why_realtor_hubs_title_six']) && !empty($data['hom_why_realtor_hubs_title_six'])){{ $data['hom_why_realtor_hubs_title_six'] }}@endif"> 
                                                          </div>
                                                          <div class="form-group ">
                                                            <label>Description 6: 
                                                            </label>
                                                            <textarea class="form-control" type="text" id="hom_why_realtor_hubs_description_six" name="hom_why_realtor_hubs_description_six">@if(isset($data['hom_why_realtor_hubs_description_six']) && !empty($data['hom_why_realtor_hubs_description_six'])){{ $data['hom_why_realtor_hubs_description_six'] }}@endif</textarea>
                                                          </div>
                                                            <div class="form-group text-center">
                                                                <input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data" >
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-1"></div>
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
    <!-- End Content Body -->

@endsection
@push('custom-scripts')
<!-- Include this Page JS -->
<script type="text/javascript" src="{{ url('public/js/admin/general-settings/index.js') }}"></script>
<script type="text/javascript">
  function readURL(input) {
    debugger;
      if (input.files && input.files[0]) {
        var id=$(input).data("id");
      console.log(id);
          var reader = new FileReader();

          reader.onload = function (e) {
              $('#'+id+'_img_url').attr('src', e.target.result);

          }

          reader.readAsDataURL(input.files[0]);
      }
  }
 $('.youtube_link_wrap').on('change', '.video_type_sel', function() {
    var video_type = $(this).val();
    if (video_type == 2) {
        var newHtml = "<div class='upload-file-group'><input type='text' name='' class='form-control upload_control upload_file upload_div uplod_vid_name' readonly='' placeholder='Upload here'><button class='file_upload_btn btn-color'><input type='file' name='home_video_url' class='agency_logo file_control video_upload_input upload_div' id='upload_logo' accept='video/*'><img src='{{ url('public/assets/')}}/images/ic_upload.png'></button></div><small class='inst_video'>Allowed Format:mp4, mov, ogg, qt, flv,ts,3gp,avi</small><br class='inst_video'><small class='inst_video'>Allowed Size:25mb</small>";
        $(this).parent().next('div.video_input_div').find('.youtube_div').remove();
        $(this).parent().next('div.video_input_div').append(newHtml);
        $('.uploaded_video').css('display','block');
        $('.youtube_error').remove();
    }else{
         var newHtml = '<input autocomplete="off" name="home_video_url" type="url" placeholder="Youtube Link" class="form-control youtube_valid_url youtube_div"/>';
        $(this).parent().next('div.video_input_div').find('.upload_div').remove();
        $(this).parent().next('div.video_input_div').find('.file_upload_btn').remove();
        $(this).parent().next('div.video_input_div').find('.inst_video').remove();
        $(this).parent().next('div.video_input_div').append(newHtml);
        $('.uploaded_video').css('display','none');
    }
});
 $(".youtube_link_wrap").on('change', '.video_upload_input', function() {
        var file_size = this.files[0].size;
        var fileExtension = ['mp4', 'mov', 'ogg', 'qt', 'flv', 'ts', '3gp', 'avi'];
        $(this).parent().prev('.uplod_vid_name').val(this.files[0].name);
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            $(this).after("<div class='video_error'>Allow Format:mp4,mov,ogg,qt,flv,ts,3gp,avi</div>");
            return false;
        }

        if (file_size > 26214400) {
            $(this).after("<div class='video_error'>File size is greater than 25MB</div>");
            return false;
        }
        $(this).next('.video_error').remove();
        return true;
    });
    $(function() {
        $('#property_form').submit(function() {
            $('.loader-outer-container').css('display', 'table');
            return true;
        });
    });

    $('.youtube_link_wrap').on('change', '.youtube_valid_url', function() {
        var url = $(this).val();
        if (url != undefined || url != '') {
            var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
            var match = url.match(regExp);
            if (match && match[2].length == 11) {

            }
            else {
                $(this).next('.youtube_error').remove();
                $(this).after("<div class='youtube_error'>Please Add Youtube Link</div>");
                return false;
            }
            $(this).next('.youtube_error').remove();
        }
    });
</script>
@endpush
@push('custom-styles')

<style type="text/css">
    .mt-10{
        margin-top: 20px;
    }
</style>
@endpush
