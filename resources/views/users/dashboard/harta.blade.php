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
                <h4 class="title">Harta Anggota</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12" id="ShowTable">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Rekening Simpanan </h4>
                        <p class="category">Berikut adalah daftar rekening simpanan anda</p>
                        <br />
                    </div>

                    <table id="bootstrap-table" class="table bootstrap-table">
                        <thead>
                            <th></th>
                            <th class="text-left" data-sortable="true">NO</th>
                            <th class="text-left" data-sortable="true">Jenis Harta</th>
                            <th class="text-left" data-sortable="true">Total </th>
                            <th class="text-left">Actions</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td class="text-left">1</td>
                                <td class="text-left">Simpanan Wajib</td>
                                <td class="text-left">
                                    @if(isset(json_decode($simwaAndSimpok->wajib_pokok)->wajib) && json_decode($simwaAndSimpok->wajib_pokok)->wajib != "") 
                                        {{ number_format(json_decode($simwaAndSimpok->wajib_pokok)->wajib) }}  
                                    @else 
                                        0 
                                    @endif</td>
                                <td class="td-actions text-left">
                                    <a href="{{ route('anggota.detail_simpanan', ['wajib']) }}" class="btn btn-primary btn-social btn-fill" title="Detail">
                                        <i class="fa fa-clipboard-list"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-left">2</td>
                                <td class="text-left">Simpanan Pokok</td>
                                <td class="text-left">
                                    @if(isset(json_decode($simwaAndSimpok->wajib_pokok)->pokok) && json_decode($simwaAndSimpok->wajib_pokok)->pokok != "") 
                                        {{ number_format(json_decode($simwaAndSimpok->wajib_pokok)->pokok) }}
                                    @else 
                                        0
                                    @endif</td>
                                <td class="td-actions text-left">
                                    <a href="{{ route('anggota.detail_simpanan', ['pokok']) }}" class="btn btn-primary btn-social btn-fill" title="Detail">
                                        <i class="fa fa-clipboard-list"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-left">3</td>
                                <td class="text-left">Simpanan Khusus</td>
                                <td class="text-left">
                                    @if(isset(json_decode($simwaAndSimpok->wajib_pokok)->khusus) && json_decode($simwaAndSimpok->wajib_pokok)->khusus != "") 
                                        {{ number_format(json_decode($simwaAndSimpok->wajib_pokok)->khusus) }}
                                    @else 
                                        0
                                    @endif</td>
                                <td class="td-actions text-left">
                                    <a href="{{ route('anggota.detail_simpanan', ['khusus']) }}" class="btn btn-primary btn-social btn-fill" title="Detail">
                                        <i class="fa fa-clipboard-list"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                
                </div>
            </div>
        </div>
    </div>
@endsection