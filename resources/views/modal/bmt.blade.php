{{--Modal Transfer Rekening--}}
<div class="modal fade" id="transferRekModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 80%">
        <div class="card card-wizard" id="wizardCardTrans">
            <form id="wizardFormTrans" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('transfer')}}" @else action="{{route('teller.transfer')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Transfer Antar Rekening</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabTrs" data-toggle="tab">Data Rekening</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabTrs">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Transfer dari Rekening <star>*</star></label>
                                        <select class="form-control select2" name="dari" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                            @foreach ($rekening_penyeimbang as $rekening)
                                                <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} @if($rekening->saldo != "") [ Rp. {{ number_format($rekening->saldo, 2) }} ] @else [ Rp. 0 ] @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Transfer ke Rekening <star>*</star></label>
                                        <select class="form-control select2" name="untuk" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                            @foreach ($rekening_penyeimbang as $rekening)
                                                <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} @if($rekening->saldo != "") [ Rp. {{ number_format($rekening->saldo, 2) }} ] @else [ Rp. 0 ] @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="jumlah" name="jumlah" required="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan <star>*</star></label>
                                        <input type="text" class="form-control" name="keterangan" required="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Transfer </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Jurnal Lain Rekening--}}
<div class="modal fade" id="jurnalLainRekModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 80%">
        <div class="card card-wizard" id="wizardCardJ">
            <form id="wizardFormJ" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('jurnal_lain')}}" @else action="{{route('teller.jurnal_lain')}}" @endif  enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title" id="title_jurnal_lain"><h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabJ" data-toggle="tab">Data Jurnal</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabJ">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div id="rowPemasukanJurnalLain">

                                {{-- @if(Request::is('admin/transfer/transfer'))
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Rekening Tujuan <star>*</star></label>
                                            <select class="form-control select2" name="tujuan[]" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                                @foreach ($dropdown as $rekening)
                                                    <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} @if($rekening->saldo != "") [ Rp. {{ number_format($rekening->saldo, 2) }} ] @else [ Rp. 0 ] @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif --}}

                                <div class="row">
                                    <div class="col-md-3 ">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Rekening Penyeimbang <star>*</star></label>
                                            <select class="form-control select2" id="idRekJ" name="dari[]" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                                @foreach ($dropdown as $rekening)
                                                    <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} @if($rekening->saldo != "") [ Rp. {{ number_format($rekening->saldo, 2) }} ] @else [ Rp. 0 ] @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Uang <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right" id="jumlah[]" name="jumlah[]" required="true">
                                                <span class="input-group-addon">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Keterangan<star>*</star></label>
                                            <input type="text" class="form-control"  name="keterangan[]" required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="" class="control-label">&nbsp;</label>
                                            <button type="button" id="add-row-pemasukan" class="btn btn-success btn-fill pull-right">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="tipe[]" id="tipe" />
                                {{-- <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group"> --}}
                                            {{-- <label for="id_" class="control-label">Tipe Transaksi <star>*</star></label>
                                            <select class="form-control select2"  name="tipe" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Transaksi-</option>
                                                <option value="1">Pemasukkan</option>
                                                <option value="0">Pengeluaran</option>
                                            </select> --}}
                                        {{-- </div>
                                    </div>
                                </div> --}}
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Transfer </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Edit Saldo Rekening--}}
<div class="modal fade" id="editSaldoModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardS">
            <form id="wizardFormS" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('edit.saldo')}}" @else  action="{{route('teller.edit.saldo')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Edit Saldo Rekening</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1" data-toggle="tab">Data Rekening</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nama Rekening <star>*</star></label>
                                        <select class="form-control select2" id="idRekS" disabled name="dari" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan Anda-</option>
                                            @foreach ($dropdown as $rekening)
                                                <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                            <input type="hidden" id="id_bmt" name="id_">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Saldo Rekening <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="saldo" name="jumlah" required="true">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Edit Saldo </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>


{{--Modal Wajib Pokok Rekening--}}
<div class="modal fade" id="wapokRekModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardWP">
            <form id="wizardFormWP" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('upgrade_simp')}}" @else action="{{route('teller.upgrade_simp')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Updgrade Simpanan Wajib Pokok</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabWP" data-toggle="tab">Data Simpanan Wajib Pokok</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabWP">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Jenis Simpanan <star>*</star></label>
                                        <select class="form-control select2"  name="wapok" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Jenis-</option>
                                            <option value="1">Simpanan Wajib</option>
                                            <option value="0">Simpanan Pokok</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="row">--}}
                                {{--<div class="col-md-10 col-md-offset-1">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="id_" class="control-label">Sumber Dana <star>*</star></label>--}}
                                        {{--<select class="form-control select2" id="sumber" name="asal" style="width: 100%;" required>--}}
                                            {{--<option class="bs-title-option" selected disabled value="">-Pilih Sumber-</option>--}}
                                            {{--<option value="1">Rekening BMT</option>--}}
                                            {{--<option value="0">Jurnal Lain</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-md-10 col-md-offset-1"  id="ShowHide">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="id_" class="control-label">Transfer dari Rekening <star>*</star></label>--}}
                                        {{--<input type="hidden" id="dariRek" name="dariRek">--}}
                                        {{--<select class="form-control select2" id="idRekWPD" name="dari" style="width: 100%;" required>--}}
                                            {{--<option class="bs-title-option" selected disabled value="pilih">-Pilih Rekening BMT-</option>--}}
                                            {{--@foreach ($dropdown as $rekening)--}}
                                                {{--<option value="{{ $rekening->id." ".$rekening->saldo }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>--}}
                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                                {{--<div class="col-md-10 col-md-offset-1" id="ShowHide2">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-md-10 col-md-offset-1">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="id_" class="control-label">Tipe Transaksi <star>*</star></label>--}}
                                        {{--<select class="form-control select2"  name="tipe" style="width: 100%;" required>--}}
                                            {{--<option class="bs-title-option" selected disabled value="">-Pilih Transaksi-</option>--}}
                                            {{--<option value="1">Upgrade</option>--}}
                                            {{--<option value="0">Downgrade</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Keterangan<star>*</star></label>
                                        <input type="text" class="form-control"  name="keterangan" required="true">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="saldoRek">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Saldo Rekening <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="j_rek" disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                        <span id="validate-status"class="help-block text-danger"><star>*</star></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Anggota <star>*</star></label>
                                        <input type="text" class="form-control text-right" id="nas_" value="{{$nasabah}}"  disabled>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Total<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="tot_nas" disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Upgrade per Anggota <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="j_upgrade" name="jumlah" required="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" id="submit-button" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Upgrade Simpanan </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Jurnal Lain Rekening Admin--}}
@if(Auth::user()->tipe == "admin")
<div class="modal fade" id="jurnalLainRekAdminModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 80%">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('jurnal_lain')}}" @endif  enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title" id="title_jurnal_lain_admin"><h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabJurnalLainAdmin" data-toggle="tab">Data Jurnal</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabJurnalLainAdmin">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>

                            <div id="rowPemasukanJurnalLainAdmin">

                                {{-- @if(Request::is('admin/transfer/transfer'))
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Rekening Tujuan <star>*</star></label>
                                            <select class="form-control select2" name="tujuan[]" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                                @foreach ($dropdown as $rekening)
                                                    <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} @if($rekening->saldo != "") [ Rp. {{ number_format($rekening->saldo, 2) }} ] @else [ Rp. 0 ] @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @endif --}}

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Rekening Penyeimbang <star>*</star></label>
                                            <select class="form-control select2" name="dari[]" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                                @foreach ($rekening_penyeimbang as $rekening)
                                                    <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} @if($rekening->saldo != "") [ Rp. {{ number_format($rekening->saldo, 2) }} ] @else [ Rp. 0 ] @endif</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Uang <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right" id="jumlah[]" name="jumlah[]" required="true">
                                                <span class="input-group-addon">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Keterangan<star>*</star></label>
                                            <input type="text" class="form-control"  name="keterangan[]" required="true">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="" class="control-label">&nbsp;</label>
                                            <button type="button" id="add-row-pemasukan-admin" class="btn btn-success btn-fill pull-right">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="tipe[]" id="tipe_admin" />
                                {{-- <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group"> --}}
                                            {{-- <label for="id_" class="control-label">Tipe Transaksi <star>*</star></label>
                                            <select class="form-control select2"  name="tipe" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Transaksi-</option>
                                                <option value="1">Pemasukkan</option>
                                                <option value="0">Pengeluaran</option>
                                            </select> --}}
                                        {{-- </div>
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Transfer </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
@endif

