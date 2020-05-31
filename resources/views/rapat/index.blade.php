@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection
@section('extra_style')

@endsection
@section('content')
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Daftar Rapat</h4>

                {{-- <div class="head-filter">
                    <p class="filter-title">Tanggal Berakhir</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Tanggal Berakhir -</option>
                        </select>
                    </form>
                </div> --}}
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Search" id="search">
                    
                    <div class="suggestion-box"></div>
                </div>
            </div>
        </div>
        
        <div class="row" id="list_rapat">

            @foreach ($rapat as $item)
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <div class="card hover">
                        <div class="card-image">
                            <img src="{{ asset('storage/public/rapat/' . $item->foto) }}">
                        </div>
                        <div class="card-body">
                            <h4 class="title">{{ $item->judul }}</h4>
                            <div class="description">{!! $item->description !!}</div>
                            <div class="date">
                                <div>
                                    <span class="label">Tanggal Dibuat</span>
                                    <p class="content">{{ Carbon\Carbon::parse($item->tanggal_dibuat)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <span class="label">Tanggal Berakhir</span>
                                    <p class="content">{{ Carbon\Carbon::parse($item->tanggal_berakhir)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('rapat.show', [$item->id]) }}">
                            <div class="overlay">
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach

            
            <div class="row" style="text-align: right;">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    {{ $rapat->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra_script')

    <script src="{{ asset('bmtmudathemes/assets/js/modal/rapat.js') }}"></script>

@endsection