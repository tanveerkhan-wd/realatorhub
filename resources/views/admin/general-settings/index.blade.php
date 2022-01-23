@extends('admin.layout.app_with_login')
@section('title','General Settings')
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
      <h3 class="box-title">General Settings</h3>
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
              $_REQUEST['data'] = (isset($_REQUEST['data']) && !empty($_REQUEST['data']))?$_REQUEST['data']:'logo_banner_data';
                        @endphp

                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item @if($_REQUEST['data'] == 'logo_banner_data') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'logo_banner_data') active @endif" id="logo-banner-data-tab" data-toggle="tab" href="#logo_banner_data" role="tab" aria-controls="logo_banner_data" aria-selected="true" aria-expanded="true">Logo & Banner</a>
                                        </li>
                                        <li class="nav-item @if($_REQUEST['data'] == 'seo-settings') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'seo-settings') active @endif" id="seo-settings-tab" data-toggle="tab" href="#seo-settings" role="tab" aria-controls="seo-settings" aria-selected="false">SEO Settings</a>
                                        </li>
                                        <li class="nav-item @if($_REQUEST['data'] == 'design-settings') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'design-settings') active @endif" id="design-settings-tab" data-toggle="tab" href="#design-settings" role="tab" aria-controls="design-settings" aria-selected="false">Design Settings</a>
                                        </li>
                                        <li class="nav-item @if($_REQUEST['data'] == 'smtp-settings') active @endif">
                                            <a class="nav-link @if($_REQUEST['data'] == 'smtp-settings') active @endif" id="design-settings-tab" data-toggle="tab" href="#smtp-settings" role="tab" aria-controls="smtp-settings" aria-selected="false">Google APIs and SMS</a>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade @if($_REQUEST['data'] == 'logo_banner_data') active in @endif in" id="logo_banner_data" role="tabpanel" aria-labelledby="logo-banner-data-tab">
                                            <!-- Start logo_banner_data Sectin -->
                                            <div class="row mt-10">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                               <form method="POST" action="{{ route('save-logo-and-icon') }}" enctype="multipart/form-data">
                                                {{ @csrf_field() }}
                                                <div class="row mt-10">
                                                    <div class="form-group">
                                                        <label>Admin Logo</label>
                                                    </div>
                                                    <div class="form-group text-center">
                                                        @if(!empty($data) && isset($data['admin_logo']))
                                                        <input type="hidden" name="old_site_admin_logo" value="{{ $data['admin_logo'] }}">
                                                        <img src="{{ url('public/uploads/common_settings').'/'.$data['admin_logo'] }}" id="logo_img_url">
                                                        @endif
                                                        <label class="la la-edit" style="cursor: pointer;">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            <input style="display: none" type="file" name="site_admin_logo" id="logo" data-id="logo" accept="image/*" onchange="readURL(this);">
                                                            <div class="errorImage"></div>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Website Logo</label>
                                                    </div>
                                                    <div class="form-group text-center">
                                                        @if(!empty($data) && isset($data[config('config.general_setting.website_logo')]))
                                                        <input type="hidden" name="old_website_logo" value="{{ $data[config('config.general_setting.website_logo')] }}">
                                                        <img src="{{ url('public/uploads/common_settings').'/'.$data[config('config.general_setting.website_logo')] }}" id="website_logo_img_url">
                                                        @endif
                                                        <label class="la la-edit" style="cursor: pointer;">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            <input style="display: none" type="file" name="website_logo" id="website_logo" data-id="website_logo" accept="image/*" onchange="readURL(this);">
                                                            <div class="errorImage"></div>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Email Logo</label>
                                                    </div>
                                                    <div class="form-group text-center">
                                                        @if(!empty($data) && isset($data['email_logo']))
                                                        <input type="hidden" name="old_email_logo" value="{{ $data['email_logo'] }}">
                                                        <img src="{{ url('public/uploads/common_settings').'/'.$data['email_logo'] }}" id="email_logo_img_url">
                                                        @endif
                                                        <label class="la la-edit" style="cursor: pointer;">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            <input style="display: none" type="file" name="email_logo" id="email_logo" data-id="email_logo" accept="image/*" onchange="readURL(this);">
                                                            <div class="errorImage"></div>
                                                        </label>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Website Favicon</label>
                                                    </div>
                                                    <div class="form-group text-center">
                                                        @if(!empty($data) && isset($data[config('config.general_setting.favicon_icon')]))
                                                        <input type="hidden" name="old_site_favicon_logo" value="{{ $data[config('config.general_setting.favicon_icon')] }}">
                                                        <img src="{{ url('public/uploads/common_settings').'/'.$data[config('config.general_setting.favicon_icon')]}}" id="site_favicon_logo_img_url">
                                                        @endif
                                                        <label class="la la-edit" style="cursor: pointer;">
                                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            <input style="display: none" type="file" name="site_favicon_logo" id="site_favicon_logo" data-id="site_favicon_logo" accept="image/*" onchange="readURL(this);">
                                                            <div class="errorImage"></div>
                                                        </label>
                                                    </div>
                                                    <div class="form-group text-center">
                                                        <input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data" >
                                                    </div>
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
                                                    <form method="POST" action="{{ route('save-seo-settings') }}"
                                                          enctype="multipart/form-data">
                                                        {{ @csrf_field() }}
                                                        <div class="form-group">
                                                            <label>Title</label>
                                                            <input type="text" required="" class="form-control" name="seo_title" id="seo_title" value="{{ (!empty($data[config('config.general_setting.seo_title')]))?($data[config('config.general_setting.seo_title')]):'' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label> Description</label>
                                                            <textarea required="" class="form-control" name="seo_description" id="seo_description">{{ (!empty($data[config('config.general_setting.seo_description')]))?($data[config('config.general_setting.seo_description')]):'' }}</textarea>
                                                        </div>
                                                        <div class="form-group text-center">
                                                            <input type="submit" value="Save" name="save" class="btn btn-primary save_home_banner_data" id="save_home_banner_data" >
                                                        </div>
                                                    </form>
                                                </div>
                                                <div class="col-md-3"></div>
                                            </div>
                                        </div>

                                        <div class="general_tab_design tab-pane fade @if($_REQUEST['data'] == 'design-settings') active in @endif in" id="design-settings" role="tabpanel" aria-labelledby="design-settings-tab">
                                            <div class="row mt-10">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <!-- Start Home Header Logo Banner -->
                                                    <form method="POST" action="{{ route('store.design.setting')  }}" enctype="multipart/form-data">
                                                        {{ @csrf_field() }}
                                                        <div class=" mt-10">
                                                            <div class="general_setting_box">
                                                                <div class="general_hedding">Font Family</div>
                                                                <div class="row">
                                                                    <div class="col-md-1"></div>
                                                                    <div class="col-md-10">
                                                                        <div class="form-group">
                                                                            <label>Font Style</label>
                                                                            <select id="font_type" name="font_type" class="form-control">
                                                                                <option value="">Please Font Style</option>
                                                                                <option value="OpenSans" <?php if(isset($data['font_type']) && $data['font_type']=='OpenSans'){echo "selected";} ?>>OpenSans</option>
                                                                                <option value="ailr" <?php if(isset($data['font_type']) && $data['font_type']=='ailr'){echo "selected";} ?>>Aileron</option>
                                                                                <option value="Didot" <?php if(isset($data['font_type']) && $data['font_type']=='Didot'){echo "selected";} ?>>Didot</option>
                                                                                <option value="Karla" <?php if(isset($data['font_type']) && $data['font_type']=='Karla'){echo "selected";} ?>>Karla</option>
                                                                                <option value="Lato" <?php if(isset($data['font_type']) && $data['font_type']=='Lato'){echo "selected";} ?>>Lato</option>
                                                                                <option value="Montserrat" <?php if(isset($data['font_type']) && $data['font_type']=='Montserrat'){echo "selected";} ?>>Montserrat</option>
                                                                                <option value="Poppins" <?php if(isset($data['font_type']) && $data['font_type']=='Poppins'){echo "selected";} ?>>Poppins</option>
                                                                                <option value="Raleway" <?php if(isset($data['font_type']) && $data['font_type']=='Raleway'){echo "selected";} ?>>Raleway</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Font Color</label>
                                                                            <input type="color" id="font_color" name="font_color" value="{{ (!empty($data['font_color']))?$data['font_color']:'' }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1"></div>
                                                                </div>
                                                            </div>
                                                            <div class="general_setting_box">
                                                                <div class="general_hedding">Button</div>
                                                                <div class="row">
                                                                    <div class="col-md-1"></div>
                                                                    <div class="col-md-10">
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Button Color</label>
                                                                                <input type="color" id="button_color" name="button_color" value="{{(!empty($data['button_color']))?$data['button_color']:'' }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Button Text Color</label>
                                                                                <input type="color" id="button_text_color" name="button_text_color" value="{{ (!empty($data['button_text_color']))?$data['button_text_color']:'' }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group">
                                                                                <label>Button Text Size</label>
                                                                                <input class="form-control" type="text" id="button_text_size" name="btn_size" value="{{ (!empty($data['btn_size']))?$data['btn_size']:'' }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1"></div>
                                                                </div>
                                                            </div>
                                                            <div class="general_setting_box">
                                                                <div class="general_hedding">Header & Footer</div>
                                                                <div class="row">
                                                                    <div class="col-md-1"></div>
                                                                    <div class="col-md-5">
                                                                        <div class="form-group">
                                                                            <label>Header Background Color</label>
                                                                            <input type="color" id="header_background_color" name="header_background_color" value="{{ (!empty($data['header_background_color']))?$data['header_background_color']:'' }}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Header Text Color</label>
                                                                            <input type="color" id="header_text_color" name="header_text_color" value="{{ (!empty($data['header_text_color']))?$data['header_text_color']:'' }}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label>Header Hover Color</label>
                                                                            <input type="color" id="" value="{{ (!empty($data['header_hover_text']))?$data['header_hover_text']:'' }}" name="header_hover_text">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <div class="form-group">
                                                                            <label>Footer Background Color</label>
                                                                            <input type="color" id="footer_background_color" name="footer_background_color" value="{{ (!empty($data['footer_background_color']))?$data['footer_background_color']:'' }}">
                                                                        </div>
                                                                        <div class="form-group">
                                                                              <label>Footer Text Color</label>
                                                                              <input type="color" id="footer_text_color" name="footer_text_color" value="{{ (!empty($data['footer_text_color']))?$data['footer_text_color']:'' }}">
                                                                          </div>
                                                                          <div class="form-group">
                                                                              <label>Footer Hover Color</label>
                                                                              <input type="color" name="footer_hover_text" value="<?php if(isset($data['footer_hover_text'])){ echo $data['footer_hover_text']; }?>">
                                                                          </div>
                                                                    </div>
                                                                    <div class="col-md-1"></div>
                                                                </div>
                                                            </div>
                                                            <div class="general_setting_box">
                                                                <div class="general_hedding">Menu</div>
                                                                <div class="row">
                                                                    <div class="col-md-1"></div>
                                                                    <div class="col-md-10">
                                                                        <div class="row">
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Menu Background Color</label>
                                                                                    <input type="color" id="menu_background_color" name="menu_background_color" value="{{ (!empty($data['menu_background_color']))?$data['menu_background_color']:'' }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Menu Text Color</label>
                                                                                    <input type="color" id="menu_text_color" name="menu_text_color" value="{{ (!empty($data['menu_text_color']))?$data['menu_text_color']:'' }}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="form-group">
                                                                                    <label>Menu Hover Color</label>
                                                                                    <input type="color" id="menu_hover_color" name="menu_hover_color" value="{{ (!empty($data['menu_hover_color']))?$data['menu_hover_color']:'' }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1"></div>
                                                                </div>
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

                                        <div class="tab-pane fade @if($_REQUEST['data'] == 'smtp-settings') active in @endif in" id="smtp-settings" role="tabpanel" aria-labelledby="smtp-settings-tab">
                                            <div class="row mt-10">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-6">
                                                    <!-- Start Contact Sectin -->
                                                    <form id="smtp_settings_form" name="smtp_settings_form" method="POST" action="{{route('store.smtp.settings')}}" enctype="multipart/form-data">
                                                        {{ @csrf_field() }}
                                                        <div class="row mt-10">
                                                            <div class="form-group">
                                                                <label>GOOGLE_MAPS_API_KEY</label>
                                                                <input type="text" class="form-control" placeholder="AIzaSyBi2dVBkdQSUcV8_uwwa*************" name="google_map_api_key" value="{{ (!empty($data[config('config.smtp_settings.google_map_api_key')]))?($data[config('config.smtp_settings.google_map_api_key')]):'' }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>MAIL_DRIVER<span class="asterisk red">*</span></label>
                                                                <input type="text" class="form-control" placeholder="smtp" name="mail_driver" value="{{ (!empty($data[config('config.smtp_settings.mail_driver')]))?($data[config('config.smtp_settings.mail_driver')]):'' }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>MAIL_HOST<span class="asterisk red">*</span></label>
                                                                <input type="text" class="form-control" placeholder="smtp.gmail.com" name="mail_host" value="{{ (!empty($data[config('config.smtp_settings.mail_host')]))?($data[config('config.smtp_settings.mail_host')]):'' }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>MAIL_PORT<span class="asterisk red">*</span></label>
                                                                <input type="text"  class="form-control" placeholder="2525" name="mail_port" value="{{ (!empty($data[config('config.smtp_settings.mail_port')]))?($data[config('config.smtp_settings.mail_port')]):'' }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>MAIL_ENCRYPTION</label>
                                                                <input type="text"  class="form-control" placeholder="ssl" name="mail_encryption" value="{{ (!empty($data[config('config.smtp_settings.mail_encryption')]))?($data[config('config.smtp_settings.mail_encryption')]):'' }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>MAIL_FROM_ADDRESS<span class="asterisk red">*</span></label>
                                                                <input type="text"  class="form-control" placeholder="abc" name="mail_from_address" value="{{ (!empty($data[config('config.smtp_settings.mail_from_address')]))?($data[config('config.smtp_settings.mail_from_address')]):'' }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>MAIL_USERNAME<span class="asterisk red">*</span></label>
                                                                <input type="text"  class="form-control" placeholder="abc" name="mail_username" value="{{ (!empty($data[config('config.smtp_settings.mail_username')]))?($data[config('config.smtp_settings.mail_username')]):'' }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>MAIL_PASSWORD<span class="asterisk red">*</span></label>
                                                                <input type="password" required="" name="mail_password" class="form-control" id="mail_password"  value="{{ (!empty($data[config('config.smtp_settings.mail_password')]))?(\Illuminate\Support\Facades\Crypt::decrypt($data[config('config.smtp_settings.mail_password')])):'' }}"  placeholder="Password">
                                                            </div>
                                                            <div class="form-group text-center">
                                                                <input type="submit" value="Save" name="save" class="btn btn-primary save_smtp_settings" id="save_smtp_settings">
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- End Believe Section -->
                                                </div>
                                                <div class="col-md-3"></div>
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
</script>
@endpush
@push('custom-styles')

<style type="text/css">
    .mt-10{
        margin-top: 20px;
    }
</style>
@endpush