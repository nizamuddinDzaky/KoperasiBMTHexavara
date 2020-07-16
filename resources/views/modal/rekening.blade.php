{{--Modal Add Rekening--}}
<div class="modal fade" id="addRekModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrgLabel">Tambah Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.rekening.add_rekening')}}" enctype="multipart/form-data"  id="addRekening">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tipeRek" class="control-label">Tipe Rekening <star>*</star></label>
                        <select {{--<onchange="IndukCheck(this)">--}} class="form-control" id="tipeRekAdd" name="tipeRek" required="true">
                            <option value="0" disabled selected>Pilih Tipe</option>
                            <option value="master">MASTER</option>
                            <option value="induk">INDUK</option>
                            <option value="detail">DETAIL</option>
                        </select>
                    </div>

                    <div id="Induk_" >
                        <div class="form-group">
                            <label for="id_rekening" class="control-label">Pilih Induk <star>*</star></label>
                            <select class="form-control select2" id="selRekening" name="id_rekening" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Induk</option>
                                <option class="bs-title-option" id="master_" value="master">[MASTER]</option>
                                @foreach ($dropdown_rekening as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} [{{$rekening->id_rekening }}]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="namaRek" class="control-label">Nama Rekening <star>*</star></label>
                        <input type="text" class="form-control" id="namaRek" name="namaRek" required="true">
                    </div>

                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Rekening</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal Edit Rekening--}}
<div class="modal fade" id="editRekModal" role="dialog" aria-labelledby="EditRekLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRekLabel">Edit Rekening</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.rekening.edit_rekening')}}" enctype="multipart/form-data"  id="editRekening">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tipeRek" class="control-label">Tipe Rekening <star>*</star></label>
                        <select {{--<onchange="IndukCheck(this)">--}} class="form-control" id="tipeRek" name="tipeRek" required="true">
                            <option value="0" disabled selected>Pilih Tipe</option>
                            <option value="master">Master</option>
                            <option value="induk">Induk</option>
                            <option value="detail">Detail</option>
                        </select>
                    </div>

                    <div id="ifInduk" >
                        <div class="form-group">
                            <label for="idRek" class="control-label">Pilih Induk <star>*</star></label>
                            <select class="form-control select2" id="indukRek" name="indukRek" style="width: 100%;" required>
                                @foreach ($dropdown_rekening as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                                <option value="master"> MASTER 0 </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="id_edit" name="id_">
                        <label for="namaRek" class="control-label">Nama Rekening <star>*</star></label>
                        <input type="text" class="form-control" id="namaRekening" name="namaRek"  required="true">
                    </div>
                    <div class="form-group">
                        <label for="namaRek" class="control-label">Kategori Rekening </label>
                        <input type="text" class="form-control" id="kategori" name="kategori" >
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


{{--Modal Hapus Rekening--}}
<div class="modal fade" id="delRekModal" role="dialog" aria-labelledby="delRekLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.datamaster.rekening.delete_rekening')}}" enctype="multipart/form-data"  id="delRekening">
                {{csrf_field()}}
                <input type="hidden" id="id_del" name="id_">
                <div class="modal-header">
                    <h5 class="modal-title" id="delReklabel">Hapus Rekening</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Hapus Rekening</h4>
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