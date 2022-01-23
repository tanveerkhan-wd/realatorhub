@extends('agency.layout.app_with_login')
@section('title','Send Email')
@section('content')
<style>
    .dataTables_scrollBody{
        height:100% !important;
    }
</style>
@if ($errors->any())                       
<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if (\Session::has('success'))
<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <ul>
        <li>{!! \Session::get('success') !!}</li>
    </ul>
</div>
@endif
<div class="container-fluid">
    <div class="path_link">
        <a href="#" class="current_page">Send Emails ></a>
    </div>

    <form method="post" action="" class="agent_form">
        <div class="form-row align-items-center mb-4">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Your Email Preference</label>
                    <select class="form-control dropdown_control country_code" name="email_type" id="email_type">
                        <!--<option>Select your email preference</option>-->
                        <option value="1" selected>Send General Email</option>
                        <option value="2">Send an email for favourite Property</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <div id="general_email_div"> 
            <div class="table_divide">
                <div class="table_header_bar">
                    <div class="form-row align-items-center">
                        <div class="col-sm-12 col-md-5 col-lg-5"><h5 class="table_title">Select Customers</h5></div>
                        <div class="col-sm-8 col-md-4 col-lg-4"></div>
                        <div class="col-sm-4 col-md-3 col-lg-3">
                            <div class="form-group">
                                <input class="form-control search_control" placeholder="Search" type="text" name="title" id="customer_search">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover center_table" id="datatableData">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Created Date</th>
                                <th>Customer Name</th>
                                <th>Customer Email & Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table_divide"> 
                <div class="table_header_bar">
                    <div class="form-row align-items-center">
                        <div class="col-sm-12 col-md-3 col-lg-4"><h5 class="table_title">Select Properties</h5></div>
                        <div class="col-sm-4 col-md-3 col-lg-3">
                            <div class="form-group "> 
                                <select name="property_type" class="form-control select2 dropdown_control property_ip" required="">
                                    <option value="">Property Type</option>
                                    <option value="1">Single Home</option>
                                    <option value="2">Multifamily</option>
                                    <option value="3">Commercial</option>
                                    <option value="4">Industrial</option>
                                    <option value="5">Lot</option>
                                </select>
                                <div id="status_validate"></div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 col-lg-2">
                            <div class="form-group "> 
                                <select name="property_purpose" class="form-control select2 dropdown_control property_ip" required="">
                                    <option value="">Purpose</option>
                                    <option value="1">Buy</option>
                                    <option value="2">Rent</option>
                                </select>
                                <div id="status_validate"></div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 col-lg-3">
                            <div class="form-group "> 
                                <input type="text" placeholder="Search" class="form-control icon_control search_control property_ip" name="property_all_search">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover center_table" id="property-tabel">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Created Date</th>
                                <th>Property ID</th>
                                <th>Address</th>
                                <th>Purpose</th>
                                <th>Type</th>
                                <th>Agent Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="">
                <div class="form-group">
                    <h5 class="table_title">Add Note</h5>
                    <textarea placeholder="Type Here" class="form-control general_note" id="general_note"></textarea>
                </div>
            </div>
            <div class="form-group text-center two_btns">
                <input type="button" value="Send" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="general_send_btn">
                <a href="{{route('agency.home')}}" class="auth_btn theme-btn grey_btn btn-text btn-size save_home_banner_data" id="prv_seo_btn">Cancel</a>
            </div>
        </div>
        <div id="fav_email_div" class="col-md-12"> 
            <div class="table_divide">
                <div class="table_header_bar">
                    <div class="form-row align-items-center">
                        <div class="col-sm-12 col-md-5 col-lg-5"><h5 class="table_title">Select Customers</h5></div>
                        <div class="col-sm-8 col-md-4 col-lg-4"></div>
                        <div class="col-sm-4 col-md-3 col-lg-3">
                            <div class="form-group">
                                <input class="form-control search_control" placeholder="Search" type="text" name="title" id="fav_customer_search">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover center_table" id="fav_customer_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Created Date</th>
                                <th>Customer Name</th>
                                <th>Customer Email & Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table_divide fav_propert_table_div">
                <div class="table_header_bar">
                    <div class="form-row align-items-center">
                        <div class="col-sm-12 col-md-3 col-lg-4"><h5 class="table_title">Select Properties</h5></div>
                        <div class="col-sm-4 col-md-3 col-lg-3">
                            <div class="form-group "> 
                                <select name="fav_property_type" class="form-control select2 dropdown_control fav_property_ip" required="">
                                    <option value="">Property Type</option>
                                    <option value="1">Single Home</option>
                                    <option value="2">Multifamily</option>
                                    <option value="3">Commercial</option>
                                    <option value="4">Industrial</option>
                                    <option value="5">Lot</option>
                                </select>
                                <div id="status_validate"></div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 col-lg-2">
                            <div class="form-group "> 
                                <select name="fav_property_purpose" class="form-control select2 dropdown_control fav_property_ip" required="">
                                    <option value="">Purpose</option>
                                    <option value="1">Buy</option>
                                    <option value="2">Rent</option>
                                </select>
                                <div id="status_validate"></div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-md-3 col-lg-3">
                            <div class="form-group "> 
                                <input type="text" placeholder="Search" class="form-control icon_control search_control fav_property_ip" name="fav_property_all_search">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover center_table" id="fav_property-tabel">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Created Date</th>
                                <th>Property ID</th>
                                <th>Address</th>
                                <th>Purpose</th>
                                <th>Type</th>
                                <th>Agent Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="">
                <div class="form-group">
                    <h5 class="table_title">Add Note</h5>
                    <textarea placeholder="Type Here" class="form-control general_note" id="fav_general_note"></textarea>
                </div>
            </div>
            <div class="form-group text-center two_btns">
                <input type="button" value="Send" class="auth_btn theme-btn btn-color btn-text btn-size save_home_banner_data" id="fav_send_btn">
                <a href="{{route('agency.home')}}" class="auth_btn theme-btn grey_btn btn-text btn-size save_home_banner_data" id="prv_seo_btn">Cancel</a>
            </div>
        </div>
</div>
</form>

@endsection
@push('custom-scripts')
<script type="text/javascript">
    var agent_table = $('#datatableData').DataTable({
        processing: true,
        serverSide: true, searching: false,
        "bLengthChange": false,
        "bInfo": false,
        "dom": '<"top"i>rt<"bottom"flp><"clear">',
        scrollX: true,
        "bSort": false,
        "initComplete": function(settings, json) {
            $(".checkall").closest("th").removeClass("sorting_asc");
        },
        ajax: {
            url: '{{route("agency.send.mail.customer.list.ajax")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.agent_all = $('#customer_search').val();
            }
        },
        columns: [{data: 'action', name: 'action'}, {data: 'created_at', name: 'created_at'}, {data: 'first_name', name: 'first_name'}, {data: 'email', name: 'email'},
        ],
        "rowCallback": function(row, data) {
            if ($.inArray(data.id, checked_chkbx) !== -1) {
                $('td:eq(0)', row).html('<div class="form-group form-check check_box"><input type="checkbox" class="form-check-input check_val general_customer" name="customer_chk" id="terms_agree" value="" data-val="' + data.id + '" checked><label class="custom_checkbox"></label></div>');
            }
        }
    });
    $('#customer_search').on('keyup', function(e) {
        agent_table.draw();
        e.preventDefault();
    });
    $('#customer_search').on('change', function(e) {
        agent_table.draw();
        e.preventDefault();
    });
    var table = $('#property-tabel').DataTable({
        processing: true,
        serverSide: true, searching: false,
        "bLengthChange": false,
        "bInfo": false,
        "dom": '<"top"i>rt<"bottom"flp><"clear">',
        scrollX: true,
        "bSort": false,
        "initComplete": function(settings, json) {
            $(".checkall").closest("th").removeClass("sorting_asc");
        },
        ajax: {
            url: '{{route("agency.send.mail.property.list.ajax")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                //d.agent = $('select[name=agent]').val();
                d.property_type = $('select[name=property_type]').val();
                d.property_purpose = $('select[name=property_purpose]').val();
                // d.property_status = $('select[name=property_status]').val();
                d.property_all_search = $('input[name=property_all_search]').val();
            }
        },
        columns: [{data: 'action', name: 'action'}, {data: 'created_at', name: 'created_at'}, {data: 'id', name: 'id'}, {data: 'address', name: 'address'}, {data: 'purpose', name: 'purpose'}, {data: 'type', name: 'type'}, {data: 'first_name', name: 'first_name'},
        ],
        "rowCallback": function(row, data) {
            var test = data.id;
            var test1 = parseInt($(test).text());
            if ($.inArray(test1, general_p_chk) !== -1) {
                $('td:eq(0)', row).html('<div class="form-group form-check check_box"><input type="checkbox" class="form-check-input check_val general_property" name="property_chk" id="terms_agree" data-val="' + test1 + '" checked><label class="custom_checkbox"></label></div>');
            }
        }
    });
    $('.property_ip').on('keyup', function(e) {
        table.draw();
        e.preventDefault();
    });
    $('.property_ip').on('change', function(e) {
        table.draw();
        e.preventDefault();
    });
    var checked_chkbx = [];
    $(document).on('click', '#datatableData td .general_customer', function() {
        var chked = $(this).data('val');
        if ($(this).prop("checked") == true) {
            checked_chkbx.push(chked);
            console.log(checked_chkbx);
        } else {
            var index = checked_chkbx.indexOf(chked);
            checked_chkbx.splice(index, 1);
            console.log(checked_chkbx);
        }
    });
    var general_p_chk = [];
    $(document).on('click', '#property-tabel td .general_property', function() {
        var chked = $(this).data('val');
        if ($(this).prop("checked") == true) {
            general_p_chk.push(chked);
            console.log(general_p_chk);
        } else {
            var index = general_p_chk.indexOf(chked);
            general_p_chk.splice(index, 1);
            console.log(general_p_chk);
        }
    });
    $(document).ready(function() {
        //$('#general_email_div').hide();
        $('#fav_email_div').hide();
        $('.fav_propert_table_div').hide();

        $('#general_send_btn').click(function() {
            //if ($(":checkbox[name='customer_chk']").is(":checked"))
            if (Array.isArray(checked_chkbx) && checked_chkbx.length)
            {
//                    var customr_arr = [];
//                    $('input.general_customer:checkbox:checked').each(function() {
//                        var checked_customer = $(this).data('val');
//                        customr_arr.push(checked_customer);
//                    });
            }
            else
            {
                swal('', 'Please Select Customer', 'error');
                return false;
            }
            //if ($(":checkbox[name='property_chk']").is(":checked")) {
            if (Array.isArray(general_p_chk) && general_p_chk.length) {
//                    var property_arr = [];
//                    $('input.general_property:checkbox:checked').each(function() {
//                        var checked_property = $(this).data('val');
//                        property_arr.push(checked_property);
//                    });
            } else {
                swal('', 'Please Select Property', 'error');
                return false;
            }
            var note = $('#general_note').val();
            if (note == '') {
                swal('', 'Please Add Note', 'error');
                return false;
            }
            $('.loader-outer-container').css('display', 'table');
            $.ajax({
                type: "POST",
                url: '{{route("agency.send.general.email")}}',
                data: {customer: checked_chkbx, property: general_p_chk, note: note},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('.loader-outer-container').css('display', 'none');
                    if (data.code == '200') {
                        toastr.success(data.message);
                        location.reload();
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        });
    });
    $(document).on('change', '#email_type', function() {
        var email_type = $(this).val();
        $('input[type="checkbox"]').prop('checked', false);
        general_p_chk=[];
        checked_chkbx=[];
        fav_p_chk=[];
        fav_cus='';
        $('.general_note').val('');
        if (email_type == 1) {
            $('#general_email_div').show();
            $('#fav_email_div').hide();
            $('.fav_propert_table_div').hide();
        } else {
            $('#fav_email_div').show();
            $('#general_email_div').hide();
        }
    });

    $('#fav_send_btn').click(function() {
        // if ($(":checkbox[name='customer_chk']").is(":checked"))
        if (fav_cus != '')
        {
              var customr_arr = [];
                    customr_arr.push(fav_cus);
//                $('input.general_customer:checkbox:checked').each(function() {
//                    var checked_customer = $(this).data('val');
//                });
        }
        else
        {
            swal('', 'Please Select Customer', 'error');
            return false;
        }
        //if ($(":checkbox[name='property_chk']").is(":checked")) {
        if (Array.isArray(fav_p_chk) && fav_p_chk.length) {
//                var property_arr = [];
//                $('input.general_property:checkbox:checked').each(function() {
//                    var checked_property = $(this).data('val');
//                    property_arr.push(checked_property);
//                });
        } else {
            swal('', 'Please Select Property', 'error');
            return false;
        }
        var note = $('#fav_general_note').val();
        if (note == '') {
            swal('', 'Please Add Note', 'error');
            return false;
        }
        $('.loader-outer-container').css('display', 'table');
        $.ajax({
            type: "POST",
            url: '{{route("agency.send.general.email")}}',
            data: {customer: customr_arr, property: fav_p_chk, note: note, fav: 'fav'},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                $('.loader-outer-container').css('display', 'none');
                if (data.code == '200') {
                    toastr.success(data.message);
                    location.reload();
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });
    var favtable = $('#fav_property-tabel').DataTable({
        processing: true,
        serverSide: true, searching: false,
        "bLengthChange": false,
        "bInfo": false,
        "dom": '<"top"i>rt<"bottom"flp><"clear">',
        scrollX: true,
        "bSort": false,
        "initComplete": function(settings, json) {
            $(".checkall").closest("th").removeClass("sorting_asc");
        },
        ajax: {
            url: '{{route("agency.send.mail.property.list.ajax")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                //d.agent = $('select[name=agent]').val();
                d.property_type = $('select[name=fav_property_type]').val();
                d.property_purpose = $('select[name=fav_property_purpose]').val();
                // d.property_status = $('select[name=property_status]').val();
                d.property_all_search = $('input[name=fav_property_all_search]').val();
                d.customer = $('#fav_customer_table td input[type="checkbox"]:checked').data('val');
            }
        },
        columns: [{data: 'action', name: 'action'}, {data: 'created_at', name: 'created_at'}, {data: 'id', name: 'id'}, {data: 'address', name: 'address'}, {data: 'purpose', name: 'purpose'}, {data: 'type', name: 'type'}, {data: 'first_name', name: 'first_name'},
        ],
        "rowCallback": function(row, data) {
            var test = data.id;
            var test1 = parseInt($(test).text());
            if ($.inArray(test1, fav_p_chk) !== -1) {
                console.log('in_array');
                $('td:eq(0)', row).html('<div class="form-group form-check check_box"><input type="checkbox" class="form-check-input check_val general_property" name="property_chk" id="terms_agree" data-val="' + test1 + '" checked><label class="custom_checkbox"></label></div>');
            }
        }
    });
    var fav_p_chk = [];
    $(document).on('click', '#fav_property-tabel td .general_property', function() {
        var chked = $(this).data('val');
        if ($(this).prop("checked") == true) {
            fav_p_chk.push(chked);
            console.log(fav_p_chk);
        } else {
            var index = fav_p_chk.indexOf(chked);
            fav_p_chk.splice(index, 1);
            console.log(fav_p_chk);
        }
    });
    $('.fav_property_ip').on('keyup', function(e) {
        favtable.draw();
        e.preventDefault();
    });
    $('.fav_property_ip').on('change', function(e) {
        favtable.draw();
        e.preventDefault();
    });
    $(document).on('click', '#fav_customer_table td input[type="checkbox"]', function() {
        $('#fav_customer_table td  input[type="checkbox"]').not(this).prop('checked', false);
        if ($(":checkbox[name='customer_chk']").is(":checked"))
        {
            $('.fav_propert_table_div').show();
        } else {
            $('.fav_propert_table_div').hide();
        }
        favtable.draw();
        //e.preventDefault();
    });
    var fav_customer_tabel = $('#fav_customer_table').DataTable({
        processing: true,
        serverSide: true, searching: false,
        "bLengthChange": false,
        "bInfo": false,
        "dom": '<"top"i>rt<"bottom"flp><"clear">',
        scrollX: true,
        "bSort": false,
        "initComplete": function(settings, json) {
            $(".checkall").closest("th").removeClass("sorting_asc");
        },
        ajax: {
            url: '{{route("agency.send.mail.customer.list.ajax")}}',
            type: 'post',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: function(d) {
                d.agent_all = $('#fav_customer_search').val();
                d.fav = 'fav';
            }
        },
        columns: [{data: 'action', name: 'action'}, {data: 'created_at', name: 'created_at'}, {data: 'first_name', name: 'first_name'}, {data: 'email', name: 'email'},
        ],
        "rowCallback": function(row, data) {
            console.log(fav_cus);

            if (data.id == fav_cus) {
                console.log('in');
                $('td:eq(0)', row).html('<div class="form-group form-check check_box"><input type="checkbox" class="form-check-input check_val general_customer" name="customer_chk" id="terms_agree" value="" data-val="' + data.id + '" checked><label class="custom_checkbox"></label></div>');
            }
        }
    });
    var fav_cus = '';
    $(document).on('click', '#fav_customer_table td .general_customer', function() {
        var chked = $(this).data('val');
        if ($(this).prop("checked") == true) {
            fav_cus = chked;
            console.log(fav_cus);
        } else {
            fav_cus = '';
        }
    });
    $('#fav_customer_search').on('keyup', function(e) {
        fav_customer_tabel.draw();
        e.preventDefault();
    });
    $('#fav_customer_search').on('change', function(e) {
        fav_customer_tabel.draw();
        e.preventDefault();
    });

</script>
@endpush