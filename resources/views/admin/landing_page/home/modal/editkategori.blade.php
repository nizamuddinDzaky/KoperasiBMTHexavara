<div class="modal fade" id="editKategoriModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" action="{{route('admin.landingpage.kategori.update')}}"  enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Edit Kategori Kegiatan </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabEditKategori" data-toggle="tab">Kategori Kegiatan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabEditKategori">

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Nama Kategori <star>*</star></label>
                                        <input type="text" class="form-control text-left" id="nama_kategori_edit" name="nama_kategori" required>
                                        <input type="hidden" id="id_kategori_edit" name="id_kategori">
                                    </div>
                                </div>
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
