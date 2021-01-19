<div class="modal fade" id="deleteAngsuranModal" role="dialog" aria-labelledby="delDepLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('teller.hapus.angsuran')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_angsuran_to_delete" name="id">
                <input type="hidden" id="nama_to_delete" name="nama">
                <div class="modal-header">
                    <h5 class="modal-title" id="delDepLabel">Hapus Data Angsuran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Yakin Akan Menghapus Data Angsuran</h4>
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