<div class="dashboard_container">
    <div class="side_bar">
      <ul>
        <li class="@if(Request::url() == url('agency/home'))active @endif">
          <a href="{{url('agency/home')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_dashboard_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_dashboard.png')}}" class="active_menu_icon">
            </span>
            <span>Dashboard</span>
          </a>
        </li>
        <li class="@if(Request::url() == route('agency.property.list'))active @endif">
          <a href="{{route('agency.property.list')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_property.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_property_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Properties</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/leads'))active @endif">
          <a href="{{url('agency/leads')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_leads_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_leads_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Leads</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/agent'))active @elseif(Request::url() == url('agency/agent/add'))active @elseif(Request::url() == url('agency/agent/edit/{id}'))active @endif">
          <a href="{{url('agency/agent')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_agent_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_agent_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Agents</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/customer-list'))active @endif">
          <a href="{{route('agency.customer.list')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_customer_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_customer_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Customers</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/sendmail'))active @endif">
          <a href="{{route('agency.send.mail')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_send_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_send.png')}}" class="active_menu_icon">
            </span>
            <span>Send Email</span>
          </a>
        </li>
          <li class="@if(strpos( trim(Request::url()),trim(url('agency/subscription'))) !== false)active @endif">
          <a href="{{route('agency.subscription')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_subscribe_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_subscribe.png')}}" class="active_menu_icon">
            </span>
            <span>My Subscriptions</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agency/settings'))active @endif">
          <a href="{{url('agency/settings')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_setting_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_setting.png')}}" class="active_menu_icon">
            </span>
            <span>Settings</span>
          </a>
        </li>
      </ul>
    </div>        