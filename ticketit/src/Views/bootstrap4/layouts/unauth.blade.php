@extends('ticketit::layouts.master')

@section('page', trans('ticketit::admin.category-index-title'))

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
                <h3>404 - Nothing found here..</h3>
                </div>
                <!-- END PAGE CONTAINER -->


@stop

