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
                            <tr class="zoom-effect">
                                <td class="with-icon">
                                    <div class="icon primary">
                                        <i class="fa fa-donate"></i>
                                    </div>
                                </td>
                                <td>SIMPANAN MUDHARABAH UMUM</td>
                                <td>140 ANGGOTA</td>
                                <td>Rp. 3,000,000,000</td>
                                <td>50 PENGAJUAN BARU</td>
                                <td class="with-icon">
                                    <div class="icon default">
                                        <i class="material-icons">more_horiz</i>
                                    </div>
                                </td>
                            </tr>
                            <tr class="zoom-effect">
                                <td class="with-icon">
                                    <div class="icon primary">
                                        <i class="fa fa-donate"></i>
                                    </div>
                                </td>
                                <td>SIMPANAN MUDHARABAH UMUM</td>
                                <td>140 ANGGOTA</td>
                                <td>Rp. 3,000,000,000</td>
                                <td>50 PENGAJUAN BARU</td>
                                <td class="with-icon">
                                    <div class="icon default">
                                        <i class="material-icons">more_horiz</i>
                                    </div>
                                </td>
                            </tr>
                            </tr>
                            <tr class="zoom-effect">
                                <td class="with-icon">
                                    <div class="icon primary">
                                        <i class="fa fa-donate"></i>
                                    </div>
                                </td>
                                <td>SIMPANAN MUDHARABAH UMUM</td>
                                <td>140 ANGGOTA</td>
                                <td>Rp. 3,000,000,000</td>
                                <td>50 PENGAJUAN BARU</td>
                                <td class="with-icon">
                                    <div class="icon default">
                                        <i class="material-icons">more_horiz</i>
                                    </div>
                                </td>
                            </tr>
                            </tr>
                            <tr class="zoom-effect">
                                <td class="with-icon">
                                    <div class="icon primary">
                                        <i class="fa fa-donate"></i>
                                    </div>
                                </td>
                                <td>SIMPANAN MUDHARABAH UMUM</td>
                                <td>140 ANGGOTA</td>
                                <td>Rp. 3,000,000,000</td>
                                <td>50 PENGAJUAN BARU</td>
                                <td class="with-icon">
                                    <div class="icon default">
                                        <i class="material-icons">more_horiz</i>
                                    </div>
                                </td>
                            </tr>
                            </tr>
                            <tr class="zoom-effect">
                                <td class="with-icon">
                                    <div class="icon primary">
                                        <i class="fa fa-donate"></i>
                                    </div>
                                </td>
                                <td>SIMPANAN MUDHARABAH UMUM</td>
                                <td>140 ANGGOTA</td>
                                <td>Rp. 3,000,000,000</td>
                                <td>50 PENGAJUAN BARU</td>
                                <td class="with-icon">
                                    <div class="icon default">
                                        <i class="material-icons">more_horiz</i>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
    </div>
@endsection