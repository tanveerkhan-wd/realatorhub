<div class="dashboard_container">
    <div class="side_bar">
      <ul>
        <li class="@if(Request::url() == url('agent/home'))active @endif">
          <a href="{{route('agent.home')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_dashboard_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_dashboard.png')}}" class="active_menu_icon">
            </span>
            <span>Dashboard</span>
          </a>
        </li>
          <li class="@if(Request::url() == route('agent.property.list'))active @endif">
          <a href="{{route('agent.property.list')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_property.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_property_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Properties</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agent/leads'))active @endif">
          <a href="{{url('agent/leads')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_leads_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_leads_white.png')}}" class="active_menu_icon">
            </span>
            <span>My Leads</span>
          </a>
        </li>
        <li class="@if(Request::url() == url('agent/sendmail'))active @endif">
          <a href="{{route('agent.send.mail')}}">
            <span class="menu_icon">
              <img src="{{url('public/assets/images/ic_send_grey.png')}}" class="menu_icon">
              <img src="{{url('public/assets/images/ic_send.png')}}" class="active_menu_icon">
            </span>
            <span>Send Email</span>
          </a>
        </li>
      </ul>
    </div>        