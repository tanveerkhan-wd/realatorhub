/**
* List Comment
*
* This file is used for Comment Listing Datatable , Active and Delete.
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
// For Datatable
var prefixModule = base_url + '/admin/blog-comment/',
    activeInactiveAjaxSource = prefixModule + 'activeInactive',
    addEditSource = prefixModule,
    deleteAjaxSource = prefixModule + 'delete',
    ajaxDataTable = prefixModule+'blogCommentAjax';
dTable = $('#datatableData').dataTable({
    // dom: "<'row'<'col-12'<'col-6'p><'col-6'l>>r>" +
    // "<'row'<'col-12't>>" +
    // "<'row'<'col-12'<'col-6'i><'col-6'p>>>",
    processing: true,
    serverSide: true,
    searching: false,
    scrollX:true,
    "bLengthChange": false,
    "bInfo" : false,
    aaSorting: [[0, 'desc']],
    "dom": '<"top"i>rt<"bottom"flp><"clear">',
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
            d.status = $('select[name=status]').val();
        }
    },
    columns: [
        {data: 'id', name: 'id',orderable:false},
        {data: 'title', name: 'title'},
        {data: 'name', name: 'name'},
        {data: 'status', name: 'status'},
        {data: 'action', name: 'action', orderable: false, searchable: false},
    ],
    "fnRowCallback" : function(nRow, aData, iDisplayIndex){      
      var oSettings = dTable.fnSettings();
       $("td:first", nRow).html(oSettings._iDisplayStart+iDisplayIndex +1);
       return nRow;
    },
    aoColumnDefs: [    
        {   
            "mRender": function (data, type, row) {

                var status =row.status;
                 var active = 'Approved';
                 var inactive = 'Disapproved';
                 var html = '';
                if (status == 'Approved') {
                        //html += '<a href="#" title="Active" class="table-active"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                        html += '<a href="javascript:void(0)" class="changeStatus" data-url="'+activeInactiveAjaxSource+'/'+row.id+'" title="Mark as Disapproved">'+row.status+'</a>&nbsp;&nbsp;';
                    }
                    if (status == 'Disapproved') {                        
                        //html += '<a href="#" title="Active" class="table-active" onclick="activeInactiveAll(\'' + inactive + '\',' + row.id + ',\'\' + single + \'\')"><i class="fa fa-eye-slash"></i></a>&nbsp;&nbsp;';
                        html += '<a href="javascript:void(0)" class="changeStatus inactiveClass" data-url="'+activeInactiveAjaxSource+'/'+row.id+'" title="Mark as Approved">'+row.status+'</a>&nbsp;&nbsp;';
                    }
                return html;
            },
            "aTargets": [3]
        },  
        {
            "mRender": function (data, type, row) {

                var status = "";
                if ($.trim(row.status) == "Approved") {
                    status = 'Disapproved';
                } else {
                    status = 'Approved';
                }
                

                var html = '';

                html += '<table border="0" style="width:90px;">';
                html += '<tr>';

                /*if (activeInactiveAjaxSource) {
                    var active = 'Approved';
                    var inactive = 'Disapproved';
                    var single = 'single';
                    if (status == 'Approved') {
                        //html += '<a href="#" title="Active" class="table-active"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                        html += '<a href="javascript:void(0)" class="changeStatus" data-url="'+activeInactiveAjaxSource+'/'+row.id+'" title="Mark as Approved"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    }
                    if (status == 'Disapproved') {                        
                        //html += '<a href="#" title="Active" class="table-active" onclick="activeInactiveAll(\'' + inactive + '\',' + row.id + ',\'\' + single + \'\')"><i class="fa fa-eye-slash"></i></a>&nbsp;&nbsp;';
                        html += '<a href="javascript:void(0)" class="changeStatus" data-url="'+activeInactiveAjaxSource+'/'+row.id+'" title="Mark as Disapproved"><i class="fa fa-eye-slash"></i></a>&nbsp;&nbsp;';
                    }
                }*/
                if (addEditSource) {
                    html += '<a href="javascript:void(0)" data-url="'+addEditSource+'viewComment/'+row.id+'" title="View" class="table-edit viewComment">View</a>&nbsp;&nbsp;';
                }
                html += '<a href="javascript:void(0)" class="deleteData" data-url="' + deleteAjaxSource +'/'+row.id+'" title="Delete" ">Delete</a>&nbsp;&nbsp;';
                /*if (deleteAjaxSource) {
                    html += '<a href="javascript:void(0)" title="Delete" class="table-delete text-danger" onclick="deleteAll(\'' + single + '\',' + row.id + ')"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;';
                }*/
                html += '</tr>';
                html += '</table>';
                return html;
            },
            "aTargets": [4]
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

//Function used for change Status
$(document).on('click','.changeStatus',function(){
    var changeStatus = $(this).data('url');
    swal({
            text: "Are you sure you want to change Status?",
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {                
                $.ajax({
                    type: "POST",
                    url: changeStatus,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data == 1) {
                            swal('Success',"Record Updated",'success');
                        }
                        dTable.fnDraw(true);
                    }
                });
            }
        });
});

//Function used for View Comment
$(document).on('click','.viewComment',function(){
    var viewComment = $(this).data('url');
    $.ajax({
            type: "POST",
            url: viewComment,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#incomplete_view_profile').html(data.view).modal('show');             
            }
        });
});

//Function used for Delete
$(document).on('click','.deleteData',function(){
    var deleteAjaxSource = $(this).data('url');
    swal({
            text: "Are you sure you want to delete?",
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {                
                $.ajax({
                    type: "POST",
                    url: deleteAjaxSource,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data == 1) {
                            swal('Success',"Data Deleted success",'success');
                        }
                        dTable.fnDraw(true);
                    }
                });
            }
        });
})
