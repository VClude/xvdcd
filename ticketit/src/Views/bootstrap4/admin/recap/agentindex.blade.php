@extends('ticketit::layouts.master')

@section('page', trans('ticketit::admin.category-index-title'))
@section('page_title', 'Rekapitulasi Surveyor')
@section('ticketit_header')
{!! link_to_route(
    $setting->grab('admin_route').'.category.create',
    trans('ticketit::admin.btn-create-new-category'), null,
    ['class' => 'btn btn-primary'])
!!}
@stop

@section('ticketit_content_parent_class', 'p-0')

@section('ticketit_content')

                <!-- START PAGE CONTAINER -->
                <div class="container" id="pnewrint">
                <div class="app-content-tabs">

                <div class="app-content-tabs">
                <ul id="tabs">
                @php
                $i = 1;
                @endphp
                    @foreach($surveyor as $svyr)
                    @php
                    switch($svyr->category_id){
                    case 1:
                        $icon = 'event_seat';
                        break;
                        case 2:
                        $icon = 'apartment';
                        break;
                        case 3:
                        $icon = 'school';
                        break;
                        case 4:
                        $icon = 'local_florist';
                        break;
                        case 5:
                        $icon = 'warning';
                        break;
                        default:
                        $icon = 'category';
                    }
                    @endphp
                    @if($i == 1)
                        <li>
                        <a class="kelikable" data-id="{{$svyr->category_id}}" href="#FACILITIES_AND_INFRASTRUCTURE"><i class="material-icons">{{$icon}}</i>{{$svyr->details->alternate}}</a>
                        </li>
                    @else
                        <li>
                        <a class="kelikable" data-id="{{$svyr->category_id}}" href="#FACILITIES_AND_INFRASTRUCTURE"><i class="material-icons">{{$icon}}</i>{{$svyr->details->alternate}}</a>
                        </li>
                    @endif
                    @php
                    $i++;
                    @endphp
                @endforeach
  </ul>
  </div>
  <div class="container app-content-tab active" id="FACILITIES_AND_INFRASTRUCTURE">
                                <div class="block">
                                    <div class="app-heading app-heading-small">
                                            <div class="icon">
                                                <span class="icon-cube"></span>
                                            </div>
                                            <div class="title">
                                                <h2 id="onthis">Statistik kategori </h2>
                                                <p>Statistik jumlah keluhan/bulan dan persentase penyelesaian</p>
                                            </div>
                                </div>
                                <div class="row">

                                        <div class="col-md-6"><div class="block-content">
                                                    <div class="app-chart-holder" id="bulan-sarpras" style="height: 300px;"></div>
                                                </div>
                                        </div>

                                            <div class="col-md-6"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                                <canvas id="per-status-chart" style="height: 338px; display: block; width: 676px;" width="737" height="368" class="chartjs-render-monitor"></canvas>
                                                </div>
                                        </div>

                                </div>
                    </div>




@stop
@section('js_content')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" integrity="sha256-xKeoJ50pzbUGkpQxDYHD7o7hxe0LaOGeguUidbq6vis=" crossorigin="anonymous"></script>
    <script>
        $( document ).ready(function() {
            $('#bulan-sarpras').hide();
            $('#per-status-chart').hide();
    });
        $('.kelikable').click(function(){
            var ayd = $(this).data('id');

            $.ajax
            ({ 
                url: '{!! route($setting->grab('main_route').'.agentredata') !!}',
                data: {"catID": ayd, "_token": '{{ csrf_token() }}' },
                type: 'post',
                success: function(result)
                {
                    console.log(result[1]);
                $('#bulan-sarpras').fadeOut();
                $('#bulan-sarpras').empty();
                $('#per-status-chart').fadeOut();
                $('#per-status-chart').empty();
                    $('#bulan-sarpras').fadeOut( function() 
                    {
                        setTimeout(function() 
                        {
                        $('#bulan-sarpras').fadeIn(200);
                        var sarpras_chart = new Morris.Bar({
                                element: "bulan-sarpras",
                                data: result[0],
                                xkey: 'timestamp',
                                ykeys: ['amount'],
                                labels: ['Jumlah Keluhan'],
                                hideHover: false,
                                barColors: [getRandomColor()]
                            });


                        $('#per-status-chart').fadeIn(200);
                        var alltime_chart = new Chart(document.getElementById('per-status-chart'), {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: result[1],
                                    backgroundColor: {!! json_encode($colorkat) !!}
                                }],
                                labels: {!! json_encode($labelkat) !!}
                            },
                            options: {
                                legend: { display: true },
                                title: {
                                    display: true,
                                    text: 'Jumlah total laporan berdasarkan status laporan'
                                }
                            }
                        });
                        }, 100);
                    });
                    
                }
            });
        });
</script>
    <script>
                    $( ".deleteit" ).click(function( event ) {
                        event.preventDefault();
                        if (confirm("{!! trans('ticketit::admin.category-index-js-delete') !!}" + $(this).attr("node") + " ?"))
                        {
                            var form = $(this).attr("form");
                            $("#" + form).submit();
                        }

                    });

                    // DIVIDER

                    $(document).ready(function () {

                var yes = {!! json_encode($mk) !!};
                $('#btn-go-export').on('click', function () {
                    html2canvas(document.querySelector('#mingguan-chart-bar'), {useCORS: true}).then(canvas => {
                        $('#test').append(canvas)
                    })

                });



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


    @stop

