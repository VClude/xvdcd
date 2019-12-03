@extends('ticketit::layouts.master')

@section('page', trans('ticketit::admin.administrator-index-title'))

@section('ticketit_content')

@stop

@section('ticketit_content_parent_class', 'p-0')

@section('ticketit_extra_content')
<div class="container">
<div class="row">
    <div class="col-md-1">

    {!! link_to_route(
    $setting->grab('admin_route').'.administrator.create',
    'Tambah Manager / Admin Baru', null,
    ['class' => 'btn btn-primary'])
!!}
</div>
</div>
    @if ($administrators->isEmpty())
        <h3 class="text-center">{{ trans('ticketit::admin.administrator-index-no-administrators') }}
            {!! link_to_route($setting->grab('admin_route').'.administrator.create', trans('ticketit::admin.administrator-index-create-new')) !!}
        </h3>
    @else
        <div id="message"></div>
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                <th>{{ trans('ticketit::admin.table-id') }}</th>
                <th>{{ trans('ticketit::admin.table-name') }}</th>
                <th>{{ trans('ticketit::admin.table-remove-administrator') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($administrators as $administrator)
                <tr>
                    <td>
                        {{ $administrator->id }}
                    </td>
                    <td>
                        {{ $administrator->name }}
                    </td>
                    <td>
                        {!! CollectiveForm::open([
                        'method' => 'DELETE',
                        'route' => [
                                    $setting->grab('admin_route').'.administrator.destroy',
                                    $administrator->id
                                    ],
                        'id' => "delete-$administrator->id"
                        ]) !!}
                        {!! CollectiveForm::submit(trans('ticketit::admin.btn-remove'), ['class' => 'btn btn-danger']) !!}
                        {!! CollectiveForm::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif
        </div>
@stop
