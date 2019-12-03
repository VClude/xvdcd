@extends('ticketit::layouts.master')

@section('page', trans('ticketit::admin.index-title'))
@section('page_title', 'Dashboard')
@section('ticketit_extra_content')
    @if($tickets_count)
    <div class="container" id="pnewrint">
                <!--START REKAPITULASI MINGGUAN-->
                <div class="row">
                    <div class="col-md-4">

                        <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0"
                            style="height: 132px;">
                            <li>
                                <!-- START WIDGET -->
                                <div class="app-widget-tile app-widget-highlight">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="icon icon-lg">
                                                <span class="icon-inbox"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="line">
                                                <div class="title">Jumlah Keluhan</div>
                                            </div>
                                            <div class="intval text-left">{{ $week_tickets_count }}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@allWeekly') }}">Laporan masuk minggu ini</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET -->
                            </li>
                        </ul>

                    </div>

                    <div class="col-md-4">

                        <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0"
                            style="height: 132px;">
                            <li>
                                <!-- START WIDGET -->
                                <div class="app-widget-tile app-widget-highlight">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="icon icon-lg">
                                                <span class="icon-warning text-danger"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="line">
                                                <div class="title">Jumlah Keluhan Mingguan</div>
                                            </div>
                                            <div class="intval text-left">{{ $week_open_tickets_count }}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexWeekly') }}">Keluhan belum ditangani</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET -->
                            </li>
                        </ul>

                    </div>

                    <div class="col-md-4">

                        <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0"
                            style="height: 132px;">
                            <li>
                                <!-- START WIDGET -->
                                <div class="app-widget-tile app-widget-highlight">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="icon icon-lg">
                                                <span class="icon-checkmark-circle text-success"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="line">
                                                <div class="title">Jumlah Keluhan Mingguan</div>
                                            </div>
                                            <div class="intval text-left">{{ $week_closed_tickets_count }}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@completeWeekly') }}">Keluhan sudah ditangani</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET -->
                            </li>
                        </ul>

                    </div>

                </div>
                <!--EOF REKAPITULASI MINGGUAN-->
                <!--START REKAPITULASI ALL-TIME-->
                <div class="row">
                    <div class="col-md-6">

                        <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0"
                            style="height: 132px;">
                            <li>
                                <!-- START WIDGET -->
                                <div class="app-widget-tile app-widget-highlight">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="icon icon-lg">
                                                <span class="icon-inbox"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="line">
                                                <div class="title">Jumlah Keluhan</div>
                                            </div>
                                            <div class="intval text-left">{{ $tickets_count }}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexAll') }}">Laporan masuk selama ini</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET -->
                            </li>
                        </ul>

                    </div>

                    <div class="col-md-6">

                        <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0"
                            style="height: 132px;">
                            <li>
                                <!-- START WIDGET -->
                                <div class="app-widget-tile app-widget-highlight">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="icon icon-lg">
                                                <span class="icon-checkmark-circle text-success"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="line">
                                                <div class="title">Jumlah Keluhan</div>
                                            </div>
                                            <div class="intval text-left">{{ $closed_tickets_count }}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexComplete') }}">Keluhan Telah Ditangani Selama Ini</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET -->
                            </li>
                        </ul>

                    </div>
                </div>
                <!--EOF REKAPITULASI ALL-TIME-->
                <!--START TOP SKOR-->
                <div class="row">

                    <div class="col-md-6">

                        <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0"
                            style="height: 132px;">
                            <li>
                                <!-- START WIDGET -->
                                <div class="app-widget-tile app-widget-highlight">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="icon icon-lg">
                                                <span class="icon-layers text-success"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="line">
                                                <div class="title">Presentase Penyelesaian</div>
                                            </div>
                                            <div class="intval text-left">{{ $percentage_completion }}%</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\RecapController@index') }}">Laporan yang telah diselesaikan</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET -->
                            </li>
                        </ul>

                    </div>


                    <div class="col-md-6">

                        <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0"
                            style="height: 132px;">
                            <li>
                                <!-- START WIDGET -->
                                <div class="app-widget-tile app-widget-highlight">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="icon icon-lg">
                                                <span class="icon-sad text-warning"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="line">
                                                <div class="title">Kategori Keluhan</div>
                                            </div>
                                            <div class="intval text-left">{{ $dsaname }}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\RecapController@index') }}">Kategori keluhan yang sering
                                                    dilaporkan</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET -->
                            </li>
                        </ul>

                    </div>

                </div>
                <!--EOF TOP SKOR-->

    </div>
    @else
        <div class="card text-center">
            {{ trans('ticketit::admin.index-empty-records') }}
        </div>
    @endif
@stop
@section('notificationstuff')
                                            @php
                                            $i = 0;
                                            @endphp
                                            @foreach($notif as $nt)
                                            @php
                                            $i++;
                                            @endphp
                                                <div class="app-timeline-item"  id="dismiss-{{$i}}">
                                                    <div class="dot dot-primary"   onclick='$("#dismiss-{{$i}}").fadeOut(200);'></div>
                                                    <div class="content">
                                                        <div class="title margin-bottom-0"><a href="{!! route( $setting->grab('main_route').'.show', $nt->ticket_id) !!}">{{$nt->content}}</a></div>
                                                    </div>
                                                </div>
                                            @endforeach
@stop
@section('countnotif', $i)
@section('notifbutton')
<button class="btn btn-default btn-icon btn-informer" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="true"   onclick='$("#countnotif").fadeOut(0);'><span class="icon-alarm"></span><span
                                class="informer informer-danger informer-sm informer-square" id="countnotif">@yield('countnotif')</span></button>
@stop
@section('footer')
    @if($tickets_count)
    {{--@include('ticketit::shared.footer')--}}
    <script type="text/javascript">
        $(document).ready(function () {
            var elementHandler = {
                '#ignore-sidebar': function (element, renderer) {
                    return true;
                },
                '#ignore-main-heading': function (element, renderer) {
                    return true;
                },
                '#ignore-heading-1': function (element, renderer) {
                    return true;
                },
                '#ignore-heading-2': function (element, renderer) {
                    return true;
                },
            };

            $('#btn-go-export').on('click', function () {
                html2canvas(document.querySelector('#mingguan-chart-bar'), {useCORS: true}).then(canvas => {
                    $('#test').append(canvas)
                })

            });




            //Mocking API Call with static JS Object
            var varshit = {!! $month_json !!}
            var notshit = [];
 
for (let key in varshit.monthly_report){
    if(varshit.monthly_report.hasOwnProperty(key)){
        notshit.push(varshit.monthly_report[key])
    }
}
 
var chart = new Morris.Line({
        element: "mingguan-chart-bare",
        //data laporan per bulan
        data: notshit,
        xkey: 'timestamp',
        //please add F for Lainnya
        ykeys: ['FACILITIES_AND_INFRASTRUCTURE', 'BUILDINGS', 'HUMAN_RESOURCE', 'CLEANING_AND_GARDENING','INCIDENT_AND_RULE_VIOLATIONS', 'OTHERS'],
        labels: ['Sarpras', 'Gedung', 'HR', 'Kebersihan', 'Insiden/Tata tertib', 'Lainnya']
    });


            var top_cat_chart = new Chart(document.getElementById('top-kategori-chart'), {
                type: 'pie',
                data: {
                    datasets: [{data:[@foreach($categories_share as $cat_name => $cat_tickets){{ $cat_tickets }},@endforeach] , backgroundColor: {!! $categories_color !!} }],
                    labels: {!! $categories_name !!}
                }
            });

            console.log(top_cat_chart.data.datasets[0].backgroundColor);
        });
        function generate6randomcolor(){
            let color = [];

            for (let j = 0; j < 6; j++) {
                color[j] = getRandomColor();
            }

            return color;
        }

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
        <script type="text/javascript"
            src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>

    <script type="text/javascript">
        google.setOnLoadCallback(drawChart);

        // performance line chart
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ["{{ trans('ticketit::admin.index-month') }}", "{!! implode('", "', $monthly_performance['categories']) !!}"],
                @foreach($monthly_performance['interval'] as $month => $records)
                    ["{{ $month }}", {!! implode(',', $records) !!}],
                @endforeach
            ]);

            var options = {
                title: '{!! addslashes(trans('ticketit::admin.index-performance-chart')) !!}',
                curveType: 'function',
                legend: {position: 'right'},
                vAxis: {
                    viewWindowMode:'explicit',
                    format: '#',
                    viewWindow:{
                        min:0
                    }
                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);

            // Categories Pie Chart
            var cat_data = google.visualization.arrayToDataTable([
              ['{{ trans('ticketit::admin.index-category') }}', '{!! addslashes(trans('ticketit::admin.index-tickets')) !!}'],
              @foreach($categories_share as $cat_name => $cat_tickets)
                    ['{!! addslashes($cat_name) !!}', {{ $cat_tickets }}],
              @endforeach
            ]);

            var cat_options = {
              title: '{!! addslashes(trans('ticketit::admin.index-categories-chart')) !!}',
              legend: {position: 'bottom'}
            };

            var cat_chart = new google.visualization.PieChart(document.getElementById('catpiechart'));

            cat_chart.draw(cat_data, cat_options);

            // Agents Pie Chart
            var agent_data = google.visualization.arrayToDataTable([
              ['{{ trans('ticketit::admin.index-agent') }}', '{!! addslashes(trans('ticketit::admin.index-tickets')) !!}'],
              @foreach($agents_share as $agent_name => $agent_tickets)
                    ['{!! addslashes($agent_name) !!}', {{ $agent_tickets }}],
              @endforeach
            ]);

            var agent_options = {
              title: '{!! addslashes(trans('ticketit::admin.index-agents-chart')) !!}',
              legend: {position: 'bottom'}
            };

            var agent_chart = new google.visualization.PieChart(document.getElementById('agentspiechart'));

            agent_chart.draw(agent_data, agent_options);

        }
    </script>
    @endif
@append
