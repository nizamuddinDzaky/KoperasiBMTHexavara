{{-- Konfirmasi pengajuan simpanan wajib --}}
<div class="modal fade" id="confirmSimpananWajibModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.simpanan.confirm')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.penyimpanan.pengajuan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif

                <input type="hidden" name="id_rekening_simpanan" value="119">
                <input type="hidden" name="jenis_pengajuan" value="Simpanan Wajib">
                <input type="hidden" name="id_pengajuan" class="id_pengajuan">
            
                <div class="header text-center">
                    <h3 class="title">Simpanan Wajib </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabSimwa" data-toggle="tab">Data Simpanan Wajib</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabSimwa">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            
                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nominal <star>*</star></label>
                                        <input type="text" class="form-control text-left nominal" name="nominal" required disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pembayaran <star>*</star></label>
                                        <select class="form-control opsi-pembayaran" id="pembayaran" name="debit" style="width: 100%;" required disabled>
                                            <option class="bs-title-option" value="-1" disabled>-Pilih jenis pembayaran-</option>
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
                                        <select class="form-control" name="dari_tabungan" id="rekening_confirm_wajib" style="width: 100%;" disabled>
                                            <option class="bs-title-option" disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabungan as $rekening)
                                            <option value="{{ $rekening->id_tabungan }}">[ {{ $rekening->id_tabungan }} ] {{ $rekening->jenis_tabungan }} [ {{ json_decode($rekening->detail)->saldo }} ]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left namabank" name="daribank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left nobank" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row opsi-transfer hide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left atasnamabank" name="atasnama" disabled>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control banksimpananwajib" name="bank" style="width: 100%;" disabled>
                                            <option class="bs-title-option" value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach($bank_bmt as $bank)
                                            <option value={{ $bank->id }}>{{ $bank->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
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

{{-- Konfirmasi pengajuan simpanan pokok --}}
<div class="modal fade" id="confirmSimpananPokokModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.simpanan.confirm')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.penyimpanan.pengajuan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif

                <input type="hidden" name="id_rekening_simpanan" value="117">
                <input type="hidden" name="jenis_pengajuan" value="Simpanan Pokok">
                <input type="hidden" name="id_pengajuan" class="id_pengajuan">
            
                <div class="header text-center">
                    <h3 class="title">Simpanan Pokok </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabSimpok" data-toggle="tab">Data Simpanan Pokok</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabSimpok">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            
                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nominal <star>*</star></label>
                                        <input type="text" class="form-control text-left nominal" name="nominal" required disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pembayaran <star>*</star></label>
                                        <select class="form-control opsi-pembayaran" id="pembayaran" name="debit" style="width: 100%;" required disabled>
                                            <option class="bs-title-option" value="-1" disabled>-Pilih jenis pembayaran-</option>
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
                                        <select class="form-control" name="dari_tabungan" style="width: 100%;" disabled>
                                            <option class="bs-title-option" disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabungan as $rekening)
                                            <option value="{{ $rekening->id_tabungan }}">[ {{ $rekening->id_tabungan }} ] {{ $rekening->jenis_tabungan }} [ {{ json_decode($rekening->detail)->saldo }} ]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left namabank" name="daribank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left nobank" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row opsi-transfer hide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left atasnamabank" name="atasnama" disabled>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control banksimpananwajib" name="bank" style="width: 100%;" disabled>
                                            <option class="bs-title-option" value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach($bank_bmt as $bank)
                                            <option value={{ $bank->id }}>{{ $bank->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
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

{{-- Konfirmasi pengajuan simpanan khusus --}}
<div class="modal fade" id="confirmSimpananKhususModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.simpanan.confirm')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.penyimpanan.pengajuan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif

                <input type="hidden" name="id_rekening_simpanan" value="120">
                <input type="hidden" name="jenis_pengajuan" value="Simpanan Khusus">
                <input type="hidden" name="id_pengajuan" class="id_pengajuan">
            
                <div class="header text-center">
                    <h3 class="title">Simpanan Khusus </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabSimsus" data-toggle="tab">Data Simpanan Khusus</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabSimsus">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            
                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nominal <star>*</star></label>
                                        <input type="text" class="form-control text-left nominal" name="nominal" required disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pembayaran <star>*</star></label>
                                        <select class="form-control opsi-pembayaran" id="pembayaran" name="debit" style="width: 100%;" required disabled>
                                            <option class="bs-title-option" value="-1" disabled>-Pilih jenis pembayaran-</option>
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
                                        <select class="form-control" name="dari_tabungan" id="rekening_confirm_khusus" style="width: 100%;" disabled>
                                            <option class="bs-title-option"  disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabungan as $rekening)
                                            <option value="{{ $rekening->id_tabungan }}">[ {{ $rekening->id_tabungan }} ] {{ $rekening->jenis_tabungan }} [ {{ json_decode($rekening->detail)->saldo }} ]</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left namabank" name="daribank" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left nobank" name="nobank" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="row opsi-transfer hide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left atasnamabank" name="atasnama" disabled>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control banksimpananwajib" name="bank" style="width: 100%;" disabled>
                                            <option class="bs-title-option" value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach($bank_bmt as $bank)
                                            <option value={{ $bank->id }}>{{ $bank->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
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