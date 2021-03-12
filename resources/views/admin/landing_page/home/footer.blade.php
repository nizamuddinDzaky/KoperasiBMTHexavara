<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card" >
            <div class="header text-center">
                <h4 class="title"><b>Footer</b></h4>
                <p class="category">Detail Footer</p>
            </div>
            <table class="table">
                <thead>
                <th>Keterangan Kiri</th>
                <th>Alamat</th>
                <th>Logo</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @if($footer != null)
                    <tr>
                        <td>{!! $footer->keterangan !!}</td>
                        <td>{!! $footer->alamat !!}</td>
                        <td><img src="{{asset($footer->logo)}}"  style="width: 300px; height: 150px" alt=""></td>
                        <td>              <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editFooterModal" title="Edit"
                                                  data-keterangan = "{{$footer->keterangan}}"
                                                  data-alamat = "{{$footer->alamat}}"
                            >
                                <i class="fa fa-edit"></i>
                            </button></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>