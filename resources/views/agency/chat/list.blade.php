<?php 
  if(Auth::user()->user_type==2){ 
    $layoutName= 'agent.layout.app_with_login';
  }
  else{ 
    $layoutName= 'agency.layout.app_with_login';
  }
?>
@extends($layoutName)
@section('title','Chat List')
@section('content')
<!-- 
View File for  List Credits
@package    Laravel
@subpackage View
@since      1.0
 -->
<style type="text/css">
    .side_content{
        padding: 30px 0px 0px;
    }
    @media (max-width: 767px){
        .side_content {
            padding: 80px 0 0px;
        }
    }
</style>
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
<?php 
    if(!empty($agent_id)){ ?>
    <div class="container-fluid">
        <div class="path_link">
            <a href="{{url('agency/agent')}}" class="">My Agents > </a> <a href="#" class="current_page">Chat List</a>
        </div>
        <div class=""> 
              <ul class="nav nav-tabs text-center theme_tab" id="myTab" role="tablist" style="margin-bottom: 40px;">
                  <li class="nav-item ">
                      <a class="nav-link "  id="agentProperty-tab" href="{{url('/agency/agent/view/'.$agent_id)}}"  >Agent Properties</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" id="designSettings-tab" href="{{url('/agency/agent/leads/'.$agent_id)}}" >Agent Leads</a>
                  </li>
                  <li class="nav-item active">
                      <a class="nav-link active" id="contactForm-tab" data-toggle="tab" href="#contactForm" role="tab" aria-controls="contactForm" aria-selected="false">Agent Chat Threads</a>
                  </li>
              </ul> 

        </div>
        <input type="hidden" name="agent_id" value="{{$agent_id}}">
    </div>
<?php }else{ ?>
  <div class="container-fluid">
    <div class="col-12">
    <div class="path_link">
        <a href="{{url('agency/leads')}}" class="current_page">My Chat</a>
        <input type="hidden" name="agent_id" value="">
    </div>
  </div>
</div>
  <?php } ?>
<div class="dash_sidebar_sidecontent">
    <div class="dash_side_content p-0">
        <div class="inbox_container">
            <div class="inbox_sidebar open">
                <div class="serchbar">
                    <input type="text" name="" placeholder="Search" class="search_control">
                </div>
                <ul class="slim-scroll">
                    <li>
                        <a href="#">
                            <div class="inbox_img">
                                <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                            </div>
                            <p class="name">Richard Carroll1</p>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#">
                            <div class="inbox_img">
                                <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                            </div>
                            <p class="name have_msg">Jessica Alba <span class="msg_counter">2</span></p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="inbox_img">
                                <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                            </div>
                            <p class="name">Paul Munoz</p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="inbox_img">
                                <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                            </div>
                            <p class="name have_msg">Charles Jackson<span class="msg_counter">2</span></p>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <div class="inbox_img">
                                <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                            </div>
                            <p class="name">Christy Wilson</p>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="inbox_content">
                <div class="chat_section slim-scroll">
                    <div class="msg">
                        <div class="msg_profile">
                            <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                        </div>
                        <div class="msg_txt">
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                        </div>
                    </div>
                    <div class="rply">
                        <div class="msg_txt">
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                        </div>
                        <div class="rply_profile">
                            <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                        </div>
                    </div>
                    <div class="msg">
                        <div class="msg_profile">
                            <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                        </div>
                        <div class="msg_txt">
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                        </div>
                    </div>
                    <div class="rply">
                        <div class="msg_txt">
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                        </div>
                        <div class="rply_profile">
                            <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                        </div>
                    </div>
                    <div class="msg">
                        <div class="msg_profile">
                            <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                        </div>
                        <div class="msg_txt">
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                        </div>
                    </div>
                    <div class="rply">
                        <div class="msg_txt">
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                            <p>
                                <span class="chat_name">name</span><br>
                                1Lorem ipsum dolor sit amet, adipiscing<br>
                                <span class="chat_time">date</span>
                            </p><br>
                        </div>
                        <div class="rply_profile">
                            <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/user.jpg">
                        </div>
                    </div>
                  
                  
                  
                    <div class="text-right">
                        <div class="open_list">
                            <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/msg_white.png">
                        </div>
                    </div>
                </div>
                <div class="type_section">
                    <form>
                        <input type="text" name="" placeholder="Replay to Jessica">
                        <div class="file_icon_div">
                            <div class="file_icon_img">
                                <img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/attach.png">
                                <input type="file" name="">
                            </div>
                        </div>
                        <button><img src="http://18.237.50.45/projects/realtorhubs/public/assets/images/message_white.png"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>   

@endsection
@push('custom-styles')

@endpush
@push('custom-scripts')
<script type="text/javascript">
  var base_url='<?php echo url(''); ?>'
</script>
@endpush