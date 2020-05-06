{{-- Modal Konfirmasi Keluar Dari Anggota --}}
<div class="modal fade" id="pencairanPenModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('admin.master_tab')}} @elseif(Auth::user()->tipe=="teller") action="{{route('teller.pencairan_rekening')}} @endif" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Pencairan Penutupan Rekening Anggota</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1KeluarDariAnggota" data-toggle="tab">Data Diri Pemohon</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1KeluarDariAnggota">
                            <h5 class="text-center">Pastikan data yang anda masukkan sesuai dengan data diri anda</h5>
                            
                            <input type="hidden" id="id_user_penutupan_rekening" name="id_user_penutupan_rekening">
                            <input type="hidden" id="id_pengajuan" name="id_pengajuan">

                            <div class="row">
                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Nama</label>
                                        <input class="form-control"
                                               type="text"
                                               name="nama_user_penutupan_rekening"
                                               id="nama_user_penutupan_rekening"
                                               disabled
                                               required="true"
                                        />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="control-label">Atasnama</label>
                                        <input class="form-control"
                                               type="text"
                                               disabled
                                               id="atasnama_penutupan_rekening"
                                               name="atasnama_penutupan_rekening"
                                               required="true"
                                        />
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Cairkan </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Tutup</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>