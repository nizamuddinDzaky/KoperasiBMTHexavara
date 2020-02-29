<div class="modal fade" id="donasiKegiatan" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.angsur_pembiayaan')}}" @ENDIF enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif
                <div class="header text-center">
                    <h3 class="title">Donasi Kegiatan </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabKegiatan" data-toggle="tab">Data Donasi Kegiatan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabKegiatan">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            
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
                                            <option class="bs-title-option" selected value="-1" disabled>-Pilih jenis pembayaran-</option>
                                            <option value="0">Tunai</option>
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
                                        <select class="form-control select2" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            <option value="">001-Mudharabah-Rp.2,000,000</option>
                                            <option value="">002-Mudharabah-Rp.4,000,000</option>
                                            <option value="">003-Mudharabah-Rp.50,000,000</option>
                                            {{-- @foreach ($datasaldoPem as $rekening)
                                                <option value="{{
                                                json_decode($rekening->detail,true )['angsuran_pokok']." ".
                                                json_decode($rekening->detail,true )['margin']." ".
                                                json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                $rekening->status_angsuran." ".
                                                json_decode($rekening->detail,true )['sisa_ang_bln']." ".
                                                json_decode($rekening->detail,true )['sisa_mar_bln']
                                                }}"> [{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [{{ $rekening->no_ktp }}]</option>
                                            @endforeach --}}
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
                                    <img style="margin: auto;width:100px;height:auto" id="pic" src=""/>
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
