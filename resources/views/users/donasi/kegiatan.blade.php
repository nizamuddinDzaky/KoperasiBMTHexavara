    <div class="row">
        @foreach($kegiatan as $kegiatan)

        @php
            $dana = json_decode($kegiatan['detail'],true)['dana'];
            $tanggal_pelaksanaan = Carbon\Carbon::parse($kegiatan['tanggal_pelaksanaan']);
        @endphp
        <div class="col-sm-12 col-md-4 col-lg-3">
            <div class="card hover" data-toggle="modal" data-target="#donasiKegiatan" 
                data-id="{{ $kegiatan['id'] }}"
                data-jenis="donasi kegiatan"
            >
                <div class="card-image">
                    @if(json_decode($kegiatan['detail'], true)['path_poster'] != "") 
                        <img src="{{ asset('storage/file' . json_decode($kegiatan['detail'], true)['path_poster']) }}">
                    @else
                        <img src="{{ asset('bmtmudathemes/assets/images/no-image-available.png') }}">
                    @endif
                </div>
                <div class="card-body">
                    <h4 class="title">{{ $kegiatan['nama_kegiatan'] }}</h4>
                    <p class="description">
                        {{ json_decode($kegiatan['detail'],true)['detail'] }}
                    </p>
                    <div class="date">
                        <div>
                            <span class="label">Dana Dibutuhkan</span>
                            <p class="content">Rp. {{ number_format($dana) }}</p>
                        </div>
                        <div>
                            <span class="label">Tanggal Pelaksanaan</span>
                            <p class="content">{{ $tanggal_pelaksanaan->format('D, d M Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="overlay"></div>
            </div>
        </div>
        @endforeach

        
        <div class="row" style="text-align: right;">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                    <li>
                        <a href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#">5</a></li>
                    <li>
                        <a href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>