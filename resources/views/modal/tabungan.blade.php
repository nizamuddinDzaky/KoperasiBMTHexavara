{{--Modal Add Tabungan--}}
<div class="modal fade" id="addTabModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrgLabel">Tambah Tabungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.tabungan.add_tabungan')}}" enctype="multipart/form-data"  id="addTabungan">
                {{csrf_field()}}
                <div class="modal-body">
                    <div id="ifInduk" >
                        <div class="form-group">
                            <label for="id_" class="control-label">Pilih Jenis Tabungan <star>*</star></label>
                            <select class="form-control select2" id="idRek" name="idRek" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Jenis Tabungan</option>
                                @foreach ($dropdown_tabungan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Margin <star>*</star></label>
                            <select class="form-control select2" id="addrekMar" name="rekMar" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_tabungan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pendapatan<star>*</star></label>
                            <select class="form-control select2" id="addrekPen" name="rekPen" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_tabungan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Nisbah Anggota <star>*</star></label>
                            <input type="text" class="form-control" name="nisbah" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Nasabah wajib pajak <star>*</star></label>
                            <select class="form-control select2" name="wajib" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih</option>
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Nasabah bayar zis <star>*</star></label>
                            <select class="form-control select2" name="zis" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih </option>
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                      </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Saldo minimal<star>*</star></label>
                            <input type="text" class="form-control" name="saldo" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Setoran awal <star>*</star></label>
                            <input type="text" class="form-control" name="awal" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Setoran minimal <star>*</star></label>
                            <input type="text" class="form-control" name="setMin" required="true">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Saldo minimal margin<star>*</star></label>
                            <input type="text" class="form-control" name="minMar" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Adm. tutup tabungan <star>*</star></label>
                            <input type="text" class="form-control" name="tutup" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Pemeliharaan <star>*</star></label>
                            <input type="text" class="form-control" name="pemeliharaan" required="true">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Adm. passif<star>*</star></label>
                            <input type="text" class="form-control" name="passif" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Adm. buka baru <star>*</star></label>
                            <input type="text" class="form-control" name="buka" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Adm. ganti buku <star>*</star></label>
                            <input type="text" class="form-control" name="buku" required="true">
                        </div>
                    </div>

                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Tabungan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal Edit Tabungan--}}
<div class="modal fade" id="editTabModal" role="dialog" aria-labelledby="EditTabLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTabLabel">Edit Tabungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.tabungan.edit_tabungan')}}" enctype="multipart/form-data"  id="editTabungan">
                {{csrf_field()}}
                <input type="hidden" id="id_edit" name="id_">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Margin <star>*</star></label>
                            <select class="form-control select2" id="editrekMar" name="rekMar" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_tabungan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pendapatan<star>*</star></label>
                            <select class="form-control select2" id="editrekPen" name="rekPen" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_tabungan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Nisbah Anggota <star>*</star></label>
                            <input type="text" class="form-control" id="nisbah" name="nisbah" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Nasabah wajib pajak <star>*</star></label>
                            <select class="form-control select2" id="wajib" name="wajib" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih</option>
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Nasabah bayar zis <star>*</star></label>
                            <select class="form-control select2" id="zis" name="zis" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih </option>
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Saldo minimal<star>*</star></label>
                            <input type="text" class="form-control" id="saldo" name="saldo" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Setoran awal <star>*</star></label>
                            <input type="text" class="form-control" id="awal" name="awal" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Setoran minimal <star>*</star></label>
                            <input type="text" class="form-control" id="setMin" name="setMin" required="true">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Saldo minimal margin<star>*</star></label>
                            <input type="text" class="form-control" id="minMar" name="minMar" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Adm. tutup tabungan <star>*</star></label>
                            <input type="text" class="form-control" id="tutup" name="tutup" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Pemeliharaan <star>*</star></label>
                            <input type="text" class="form-control" id="pemeliharaan" name="pemeliharaan" required="true">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Adm. passif<star>*</star></label>
                            <input type="text" class="form-control" id="passif" name="passif" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Adm. buka baru <star>*</star></label>
                            <input type="text" class="form-control" id="baru" name="buka" required="true">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="namaSim" class="control-label">Adm. ganti buku <star>*</star></label>
                            <input type="text" class="form-control" id="buku" name="buku" required="true">
                        </div>
                    </div>


                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Edit Tabungan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal Hapus Tabungan--}}
<div class="modal fade" id="delTabModal" role="dialog" aria-labelledby="delTabLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.datamaster.tabungan.delete_tabungan')}}" enctype="multipart/form-data"  id="delTabungan">
                {{csrf_field()}}
                <input type="hidden" id="id_del" name="id_">
                <div class="modal-header">
                    <h5 class="modal-title" id="delTabLabel">Hapus Tabungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Hapus Tabungan</h4>
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