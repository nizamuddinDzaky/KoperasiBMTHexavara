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
                <h4 class="title">Riwayat Tabungan</h4>

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
                            <h4 class="title"> Riwayat Tabungan Anggota</h4>
                            <p class="category">Daftar  Riwayat Transaksi Tabungan Anggota</p>
                            <br />
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                                          <span></span>
                    </div>

                        <table class="table bootstrap-table-asc">
                            <thead>
                            <th></th>
                                <th data-sortable="true" class="text-left">TGL TRANSAKSI</th>
                                <th data-sortable="true">NAMA ANGGOTA</th>
                                <th data-sortable="true">JENIS TRANSAKSI</th>
                                <th data-sortable="true">DEBIT</th>
                                <th data-sortable="true">KREDIT</th>
                                <th data-sortable="true">SALDO</th>
                            </thead>
                            <tbody>
                            @foreach ($data_tabungan as $item)
                                <tr>
                                    <td></td>
                                    <td>{{ $item->created_at->format('D, d M Y H:i') }}</td>
                                    <td>{{ $item->user->nama  }}</td>
                                    <td>{{ $item->status   }}</td>
                                    @if($item->status == "Debit" || str_before($item->status, " ") == "Distribusi" || (str_before($item->status, " ") == "SIMPANAN" && json_decode($item->transaksi)->jumlah > 0)  || (str_before($item->status, " ") == "Transfer" && json_decode($item->transaksi)->jumlah > 0 ))
                                    <td>{{ number_format(json_decode($item->transaksi)->jumlah,2)  }}</td>
                                    @else
                                    <td>0</td>
                                    @endif

                                    @if($item->status == "Kredit" || (str_before($item->status, " ") == "SIMPANAN" && json_decode($item->transaksi)->jumlah < 0 ) || str_before($item->status, " ") == "Angsuran" || str_before($item->status, " ") == "Pembayaran" || str_before($item->status, " ") == "Angsuran" || (str_before($item->status, " ") == "Transfer" && json_decode($item->transaksi)->jumlah < 0 ) || str_before($item->status, " ") == "Pelunasan" )
                                    <td>{{ str_replace("-", "", number_format(json_decode($item->transaksi)->jumlah))  }}</td>
                                    @else
                                    <td>0</td>
                                    @endif

                                    <td>{{ number_format(json_decode($item->transaksi)->saldo_akhir)  }}</td>
                                    {{-- <td class="text-uppercase text-center">{{ $usr->status }}</td> --}}
                                    {{-- <td class="td-actions text-center">
                                        <form  method="post" action="{{route('admin.detail_tabungan')}}">
                                            <input type="hidden" id="id_status" name="id_" value="{{$usr->id}}">
                                            {{csrf_field()}}
                                            <button type="submit" class="btn btn-social btn-info btn-fill @if($usr->status=="blocked" || $usr->status=="not active") btn-danger" @endif title="Detail"
                                                    data-id      = "{{$usr->no_ktp}}"
                                                    data-nama    = "{{$usr->nama}}" name="id">
                                                <i class="fa fa-clipboard-list"></i>
                                            </button>

                                            @if($usr->status!="closed" && $usr->status!="not active")
                                                <button type="button"  @if($usr->status=="blocked" || $usr->status=="not active")  class="btn btn-social btn-fill btn-success" title="Aktivasi Rekening" @else  class="btn btn-social btn-fill btn-danger" title="Blokir Rekening"@endif  data-toggle="modal" data-target="#blockRekModal"
                                                        data-id         = "{{$usr->id}}"
                                                        data-nama       = "{{$usr->jenis_tabungan}}"
                                                        data-status       = "{{$usr->status}}">
                                                    @if($usr->status=="active")
                                                        <i class="fa fa-remove"></i>
                                                    @elseif($usr->status=="blocked")
                                                        <i class="fa fa-check-square"></i>
                                                    @endif
                                                </button>
                                            @endif
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->
    </div>
@endsection