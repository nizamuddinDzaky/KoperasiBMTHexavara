{{--Modal Edit Status Pengajuan--}}
<div class="modal fade" id="editStatusModal" role="dialog" aria-labelledby="StatusLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" @if(Auth::user()->tipe=="admin")action="{{route('admin.pengajuan.status')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.pengajuan.status')}}" @endif enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_status" name="id_">
                <input type="hidden" id="id_status_user" name="id_user">
                <input type="hidden" id="id_status_detail" name="detail">
                <div class="modal-header">
                    <h5 class="modal-title" id="StatusLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label class="control-label">Ubah Status</label>
                                <select  name="status" class="form-control" required="true">
                                    <option selected="" disabled="">- Pilih -</option>
                                    <option value="Disetujui">Setujui</option>
                                    <option value="Ditolak">Tolak</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label class="control-label">Keterangan</label>
                                <input class="form-control"
                                       type="text"
                                       name="keterangan"
                                       value=""
                                       required="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Ubah Status</button>
                </div>
            </form>
        </div>
    </div>
</div>