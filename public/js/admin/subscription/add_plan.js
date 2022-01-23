$('#add-plan-form').validate({
    errorPlacement: function (error, element) {
        var name = $(element).attr("name");
        error.appendTo($("#" + name + "_validate"));
    },
});
$(document).off('click','#save_plan');
$(document).on('click','#save_plan',function (e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: base_url + '/admin/subscriptions/store',
        data: $('#add-plan-form').serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            console.log(data)
            if(data.code == 301){
                toastr.warning(data.message)
            }
            else if(data.code == 500){
                toastr.error(data.message)
            }
            else{
                toastr.success(data.message)
                window.location.reload();
            }
        }
    });

})