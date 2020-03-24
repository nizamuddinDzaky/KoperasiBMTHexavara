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
                    <p class="filter-title">Periode Pengajuan</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>

                <div class="button-group right">
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#pengajuanSimpananWajib"><i class="fa fa-credit-card"></i> Bayar Simpanan Wajib</button>
                    <button class="btn btn-success rounded right shadow-effect" data-toggle="modal" data-target="#pengajuanSimpananPokok"><i class="fa fa-credit-card"></i> Bayar Simpanan Pokok</button>
                    <button class="btn btn-danger rounded right shadow-effect" data-toggle="modal" data-target="#pengajuanSimpananKhusus"><i class="fa fa-credit-card"></i> Bayar Simpanan Khusus</button>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12" id="ShowTable">
                
                {{-- @if(count($data) > 0) --}}
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Pengajuan Simpanan Anggota </h4>
                        <p class="category">Berikut adalah daftar pengajuan simpanan anda</p>
                        <br />
                    </div>
                    
                    <table class="bootstrap-table table">
                        <thead>
                            <th></th>
                            <th class="text-left" data-sortable="true">ID</th>
                            <th class="text-left" data-sortable="true">Jenis Pembayaran</th>
                            <th class="text-left" data-sortable="true">Tgl Pengajuan</th>
                            <th class="text-left" data-sortable="true">Nominal</th>
                            <th class="text-left" data-sortable="true">Status</th>
                        </thead>
                        <tbody>
                        @foreach ($data as $usr)
                            <tr>
                                <td></td>
                                <td class="text-left">{{ $usr->id }}</td>
                                <td class="text-left">{{ $usr->jenis_pengajuan   }}</td>
                                <td class="text-left">{{ $usr->created_at->format('d F Y') }}</td>
                                <td class="text-left">Rp{{" ". number_format(json_decode($usr->detail,true)['jumlah'],2) }}</td>
                                <td class="text-left text-uppercase">{{ $usr->status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                <!--  end card  -->

                {{-- @else --}}
                {{-- <div class="header text-center" style="display: flex; flex-direction: column; justify-content: center; height: 400px">
                    <h4 class="title">Belum Ada Riwayat Pengajuan Simpanan </h4>
                    <br />
                </div> --}}
                {{-- @endif --}}
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
    </div>
@endsection

@include('modal/simpanan/pengajuan')
    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->
