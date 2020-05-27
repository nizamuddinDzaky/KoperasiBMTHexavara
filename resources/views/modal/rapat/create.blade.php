{{--Modal Create rapat baru --}}
<div class="modal fade" id="createRapatModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="container-fluid">
        <div class="modal-dialog" role="document">
            <div class="card card-wizard wizardCard">
                <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('rapat.store')}}" @ENDIF enctype="multipart/form-data">
                    {{csrf_field()}}
                    @if(Auth::user()->tipe!="anggota")
                        <input type="hidden" name="teller" value="teller">
                    @endif
                    <div class="header text-center">
                        <h3 class="title">Buat Rapat Baru</h3>
                        <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                    </div>

                    <div class="content">
                        <ul class="nav">
                            <li><a href="#tab1PelunasanLebihAwal" data-toggle="tab">Data Rapat</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="tab1PelunasanLebihAwal">
                                <h5 class="text-center">Pastikan kembali data yang anda masukkan sudah benar!</h5>

                                
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Judul Rapat <star>*</star></label>
                                            <input type="text" class="form-control" name="judul" placeholder="Pilih Judul Rapat">
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Tanggal Berakhir <star>*</star></label>
                                            <input type="text" name="tanggal_berakhir" class="form-control datepicker" placeholder="Tanggal Berakhir">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Deskripsi <star>*</star></label>
                                            <textarea class="summernote" name="deskripsi"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">
                                        <div class="form-group">
                                            <label>Upload Cover</label><br>
                                            <span class="btn btn-info btn-fill btn-file"> Browse
                                                <input type="file" onchange="readURL(this)" name="file" accept=".jpg, .png, .jpeg|images/*" required="true"/>
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
                        <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Buat </button>
                        <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                        <div class="clearfix"></div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>