<div class="modal fade" id="editVisiMisiModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" action="{{route('admin.landingpage.visimisi.update')}}"  enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Edit Moto Visi Misi </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabVisiMisi" data-toggle="tab">Moto Visi Misi</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabVisiMisi">

                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Moto <star>*</star></label>
                                        <textarea class="summernote" name="moto" id="moto" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Visi <star>*</star></label>
                                        <textarea class="summernote" name="visi" id="visi" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Misi <star>*</star></label>
                                        <textarea class="summernote" name="misi" id="misi" required></textarea>
                                    </div>
                                </div>
                            </div>
{{--                            <div class="row">--}}
{{--                                <div class="col-sm-12 col-md-5 col-lg-5 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label>Upload Gambar <star>*</star></label><br>--}}
{{--                                        <span class="btn btn-info btn-fill btn-file center-block"> Browse--}}
{{--                                            <input type="file" onchange="readURL(this);" class="bukti" name="file" accept=".jpg, .png, .jpeg|images/*">--}}
{{--                                        </span><br><br>--}}
{{--                                        <span class="help-block text-danger">{{ $errors->first('file') }}</span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="text-center">--}}
{{--                                    <img style="margin: auto;width:100px;height:auto" class="pic" src=""/>--}}
{{--                                </div>--}}
{{--                            </div>--}}
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
