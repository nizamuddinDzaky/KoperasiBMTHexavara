{{--Modal Pelunasan Lebih Awal Pembiayaan--}}
<div class="modal fade" id="pelunasanLebihAwalPembiayaanModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardPelunasanLebihAwal">
            <form id="wizardFormPelunasanLebihAwal" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.pelunasan_pembiayaan')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.pelunasan_pembiayaan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif
                <div class="header text-center">
                    <h3 class="title">Pelunasan Pembiayaan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1PelunasanLebihAwal" data-toggle="tab">Data Pembiayaan</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab1PelunasanLebihAwal">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <input type="hidden" id="idPembiayaan" name="id_pembiayaan">
                            <input type="hidden" id="tipe_user" name="tipe_user" value={{ Auth::user()->tipe }}>
                            
                            @if(Auth::user()->tipe == "teller")
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Anggota <star>*</star></label>
                                        <select class="form-control select2" id="user_pelunasan" name="user" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Pembiayaan-</option>
                                            
                                            @foreach ($user as $item)
                                            <option value="{{ $item->id }}">[ {{ $item->id }} ] {{ $item->nama}}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Pembiayaan <star>*</star></label>
                                        <select class="form-control select2" id="idRekPelunasan" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Pembiayaan-</option>
                                            
                                            @if(Auth::user()->tipe == "anggota")
                                                @foreach ($datasaldoPem as $rekening)
                                                    <option value="{{
                                                        json_decode($rekening->detail,true )['sisa_angsuran'] ." " .
                                                        json_decode($rekening->detail,true )['sisa_margin'] . " " .
                                                        json_decode($rekening->detail,true )['jumlah_margin_bulanan'] . " " .
                                                        json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                        $rekening->status_angsuran." ".
                                                        $rekening->id_rekening . " " . 
                                                        $rekening->id_pembiayaan
                                                    }}">[{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [{{ $rekening->no_ktp }}]</option>
                                                @endforeach
                                            @endif

                                            <input type="hidden" id="idRekA" name="id_">
                                            <input type="hidden" id="pokok_" name="pokok_">
                                            <input type="hidden" id="jumlah_" name="jumlah_">
                                            <input type="hidden" id="jenis_" name="jenis_">
                                            <input type="hidden" id="tipe_" name="tipe_">
                                            <input type="hidden" id="min_" name="min_">
                                            <input type="hidden" id="sisa_mar_" name="sisa_mar">
                                            <input type="hidden" id="sisa_ang_" name="sisa_ang">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Angsuran <star>*</star></label>
                                        <select class="form-control select2" id="debitPelunasan" name="debit" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="default" disabled>-Pilih jenis angsuran-</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                            <option value="2">Tabungan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="toHideTabunganPelunasan">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Rekening Tabungan <star>*</star></label>
                                        <select class="form-control select2" id="tabunganPelunasan" name="tabungan" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabungan as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }} [ {{number_format(json_decode($rekening->detail)->saldo,2) }} ] </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row toHideBankTransfer">
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
                            <div class="row toHideBankTransfer">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaDeb" name="atasnama">
                                    </div>
                                </div>
                            </div>
                            <div class="row toHideBankTransfer">
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
                            {{--PEMBAYARAN--}}
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1" >
                                    <div class="form-group">
                                        <label class="control-label">Sisa Tagihan Pokok <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="tagihan_pokok_pelunasan" name="tagihan_pokok" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5" id="sisa_mar">
                                    <div class="form-group">
                                        <label class="control-label">Sisa Tagihan Margin<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="tagihan_margin_pelunasan" name="tagihan_margin" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row">
                                    {{-- <div class="col-md-5 col-md-offset-1" id="showPok"></div> --}}
                                    <div class="col-md-5 col-md-offset-1" id="angHide">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Bayar Angsuran Pokok<star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right" id="bayar_ang_pelunasan" name="bayar_ang" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5" id="marginHide">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Bayar Margin <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right"  id="bayar_margin_pelunasan" name="bayar_mar">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Lunasi </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>