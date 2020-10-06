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
                <h4 class="title">Daftar Teller</h4>

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
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card">

                        <div class="header text-center">
                            <h4 class="title"> Daftar Teller</h4>
                            <p class="category">Daftar  Teller BMT</p>
                            <br />
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                                          <span></span>
                    </div>

                    <table class="table beautiful-table">
                        <thead class="style-head">
                            <th class="text-left" data-sortable="true" colspan="2">NAMA </th>
                            <th class="text-left" data-sortable="true">ALAMAT</th>
                            <th class="text-left" data-sortable="true">SALDO</th>
                            {{-- <th class="text-left" data-sortable="true">PENGAJUAN BARU</th> --}}
                            <th class="text-center" data-sortable="true">DETAIL</th>
                        </thead>
                        <tbody>
                            @foreach($data as $item)
                            <tr class="zoom-effect">
{{--                                <td class="with-icon">--}}
{{--                                    <div class="icon primary">--}}
{{--                                        <i class="fa fa-donate"></i>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
                                <td></td>
                                <td class="text-uppercase">{{ $item['nama'] }}</td>
                                <td class="text-uppercase">{{ $item['alamat'] }} ANGGOTA</td>
                                <td>Rp. {{ number_format($item['saldo']) }}</td>
                                {{-- <td>{{ count($item->pengajuan) }} PENGAJUAN BARU</td> --}}
                                <td class="with-icon">
                                    <a href="{{ route('kas_harian', [$item['id_rekening']]) }}">
                                        <div class="icon default">
                                            <i class="material-icons">more_horiz</i>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->
    </div>
@endsection