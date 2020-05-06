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
                <h4 class="title">Pengajuan Penutupan Rekening</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode Pengajuan</p>
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
            <div class="col-md-12">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Pengajuan Penutupan Rekening </h4>
                        <p class="category">Daftar Pengajuan Anggota</p>
                        <br />
                    </div>
                    <table class="table bootstrap-table">
                        <thead>
                            <th></th>
                            <th class="text-left" data-sortable="true">ID Pengajuan</th>
                            <th class="text-left" data-sortable="true">Jenis Pengajuan</th>
                            <th class="text-left" data-sortable="true">Nama Anggota</th>
                            <th class="text-left" data-sortable="true">Kategori</th>
                            <th class="text-left" data-sortable="true">Tanggal Pengajuan</th>
                            <th class="text-left" data-sortable="true">Status</th>
                            <th class="text-left">Actions</th>
                        </thead>
                        <tbody>
                            
                            @foreach ($data as $usr)
                                <tr>
                                    <td></td>
                                    <td class="text-left">{{ $usr['id'] }}</td>
                                    <td class="text-left">{{ $usr['jenis_pengajuan']   }}</td>
                                    <td class="text-center">{{ json_decode($usr['detail'],true)['nama'] }}</td>
                                    <td class="text-left">{{$usr['kategori'] }}</td>
                                    <td>{{ $usr['created_at']->format('d F Y') }}</td>
                                    <td class="text-left">{{ $usr['status'] }}</td>
                                    <td class="td-actions text-center">
                                        <div class="row">
                                            @if($usr['status']=="Sudah Dikonfirmasi")
                                            @elseif($usr['status']=="Disetujui" || substr($usr['status'],2,9)=="Disetujui")
                                                @if(Auth::user()->tipe=="teller")
                                                    {{--KONFIRMASI UNTUK TRANSAKSI--}}
                                                    <button type="button" id="konfirm" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#pencairan{{substr($usr['kategori'],0,3)}}Modal" title="Konfirmasi Pengajuan"
                                                        data-id       = "{{$usr['id']}}"
                                                        data-id_user       = "{{json_decode($usr['detail'])->id}}"
                                                        data-nama     = "{{ json_decode($usr['detail'])->nama }}"
                                                        {{-- data-jumlah     = "{{ number_format(json_decode($usr['detail'],true)['jumlah'])}}" --}}
                                                        {{-- data-debit     = "{{ json_decode($usr['detail'],true)['jenis']}}" --}}
                                                        {{-- data-bank_tujuan     = "{{ json_decode($usr['detail'],true)['bank_tujuan_transfer']  }}" --}}
                                                        {{-- data-namabank     = "{{ json_decode($usr['detail'],true)['daribank']}}" --}}
                                                        {{-- data-nobank     = "{{ json_decode($usr['detail'],true)['no_bank']}}" --}}
                                                        data-atasnama     = "{{ json_decode($usr['detail'],true)['atasnama']}}"
                                                        {{-- data-banktujuan     = "{{ json_decode($usr['detail'],true)['bank_tujuan_transfer']}}" --}}
                                                        {{-- data-pathbukti     = "{{ json_decode($usr['detail'],true)['path_bukti']}}" --}}
                                                    >
                                                        <i class="fa fa-check-square"></i>
                                                    </button>
                                                @endif
                                            @else
                                                <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editStatusModal" title="Ubah Status Pengajuan"
                                                        data-id      = "{{$usr['id']}}"
                                                        data-id_user = "{{$usr['id_user']}}"
                                                        data-nama    = "{{$usr['jenis_pengajuan']}}">
                                                    <i class="fa fa-edit"></i>
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
        </div> <!-- end row -->
    </div>

    @include('modal.penutupan_rekening.pencairan')
    @include('modal.penutupan_rekening.edit_status')
@endsection

@section('extra_script')

    {{-- MODAL&DATATABLE --}}

    <!-- simpanan user modal -->
    <script src="{{ asset('bmtmudathemes/assets/js/modal/simpanan.js') }}"></script>

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('#pencairanPenModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $("#nama_user_penutupan_rekening").val(button.data('nama'));
            $("#id_pengajuan").val(button.data('id'));
            $("#id_user_penutupan_rekening").val(button.data('id_user'));
            $("#atasnama_penutupan_rekening").val(button.data('atasnama'));
        });

        $('#editStatusModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            $("#id_status").val(button.data('id'));
        });
    </script>

@endsection