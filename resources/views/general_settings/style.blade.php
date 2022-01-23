<?php $general_settings = getGeneralSettings();
?>
<style type="text/css">
  <?php if(isset($general_settings['font_type']) && !empty($general_settings['font_type'])){ ?>
  .font-family, body{
    font-family: <?= $general_settings['font_type'] ?>, sans-serif;
  }
<?php } ?>
<?php if(isset($general_settings['font_color']) && !empty($general_settings['font_color'])){ ?>
  .font-color, body, a, a:hover, .table{
      color:<?= $general_settings['font_color'] ?>;
  }
<?php } ?>
<?php if(isset($general_settings['button_color']) && !empty($general_settings['button_color'])){ ?>
  .btn-color, h1.auth_title:after, .theme_tab li a.nav-link.active, .theme_tab li a.nav-link:hover, .bed_box ul li input[type=checkbox]:checked+span, .step_tab li.active, .step_tab li:hover, .step_tab li.active:after{
      background-color:<?= $general_settings['button_color'] ?>; 
  }
  .bottom-link a, p.btn-color-title, .btn-color-title, .loader svg, .auth_link a, .main_header a.nav-link.active, .main_header a.nav-link:hover, .bottom-info svg, .map_pro_detail svg, p.bed_bath span svg{
    color:<?= $general_settings['button_color'] ?>; 
  }
  .btn-border{
      border:1px solid <?= $general_settings['button_color'] ?>;
      color:<?= $general_settings['button_color'] ?>;
  }
<?php } ?>
<?php if(isset($general_settings['button_text_color']) && !empty($general_settings['button_text_color'])){ ?>  
  .btn-text, .theme_tab li a.nav-link.active, .theme_tab li a.nav-link:hover, a.theme-btn:hover, ul.dropdown-menu.noti_menu li.footer a, .bed_box ul li input[type=checkbox]:checked+span, .step_tab li.active a, .step_tab li:hover a{
      color:<?= $general_settings['button_text_color'] ?> !important; 
  }
<?php } ?>
<?php if(isset($general_settings['btn_size']) && !empty($general_settings['btn_size'])){ ?>
  .btn-size{
      font-size:<?= $general_settings['btn_size'] ?>px;
  }
<?php } ?>
<?php if(isset($general_settings['header_background_color']) && !empty($general_settings['header_background_color'])){ ?>
  .header-bg{
      background-color:<?= $general_settings['header_background_color'] ?>;
  }
<?php } ?>
<?php if(isset($general_settings['header_text_color']) && !empty($general_settings['header_text_color'])){ ?>
  .header-text, .header-text a, li.dropdown.profile_drop_div button{
      color: <?= $general_settings['header_text_color'] ?> !important;
  }
<?php } ?>
<?php if(isset($general_settings['footer_background_color']) && !empty($general_settings['footer_background_color'])){ ?>
  .footer-bg{
      background-color: <?= $general_settings['footer_background_color'] ?>;
  }
<?php } ?>
<?php if(isset($general_settings['footer_text_color']) && !empty($general_settings['footer_text_color'])){ ?>
  .footer-text, .footer-text a{
      color: <?= $general_settings['footer_text_color'] ?>;
  }
<?php } ?>
<?php if(isset($general_settings['menu_background_color']) && !empty($general_settings['menu_background_color'])){ ?>
  .menu-bg{
      background-color: <?= $general_settings['menu_background_color'] ?>;
  }
<?php } ?>
<?php if(isset($general_settings['menu_text_color']) && !empty($general_settings['menu_text_color'])){ ?>
  .menu-text, .menu-text a{
      color: <?= $general_settings['menu_text_color'] ?> ;
  }
<?php } ?>
<?php if(isset($general_settings['menu_hover_color']) && !empty($general_settings['menu_hover_color'])){ ?>
  .menu-hover-text a:hover{
      color: <?= $general_settings['menu_hover_color'] ?> !important;
  }
<?php } ?>
<?php if(isset($general_settings['form_background_color']) && !empty($general_settings['form_background_color'])){ ?>
  .form-bg{
      background-color: <?= $general_settings['form_background_color'] ?>;
  }
<?php } ?>
<?php if(isset($general_settings['header_hover_text']) && !empty($general_settings['header_hover_text'])){ ?>
  .header-hover-text a:hover{
      color:<?= $general_settings['header_hover_text'] ?> !important;
  }
  .header-hover-text a.hvr-underline-from-left:before{
      background-color: <?= $general_settings['header_hover_text'] ?> !important;
  }
<?php } ?>
<?php if(isset($general_settings['footer_hover_text']) && !empty($general_settings['footer_hover_text'])){ ?>
  .footer-hover-text a:hover{
      color: <?= $general_settings['footer_hover_text'] ?>;
  }
<?php } ?>
<?php if(isset($general_settings['contact_form_background_color']) && !empty($general_settings['contact_form_background_color'])){ ?>
  .form-bg{
      background-color: <?= $general_settings['contact_form_background_color'] ?>;
  }
<?php } ?>

</style>