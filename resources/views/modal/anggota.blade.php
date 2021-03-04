{{--Modal Add User--}}
<div class="modal fade" id="addUsrModal" role="dialog" aria-labelledby="addOrgLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrgLabel">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{route('admin.datamaster.anggota.add_anggota')}}" enctype="multipart/form-data"  id="addUsr">
                {{csrf_field()}}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tipeUsr" class="control-label" >Tipe User <star>*</star></label>
                        <select {{--<onchange="IndukCheck(this)">--}} id="jenistipe" class="form-control" id="tipeUsrAdd" name="tipe" required="true">
                            <option value="0" disabled selected>Pilih Tipe</option>
                            <option value="anggota">Anggota</option>
                            <option value="teller">Teller</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tipeUsr" class="control-label" >Role User <star>*</star></label>
                        <select {{--<onchange="IndukCheck(this)">--}} id="jenistipe" class="form-control"  name="role" required="true">
                            <option value="0" disabled selected>Pilih Role</option>
                            <option value="anggota">Anggota</option>
                            <option value="pengurus">Pengurus</option>
                            <option value="pengelolah">Pengelolah</option>
                            <option value="pengelolah&pengurus">Pengelolah & Pengurus</option>
                        </select>
                    </div>

                    <div class="form-group" id="toShow">
                        <label for="tipeUsr" class="control-label">Rekening KAS <star>*</star></label>
                        <select {{--<onchange="IndukCheck(this)">--}} class="form-control" id="idrekteller" name="idteller" required="true">
                            <option value="0" disabled selected>Pilih Tipe</option>
                            @foreach ($dropdown7 as $rekening)
                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group{{ $errors->has('no_ktp') ? 'errors' : '' }}" id="toHideUsr">
                        <label for="idUsr" class="control-label">No KTP <star>*</star></label>
                        <input type="text" placeholder="Nomor KTP" class="form-control" id="idUsr" name="no_ktp" value="{{ old('no_ktp') }}"required
                               {{--oninvalid="setCustomValidity('No KTP harus 16 digit!')"--}}
                               {{--onchange="try{setCustomValidity('')}catch(e){}" />--}}
                        />
                        @if ($errors->has('no_ktp'))
                            <span class="help-block">
                                <strong>{{ $errors->first('no_ktp') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group {{ $errors->has('nama') ? ' has-error' : '' }}">
                        <label for="namaUsr" class="control-label">Nama User <star>*</star></label>
                        <input type="text" placeholder="Nama" class="form-control" id="namaUsr" name="nama" value="{{ old('nama') }}" required="true">
                        @if ($errors->has('nama'))
                            <span class="help-block">
                                                <strong>{{ $errors->first('nama') }}</strong>
                                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('alamat') ? ' has-error' : '' }}">
                        <label for="alamatUsr" class="control-label">Alamat User <star>*</star></label>
                        <input type="text" placeholder="Alamat" class="form-control" id="alamatUsr" name="alamat"  value="{{ old('alamat') }}" required>
                        @if ($errors->has('alamat'))
                            <span class="help-block">
                                                <strong>{{ $errors->first('alamat') }}</strong>
                                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="control-label">Password User <star>*</star></label>
                        <input type="password" placeholder="Password" class="form-control" name="password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                        @endif
                    </div>
                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Modal Edit User--}}
<div class="modal fade" id="editUsrModal" role="dialog" aria-labelledby="EditUsrLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUsrLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" action="{{route('admin.datamaster.anggota.edit_anggota')}}" enctype="multipart/form-data"  id="editUsr">
                {{csrf_field()}}
                <input type="hidden" id="id_edit" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_usr_edit" class="control-label">No KTP <star>*</star></label>
                        <input type="text" class="form-control" id="id_usr_edit" name="no_ktp" required="true">
                    </div>

                    <div class="form-group">
                        <label for="nama_usr" class="control-label">Nama User <star>*</star></label>
                        <input type="text" class="form-control" id="nama_usr" name="nama" required="true">
                    </div>

                    <div class="form-group">
                        <label for="alamat_usr" class="control-label">Alamat User <star>*</star></label>
                        <input type="text" class="form-control" id="alamat_usr" name="alamat" required="true">
                    </div>

                    <div class="form-group">
                        <label for="tipe_usr" class="control-label">Tipe User <star>*</star></label>
                        <select {{--<onchange="IndukCheck(this)">--}} class="form-control" id="tipe_usr" name="tipe" required="true">
                            <option value="0" disabled selected>Pilih Tipe</option>
                            <option value="anggota">Anggota</option>
                            <option value="teller">Teller</option>
                        </select>
                    </div>
                    <div class="form-group" id="toShow2">
                        <label for="tipeUsr" class="control-label">Rekening KAS <star>*</star></label>
                        <select  class="form-control" id="idrekteller2" name="idteller">
                            <option value="0" disabled selected>Pilih Rekening KAS</option>
                            @foreach ($dropdown7 as $rekening)
                                <option value="{{ $rekening->id }}"> [{{$rekening->id_rekening }}] {{ $rekening->nama_rekening }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" id="toShow3">
                        <label for="kotaTeller" class="control-label"> Kota <star>*</star></label>
                        <input  type="text" class="form-control" id="kotateller2" name="kotateller" required>
                    </div>
                    <div class="form-group">
                        <label for="tipeUsr" class="control-label" >Role User <star>*</star></label>
                        <select {{--<onchange="IndukCheck(this)">--}} id="role" class="form-control"  name="role" required="true">
                            <option value="0" disabled selected>Pilih Role</option>
                            <option value="anggota">Anggota</option>
                            <option value="pengurus">Pengurus</option>
                            <option value="pengelolah">Pengelolah</option>
                            <option value="pengelolah&pengurus">Pengelolah & Pengurus</option>
                        </select>
                    </div>


                    <div class="category"><star>*</star> Required fields</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Edit User</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{--Modal Hapus User--}}
<div class="modal fade" id="delUsrModal" role="dialog" aria-labelledby="delUsrLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.datamaster.anggota.delete_anggota')}}" enctype="multipart/form-data"  id="delUsr">
                {{csrf_field()}}
                <input type="hidden" id="id_del" name="id_">
                <div class="modal-header">
                    <h5 class="modal-title" id="delUsrlabel">Hapus User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>Hapus User</h4>
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

{{--Edit Password User--}}
<div class="modal fade" id="editPassUsrModal" role="dialog" aria-labelledby="editPassUsrLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.datamaster.anggota.pwd_anggota')}}" enctype="multipart/form-data"  id="passUsr">
                {{csrf_field()}}
                <input type="hidden" id="id_usr_p" name="no_ktp">
                <div class="modal-body">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPassUsrlabel">Ubah Password User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" placeholder="Password" class="form-control" name="password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="password" placeholder="Password Confirmation" class="form-control" name="password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ubah Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--Edit Password User--}}
<div class="modal fade" id="editFoto" role="dialog" aria-labelledby="editFotoLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('upload.foto')}}" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="id_usr_pic" name="no_ktp">
                <div class="modal-body">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPassUsrlabel">Edit Foto Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <style>
                        article, aside, figure, footer, header, hgroup,
                        menu, nav, section { display: block; }
                        .btn-file {
                            position: relative;
                            overflow: hidden;
                        }
                        .btn-file input[type=file] {
                            position: absolute;
                            top: 0;
                            right: 0;
                            min-width: 100%;
                            min-height: 100%;
                            font-size: 100px;
                            text-align: right;
                            filter: alpha(opacity=0);
                            opacity: 0;
                            outline: none;
                            background: white;
                            cursor: inherit;
                            display: block;
                        }
                    </style>

                    <div class="col-md-10 col-md-offset-1 {{ !$errors->has('file') ?: 'has-error' }}">
                        <div class="form-group {{ !$errors->has('file') ?: 'has-error' }}">
                            <br><br><label> Unggah Foto</label><br>
                            <span class="btn btn-info btn-fill btn-file">Browse
                                <input type="file" onchange="readURL4(this);" name="file" accept=".jpg, .png, .jpeg|images/*"/>
                            </span><br><br>
                            <div class="text-center">
                                @if(Auth::user()->pathfile != null && json_decode(Auth::user()->pathfile, true)['profile'] != null)
                                <img style="width:400px;height:auto;margin: auto" id="pic4" src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['profile'])}}"/>
                                @else
                                <img style="width:400px;height:auto;margin: auto" id="pic4"/>
                                @endif
                            </div>
                            <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Upload Foto</button>
                </div>
            </form>
        </div>
    </div>
</div>
