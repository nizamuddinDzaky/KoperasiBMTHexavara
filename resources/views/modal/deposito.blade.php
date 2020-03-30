{{--Modal Add Deposito--}}
<div class="modal fade" id="addDepModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrgLabel">Tambah Mudharabah Berjangka</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.deposito.add_deposito')}}" enctype="multipart/form-data"  id="addDeposito">
                {{csrf_field()}}
                <div class="modal-body">
                    <div id="ifInduk" >
                        <div class="form-group">
                            <label for="id_" class="control-label">Pilih Jenis Mudharabah Berjangka <star>*</star></label>
                            <select class="form-control select2" id="idRek" name="idRek" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Jenis Mudharabah Berjangka</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Margin<star>*</star></label>
                            <select class="form-control select2" id="addrekMar" name="rekMar" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pajak Margin<star>*</star></label>
                            <select class="form-control select2" id="addrekPaj" name="rekPaj" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Jatuh Tempo<star>*</star></label>
                            <select class="form-control select2" id="addrekTemp" name="rekTemp" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Cadangan Margin<star>*</star></label>
                            <select class="form-control select2" id="addrekCad" name="rekCad" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pinalti<star>*</star></label>
                            <select class="form-control select2" id="addrekPin" name="rekPin" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Anggota wajib pajak <star>*</star></label>
                            <select class="form-control select2" name="wajib" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih</option>
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Jangka waktu (BULAN)<star>*</star></label>
                            <input type="number" class="form-control" name="waktu" required="true">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Nisbah Anggota <star>*</star></label>
                            <input type="text" class="form-control" name="nisbah" required="true">
                        </div>
                      </div>

                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Mudharabah Berjangka</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal Edit Deposito--}}
<div class="modal fade" id="editDepModal" role="dialog" aria-labelledby="EditDepLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDepLabel">Edit Mudharabah Berjangka</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.deposito.edit_deposito')}}" enctype="multipart/form-data"  id="editDeposito">
                {{csrf_field()}}
                <input type="hidden" id="id_edit" name="id_">
                <div class="modal-body">

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Margin<star>*</star></label>
                            <select class="form-control select2" id="editrekMar" name="rekMar" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pajak Margin<star>*</star></label>
                            <select class="form-control select2" id="editrekPaj" name="rekPaj" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Jatuh Tempo<star>*</star></label>
                            <select class="form-control select2" id="editrekTemp" name="rekTemp" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Cadangan Margin<star>*</star></label>
                            <select class="form-control select2" id="editrekCad" name="rekCad" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pinalti<star>*</star></label>
                            <select class="form-control select2" id="editrekPin" name="rekPin" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_deposito as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Anggota wajib pajak <star>*</star></label>
                            <select class="form-control select2" id="edwajib" name="wajib" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih</option>
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Jangka waktu (BULAN)<star>*</star></label>
                            <input type="number" class="form-control" id="edwaktu" name="waktu" required="true">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Nisbah Anggota <star>*</star></label>
                            <input type="text" class="form-control" id="ednisbah" name="nisbah" required="true">
                        </div>
                    </div>
                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Edit MDB</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{--Modal Hapus Deposito--}}
<div class="modal fade" id="delDepModal" role="dialog" aria-labelledby="delDepLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.datamaster.deposito.delete_deposito')}}" enctype="multipart/form-data"  id="delDeposito">
                {{csrf_field()}}
                <input type="hidden" id="id_del" name="id_">
                <div class="modal-header">
                    <h5 class="modal-title" id="delDepLabel">Hapus Mudharabah Berjangka</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Hapus Mudharabah Berjangka</h4>
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