<div class="modal fade" id="editStrukturOrganisasiModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" action="{{route('admin.landingpage.strukturorganisasi.update')}}"  enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Edit Anggota Struktur Organisasi </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabEditStrukturOrganisasi" data-toggle="tab">Struktur Organisasi</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabEditStrukturOrganisasi">

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama <star>*</star></label>
                                        <input type="text" class="form-control text-left" name="nama" id="nama_struktur_edit" required>
                                        <input type="hidden" id="id_struktur_edit" name="id_strukturorganisasi">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Jabatan (ex: Ketua)</label>
                                        <input type="text" class="form-control text-left" name="jabatan" id="jabatan_struktur_edit">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Kategori</label>
                                        <select name="kategori" class="form-control" id="kategori_struktur_edit" required>
                                            <option value="Pembina">Pembina</option>
                                            <option value="Pengawas">Pengawas</option>
                                            <option value="Dewan Pengawas Syariah">Dewan Pengawas Syariah</option>
                                            <option value="Pengurus">Pengurus</option>
                                            <option value="Pengelola">Pengelola</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="text-center">
                                <img style="margin: auto;width:100px;height:auto" class="pic" src=""/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Update </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
