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
                    
                    <div class="col-sm-12 col-md-4 col-lg-3">
                        <input type="text" class="form-control" name="daterange" placeholder="Filter" />
                    </div>
                </div>

                @if(Auth::user()->tipe == "admin")
                <div class="button-group right">
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#createRapatModal"><i class="fas fa-plus"></i> &nbsp;Buat Rapat Baru</button>
                </div>
                @endif
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
                        <tbody id="data_rapat">
                            @foreach ($rapat as $item)
                                <tr>
                                    <td></td>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->tanggal_dibuat)->format("D, d M yy H:i") }}</td>
                                    <td>{{ Carbon\Carbon::parse($item->tanggal_berakhir)->format("D, d M yy H:i") }}</td>
                                    <td>{{ $item->total_agree }}/{{ $item->total_finish_vouting }} ( {{ $item->percentage_agree }}% )</td>
                                    <td>{{ $item->total_disagree }}/{{ $item->total_finish_vouting }} ( {{ $item->percentage_disagree }}% )</td>
                                    <td>{{ $item->not_vouting }}/{{ $item->total_vouter }} ( {{ $item->percentage_not_vouting }}% )</td>
                                    <td class="td-actions">
                                        <div class="row">
                                            <a href={{ route('rapat.show', $item->id) }} type="button" id="detail" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="" title="View Vouters">
                                                <i class="fa fa-list-alt"></i>
                                            </a>

                                            @if(Auth::user()->tipe == "admin")
                                                @if($item->total_finish_vouting <= 0)
                                                    <button type="button" id="detail" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editRapatModal" title="Edit Detail"
                                                        data-id="{{ $item->id }}"
                                                        data-judul="{{ $item->judul }}"
                                                        data-end_date="{{ Carbon\Carbon::parse($item->tanggal_berakhir)->format('d-m-yy') }}"
                                                        data-deskripsi="{{ $item->description }}"
                                                        data-cover="{{ URL::asset('storage/public/rapat/' . $item->foto ) }}"
                                                        data-ori_cover="{{ $item->foto }}"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                @endif
                                                
                                                <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#deleteRapatModal" title="Delete" data-id_rapat="{{ $item->id }}">
                                                    <i class="fa fa-remove"></i>
                                                </button>
                                                
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div>
        <!-- end row -->
    </div>

    @include('modal.rapat.create')
    @include('modal.rapat.edit')
    @include('modal.rapat.delete')
@endsection

@section('extra_script')
    <script src="{{URL::asset('bootstrap/assets/js/bootstrap-datetimepicker.js')}}"></script>
    <script src="{{ asset('bmtmudathemes/assets/js/modal/rapat.js') }}"></script>
    <script>
    $('.date-picker').datetimepicker({
    //                    defaultDate: "11/1/2013",
    defaultDate: '{{\Carbon\Carbon::now()}}',
    format: 'MM/DD/YYYY',
    icons: {
    time: "fa fa-clock-o",
    date: "fa fa-calendar",
    up: "fa fa-chevron-up",
    down: "fa fa-chevron-down",
    previous: 'fa fa-chevron-left',
    next: 'fa fa-chevron-right',
    today: 'fa fa-screenshot',
    clear: 'fa fa-trash',
    close: 'fa fa-remove'
    }
    });
    </script>

@endsection
