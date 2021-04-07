{{--Modal Add--}}
<div class="modal fade" id="addObjectPengajuanMRB" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrgLabel">Tambah Object Pengajuan MRB</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('admin.datamaster.anggota.add_item_pengajuan_mrb')}}" enctype="multipart/form-data"  id="addUsr">
                {{csrf_field()}}
                <div class="modal-body">
                    
                    <div class="form-group{{ $errors->has('nama') ? 'errors' : '' }}" id="toHideUsr">
                        <label for="idUsr" class="control-label">Nama <star>*</star></label>
                        <input type="text" placeholder="Nama" class="form-control" id="input-add-nama" name="nama" value="{{ old('nama') }}"required/>
                        @if ($errors->has('nama'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nama') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="input-add-is_active" name="is_active">
                        <label class="form-check-label" for="input-add-is_active">
                            Aktif
                        </label>
                    </div>
                    
                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal EDIT--}}

<div class="modal fade" id="editObjectPengajuanMRB" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrgLabel">Ubah Object Pengajuan MRB</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('admin.datamaster.anggota.edit_item_pengajuan_mrb')}}" enctype="multipart/form-data"  id="addUsr">
                {{csrf_field()}}
                <div class="modal-body">
                    
                    <div class="form-group{{ $errors->has('id') ? 'errors' : '' }} hide" id="toHideUsr">
                        <label for="idUsr" class="control-label">id <star>*</star></label>
                        <input type="text" placeholder="Nama" class="form-control" id="input-edit-id" name="id" value="{{ old('id') }}"required
                        />
                        @if ($errors->has('nama'))
                            <span class="help-block">
                                <strong>{{ $errors->first('id') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('nama') ? 'errors' : '' }}" id="toHideUsr">
                        <label for="idUsr" class="control-label">Nama <star>*</star></label>
                        <input type="text" placeholder="Nama" class="form-control" id="input-edit-nama" name="nama" value="{{ old('nama') }}"required
                        />
                        @if ($errors->has('nama'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nama') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="input-edit-is_active" name="is_active">
                        <label class="form-check-label" for="input-edit-is_active">
                            Aktif
                        </label>
                    </div>
                    
                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </div>
            </form>
        </div>
    </div>
</div>