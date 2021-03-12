<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card" >
            <div class="header text-center">
                <h4 class="title"><b>Pendiri</b></h4>
                <p class="category">Detail Pendiri</p>
                <button type="button" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#tambahPendiriModal" title="Insert" style="float:right"
                >Tambah Pendiri</button>
            </div>
            <table class="table">
                <thead>
                <th data-sortable="true">No</th>
                <th data-sortable="false">Nama</th>
                <th data-sortable="false">Actions</th>
                </thead>
                <tbody>
                @foreach($pendiri as $keys => $value)
                    <tr>
                        <td>{{++$keys}}</td>
                        <td>{{$value->nama}}</td>
                        <td>              <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editPendiriModal" title="Edit"
                                                  data-id = "{{$value->id}}"
                                                  data-nama = "{{$value->nama}}"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-social btn-danger btn-fill" title="Edit" onclick="deletePendiri({{$value->id}})"
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