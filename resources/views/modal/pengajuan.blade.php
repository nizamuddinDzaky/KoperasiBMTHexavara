{{--Modal Tutup Tabungan--}}
<div class="modal fade" id="tutupTabModal2" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardClose2">
            <form id="wizardFormClose2" method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.konfirmasi.tutup')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.konfirmasi.tutup')}}" @else action="{{route('anggota.tutup_tabungan')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                @if(Auth::user()->tipe!="anggota")
                    <input type="hidden" name="teller" value="teller">
                    <input type="hidden" id="jumlahCKAc2" name="jumlahCK">
                @endif
                <div class="header text-center">
                    <h3 class="title">Tutup Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabclose2" data-toggle="tab">Data Anggota</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabclose2">
                            <h5 class="text-center">Pastikan kembali data yandg anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Pilih Rekening Tabungan <star>*</star></label>
                                        <input type="text" class="form-control text-left" id="idRekC2" name="idRek" value="" disabled="">
                                        <input type="hidden" id="idRekCls2" name="id_">
                                        <input type="hidden" id="jumlahClse2" name="jumlah">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Pencairan <star>*</star></label>
                                        <select class="form-control" id="jeniscls2" name="jenis" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Transaksi</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideBankcls2">
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="bankCls2" name="daribank" required>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                        <input type="number" class="form-control text-left"  id="nobankcls2" name="nobank" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHideBank2cls2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="atasnamacls2" name="atasnama" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="toHidecls2">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                        <select class="form-control" id="bankcls2" name="bank" style="width: 100%;" >
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
                                            <input type="file" onchange="readURL(this);" id="bukticls2" name="file" accept=".jpg, .png, .jpeg|images/*" />
                                        </span><br><br>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="piccls2" src=""/>
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
{{--Modal Open Tabungan--}}
<div class="modal fade" id="openTabModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard">
            <form id="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.master_tab')}} @elseif(Auth::user()->tipe=="teller") action="{{route('teller.master_tab')}} @elseif(Auth::user()->tipe=="anggota") action="{{route('master_tab')}} @endif" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Pembukaan Rekening Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Tab" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Tab">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            @if(Auth::user()->tipe!="anggota")
                            <div class="row" id="toHideNasabah">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pilih Anggota<star>*</star></label>
                                        <select class="form-control" id="nasabah" name="nama_nasabah" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan-</option>
                                            @foreach ($dropdown8 as $usr)
                                            <option value="{{ $usr->no_ktp }}">[{{ $usr->no_ktp }}] {{ $usr->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama<star>*</star></label>
                                        <select id="atasnama" name="atasnama" class="form-control" >
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHide">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="id_user"
                                               disabled
                                               required="true"
                                               @if( Auth::user()->tipe!="admin")
                                               value="{{ Auth::user()->no_ktp}}"
                                               @endif
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               disabled
                                               id="namauser"
                                               name="nama"
                                               @if( Auth::user()->tipe!="admin")
                                               value="{{Auth::user()->nama}}"
                                               @endif
                                               required="true"
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide2">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="idhukum"
                                               name="id_user"
                                               required="true"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="namahukum"
                                               name="nama"
                                               required="true"
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Tabungan<star>*</star></label>
                                    <select class="form-control" id="rekTab" name="tabungan" style="width: 100%;" required>
                                        <option class="bs-title-option" value="">Pilih Tabungan</option>
                                        @foreach ($dropdown as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               name="keterangan"
                                               value=""
                                        />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Kirim Pengajuan </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
{{--Modal View Tabungan--}}
<div class="modal fade" id="viewTabModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardv">
            <form id="wizardFormv">
                <div class="header text-center">
                    <h3 class="title">Pembukaan Rekening Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Tabv" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Tabv">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama<star>*</star></label>
                                        <select id="vatasnama" name="atasnama" class="form-control" disabled>
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHidev">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="viduser"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="vnama"
                                               name="nama"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide2v">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="vidhukum"
                                               name="id_user"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="vnamahukum"
                                               name="nama"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-5 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Akad <star>*</star></label>
                                    <select class="form-control" id="vrekAkad" name="akad" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" value="">Pilih Akad</option>
                                        <option class="0" value="">Lain-lain</option>
                                        @foreach ($dropdown as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} [{{$rekening->id_rekening }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="namaSim" class="control-label">Jenis Tabungan<star>*</star></label>
                                    <select class="form-control" id="vrekTab" name="tabungan" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" value="">Pilih Tabungan</option>
                                        @foreach ($dropdown as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} [{{$rekening->id_rekening }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               name="keterangan"
                                               id="vketerangan"
                                               value=""
                                               disabled
                                        />
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
{{--Modal Aktivasi Tabungan--}}
<div class="modal fade" id="activeTabModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCarda">
            <form id="wizardForma" method="POST" @if(Auth::user()->tipe=="teller") action="{{ route('teller.konfirmasi_pendaftaran_baru') }}" @elseif(Auth::user()->tipe=="admin") action="{{route('admin.pengajuan.active')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_act_tab" name="id_">
                <div class="header text-center">
                    <h3 class="title">Pembukaan Rekening Tabungan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Taba" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Taba">
                            <h5 class="text-center">Pastikan terlebih dahalu sebelum aktivasi rekening</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama</label>
                                        <select id="aatasnama" name="atasnama" class="form-control" disabled>
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHidea">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas</label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="aiduser"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap</label>
                                        <input class="form-control"
                                               type="text"
                                               id="anama"
                                               name="nama"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide2a">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum</label>
                                        <input class="form-control"
                                               type="text"
                                               id="aidhukum"
                                               name="id_user"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga</label>
                                        <input class="form-control"
                                               type="text"
                                               id="anamahukum"
                                               name="nama"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-5 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Akad </label>
                                    <select class="form-control" id="arekAkad" name="akad" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" value="">Pilih Akad</option>
                                        <option class="0" value="">Lain-lain</option>
                                        @foreach ($dropdown as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} [{{$rekening->id_rekening }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="namaSim" class="control-label">Jenis Tabungan</label>
                                    <select class="form-control" id="arekTab" name="tabungan" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" value="">Pilih Tabungan</option>
                                        @foreach ($dropdown as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} [{{$rekening->id_rekening }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               name="keterangan"
                                               id="aketerangan"
                                               value=""
                                               disabled
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row" >
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Apakah anggota telah membayar "Setoran Awal" ?<star>*</star></label>
                                        <select required  name="syarat" class="form-control" >
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" >
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Apakah anggota telah mengisi "Identitas Anggota" dengan benar ?<star>*</star></label>
                                        <select required name="identitas" class="form-control" >
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="Awal">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Simpanan Pokok Anggota<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="pokokawal" name="pokok"  required>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 ">
                                    <div class="form-group">
                                        <label class="control-label">Simpanan Wajib Anggota<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="wajibawal" name="wajib"  required>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Aktivasi </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
    </div>
</div>


{{--Modal Open Deposito--}}
<div class="modal fade" id="openDepModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard2">
            <form id="wizardForm2" method="POST" @if(Auth::user()->tipe=="teller") action="{{route('teller.deposito.open')}}" @elseif(Auth::user()->tipe=="admin") action="{{route('admin.master_dep')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('master_dep')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Pembukaan Mudharabah Berjangka</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Dep" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Dep">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            @if(Auth::user()->tipe!="anggota")
                            <div class="row" id="toHideNasabah2">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pilih Anggota<star>*</star></label>
                                        <select class="form-control" id="nasabah2" name="nama_nasabah" style="width: 100%;" required>
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown8 as $usr)
                                                <option value="{{ $usr->no_ktp }}" class="{{ $usr->no_ktp }}"> [{{ $usr->no_ktp }}] {{ $usr->nama }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label class="control-label">Tabungan Pencairan<star>*</star></label>
                                    <select class="form-control" id="rek_tabungan" name="rek_tabungan" style="width: 100%;" required>
                                        <option class="bs-title-option" value="">Pilih Tabungan</option>
                                        @foreach ($tab as $rekening)
                                            <option value="{{ $rekening->id }}" class="selectors {{$rekening->no_ktp}}">[{{ $rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }} [{{$rekening->no_ktp }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama<star>*</star></label>
                                        <select id="atasnama2" name="atasnama" class="form-control" >
                                            <option selected disabled class="bs-title-option" value="">- Pilih -</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHide3">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="id_user2"
                                               name="id_user"
                                               required="true"
                                               disabled
                                               @if( Auth::user()->tipe!="admin")
                                               value="{{Auth::user()->no_ktp}}"
                                               @endif
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="namauser2"
                                               name="nama"
                                               @if( Auth::user()->tipe!="admin")
                                               value="{{Auth::user()->nama}}"
                                               @endif
                                               disabled
                                               required="true"
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide4">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="idhukum2"
                                               name="id_user"
                                               required="true"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="namahukum2"
                                               name="nama"
                                               required="true"
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-5 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Mudharabah Berjangka <star>*</star></label>
                                    <input type="hidden" id="deposito_id" name="deposito_">
                                    <select class="form-control" id="rekDep" name="deposito" style="width: 100%;" required>
                                        <option class="bs-title-option" value="">Pilih Mudharabah Berjangka</option>
                                        @foreach ($dropdown2 as $rekening)
                                            <option value="{{ $rekening->id." ".
                                                json_decode($rekening->detail,true )['nisbah_anggota']
                                            }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="namaSim" class="control-label">Nisbah Anggota <star>*</star></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" id="ket_nisbah"  disabled/>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>

                            @if(Auth::user()->tipe == 'anggota')
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jenis Transaksi <star>*</star></label>
                                        <select class="form-control opsi-pembayaran" id="adebitdeb" name="kredit" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="-1" disabled>-Pilih jenis Transaksi-</option>
                                            <option value="0">Tunai</option>
                                            <option value="1">Transfer</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row opsi-transfer hide">
                                <div class="col-sm-12 col-md-5 col-lg-5 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Rekening Bank Tujuan <star>*</star></label><br>
                                        <select class="form-control select2" name="bank_tujuan" style="width: 100%;">
                                            <option class="bs-title-option" selected value="-1" disabled>-Pilih Rekening Bank-</option>
                                            @foreach($bank_bmt as $bank)
                                            <option value="{{ $bank->id }}">[{{$bank->id_rekening}}] {{ $bank->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-5 col-lg-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Upload Bukti Transfer <star>*</star></label><br>
                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" class="bukti" name="file" accept=".jpg, .png, .jpeg|images/*">
                                        </span><br><br>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;height:auto" id="pic" src="" name="image" />
                                </div>
                            </div>
                            @endif
                            
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="jumlahdep" name="jumlah"  required>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               name="keterangan"
                                               value=""
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="perpanjang_otomatis">
                                            <span class="form-check-label" for="exampleCheck1">Perpanjangan Otomatis</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Kirim Pengajuan </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
{{--Modal View Deposito--}}
<div class="modal fade" id="viewDepModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard2v">
            <form id="wizardForm2v">
                <div class="header text-center">
                    <h3 class="title" id="titleVDep">Pembukaan Mudharabah Berjangka</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Depv" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Depv">
                            <h5 class="text-center">Detail pengajuan mudharabah berjangka Anda</h5>
                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label class="control-label">Tabungan Pencairan<star>*</star></label>
                                    <select class="form-control" id="vrek_tabungan"  style="width: 100%;" disabled>
                                        <option class="bs-title-option" value="">Pilih Tabungan</option>
                                        @foreach ($tab as $rekening)
                                            <option value="{{ $rekening->id }}" class="selectors {{$rekening->no_ktp}}">[{{ $rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }} [{{$rekening->no_ktp }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama<star>*</star></label>
                                        <select id="vatasnama2" name="atasnama" class="form-control" disabled >
                                            <option selected disabled class="bs-title-option" value="">- Pilih -</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHide3v">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="viduser2"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="nama"
                                               id="vnama2"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide4v">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="vidhukum2"
                                               name="id_user"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="vnamahukum2"
                                               name="nama"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-5 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Mudharabah Berjangka <star>*</star></label>
                                    <select class="form-control" id="vrekDep" name="deposito" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" value="">Pilih Mudharabah Berjangka</option>
                                        @foreach ($dropdown2 as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="namaSim" class="control-label">Nisbah Anggota <star>*</star></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" id="vket_nisbah"  disabled/>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="vjumlahdep" name="jumlah"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               name="keterangan"
                                               id="vketerangan2"
                                               value=""
                                               disabled
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="vPerpanjanganOtomatisDeposito" name="perpanjang_otomatis" disabled>
                                            <span class="form-check-label" for="exampleCheck1">Perpanjangan Otomatis</span>
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
{{--Modal Aktivasi Deposito--}}
<div class="modal fade" id="activeDepModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard2a">
            <form id="wizardForm2a" method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.pengajuan.active')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.deposito.confirm')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_act_dep" name="id_">
                <div class="header text-center">
                    <h3 class="title">Pembukaan Mudharabah Berjangka</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Depa" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Depa">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label class="control-label">Tabungan Pencairan<star>*</star></label>
                                    <select class="form-control" id="arek_tabungan" name="rek_tabungan"  style="width: 100%;" disabled>
                                        <option class="bs-title-option" value="">Pilih Tabungan</option>
                                        @foreach ($tab as $rekening)
                                            <option value="{{ $rekening->id }}" class="selectors {{$rekening->no_ktp}}">[{{ $rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }} [{{$rekening->no_ktp }}]</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama<star>*</star></label>
                                        <select id="aatasnama2" name="atasnama" class="form-control" disabled >
                                            <option selected disabled class="bs-title-option" value="">- Pilih -</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHide3a">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="aiduser2"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="nama"
                                               id="anama2"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide4a">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="aidhukum2"
                                               name="id_user"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="anamahukum2"
                                               name="nama"
                                               required="true"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-5 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Deposito <star>*</star></label>
                                    <select class="form-control" id="arekDep" name="deposito" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" value="">Pilih Deposito</option>
                                        @foreach ($dropdown2 as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="namaSim" class="control-label">Nisbah Anggota <star>*</star></label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" id="aket_nisbah"  disabled/>
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1 bank_tujuan">
                                    <div class="form-group">
                                        <label class="control-label">Bank BMT Tujuan <star>*</star></label>
                                        <select class="form-control" id="bank_tujuan" name="bank_tujuan" style="width: 100%;" required disabled>
                                            <option class="bs-title-option" value="">Pilih Bank BMT Tujuan</option>
                                            @foreach ($bank_bmt as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->nama_rekening }} {{$rekening->id_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5 jumlah_uang">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Uang <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="ajumlahdep" name="jumlah"  disabled>
                                            <span class="input-group-addon">.00</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <input class="form-control"
                                               type="text"
                                               name="keterangan"
                                               id="aketerangan2"
                                               value=""
                                               disabled
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Apakah anggota telah memenuhi persyaratan?<star>*</star></label>
                                        <select required  name="syarat" class="form-control" >
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Apakah anggota telah mengisi "Identitas Anggota" dengan benar ?<star>*</star></label>
                                        <select required name="identitas" class="form-control" >
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="activePerpanjanganOtomatisDeposito" name="perpanjang_otomatis">
                                            <span class="form-check-label" for="exampleCheck1">Perpanjangan Otomatis</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Aktivasi </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>


{{--Modal Open Pembiayaan--}}
<div class="modal fade" id="openPemModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="container-fluid">
    <div class="modal-dialog" role="document">
      <div class="card card-wizard" id="wizardCard3">
            <form id="wizardForm3" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.master_pem')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.pembiayaan.open')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('master_pem')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Permohonan Pembiayaan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Pem" data-toggle="tab">Data Diri Pemohon</a></li>
                        <li><a href="#tab2Pem" data-toggle="tab">Detail Jaminan</a></li>
                        @if(Auth::user()->tipe!="anggota")
                        <li><a href="#tab3Pem" data-toggle="tab">Aktivasi</a></li>
                        @endif
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Pem">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            @if(Auth::user()->tipe!="anggota")
                            <div class="row" id="toHideNasabah3">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pilih Anggota<star>*</star></label>
                                        <select id="nasabah3" name="nama_nasabah" class="form-control"  style="width: 100%;">
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($dropdown8 as $usr)
                                                <option value="{{ $usr->no_ktp }}"> [{{ $usr->no_ktp }}] {{ $usr->nama }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama<star>*</star></label>
                                        <select id="atasnama3" name="atasnama" class="form-control">
                                            <option selected disabled class="bs-title-option" value="">- Pilih -</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHide5">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="id_user3"
                                               required="true"
                                               @if( Auth::user()->tipe!="admin")
                                               value="{{Auth::user()->no_ktp}}"
                                               @endif disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="namauser3"
                                               name="nama"
                                               @if( Auth::user()->tipe!="admin")
                                               value="{{Auth::user()->nama}}"
                                               @endif required="true" disabled
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide6">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="idhukum3"
                                               name="id_user"
                                               required="true"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="namahukum3"
                                               name="nama"
                                               required="true"
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Pembiayaan <star>*</star></label>
                                    <select class="form-control" id="rekPem" name="pembiayaan" style="width: 100%;" required>
                                        <option class="bs-title-option" value="">Pilih Pembiayaan</option>
                                        @foreach ($dropdown3 as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label class="control-label">Jumlah Uang</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" class="currency form-control text-right" name="jumlah" required="true">
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Untuk Usaha</label>
                                        <input class="form-control"
                                               type="text"
                                               name="usaha"
                                               value=""
                                               required="true"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Jenis Usaha</label>
                                        <select  name="jenisUsaha" class="form-control" required="true">
                                            <option selected="" disabled="">- Pilih -</option>
                                            <option value="Pertanian">Pertanian</option>
                                            <option value="Dagang">Dagang</option>
                                            <option value="Industri">Industri</option>
                                            <option value="Lain-lain">Lain-lain</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jangka Waktu</label>
                                        <input class="form-control"
                                               type="number"
                                               name="waktu"
                                               value=""
                                               required="true"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <select id="ket_waktu" name="ketWaktu" class="form-control" selected="" required="true">
                                            <option   class="bs-title-option" disabled="">- Pilih -</option>
                                            <option value="Hari">Hari</option>
                                            <option value="Pekan">Pekan</option>
                                            <option value="Bulan">Bulan</option>
                                            <option value="Tahun">Tahun</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2Pem">

                            <div class="row" id="detailJam">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Pilih Jaminan <star>*</star></label>
                                    <select  class="form-control" id="rekPem2" name="list" style="width: 100%;" required>
                                        <option class="bs-title-option" disabled value="0">Pilih Jaminan</option>
                                        @foreach ($dropdown9 as $jamrek)
                                            <option value="{{$jamrek->id.".".$jamrek->detail.".".$jamrek->field.".".$jamrek->nama_jaminan
                                            }}">{{ $jamrek->nama_jaminan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row" >
                                <div id="ShowJamber" class="col-md-4 col-md-offset-1"></div>
                                <div id="HideJamber" class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jaminan Berupa</label>
                                        <input class="form-control"
                                               type="text"
                                               id="ketjam"
                                               name="jaminan"
                                               value=""
                                               required="true"
                                        />
                                    </div>
                                </div>
                                <style>
                                    article, aside, figure, footer, header, hgroup,
                                    menu, nav, section { display: block; }
                                    .btn-file {
                                        position: relative;
                                        overflow: hidden;
                                    }
                                    .btn-file input[type=file] {
                                        position: absolute;
                                        top: 0;
                                        right: 0;
                                        min-width: 100%;
                                        min-height: 100%;
                                        font-size: 100px;
                                        text-align: right;
                                        filter: alpha(opacity=0);
                                        opacity: 0;
                                        outline: none;
                                        background: white;
                                        cursor: inherit;
                                        display: block;
                                    }
                                </style>

                                <div class="col-md-6 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Upload Jaminan</label><br>
                                        <span class="btn btn-info btn-fill btn-file"> Browse
                                            <input type="file" onchange="readURL5(this);" name="file" accept=".jpg, .png, .jpeg|images/*" required="true"/>
                                        </span><br><br>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="margin: auto;width:100px;" id="pic5" src=""/>
                                </div>


                            </div>
                        </div>
                        @if(Auth::user()->tipe!="anggota")
                        <div class="tab-pane" id="tab3Pem">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Saksi 1 [Pihak BMT] <star>*</star></label>
                                        <input type="text" name="saksi1"  class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Saksi 2 [saksi Pihak Peminjam] <star>*</star></label>
                                        <input type="text" name="saksi2"  class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Alamat [saksi Pihak Peminjam] <star>*</star></label>
                                        <input type="text" name="alamat2"  class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">KTP [saksi Pihak Peminjam] <star>*</star></label>
                                        <input type="text" name="ktp2"  class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Apakah anggota telah memenuhi persyaratan?" ?<star>*</star></label>
                                        <select required  name="syarat" class="form-control" >
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Nisbah BMT <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                            <input type="text" name="nisbah"  class="form-control text-right" required>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Pencairan dari Rekening <star>*</star></label>
                                        <select class="form-control" name="bank" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening-</option>
                                            @foreach ($dropdown6->merge($dropdown7)->all() as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="footer">
                    <button type="button" id="closepem" class="btn btn-default btn-fill btn-wd btn-close pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="backpem" class="btn btn-default btn-fill btn-wd btn-back pull-left">Kembali</button>
                    <button type="button" id="nextpem" class="btn btn-info btn-fill btn-wd btn-next pull-right">Selanjutnya</button>
                    <button type="submit" id="finishpem" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Kirim Pengajuan </button>
                <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
    </div>
</div>
{{--Modal View Pembiayaan--}}
<div class="modal fade" id="viewPemModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard3v">
            <form id="wizardForm3v">
                <div class="header text-center">
                    <h3 class="title">Permohonan Pembiayaan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Pemv" data-toggle="tab">Data Diri Pemohon</a></li>
                        <li><a href="#tab2Pemv" data-toggle="tab">Detail Jaminan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Pemv">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama<star>*</star></label>
                                        <select id="vatasnama3" name="atasnama" class="form-control" disabled>
                                            <option selected disabled class="bs-title-option" value="">- Pilih -</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHide5v">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="viduser3"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="nama"
                                               id="vnama3"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide6v">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="vidhukum3"
                                               name="id_user"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="vnamahukum3"
                                               name="nama"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Pembiayaan <star>*</star></label>
                                    <select class="form-control" id="vrekPem" name="pembiayaan" style="width: 100%;" disabled required>
                                        <option class="bs-title-option" value="">Pilih Pembiayaan</option>
                                        @foreach ($dropdown3 as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label class="control-label">Jumlah Uang</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp</span>
                                        <input disabled type="text" class="form-control text-right" id="vjumlah" name="jumlah" required="true">
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Untuk Usaha</label>
                                        <input class="form-control"
                                               type="text"
                                               name="usaha"
                                               id="vusaha"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Jenis Usaha</label>
                                        <select  id="vjenis" name="jenisUsaha" class="form-control" required="true" disabled>
                                            <option selected="" disabled="">- Pilih -</option>
                                            <option value="Pertanian">Pertanian</option>
                                            <option value="Dagang">Dagang</option>
                                            <option value="Industri">Industri</option>
                                            <option value="Lain-lain">Lain-lain</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jangka Waktu</label>
                                        <input class="form-control"
                                               type="number"
                                               name="waktu"
                                               id="vwaktu"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <select disabled id="vketWaktu" name="ketWaktu" class="form-control" selected="" required="true">
                                            <option   class="bs-title-option" disabled="">- Pilih -</option>
                                            <option value="Hari">Hari</option>
                                            <option value="Pekan">Pekan</option>
                                            <option value="Bulan">Bulan</option>
                                            <option value="Tahun">Tahun</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2Pemv">
                            <div class="row" id="vtabPem2">
                                <div class="row" id="vdetailJam">
                                    <div class="form-group col-md-10 col-md-offset-1">
                                        <label for="namaSim" class="control-label">Tipe Jaminan <star>*</star></label>
                                        <select  class="form-control" disabled id="vrekPem2" style="width: 100%;" required>
                                            <option class="bs-title-option" disabled value="0">Pilih Jaminan</option>
                                            @foreach ($dropdown9 as $jamrek)
                                                <option value="{{$jamrek->nama_jaminan }}">{{ $jamrek->nama_jaminan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jaminan Berupa</label>
                                        <input class="form-control"
                                               type="text"
                                               name="jaminan"
                                               id="vjaminan"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <style>
                                    article, aside, figure, footer, header, hgroup,
                                    menu, nav, section { display: block; }
                                    .btn-file {
                                        position: relative;
                                        overflow: hidden;
                                    }
                                    .btn-file input[type=file] {
                                        position: absolute;
                                        top: 0;
                                        right: 0;
                                        min-width: 100%;
                                        min-height: 100%;
                                        font-size: 100px;
                                        text-align: right;
                                        filter: alpha(opacity=0);
                                        opacity: 0;
                                        outline: none;
                                        background: white;
                                        cursor: inherit;
                                        display: block;
                                    }
                                </style>

                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Foto Jaminan</label><br>
                                        <div class="modal-body">
                                            <img style="margin: auto;width:100px;height:auto" id="vpic5" src="">
                                        </div>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="button" id="vnextpem" class="btn btn-info btn-fill btn-wd btn-next pull-right">Selanjutnya</button>
                    <button type="button" id="vclosepem" class="btn btn-default btn-fill btn-wd btn-close pull-left" data-dismiss="modal">Close</button>
                    <button type="button" id="vbackpem" class="btn btn-default btn-fill btn-wd btn-back pull-left">Kembali</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
{{--Modal Aktivasi Pembiayaan--}}
<div class="modal fade" id="activePemModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard3a">
            <form id="wizardForm3a" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.pengajuan.active')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.pembiayaan.confirm')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_act_pem" name="id_">
                <input type="hidden" value="ya" name="identitas">
                <input type="hidden" value="true" name="pembiayaan">
                <div class="header text-center">
                    <h3 class="title">Permohonan Pembiayaan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Pema" data-toggle="tab">Data Diri Pemohon</a></li>
                        <li><a href="#tab2Pema" data-toggle="tab">Detail Jaminan</a></li>
                        <li><a href="#tab3Pema" data-toggle="tab">Aktivasi</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Pema">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Atas Nama<star>*</star></label>
                                        <select id="aatasnama3" name="atasnama" class="form-control" disabled>
                                            <option selected disabled class="bs-title-option" value="">- Pilih -</option>
                                            <option value="1">Pribadi</option>
                                            <option value="2">Lembaga</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="toHide5a">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Identitas<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="aiduser3"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lengkap<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               name="nama"
                                               id="anama3"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>
                            <div class="row" id="toHide6a">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">No Badan Hukum<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="aidhukum3"
                                               name="id_user"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Nama Lembaga<star>*</star></label>
                                        <input class="form-control"
                                               type="text"
                                               id="anamahukum3"
                                               name="nama"
                                               disabled
                                        />
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label for="namaSim" class="control-label">Jenis Pembiayaan <star>*</star></label>
                                    <select disabled class="form-control" id="arekPem" name="pembiayaan" style="width: 100%;" required>
                                        <option class="bs-title-option" value="">Pilih Pembiayaan</option>
                                        @foreach ($dropdown3 as $rekening)
                                            <option value="{{ $rekening->id }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1">
                                    <label class="control-label">Jumlah Uang</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp</span>
                                        <input disabled type="text" class="form-control text-right" id="ajumlah" name="jumlah" required="true">
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Untuk Usaha</label>
                                        <input class="form-control"
                                               type="text"
                                               name="usaha"
                                               id="ausaha"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Jenis Usaha</label>
                                        <select  id="ajenis" name="jenisUsaha" class="form-control" required="true" disabled>
                                            <option selected="" disabled="">- Pilih -</option>
                                            <option value="Pertanian">Pertanian</option>
                                            <option value="Dagang">Dagang</option>
                                            <option value="Industri">Industri</option>
                                            <option value="Lain-lain">Lain-lain</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jangka Waktu</label>
                                        <input class="form-control"
                                               type="number"
                                               name="waktu"
                                               id="awaktu"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan</label>
                                        <select disabled id="aketWaktu" name="ketWaktu" class="form-control" selected="" required="true">
                                            <option   class="bs-title-option" disabled="">- Pilih -</option>
                                            <option value="Hari">Hari</option>
                                            <option value="Pekan">Pekan</option>
                                            <option value="Bulan">Bulan</option>
                                            <option value="Tahun">Tahun</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab2Pema">
                            <div class="row" id="atabPem2">
                                <div class="row" id="adetailJam">
                                    <div class="form-group col-md-10 col-md-offset-1">
                                        <label for="namaSim" class="control-label">Tipe Jaminan <star>*</star></label>
                                        <select  class="form-control" disabled id="arekPem2" style="width: 100%;" required>
                                            <option class="bs-title-option" disabled value="0">Pilih Jaminan</option>
                                            @foreach ($dropdown9 as $jamrek)
                                                <option value="{{$jamrek->nama_jaminan }}">{{ $jamrek->nama_jaminan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jaminan Berupa</label>
                                        <input class="form-control"
                                               type="text"
                                               name="jaminan"
                                               id="ajaminan"
                                               disabled
                                        />
                                    </div>
                                </div>
                                <style>
                                    article, aside, figure, footer, header, hgroup,
                                    menu, nav, section { display: block; }
                                    .btn-file {
                                        position: relative;
                                        overflow: hidden;
                                    }
                                    .btn-file input[type=file] {
                                        position: absolute;
                                        top: 0;
                                        right: 0;
                                        min-width: 100%;
                                        min-height: 100%;
                                        font-size: 100px;
                                        text-align: right;
                                        filter: alpha(opacity=0);
                                        opacity: 0;
                                        outline: none;
                                        background: white;
                                        cursor: inherit;
                                        display: block;
                                    }
                                </style>

                                <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Foto Jaminan</label><br>
                                        <div class="modal-body">
                                            <img style="margin: auto;width:100px;height:auto" id="apic5" src="">
                                        </div>
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab3Pema">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Saksi 1 [Pihak BMT] <star>*</star></label>
                                        <input type="text" name="saksi1"  class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Saksi 2 [saksi Pihak Peminjam] <star>*</star></label>
                                        <input type="text" name="saksi2"  class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Alamat [saksi Pihak Peminjam] <star>*</star></label>
                                        <input type="text" name="alamat2"  class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">KTP [saksi Pihak Peminjam] <star>*</star></label>
                                        <input type="text" name="ktp2"  class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Apakah anggota telah memenuhi persyaratan?" ?<star>*</star></label>
                                        <select required  name="syarat" class="form-control" >
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            <option value="ya">Ya</option>
                                            <option value="tidak">Tidak</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Nisbah BMT <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon"></span>
                                            <input type="text" name="nisbah"  class="form-control text-right" required>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Pencairan dari Rekening <star>*</star></label>
                                        <select class="form-control" name="bank" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih Rekening-</option>
                                            @foreach ($dropdown6->merge($dropdown7)->all() as $rekening)
                                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="footer">
                        <button type="button" id="aclosepem" class="btn btn-default btn-fill btn-wd btn-close pull-left" data-dismiss="modal">Close</button>
                        <button type="button" id="abackpem" class="btn btn-default btn-fill btn-wd btn-back pull-left">Kembali</button>
                        <button type="button" id="anextpem" class="btn btn-info btn-fill btn-wd btn-next pull-right">Selanjutnya</button>
                        <button type="submit" id="afinishpem" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Aktivasi </button>
                        {{--<button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>--}}
                        <div class="clearfix"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


{{--Modal Hapus Pengajuan--}}
<div class="modal fade" id="delModal" role="dialog" aria-labelledby="delTabLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('delete.pengajuan')}}" enctype="multipart/form-data"  id="delTabungan">
                {{csrf_field()}}
                <input type="hidden" id="id_del" name="id_">
                <div class="modal-header">
                    <h5 class="modal-title" id="delTabLabel">Hapus Pengajuan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Hapus Pengajuan</h4>
                    <h5 id="toDelete"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{--Modal Blokir Rekening--}}
<div class="modal fade" id="blockRekModal" role="dialog" aria-labelledby="delTabLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" @if(Auth::user()->tipe=="admin") action="{{route('un_block.rekening')}}" @elseif(Auth::user()->tipe =="teller") action="{{route('teller.un_block.rekening')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_block" name="id">
                <input type="hidden" id="st_block" name="status">
                <input type="hidden" id="tipeRek" name="tipe">
                <div class="modal-header">
                    <h5 class="modal-title" id="blockRekLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Blokir Rekening User</h4>
                    <h5 id="toBlock"></h5>
                </div>
                <div class="modal-footer">
                    <button type="button"  class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btn_active" class="btn btn-success">Aktivasi</button>
                    <button type="submit" id="btn_block"class="btn btn-danger">Blokir</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{--Modal Edit Status Pengajuan--}}
<div class="modal fade" id="editStatusModal" role="dialog" aria-labelledby="StatusLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.pengajuan.status')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.pengajuan.status')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_status" name="id_">
                <input type="hidden" id="id_status_user" name="id_user">
                <input type="hidden" id="id_status_detail" name="detail">
                <div class="modal-header">
                    <h5 class="modal-title" id="StatusLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label class="control-label">Ubah Status</label>
                                <select  name="status" class="form-control" required="true">
                                    <option selected="" disabled="">- Pilih -</option>
                                    <option value="Disetujui">Setujui</option>
                                    <option value="Ditolak">Tolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label class="control-label">Keterangan</label>
                                <input class="form-control"
                                       type="text"
                                       name="keterangan"
                                       value=""
                                       required="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Ubah Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{--Modal Active Pengajuan--}}
<div class="modal fade" id="activePengajuanModal" role="dialog" aria-labelledby="ActiveLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="#" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_active" name="id_">
                <input type="hidden" id="id_active_user" name="id_user">
                <div class="modal-header">
                    <h5 class="modal-title" id="ActiveLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="text-center">Pastikan terlebih dahalu sebelum aktifasi akun!</div>
                        <br>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label class="control-label">Apakah anggota telah membayar "Setoran Awal" ?<star>*</star></label>
                                <select  name="atasnama" class="form-control" >
                                    <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                    <option value="ya">Ya</option>
                                    <option value="tidak">Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label class="control-label">Apakah anggota telah memngisi "Identitas Anggota" dengan benar ?<star>*</star></label>
                                <select  name="atasnama" class="form-control" >
                                    <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                    <option value="ya">Ya</option>
                                    <option value="tidak">Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Aktivasi Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal View Donasi--}}
<div class="modal fade" id="viewDonModal" role="dialog" aria-labelledby="ActiveLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDv">
            <form id="wizardFormDv" method="POST" action="{{route('donasimaal')}}" enctype="multipart/form-data"">
            {{csrf_field()}}
            <div class="header text-center">
                <h3 class="title" id="titleDon">Donasi Kegiatan Maal</h3>
                <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
            </div>

            <div class="content">
                <ul class="nav">
                    <li><a href="#tab1TabDon" data-toggle="tab">Data Transaksi</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane" id="tab1TabDon">
                        <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                        <div class="row" id="HideRekDon">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="id_" class="control-label">Pilih Kegiatan Maal <star>*</star></label>
                                    <select class="form-control" id="vidRekDon" name="kegiatan" style="width: 100%;" required disabled>
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
                                    <select class="form-control" id="vjenisDon" name="jenis" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" selected disabled value="">-Pilih Jenis Pembayaran-</option>
                                        <option value="Transfer">Transfer dari Rekening Bank</option>
                                        <option value="Tabungan">Transfer dari Rekening Tabungan</option>
                                        <option value="Tunai">Tunai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="toHideBankDon">
                            <div class="col-md-4 col-md-offset-1">
                                <div class="form-group">
                                    <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                    <input type="text" class="form-control text-left" disabled id="vbankDon" name="daribank" required>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                    <input type="number" class="form-control text-left" disabled id="vnobankDon" name="nobank" required>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="toHideBank2Don">
                            <div class="col-md-5 col-md-offset-1">
                                <div class="form-group">
                                    <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                    <input type="text" class="form-control text-left" disabled  id="vatasnamaDon" name="atasnama" required>
                                </div>
                            </div>
                            <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                <div class="form-group">
                                    <label> Bukti Transfer <star>*</star></label><br>
                                    {{--<span class="btn btn-info btn-fill btn-file center-block"> Browse--}}
                                    {{--<input type="file" onchange="readURL(this);" id="vbuktiDon" disabled name="file" accept=".jpg, .png, .jpeg|images/*" />--}}
                                    {{--</span><br><br>--}}
                                    <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <img style="margin: auto;width:200px;height:auto" id="vpicDon" src=""/>
                            </div>
                        </div>

                        {{-- <div class="row" id="RekBank">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                    <select class="form-control" id="vbank_" style="width: 100%;" disabled>
                                        <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                        @foreach ($dropdown6 as $rekening)
                                            <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}

                        <div class="row" id="toHideTabDon">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="id_" class="control-label">Transfer dari Rekening <star>*</star></label>
                                    <select class="form-control" id="vidRekTabDon" name="dari" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan Anda-</option>
                                        @foreach ($datasaldo as $rekening)
                                            <option value="{{ $rekening->id_tabungan }}">[{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}</option>
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
                                        <input type="text" class="currency form-control text-right" id="vjumlahDon" disabled name="jumlah" required="true">
                                        <span class="input-group-addon">.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="footer">
                {{--<button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Donasi Sekarang </button>--}}
                {{--<button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>--}}
                <div class="clearfix"></div>
            </div>
            </form>

        </div>
    </div>
</div>


{{--Modal Konfirmasi Donasi--}}
<div class="modal fade" id="confirmDonModal" role="dialog" aria-labelledby="ActiveLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardDc">
            <form id="wizardFormDc" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.konfirmasi.donasimaal')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.konfirmasi.donasimaal')}}" @endif enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" id="iddonasi" name="id_">
            <input type="hidden" name="teller" value="teller">
            <div class="header text-center">
                <h3 class="title" id="ctitleDon">Donasi Kegiatan Maal</h3>
                <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
            </div>

            <div class="content">
                <ul class="nav">
                    <li><a href="#tab1Tabc" data-toggle="tab">Data Transaksi</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane" id="tab1Tabc">
                        <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>

                        <div class="row" id="cHideRekDon">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="id_" class="control-label">Pilih Kegiatan Maal <star>*</star></label>
                                    <select class="form-control" id="cidRekDon" name="kegiatan" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" selected disabled value="">-Pilih kegiatan-</option>
                                        @foreach ($kegiatan as $rekening)
                                            <option value="{{ $rekening->id }}">[{{ $rekening->tanggal_pelaksaaan }}] {{ $rekening->nama_kegiatan }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" id="IDdonasi" name="rekDon"/>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="id_" class="control-label">Jenis Pembayaran <star>*</star></label>
                                    <select class="form-control" id="cjenisDon" name="jenis" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" selected disabled value="">-Pilih Jenis Pembayaran-</option>
                                        <option value="Transfer">Transfer dari Rekening Bank</option>
                                        <option value="Tabungan">Transfer dari Rekening Tabungan</option>
                                        <option value="Tunai">Tunai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="toHideBankDonc">
                            <div class="col-md-4 col-md-offset-1">
                                <div class="form-group">
                                    <label for="namaSim" class="control-label">Nama BANK User <star>*</star></label>
                                    <input type="text" class="form-control text-left" disabled id="cbankDon" name="daribank" required>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="namaSim" class="control-label">No. Rekening BANK User <star>*</star></label>
                                    <input type="number" class="form-control text-left" disabled id="cnobankDon" name="nobank" required>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="toHideBank2Donc">
                            <div class="col-md-5 col-md-offset-1">
                                <div class="form-group">
                                    <label for="namaSim" class="control-label">Atas Nama <star>*</star></label>
                                    <input type="text" class="form-control text-left" disabled  id="catasnamaDon" name="atasnama" required>
                                </div>
                            </div>
                            <div class="col-md-5 {{ !$errors->has('file') ?: 'has-error' }}">
                                <div class="form-group">
                                    <label> Bukti Transfer <star>*</star></label><br>
                                    {{--<span class="btn btn-info btn-fill btn-file center-block"> Browse--}}
                                    {{--<input type="file" onchange="readURL(this);" id="cbuktiDon" disabled name="file" accept=".jpg, .png, .jpeg|images/*" />--}}
                                    {{--</span><br><br>--}}
                                    <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <img style="margin: auto;width:200px;height:auto" id="cpicDon" src=""/>
                            </div>
                        </div>
                        {{-- <div class="row" id="RekBank2">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="namaSim" class="control-label">Transfer ke Rek. BANK <star>*</star></label>
                                    <select class="form-control select2" id="cbank_" style="width: 100%;" disabled>
                                        <option class="bs-title-option" selected value="" disabled>-Pilih Rekening BANK-</option>
                                        @foreach ($dropdown6 as $rekening)
                                            <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row" id="toHideTabDonc">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="id_" class="control-label">Transfer dari Rekening <star>*</star></label>
                                    <select class="form-control" id="cidRekTabDon" name="dari" style="width: 100%;" required disabled>
                                        <option class="bs-title-option" selected disabled value="">-Pilih Rekening Tabungan Anda-</option>
                                        @foreach ($datasaldo as $rekening)
                                            <option value="{{ $rekening->id_tabungan }}">[{{$rekening->id_tabungan }}] {{ $rekening->jenis_tabungan }}</option>
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
                                        <input type="text" class="currency form-control text-right" id="cjumlahDon" disabled name="jumlah" required="true">
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


