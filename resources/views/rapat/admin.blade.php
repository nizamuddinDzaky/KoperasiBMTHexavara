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
                <h4 class="title">Data Rapat BMT</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>

                <div class="button-group right">
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#transferRekModal"><i class="fas fa-plus"></i> &nbsp;Buat Rapat Baru</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Data Rapat BMT </h4>
                        <p class="category">Berikut adalah data riwayat rapat BMT anda</p>
                        <br />
                    </div>

                    <table class="table bootstrap-table">
                        <thead>
                            <th></th>
                            <th class="text-left" data-sortable="true" >ID RAPAT</th>
                            <th class="text-left" data-sortable="true">JUDUL RAPAT</th>
                            <th class="text-left" data-sortable="true">TANGGAL MULAI</th>
                            <th class="text-left" data-sortable="true">TANGGAL BERAKHIR</th>
                            <th class="text-left" data-sortable="true">VOUTING SETUJU</th>
                            <th class="text-left" data-sortable="true">VOUTING TIDAK SETUJU</th>
                            <th class="text-left" data-sortable="true">BELUM VOUTING</th>
                            <th class="text-left">ACTION</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>1</td>
                                <td>Lorem Ipsum Dolor Iset</td>
                                <td>Mon 10 Mei 2020 10:00:00</td>
                                <td>Mon 20 Mei 2020 10:00:00</td>
                                <td>500</td>
                                <td>10</td>
                                <td>10</td>
                                <td>
                                    <button type="button"  class="btn btn-social btn-default circle" title="Delete">
                                        <i class="material-icons">more_horiz</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>1</td>
                                <td>Lorem Ipsum Dolor Iset</td>
                                <td>Mon 10 Mei 2020 10:00:00</td>
                                <td>Mon 20 Mei 2020 10:00:00</td>
                                <td>500</td>
                                <td>10</td>
                                <td>10</td>
                                <td>
                                    <button type="button"  class="btn btn-social btn-default circle" title="Delete">
                                        <i class="material-icons">more_horiz</i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>1</td>
                                <td>Lorem Ipsum Dolor Iset</td>
                                <td>Mon 10 Mei 2020 10:00:00</td>
                                <td>Mon 20 Mei 2020 10:00:00</td>
                                <td>500</td>
                                <td>10</td>
                                <td>10</td>
                                <td>
                                    <button type="button"  class="btn btn-social btn-default circle" title="Delete">
                                        <i class="material-icons">more_horiz</i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>


                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div>
        <!-- end row -->
    </div>
@endsection
