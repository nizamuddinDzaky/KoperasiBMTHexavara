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
            <div class="col-sm-12 col-md-4 col-lg-3">
                <div class="card hover">
                    <div class="card-image">
                        <img src="{{ asset('bmtmudathemes/assets/images/background.jpg') }}">
                    </div>
                    <div class="card-body">
                        <h4 class="title">Contoh Judul Rapat</h4>
                        <p class="description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
                        </p>
                        <div class="date">
                            <div>
                                <span class="label">Tanggal Dibuat</span>
                                <p class="content">17-02-2020</p>
                            </div>
                            <div>
                                <span class="label">Tanggal Berakhir</span>
                                <p class="content">17-02-2020</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('rapat.show', [1]) }}">
                        <div class="overlay">
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3">
                <div class="card hover">
                    <div class="card-image">
                        <img src="{{ asset('bmtmudathemes/assets/images/background.jpg') }}">
                    </div>
                    <div class="card-body">
                        <h4 class="title">Contoh Judul Rapat</h4>
                        <p class="description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
                        </p>
                        <div class="date">
                            <div>
                                <span class="label">Tanggal Dibuat</span>
                                <p class="content">17-02-2020</p>
                            </div>
                            <div>
                                <span class="label">Tanggal Berakhir</span>
                                <p class="content">17-02-2020</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('rapat.show', [1]) }}">
                        <div class="overlay">
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3">
                <div class="card hover">
                    <div class="card-image">
                        <img src="{{ asset('bmtmudathemes/assets/images/background.jpg') }}">
                    </div>
                    <div class="card-body">
                        <h4 class="title">Contoh Judul Rapat</h4>
                        <p class="description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
                        </p>
                        <div class="date">
                            <div>
                                <span class="label">Tanggal Dibuat</span>
                                <p class="content">17-02-2020</p>
                            </div>
                            <div>
                                <span class="label">Tanggal Berakhir</span>
                                <p class="content">17-02-2020</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('rapat.show', [1]) }}">
                        <div class="overlay">
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-3">
                <div class="card hover">
                    <div class="card-image">
                        <img src="{{ asset('bmtmudathemes/assets/images/background.jpg') }}">
                    </div>
                    <div class="card-body">
                        <h4 class="title">Contoh Judul Rapat</h4>
                        <p class="description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s
                        </p>
                        <div class="date">
                            <div>
                                <span class="label">Tanggal Dibuat</span>
                                <p class="content">17-02-2020</p>
                            </div>
                            <div>
                                <span class="label">Tanggal Berakhir</span>
                                <p class="content">17-02-2020</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('rapat.show', [1]) }}">
                        <div class="overlay">
                        </div>
                    </a>
                </div>
            </div>

            
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
    </div>
@endsection