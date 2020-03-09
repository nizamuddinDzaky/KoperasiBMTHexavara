{{--Modal Open Pembiayaan--}}
<div class="modal fade" id="openPemModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="container-fluid">
    <div class="modal-dialog" role="document">
      <div class="card card-wizard" id="wizardCard3">
            <form id="wizardForm3" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.master_pem')}}" @elseif(Auth::user()->tipe=="teller") action="{{route('teller.master_pemt')}}" @elseif(Auth::user()->tipe=="anggota") action="{{route('master_pem')}}" @endif enctype="multipart/form-data">
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
                                        <label class="control-label">Pilih Nasabah<star>*</star></label>
                                        <select id="nasabah3" name="nama_nasabah" class="form-control select2"  style="width: 100%;">
                                            <option selected disabled class="bs-title-option" value="">-- Pilih --</option>
                                            @foreach ($all_nasabah as $user)
                                                <option value="{{ $user->no_ktp }}"> [{{ $user->no_ktp }}] {{ $user->nama }}</option>
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
                                        @foreach ($all_pembiayaan as $rekening)
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
                                    <select  class="form-control select2" id="rekPem2" name="list" style="width: 100%;" required>
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
                                        <select class="form-control select2" name="bank" style="width: 100%;" required>
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