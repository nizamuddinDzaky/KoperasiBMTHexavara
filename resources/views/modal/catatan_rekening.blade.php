{{--Modal Edit Rekening--}}
<div class="modal fade" id="editCatatanRekening" role="dialog" aria-labelledby="EditRekLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRekLabel">Edit Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.rekening.edit_keterangan_rekening')}}" enctype="multipart/form-data"  id="editRekening">
                {{csrf_field()}}
                <div class="modal-body">

                    <div class="form-group">
                        <input type="hidden" id="id_edit" name="id_">
                        <label for="namaRek" class="control-label">Nama Rekening <star>*</star></label>
                        <input type="text" class="form-control" id="namaRekening" name="namaRek"  required="true" readonly>
                        <input type="hidden" class="form-control" id="idRekening" name="idRek"  required="true" readonly>
                    </div>
                    <div class="form-group">
                        <label for="namaRek" class="control-label">Catatan Rekening <star>*</star></label>
                        <textarea class="form-control" name="catatan" id="catatan" placeholder="Catatan" rows="5"></textarea>
                    </div>

                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Edit Rekening</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal Detail Laporan Keuangan--}}
<div class="modal fade" id="viewCatatanRekening" role="dialog" aria-labelledby="EditRekLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRekLabel">View Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form>
                <div class="modal-body">

                    <div class="form-group">
                        <input type="hidden" id="id_edit" name="id_">
                        <label for="namaRek" class="control-label">Nama Rekening <star>*</star></label>
                        <input type="text" class="form-control" id="vnamaRekening" name="namaRek" readonly>
                    </div>
                    <div class="form-group">
                        <label for="namaRek" class="control-label">Catatan Rekening <star>*</star></label>
                        <textarea class="form-control" name="catatan" id="vcatatan" placeholder="Catatan" rows="5" readonly></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
                </div>
            </form>
        </div>
    </div>
</div>