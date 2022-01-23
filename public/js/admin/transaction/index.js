$(document).ready(function () {
// For Datatable
    $('input[name="datefilter"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
        $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
        dTable.fnDraw(true);
    });

    $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#start_date').val('');
        $('#end_date').val('');
        dTable.fnDraw(true);
    });


    var prefixModule = base_url + '/admin/transactions/',
        ajaxDataTable = prefixModule+'listTransactionAjax';
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
        oLanguage: {
            //sProcessing: showMessage()
        },
        "lengthMenu": [[10, 25, 50,100 ,-1], [10, 25, 50,100,"All"]],
        "initComplete": function (settings, json) {
            $(".checkall").closest("th").removeClass("sorting_asc");
        },
        ajax: {
            url: ajaxDataTable,
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function (d) {
                d.start_date = $('input[name=start_date]').val();
                d.end_date = $('input[name=end_date]').val();
                d.search_agency = $('select[name=search_agency]').val();
                d.search_plan = $('select[name=search_plan]').val();
            }
        },
        columns: [
            {data: 'id', name: 'id',orderable:false},
            {data: 'transaction_id', name: 'transaction_id'},
            {data: 'date', name: 'date'},
            {data: 'agency_name', name: 'agency_name'},
            {data: 'plan_name', name: 'plan_name'},
            {data: 'payment_type', name: 'payment_type'},
            {data: 'base_price', name: 'base_price'},
            {data: 'additional_agent', name: 'additional_agent'},
            {data: 'additional_agent_price', name: 'additional_agent_price'},
            {data: 'total', name: 'total'}
        ],
        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
            $("td:first", nRow).html(iDisplayIndex +1);
            return nRow;
        },
        aoColumnDefs: [
            {
                "mRender": function (data, type, row) {
                    var html =row.subscription_id;

                    return html;
                },
                "aTargets": [1]
            },
            {
                "mRender": function (data, type, row) {
                    // var dateTime =changeTimezone(row.created_at);
                    // dateTime = moment(dateTime).format("YYYY-MM-DD HH:mm:ss");
                    var html =changeTimezone(row.created_at);

                    return html;
                },
                "aTargets": [2]
            },
            {
                "mRender": function (data, type, row) {
                    if(row.agency_name) {
                        var html = row.id +' ' + row.agency_name;
                    }
                    else{
                        var html = '';
                    }

                    return html;
                },
                "aTargets": [3]
            },
            {
                "mRender": function (data, type, row) {

                    var html = row.plan.plan_name;

                    return html;
                },
                "aTargets": [4]
            },
            {
                "mRender": function (data, type, row) {
                    if(row.payment_type==0){
                        var html = 'Free Trail';
                    }else{
                        var html ='Paid';
                    }
                    return html;
                },
                "aTargets": [5]
            },
            {
                "mRender": function (data, type, row) {

                    var html = row.plan.monthly_price;

                    return html;
                },
                "aTargets": [6]
            },
            {
                "mRender": function (data, type, row) {
                    var html = row.additional_agent;
                    return html;
                },
                "aTargets": [7]
            },
            {
                "mRender": function (data, type, row) {
                    var html = row.additional_agent * row.plan.additional_agent_per_rate;
                    return html;
                },
                "aTargets": [8]
            },
            {
                "mRender": function (data, type, row) {
                    if(row.payment_type==0){
                        var html = 0;
                    }else{
                        var html = row.total_amount;
                    }
                    
                    return html;
                },
                "aTargets": [9]
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

})


function changeTimezone(time) {
    // var format = 'yyyy-mm-dd HH:mm:ss';
    /*var format = data_format+' '+time_format;*/
   // var format = 'mm DD, Y g:i A';
    //console.log(format+' time:'+time);
    /*var format = 'DD-MM-YYYY HH:mm:ss';
    var abc = moment(time).tz(timezone).format(format);*/
    return time;
}