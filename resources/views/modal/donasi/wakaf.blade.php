<div class="modal fade" id="donasiWakaf" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.donasi.pay')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('donasimaal')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif
                <div class="header text-center">
                    <h3 class="title">Donasi Wakaf </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabWakaf" data-toggle="tab">Data Wakaf</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabWakaf">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            
                            <input type="hidden" name="id_donasi" id="id_donasi">
                            <input type="hidden" name="jenis_donasi" id="jenis_donasi" value="wakaf">

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nominal <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="nominal" name="nominal" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pembayaran <star>*</star></label>
                                        <select class="form-control opsi-pembayaran" id="debit" name="debit" style="width: 100%;" required>
                                            <option selected value="-1" disabled>-Pilih jenis pembayaran-</option>
                                            @if(Auth::user()->tipe != "anggota")
                                            <option value="0">Tunai</option>
                                            @endif
                                            <option value="1">Transfer</option>
                                            <option value="2">Rekening Tabungan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-tabungan hide">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" name="rekening" style="width: 100%;">
                                            <option selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabungan as $tabungan)
                                                <option value="{{ $tabungan->id_tabungan }}">[{{ $tabungan->id_tabungan }}] {{ $tabungan->jenis_tabungan }} [ Rp. {{ number_format(json_decode($tabungan->detail)->saldo) }} ]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nama Bank <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="nama_bank" name="nama_bank">
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atas_nama" name="atas_nama">
                                    </div>
                                </div>
                            </div>
                            <div class="row opsi-transfer hide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nomor Rekening <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="nomor_rekening" name="nomor_rekening">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Bank Tujuan Transfer <star>*</star></label>
                                        <select class="form-control select2" name="bank_tujuan" style="width: 100%;" required>
                                            <option selected disabled>-Pilih Bank Tujuan-</option>
                                            @foreach ($bank_bmt as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row opsi-transfer hide">
                                <div class="col-sm-12 col-md-5 col-lg-5 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" class="bukti" name="file" accept=".jpg, .png, .jpeg|images/*">
                                        </span><br><br>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" class="pic" src=""/>
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
