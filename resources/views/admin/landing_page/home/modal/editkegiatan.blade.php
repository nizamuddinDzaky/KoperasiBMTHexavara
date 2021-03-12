<div class="modal fade" id="editKegiatanModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" action="{{route('admin.landingpage.kegiatan.update')}}"  enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Edit Kegiatan </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabEditKegiatan" data-toggle="tab">Kegiatan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabEditKegiatan">

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Keterangan <star>*</star></label>
                                        <input type="text" class="form-control text-left" maxlength="23" name="keterangan" id="keterangan_kegiatan_edit" required>
                                        <input type="hidden" id="id_kegiatan_edit" name="id_kegiatan">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Kategori <star>*</star></label>
                                        <select class="form-control" name="kategori" id="kategori_kegiatan_edit" required>
                                            @foreach($kategori as $keys => $value)
                                                <option value="{{$value->id}}">{{$value->nama}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-5 col-lg-5 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">
                                        <div class="form-group">
                                            <label>Upload Gambar Kegiatan <star>*</star></label><br>
                                            <span class="btn btn-info btn-fill btn-file center-block"> Browse
                                            <input type="file" onchange="readURL(this);" class="bukti" name="file" accept=".jpg, .png, .jpeg|images/*">
                                        </span><br><br>
                                            <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img style="margin: auto;width:100px;height:auto" class="pic" src=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Tambah </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
