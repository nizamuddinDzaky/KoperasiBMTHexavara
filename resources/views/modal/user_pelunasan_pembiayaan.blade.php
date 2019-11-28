{{--Modal Angsuran Pembiayaan--}}
<div class="modal fade" id="angsurPelunasanModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardAngPelunasan">
            <form id="wizardFormAngPelunasan" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.angsur_pembiayaan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif
                <div class="header text-center">
                    <h3 class="title">Angsuran Pembiayaan </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1AngPelunasan" data-toggle="tab">Data Angsuran</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1AngPelunasan">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Pembiayaan <star>*</star></label>
                                        <select class="form-control select2" id="angidRekPelunasan" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Pembiayaan-</option>
                                            @foreach ($datasaldoPem as $rekening)
                                                <option value="{{
                                                json_decode($rekening->detail,true )['angsuran_pokok']." ".
                                                json_decode($rekening->detail,true )['margin']." ".
                                                json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                $rekening->status_angsuran." ".
                                                json_decode($rekening->detail,true )['sisa_ang_bln']." ".
                                                json_decode($rekening->detail,true )['sisa_mar_bln']." ".
                                                json_decode($rekening->detail,true )['sisa_angsuran']." ".
                                                json_decode($rekening->detail,true )['pinjaman']
                                                }}"> [{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [{{ $rekening->no_ktp }}]
                                                </option>
                                            @endforeach
                                            <input type="hidden" id="idRekAPelunasan" name="id_">
                                            <input type="hidden" id="pokok_pelunasan" name="pokok_">
                                            <input type="hidden" id="jumlah_pelunasan" name="jumlah_">
                                            <input type="hidden" id="jenis_pelunasan" name="jenis_">
                                            <input type="hidden" id="tipe_pelunasan" name="tipe_">
                                            <input type="hidden" id="min_pelunasan" name="min_">
                                            <input type="hidden" id="sisa_mar_pelunasan" name="sisa_mar">
                                            <input type="hidden" id="sisa_ang_pelunasan" name="sisa_ang">
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
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="debitPelunasanTransferForm">
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                            <input type="text" class="form-control text-left"  id="bankDebPelunasan" name="daribank" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 ">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                            <input type="number" class="form-control text-left"  id="nobankDebPelunasan" name="nobank" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                            <input type="text" class="form-control text-left"  id="atasnamaDebPelunasan" name="atasnama" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                            <select class="form-control select2" id="bankPelunasan" name="bank" style="width: 100%;" >
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
                            </div>
                            {{--PEMBAYARAN--}}
                            <div id="pembayaranAngsuran">
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1" >
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Biaya Peminjaman<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="total_peminjaman" name="total_peminjaman" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5" >
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Biaya Margin per /bln<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="biaya_margin_per_bulan" name="tagihan_pokok" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-md-5 col-md-offset-1" id="showPok"></div> -->
                                <div class="col-md-5 col-md-offset-1" id="angHide">
                                    <div class="form-group">
                                        <label class="control-label">Pembayaran Sisa Tagihan Angsuran Pokok<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="bayar_ang_pelunasan" name="bayar_ang" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5" id="marginHide">
                                    <div class="form-group">
                                        <label class="control-label">Pembayaran Sisa Tagihan Margin 2 Bulan<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="bayar_mar_pelunasan" name="bayar_mar">
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