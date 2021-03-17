<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card" >
            <div class="header text-center">
                <h4 class="title"><b>Tausiah</b></h4>
                <p class="category">Detail Tausiah</p>
                <button type="button" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#tambahTausiahModal" title="Insert" style="float:right"
                >Tambah Tausiah</button>
            </div>
            <table class="bootstrap-table">
                <thead>
                <th>No</th>
                <th>Judul</th>
                <th>Isi</th>
                <th>Gambar</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($tausiah as $keys => $value)
                    <tr>
                        <td>{{++$keys}}</td>
                        <td>{{$value->judul}}</td>
                        <td>{!! substr($value->isi,0, 200)  !!}...</td>
                        <td><img src="{{asset($value->gambar)}}"  style="width: 300px; height: 150px" alt=""></td>
                        <td>              <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editTausiahModal" title="Edit"
                                                  data-judul = "{{$value->judul}}"
                                                  data-isi = "{{$value->isi}}"
                                                  data-id = "{{$value->id}}"
                            >
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-social btn-danger btn-fill" title="Edit" onclick="deleteTausiah({{$value->id}})"
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