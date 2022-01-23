/**
 * List About us Image Logo 
 *
 * This file is used for Blog Category Listing Datatable , Active and Delete.
 * 
 * @package    Laravel
 * @subpackage JS
 * @since      1.0
 */
// For Datatable
var prefixModule = base_url + '/admin/blog-post/',
    activeInactiveAjaxSource = prefixModule + 'activeInactive',
    addEditSource = prefixModule,
    deleteAjaxSource = prefixModule + 'delete',
    ajaxDataTable = prefixModule + 'blogpostAjax';
dTable = $('#datatableData').dataTable({
    // dom: "<'row'<'col-12'<'col-6'p><'col-6'l>>r>" +
    // "<'row'<'col-12't>>" +
    // "<'row'<'col-12'<'col-6'i><'col-6'p>>>",
    processing: true,
    serverSide: true,
    searching: false,
    "bLengthChange": false,
    "bInfo": false,
    "dom": '<"top"i>rt<"bottom"flp><"clear">',
    scrollX: true,
    // aaSorting: [[2, 'asc']],
    oLanguage: {
        //sProcessing: showMessage()
    },
    "initComplete": function(settings, json) {
        $(".checkall").closest("th").removeClass("sorting_asc");
    },
    ajax: {
        url: ajaxDataTable,
        type: 'post',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: function(d) {
            d.title = $('input[name=title]').val();
            d.status = $('select[name=status]').val();
        }
    },
    columns: [
        { data: 'id', name: 'id', orderable: false },
        { data: 'title', name: 'title' },
        { data: 'title', name: 'title' },
        { data: 'status', name: 'status' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
    ],
    "fnRowCallback": function(nRow, aData, iDisplayIndex) {
        var oSettings = dTable.fnSettings();
        $("td:first", nRow).html(oSettings._iDisplayStart + iDisplayIndex + 1);
        return nRow;
    },
    aoColumnDefs: [{
            "mRender": function(data, type, row) {
                var html = '<img src="' + base_url + '/public/uploads/blog_image/' + row.image + '" width="100px" height="auto">';
                return html;
            },
            "aTargets": [1]
        },
        {
            "mRender": function(data, type, row) {

                var status = row.status;
                if (status == '1') {
                    var html = '<a title="Mark as Inactive" href="javascript:void(0)" class="changeStatus" data-url="' + activeInactiveAjaxSource + '/' + row.id + '">Active</a>';
                } else {
                    var html = '<a href="javascript:void(0)" class="changeStatus inactiveClass" title="Mark as Active" data-url="' + activeInactiveAjaxSource + '/' + row.id + '">Inactive</a>';
                }
                return html;
            },
            "aTargets": [3]
        },
        {
            "mRender": function(data, type, row) {

                var status = "";
                if ($.trim(row.status) == "0") {
                    status = 'Inactive';
                } else {
                    status = 'Active';
                }
                var html = '';

                html += '<table border="0" style="width:90px;">';
                html += '<tr>';

                /*if (activeInactiveAjaxSource) {
                    var active = 'Active';
                    var inactive = 'Inactive';
                    var single = 'single';
                    if (status == 'Active') {
                        //html += '<a href="#" title="Active" class="table-active"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                        html += '<a href="javascript:void(0)" class="changeStatus" data-url="'+activeInactiveAjaxSource+'/'+row.id+'" title="Mark as Active"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;';
                    }
                    if (status == 'Inactive') {
                        //html += '<a href="#" title="Active" class="table-active" onclick="activeInactiveAll(\'' + inactive + '\',' + row.id + ',\'\' + single + \'\')"><i class="fa fa-eye-slash"></i></a>&nbsp;&nbsp;';
                        html += '<a href="javascript:void(0)" class="changeStatus" data-url="'+activeInactiveAjaxSource+'/'+row.id+'" title="Mark as Inactive"><i class="fa fa-eye-slash"></i></a>&nbsp;&nbsp;';
                    }
                }*/
                if (addEditSource) {
                    html += '<a href="' + addEditSource + row.id + '/edit" title="Edit" class="table-edit "><img src="'+base_url+'/public/assets/images/ic_edit_white.png"></a>&nbsp;&nbsp;';
                }
                html += '<a href="javascript:void(0)" class="deleteData" data-url="' + deleteAjaxSource + '/' + row.id + '" title="Delete" "><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;';
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
$('#search-form input').on('keyup', function(e) {
    dTable.fnDraw(true);
    e.preventDefault();
});
$('#search-form select').on('change', function(e) {
    dTable.fnDraw(true);
    e.preventDefault();
});

//Function used for change Status
$(document).on('click', '.changeStatus', function() {
    var changeStatus = $(this).data('url');
    swal({
        text: "Are you sure you want to change Status?",
        type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'blue_button alert_btn mr-40',
        cancelButtonClass: 'blue_border_button alert_btn',
        confirmButtonText: 'Yes'
    }).then(function(isConfirm) {
        if (isConfirm.value == true) {
            $.ajax({
                type: "POST",
                url: changeStatus,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data == 1) {
                        swal('Success', "Status Changed Successfully.", 'success');
                    }
                    dTable.fnDraw(true);
                }
            });
        }
    });
});


//Function used for Delete
$(document).on('click', '.deleteData', function() {
    var deleteAjaxSource = $(this).data('url');
    swal({
        text: "Are you sure you want to delete blog?",
        type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'blue_button alert_btn mr-40',
        cancelButtonClass: 'blue_border_button alert_btn',
        confirmButtonText: 'Yes'
    }).then(function(isConfirm) {
        if (isConfirm.value == true) {
            $.ajax({
                type: "POST",
                url: deleteAjaxSource,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    if (data == 1) {
                        swal('Success', "Data Deleted success", 'success');
                    }
                    dTable.fnDraw(true);
                }
            });
        }
    });
})