<div class="modal fade" id="reactiveUsrModal" role="dialog" aria-labelledby="reactiveUsrLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{route('admin.datamaster.anggota.reactive_anggota')}}" enctype="multipart/form-data"  id="passUsr">
                {{csrf_field()}}
                <input type="hidden" id="id_usr_react" name="no_ktp">
                <div class="modal-body">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reactiveUsrLabel"></h5>
                        <h6 class="modal-title">Perubahan password dibutuhkan untuk meng-aktifkan ulang akun</h6>
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