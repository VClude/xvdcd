@extends($master)
@section('css_content')

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('/FTUIM-Admin/resources/css/styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/FTUIM-Admin/resources/css/override.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/FTUIM-Admin/resources/css/font-import.css') }}">
@endsection
@section('js_content')
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/jquery/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/jquery/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/bootstrap/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/moment/moment.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/customscrollbar/jquery.mCustomScrollbar.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/bootstrap-select/bootstrap-select.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/select2/select2.full.min.js') }}"></script>
<script type="text/javascript"
        src="{{ asset('/FTUIM-Admin/resources/js/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/maskedinput/jquery.maskedinput.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/form-validator/jquery.form-validator.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/noty/jquery.noty.packaged.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/datatables/dataTables.bootstrap.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/sweetalert/sweetalert.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/knob/jquery.knob.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/sparkline/jquery.sparkline.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/morris/raphael.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/morris/morris.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/rickshaw/d3.v3.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/rickshaw/rickshaw.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/isotope/isotope.pkgd.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/dropzone/dropzone.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/nestable/jquery.nestable.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/cropper/cropper.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/tableexport/tableExport.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/tableexport/jquery.base64.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/tableexport/html2canvas.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/tableexport/jspdf/libs/sprintf.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/tableexport/jspdf/jspdf.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/tableexport/jspdf/libs/base64.js') }}"></script>

<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/bootstrap-daterange/daterangepicker.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/bootstrap-tour/bootstrap-tour.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/fullcalendar/fullcalendar.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/vendor/smartwizard/jquery.smartWizard.js') }}"></script>



<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/app_plugins.js') }}"></script>
<script type="text/javascript" src="{{ asset('/FTUIM-Admin/resources/js/app_demo.js') }}"></script>


@endsection

@section('content')
    @include('ticketit::shared.header')
    <div class="app-container">
    <div id="ignore-sidebar" class="app-sidebar app-navigation  app-navigation-fixed app-navigation-style-light dir-left" data-type="close-other">
        <a href="index.html" class="app-navigation-logo"></a>
        <nav>
            <ul>
            

                <li><a href="{{ action('\Kordy\Ticketit\Controllers\DashboardController@index') }}"><span class="icon-home"></span>{{ trans('ticketit::admin.nav-dashboard') }}</a></li>
                <li class="openable"><a href="#"><span class="icon-inbox"></span> Keluhan</a>
                    <ul>
                    <li>
                    <a
                href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexOpen') }}">Keluhan Dibuka
                <span class="nav-icon-hexa">Ac</span>
                <span class="label label-default">
                     <?php 
                        if ($u->isAdmin()) {
                            echo Kordy\Ticketit\Models\Ticket::active()->count();
                        } elseif ($u->isAgent()) {
                            echo Kordy\Ticketit\Models\Ticket::active()->myKeluhan($u->id)->count();
                        } else {
                            echo Kordy\Ticketit\Models\Ticket::userTickets($u->id)->active()->count();
                        }
                    ?>
                </span>
            </a>
        </li>
                                <li>
                                    <a
                                    href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@indexComplete') }}">Keluhan Selesai
                                    <span class="nav-icon-hexa">Co</span>
                                    <span class="label label-default">
                                        <?php 
                        if ($u->isAdmin()) {
                            echo Kordy\Ticketit\Models\Ticket::complete()->count();
                        } elseif ($u->isAgent()) {
                            echo Kordy\Ticketit\Models\Ticket::complete()->myKeluhan($u->id)->count();
                        } else {
                            echo Kordy\Ticketit\Models\Ticket::userTickets($u->id)->complete()->count();
                        }
                                        ?>
                                    </span>
                                </a>
                            </li>
                    </ul>
                </li>
                <li><a href="{{ action('\Kordy\Ticketit\Controllers\RecapController@index') }}"><span class="icon-database"></span>Statistik</a></li>
                @if ($u->isAdmin())
               <li><a href="{{ action('\jeremykenedy\LaravelLogger\App\Http\Controllers\LaravelLoggerController@showAccessLog') }}"><span class="icon-history"></span>Riwayat Log</a></li>
                <li class="openable"><a href="#"><span class="icon-inbox"></span> Manager / Admin Tools</a>
                <ul>
                    <li>
                    <a href="{{ action('\Kordy\Ticketit\Controllers\StatusesController@userList') }}">User List
                            <span class="nav-icon-hexa">Us</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('\Kordy\Ticketit\Controllers\AdministratorsController@index') }}">Manager List
                            <span class="nav-icon-hexa">Ma</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('\Kordy\Ticketit\Controllers\AgentsController@index') }}">Surveyor List
                            <span class="nav-icon-hexa">Su</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('\Kordy\Ticketit\Controllers\CategoriesController@index') }}">Category List
                            <span class="nav-icon-hexa">Ca</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ action('\Kordy\Ticketit\Controllers\StatusesController@index') }}">Status List
                            <span class="nav-icon-hexa">St</span>
                        </a>
                    </li>
                </ul>
                </li>
                @endif
                </ul>
                
        </nav>
        </div>
        <div class="app-content app-sidebar-left">
        <div id="ignore-main-heading" class="app-header">
            <ul class="app-header-buttons">
                <li class="visible-mobile"><a href="#" class="btn btn-link btn-icon"
                        data-sidebar-toggle=".app-sidebar.dir-left"><span class="icon-menu"></span></a></li>
                <li class="hidden-mobile"><a href="#" class="btn btn-link btn-icon"
                        data-sidebar-minimize=".app-sidebar.dir-left"><span class="icon-list"></span></a></li>
            </ul>
            <form class="app-header-search" action="" method="post">
                <input type="text" name="keyword" placeholder="Search">
            </form>

            <ul class="app-header-buttons pull-right">

                <li>
                    <div class="dropdown">
                        @yield('notifbutton')
                        <ul class="dropdown-menu dropdown-form dropdown-left dropdown-form-wide">
                            <li class="padding-0">

                                <div class="app-heading title-only app-heading-bordered-bottom">
                                    <div class="icon">
                                        <span class="icon-text-align-left"></span>
                                    </div>
                                    <div class="title">
                                        <h2>Notifications</h2>
                                    </div>
                                    <div class="heading-elements">
                                        <a href="#" class="btn btn-default btn-icon"><span class="icon-sync"></span></a>
                                    </div>
                                </div>

                                <div class="app-timeline scroll app-timeline-simple text-sm mCustomScrollbar _mCS_2 mCS-autoHide mCS_no_scrollbar"
                                    style="height: 240px;">
                                    <div id="mCSB_2" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside"
                                        style="max-height: 200px;" tabindex="0">
                                        <div id="mCSB_2_container"
                                            class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y"
                                            style="position:relative; top:0; left:0;" dir="ltr">
                                            @yield('notificationstuff')


                                        </div>
                                        <div id="mCSB_2_scrollbar_vertical"
                                            class="mCSB_scrollTools mCSB_2_scrollbar mCS-light mCSB_scrollTools_vertical"
                                            style="display: none;">
                                            <div class="mCSB_draggerContainer">
                                                <div id="mCSB_2_dragger_vertical" class="mCSB_dragger"
                                                    style="position: absolute; min-height: 30px; top: 0px;">
                                                    <div class="mCSB_dragger_bar" style="line-height: 30px;"></div>
                                                </div>
                                                <div class="mCSB_draggerRail"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </li>
                            <li class="padding-top-0">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>   
                                <button class="btn btn-block btn-link">Preview All</button>
                            </li>
                        </ul>
                    </div>
                </li>
        <li>
            <div class="contact-controls">
                <div class="dropdown">
                    <button type="button" class="btn btn-default btn-icon" data-toggle="dropdown"><span class="icon-layers"></span></button>
                    <ul class="dropdown-menu dropdown-left">
                        <li><a href="../profile-edit.html"><span class="icon-users"></span> Ubah Profil</a></li>
                        <li><a href="../pwd_chng.html"><span class="icon-lock"></span> Ubah Password</a></li>
                        <li> <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><span class="icon-power-switch"></span> Logout</a></li>
                    </ul>
                </div>
            </div>
        </li>
            </ul>
        </div>
        <div class="app-heading app-heading-bordered app-heading-page">
                <!-- <div class="icon icon-lg">
                    <span class="icon-user"></span>
                </div> -->
                <div class="contact contact-rounded contact-bordered contact-lg contact-ps-controls hidden-xs" style="padding-right:0px !important;">
                    <?php
                    if (Auth::user()->imgurl == "" || Auth::user()->imgurl == NULL ) {?>
                        <img src="{{ asset('/FTUIM-Admin/resources/assets/images/users/user_1.jpg') }}" alt="{{ Auth::user()->name }}">
                        
                        <?php
                    } else{?>
                        <img src="{{ asset('/user/images/' . Auth::user()->imgurl) }}" alt="{{ Auth::user()->name }}">

            <?php
                    }
                    ?>    
                     </div>
                <div class="title">
                    <h1>
                    {{ Auth::user()->name }}
                    </h1>
                    <p><?php
                        if ($u->isAdmin()) {
                            echo "Manager";
                        } elseif ($u->isAgent()) {
                            echo "Surveyor";
                        } else {
                            echo "Mahasiswa";
                        }
                        ?></p>
                </div>
            </div>
            <div class="app-heading-container app-heading-bordered bottom">
                <ul class="breadcrumb">
                <li><a href="#">E-Keluhan</a></li>
                @if(View::hasSection('section'))
                    <li><a href="#">@yield('section')</a></li>
                    @endif

                    <li class="active">@yield('page_title')</li>
                </ul>
            </div>


        @if(View::hasSection('ticketit_content'))
        @if($errors->first() != '')
    <div class="container">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
@if(Session::has('warning'))
    <div class="container">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
            {{ session('warning') }}
        </div>
    </div>
@endif
@if(Session::has('status'))
    <div class="container">
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
            {{ session('status') }}
        </div>
    </div>
@endif
            <div class="card">
 
                <div class="card-body @yield('ticketit_content_parent_class')">
      
                    @yield('ticketit_content')
                </div>
            </div>
        @endif
        @include('toast::messages')
        @yield('ticketit_extra_content')
        </div>
    </div>
@stop
