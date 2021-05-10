{{--Modal Angsuran Pembiayaan--}}
<div class="modal fade" id="angsurPemModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardAng">
            <form id="wizardFormAng" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.angsur_pembiayaan')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('anggota.angsur_pembiayaan')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                @endif
                <div class="header text-center">
                    <h3 class="title">Angsuran Pembiayaan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Ang" data-toggle="tab">Data Angsuran</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Ang">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Pembiayaan <star>*</star></label>
                                        <select class="form-control select2" id="angidRek" name="idRek" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Pembiayaan-</option>
                                            
                                            @foreach ($datasaldoPem as $rekening)
                                                @if(json_decode($rekening->detail,true )['sisa_ang_bln'] > 0)
                                                    @if(json_decode($rekening->detail,true )['sisa_angsuran'] > json_decode($rekening->detail,true)['jumlah_angsuran_bulanan'])
                                                        <option value="{{
                                                            json_decode($rekening->detail,true )['angsuran_pokok'] ." " .
                                                            json_decode($rekening->detail,true )['margin'] . " " .
                                                            json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                            json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                            $rekening->status_angsuran." ".
                                                            $rekening->tagihan_angsuran_sekarang." " .
                                                            (json_decode($rekening->detail,true )['jumlah_margin_bulanan'] + json_decode($rekening->detail,true)['sisa_mar_bln']) . " " .
                                                            $rekening->id_rekening . " " .
                                                            json_decode($rekening->detail,true )['sisa_pinjaman'] . " " .
                                                            $rekening->id_user
                                                        }}">[{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [Sisa Pinjaman : {{number_format(json_decode($rekening->detail,true )['sisa_pinjaman'])}}] [ {{ $rekening->alamat }} ]</option>
                                                    @else
                                                        <option value="{{
                                                            json_decode($rekening->detail,true )['angsuran_pokok'] ." " .
                                                            json_decode($rekening->detail,true )['margin'] . " " .
                                                            json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                            json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                            $rekening->status_angsuran." ".
                                                            $rekening->tagihan_angsuran_sekarang." " .
                                                            (json_decode($rekening->detail,true )['sisa_margin']) . " " .
                                                            $rekening->id_rekening . " " .
                                                            json_decode($rekening->detail,true )['sisa_pinjaman'] . " " .
                                                            $rekening->id_user
                                                        }}">[{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}]  [Sisa Pinjaman : {{number_format(json_decode($rekening->detail,true )['sisa_angsuran'])}}] [ {{ $rekening->alamat }} ]</option>
                                                    @endif
                                                @elseif(json_decode($rekening->detail,true )['kelebihan_angsuran_bulanan'] > 0)
                                                    @if(json_decode($rekening->detail,true )['sisa_angsuran'] > json_decode($rekening->detail,true)['jumlah_angsuran_bulanan'])
                                                        <option value="{{
                                                            json_decode($rekening->detail,true )['angsuran_pokok'] ." " .
                                                            json_decode($rekening->detail,true )['margin'] . " " .
                                                            json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                            json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                            $rekening->status_angsuran." ".
                                                            $rekening->tagihan_angsuran_sekarang." " .
                                                            (json_decode($rekening->detail,true )['jumlah_margin_bulanan'] - json_decode($rekening->detail,true)['kelebihan_margin_bulanan']) . " " . 
                                                            $rekening->id_rekening . " " .
                                                            json_decode($rekening->detail,true )['sisa_pinjaman'] . " " .
                                                            $rekening->id_user
                                                        }}">[{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [Sisa Pinjaman : {{number_format(json_decode($rekening->detail,true )['sisa_pinjaman'])}}] [ {{ $rekening->alamat }} ]</option>
                                                    @else
                                                        <option value="{{
                                                            json_decode($rekening->detail,true )['angsuran_pokok'] ." " .
                                                            json_decode($rekening->detail,true )['margin'] . " " .
                                                            json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                            json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                            $rekening->status_angsuran." ".
                                                            $rekening->tagihan_angsuran_sekarang." " .
                                                            (json_decode($rekening->detail,true )['sisa_margin']) . " " .
                                                            $rekening->id_rekening . " " .
                                                            json_decode($rekening->detail,true )['sisa_pinjaman'] . " " .
                                                            $rekening->id_user
                                                        }}">[{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [Sisa Pinjaman : {{number_format(json_decode($rekening->detail,true )['sisa_angsuran'])}}] [ {{ $rekening->alamat }} ]</option>
                                                    @endif
                                                @else
                                                    @if(json_decode($rekening->detail,true )['sisa_angsuran'] > json_decode($rekening->detail,true)['jumlah_angsuran_bulanan'])
                                                        @if($rekening->jenis_pembiayaan == "PEMBIAYAAN MRB")
                                                        <option value="{{
                                                            json_decode($rekening->detail,true )['angsuran_pokok'] ." " .
                                                            json_decode($rekening->detail,true )['margin'] . " " .
                                                            json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                            json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                            $rekening->status_angsuran." ".
                                                            $rekening->tagihan_angsuran_sekarang ." " .
                                                            json_decode($rekening->detail,true )['sisa_margin'] . " " .
                                                            $rekening->id_rekening . " " .
                                                            json_decode($rekening->detail,true )['sisa_angsuran'] . " " .
                                                            $rekening->id_user. " ".
                                                            $rekening->tagihan_margin_sekarang. " ".
                                                            'MRB'
                                                        }}">[{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [Sisa Pinjaman : {{number_format(json_decode($rekening->detail,true )['sisa_pinjaman'])}}] [ {{ $rekening->alamat }} ]</option>
                                                        @else
                                                            <option value="{{
                                                            json_decode($rekening->detail,true )['angsuran_pokok'] ." " .
                                                            json_decode($rekening->detail,true )['margin'] . " " .
                                                            json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                            json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                            $rekening->status_angsuran." ".
                                                            $rekening->tagihan_angsuran_sekarang. " " .
                                                            json_decode($rekening->detail,true )['jumlah_margin_bulanan'] . " " .
                                                            $rekening->id_rekening . " " .
                                                            json_decode($rekening->detail,true )['sisa_pinjaman'] . " " .
                                                            $rekening->id_user
                                                        }}">[{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [Sisa Pinjaman : {{number_format(json_decode($rekening->detail,true )['sisa_pinjaman'])}}] [ {{ $rekening->alamat }} ]</option>
                                                            @endif
                                                    @else
                                                        <option value="{{
                                                            json_decode($rekening->detail,true )['angsuran_pokok'] ." " .
                                                            json_decode($rekening->detail,true )['margin'] . " " .
                                                            json_decode($rekening->detail,true )['lama_angsuran']." ".
                                                            json_decode($rekening->rekening,true )['jenis_pinjaman']." ".
                                                            $rekening->status_angsuran." ".
                                                            $rekening->tagihan_angsuran_sekarang." " .
                                                            json_decode($rekening->detail,true )['sisa_margin'] . " " .
                                                            $rekening->id_rekening . " " .
                                                            json_decode($rekening->detail,true )['sisa_pinjaman'] . " " .
                                                            $rekening->id_user
                                                        }}">[{{$rekening->id_pembiayaan }}] {{ $rekening->jenis_pembiayaan }} [{{ $rekening->nama }}] [Sisa Pinjaman : {{number_format(json_decode($rekening->detail,true )['sisa_angsuran'])}}] [ {{ $rekening->alamat }} ]</option>
                                                    @endif
                                                @endif
                                            @endforeach

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
                                        <select class="form-control select2" id="debit" name="debit" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="default" disabled>-Pilih jenis angsuran-</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                            <option value="2">Rekening Tabungan</option>
                                            <option value="3">Qris</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="toHideTabungan">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Rekening Tabungan <star>*</star></label>
                                        @if(Auth::user()->tipe == "anggota")
                                        <select class="form-control select2" id="tabungan" name="tabungan" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening Tabungan-</option>
                                            @foreach ($tabungan as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }} [ {{number_format(json_decode($rekening->detail)->saldo,2) }} ] </option>
                                            @endforeach
                                        </select>
                                        @else
                                        <select class="form-control select2" id="tabungan" name="tabungan" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening Tabungan-</option>
                                        </select>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{--<div class="row">--}}
                                {{--<div class="col-md-10 col-md-offset-1">--}}
                                    {{--<div class="form-group">--}}
                                        {{--<label for="namaSim" class="control-label">Jenis Pembayaran Angsuran <star>*</star></label>--}}
                                        {{--<select class="form-control select2" id="pembayaran" name="jenis_bayar" style="width: 100%;" required>--}}
                                            {{--<option class="bs-title-option" selected value="default" disabled>-Pilih jenis pembayaran-</option>--}}
                                            {{--<option value="0">Biaya Angsuran Bulanan [Pokok + Margin]</option>--}}
                                            {{--<option value="1">Biaya Angsuran Pokok</option>--}}
                                            {{--<option value="2">Biaya Margin</option>--}}
                                            {{--<option value="3">Custom Pembayaran</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="row" id="toHideDebBank">
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
                            <div class="row" id="toHideDebBank2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamaDeb" name="atasnama">
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
                                @if(Auth::user()->tipe== "anggota")
                                    <div class="text-center">
                                        <img style="margin: auto;width:100px;height:auto" id="picanggota" src=""/>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <img style="margin: auto;width:100px;height:auto" id="pic" src=""/>
                                    </div>
                                    @endif

                            </div>

                            <div id="divImgQris">
                                <div class="text-center">
                                    <img style="margin: auto;width:200px;height:auto" id="imgQris" src=""/>
                                </div>
                            </div>

                            <div class="row" id="toHideQris">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control select2" id="bankQris" name="bank" style="width: 100%;" >
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                            @foreach ($dropdown6 as $rekening)
                                                @if($rekening->qris)
                                                    <option value="{{ $rekening->id }}" data-qris = " {{asset('storage/public/qris/'.$rekening->qris->path_file)}}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURLQris(this);" id="buktiQris" name="file" accept=".jpg, .png, .jpeg|images/*" />
                                        </span><br><br>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                                @if(Auth::user()->tipe== "anggota")
                                    <div class="text-center">
                                        <img style="margin: auto;width:100px;height:auto" id="picanggota" src=""/>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <img style="margin: auto;width:100px;height:auto" id="picQris" src=""/>
                                    </div>
                                    @endif

                            </div>
                            {{--PEMBAYARAN--}}
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1" >
                                    <div class="form-group">
                                        <label class="control-label">Sisa Tagihan Pokok <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currencyDecimal form-control text-right" id="tagihan_pokok" name="tagihan_pokok" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5" id="sisa_mar">
                                    <div class="form-group">
                                        <label class="control-label">Sisa Tagihan Margin <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currencyDecimal form-control text-right" id="tagihan_margin" name="tagihan_margin" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="row" id="toHideBagi">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Biaya Angsuran Pokok <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currencyDecimal form-control text-right" id="bagi_pokok"  disabled />
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-5" id="bayar_mar_mrb">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Biaya Margin<star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="currency form-control text-right" id="bagi_margin_mrb" name="nisbah" disabled >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1" id="showPok"></div>
                                    <div class="col-md-5 col-md-offset-1" id="angHide">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Bayar Angsuran<star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" @if(Auth::user()->tipe!=='anggota') class="currencyDecimal form-control text-right" @else class="currency form-control text-right"  @endif id="bayar_ang" name="bayar_ang" >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5" id="marginHide">
                                        <div class="form-group">
                                            <label class="control-label">Jumlah Bayar Margin <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" @if(Auth::user()->tipe!=='anggota') class="currencyDecimal form-control text-right" @else class="currency form-control text-right"  @endif  id="bayar_margin" name="bayar_mar" value="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Angsur </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>