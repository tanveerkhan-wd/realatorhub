/**
* List Services 
*
* This file is used for Service Listing Datatable , Active and Delete.
* 
* @package    Laravel
* @subpackage JS
* @since      1.0
*/
// For Datatable

var prefixModule = base_url + '/agency/agent/',
    activeInactiveAjaxSource = prefixModule + 'activeInactive',
    verifiedUnverifiedAjaxSource = prefixModule + 'verifiedUnverified',
    viewAgent = prefixModule + 'view/',
    addEditSource = prefixModule,
    deleteAjaxSource = prefixModule + 'delete',
    ajaxDataTable = prefixModule+'listAgentAjax';

dTable = $('#datatableData').dataTable({
    processing: true,
    serverSide: true,
    searching: false,
    "bLengthChange": false,
    "bInfo" : false,
    "dom": '<"top"i>rt<"bottom"flp><"clear">',
    scrollX:true,
    "bSort" : false,
    oLanguage: {
       
    },
    "initComplete": function (settings, json) {
        $(".checkall").closest("th").removeClass("sorting_asc");
    },
    ajax: {
        url: ajaxDataTable,
        type: 'post',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: function (d) {
            d.name = $('input[name=title]').val();
            d.status = $('select[name=status]').val();
            d.city = $('select[name=city]').val();
        }
    },
    columns: [
        {data: 'id', name: 'id',orderable:false},
        {data: 'agent_unique_id', name: 'agent_unique_id'},
        {data: 'first_name', name: 'first_name'},
        {data: 'last_name', name: 'last_name'},
        {data: 'email', name: 'email'},
        {data: 'phone', name: 'phone'},
        {data: 'created_at', name: 'created_at'},
        {data: 'user_status', name: 'user_status'},
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
                var status =row.agent_unique_id;
                var html = '<a id="agent_unique_id" href="javascript:void(0)">'+row.agent_unique_id+'</a>'; 
                return html;
            },
            "aTargets": [1]
        },   
        {   
            "mRender": function (data, type, row) {
                var status =row.user_status;
                if(status == '1'){
                    var html = '<a title="Mark as Inactive" href="javascript:void(0)" class="changeStatus greenlink" style="color:green" data-url="'+activeInactiveAjaxSource+'/'+row.id+'">Active</a>';    
                }else{
                    var html = '<a href="javascript:void(0)" class="changeStatus inactiveClass redlink" title="Mark as Active" data-url="'+activeInactiveAjaxSource+'/'+row.id+'">In Active</a>';
                }
                return html;
            },
            "aTargets": [7]
        },
        {
            "mRender": function (data, type, row) {

                var status = "";
                if ($.trim(row.status) == "0") {
                    status = '0';
                } else {
                    status = '1';
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
                 html += '<a href="'+viewAgent+row.id+'" class="action_icon"><img src="'+base_url+'/public/assets/images/ic_view.png"></a>';
                if (addEditSource) {
                    html += '<a href="' + addEditSource + 'edit/' + row.id+'" title="Edit" class="table-edit action_icon"><img src="'+base_url+'/public/assets/images/ic_edit.png"></a>&nbsp;&nbsp;';
                }
                html += '<a href="javascript:void(0)" class="deleteData action_icon" data-url="' + deleteAjaxSource +'/'+row.id+'" title="Delete" " data-id="'+row.id+'"><img src="'+base_url+'/public/assets/images/ic_delete.png"></a>';
                /*if (deleteAjaxSource) {
                    html += '<a href="javascript:void(0)" title="Delete" class="table-delete text-danger" onclick="deleteAll(\'' + single + '\',' + row.id + ')"><i class="fa fa-trash-o"></i></a>&nbsp;&nbsp;';
                }*/
                html += '</tr>';
                html += '</table>';
                return html;
            },
            "aTargets": [8]
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
            text: 'Are you sure you want to change the status?',
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) { 
                $('.loader-outer-container').css('display','table');               
                $.ajax({
                    type: "POST",
                    url: changeStatus,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.loader-outer-container').css('display','none');
                        if (data.code == '200') {
                            toastr.success(data.message);
                        }else{
                            toastr.error(data.message);
                        }
                        dTable.fnDraw(true);
                    }
                });
            }
        });
    });
$(document).on('click','.changeVerifiedStatus',function(){
    var changeStatus = $(this).data('url');
    swal({
            text: "Are you sure you want to change email status?",
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
        if (isConfirm.value == true) { 
            $('.loader-outer-container').css('display','table');               
            $.ajax({
                type: "POST",
                url: changeStatus,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('.loader-outer-container').css('display','none');
                    if (data == 1) {
                        swal('Success',Messages.record_updated,'success');
                    }
                    dTable.fnDraw(true);
                }
            });
        }
    });
});
//Function used for Delete Image
$(document).on('click','.deleteData',function(){
    var deleteAjaxSource = $(this).data('url');
    var id=$(this).data('id');
    $("input[name=add-agent-url]").val(deleteAjaxSource);
    swal({
            text: 'Are you sure you want to delete this agent?',
            type: 'info',
            showCancelButton: true,
            confirmButtonClass: 'blue_button alert_btn mr-40',
            cancelButtonClass: 'blue_border_button alert_btn',
            confirmButtonText: 'Yes'
        }).then(function (isConfirm) {
            if (isConfirm.value == true) {  
                $('#agent-model-popup').modal('show');
                
                $('.loader-outer-container').css('display','table');              
                $.ajax({
                    type: "POST",
                    url: base_url+'/common/getAgencyAgent',
                    data:{user_id:id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('#agent-model-popup').modal('show');
                        $('.loader-outer-container').css('display','none');
                        $('#model-agent-list').html(data);
                    }
                });
            }
        });
});



