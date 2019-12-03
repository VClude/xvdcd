<div class="container">
                <div class="app-content-separate app-content-separated-left">


 
                        <!-- CONTENT CONTAINER -->
                        <div class="container">

                            <div class="block block-condensed padding-top-20">

                                <div class="block-content margin-bottom-20 padding-left-15">
                                        <div class="pull-left">
                                             <h3>Daftar Keluhan</h3>
                                        </div>


                                </div>
                                <div class="block-divider margin-0"></div>
                                <div class="table-responsive">
                                    <table class="table table-head-light table-striped">
                                        <thead>
                                        <tr>
                                            <td>{{ trans('ticketit::lang.table-id') }}</td>
                                            <td>{{ trans('ticketit::lang.table-owner') }}</td>
                                            <td>{{ trans('ticketit::lang.table-subject') }}</td>
                                            <td>{{ trans('ticketit::lang.table-status') }}</td>
                                            <td>{{ trans('ticketit::lang.table-last-updated') }}</td>
                                            {{-- <td>{{ trans('ticketit::lang.table-agent') }}</td> --}}
                                          @if( $u->isAgent() || $u->isAdmin() )
                                            {{-- <td>{{ trans('ticketit::lang.table-priority') }}</td> --}}
                                            
                                            <td>{{ trans('ticketit::lang.table-category') }}</td>
                                          @endif
                                        </tr>
                                        </thead>
                                        <tbody id="table-blyat">
                                        <!--Appended by JS-->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <!-- CONTENT CONTAINER -->
                    </div>

            </div>
