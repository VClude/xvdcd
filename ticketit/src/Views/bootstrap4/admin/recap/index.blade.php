@extends('ticketit::layouts.master')

@section('page', trans('ticketit::admin.category-index-title'))
@section('page_title', 'Rekapitulasi Manager')
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
                <!--START REKAPITULASI MINGGUAN-->
                <div class="row">
                    <div class="col-md-12">
                        <!-- START ALLTIME CATEGORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Laporan per Kategori</h2>
                                    <p>Jumlah laporan sepanjang waktu per-kategori</p>
                                </div>
                            </div>
                            <div class="block-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Laporan Diterima</th>
                                        <th>Laporan Terselesaikan</th>
                                        <th>Laporan belum terselesaikan</th>
                                        <th>Total penyelesaian</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($kat as $category)
                <tr>
                    <td>
                        {{ $category->nk }}
                    </td>
                    <td>
                        {{ $category->jk }}
                    </td>
                    <td>
                        {{ $category->jkt }}
                    </td>
                    <td>
                        {{ $category->jkbt }}
                    </td>
                    <td>
                        {{ $category->jkt }}/{{ $category->jk }}
                    </td>
                </tr>
            @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- EOF ALLTIME CATEGORY -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- START ALLTIME CATEGORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Total laporan per bulan</h2>
                                    <p>Tabel laporan masuk per bulan</p>
                                </div>
                            </div>
                            <div class="block-content">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Jumlah Laporan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($month_count as $mon)
                                        <tr>
                                            <td><a href="#">{{ $mon['timestamp'] }}</a>
                                                
                                            </td>
                                            <td>{{ $mon['total'] }}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- EOF ALLTIME CATEGORY -->
                    </div>
                    <div class="col-md-6">
                        <!-- START TOP STORES -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Status Laporan</h2>
                                    <p>Perbandingan tingkat status penyelesaian laporan selama ini</p>
                                </div>
                            </div>
                            <div class="block-content">
                                <canvas height="300px" id="per-status-chart"></canvas>
                            </div>
                        </div>
                        <!-- END TOP STORES -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <!-- START PRODUCT SALES HISTORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Sejarah laporan : Kategori Sarana & prasarana</h2>
                                    <p>Laporan masuk per-bulan</p>
                                </div>
                            </div>

                            <div class="block-content">
                                <div class="app-chart-holder" id="bulan-sarpras" style="height: 300px;"></div>
                            </div>
                        </div>
                        <!-- END PRODUCT SALES HISTORY -->

                    </div>

                    <div class="col-md-6">

                        <!-- START PRODUCT SALES HISTORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Sejarah laporan : Kategori Gedung</h2>
                                    <p>Laporan masuk per-bulan</p>
                                </div>
                            </div>

                            <div class="block-content">
                                <div class="app-chart-holder" id="bulan-gedung" style="height: 300px;"></div>
                            </div>
                        </div>
                        <!-- END PRODUCT SALES HISTORY -->

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <!-- START PRODUCT SALES HISTORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Sejarah laporan : Kategori Tenaga kependidikan</h2>
                                    <p>Laporan masuk per-bulan</p>
                                </div>
                            </div>

                            <div class="block-content">
                                <div class="app-chart-holder" id="bulan-hr" style="height: 300px;"></div>
                            </div>
                        </div>
                        <!-- END PRODUCT SALES HISTORY -->

                    </div>

                    <div class="col-md-6">

                        <!-- START PRODUCT SALES HISTORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Sejarah laporan : Kategori Kebersihan</h2>
                                    <p>Laporan masuk per-bulan</p>
                                </div>
                            </div>

                            <div class="block-content">
                                <div class="app-chart-holder" id="bulan-kebersihan" style="height: 300px;"></div>
                            </div>
                        </div>
                        <!-- END PRODUCT SALES HISTORY -->

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <!-- START PRODUCT SALES HISTORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Sejarah laporan : Kategori Insiden dan tata tertib</h2>
                                    <p>Laporan masuk per-bulan</p>
                                </div>
                            </div>

                            <div class="block-content">
                                <div class="app-chart-holder" id="bulan-tatatertib" style="height: 300px;"></div>
                            </div>
                        </div>
                        <!-- END PRODUCT SALES HISTORY -->

                    </div>

                    <div class="col-md-6">

                        <!-- START PRODUCT SALES HISTORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Sejarah laporan : Kategori Lainnya</h2>
                                    <p>Laporan masuk per-bulan</p>
                                </div>
                            </div>

                            <div class="block-content">
                                <div class="app-chart-holder" id="bulan-lainnya" style="height: 300px;"></div>
                            </div>
                        </div>

                    </div>
                    <!-- END PRODUCT SALES HISTORY -->
                </div>

                <div class="row">
                    <div class="col-md-8">

                        <!-- START PRODUCT SALES HISTORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Sejarah laporan : Keseluruhan</h2>
                                    <p>Laporan masuk per-bulan</p>
                                </div>
                            </div>

                            <div class="block-content">
                                <div class="app-chart-holder" id="mingguan-chart-bar" style="height: 300px;"></div>
                            </div>
                        </div>
                        <!-- END PRODUCT SALES HISTORY -->

                    </div>

                    <div class="col-md-4">
                        <!-- START TOP STORES -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Top 5 Kategori Keluhan</h2>
                                    <p>Kategori laporan terbanyak</p>
                                </div>
                            </div>
                            <div class="block-content">
                                <canvas height="300px" id="top-kategori-chart"></canvas>
                            </div>
                        </div>
                        <!-- END TOP STORES -->
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 col-md-offset-10">
                        <a href="../printorexport.html" id="btn-go-export" class="btn btn-default"><span class="icon icon-printer"></span> Export
                            ke PDF
                        </a>
                    </div>
                </div>
                <div id="test"></div>
            </div>
            <!-- END PAGE CONTAINER -->


@stop
@section('js_content')
    @parent
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" integrity="sha256-xKeoJ50pzbUGkpQxDYHD7o7hxe0LaOGeguUidbq6vis=" crossorigin="anonymous"></script>
    <script>
                    console.log({!! json_encode($labelkat) !!});
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
                $('#roles').innerText = "Manager";

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

            

                var yes = {!! json_encode($mk) !!};
                $('#btn-go-export').on('click', function () {
                    html2canvas(document.querySelector('#mingguan-chart-bar'), {useCORS: true}).then(canvas => {
                        $('#test').append(canvas)
                    })

                });


                var chart = new Morris.Line({
                    element: 'mingguan-chart-bar',
                    data: yes,
                    xkey: 'timestamp',
                    ykeys: ['a', 'b', 'c', 'd', 'e', 'f'],
                    labels: {!! json_encode($katlabel) !!}
                });

                var perbulan_sarpras = [];
                var perbulan_gedung = [];
                var perbulan_pendidikan = [];
                var perbulan_kebersihan = [];
                var perbulan_insiden_tata_tertib = [];
                var perbulan_lainnya = [];

                yes.forEach(data => {
                    perbulan_sarpras.push({'timestamp' : data.timestamp, 'amount': data.a});
                    perbulan_gedung.push({'timestamp' : data.timestamp, 'amount': data.b});
                    perbulan_pendidikan.push({'timestamp' : data.timestamp, 'amount': data.c});
                    perbulan_kebersihan.push({'timestamp' : data.timestamp, 'amount': data.d});
                    perbulan_insiden_tata_tertib.push({'timestamp' : data.timestamp, 'amount': data.e});
                    perbulan_lainnya.push({'timestamp' : data.timestamp, 'amount': data.f});
                });

                let alltime_chart = new Chart(document.getElementById('per-status-chart'), {
                    type: 'pie',
                    data: {
                        datasets: [{
                            data: {!! json_encode($countkat) !!},
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

                var sarpras_chart = new Morris.Bar({
                    element: "bulan-sarpras",
                    data: perbulan_sarpras,
                    xkey: 'timestamp',
                    ykeys: ['amount'],
                    labels: ['Sarpras'],
                    hideHover: false,
                    barColors: [getRandomColor()]
                });

                var gedung_chart = new Morris.Bar({
                    element: "bulan-gedung",
                    data: perbulan_gedung,
                    xkey: 'timestamp',
                    ykeys: ['amount'],
                    labels: ['Gedung'],
                    hideHover: false,
                    barColors: [getRandomColor()]
                });

                var hr_chart = new Morris.Bar({
                    element: "bulan-hr",
                    data: perbulan_pendidikan,
                    xkey: 'timestamp',
                    ykeys: ['amount'],
                    labels: ['Tenaga kependidikan'],
                    hideHover: false,
                    barColors: [getRandomColor()]
                });

                var kebersihan_chart = new Morris.Bar({
                    element: "bulan-kebersihan",
                    data: perbulan_kebersihan,
                    xkey: 'timestamp',
                    ykeys: ['amount'],
                    labels: ['Kebersihan'],
                    hideHover: false,
                    barColors: [getRandomColor()]
                });

                var tata_tertib_chart = new Morris.Bar({
                    element: "bulan-tatatertib",
                    data: perbulan_insiden_tata_tertib,
                    xkey: 'timestamp',
                    ykeys: ['amount'],
                    labels: ['Tata Tertib'],
                    hideHover: false,
                    barColors: [getRandomColor()]
                });

                var lainnya_chart = new Morris.Bar({
                    element: "bulan-lainnya",
                    data: perbulan_lainnya,
                    xkey: 'timestamp',
                    ykeys: ['amount'],
                    labels: ['Lainnya'],
                    hideHover: false,
                    barColors: [getRandomColor()]
                });

                var top_cat_chart = new Chart(document.getElementById('top-kategori-chart'), {
                    type: 'pie',
                    data: {
                        datasets: [{data: {!! json_encode($countkat2) !!}, backgroundColor: {!! json_encode($colorkat2) !!} }],
                        labels: {!! json_encode($katlabel) !!}
                    }
                });

                console.log(perbulan_lainnya);

                // var chart = new Morris.Bar({
                //     element: "mingguan-chart-bar",
                //     data: data,
                //     xkey: "day",
                //     ykeys: ["value"],
                //     labels: ["Laporan"]
                // });


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

@section('footer')

@append
