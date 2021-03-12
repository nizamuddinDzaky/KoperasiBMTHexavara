<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card" >
            <div class="header text-center">
                <h4 class="title"><b>Izin Pendirian</b></h4>
                <p class="category">Detail Izin Pendirian</p>
                <button type="button" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#tambahIzinPendirianModal" title="Insert" style="float:right"
                >Tambah Izin Pendirian</button>
            </div>
            <table class="bootstrap-table">
                <thead>
                <th>No</th>
                <th>Keterangan</th>
                <th>Gambar</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($izin_pendirian as $keys => $value)
                    <tr>
                        <td>{{++$keys}}</td>
                        <td>{{$value->keterangan}}</td>
                        <td><img src="{{asset($value->gambar)}}"  style="width: 300px; height: 150px" alt=""></td>
                        <td>              <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editIzinPendirianModal" title="Edit"
                                                  data-keterangan = "{{$value->keterangan}}"
                                                  data-id = "{{$value->id}}"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-social btn-danger btn-fill" title="Edit" onclick="deleteIzinPendirian({{$value->id}})"
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