<div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card" >
                <div class="header text-center">
                <h4 class="title"><b>Headline</b></h4>
                <p class="category">Detail Headline</p>
            </div>
                <table class="table">
                    <thead>
                    <th>Gambar</th>
                    <th>Title</th>
                    <th>Subtitle</th>
                    <th>Deskripsi</th>
                    <th>Actions</th>
                    </thead>
                    <tbody>
                    @if($homepage != null)
                    <td><img src="{{asset($homepage->gambar)}}"  class="img-thumbnail" alt=""></td>
                    <td>{{$homepage->title}}</td>
                    <td>{{$homepage->subtitle}}</td>
                    <td>{!! $homepage->deskripsi  !!}</td>
                    <td>              <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editHeadlineModal" title="Edit"
                                              data-title = "{{$homepage->title}}"
                                              data-subtitle = "{{$homepage->subtitle}}"
                                              data-deskripsi = "{{$homepage->deskripsi}}"
                        >
                            <i class="fa fa-edit"></i>
                        </button></td>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
</div>