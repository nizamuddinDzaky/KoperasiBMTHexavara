@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection
@section('extra_style')
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet"/>
    <style>
        .fa-3x {
            font-size: 5vmax;}
        h3 {
            font-size: 2vw !important;}
    </style>
@endsection
@section('content')
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Simpanan Anggota</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>

                    {{-- <div class="button-group right">
                        <button class="btn btn-primary rounded right shadow-effect"><i class="fa fa-plus"></i> Tambah Pengajuan</button>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#simpok" aria-controls="simpok" role="tab" data-toggle="tab">SIMPANAN POKOK</a></li>
                    <li role="presentation"><a href="#simwa" aria-controls="simwa" role="tab" data-toggle="tab">SIMPANAN WAJIB</a></li>
                    <li role="presentation"><a href="#simsus" aria-controls="simsus" role="tab" data-toggle="tab">SIMPANAN KHUSUS</a></li>
                    <li role="presentation"><a href="#nisbah" aria-controls="nisbah" role="tab" data-toggle="tab">KONTRIBUSI NISBAH</a></li>
                </ul>
            </div>
        </div>
          
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="simpok">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
    
                            <div class="header text-center">
                                <h4 class="title">Simpanan Pokok </h4>
                                <p class="category">Daftar Pengajuan Anggota</p>
                                <br />
                            </div>
                            <table class="bootstrap-table-asc table">
                                <thead>
                                    <th></th>
                                    <th class="text-left" data-sortable="true">ID </th>
                                    <th class="text-left" data-sortable="true">NAMA</th>
                                    <th class="text-left" data-sortable="true">NO KTP</th>
                                    <th class="text-left" data-sortable="true">JUMLAH SIMPANAN POKOK</th>
                                </thead>
                                <tbody>
                                    @foreach($simpanan_pokok as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user->nama }}</td>
                                        <td>{{ $item->user->no_ktp }}</td>
                                        <td>{{ number_format(json_decode($item->transaksi)->jumlah) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
    
                        </div><!--  end card  -->
                    </div> <!-- end col-md-12 -->
                </div> <!-- end row -->
            </div>

            <div role="tabpanel" class="tab-pane" id="simwa">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
    
                            <div class="header text-center">
                                <h4 class="title">Simpanan Wajib </h4>
                                <p class="category">Daftar Pengajuan Anggota</p>
                                <br />
                            </div>
                            <table class="bootstrap-table-asc table">
                                <thead>
                                    <th></th>
                                    <th class="text-left" data-sortable="true">ID </th>
                                    <th class="text-left" data-sortable="true">NAMA</th>
                                    <th class="text-left" data-sortable="true">NO KTP</th>
                                    <th class="text-left" data-sortable="true">JUMLAH SIMPANAN WAJIB</th>
                                </thead>
                                <tbody>
                                    @foreach($simpanan_wajib as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user->nama }}</td>
                                        <td>{{ $item->user->no_ktp }}</td>
                                        <td>{{ number_format(json_decode($item->transaksi)->jumlah) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
    
                        </div><!--  end card  -->
                    </div> <!-- end col-md-12 -->
                </div> <!-- end row -->
            </div>

            <div role="tabpanel" class="tab-pane" id="simsus">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
    
                            <div class="header text-center">
                                <h4 class="title">Simpanan Khusus </h4>
                                <p class="category">Daftar Pengajuan Anggota</p>
                                <br />
                            </div>
                            <table class="bootstrap-table-asc table">
                                <thead>
                                    <th></th>
                                    <th class="text-left" data-sortable="true">ID </th>
                                    <th class="text-left" data-sortable="true">NAMA</th>
                                    <th class="text-left" data-sortable="true">NO KTP</th>
                                    <th class="text-left" data-sortable="true">JUMLAH SIMPANAN KHUSUS</th>
                                </thead>
                                <tbody>
                                    @foreach($simpanan_khusus as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user->nama }}</td>
                                        <td>{{ $item->user->no_ktp }}</td>
                                        <td>{{ number_format(json_decode($item->transaksi)->jumlah) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
    
                        </div><!--  end card  -->
                    </div> <!-- end col-md-12 -->
                </div> <!-- end row -->
            </div>

            <div role="tabpanel" class="tab-pane" id="nisbah">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
    
                            <div class="header text-center">
                                <h4 class="title">Kontribusi Nisbah </h4>
                                <p class="category">Daftar Pengajuan Anggota</p>
                                <br />
                            </div>
                            <table class="bootstrap-table-asc table">
                                <thead>
                                    <th></th>
                                    <th class="text-left" data-sortable="true">ID </th>
                                    <th class="text-left" data-sortable="true">NAMA</th>
                                    <th class="text-left" data-sortable="true">NO KTP</th>
                                    <th class="text-left" data-sortable="true">JUMLAH KONTRIBUSI NISBAH</th>
                                </thead>
                                <tbody>
                                    @foreach($kontribusi_margin as $item)
                                    <tr>
                                        <td></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user->nama }}</td>
                                        <td>{{ $item->user->no_ktp }}</td>
                                        <td>{{ number_format(json_decode($item->transaksi)->bayar_margin) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
    
                        </div><!--  end card  -->
                    </div> <!-- end col-md-12 -->
                </div> <!-- end row -->
            </div>
        </div>
    </div>
@endsection