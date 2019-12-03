@extends('ticketit::layouts.master')

@section('page', trans('ticketit::admin.status-index-title'))

@section('ticketit_header')
{!! link_to_route(
    $setting->grab('admin_route').'.status.create',
    trans('ticketit::admin.btn-create-new-status'), null,
    ['class' => 'btn btn-primary'])
!!}
@stop

@section('ticketit_content_parent_class', 'p-0')

@section('ticketit_content')
<div class="container">
    @if ($statuses->isEmpty())
        <h3 class="text-center">{{ trans('ticketit::admin.status-index-no-statuses') }}
            {!! link_to_route($setting->grab('admin_route').'.status.create', trans('ticketit::admin.status-index-create-new')) !!}
        </h3>
    @else
        <div id="message"></div>
        <div class="row">
    <div class="col-md-1">

    {!! link_to_route(
    $setting->grab('admin_route').'.user.create',
    'Tambah User Baru', null,
    ['class' => 'btn btn-primary'])
!!}
</div>
</div>
        <table class="table table-hover">
        <thead>
                <tr>
                    <th>{{ trans('ticketit::admin.table-id') }}</th>
                    <th>{{ trans('ticketit::admin.table-name') }}</th>
                    <th>E-mail</th>
                    <th>Nomor Induk</th>
                    <th>{{ trans('ticketit::admin.table-action') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($statuses as $status)
            <tr>
                    <td style="vertical-align: middle">
                        {{ $status->id }}
                    </td>
                    <td style="vertical-align: middle">
                        {{ $status->name }}
                    </td>
                    <td style="vertical-align: middle">
                        {{ $status->email }}
                    </td>
                    <td style="vertical-align: middle">
                        {{ $status->noidentitas }}
                    </td>
                    <td>
                    @if($status->ticketit_admin == 1)
                    (Manager)
                    @elseif($status->ticketit_agent == 1)
                    {!! link_to_route(
                                                $setting->grab('admin_route').'.user.edit', trans('ticketit::admin.btn-edit'), $status->id,
                                                ['class' => 'btn btn-info'] )
                            !!}
                    (Surveyor)
                    @else
                        {!! link_to_route(
                                                $setting->grab('admin_route').'.user.edit', trans('ticketit::admin.btn-edit'), $status->id,
                                                ['class' => 'btn btn-info'] )
                            !!}

                            {!! link_to_route(
                                                $setting->grab('admin_route').'.user.destroy', trans('ticketit::admin.btn-delete'), $status->id,
                                                [
                                                'class' => 'btn btn-danger deleteit',
                                                'form' => "delete-$status->id",
                                                "node" => $status->name
                                                ])
                            !!}
                        {!! CollectiveForm::open([
                                        'method' => 'DELETE',
                                        'route' => [
                                                    $setting->grab('admin_route').'.user.destroy',
                                                    $status->id
                                                    ],
                                        'id' => "delete-$status->id"
                                        ])
                        !!}
                        {!! CollectiveForm::close() !!}
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
    {!! $statuses->render("pagination::bootstrap-4") !!}

</div>
@stop

@section('extra_script')
<script>
        $( ".deleteit" ).click(function( event ) {
            event.preventDefault();
            if (confirm("{!! trans('ticketit::admin.status-index-js-delete') !!}" + $(this).attr("node") + " ?"))
            {
                $form = $(this).attr("form");
                $("#" + $form).submit();
            }

        });
    </script>
@append
