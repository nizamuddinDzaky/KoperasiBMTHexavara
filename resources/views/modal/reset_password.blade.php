<div class="modal fade" id="resetPasswordModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 60%; height: 100%">
        <div class="card card-wizard" id="wizardCardReset">
            <form id="wizardFormReset" method="POST" action="{{route('reset_password.anggota')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Reset Password</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2 ">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Password Lama <star>*</star></label>
                                        <input type="text" class="form-control"  name="passwordLama" required="true" minlength="6">
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Password Baru <star>*</star></label>
                                        <input type="password" class=" form-control"  name="passwordBaru" required="true" minlength="6">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">Ulangi Password <star>*</star></label>
                                        <input type="password" class=" form-control"  name="ulangiPassword" required="true" minlength="6">
                                    </div>
                                </div>
                            </div>


                    </div>

                <button type="submit" class="btn btn-info btn-fill pull-right" style="margin-bottom: 2.5%" >Reset Password</button>
                <button data-dismiss="modal"   class="btn btn-secondary btn-fill pull-right" style="margin-bottom: 2.5%" >Cancel</button>
                <div class="clearfix"></div>

            </form>

        </div>
    </div>
</div>