{{--Modal edit rapat --}}
<div class="modal fade" id="editRapatModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="container-fluid">
        <div class="modal-dialog" role="document">
            <div class="card card-wizard wizardCard">
                <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('rapat.update')}}" @ENDIF enctype="multipart/form-data">
                    {{csrf_field()}}
                    @if(Auth::user()->tipe!="anggota")
                        <input type="hidden" name="teller" value="teller">
                        <input type="hidden" name="id" id="id_rapat">
                    @endif
                    <div class="header text-center">
                        <h3 class="title">Edit Rapat</h3>
                        <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                    </div>

                    <div class="content">
                        <ul class="nav">
                            <li><a href="#tabEditRapat" data-toggle="tab">Data Rapat</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="tabEditRapat">
                                <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>

                                
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Judul Rapat <star>*</star></label>
                                            <input type="text" class="form-control" name="judul" id="judul_rapat" placeholder="Pilih Judul Rapat">
                                        </div>
                                    </div>
                                
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Tanggal Berakhir <star>*</star></label>
                                            <input type="text" name="tanggal_berakhir" class="form-control datepicker" id="tanggal_berakhir" placeholder="Tanggal Berakhir">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Deskripsi <star>*</star></label>
                                            <textarea class="summernote" id="deskripsi" name="deskripsi"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">
                                        <div class="form-group">
                                            <label>Upload Cover</label><br>
                                            <span class="btn btn-info btn-fill btn-file"> Browse
                                                <input type="file" onchange="readURL(this)" name="file" accept=".JPG, .jpg, .png, .jpeg|images/*"/>
                                            </span><br><br>
                                            <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img style="margin: auto; width:200px;" class="pic" src=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="footer">
                        <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Update</button>
                        <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                        <div class="clearfix"></div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>