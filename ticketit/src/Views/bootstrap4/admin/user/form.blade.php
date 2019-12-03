<div class="form-group">
    {!! CollectiveForm::label('name', trans('ticketit::admin.status-create-name') . trans('ticketit::admin.colon'), ['class' => '']) !!}
    {!! CollectiveForm::text('name', isset($status->name) ? $status->name : null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! CollectiveForm::label('email', 'Email' . trans('ticketit::admin.colon'), ['class' => '']) !!}
    {!! CollectiveForm::text('email', isset($status->email) ? $status->email : null, ['class' => 'form-control']) !!}
</div>
@if($new)
<div class="form-group">
    {!! CollectiveForm::label('password', 'Password' . trans('ticketit::admin.colon'), ['class' => '']) !!}
    {!! CollectiveForm::password('password', null, ['class' => 'form-control awesome']) !!}
</div>
@endif
<div class="form-group">
    {!! CollectiveForm::label('identitas', 'Identitas' . trans('ticketit::admin.colon'), ['class' => '']) !!}
    {!! CollectiveForm::select('identitas', ['Mahasiswa' => 'Mahasiswa', 'Staff' => 'Staff', 'Dosen' => 'Dosen', 'Lain-Lain' => 'Lain-Lain'], isset($status->noidentitas) ? $status->noidentitas : null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! CollectiveForm::label('noidentitas', 'Nomor Identitas' . trans('ticketit::admin.colon'), ['class' => '']) !!}
    {!! CollectiveForm::text('noidentitas', isset($status->noidentitas) ? $status->noidentitas : null, ['class' => 'form-control']) !!}
</div>
{!! link_to_route($setting->grab('admin_route').'-userlist', trans('ticketit::admin.btn-back'), null, ['class' => 'btn btn-link']) !!}
@if(isset($status))
    {!! CollectiveForm::submit(trans('ticketit::admin.btn-update'), ['class' => 'btn btn-primary']) !!}
@else
    {!! CollectiveForm::submit(trans('ticketit::admin.btn-submit'), ['class' => 'btn btn-primary']) !!}
@endif
