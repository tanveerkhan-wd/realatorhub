@extends('agent.layout.app_with_login')
@section('title','Dashboard')
@section('content')

<!-- Start Content Body -->

<div class="container-fluid">
    <div class="row text-center equal_height">
        <div class="col-md-3 col-6 equal_height_container">
            <div class="dash_tile">
                <a href="{{route('agent.property.list')}}">
                    <div class="dash_tile_top">
                        <img src="{{ url('public/assets/images/ic_properties.png') }}" class="tile_img">
                        <img src="{{ url('public/assets/images/ic_properties_color.png') }}" class="tile_hover_img">    
                    </div>
                    <div class="dash_tile_bottom">
                        <p>My Properties</p>
                        <h3>{{$total_property}}</h3>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-3 col-6 equal_height_container">
            <div class="dash_tile">
                <a href="{{route('agent.leads.list')}}">
                    <div class="dash_tile_top">
                        <img src="{{ url('public/assets/images/ic_leads1.png') }}" class="tile_img">
                        <img src="{{ url('public/assets/images/ic_leads_color.png') }}" class="tile_hover_img"> 
                    </div>
                    <div class="dash_tile_bottom">
                        <p>My Leads</p>
                        <h3>{{$total_lead}}</h3>
                    </div>
                </a>
            </div>
        </div>
        <!--            <div class="col-md-3 col-6 equal_height_container">
                        <div class="dash_tile">
                            <a href="#">
                                <div class="dash_tile_top">
                                    <img src="{{ url('public/assets/images/ic_agent.png') }}" class="tile_img">
                                    <img src="{{ url('public/assets/images/ic_agent_color.png') }}" class="tile_hover_img"> 
                                </div>
                                <div class="dash_tile_bottom">
                                    <p>My Agents</p>
                                    <h3>30</h3>
                                </div>
                            </a>
                        </div>
                    </div>-->
        <!--            <div class="col-md-3 col-6 equal_height_container">
                        <div class="dash_tile">
                            <a href="#">
                                <div class="dash_tile_top">
                                    <img src="{{ url('public/assets/images/ic_customer.png') }}" class="tile_img">
                                    <img src="{{ url('public/assets/images/ic_customer_color.png') }}" class="tile_hover_img">  
                                </div>
                                <div class="dash_tile_bottom">
                                    <p>My Customers</p>
                                    <h3>40</h3>
                                </div>
                            </a>
                        </div>
                    </div>-->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="chart_section">
                <form id="search-form">
                    <div class="row">
                        <div class="col-md-9 col-sm-6"></div>
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group "> 
                                <!-- <label>Purpose</label> -->
                                <select name="property_purpose" class="form-control select2 dropdown_control" required="" id='leads_dropdown'>
                                    @foreach($year_list as $key=>$value)
                                    <option value="{{$value->year}}" @if($value->year==date('Y')) selected="selected" @endif>{{$value->year}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="leads"></div>
            </div>
        </div>
    </div>
</div>

<!-- End Content Body -->
@endsection
@push('custom-styles')
<!-- Include this Page CSS -->
@endpush
@push('custom-scripts')
<!-- Include this Page JS -->
<script src="{{ url('public/js/common/highchart/highcharts.js') }}"></script>
<script src="{{ url('public/js/common/highchart/exporting.js') }}"></script>
<script src="{{ url('public/js/common/highchart/export-data.js') }}"></script>
<script src="{{ url('public/js/common/highchart/accessibility.js') }}"></script>
<script>
var data = <?php echo $lead_year_count ?>;

var chart = Highcharts.chart('leads', {
    chart: {
        type: 'column'
    },
    title: {
        text: ''
    },
//        subtitle: {
//            text: 'Source: WorldClimate.com'
//        },
    xAxis: {
        categories: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'Jun',
            'Jul',
            'Aug',
            'Sep',
            'Oct',
            'Nov',
            'Dec'
        ],
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Leads'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{categories.name} </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
            name: 'Leads',
            data: data,
            color: '#4e43fc'
        }
    ],
    credits: {
        enabled: false
    },
    exporting: {enabled: false}
});


$(document).on('change', '#leads_dropdown', function() {
    $('.loader-outer-container').css('display', 'table');
    var year = $(this).val();
    $.ajax({
        type: "POST",
        dataType: 'json',
        url: '{{route("agent.lead.chart.data")}}',
        data: {year: year},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            chart.series[0].setData(data);
            $('.loader-outer-container').css('display', 'none');
        }
    });
});
</script>
@endpush