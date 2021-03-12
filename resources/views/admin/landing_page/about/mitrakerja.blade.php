<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card" >
            <div class="header text-center">
                <h4 class="title"><b>Mitra Kerja</b></h4>
                <p class="category">Detail Mitra Kerja</p>
                <button type="button" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#tambahMitraKerjaModal" title="Insert" style="float:right"
                >Tambah Mitra Kerja</button>
            </div>
            <table class="bootstrap-table">
                <thead>
                <th>No</th>
                <th>Nama</th>
                <th>Keterangan</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($mitrakerja as $keys => $value)
                    <tr>
                        <td>{{++$keys}}</td>
                        <td>{{$value->nama}}</td>
                        <td>{{$value->keterangan}}</td>
                        <td>              <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editMitraKerjaModal" title="Edit"
                                                  data-keterangan = "{{$value->keterangan}}"
                                                  data-id = "{{$value->id}}"
                                                  data-nama = "{{$value->nama}}"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-social btn-danger btn-fill" title="Edit" onclick="deleteMitraKerja({{$value->id}})"
                            >
                                <i class="fa fa-trash"></i>
                            </button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>