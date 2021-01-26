{{--Modal Add Kegiatan Maal--}}
<div class="modal fade" id="addMaalModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard">
            <form id="wizardForm" method="POST" action="{{route('kegiatan.store')}}" enctype="multipart/form-data"">
            {{csrf_field()}}
            <div class="header text-center">
                <h3 class="title">Form Kegiatan Maal</h3>
                <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
            </div>

            <div class="content">
                <ul class="nav">
                    <li><a href="#tab1" data-toggle="tab">Data Kegiatan Maal</a></li>
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
                                        <input type="text" class="currencyDecimal form-control text-right" name="jumlah"  required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label class="control-label">Detail Kegiatan<star>*</star></label>
                                    <textarea class="summernote"  name="detail" required rows="3"></textarea>
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
                <h3 class="title">Edit Kegiatan Maal</h3>
                <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
            </div>

            <div class="content">
                <ul class="nav">
                    <li><a href="#tab1e" data-toggle="tab">Data Kegiatan Maal</a></li>
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
                                        <input type="text" class="currencyDecimal form-control text-right" id="edana" name="dana"  required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label class="control-label">Detail Kegiatan<star>*</star></label>
                                    <textarea class="form-control" id="edetail" name="detail" required rows="3"></textarea>
{{--                                    <textarea class="summernote" id="edetail" name="detail"  required rows="3"></textarea>--}}
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
                    <h5 class="modal-title" id="delTabLabel">Hapus Kegiatan Mall</h5>
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


<div class="modal fade" id="pencairanMaalModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 80%">
        <div class="card card-wizard" id="wizardCardP">
            <form id="wizardFormP" method="POST" action="{{route('admin.maal.pencairan')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_kegiatan" name="idkegiatan">
                <input type="hidden" id="id_rekening" name="idrekening">
                <div class="header text-center">
                    <h3 class="title">Pencairan Donasi Kegiatan Maal</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1p" data-toggle="tab" id="namaKegiatan">Data Donasi Maal</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1p">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Rekening Penyeimbang <star>*</star></label>
                                        <select class="form-control select2" id="idRekJ" name="dari" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                            @foreach ($dropdownPencairan as $rekening)
                                                <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }} @if($rekening->saldo != "") [ Rp. {{ number_format($rekening->saldo, 2) }} ] @else [ Rp. 0 ] @endif</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Dana Tersisa Saat Ini <star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currencyDecimal form-control text-right" id="danaTersisa" name="danaTersisa" required="true" readonly="true">
{{--                                            <span class="input-group-addon">.00</span>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Jumlah Pencairan<star>*</star></label>
                                        <div class="input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="currencyDecimal form-control text-right" id="jumlahPencairan" name="jumlahPencairan" required="true">
{{--                                            <span class="input-group-addon">.00</span>--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">Keterangan<star>*</star></label>
                                        <input type="text" class="form-control" id="keteranganPencairanMAAL" name="keteranganPencairanMAAL" required="true">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Cairkan</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

