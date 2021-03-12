<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card" >
            <div class="header text-center">
                <h4 class="title"><b>Moto Visi Misi</b></h4>
                <p class="category">Detail Moto Visi Misi</p>
            </div>
            <table class="table">
                <thead>
                <th>Moto</th>
                <th>Visi</th>
                <th>Misi</th>
{{--                <th>Gambar Moto</th>--}}
{{--                <th>Gambar Visi</th>--}}
{{--                <th>Gambar Misi</th>--}}
                <th>Actions</th>
                </thead>
                <tbody>
                @if($homepage != null)
                    <td>{!!$homepage->moto  !!}</td>
                    <td>{!!$homepage->visi  !!}</td>
                    <td>{!!$homepage->misi  !!}</td>
{{--                    <td>{{$homepage->gambar_moto}}</td>--}}
{{--                    <td>{{$homepage->gambar_visi}}</td>--}}
{{--                    <td>{{$homepage->gambar_misi}}</td>--}}
                    <td>
                        <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editVisiMisiModal" title="Edit"
                                              data-moto = "{{$homepage->moto}}"
                                              data-visi = "{{$homepage->visi}}"
                                              data-misi = "{{$homepage->misi}}"
                        >
                            <i class="fa fa-edit"></i>
                        </button></td>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>