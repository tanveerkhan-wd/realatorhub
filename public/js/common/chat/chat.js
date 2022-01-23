var socket_base_url = "http://18.237.50.45", portNo ="8002";
    var socket = io(socket_base_url+":"+portNo);
    $(document).ready(function(){
		$('input[name="chat_img"]').change(function(e){
			var ext = $(this).val().split(".");
			ext = ext[ext.length-1].toLowerCase();      
			var arrayExtensions = ["jpg" , "jpeg", "png"];

			/*if (arrayExtensions.lastIndexOf(ext) == -1) {
				toastr.error("Please upload only jpg,jpeg,and png file");
			    //alert("Please upload only jpg,jpeg, and png file");
			    //$("#image").val("");
			}else{*/
			  	//$('#pageLoadingDiv').css('display','table');
                if (e.files && e.files[0]) {
		            var reader = new FileReader();

		            reader.onload = function (e1) {
		                var uploadedimage=e1.target.result;

		            }

		            reader.readAsDataURL(e.files[0]);
		        }
            	var from = $('#user').val();
                var message = $('#m').val();
                
                var to = $("#to").val();
                var profile_image = $("#profile_image").val();
                //var formData = new FormData($("#chat-form"+id));
                var form = $("#chat-form")[0];
			    var data = new FormData(form);
			    var message_type='';
			    if( document.getElementById('chat_img').files.length != 0 ){
			    	message_type="image";
			    }else{
			    	message_type="text";
			    }
			    $('#m').val('');
			    if(message_type!=''){
			    	$.ajax({
			              type: "POST",
			              url: image_url+"common/send_chat_msg",
			              data: data,
			              dataType: "json",
			              processData: false,
			              contentType: false,
			              success: function(result){
			              	$('#pageLoadingDiv').css('display','none');
			              	if(result.code==201){
			              		toastr.error(result.message);
			              	}else{
			              		var responseMessage=result.message;
			              		var time=result.time;
				              	socket.emit('chatMessage', from, {msg:responseMessage,to:to,message_type:message_type,id:to,profile_image:profile_image,name:name,current_room_id:current_room_id,time:time});
				              	$('#chat_img').val(null);
				              	$('#m').val('');
				              	$('#m').prop('disabled', false);
			              	}
			              }
		            });
			    }		  
			//}
		});
  	});
  	function submitfunction() {
 		//$('#pageLoadingDiv').css('display','table');
          
    	var from = $('#user').val();
        var message = $('#m').val();
        var profile_image = $("#profile_image").val();
        var name = $("#user_name").val();
        //var formData = new FormData($("#chat-form"+id));
        var form = $("#chat-form")[0];
	    var data = new FormData(form);
	    var message_type='';
	    if( document.getElementById('chat_img').files.length != 0 ){
	    	message_type="image";
	    }else{
	    	message_type="text";
	    }
	    $('#m').val('');
	    if(message_type!='' && message!=''){
	    	$.ajax({
	              type: "POST",
	              url: image_url+"common/send_chat_msg",
	              data: data,
	              dataType: "json",
	              processData: false,
	              contentType: false,
	              success: function(result){
	              	$('#pageLoadingDiv').css('display','none');
	              	if(result.code==201){
	              		toastr.error(result.message);
	              	}else{
		              	var responseMessage=result.message;
		              	var time=result.time;
		              	socket.emit('chatMessage', from, {msg:responseMessage,message_type:message_type,profile_image:profile_image,name:name,current_room_id:current_room_id,time:time});
		              	$('#chat_img').val(null);
		              	$('#m').val('');
		              	$('#m').prop('disabled', false);
		            }
	              }
            });
	    }				
		
		return false;
		}
  	function notifyTyping() {
        var user = $('#user').val();
        console.log('on key up',user);
        socket.emit('start_typing', user);
    }
    function changeTimezone(time) {
	    var format = 'YYYY-MM-DD HH:mm';
	    abc = moment(time).tz(timezone).format(format);
	    return abc;
	}

            socket.on('chatMessage', function (from,msg) {
            	//app_type 1=>mobile, 0=>web
                var me = $('#user').val();
                var color = (from == me) ? 'green' : '#009afd';
                var from = from;
                var time = msg.time;
                var current_time=changeTimezone(time);
				//var current_time=time.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
               if(msg.message_type=="text"){
               	   //if(msg.msg.indexOf("sp_awaiting_view") > -1){
               	   	if(from==me){
               	   		$('#chat_area_'+msg.current_room_id).append('<div class="rply"><div class="msg_txt"><p><span class="chat_name">'+msg.name+'</span><br>'+msg.msg+'<br><span class="chat_time">'+current_time+'</span></p><br></div><div class="rply_profile"><img src="'+msg.profile_image+'"></div></div>');
               	   		
               	   	}else{
               	   		$('#chat_area_'+msg.current_room_id).append('<div class="msg"><div class="msg_profile"><img src="'+msg.profile_image+'"></div><div class="msg_txt"><p><span class="chat_name">'+msg.name+'</span><br>'+msg.msg+'<br><span class="chat_time">'+current_time+'</span></p><br></div></div>');
               	   	}
               	}else{
               		if(from==me){
               			$('#chat_area_'+msg.current_room_id).append('<div class="rply"><div class="msg_txt"><p><span class="chat_name">'+msg.name+'</span><br><img src="'+image_url+msg.msg+'" onclick="image_popup(\''+image_url+msg.msg+'\')"><br><span class="chat_time">'+current_time+'</span></p><br></div><div class="rply_profile"><img src="'+msg.profile_image+'"></div></div>');
               	   		
               	   	}else{
               	   		$('#chat_area_'+msg.current_room_id).append('<div class="msg"><div class="msg_profile"><img src="'+msg.profile_image+'"></div><div class="msg_txt"><p><span class="chat_name">'+msg.name+'</span><br><img src="'+image_url+msg.msg+'" onclick="image_popup(\''+image_url+msg.msg+'\')"><br><span class="chat_time">'+current_time+'</span></p><br></div></div>');
               	   	}
               		/*$('#chat_area'+msg.id).append('<div class="rply"><div class="msg_txt"><img src="'+image_url+msg.msg+'" onclick="image_popup(\''+image_url+msg.msg+'\')"><div class="date">'+current_time+'</div><br></div></div>');
               		$('#chat_area'+from).append('<div class="msg"><div class="msg_txt"><img src="'+image_url+msg.msg+'" onclick="image_popup(\''+image_url+msg.msg+'\')"><div class="date">'+current_time+'</div><br></div></div>');*/
               		/*$('#list_msg_des'+msg.id+' .last_msg').html('Image');
               		$('#list_msg_des'+from+' .last_msg').html('Image');
               		$('#list_msg_des'+msg.id+' .last_msg_time').html(current_time);
               		$('#list_msg_des'+from+' .last_msg_time').html(current_time);*/
               	}
               	$("#chat_area_"+msg.current_room_id).scrollTop($("#chat_area_"+msg.current_room_id)[0].scrollHeight);
                $(".chat_section").scrollTop($(".chat_section")[0].scrollHeight);
          	});

            socket.on('start_typing', function (user) {
                var me = $('#user').val();
                console.log('before',me);
                console.log('before user',user);
                if (user != me) {
                    $('#notifyUser').text(user + ' is typing ...');
                }
                console.log('after',me);
                setTimeout(function () {
                    $('#notifyUser').text('');
                }, 10000);
                ;
            });