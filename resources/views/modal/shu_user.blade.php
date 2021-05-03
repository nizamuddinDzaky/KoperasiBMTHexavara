<div class="modal fade" id="modalShuUser" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 60%; height: 100%">
        <div class="card card-wizard" id="wizardCardReset">
            <form id="wizardFormReset" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title">Detail SHU</h3>
                    <p class="category">{{Auth::user()->nama}} ( {{Auth::user()->alamat}} )</p>
                </div>

                <div class="content">

                            <div class="row">
                                <div class="col-md-8 col-md-offset-2 ">
                                    <div class="form-group">
                                        <label for="id_" class="control-label">SHU PENGELOLA <star>*</star></label>
                                        <input type="text" class="form-control" readonly value="{{number_format($shu_user['shu_pengelola'],2)}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="id_" class="control-label">SHU PENGURUS <star>*</star></label>
                                    <input type="text" class=" form-control" readonly value="{{number_format($shu_user['shu_pengurus'],2)}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="id_" class="control-label">SHU SIMPANAN <star>*</star></label>
                                    <input type="text" class=" form-control" readonly value="{{number_format($shu_user['shu_simpanan'],2)}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="id_" class="control-label">SHU MARGIN <star>*</star></label>
                                    <input type="text" class=" form-control" readonly value="{{number_format($shu_user['shu_margin'],2)}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="form-group">
                                    <label for="id_" class="control-label">TOTAL SHU ANGGOTA <star>*</star></label>
                                    <input type="text" class=" form-control" readonly value="{{number_format($shu_user['total_shu_anggota'],2)}}">
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="clearfix"></div>

            </form>

        </div>
    </div>
</div>