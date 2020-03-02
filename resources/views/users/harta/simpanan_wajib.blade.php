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
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12" id="ShowTable">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Riwayat Simpanan Wajib </h4>
                        <p class="category">Berikut Catatan Riwayat Simpanan Wajib Anda</p>
                        <br />
                    </div>

                    <table id="bootstrap-table" class="table">
                        <thead>
                            <th></th>
                            <th class="text-left" data-sortable="true">Tgl Transaksi</th>
                            <th class="text-left" data-sortable="true">Jenis Transaksi</th>
                            <th class="text-left" data-sortable="true">Jumlah</th>
                            <th class="text-left" data-sortable="true">Saldo Awal</th>
                            <th class="text-left" data-sortable="true">Saldo Akhir</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td class="text-left">Mon 10 Feb 2020</td>
                                <td class="text-left">Simpanan Wajib</td>
                                <td class="text-left">Rp. 100,000</td>
                                <td class="text-left">Rp. 0</td>
                                <td class="text-left">Rp. 100,000</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-left">Mon 10 Feb 2020</td>
                                <td class="text-left">Simpanan Wajib</td>
                                <td class="text-left">Rp. 100,000</td>
                                <td class="text-left">Rp. 0</td>
                                <td class="text-left">Rp. 100,000</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-left">Mon 10 Feb 2020</td>
                                <td class="text-left">Simpanan Wajib</td>
                                <td class="text-left">Rp. 100,000</td>
                                <td class="text-left">Rp. 0</td>
                                <td class="text-left">Rp. 100,000</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-left">Mon 10 Feb 2020</td>
                                <td class="text-left">Simpanan Wajib</td>
                                <td class="text-left">Rp. 100,000</td>
                                <td class="text-left">Rp. 0</td>
                                <td class="text-left">Rp. 100,000</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-left">Mon 10 Feb 2020</td>
                                <td class="text-left">Simpanan Wajib</td>
                                <td class="text-left">Rp. 100,000</td>
                                <td class="text-left">Rp. 0</td>
                                <td class="text-left">Rp. 100,000</td>
                            </tr>
                        </tbody>
                    </table>
                
                </div>
            </div>
        </div>
    </div>
@endsection