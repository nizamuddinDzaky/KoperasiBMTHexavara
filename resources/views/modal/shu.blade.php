{{--Modal Add Shu--}}
<div class="modal fade" id="addSHUModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard">
            <form id="wizardForm" method="POST" action="{{route('admin.datamaster.shu.add_shu')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Pembagian Persentase SHU</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1TabTrs" data-toggle="tab">Data Rekening SHU</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1TabTrs">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nama Rekening <star>*</star></label>
                                        <select class="form-control select2" id="idRekSHU" name="dari" style="width: 100%;" required>
                                            <option class="bs-title-option" selected disabled value="">-Pilih Rekening BMT-</option>
                                            @foreach ($dropdown as $rekening)
                                                <option value="{{ $rekening->id }}">[{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Persentase <star>*</star></label>
                                        <div class="input-group">
                                            <input type="number" max="???" class="form-control text-right" id="persen" name="persen" required="true">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Tambah Rekening SHU </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal edit Shu--}}
<div class="modal fade" id="editSHUModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard3">
            <form id="wizardForm3" method="POST" action="{{route('admin.datamaster.shu.edit_shu')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Edit Pembagian Persentase SHU</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Tab3" data-toggle="tab">Data Rekening SHU</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Tab3">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            @for ($i=0;$i<count($data);$i++)
                                @if($data[$i]->status=="active")
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="form-group">
                                            <label class="control-label">Nama Rekening <star>*</star></label>
                                            <input type="text" class="form-control"  id="nama{{$i}}" value="{{$data[$i]->nama_shu}}" name="keterangan" disabled>
                                        </div>
                                        <input type="hidden" id="counti" name="id{{$i}}" value="{{$data[$i]['id']}}">
                                        <input type="hidden" name="jumlah" value="{{count($data)}}">
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="control-label">Persentase <star>*</star></label>
                                            <div class="input-group">
                                                <span class="input-group-addon"></span>
                                                <input type="text" class="qty1 form-control text-right"  id="persen{{$i}}" value="{{$data[$i]->persentase*100}}" name="persen{{$i}}" required="true">
                                                <span class="input-group-addon">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endfor
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label class="control-label">Total Persentase <star>*</star></label>
                                        <div class="input-group">
                                            <input type="hidden" id="total_" name="total">
                                            <input type="text" class="total form-control text-right"disabled>
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Edit Persentase SHU </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal edit status Shu--}}
<div class="modal fade" id="statusSHUModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard2">
            <form id="wizardForm2" method="POST" action="{{route('admin.datamaster.shu.status_shu')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Edit Status SHU</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Tab" data-toggle="tab">Data Rekening SHU</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Tab">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <input type="hidden" id="id_status" name="id_status">
                                        <label for="namaSim" class="control-label">Status Aktivasi <star>*</star></label>
                                        <select class="form-control select2" id="editstatus" name="status" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Aktivasi</option>
                                            <option value="0">NON AKTIFKAN</option>
                                            <option value="1">AKTIF</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Edit Status SHU </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Add Jaminan--}}
<div class="modal fade" id="addJamModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardJ">
            <form id="wizardFormJ" method="POST" action="{{route('admin.datamaster.jaminan.add_jaminan')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Tambah Datamaster Jaminan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Jam" data-toggle="tab">Data Jaminan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Jam">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nama Jaminan <star>*</star></label>
                                        <input type="text" class="form-control text-left"  name="nama" required="true">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="itemRows">
                                <div class="col-md-8 col-md-offset-1" >
                                    <div class="form-group" >
                                        <label for="id_" class="control-label">Field Jaminan <star>*</star></label>
                                        <input type="text" class="form-control text-left" name="field[]" required="true"/>
                                    </div>
                                </div>
                                <div class="col-md-2 " >
                                    <div class="form-group" >
                                        <label for="id_" class="control-label">Add Field <star>*</star></label>
                                        <input onclick="addRow(this.form);" type="button" class="btn btn-fill btn-primary btn-sm " value="Add row" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Tambah Datamaster </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal edit status Jaminan--}}
<div class="modal fade" id="statusJamModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardJ2">
            <form id="wizardFormJ2" method="POST" action="{{route('admin.datamaster.jaminan.status_jaminan')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Edit Status SHU</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Jam2" data-toggle="tab">Data Rekening SHU</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Jam2">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <input type="hidden" id="id_statusJam" name="id_status">
                                        <label for="namaSim" class="control-label">Status Aktivasi <star>*</star></label>
                                        <select class="form-control select2" id="editstatus" name="status" style="width: 100%;" required>
                                            <option class="bs-title-option" selected value="" disabled>-Pilih jenis Aktivasi</option>
                                            <option value="0">NON AKTIFKAN</option>
                                            <option value="1">AKTIF</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Edit Status Jaminan </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>

{{--Modal Edit Jaminan--}}
<div class="modal fade" id="editJamModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardJ3">
            <form id="wizardFormJ3" method="POST" action="{{route('admin.datamaster.jaminan.edit_jaminan')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden"  id="id_editJam" name="id">
                <div class="header text-center">
                    <h3 class="title">Edit Datamaster Jaminan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tab1Jam3" data-toggle="tab">Data Jaminan</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tab1Jam3">
                            <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>
                            <div class="row">
                                <div class="col-md-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Nama Jaminan <star>*</star></label>
                                        <input type="text" class="form-control text-left"  id="enama" name="nama" required="true">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="itemRows2">
                                <div class="col-md-8 col-md-offset-1" >
                                    <div class="form-group" >
                                        <label for="id_" class="control-label">Field Jaminan <star>*</star></label>
                                        <input type="text" class="form-control text-left"  name="field[]" required="true"/>
                                    </div>
                                </div>
                                <div class="col-md-2 " >
                                    <div class="form-group" >
                                        <label for="id_" class="control-label">Add Field <star>*</star></label>
                                        <input onclick="addRow2(this.form);" type="button" class="btn btn-fill btn-primary btn-sm " value="Add row" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Edit Datamaster </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
