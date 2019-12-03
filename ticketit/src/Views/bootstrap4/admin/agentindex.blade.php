@extends('ticketit::layouts.master')

@section('page', trans('ticketit::admin.index-title'))
@section('page_title', 'Dashboard')
@section('ticketit_extra_content')

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
    <a class="active kelikable" data-id="{{$svyr->category_id}}" href="#{{$svyr->details->name}}"><i class="material-icons">{{$icon}}</i>{{$svyr->details->alternate}}</a>
    </li>
    @else
    <li>
    <a class="kelikable" data-id="{{$svyr->category_id}}" href="#{{$svyr->details->name}}"><i class="material-icons">{{$icon}}</i>{{$svyr->details->alternate}}</a>
    </li>
    @endif
    @php
    $i++;
    @endphp
  @endforeach
  </ul>
  </div>
<div class="container">


<div class="row stupf">
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
                                                <div class="title">Total :</div>
                                            </div>
                                            <div class="intval text-left"  id="tka">{{$tickets_count}}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexAll') }}">Jumlah Laporan masuk</a></div>
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
                                            <div class="title">Total :</div>
                                            </div>
                                            <div class="intval text-left" id="tkn">{{$open_tickets_count}}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexOpen') }}">Keluhan belum ditangani</a></div>
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
                                            <div class="title">Total :</div>
                                            </div>
                                            <div class="intval text-left" id="tkd">{{$closed_tickets_count}}</div>
                                            <div class="line">
                                                <div class="subtitle"><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexComplete') }}">Keluhan sudah ditangani</a></div>
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

        <div class="row">
            <div class="col-md-6">

                <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0" style="height: 131.833px;">
                    <li>
                        <!-- START WIDGET -->
                        <div class="app-widget-tile app-widget-highlight">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="icon icon-lg">
                                        <span class="icon-hourglass text-info"></span>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="line">
                                        <div class="title">keluhan sedang ditangani</div>
                                    </div>
                                    <div class="intval text-left">{{$tkpr}}</div>
                                    <div class="line">
                                        <div class="subtitle"></a>
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

                <ul class="app-feature-gallery app-feature-gallery-noshadow margin-bottom-0" style="height: 131.833px;">
                    <li>
                        <!-- START WIDGET -->
                        <div class="app-widget-tile app-widget-highlight">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="icon icon-lg">
                                        <span class="icon-undo text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="line">
                                        <div class="title">Keluhan dibuka kembali</div>
                                    </div>
                                    <div class="intval text-left" id="tkro">{{$tkro}}</div>
                                    <div class="line">
                                        <div class="subtitle"></a>
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



</div>

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
@section('extra_script')
<script>
$('.kelikable').click(function(){

    var ayd = $(this).data('id');

    $.ajax
    ({ 
        url: '{!! route($setting->grab('main_route').'.agentdata') !!}',
        data: {"catID": ayd, "_token": '{{ csrf_token() }}' },
        type: 'post',
        success: function(result)
        {
          $('#tka').fadeOut();
          $('#tkd').fadeOut();
            $('#tkn').fadeOut( function() 
            {
                setTimeout(function() 
                {
                  $('#tka').text(result.tc).fadeIn(200);
                  $('#tkd').text(result.ctc).fadeIn(200);
                  $('#tkn').text(result.otc).fadeIn(200);
                }, 100);
            });
            
        }
    });
});
</script>

@append