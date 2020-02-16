<div class="modal fade" id="donasi" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard">
            <span class="help-block text-danger">{{ $errors->first('file') }}</span>
            {{--@if(session('success'))--}}
            {{--<div class="alert alert-success">--}}
            {{--{{ session('success') }}--}}
            {{--</div>--}}
            {{--@endif--}}


            <div class="card card-wizard" id="wizardCard">
                <form id="wizardForm" method="POST" @if(Auth::user()->tipe =="anggota")action="{{route('donasimaal')}}"@elseif(Auth::user()->tipe !="anggota") action="{{route('teller.donasimaal')}}" @endif enctype="multipart/form-data"">
                    {{csrf_field()}}
                    <div class="header text-center">
                        <h3 class="title">Donasi Kegiatan Maal</h3>
                        <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                    </div>

                    <div class="content">
                        <ul class="nav">
                            <li><a href="#tab1TabTrs" data-toggle="tab">Data Transaksi</a></li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane" id="tab1TabTrs">
                                <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Pilih Rekenng Donasi <star>*</star></label>
                                            <select class="form-control select2"  id="rekdon" name="rekdon" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Jenis Pembayaran-</option>
                                                <option value="0">Rekening Kegiatan Maal</option>
                                                <option value="1">Rekening Waqaf</option>
                                            </select>
                                        </div>
                                        <input type="hidden" id="tipdon" name="tipe_donasi"/>
                                    </div>
                                </div>
                                <div class="row" id="Hide">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Pilih Kegiatan Maal <star>*</star></label>
                                            <select class="form-control select2" id="idRekT" name="kegiatan" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih kegiatan-</option>
                                                @foreach ($kegiatan as $rekening)
                                                    <option value="{{ $rekening->id }}">[{{ $rekening->tanggal_pelaksaaan }}] {{ $rekening->nama_kegiatan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Pilih Jenis Pembayaran <star>*</star></label>
                                            <select class="form-control select2" id="jenis" name="jenis" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Jenis Pembayaran-</option>
                                                <option value="0">Transfer dari Rekening Bank</option>
                                                <option value="1">Transfer dari Rekening Tabungan</option>
                                                <option value="2">Tunai</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="toHideBank">
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
                                <div class="row" id="toHideBank2">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                            <input type="text" class="form-control text-left"  id="atasnamaDeb" name="atasnama" required>
                                        </div>
                                    </div>
                                    <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                        <div class="form-group">
                                            <label>Upload Bukti Transfer <star>*</star></label><br>
                                            {{--<span class="btn btn-info btn-fill btn-file center-block"> Browse--}}
                                                <input type="file" onchange="readURL(this);" id="bukti" name="file" accept=".jpg, .png, .jpeg|images/*" />
                                            {{--</span><br><br>--}}
                                            <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img style="margin: auto;width:200px;height:auto" id="pic" src=""/>
                                    </div>
                                </div>
                                <div class="row" id="RekBank">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                            <select class="form-control select2" id="bank" name="bank" style="width: 100%;" required>
                                                <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                                @foreach ($dropdown6 as $rekening)
                                                    <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="toHideTab">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Transfer dari Rekening <star>*</star></label>
                                            <select class="form-control select2" id="idRekTab" style="width: 100%;" required>
                                                <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan Anda-</option>
                                                @if(Auth::user()->tipe!="anggota")
                                                    @foreach ($dropdown as $rekening)
                                                        <option value="{{ $rekening->id." ".number_format(json_decode($rekening->detail,true)['saldo'],2) }}">[{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }} [{{ $rekening->nama }}]</option>
                                                    @endforeach
                                                @elseif(Auth::user()->tipe=="anggota")
                                                    @foreach ($dropdown as $rekening)
                                                        <option value="{{ $rekening->id ." ".number_format(json_decode($rekening->detail,true)['saldo'],2)}}">[{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <input type="hidden" id="idTab_" name="dari" />
                                        </div>
                                    </div>
                                    <div class="col-md-5 ">
                                        <div class="form-group">
                                            <label for="id_" class="control-label">Saldo Rekening Tabungan <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon">Rp</span>
                                                <input type="text" class="form-control text-right" id="saldo" disabled="true">
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
                        <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Donasi Sekarang </button>
                        <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                        <div class="clearfix"></div>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>