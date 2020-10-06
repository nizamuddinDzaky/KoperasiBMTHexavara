    <div class="row">
        @foreach($kegiatan as $item)

        @php
            $dana = json_decode($item['detail'],true)['dana'];
            $tanggal_pelaksanaan = Carbon\Carbon::parse($item['tanggal_pelaksanaan']);
        @endphp
        <div class="col-sm-12 col-md-4 col-lg-3">
            <div class="card hover" data-toggle="modal" data-target="#donasiKegiatan" 
                data-id="{{ $item['id'] }}"
                data-jenis="donasi kegiatan"
            >
                <div class="card-image">
                    @if(json_decode($item['detail'], true)['path_poster'] != "") 
                        <img src="{{ asset('storage/public/maal/' . json_decode($item['detail'], true)['path_poster']) }}">
                    @else
                        <img src="{{ asset('bmtmudathemes/assets/images/no-image-available.png') }}">
                    @endif
                </div>
                <div class="card-body">
                    <div class="date">
                        <p class="content">DANA DIBUTUHKAN : {{ number_format($dana) }}</p>
                    </div>
                    <h4 class="title">{{ $item['nama_kegiatan'] }}</h4>
                    <p class="description">
                        <div class="summernote-content">{!! json_decode($item['detail'],true)['detail'] !!}</div>
                    </p>
                </div>

                <div class="overlay"></div>
            </div>
        </div>
        @endforeach

        
        <div class="row" style="text-align: right;">
            <div class="col-sm-12 col-md-12 col-lg-12">
                {{ $kegiatan->links() }}
            </div>
        </div>
    </div>