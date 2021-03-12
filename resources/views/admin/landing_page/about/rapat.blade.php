<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card" >
            <div class="header text-center">
                <h4 class="title"><b>Rapat</b></h4>
                <p class="category">Detail Rapat</p>
            </div>
            <table class="table">
                <thead>
                <th data-sortable="false">Nama</th>
                <th data-sortable="false">Dokumen</th>
                <th data-sortable="false">Actions</th>
                </thead>
                <tbody>
                @foreach($rapat as $keys => $value)
                    <tr>
                        <td>{{$value->nama}}</td>
                        @if($value->link_dokumen != null)
                            <td><a href="{{url('admin/landing_page/tentang_kami/downloadrapat').'/'.$value->id}}">Download Dokumen</a></td>
                        @else
                            <td>Belum Ada Dokumen</td>
                            @endif

                        <td>              <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editRapatModal" title="Edit"
                                                  data-id = "{{$value->id}}"
                                                  data-nama = "{{$value->nama}}"
                            >
                                <i class="fa fa-edit"></i>
                            </button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>