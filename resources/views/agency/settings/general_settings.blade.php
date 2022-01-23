@extends('agency.layout.app_with_login')
@section('title','General Setting')
@section('content')
<!-- 
View File for About us Setting
@package    Laravel
@subpackage View
@since      1.0
 -->

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
	@php
		$data = array_column($data,'text_value','text_key');                 				
		$_REQUEST['data'] = (isset($_REQUEST['data']) && !empty($_REQUEST['data']))?$_REQUEST['data']:'aboutAgency';
@endphp
<?php //echo "<pre>"; print_r($data); exit; ?>



	          		
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">	
			<ul class="nav nav-tabs text-center theme_tab theme_tab4" id="myTab" role="tablist">
			  <li class="nav-item @if($_REQUEST['data'] == 'aboutAgency') active @endif">
			    <a class="nav-link @if($_REQUEST['data'] == 'aboutAgency') active @endif" id="aboutAgency-tab" data-toggle="tab" href="#aboutAgency" role="tab" aria-controls="aboutAgency" aria-selected="false">About Agency</a>
			  </li>
			  <li class="nav-item @if($_REQUEST['data'] == 'designSettings') active @endif">
			    <a class="nav-link @if($_REQUEST['data'] == 'designSettings') active @endif" id="designSettings-tab" data-toggle="tab" href="#designSettings" role="tab" aria-controls="designSettings" aria-selected="false">Design Settings</a>
			  </li>
			  <li class="nav-item @if($_REQUEST['data'] == 'contactForm') active @endif">
			    <a class="nav-link @if($_REQUEST['data'] == 'contactForm') active @endif" id="contactForm-tab" data-toggle="tab" href="#contactForm" role="tab" aria-controls="contactForm" aria-selected="false">Contact & Chat</a>
			  </li>
			  <li class="nav-item @if($_REQUEST['data'] == 'seoSetting') active @endif">
			    <a class="nav-link @if($_REQUEST['data'] == 'seoSetting') active @endif" id="seoSetting-tab" data-toggle="tab" href="#seoSetting" role="tab" aria-controls="seoSetting" aria-selected="false">SEO Settings</a>
			  </li>
			</ul>

			<div class="tab-content theme_tab_content" id="myTabContent">								  
		  		<div class="tab-pane fade @if($_REQUEST['data'] == 'aboutAgency') active in show @endif" id="aboutAgency" role="tabpanel" aria-labelledby="aboutAgency-tab">
		  			<form method="POST" action="{{ url('agency/saveAboutAgency') }}" enctype="multipart/form-data">
					{{ @csrf_field() }}
						<div class="row mt-10">
		                    <div class="col-md-3"></div>
	                      	<div class="col-md-6">
                    	
								<div class="form-group">
									<label>Banner Image</label>
									<div class="upload-file-group">
                                        <div class="choose_imd_box text-center">
                                        	<?php if(isset($data['agency_banner_image'])){?>
												<img id="agency_banner_image" src="{{ url('public/uploads/agency_about_banner').'/'.$data['agency_banner_image'] }}" >
											<?php }else{?> 
												<div class="banner_default_box">
													<img id="agency_banner_image" src="{{ url('public/assets/images/ic_upload_blue.png')}}">
													<h4 class="upload_here">Upload here</h4>
												</div>
											<?php } ?>
                                        </div>
                                        <p class="file_upload_btn btn-color btn-text">
                                        	<input type="file" name="agency_banner_image" class="agency_logo file_control" id="agency_banner_image" accept="image/*" onchange="readURL(this);">Choose
                                        </p>
                                    </div>
                                    <div class="errorImage"></div>
								</div>

								<div class="form-group">
									<label>About Us Text</label>
									
									<textarea required class="form-control" name="agency_text" id="agency_text" rows="10" cols="80"><?php if(isset($data['agency_text'])){ echo $data['agency_text']; }?></textarea>
								</div>

								<div class="form-group">
									<label>About Us SEO Title</label>
									<input type="text" name="about_us_seo_title" class="form-control" placeholder="Enter Title" value="<?php if(isset($data['about_us_seo_title'])){ echo $data['about_us_seo_title']; }?>">
									<small>SEO Title tags are displayed on search engine results pages (SERPs) as the clickable headline for a given result, and are important for usability, SEO, and social sharing. </small>
								</div>
								<div class="form-group">
									<label>About Us SEO Description</label>
									<textarea class="form-control" name="about_us_seo_desc" placeholder="Type Here" rows="3"><?php if(isset($data['about_us_seo_desc'])){ echo $data['about_us_seo_desc']; }?></textarea>
									<small>The SEO description tag in HTML is the 160 character snippet used to summarise a web page's content. Search engines sometimes use these snippets in search results to let visitors know what a page is about before they click on it. </small>
								</div>
								<label class="btn-color-title">Social Links</label>
								<div class="form-group">
									<label>Facebook</label>
									<input type="text" name="facebook" class="form-control" value="<?php if(isset($data['facebook'])){ echo $data['facebook']; }?>" placeholder="Enter Facebook link">
								</div>
								<div class="form-group">
									<label>Twitter</label>
									<input type="text" name="twitter" class="form-control" value="<?php if(isset($data['twitter'])){ echo $data['twitter']; }?>" placeholder="Enter Twitter link">
								</div>
								<div class="form-group">
									<label>Instagram</label>
									<input type="text" name="instagram" class="form-control" value="<?php if(isset($data['instagram'])){ echo $data['instagram']; }?>" placeholder="Enter Instagram link">
								</div>
								<div class="form-group">
									<label>LinkedIn</label>
									<input type="text" name="linkedin" class="form-control" value="<?php if(isset($data['linkedin'])){ echo $data['linkedin']; }?>" placeholder="Enter LinkedIn link">
								</div>
							    <div class="form-group text-center">
									<input type="submit" value="Save" name="save" class="theme-btn btn-color btn-text btn-size auth_btn save_home_banner_data" id="save_home_banner_data" >
								</div>
							</div>
							<div class="col-md-3"></div>
						</div>
	                </form>
	            </div>
		  		<div class="tab-pane fade @if($_REQUEST['data'] == 'designSettings') active show  @endif in" id="designSettings" role="tabpanel" aria-labelledby="designSettings-tab">
		  			<form method="POST" action="{{ url('agency/saveDesignSetting') }}" enctype="multipart/form-data">
					{{ @csrf_field() }}	

			  			<div class="row">
		                    <div class="col-md-1"></div>
		                    <div class="col-md-10">
	                    		<div class="grey_box">
	                    			<div class="grey_box_header">
	                    				<h4>Font Family</h4>
	                    			</div>
	                    			<div class="grey_box_body">
	                    				<div class="row">
	                    					<div class="col-md-1"></div>
	                    					<div class="col-md-6">
	                    						<label>Font Family</label>
	                    						<select id="font_type" name="font_type" class="form-control dropdown_control">
													<?php if(!isset($data['font_type'])){ $data['font_type']=''; }?>
													<option value="">Please Font Style</option>
													<option value="OpenSans" <?php if($data['font_type']=='OpenSans'){echo "selected";} ?>>OpenSans</option>
													<option value="ailr" <?php if($data['font_type']=='ailr'){echo "selected";} ?>>Aileron</option>
													<option value="Didot" <?php if($data['font_type']=='Didot'){echo "selected";} ?>>Didot</option>
													<option value="Karla" <?php if($data['font_type']=='Karla'){echo "selected";} ?>>Karla</option>
													<option value="Lato" <?php if($data['font_type']=='Lato'){echo "selected";} ?>>Lato</option>
													<option value="Montserrat" <?php if($data['font_type']=='Montserrat'){echo "selected";} ?>>Montserrat</option>
													<option value="Poppins" <?php if($data['font_type']=='Poppins'){echo "selected";} ?>>Poppins</option>
													<option value="Raleway" <?php if($data['font_type']=='Raleway'){echo "selected";} ?>>Raleway</option>
													
												</select>
	                    					</div>
	                    					<div class="col-md-4">
	                    						<label class="w-100">Font Color</label>
												<input type="color" id="font_color" name="font_color"  value="<?php if(isset($data['font_color'])){ echo $data['font_color']; }?>">
	                    					</div>
	                    					<div class="col-md-1"></div>
	                    				</div>
	                    			</div>
	                    		</div>
	                    		
	                    		<div class="grey_box">
	                    			<div class="grey_box_header">
	                    				<h4>Button</h4>
	                    			</div>
	                    			<div class="grey_box_body">
	                    				<div class="row">
	                    					<div class="col-md-1"></div>
	                    					<div class="col-md-10">
	                    						<div class="row">
	                    							<div class="col-md-4">
	                    								<div class="form-group button_group">
				                    						<label class="w-100">Button Color</label>
															<input type="color" id="button_color" name="button_color" value="<?php if(isset($data['button_color'])){ echo $data['button_color']; }?>">
														</div>
	                    							</div>
	                    							<div class="col-md-4">
	                    								<div class="form-group button_group">
				                    						<label class="w-100">Button Text Color</label>
															<input type="color" id="button_text_color" name="button_text_color" value="<?php if(isset($data['button_text_color'])){ echo $data['button_text_color']; }?>">
														</div>
	                    							</div>
	                    							<div class="col-md-4">
	                    								<div class="form-group button_group">
				                    						<label class="w-100">Button Size</label>
															<input type="text" id="" name="btn_size" value="<?php if(isset($data['btn_size'])){ echo $data['btn_size']; }?>" class="form-control">
														</div>
	                    							</div>
	                    						</div>
	                    						
	                    					</div>
	                    					<div class="col-md-5">
	                    						
	                    					</div>
	                    					<div class="col-md-1"></div>
	                    				</div>
	                    			</div>
	                    		</div>
	                    		<div class="grey_box">
	                    			<div class="grey_box_header">
	                    				<h4>Header & Footer</h4>
	                    			</div>
	                    			<div class="grey_box_body">
	                    				<div class="row">
	                    					<div class="col-md-1"></div>
	                    					<div class="col-md-5 col-sm-6">
	                    						<div class="form-group header_footer_group">
													<label class="w-100">Header Background Color</label>
													<input type="color" id="header_background_color" name="header_background_color" value="<?php if(isset($data['header_background_color'])){ echo $data['header_background_color']; }?>">
												</div>
												<div class="form-group header_footer_group">
													<label class="w-100">Header Text Color</label>
													<input type="color" id="header_text_color" name="header_text_color" value="<?php if(isset($data['header_text_color'])){ echo $data['header_text_color']; }?>">
												</div>
												<div class="form-group header_footer_group">
													<label class="w-100">Header Hover Color</label>
													<input type="color" id="" value="<?php if(isset($data['header_hover_text'])){ echo $data['header_hover_text']; }?>" name="header_hover_text">
												</div>
	                    					</div>
	                    					<div class="col-md-5 col-sm-6">
	                    						<div class="form-group header_footer_group">
													<label class="w-100">Footer Background Color</label>
													<input type="color" id="footer_background_color" name="footer_background_color" value="<?php if(isset($data['footer_background_color'])){ echo $data['footer_background_color']; }?>">
												</div>
												<div class="form-group header_footer_group">
													<label class="w-100">Footer Text Color</label>
													<input type="color" id="footer_text_color" name="footer_text_color" value="<?php if(isset($data['footer_text_color'])){ echo $data['footer_text_color']; }?>">
												</div>
												<div class="form-group header_footer_group">
													<label class="w-100">Footer Hover Color</label>
													<input type="color" id=""  value="<?php if(isset($data['footer_hover_text'])){ echo $data['footer_hover_text']; }?>" name="footer_hover_text">
												</div>
	                    					</div>
	                    					<div class="col-md-1"></div>
	                    				</div>
	                    			</div>
	                    		</div>
	                    		<div class="grey_box">
	                    			<div class="grey_box_header">
	                    				<h4>Menu</h4>
	                    			</div>
	                    			<div class="grey_box_body">
	                    				<div class="row">
	                    					<div class="col-md-1"></div>
	                    					<div class="col-md-10">
	                    						<div class="form-group menu_group">
													<label>Menu Background Color</label>
													<input type="color" id="menu_background_color" name="menu_background_color" value="<?php if(isset($data['menu_background_color'])){ echo $data['menu_background_color']; }?>">
												</div>
												<div class="form-group menu_group">
													<label>Menu Text Color</label>
													<input type="color" id="menu_text_color" name="menu_text_color" value="<?php if(isset($data['menu_text_color'])){ echo $data['menu_text_color']; }?>">
												</div>
												<div class="form-group menu_group">
													<label>Menu Hover Color</label>
													<input type="color" id="menu_hover_color" name="menu_hover_color" value="<?php if(isset($data['menu_hover_color'])){ echo $data['menu_hover_color']; }?>">
												</div>
	                    					</div>
	                    					<div class="col-md-1"></div>
	                    				</div>
	                    			</div>
	                    		</div>
	                    		<div class="grey_box">
	                    			<div class="grey_box_header">
	                    				<h4>Property</h4>
	                    			</div>
	                    			<div class="grey_box_body">
	                    				<div class="row">
	                    					<div class="col-md-1"></div>
	                    					<div class="col-md-10">
	                    						<div class="form-group">
													<label class="w-100">Property Alignment</label>
													<div class="form-check custom_check_div w-auto">
														<input class="form-check-input" type="radio" name="property_alignment"  value="left" <?php if(!empty($data['property_alignment'])){ if($data['property_alignment']=='left'){ echo "checked";}}else{echo "checked";} ?>>
                                                    	<label class="custom_radio"></label>
                                                        <label class="form-check-label">Left</label>
                                                    </div>
                                                    <div class="form-check custom_check_div w-auto">
                                                    	<input class="form-check-input" type="radio" name="property_alignment" value="right" <?php if(!empty($data['property_alignment']) && $data['property_alignment']=='right'){ echo "checked";} ?>>
														<label class="custom_radio"></label>
                                                        <label class="form-check-label">Right</label>
                                                    </div>
												</div>
												<div class="form-group">
													<label class="w-100">No. of Property Display In One Row</label>
													<div class="form-check custom_check_div w-auto">
														<input class="form-check-input" type="radio" name="property_display_row" value="1" <?php if(!empty($data['property_display_row'])){ if($data['property_display_row']=='1'){ echo "checked";}}else{echo "checked";} ?>>
														<label class="custom_radio"></label>
                                                        <label class="form-check-label">One</label>
                                                    </div>
                                                    <div class="form-check custom_check_div w-auto">
                                                    	<input class="form-check-input" type="radio" name="property_display_row" value="2" <?php if(!empty($data['property_display_row']) && $data['property_display_row']=='2'){ echo "checked";} ?>>
                                                    	<label class="custom_radio"></label>
                                                        <label class="form-check-label">Two</label>
                                                    </div>
                                                    <div class="form-check custom_check_div w-auto">
                                                    	<input class="form-check-input" type="radio" name="property_display_row"  value="3" <?php if(!empty($data['property_display_row']) && $data['property_display_row']=='3'){ echo "checked";} ?>>
                                                    	<label class="custom_radio"></label>
                                                        <label class="form-check-label">Three</label>
                                                    </div>
												</div>
												<div class="form-group map_group">
													<label>MAP Pin</label>
													<label class="la la-edit" style="cursor: pointer;">
														<i class="far fa-edit"></i>
														<input style="display: none" type="file" name="property_map_pin" id="property_map_pin" accept="image/*" onchange="pinreadURL(this);">
														<div class="errorImage"></div>
						                            </label>
						                            <div class="map_pin_box">
						                            	<?php if(isset($data['property_map_pin'])){  ?> 
															<img id="property_map_pin_image" src="{{ url('public/uploads/map_pin').'/'.$data['property_map_pin'] }}" >
														<?php }else{?>
															<img id="property_map_pin_image" src="" >
														<?php }?>
						                            </div>
												</div>
	                    					</div>
	                    					<div class="col-md-1"></div>
	                    				</div>
	                    			</div>
	                    		</div>
								<div class="form-group text-center">
									<input type="submit" value="Save" name="save" class="theme-btn btn-color btn-text btn-size auth_btn save_home_banner_data" id="save_home_banner_data" >
								</div>
							</div>
							<div class="col-md-1"></div>
	                	</div>
	                </form>
	  			</div>
		  		<div class="tab-pane fade @if($_REQUEST['data'] == 'contactForm') active show @endif in" id="contactForm" role="tabpanel" aria-labelledby="contactForm-tab">
		  			<form method="POST" action="{{ url('agency/saveContactFormSettings') }}" enctype="multipart/form-data">
					{{ @csrf_field() }}
			  			<div class="row">
		                    <div class="col-md-3"></div>
		                    <div class="col-md-6">
	                    		<div class="form-group">
									<label>Form Title</label>
									<textarea required class="form-control" name="contact_form_title" id="contact_form_title"><?php if(isset($data['contact_form_title'])){ echo $data['contact_form_title']; }?></textarea>
								</div>
								<div class="form-group">
									<label>Background Color</label>
									<input type="color" id="font_color" name="contact_form_background_color" value="<?php if(isset($data['contact_form_background_color'])){ echo $data['contact_form_background_color']; }?>">
								</div>
								<div class="form-group">
									<label>Chat First Auto Message</label>
									<textarea required class="form-control" name="chat_first_auto_message" id="chat_first_auto_message"><?php if(isset($data['chat_first_auto_message'])){ echo $data['chat_first_auto_message']; }?></textarea>
								</div>
								<div class="form-group">
									<?php if(!isset($data['customer_message_send_time'])){ $data['customer_message_send_time']=''; }?>
									<label>Customer Message Send Time</label>
									<select id="customer_message_send_time" name="customer_message_send_time" class="form-control dropdown_control">
										<option value="0">Please Select Time</option>
										<option value="1" <?php if($data['customer_message_send_time']=='1'){echo "selected";} ?>>30 sec</option>
										<option value="2" <?php if($data['customer_message_send_time']=='2'){echo "selected";} ?>>1 Min</option>
										
									</select>
								</div>
							    <div class="form-group text-center">
									<input type="submit" value="Save" name="save" class="theme-btn btn-color btn-text btn-size auth_btn save_home_banner_data" id="save_home_banner_data" >
								</div>
							</div>
		                    <div class="col-md-3"></div>
	                	</div>
	                </form>
	  			</div>
	  			<div class="tab-pane fade @if($_REQUEST['data'] == 'seoSetting') active show @endif in" id="seoSetting" role="tabpanel" aria-labelledby="seoSetting-tab">
	  				<form method="POST" action="{{ url('agency/saveSEOSettings') }}" enctype="multipart/form-data">
					{{ @csrf_field() }}	
			  			<div class="row mt-10">
		                    <div class="col-md-3"></div>
		                    <div class="col-md-6">
	                    		<div class="form-group">
									<label>Property Search SEO Title</label>
									<input type="text" class="form-control" name="meta_title" value="<?php if(isset($data['meta_title'])){ echo $data['meta_title']; }?>">
									<small>SEO Title tags are displayed on search engine results pages (SERPs) as the clickable headline for a given result, and are important for usability, SEO, and social sharing. </small>  
								</div>
								<div class="form-group">
									<label>Property Search SEO Description</label>
									<textarea required class="form-control" name="meta_description" id="meta_description"><?php if(isset($data['meta_description'])){ echo $data['meta_description']; }?></textarea>
									<small>The SEO description tag in HTML is the 160 character snippet used to summarise a web page's content. Search engines sometimes use these snippets in search results to let visitors know what a page is about before they click on it.</small>
								</div>
							    <div class="form-group text-center">
									<input type="submit" value="Save" name="save" class="theme-btn btn-color btn-text btn-size auth_btn save_home_banner_data" id="save_home_banner_data" >
								</div>
							</div>
							<div class="col-md-3"></div>
	                	</div>
	                </form>
	  			</div>
			</div>
		</div>
	</div>
</div>
		
<!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush
@push('custom-scripts')
<!-- Include this Page Js -->
<script src="{{ url('public/admin/bower_components/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
	$(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('agency_text')
    //bootstrap WYSIHTML5 - text editor   
  })
    CKEDITOR.getFullHTMLContent = function(editor){
    var cnt = "";
    editor.once('contentPreview', function(e){
      cnt = e.data.dataValue;
      return false;
    });
    editor.execCommand('preview');
    
    return cnt;
  }
  config.allowedContent = true;
</script>
<script type="text/javascript">
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#agency_banner_image').attr('src', e.target.result);
                $('.upload_here').css("display",'none');
                $('.banner_default_box').css("padding",'0');

            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    function pinreadURL(input) {
    	console.log("test");
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#property_map_pin_image').attr('src', e.target.result);

            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
  <!-- <script type="text/javascript" src="{{ url('public/js/admin/setting/about.js') }}"></script> -->
@endpush