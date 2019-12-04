@extends('ticketit::layouts.master')
@section('page', 'Ubah Password')
@section('page_title', 'Ubah Password')
@section('ticketit_content')
<div class="container" id="pnewrint">
                <!--START REKAPITULASI MINGGUAN-->

                <div class="row">
                    <div class="col-md-12">

                        <!-- START PRODUCT SALES HISTORY -->
                        <div class="block block-condensed">
                            <div class="app-heading">
                                <div class="title">
                                    <h2>Ganti password</h2>
                                    <p>Ganti informasi kredensial untuk login</p>
                                </div>
                            </div>
                            {!! CollectiveForm::model($status, [
                                    'route' => [$setting->grab('admin_route').'.changepass'],
                                    'method' => 'POST'
                                    ]) !!}
                            <div class="block-content" style="width: 700px">
                                <div class="form-group" id="pwd_bfr">
                                    <label for="pwd_bfr_inpt" class="control-label">Password lama</label>
                                    <input type="password" class="form-control" id="pwd_bfr_inpt" name="oldpassword">
                                </div>
                                <div class="form-group" id="pwd_aftr">
                                    <label for="pwd_aftr_inpt" class="control-label">Password baru</label>
                                    <input type="password" class="form-control" id="pwd_aftr_inpt" name="newpassword">
                                </div>
                                <div class="form-group" id="pwd_aftr_ver">
                                    <label for="pwd_aftr_ver_inpt" class="control-label">Verifikasi password baru</label>
                                    <input type="password" class="form-control" id="pwd_aftr_ver_inpt" name="verifpassword">
                                </div>

                                <div class="form-group" id="submit-btn">
                                    <input type="submit" class="btn btn-info" id="submit_btn_btn" value="Ubah Password">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            {!! CollectiveForm::close() !!}
@stop

