{{--Modal View Keluar Dari Anggota--}}
<div class="modal fade" id="viewPenModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.master_tab')}} @elseif(Auth::user()->tipe=="teller") action="{{route('teller.master_tab')}} @elseif(Auth::user()->tipe=="anggota") action="{{route('master_tab')}} @endif" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Keluar Dari Anggota</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1KeluarDariAnggota" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1KeluarDariAnggota">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            
                            <div class="row">
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

                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Tabungan Anggota</label>
                                        <select class="form-control select2" name="nama_nasabah" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Lihat Tabungan Anggota-</option>
                                            @foreach($tabungan_anggota as $tabungan)
                                            <option disabled value="{{ $tabungan->id_tabungan }}">[ {{ $tabungan->id_tabungan }} ] {{ $tabungan->jenis_tabungan }} -  Rp.{{ number_format(json_decode($tabungan->detail)->saldo, 2) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Mudharabah Berjangka Anggota</label>
                                        <select class="form-control select2" name="nama_nasabah" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Lihat Mudharabah Berjangka Anggota-</option>
                                            @foreach($deposito_anggota as $deposito)
                                            <option disabled value="{{ $deposito->id_deposito }}">[ {{ $deposito->id_deposito }} ] {{ $deposito->jenis_deposito }} -  Rp.{{ number_format(json_decode($deposito->detail)->jumlah, 2) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Pembiayaan Anggota</label>
                                        <select class="form-control select2" name="nama_nasabah" style="width: 100%;">
                                            <option class="bs-title-option" selected disabled value="">-Lihat Pembiayaan Anggota-</option>
                                            @foreach($pembiayaan_anggota as $pembiayaan)
                                            <option disabled value="{{ $pembiayaan->id_pembiayaan }}">[ {{ $pembiayaan->id_pembiayaan }} ] {{ $pembiayaan->jenis_pembiayaan }} -  Rp.{{ number_format(json_decode($pembiayaan->detail)->sisa_pinjaman, 2) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Simpanan Wajib Anggota</label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="id_user"
                                               disabled
                                               required="true"
                                               value="{{ number_format(json_decode(Auth::user()->wajib_pokok)->wajib, 2) }}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Simpanan Pokok Anggota</label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="id_user"
                                               disabled
                                               required="true"
                                               value="{{ number_format(json_decode(Auth::user()->wajib_pokok)->pokok, 2) }}"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Simpanan Khusus Anggota</label>
                                        <input class="form-control"
                                               type="text"
                                               name="id_user"
                                               id="id_user"
                                               disabled
                                               required="true"
                                               value="{{ number_format(json_decode(Auth::user()->wajib_pokok)->khusus, 2) }}"
                                        />
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

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Tutup</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>