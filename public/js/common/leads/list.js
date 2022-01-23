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

var prefixModule = base_url + '/common/leads/',
    activeInactiveAjaxSource = prefixModule + 'activeInactive',
    verifiedUnverifiedAjaxSource = prefixModule + 'verifiedUnverified',
    viewAgent = prefixModule + 'view/',
    addEditSource = prefixModule,
    deleteAjaxSource = prefixModule + 'delete',
    ajaxDataTable = prefixModule+'listLeadsAjax';

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
            d.agent_id = $('input[name=agent_id]').val();
            d.title = $('input[name=title]').val();
            d.status = $('select[name=status]').val();
            d.address = $('select[name=address]').val();
        }
    },
    columns: [
        {data: 'id', name: 'id',orderable:false},
        {data: 'property_id', name: 'property_id'},
        {data: 'address', name: 'address'},
        {data: 'customer_name', name: 'customer_name'},
        {data: 'email', name: 'email'},
        {data: 'created_at', name: 'created_at'},
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
                var property_address=row.address;
                property_address=urlString(property_address);
                console.log(property_address);

                var status =row.agent_unique_id;
                var html = '<a href="'+base_url+'/'+row.slug+'/property-detail/'+property_address+'-'+row.property_id+'" target="_blank">#'+row.property_id+'</a>'; 
                return html;
            },
            "aTargets": [1]
        }, 
        {   
            "mRender": function (data, type, row) {
                var status =row.agent_unique_id;
                var html = row.email+'<br>'+row.phone; 
                return html;
            },
            "aTargets": [4]
        }, 
        {   
            "mRender": function (data, type, row) {
                var status =row.status;
                if(status == '1'){
                    var html = '<ul class="navbar-nav droup_middle"><li class="dropdown spam_status"><button class="btn dropdown-toggle" type="button" id="statusDropdown'+row.id+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Spam</button><div class="dropdown-menu sett_drop" aria-labelledby="statusDropdown"><a href="javascript:void(0)" class="changeStatus" data-id="'+row.id+'" data-status="2"><p>Done</p></a><a href="javascript:void(0)" class="changeStatus" data-id="'+row.id+'" data-status="0"><p>Pending</p></a></div></li></ul>';    
                }else if(status=='2'){
                    var html = '<ul class="navbar-nav droup_middle"><li class="dropdown done_status"><button class="btn dropdown-toggle" type="button" id="statusDropdown'+row.id+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Done</button><div class="dropdown-menu sett_drop" aria-labelledby="statusDropdown"><a href="javascript:void(0)" class="changeStatus" data-id="'+row.id+'" data-status="1"><p>Spam</p></a><a href="javascript:void(0)" class="changeStatus" data-id="'+row.id+'" data-status="0"><p>Pending</p></a></div></li></ul>';
                }else{
                    var html = '<ul class="navbar-nav droup_middle"><li class="dropdown pending_status"><button class="btn dropdown-toggle" type="button" id="statusDropdown'+row.id+'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pending</button><div class="dropdown-menu sett_drop" aria-labelledby="statusDropdown"><a href="javascript:void(0)" class="changeStatus" data-id="'+row.id+'" data-status="1"><p>Spam</p></a><a href="javascript:void(0)" class="changeStatus" data-id="'+row.id+'" data-status="2"><p>Done</p></a></div></li></ul>';
                }
                return html;
            },
            "aTargets": [6]
        }, 
        {   
            "mRender": function (data, type, row) {
                var status =row.user_status;
                
                    var html = '<a href="javascript:void(0)" class="theme-btn small_btn btn-color btn-text btn-size mb-2 view-note-message" data-type="1" data-id="'+row.id+'" data-message="'+row.message+'">Message</a>';    
                
                    html += '<a href="javascript:void(0)" class="theme-btn small_btn btn-color btn-text btn-size mb-2 view-note-message" data-type="2" data-id="'+row.id+'" data-message="'+row.note+'">Note</a>';
                    html +='<a href="javascript:void(0)" class="deleteData action_icon" data-url="' + deleteAjaxSource +'/'+row.id+'" title="Delete" "><img src="'+base_url+'/public/assets/images/ic_delete.png"></a>';
                return html;
            },
            "aTargets": [7]
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
    var id = $(this).data('id');
    var status = $(this).data('status');
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
                    url: base_url+'/common/leads/changeStatus',
                    data:{id:id,status:status},
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
    swal({
            text: 'Are you sure you want to delete this Lead?',
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
                    url: deleteAjaxSource,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.loader-outer-container').css('display','none');
                        if (data.code == '200') {
                            toastr.success(data.message);
                            window.setTimeout(function(){window.location.reload();
                                },2000);
                        }else{
                            toastr.error(data.message);
                            window.setTimeout(function(){window.location.reload();
                                },2000);
                        }
                        dTable.fnDraw(true);
                    }
                });
            }
        });
});

function urlString(property_address){
    var url=property_address.split(", ").join("-");
                url=url.split(',').join('-');
                url=url.split(' ').join('-');
                url=url.split('/').join('-');
                url=url.split("'").join('-');
                url=url.split('--').join('-');
                return url;
}
