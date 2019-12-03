@extends('ticketit::layouts.master')
@section('page', trans('ticketit::admin.status-create-title'))

@section('ticketit_content') 
    {!! CollectiveForm::open(['route'=> $setting->grab('admin_route').'.user.store', 'method' => 'POST', 'class' => '']) !!}
        @include('ticketit::admin.user.form')
    {!! CollectiveForm::close() !!}
@stop
