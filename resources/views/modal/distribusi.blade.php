{{--Modal Distribusi Pendapatan--}}
<div class="modal fade" id="distribusiModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard">
            <form id="wizardForm" method="POST" action="{{route('distribusi.pendapatan')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Pembagian Distribusi Pendapatan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabTrs" data-toggle="tab">Data Distribusi Pendapatan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabTrs">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-7 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Margin Tabungan kena Pajak <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="pajak" name="pajaktab" required="true">
                                            <span class="input-group-addon">0.0</span>
                                            <input type="hidden" name="nasabah" value="{{isset($data['nasabah'])?$data['nasabah']:""}}">
                                            <input type="hidden" name="bmt" value="{{isset($data['bmt'])?$data['bmt']:""}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Persentase <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                            <input type="text" class="currency form-control text-right" id="persen" name="persentab" required="true">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-7 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Margin Deposito kena Pajak <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="pajak2" name="pajakdep" required="true">
                                            <span class="input-group-addon">0.0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Persentase <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                            <input type="text" class="currency form-control text-right" id="persen2" name="persendep" required="true">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Distribusikan Pendapatan </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Distribusi Shu--}}
<div class="modal fade" id="distribusiSHUModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard2">
            <form id="wizardForm2" method="POST" action="{{route('distribusi.shu')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Pembagian Distribusi SHU</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabTrs2" data-toggle="tab">Data Distribusi SHU</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabTrs2">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-7 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Margin SHU kena Pajak <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="spajak" name="pajak" required="true">
                                            <span class="input-group-addon">0.0</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Persentase <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                            <input type="text" class="currency form-control text-right" id="spersen" name="persen" required="true">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Distribusikan SHU </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
