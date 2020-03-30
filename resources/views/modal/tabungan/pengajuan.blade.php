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
                                        <select class="form-control select2" id="nasabah" name="nama_nasabah" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Pilih Anggota Tabungan-</option>
                                            @foreach ($all_nasabah as $user)
                                            <option value="{{ $user->no_ktp }}">[{{ $user->no_ktp }}] {{ $user->nama }}</option>
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
                                    <select class="form-control select2" id="rekTab" name="tabungan" style="width: 100%;" required>
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