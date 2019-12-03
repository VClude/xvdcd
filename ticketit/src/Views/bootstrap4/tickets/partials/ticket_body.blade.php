@section('extra_css')
<link rel="stylesheet" src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">
<style>
/* Main carousel style */
.carousel {
    width: 600px;
}

/* Indicators list style */
.article-slide .carousel-indicators {
    bottom: 0;
    left: 0;
    margin-left: 5px;
    width: 100%;
}
/* Indicators list style */
.article-slide .carousel-indicators li {
    border: medium none;
    border-radius: 0;
    float: left;
    height: 54px;
    margin-bottom: 5px;
    margin-left: 0;
    margin-right: 5px !important;
    margin-top: 0;
    width: 100px;
}
/* Indicators images style */
.article-slide .carousel-indicators img {
    border: 2px solid #FFFFFF;
    float: left;
    height: 54px;
    left: 0;
    width: 100px;
}
/* Indicators active image style */
.article-slide .carousel-indicators .active img {
    border: 2px solid #428BCA;
    opacity: 0.7;
}
</style>
@stop
<div class="container">
                <div class="app-content-separate app-content-separated-left">

                   
                    <div class="app-content-separate-center">
                        <!-- CONTENT CONTAINER -->
                        <div class="container">

                            <div class="block block-condensed">
                                <div class="app-heading">
                                <script>

                                </script>
                                    <div class="title">
                                        <h2>{{ $ticket->subject }}</h2>
                                        <p>{{ $ticket->created_at->diffForHumans() }} <span class="icon-map-marker"></span> {{ $ticket->location }}</p>
                                        <p class="margin-top-5"><span class="label label-danger">{{ $ticket->status->alternate }}</span>&nbsp;<span class="label label-primary">{{ $ticket->category->alternate }}</span></p>
                                    </div>
                                    <div class="heading-elements">
                                        <div class="contact contact-rounded contact-bordered contact-lg status-online" style="width: 300px;">
                                            <img src="{{ asset('/FTUIM-Admin/resources/assets/images/users/user_5.jpg') }}">
                                            <div class="contact-container">
                                                <a href="#">{{ $ticket->user_id == $u->id ? $u->name : $ticket->user->name }}</a>
                                                <span>
                                               </span>
                                            </div>
                                            <div class="contact-controls dropdown dropdown-menu-right">
                                                <button class="btn btn-default btn-icon dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"><span class="fa fa-cogs"></span></button>
                                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                            
    @if($u->isAdmin())
        @if(! $ticket->completed_at && $proses_perm == 'yes' && $ticket->status_id == '1' || $ticket->status_id == '4')
                <li>
                    <a href="{!! route($setting->grab('main_route').'.proses', $ticket->id) !!}"><span class="icon-exit-up"></span>
                        Proses Keluhan
                    </a>
                </li>
                
        @elseif($ticket->completed_at && $reopen_perm == 'yes' && $ticket->status_id == '3')
                <li>
                    <a href="{!! route($setting->grab('main_route').'.reopen', $ticket->id) !!}"><span class="icon-history"></span>
                        Buka Kembali Keluhan
                    </a>
                </li>
        @elseif(! $ticket->completed_at && $close_perm == 'yes' && $ticket->status_id == '2')
                <li>
                    <a href="{!! route($setting->grab('main_route').'.complete', $ticket->id) !!}"><span class="icon-exit-up"></span>
                        Akhiri / Tutup Keluhan
                    </a>
                </li>
        @endif
            <li>
            <a href='#'  data-toggle="modal" data-target="#ticket-edit-modal"><span class="icon-exit-up"></span>
                {{ trans('ticketit::lang.btn-edit')  }}
            </a>
            </li>
    @elseif($u->isAgent())
        @if(! $ticket->completed_at && $proses_perm == 'yes' && $ticket->status_id == '1' || $ticket->status_id == '4')
                <li>
                    <a href="{!! route($setting->grab('main_route').'.proses', $ticket->id) !!}"><span class="icon-exit-up"></span>
                        Proses Keluhan
                    </a>
                </li>
                <li>
                    <a href="{!! route($setting->grab('main_route').'.complete', $ticket->id) !!}"><span class="icon-hand"></span>
                        Akhiri / Tutup Keluhan
                    </a>
                </li>
        @elseif($ticket->completed_at && $reopen_perm == 'yes' && $ticket->status_id == '3')
                <li>
                    <a href="{!! route($setting->grab('main_route').'.reopen', $ticket->id) !!}"><span class="icon-history"></span>
                        Buka Kembali Keluhan
                    </a>
                </li>
        @elseif(! $ticket->completed_at && $close_perm == 'yes' && $ticket->status_id == '2')
        <li>
                    <a href="{!! route($setting->grab('main_route').'.complete', $ticket->id) !!}"><span class="icon-hand"></span>
                        Akhiri / Tutup Keluhan
                    </a>
                </li>
        @endif
    @else
        @if(! $ticket->completed_at && $close_perm == 'yes' && $ticket->status_id == '2')
        <li>
            {!! link_to_route($setting->grab('main_route').'.complete', trans('ticketit::lang.btn-mark-complete'), $ticket->id,
                                ['class' => 'btn btn-success']) !!}
   </li>
        @endif
    @endif
    @if($u->isAdmin())
        @if($setting->grab('delete_modal_type') == 'builtin')
        <li>   
        {!! link_to_route(
          
                            $setting->grab('main_route').'.destroy', 'Hapus Keluhan', $ticket->id,
                            [
                            'class' => 'deleteit',
                            'form' => "delete-ticket-$ticket->id",
                            "node" => $ticket->subject
                            ])
                
            !!}

            </li>
        @elseif($setting->grab('delete_modal_type') == 'modal')
{{-- // OR; Modal Window: 1/2 --}}
            {!! CollectiveForm::open(array(
                    'route' => array($setting->grab('main_route').'.destroy', $ticket->id),
                    'method' => 'delete',
                    'style' => 'display:inline'
               ))
            !!}
            <button type="button"
                    class="btn btn-danger"
                    data-toggle="modal"
                    data-target="#confirmDelete"
                    data-title="{!! trans('ticketit::lang.show-ticket-modal-delete-title', ['id' => $ticket->id]) !!}"
                    data-message="{!! trans('ticketit::lang.show-ticket-modal-delete-message', ['subject' => $ticket->subject]) !!}"
             >
              {{ trans('ticketit::lang.btn-delete') }}
            </button>
        @endif
            {!! CollectiveForm::close() !!}
{{-- // END Modal Window: 1/2 --}}
    @endif


                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="block-content">
                                {{ $ticket->content }}
                                </div>
                                <div class="block-divider"></div>
                                <div class="block-content text-bold">
                                    <h5 class="text-bold text-rg margin-bottom-5"><span class="fa fa-file-o"></span> Gambar keluhan</h5>
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                    <div class="carousel slide article-slide" id="article-photo-carousel">
                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner cont-slider">
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach($images as $image)
                                            @if($i == 1)
                                                <div class="item active">
                                                    <a href="{!! url('/') . '/images/' . $image->image !!}" data-toggle="lightbox">
                                                        <img alt="" style="width:600 !important; height:400 !important;" width="600" height="400" src="{!! url('/') . '/images/' . $image->image !!}">
                                                    </a>
                                                </div>
                                            @else
                                            <div class="item">
                                                    <a href="{!! url('/') . '/images/' . $image->image !!}" data-toggle="lightbox">
                                                        <img alt="" style="width:600 !important; height:400 !important;" width="600" height="400" src="{!! url('/') . '/images/' . $image->image !!}">
                                                    </a>
                                                </div>
                                            @endif
                                            @php
                                                $i++;
                                            @endphp
                                     @endforeach
                                        </div>
                                        <!-- Indicators -->
                                        <ol class="carousel-indicators">
                                        @php
                                            $a = 0;
                                        @endphp
                                        @foreach($images as $image)
                                            @if($a == 0)
                                                <li class="active" data-slide-to="{{$a}}" data-target="#article-photo-carousel">
                                                    <img alt="" width="250" height="180" src="{!! url('/') . '/images/' . $image->image !!}">
                                                </li>
                                            @else
                                            <li class="" data-slide-to="{{$a}}" data-target="#article-photo-carousel">
                                                <img alt="" width="250" height="180" src="{!! url('/') . '/images/' . $image->image !!}">
                                            </li>
                                            @endif
                                            @php
                                                $a++;
                                            @endphp
                                     @endforeach

                                        </ol>
                                        </div>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                                </div>
                            </div>

                            <div class="block block-condensed block-arrow-top">
                                <div class="app-heading">
                                    <div class="title">
                                        <h2>{!! $commentscount !!} Comments for this post</h2>
                                    </div>
                                </div>
                                <div class="block-content">
                                <div class="comments">

                            @if(!$comments->isEmpty())
                                @foreach($comments as $comment)
                                <div class="comment">
                                    <div class="contact contact-rounded contact-lg"><img
                                            src="http://pengaduan.ccit-solution.com/FTUIM-Admin/resources/assets/images/users/user_1.jpg">
                                        <div class="contact-container"><a href="#">{!! $comment->user->name !!}  
                                        
                                        
                                        </a>  
                                       
                                        <span>{!! $comment->content !!}</span>
                                        <span class="pull-right text-muted"><i class="fa fa-clock-o"></i> {!! $comment->created_at->diffForHumans() !!}

                                            @if(Auth::user()->id == $comment->user_id)
|
                                                        <a href="{!! route($setting->grab('main_route').'.komendestroy', $comment->id) !!}"><span class="icon-delete"></span>
                                                            Hapus
                                                        </a>

                                            @endif
                                         </span>
                                    </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                                <br/>
                                @section('ticketit_extra_content')
                                    @include('ticketit::tickets.partials.comments')
                                    {{-- pagination --}}
                                    {!! $comments->render("pagination::bootstrap-4") !!}
                                    @if($ticket->completed_at && $reopen_perm == 'yes')

                                    @else
                                        @include('ticketit::tickets.partials.comment_form')
                                    @endif
                                    @stop

                                    {!! CollectiveForm::open(['method' => 'POST', 'route' => $setting->grab('main_route').'-comment.store', 'class' => '']) !!}
                                    <div class="form">
                                    {!! CollectiveForm::hidden('ticket_id', $ticket->id ) !!}
                                    <div class="form-group">
                                        <div class="input-group">
                                       


                                        


                                        {!! CollectiveForm::text('content', null, ['class' => 'form-control','placeholder' => 'Your comment...']) !!}

                                            <div class="input-group-btn">  {!! CollectiveForm::button( trans('ticketit::lang.reply'), ['class' => 'btn btn-default', 'type' => 'submit']) !!}</div>
                                            
                                        </div>
                                    </div>
                                </div>
                                {!! CollectiveForm::close() !!}   
                            </div>
                                    <!-- <div class="form-group">
                                        <textarea class="form-control" rows="3" placeholder="Post your reply"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="pull-left">
                                            <div class="form-group">
                                                <a href="#" class="file-input btn btn-default"><input id="image_input" type="file" class="file" accept="image/*" title="Add attachment">Add attachment</a><span class="file-input-name"></span>
                                            </div>
                                        </div>
                                        <div class="pull-right">
                                            <button class="btn btn-danger"><span class="fa fa-envelope"></span> Send Message</button>
                                        </div>
                                    </div>
                                </div>
                            </div> -->

                        </div>
                        <!-- CONTENT CONTAINER -->
                    </div>

                </div>
            </div>
@section('extra_script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.js"></script>
<script>
$(document).on('click', '[data-toggle="lightbox"]', function(event) {
                event.preventDefault();
                $(this).ekkoLightbox();
            });
</script>
@append
{!! CollectiveForm::open([
                'method' => 'DELETE',
                'route' => [
                            $setting->grab('main_route').'.destroy',
                            $ticket->id
                            ],
                'id' => "delete-ticket-$ticket->id"
                ])
!!}
{!! CollectiveForm::close() !!}


@if($u->isAgent() || $u->isAdmin())
    @include('ticketit::tickets.edit')
@endif

{{-- // OR; Modal Window: 2/2 --}}
@if($u->isAdmin())
    @include('ticketit::tickets.partials.modal-delete-confirm')
@endif
{{-- // END Modal Window: 2/2 --}}
