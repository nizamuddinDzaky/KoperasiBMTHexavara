@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection
@section('extra_style')
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet"/>
@endsection
@section('content')
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Detail Kas</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option selected disabled>- Periode -</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title"> Detail Kas</h4>
                        <p class="category">Daftar  Transaksi Kas</p>
                        <br />
                    </div>

                    <table class="table bootstrap-table">
                        <thead>
                            <th></th>
                            <th data-sortable="true" class="text-left">No Transaksi</th>
                            <th data-sortable="true">Tanggal Transaksi</th>
                            <th data-sortable="true">Jenis Transaksi</th>
                            <th data-sortable="true" class="text-right">Debet</th>
                            <th data-sortable="true" class="text-right">Kredit</th>
                            <th data-sortable="true" class="text-right">Saldo</th>
                        </thead>
                        <tbody>
                        @foreach ($data as $usr)
                            <tr>
                                <td></td>
                                <td>{{ $usr->id }}</td>
                                <td>{{ $usr->created_at->format('D, d F Y h:i:s')  }}</td>
                                <td style="text-transform: capitalize;">{{ $usr->status   }}</td>

                                @if(json_decode($usr->transaksi)->jumlah > 0)
                                <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['jumlah'],2) }}</td>
                                @else
                                <td class="text-right">0</td>
                                @endif

                                @if(json_decode($usr->transaksi)->jumlah < 0)
                                <td class="text-right">{{ number_format(-json_decode($usr->transaksi,true)['jumlah'],2) }}</td>
                                @else
                                <td class="text-right">0</td>
                                @endif

                                <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['saldo_akhir'],2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
    </div>
    {{-- @include('modal.pengajuan') --}}
@endsection