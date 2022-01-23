/**
 * List school
 *
 * This file is used for school Listing Datatable , Active and Delete.
 *
 * @package    Laravel
 * @subpackage JS
 * @since      1.0
 */
// For Datatable
var prefixModule = base_url + '/admin/subscriptions/',
   ajaxDataTable = prefixModule+'listAjax';
dTable = $('#datatableData').dataTable({
    processing: true,
    serverSide: true,
    searching: false,
    scrollX:true,
    "ordering": false,
    "bLengthChange": true,
    "bInfo" : false,
    "dom": "<'row'<'col-sm-12'l><'col-sm-12'f>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-12'i><'col-sm-12'p>>"+
    '<"clear">',
    "order": [[1,'id']],
    "lengthMenu": [[10, 25, 50,100 ,-1], [10, 25, 50,100,"All"]],
    oLanguage: {
        //sProcessing: showMessage()
    },
    "initComplete": function (settings, json) {
        $(".checkall").closest("th").removeClass("sorting_asc");
    },
    ajax: {
        url: ajaxDataTable,
        type: 'post',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: ''
    },
    columns: [
        {data: 'id', name: 'id',orderable:false},
        {data: 'date', name: 'date'},
        {data: 'plan_name', name: 'plan_name'},
        {data: 'monthly_price', name: 'monthly_price'},
        {data: 'no_of_agent', name: 'no_of_agent'},
        {data: 'subscriptions_count', name: 'subscriptions_count'},
        {data: 'is_deleted', name: 'is_deleted'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        $("td:first", nRow).html(iDisplayIndex +1);
        return nRow;
    },
    aoColumnDefs: [
        {
            "mRender": function (data, type, row) {
                // var dateTime = new Date(row.created_at);
                // dateTime = moment(dateTime).format("YYYY-MM-DD HH:mm:ss");
                var html =changeTimezone(row.created_at);
                // var html =row.created_at;

                return html;
            },
            "aTargets": [1]
        },
        {
            "mRender": function (data, type, row) {

                var html = row.plan_name;

                return html;
            },
            "aTargets": [2]
        },
        {
            "mRender": function (data, type, row) {

                var html = row.monthly_price;

                return html;
            },
            "aTargets": [3]
        },
        {
            "mRender": function (data, type, row) {

                var html = row.no_of_agent;

                return html;
            },
            "aTargets": [4]
        },
        {   
            "mRender": function (data, type, row) {
                if(row.subscriptions_count > 0){
                    var html =row.subscriptions_count;
                }else{
                    var html =0;
                }
                return html;
            },
            "aTargets": [5]
        },
        {   
            "mRender": function (data, type, row) {
                var status =row.is_deleted;
                if(status == 1){
                    var html = 'Yes';    
                }else{
                    var html = 'No';
                }
                return html;
            },
            "aTargets": [6]
        },
        {
            "mRender": function (data, type, row) {
                var is_deleted =row.is_deleted;
                html = '<a href="' + prefixModule + row.id + '/show" class="viewData btn btn-primary btn-sm" style="color: blue;padding: 5px 10px;" data-url="" title="View" "><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                if(is_deleted!=1){
                    var id = row.id;
                    
                    html += '<a href="javascript:void(0)" class="deleteData btn btn-danger btn-sm" data-id="'+row.id+'"  style="color: red;padding: 5px 10px;" data-url="' + prefixModule +row.id+'/delete" title="Delete" "><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;';

                    
                }
                return html;
            },
            "aTargets": [7]
        },
    ]
});

$(document).on('click','.deleteData',function(){
    var deleteAjaxSource = $(this).data('url');
    console.log(deleteAjaxSource);
    var id = $(this).attr('data-id');
    swal({
        text: "Are you sure you want to delete this plan?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'blue_button alert_btn mr-40',
        cancelButtonClass: 'blue_border_button alert_btn',
        confirmButtonText: 'Yes'
    }).then(function (isConfirm) {
        if (isConfirm.value == true) {
            $('.loader-outer-container').css('display','');
            $.ajax({
                type: "POST",
                url: deleteAjaxSource,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('.loader-outer-container').css('display','none');
                    if (data.code == 200) {
                        swal('Success',"Subscription Plan Deleted Successfully",'success');
                        dTable.fnDraw(true);
                    }
                    else{
                        toastr.error(data.message);
                    }

                }
            });
        }
    });
});



function changeTimezone(time) {
    var format = 'DD-MM-YYYY HH:mm:ss';
    var abc = moment(time).tz(timezone).format(format);
    return abc;
}