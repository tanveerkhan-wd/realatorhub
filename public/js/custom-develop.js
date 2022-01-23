$(document).on('click','.read_all_notifications_admin',function(){
    console.log('addsada')
    $.ajax({
        type: "GET",
        url: base_url_route+'/agency/readnotification',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {
            $('.noti_counter').html('0');
        }
    });
});
$(document).on('click','.read_all_notifications_agent',function(){
    console.log('addsada')
    $.ajax({
        type: "GET",
        url: base_url_route+'/agent/readnotification',
        datatype: JSON,
        processData: false,
        contentType: false,
        cache: false,
        success: function (data) {
            $('.noti_counter').html('0');
        }
    });
});