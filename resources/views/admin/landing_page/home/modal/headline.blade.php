<div class="modal fade" id="editHeadlineModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard wizardCard">
            <form class="wizardForm" method="POST" action="{{route('admin.landingpage.headline.update')}}"  enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="header text-center">
                    <h3 class="title">Edit Headline </h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">
                    <ul class="nav">
                        <li><a href="#tabHeadline" data-toggle="tab">Headline</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="tabHeadline">

                                <div class="row">
                                    <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Title <star>*</star></label>
                                            <input type="text" class="form-control text-left"  id="titleHeadline" name="title">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="namaSim" class="control-label">Subtitle <star>*</star></label>
                                            <input type="text" class="form-control text-left"  id="subtitleHeadline" name="subtitle">
                                        </div>
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-10 col-lg-10 col-md-offset-1">
                                    <div class="form-group">
                                        <label for="namaSim" class="control-label">Deskripsi / Alamat <star>*</star></label>
                                        <textarea class="summernote" name="deskripsi" id="deskripsiHeadline"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-5 col-lg-5 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">
                                    <div class="form-group">
                                        <label>Upload Gambar Headline <star>*</star></label><br>
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

                <div class="footer">
                    <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish pull-right">Update </button>
                    <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-right: 0.5em">Batal</button>
                    <div class="clearfix"></div>
                </div>
            </form>

        </div>
    </div>
</div>
