/**
* List Email
*
* This file is used for faq Listing Datatable , Active and Delete.
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
// For Datatable
var baseModule =base_url+'/agency';
var prefixModule = base_url + '/agency/notifications/',
    activeInactiveAjaxSource = prefixModule + 'activeInactive',
    addEditSource = prefixModule,
    deleteAjaxSource ='',
    ajaxDataTable = prefixModule+'notificationAjax';
dTable = $('#datatableData').dataTable({
    // dom: "<'row'<'col-12'<'col-6'p><'col-6'l>>r>" +
    // "<'row'<'col-12't>>" +
    // "<'row'<'col-12'<'col-6'i><'col-6'p>>>",
    processing: true,
    serverSide: true,
    searching: false,
    "bLengthChange": false,
    "bInfo" : false,
    "dom": '<"top"i>rt<"bottom"flp><"clear">',
    scrollX:true,
    "bSort": false,
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
        data: function (d) {
            d.title = $('input[name=title]').val();
        }
    },
    columns: [
        {data: 'notification_desc', name: 'notification_desc'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    aoColumnDefs: [       
        {
            "mRender": function (data, type, row) {
                var notifi = '<a href="'+baseModule+row.link+'" class="noti_content">'+row.notification_desc +'</a>'
                var html = notifi +' - '+ changeTimezone(row.created_at);

                return html;
            },
            "aTargets": [0]
        },
        {
            "mRender": function (data, type, row) {

                var status = "";
                if ($.trim(row.status) == "Active") {
                    status = 'Inactive';
                } else {
                    status = 'Active';
                }
                var html = '';

                html += '<table border="0" style="width:90px;">';
                html += '<tr>';
                html += '<a href="' + prefixModule +row.n_id+'/delete" class="deleteData action_icon" title="Delete" "><img src="'+base_url+'/public/assets/images/ic_delete.png"></a>&nbsp;&nbsp;';
                //html += '<a href="javascript:void(0)" class="deleteData" data-url="' + deleteAjaxSource +'/'+row.id+'" title="Delete" ">Delete</a>&nbsp;&nbsp;';
                /*if (deleteAjaxSource) {
                    html += '<a href="javascript:void(0)" title="Delete" class="table-delete text-danger" onclick="deleteAll(\'' + single + '\',' + row.id + ')"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;';
                }*/
                html += '</tr>';
                html += '</table>';
                return html;
            },
            "aTargets": [1]
        },
    ]
});


// Both Function for Filter 
$('#search-form input').on('keyup', function (e) {
    dTable.fnDraw(true);
    e.preventDefault();
});
$('#search-form select').on('change', function (e) {
    dTable.fnDraw(true);
    e.preventDefault();
});
//Function used for Change Sort ORder
$(document).on('blur','.sort_order',function(){
    var deleteAjaxSource = $(this).data('url');
    $.ajax({
            type: "POST",
            url: base_url+'/adminstaffelyadmin420/faqs/changeSortOrder',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                'faq_id' : $(this).attr('data-question-id'),
                'sort_number' : $(this).val(),
            },
            success: function (data) {
                if (data == 1) {
                    //swal('Success',"Data Deleted success",'success');
                }
                dTable.fnDraw(true);
            }
        });
});


function changeTimezone(time) {
    var format = 'DD-MM-YYYY HH:mm:ss';
    var abc = moment(time).tz(timezone).format(format);
    return abc;
}