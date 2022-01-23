<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->

   
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="@if(Request::url() == url('admin/home'))active @endif"><a href="{{ url('admin/home') }}">
        {{--<img src="{{ url('public/admin/dist/img/ic_dashboard.png') }}" class="menu_icon">--}}
        {{--<img src="{{ url('public/admin/dist/img/ic_dashboard_green.png') }}" class="active_menu_icon"><span> Dashboard</span></a>--}}
          <i class="fa fa-desktop"></i> Dashboard</a>
      </li>
      <li  class="@if(Request::url() == url('admin/agency/list'))active @endif">
        <a href="{{route('admin.agency.list')}}"><i class="fa fa-list-alt"></i> Agencies</a>
      </li>
      <li  class="@if(Request::url() == url('admin/property/list'))active @endif">
        <a href="{{route('admin.property.list') }}"><i class="fa fa-list-alt"></i> Properties</a>
      </li>
      <!-- <li  class="@if(Request::url() == url('admin/general-settings'))active @endif">
        <a href="{{ url('admin/general-settings') }}"><i class="fa fa-cog"></i> General Settings</a>
      </li> -->
      <li class="treeview">
        <a href="#">
          <i class="fa fa-cog"></i>
          <span>Settings</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu checkActive">
            
          

          <li active-slug="admin/settings" class=" @if( strpos( trim(Request::url()),trim(url('admin/settings'))) !== false && isset($is_setting))
            active
          @endif"><a  class="ajax_request   @if( strpos( trim(Request::url()),trim(url('admin/settings'))) !== false  && isset($is_setting))
            menucolor
          @endif" data-slug="admin/settings" href="{{ url('admin/settings') }}"><i class="fa fa-circle-o"></i>Home Page</a></li>
          <li active-slug="admin/settings/aboutus-setting" class="@if( strpos( trim(Request::url()),trim(url('admin/about-us-setting'))) !== false)
            active
          @endif"><a  class="ajax_request @if( strpos( trim(Request::url()),trim(url('admin/about-us-setting'))) !== false)
            menucolor
          @endif" data-slug="admin/about-us-setting" href="{{ url('admin/about-us-setting') }}"><i class="fa fa-circle-o"></i>About Us</a></li>

          <li active-slug="admin/settings/contact-us-setting" class="@if( strpos( trim(Request::url()),trim(url('admin/contact-us-setting'))) !== false)
            active
          @endif"><a  class="ajax_request @if( strpos( trim(Request::url()),trim(url('admin/contact-us-setting'))) !== false)
            menucolor
          @endif" data-slug="admin/contact-us-setting" href="{{ url('admin/contact-us-setting') }}"><i class="fa fa-circle-o"></i>Contact Us</a></li>
          <li  class="@if(Request::url() == url('admin/faq-setting')) active @endif"><a href="{{ url('admin/faq-setting') }}"><i class="fa fa-circle-o"></i>FAQs</a></li>
          <li active-slug="admin/general-settings" class="@if( strpos( trim(Request::url()),trim(url('admin/general-settings'))) !== false)
            active
          @endif"><a  class="ajax_request  @if( strpos( trim(Request::url()),trim(url('admin/general-settings'))) !== false)
            menucolor
          @endif" data-slug="admin/general-settings" href="{{ url('admin/general-settings') }}"><i class="fa fa-circle-o"></i>General Settings</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-cog"></i>
          <span>Blog</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>        
        <ul class="treeview-menu checkActive">
            <li class="@if(strpos( trim(Request::url()),trim(url('admin/blog-post-category'))) !== false ) active @endif"><a href="{{ url('admin/blog-post-category') }}"><i class="fa fa-circle-o"></i>Blog Post Categories</a></li>

           <li class="@if(Request::url() == url('admin/blog-post')) active @elseif(Request::url() == url('admin/blog-post/add')) active  @elseif(Request::segment(2)=='blog-post') active @endif"><a href="{{ url('admin/blog-post') }}"><i class="fa fa-circle-o"></i>Blog Post</a></li>
        </ul>
      </li>
      <li  class="@if(Request::url() == url('admin/subscriptions'))active @endif">
        <a href="{{ url('admin/subscriptions') }}"><i class="fa fa-list-alt"></i> Subscriptions</a>
      </li>
      <li  class="@if(Request::url() == url('admin/transactions'))active @endif">
        <a href="{{ url('admin/transactions') }}"><i class="fa fa-list-alt"></i> Transactions</a>
      </li>
      <li  class="@if(Request::url() == url('admin/email-template'))active @endif">
        <a href="{{ url('admin/email-template') }}"><i class="fa fa-list-alt"></i> Email Template</a>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-cog"></i>
          <span>Masters</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-right pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu checkActive">
          <li class="@if( strpos( trim(Request::url()),trim(url('admin/cms'))) !== false )
            active
          @endif"><a href="{{ url('admin/cms') }}"><i class="fa fa-circle-o"></i>CMS</a></li>

          <li class="@if( strpos( trim(Request::url()),trim(url('admin/faqs'))) !== false )
            active
          @endif"><a href="{{ url('admin/faqs') }}"><i class="fa fa-circle-o"></i>FAQS</a></li>

        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>