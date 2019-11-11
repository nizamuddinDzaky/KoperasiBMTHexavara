{{--Modal Tutup Tabungan--}}
<div class="modal fade" id="tutupTabModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardClose">
            <form id="wizardFormClose" method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.konfirmasi.tutup')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.konfirmasi.tutup')}}" @else action="{{route('anggota.tutup_tabungan')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                    <input type="hidden" id="jumlahCKAc" name="jumlahCK">
                @endif
                <div class="header text-center">
                    <h3 class="title">Tutup Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabclose" data-toggle="tab">Data Nasabah</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tabclose">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="idRekC" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @if(Auth::user()->tipe=="teller")
                                                @foreach ($tabactive as $rekening)
                                                    <option value="{{ (number_format(json_decode($rekening->detail,true )['saldo']))}}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}  [{{$rekening->nama}}]  [{{$rekening->no_ktp}}]</option>
                                             @endforeach
                                            @endif
                                            <input type="hidden" id="idRekCls" name="id_">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pencairan <star>*</star></label>
                                        <select class="form-control select2" id="jeniscls" name="jenis" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideBankcls">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankCls" name="daribank" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankcls" name="nobank" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideBank2cls">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamacls" name="atasnama" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="toHidecls">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id=bankcls name="bank" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" id="bukticls" name="file" accept=".jpg, .png, .jpeg|images/*" />
                                        </span><br><br>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="piccls" src=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Total Saldo Saat ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="jumlahcls"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Tutup </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Kredit Tabungan--}}
<div class="modal fade" id="kreditTabModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDeb">
            <form id="wizardFormDeb" method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.konfirmasi.debit')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.konfirmasi.debit')}}" @else action="{{route('anggota.debit_tabungan')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                    <input type="hidden" id="jumlahCKA" name="jumlahCK">
                @endif
                <div class="header text-center">
                    <h3 class="title">Setor Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabDeb" data-toggle="tab">Data Setoran</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabDeb">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="idRek" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @if(Auth::user()->tipe=="anggota")
                                                @foreach ($data as $rekening)
                                                    <option value="{{ isset($rekening['id'])?$rekening['id']:0 }}"> [{{isset($rekening['id_tabungan'])?$rekening['id_tabungan']:0 }}] {{isset($rekening['jenis_tabungan'])?$rekening['jenis_tabungan']:0  }}</option>
                                                @endforeach
                                            @else
                                                @foreach ($tabactive as $rekening)
                                                    <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}  [{{$rekening->nama}}] [{{$rekening->no_ktp}}]</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="debit" name="debit" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebBank">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankDeb" name="daribank" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankDeb" name="nobank" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebBank2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaDeb" name="atasnama" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="toHideDeb">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id=bank name="bank" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" id="bukti" name="file" accept=".jpg, .png, .jpeg|images/*" />
                                        </span><br><br>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="pic" src=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vjumlah" name="jumlah" required="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Setor </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal View Kredit Tabungan--}}
<div class="modal fade" id="viewKreModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDebv">
            <form id="wizardFormDebv" method="POST" action="#" enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Setoran Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabDebv" data-toggle="tab">Data Setoran</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabDebv">
                            <h5 class="text-center">Detail Setoran Nasabah</h5>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">ID Nasabah <star>*</star></label>
                                        <input type="text" class="form-control" id="vdebktp" name="jumlah"  disabled>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nama Nasabah <star>*</star></label>
                                        <input type="text" class="form-control" id="vdebnama" name="jumlah"  disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="vRekDeb" name="idRek" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tab as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}  [{{$rekening->no_ktp}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="vdebitdeb" name="debit" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebBankv">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="vbankDeb" name="daribank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="vnobankDeb" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebBank2v">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="vatasnamaDeb" name="atasnama" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="toHideDebv">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id=vbankdeb name="bank" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Bukti Transfer <star>*</star></label><br>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="picDeb" src=""/>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vjumlahdeb" name="jumlah"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Active Kredit Tabungan--}}
<div class="modal fade" id="activeKreModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDeba">
            <form id="wizardFormDeba" method="POST" action="#" enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Setoran Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabDeba" data-toggle="tab">Data Setoran</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabDeba">
                            <h5 class="text-center">Detail Setoran Nasabah</h5>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">ID Nasabah <star>*</star></label>
                                        <input type="text" class="form-control" id="adebktp" name="jumlah"  disabled>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nama Nasabah <star>*</star></label>
                                        <input type="text" class="form-control" id="adebnama" name="jumlah"  disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="aRekDeb" name="idRek" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tab as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}  [{{$rekening->no_ktp}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="adebitdeb" name="debit" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebBanka">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="abankDeb" name="daribank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="anobankDeb" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebBank2a">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="aatasnamaDeb" name="atasnama" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDeba">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Rekening BANK <star>*</star></label>
                                        <select class="form-control select2" id=abankdeb name="bank" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Bukti Transfer <star>*</star></label><br>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="picDeba" src=""/>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="ajumlahdeb" name="jumlah"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Setor </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal Konfirmasi Kredit Tabungan--}}
<div class="modal fade" id="confirmKreModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDebc">
            <form id="wizardFormDebc" method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.konfirmasi.debit')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.konfirmasi.debit')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="iduser" name="iduser">
                <input type="hidden" id="idconfirm" name="id">
                <input type="hidden" id="idtab" name="idtab">
                <div class="header text-center">
                    <h3 class="title">Konfirmasi Setoran Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabC" data-toggle="tab">Data Setoran</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabC">
                            <h5 class="text-center">Konfirmasi Pembayaran Setoran</h5>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">ID Nasabah <star>*</star></label>
                                        <input type="text" class="form-control" id="cdebktp" name="jumlah"  disabled>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nama Nasabah <star>*</star></label>
                                        <input type="text" class="form-control" id="cdebnama" name="jumlah"  disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="cRekDeb" name="idRek" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tab as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}  [{{$rekening->no_ktp}}]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="cdebitdeb" name="debit" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebBankc">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="cbankDeb" name="daribank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="cnobankDeb" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebBank2c">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="catasnamaDeb" name="atasnama" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebc" >
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id=cbankdeb name="bank" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Bukti Transfer <star>*</star></label><br>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="cpicDeb" src=""/>
                                </div>


                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="cjumlahdeb" name="jumlah"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Konfirmasi </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Debit Tabungan--}}
<div class="modal fade" id="debitTabModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardKre">
            <form id="wizardFormKre" method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.konfirmasi.kredit')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.konfirmasi.kredit')}}" @else action="{{route('anggota.kredit_tabungan')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                <input type="hidden" name="idcKre" value="CK">
                <input type="hidden" name="teller" value="teller">
                <input type="hidden" id="jumlahCKA" name="jumlahCK">
                @endif
                <div class="header text-center">
                    <h3 class="title">Tarik Tabungan </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabKre" data-toggle="tab">Detail Penarikan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabKre">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="kreidRek" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabactive as $rekening)
                                                <option value="{{ (json_decode($rekening->detail,true )['saldo'])}}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}  [{{$rekening->nama}}]  [{{$rekening->no_ktp}}]</option>
                                            @endforeach
                                            <input type="hidden" id="idRekKR" name="id_">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="kredit" name="kredit" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideKre">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankKre" name="bank" >
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK user <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankKre" name="nobank" >
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideKre2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaKre" name="atasnama" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Total Saldo Saat ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="krejumlah"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="saldo_kre" name="jumlah" required="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(Auth::user()->tipe!="anggota")
                                <div class="row" id="toHideKreBankA">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label class="control-label">Transfer dari Rekening" ?<star>*</star></label>
                                            <select id="daribank2" name="daribank" class="form-control" required="true">
                                                <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                                @foreach ($dropdown6 as $rekening)
                                                    <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="toHideKreTellA">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label class="control-label">Pilih Rekening Teller<star>*</star></label>
                                            <select  id="dariteller2" name="dariteller" class="form-control" required="true">
                                                <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                                @foreach ($dropdown7 as $rekening)
                                                    <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Tarik </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal View Debit Tabungan--}}
<div class="modal fade" id="viewDebModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardKrev">
            <form id="wizardFormKrev" method="POST" action="{{route('anggota.kredit_tabungan')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Tarik Tabungan </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabKrev" data-toggle="tab">Detail Penarikan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabKrev">
                            <h5 class="text-center">Detail Penarikan</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="vRekKre" name="idRek" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($datasaldo as $rekening)
                                                <option value="{{ $rekening->id}}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}  [{{$rekening->no_ktp}}]</option>
                                            @endforeach
                                            <input type="hidden" id="idRekKR" name="id_">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="vkredit" name="kredit" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideKrev">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User<star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="vbankKre" name="bank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK user <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="vnobankKre" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideKre2v">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="vatasnamaKre" name="atasnama" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Total Saldo Saat ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vsaldo_kre"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vjumlahKre" name="jumlah" disabled="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Simpanan Wajib--}}
<div class="modal fade" id="simpWajibModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardW">
            <form id="wizardFormW" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('simpanan_wajib')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.simpanan_wajib')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe=="admin" ||Auth::user()->tipe=="teller")
                    <input type="hidden" name="teller" value="teller"/>
                @endif
                <div class="header text-center">
                    <h3 class="title">Bayar Simpanan Wajib</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabW" data-toggle="tab">Data Simpanan Wajib</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabW">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>

                            <div class="row" id="toHideNasabah2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pilih Nasabah<star>*</star></label>
                                        <select class="form-control select2" id="nasabah_wajib" name="nama_nasabah" style="width: 100%;" required>
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown8 as $usr)
                                                <option value="{{ $usr->id." ".number_format(json_decode($usr->wajib_pokok,true)['wajib'])." ".$usr->nama }}">  [{{$usr->no_ktp}}] {{ $usr->nama }}</option>
                                            @endforeach

                                        </select>
                                        <input type="hidden" id="idRekW" name="id_">
                                        <input type="hidden" id="NamaW" name="nama">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="jwajib" name="jenis" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideW">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankW" name="daribank" >
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK user <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankW" name="nobank" >
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideW2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaW" name="atasnama" >
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideWB">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id="bankrek" name="bank" style="width: 100%;">
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" id="buktiW" name="file" accept=".jpg, .png, .jpeg|images/*" />
                                        </span><br><br>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="picw" src=""/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Total Simpanan Wajib <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="total_wajib"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="jumlah" name="jumlah" required="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Bayar Simpanan </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal View Simpanan Wajib--}}
<div class="modal fade" id="viewSimModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardWS">
            <form id="wizardFormWS" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('simpanan_wajib')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.simpanan_wajib')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Bayar Simpanan Wajib</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#vtab1TabW" data-toggle="tab">Data Simpanan Wajib</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="vtab1TabW">

                            @if(Auth::user()->tipe!="anggota")
                            <div class="row" id="vtoHideNasabah2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pilih Nasabah<star>*</star></label>
                                        <select class="form-control select2" id="vnasabah_wajib" name="nama_nasabah" style="width: 100%;" disabled>
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown8 as $usr)
                                                <option value="{{ $usr->id }}">  [{{$usr->no_ktp}}] {{ $usr->nama }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="vjwajib" name="jenis" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="vtoHideW">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="vbankW" name="daribank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK user <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="vnobankW" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="vtoHideW2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="vatasnamaW" name="atasnama" disabled >
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="vtoHideWB">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id="vbankrek" name="bank" style="width: 100%;" disabled>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Bukti Transfer <star>*</star></label><br>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="vpicw" src=""/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Bayar <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vjumlahW" name="jumlah" disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Konfirmasi Debit Tabungan--}}
<div class="modal fade" id="confirmDebModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardKrec">
            <form id="wizardFormKrec" method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.konfirmasi.kredit')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.konfirmasi.kredit')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="idcKre" value="CK">
                <input type="hidden" id="jumlahCK" name="jumlahCK">
                <input type="hidden" id="idconfirmKre" name="id">
                <input type="hidden" id="idtabKre" name="idtab">
                <div class="header text-center">
                    <h3 class="title">Konfirmasi Penarikan Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabKreC" data-toggle="tab">Detail Penarikan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabKreC">
                            <h5 class="text-center">Konfirmasi Pembayaran Penarikan</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="cRekKre" name="idRek" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($datasaldo as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}  [{{$rekening->no_ktp}}]</option>
                                            @endforeach
                                            <input type="hidden" id="idRekKR" name="id_">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="ckredit" name="kredit" style="width: 100%;" disabled>
                                            <option class="bs-title-option" value="" disabled selected>-Pilih jenis Transaksi-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideKrec">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User<star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="cbankKre" name="bank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK  user<star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="cnobankKre" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideKre2c">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="catasnamaKre" name="atasnama" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Total Saldo Saat ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="csaldo_kre"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                        <span class="help-block">
                                              <strong id="warning" class="text-danger"></strong>
                                      </span>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="cjumlahKre" name="jumlah" disabled="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideKreBank">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Transfer dari Rekening" ?<star>*</star></label>
                                        <select id="daribank" name="daribank" class="form-control" required="true">
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideKreTell">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pilih Rekening Teller<star>*</star></label>
                                        <select  id="dariteller" name="dariteller" class="form-control" required="true">
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown7 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right" id="submit_kredit" disabled>Konfirmasi </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>


{{--Modal Transfer Tabungan--}}
<div class="modal fade" id="transferTabModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardTrans">
            <form id="wizardFormTrans" method="POST" action="{{route('anggota.debit_tabungan')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Transfer Antar Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabTrs" data-toggle="tab">Data Rekening Tujuan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabTrs">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Nasabah <star>*</star></label>
                                        <select class="form-control select2" id="idUsrT" name="idUsrRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Nasabah-</option>
                                            @foreach ($dropdown4 as $usr)
                                                <option value="{{ $usr->id }}"> {{$usr->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Tabungan Tujuan <star>*</star></label>
                                        <select class="form-control select2" id="idRekT" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($dropdown5 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}][{{$rekening->nama }}] {{ $rekening->jenis_tabungan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Transfer dari Rekening Tabungan? <star>*</star></label>
                                        <select class="form-control select2" id="idRekT" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan Anda-</option>
                                            @foreach ($dropdown3 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="tjumlah" name="jumlah" required="true">
                                            <span class="input-group-addon">.00</span>
                                        </div>
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

{{--Modal View Transfer Tabungan--}}
<div class="modal fade" id="viewTransModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDebv">
            <form id="wizardFormDebv" method="POST" action="{{route('anggota.debit_tabungan')}}" enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Tarik Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabDebv" data-toggle="tab">Detail Penarikan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabDebv">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Nasabah <star>*</star></label>
                                        <select class="form-control select2" id="vRekDeb" name="idRek" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($data as $rekening)
                                                <option value="{{ isset($rekening['id'])?$rekening['id']:0 }}"> [{{isset($rekening['id_tabungan'])?$rekening['id_tabungan']:0 }}] {{isset($rekening['jenis_tabungan'])?$rekening['jenis_tabungan']:0  }}</option>
                                                {{--<option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}</option>--}}
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control select2" id="vdebitdeb" name="debit" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="Tunai">Tunai</option>
                                            <option value="Transfer">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideDebv">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Rekening BANK <star>*</star></label>
                                        <select class="form-control select2" id=vtbankdeb name="bank" style="width: 100%;" disabled>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Bukti Transfer <star>*</star></label><br>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="picDebTrans" src=""/>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vjumlahdeb" name="jumlah"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

