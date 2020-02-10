var url_add;
var url_edit;
var url_delete;
var title_;
var area_sel;
var rayon_sel;

add_org = {
    showSwal: function () {
        title_ = "Tambah Rekening";

        swal({
                title: title_,
                html:
                '<label>ID Organisasi</label>' +
                '<input id="add_id_org" class="form-control" type="number" required>' +
                '<label>Nama Organisasi</label>' +
                '<input id="add_nama" class="form-control" required>' +
                '<label>Tipe Organisasi</label><br>' +
                '<select id="add_tipe" class="selectpicker" data-title="Single Select" data-style="btn-default btn-block" data-menu-style="dropdown-blue" required>' +
                '<option value="2">Area</option>' +
                '<option value="3">Rayon</option>' +
                '</select><br>' +
                '<label>Alamat Organisasi</label>' +
                '<input id="add_alamat" class="form-control" required>' +
                '<label>Password</label>' +
                '<input id="add_password" type="password" class="form-control" required>',
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            },
            function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(url_add,
                    {
                        id_org: $('#add_id_org').val(),
                        nama: $('#add_nama').val(),
                        tipe: $('#add_tipe').val(),
                        alamat: $('#add_alamat').val(),
                        pass: $('#add_password').val()
                    }, function(data, status){
                        if (status === "success" && data === "1")
                            swal({
                                title: "Data telah ditambah",
                                type: "success"
                            }, location.reload());
                        else
                            swal({
                                title: "Error",
                                type: "error"
                            }, location.reload());
                    });
            });
    }
};

edit_org = {
    showSwal: function (id_, id_org_, nama_, tipe_, alamat_) {
        title_ = "Edit Organisasi";
        area_sel = "";
        rayon_sel = "";

        if (tipe_ === 2)
            area_sel = 'selected="selected"';
        else if (tipe_ === 3)
            rayon_sel = 'selected="selected"';

        swal({
                title: title_,
                html:
                '<label>ID Organisasi</label>' +
                '<input id="edit_id_org" class="form-control" type="number" value='+ id_org_ +' required>' +
                '<label>Nama Organisasi</label>' +
                '<input id="edit_nama" class="form-control" value="'+ nama_ +'" required>' +
                '<label>Tipe Organisasi</label><br>' +
                '<select id="edit_tipe" class="selectpicker" data-title="Single Select" data-style="btn-default btn-block" data-menu-style="dropdown-blue" required>' +
                '<option value="2"' + area_sel + '>Area</option>' +
                '<option value="3"' + rayon_sel + '>Rayon</option>' +
                '</select><br>' +
                '<label>Alamat Organisasi</label>' +
                '<input id="edit_alamat" class="form-control" value="' + alamat_ +'" required>',
                showCancelButton: true,
                closeOnConfirm: false,
                allowOutsideClick: false,
                showLoaderOnConfirm: true
            },
            function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(url_edit,
                    {
                        task: 1,
                        id: id_,
                        id_org: $('#edit_id_org').val(),
                        nama: $('#edit_nama').val(),
                        tipe: $('#edit_tipe').val(),
                        alamat: $('#edit_alamat').val()
                    }, function(data, status){
                        console.log(data);
                        if (status === "success" && data === "1")
                            swal({
                                title: "Data telah diubah",
                                type: "success"
                            }, location.reload());
                        else
                            swal({
                                title: "Error",
                                type: "error"
                            }, location.reload());
                    });
            })
    }
};

hapus_org = {
    showSwal: function (id_, nama_, tipe_) {
        title_ = "Hapus Organisasi";

        var tipe__;

        if (tipe_ === 2)
            tipe__ = "Area";
        else if (tipe_ === 3)
            tipe__ = "Rayon";

        swal({
                title: title_,
                text: tipe__ + " " + nama_ +" akan dihapus!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-info btn-fill",
                confirmButtonText: "Hapus",
                cancelButtonClass: "btn btn-danger btn-fill",
                cancelButtonText: "Batal",
                closeOnConfirm: false
            },
            function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(url_delete,
                    {
                        id: id_
                    }, function(data, status){
                        if (status === "success" && data === "1")
                            swal({
                                title: "Organisasi telah dihapus",
                                type: "success"
                            }, location.reload());
                        else
                            swal({
                                title: "Error",
                                type: "error"
                            }, location.reload());
                    });
            });
    }
};

edit_pass = {
    showSwal: function (id_) {
        title_ = "Edit Password";

        swal({
            title: title_,
            input: 'password',
            inputPlaceHolder: 'Password',
            html:
            '<label>Password</label>' +
            '<input id="edit_password" class="form-control" type="password" required>',
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn btn-info btn-fill",
            confirmButtonText: "Ganti Password",
            cancelButtonClass: "btn btn-danger btn-fill",
            cancelButtonText: "Batal",
            closeOnConfirm: false
        },function (isConfirm) {
            if (isConfirm)
            {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(url_edit,
                    {
                        task: 0,
                        id: id_,
                        pass: $('#edit_password').val()
                    }, function (data, status) {
                        if (status === "success" && data === "1")
                            swal({
                                title: "Password Telah Diubah",
                                type: "success"
                            }, location.reload());
                        else
                            swal({
                                title: "Error",
                                type: "error"
                            }, location.reload());
                    });
            }
        })
    }
};