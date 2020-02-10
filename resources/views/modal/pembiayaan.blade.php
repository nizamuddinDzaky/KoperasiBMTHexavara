{{--Modal Add Pembiayaan--}}
<div class="modal fade" id="addPemModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCardAdd">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrgLabel">Tambah Pembiayaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.pembiayaan.add_pembiayaan')}}" enctype="multipart/form-data"  id="addPembiayaan">
                {{csrf_field()}}
                <div class="modal-body">
                    <div id="ifInduk" >
                        <div class="form-group">
                            <label for="id_" class="control-label">Pilih Jenis Pembiayaan <star>*</star></label>
                            <select class="form-control select2" id="idRek" name="idRek" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Jenis Pembiayaan</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
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
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening ZIS<star>*</star></label>
                            <select class="form-control select2" id="addrekZis" name="rekZis" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Denda<star>*</star></label>
                            <select class="form-control select2" id="addrekDen" name="rekDen" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Administrasi<star>*</star></label>
                            <select class="form-control select2" id="addrekAdm" name="rekAdm" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Notaris<star>*</star></label>
                            <select class="form-control select2" id="addrekNot" name="rekNot" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pendapatan WO<star>*</star></label>
                            <select class="form-control select2" id="addrekWO" name="rekWO" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Materai<star>*</star></label>
                            <select class="form-control select2" id="addrekMat" name="rekMat" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Asuransi<star>*</star></label>
                            <select class="form-control select2" id="addrekAsu" name="rekAsu" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Provisi<star>*</star></label>
                            <select class="form-control select2" id="addrekProv" name="rekProv" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pendapatan Provisi<star>*</star></label>
                            <select class="form-control select2" id="addrekPpro" name="rekPpro" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Piutang <star>*</star></label>
                            <select class="form-control select2" id="piutang" name="piutang" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih</option>
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="toHideM">
                            <label for="namaSim" class="control-label">M Ditangguhkan<star>*</star></label>
                            <select class="form-control select2" id="addrekMt" name="rekMt" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="namaSim" class="control-label">Jenis Pinjaman <star>*</star></label>
                            <select class="form-control select2" id="pinjam" name="pinjam" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih</option>
                                <option value="1">Jual Beli</option>
                                <option value="2">Bagi Hasil</option>
                                <option value="3">Gadai/Rahn</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 {{ !$errors->has('file') ?: 'has-error' }}">
                            <div class="form-group">
                                <label for="namaSim" class="control-label">Form Akad <star>*</star></label>
                                <span >
                                    <input type="file" onchange="readURL(this);" id="formakad" name="file" accept=".doc, .docx" />
                                </span><br>
                                <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="category"><star>*</star> Required fields</div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Pembiayaan</button>
                </div>
            </form>
        </div>
        </div>
    </div>
</div>

{{--Modal Edit Pembiayaan--}}
<div class="modal fade" id="editPemModal" role="dialog" aria-labelledby="EditPemLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card card-wizard" id="wizardCard">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPemLabel">Edit Pembiayaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="wizardForm" method="POST" action="{{route('admin.datamaster.pembiayaan.edit_pembiayaan')}}" enctype="multipart/form-data"  id="editPembiayaan">
                {{csrf_field()}}
                <input type="hidden" id="id_edit" name="id_">

                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Margin<star>*</star></label>
                            <select class="form-control select2" id="editrekMar" name="rekMar" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening ZIS<star>*</star></label>
                            <select class="form-control select2" id="editrekZis" name="rekZis" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Denda<star>*</star></label>
                            <select class="form-control select2" id="editrekDen" name="rekDen" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Administrasi<star>*</star></label>
                            <select class="form-control select2" id="editrekAdm" name="rekAdm" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row">

                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Notaris<star>*</star></label>
                            <select class="form-control select2" id="editrekNot" name="rekNot" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pendapatan WO<star>*</star></label>
                            <select class="form-control select2" id="editrekWO" name="rekWO" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Materai<star>*</star></label>
                            <select class="form-control select2" id="editrekMat" name="rekMat" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Asuransi<star>*</star></label>
                            <select class="form-control select2" id="editrekAsu" name="rekAsu" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Provisi<star>*</star></label>
                            <select class="form-control select2" id="editrekProv" name="rekProv" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Rekening Pendapatan Provisi<star>*</star></label>
                            <select class="form-control select2" id="editrekPpro" name="rekPpro" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="namaSim" class="control-label">Piutang <star>*</star></label>
                            <select class="form-control select2" id="editpiutang" name="piutang" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih</option>
                                <option value="1">YA</option>
                                <option value="0">TIDAK</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="toHideMedit">
                            <label for="namaSim" class="control-label">M Ditangguhkan<star>*</star></label>
                            <select class="form-control select2" id="editrekMt" name="rekmt" style="width: 100%;" >
                                <option class="bs-title-option" value="">Pilih Rekening</option>
                                @foreach ($dropdown_pembiayaan as $rekening)
                                    <option value="{{ $rekening->id_rekening }}">{{ $rekening->nama_rekening }} {{$rekening->id_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label for="namaSim" class="control-label">Jenis Pinjaman <star>*</star></label>
                            <select class="form-control select2" id="editpinjam" name="pinjam" style="width: 100%;" required>
                                <option class="bs-title-option" value="">Pilih</option>
                                <option value="1">Jual Beli</option>
                                <option value="2">Bagi Hasil</option>
                                <option value="3">Gadai/Rahn</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 {{ !$errors->has('file') ?: 'has-error' }}">
                            <div class="form-group">
                                <label for="namaSim" class="control-label">Form Akad <star>*</star></label>
                                <span >
                                    <input type="file" onchange="readURL(this);" id="eformakad" name="file" accept=".doc, .docx" />
                                </span><br>
                                <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="category"><star>*</star> Required fields</div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Edit Pembiayaan</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

{{--Modal Hapus Pembiayaan--}}
<div class="modal fade" id="delPemModal" role="dialog" aria-labelledby="delPemLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.datamaster.pembiayaan.delete_pembiayaan')}}" enctype="multipart/form-data"  id="delPembiayaan">
                {{csrf_field()}}
                <input type="hidden" id="id_del" name="id_">
                <div class="modal-header">
                    <h5 class="modal-title" id="delPemLabel">Hapus Pembiayaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Hapus Pembiayaan</h4>
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