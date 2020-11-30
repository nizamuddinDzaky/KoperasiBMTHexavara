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
                <h4 class="title">Rekening Deposito</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                            <input type="text" class="form-control daterange" placeholder="Filter" />
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
            <div class="col-md-12">
                <div class="card">
                    <table class="table beautiful-table">
                        <thead class="style-head">
                            <th class="text-left" data-sortable="true" colspan="2">JENIS TABUNGAN </th>
                            <th class="text-left" data-sortable="true">JUMLAH ANGGOTA</th>
                            <th class="text-left" data-sortable="true">TOTAL TABUNGAN</th>
                            <th class="text-left" data-sortable="true">PENGAJUAN BARU</th>
                            <th class="text-center" data-sortable="true">DETAIL</th>
                        </thead>
                        <tbody>
                        @if($data_deposito != null)
                            @foreach($data_deposito as $deposito)
                            <tr class="zoom-effect">
{{--                                <td class="with-icon">--}}
{{--                                    <div class="icon primary">--}}
{{--                                        <i class="fa fa-donate"></i>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
                                <td></td>
                                <td>{{ $deposito->nama_rekening }}</td>
                                <td>{{ $deposito->jumlah_anggota }} ANGGOTA</td>
                                <td>{{ number_format($deposito->jumlah_saldo,2) }}</td>
                                <td>{{ count($deposito->pengajuan) }} PENGAJUAN BARU</td>
                                <td class="with-icon">
                                    @if($start != "start" && $end != "end")
                                    <a href="{{ route('admin.transaksi.deposito.detail', [$deposito->id, $start, $end]) }}">
                                        @else
                                            <a href="{{ route('admin.transaksi.deposito.detail', [$deposito->id, "start", "end"]) }}">
                                            @endif
                                        <div class="icon default">
                                            <i class="material-icons">more_horiz</i>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
    </div>
@endsection