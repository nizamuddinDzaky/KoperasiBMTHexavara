<div class="modal fade" id="showTandaTanganRapat" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 60%; height: 100%">
        <div class="card card-wizard" id="wizardCardReset">
            <form id="wizardFormReset" method="POST" action="{{route('reset_password.anggota')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="header text-center">
                    <h3 class="title" id="tanda_tangan_title">Tanda Tangan</h3>
                    <p class="category">BMT MANDIRI UKHUWAH PERSADA</p>
                </div>

                <div class="content">

                    <div class="row">
                        <div class="col-md-12" style="text-align: center">
                            <img src="" alt="tanda_tangan" id="gambar_tanda_tangan">
                        </div>
                    </div>

                </div>

                <button data-dismiss="modal"   class="btn btn-secondary btn-fill pull-right" style="margin-bottom: 2.5%" >Tutup</button>
                <div class="clearfix"></div>

            </form>

        </div>
    </div>
</div>