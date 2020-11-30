<div class="modal fade" id="pengajuanSimpananWajib" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.simpanan.bayar')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.penyimpanan.pengajuan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif

                <input type="hidden" name="id_rekening_simpanan" value="119">
                <input type="hidden" name="jenis_pengajuan" value="Simpanan Wajib">

                <div class="header text-center">
                    <h3 class="title">Setor Simpanan Wajib </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabSimwaTeller" data-toggle="tab">Data Simpanan Wajib</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabSimwaTeller">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih User <star>*</star></label>
                                        <select class="form-control select2 donatur" name="user" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nominal <star>*</star></label>
                                        <input type="text" class="form-control text-left currencyDecimal"  id="nominal" name="nominal" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pembayaran <star>*</star></label>
                                        <select class="form-control opsi-pembayaran" id="debit" name="debit" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="-1" disabled>-Pilih jenis pembayaran-</option>
                                            @if(auth::user()->tipe != "anggota")
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
                                        <select class="form-control select2 rekening-tabungan" name="dari_tabungan" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
{{--                                            @foreach ($tabungan as $rekening)--}}
{{--                                            <option value="{{ $rekening->id_tabungan }}">[ {{ $rekening->id_tabungan }} ] {{ $rekening->jenis_tabungan }} [ {{ json_decode($rekening->user_detail)->nama }} ]</option>--}}
{{--                                            @endforeach--}}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankDeb" name="daribank">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankDeb" name="nobank">
                                    </div>
                                </div>
                            </div>
                            <div class="row opsi-transfer hide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaDeb" name="atasnama">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id=bank name="bank" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach($bank_bmt as $bank)
                                            <option value={{ $bank->id }}>{{ $bank->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }} col-sm-offset-1">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" id="bukti" name="file" accept=".jpg, .png, .jpeg|images/*" />
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

<div class="modal fade" id="pengajuanSimpananKhusus" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.simpanan.bayar')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.penyimpanan.pengajuan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif

                <input type="hidden" name="id_rekening_simpanan" value="120">
                <input type="hidden" name="jenis_pengajuan" value="Simpanan Khusus">

                <div class="header text-center">
                    <h3 class="title">Setor Simpanan Khusus </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabSimsusTeller" data-toggle="tab">Data Simpanan Khusus</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabSimsusTeller">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih User <star>*</star></label>
                                        <select class="form-control select2 donatur " name="user" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nominal <star>*</star></label>
                                        <input type="text" class="form-control text-left currencyDecimal"  id="nominal" name="nominal" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pembayaran <star>*</star></label>
                                        <select class="form-control opsi-pembayaran" id="debit" name="debit" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="-1" disabled>-Pilih jenis pembayaran-</option>
                                            @if(auth::user()->tipe != "anggota")
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
                                        <select class="form-control select2 rekening-tabungan" name="dari_tabungan" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
{{--                                            @foreach ($tabungan as $rekening)--}}
{{--                                            <option value="{{ $rekening->id_tabungan }}">[ {{ $rekening->id_tabungan }} ] {{ $rekening->jenis_tabungan }} [ {{ json_decode($rekening->user_detail)->nama }} ]</option>--}}
{{--                                            @endforeach--}}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankDeb" name="daribank">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankDeb" name="nobank">
                                    </div>
                                </div>
                            </div>
                            <div class="row opsi-transfer hide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaDeb" name="atasnama">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id=bank name="bank" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach($bank_bmt as $bank)
                                            <option value={{ $bank->id }}>{{ $bank->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }} col-sm-offset-1">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" id="bukti" name="file" accept=".jpg, .png, .jpeg|images/*" />
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


<div class="modal fade" id="pengajuanSimpananPokok" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.simpanan.bayar')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.penyimpanan.pengajuan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif

                <input type="hidden" name="id_rekening_simpanan" value="117">
                <input type="hidden" name="jenis_pengajuan" value="Simpanan Pokok">

                <div class="header text-center">
                    <h3 class="title">Setor Simpanan Pokok </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabSimpokTeller" data-toggle="tab">Data Simpanan Pokok</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabSimpokTeller">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih User <star>*</star></label>
                                        <select class="form-control select2" name="user" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nominal <star>*</star></label>
                                        <input type="text" class="form-control text-left currency"  id="nominal" name="nominal" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pembayaran <star>*</star></label>
                                        <select class="form-control opsi-pembayaran" id="debit" name="debit" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="-1" disabled>-Pilih jenis pembayaran-</option>
                                            @if(auth::user()->tipe != "anggota")
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
                                        <select class="form-control select2" name="dari_tabungan" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabungan as $rekening)
                                            <option value="{{ $rekening->id_tabungan }}">[ {{ $rekening->id_tabungan }} ] {{ $rekening->jenis_tabungan }} [ {{ json_decode($rekening->user_detail)->nama }} ]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankDeb" name="daribank">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankDeb" name="nobank">
                                    </div>
                                </div>
                            </div>
                            <div class="row opsi-transfer hide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaDeb" name="atasnama">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id=bank name="bank" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach($bank_bmt as $bank)
                                            <option value={{ $bank->id }}>{{ $bank->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }} col-sm-offset-1">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" id="bukti" name="file" accept=".jpg, .png, .jpeg|images/*" />
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