<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card" >
            <div class="header text-center">
                <h4 class="title"><b>Struktur Organisasi</b></h4>
                <p class="category">Detail Struktur Organisasi</p>
                <button type="button" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#tambahStrukturOrganisasiModal" title="Insert" style="float:right"
                >Tambah Anggota Struktur Organisasi</button>
            </div>
            <table class="bootstrap-table">
                <thead>
                <th>No</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Jabatan</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($anggota as $keys => $value)
                    <tr>
                        <td>{{++$keys}}</td>
                        <td>{{$value->nama}}</td>
                        <td>{{$value->kategori}}</td>
                        @if($value->jabatan != null)
                        <td>{{$value->jabatan}}</td>
                        @else
                            <td>Tidak Ada Jabatan Tambahan</td>
                            @endif
                        <td>              <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editStrukturOrganisasiModal" title="Edit"
                                                  data-nama = "{{$value->nama}}"
                                                  data-id = "{{$value->id}}"
                                                  data-kategori = "{{$value->kategori}}"
                                                  data-jabatan = "{{$value->jabatan}}"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-social btn-danger btn-fill" title="Edit" onclick="deleteStrukturOrganisasi({{$value->id}})"
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