{{--Modal Add Kegiatan Maal--}}
<div class="modal fade" id="addMaalModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard">
            <form id="wizardForm" method="POST" action="{{route('kegiatan.store.wakaf')}}" enctype="multipart/form-data"">
            {{csrf_field()}}
            <div class="header text-center">
                <h3 class="title">Form Kegiatan Wakaf</h3>
                <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
            </div>

            <div class="content">
                <ul class="nav">
                    <li><a href="#tab1" data-toggle="tab">Data Kegiatan Wakaf</a></li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane" id="tab1">
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label class="control-label">Nama Kegiatan</label>
                                    <input class="form-control"
                                           type="text"
                                           name="kegiatan"
                                           required="true"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label class="control-label">Tanggal pelaksanaan</label>
                                    <input class="form-control date-picker"
                                           type="text"
                                           name="tgl"
                                           required="true"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label class="control-label">Jumlah Dana Yang Dibutuhkan<star>*</star></label>
                                    <div class="input-group">
                                        <span class="input-group-addon">Rp</span>
                                        <input type="text" class="currency form-control text-right" name="jumlah"  required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label class="control-label">Detail Kegiatan<star>*</star></label>
                                    <textarea class="summernote" name="detail" required rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="col-md-12 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="text-center form-group">
                                        <label>Upload Poster Kegiatan  <star>*</star></label><br>
                                        {{--<span class="btn btn-default btn-fill btn-file center-block"> Browse--}}
                                        <input type="file" onchange="readURL(this);"  name="file" accept=".jpg, .png, .jpeg|images/*" required /><i class="fa fa-photo"></i>
                                        {{--</span>--}}
                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                    </div>
                                    <div class="text-center form-group">
                                        <img style="margin: auto;width:300px;height:auto" id="pic" src="">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="footer">
                <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Post </button>
                <div class="clearfix"></div>
            </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Edit Kegiatan Maal--}}
<div class="modal fade" id="editMaalModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardE">
            <form id="wizardFormE" method="POST" action="{{route('edit.kegiatan')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_edit" name="id_">
                <div class="header text-center">
                    <h3 class="title">Edit Kegiatan Wakaf</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1e" data-toggle="tab">Data Kegiatan Wakaf</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1e">
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Nama Kegiatan</label>
                                        <input class="form-control"
                                               type="text"
                                               id="ekegiatan"
                                               name="kegiatan"
                                               required="true"
                                        />
                                    </div>
                                </div>
                            </div>
                            {{--<div class="row">--}}
                            {{--<div class="col-md-10 col-md-offset-1">--}}
                            {{--<div class="form-group">--}}
                            {{--<label class="control-label">Tanggal pelaksanaan</label>--}}
                            {{--<input class="form-control datepicker"--}}
                            {{--type="text"--}}
                            {{--id="etgl"--}}
                            {{--name="tgl"--}}
                            {{--required="true"--}}
                            {{--/>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Dana Yang Dibutuhkan<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currency form-control text-right" id="edana" name="dana"  required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Detail Kegiatan<star>*</star></label>
                                        <textarea class="form-control" id="edetail" name="detail" required rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="col-md-12 {{ !$errors->has('file') ?: 'has-error' }}">
                                        <div class="text-center form-group">
                                            <label>Upload Poster Kegiatan  <star>*</star></label><br>
                                            {{--<span class="btn btn-default btn-fill btn-file center-block"> Browse--}}
                                            <input type="file" onchange="readURL(this);"  name="file" accept=".jpg, .png, .jpeg|images/*" /><i class="fa fa-photo"></i>
                                            {{--</span>--}}
                                            <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                        </div>
                                        <div class="text-center form-group">
                                            <img style="margin: auto;width:300px;height:auto" id="epic" src="">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Edit Post </button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>


{{--Modal Hapus Maal--}}
<div class="modal fade" id="delMaalModal" role="dialog" aria-labelledby="delTabLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('delete.kegiatan')}}" enctype="multipart/form-data"  id="delTabungan">
                {{csrf_field()}}
                <input type="hidden" id="id_del" name="id_">
                <div class="modal-header">
                    <h5 class="modal-title" id="delTabLabel">Hapus Kegiatan Wakaf</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Hapus Kegiatan</h4>
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