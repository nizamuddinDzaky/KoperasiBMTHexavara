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
                <h4 class="title">Pengajuan Pembiayaan Anggota</h4>

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
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#openPemModal"><i class="fa fa-handshake-o"></i> Buka Pembiayaan</button>
                    <button class="btn btn-success rounded right shadow-effect" data-toggle="modal" data-target="#angsurPemModal"><i class="fa fa-money-bill-alt"></i> Angsur Pembiayaan</button>
                    <button class="btn btn-danger rounded right shadow-effect" data-toggle="modal" data-target="#pelunasanLebihAwalPembiayaanModal"><i class="fa fa-money-bill-alt"></i> Pelunasan Pembiayaan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="col-sm-12 col-md-12 col-lg-12" id="ShowTable">
            <div class="card">

                <div class="header text-center">
                    <h4 class="title"><b>Pengajuan Pembiayaan</b> </h4>
                    <p class="category">Daftar Pengajuan Pembiayaan Anggota</p>
                    <br />
                </div>
                
                <table class="table bootstrap-table">
                    <thead>
                    <th></th>
                    <th data-sortable="true" class="text-left">ID</th>
                    <th data-sortable="true">Jenis Pengajuan</th>
                    <th data-sortable="true">Nama Anggota</th>
                    <th data-sortable="true">Keterangan</th>
                    <th data-sortable="true">Tgl Pengajuan</th>
                    <th data-sortable="true">Status</th>
                    <th data-sortable="true">Teller</th>
                    <th class="text-center">Actions</th>
                    </thead>
                    <tbody>
                    @foreach ($data as $usr)
                        <tr>
                            <td></td>
                            <td class="text-left">{{ $usr['id'] }}</td>
                            <td class="text-left">{{ $usr['jenis_pengajuan']   }}</td>
                            <td class="text-left">{{ json_decode($usr['detail'])->nama   }}</td>
                            <td class="text-center">{{$usr['kategori'] }}</td>
                            <td>{{ $usr['created_at'] }} </td>
                            <td class="text-center text-uppercase">{{ $usr['status'] }}</td>
                            <td class="text-center text-uppercase">{{ $usr['teller'] }}</td>

                            <td class="td-actions text-center">
                                <div class="row">
                                    @if(str_before($usr['kategori'],' ')=="Angsuran" || str_before($usr['kategori'],' ')=="Pelunasan")
                                        @if($usr['status']=="Sudah Dikonfirmasi" || $usr['status']=="Disetujui")
                                        @else
                                            {{--KONFIRMASI BUKA BARU--}}
                                            
                                        @if(Auth::user()->tipe=="teller")
                                            <button type="button" id="konfirm" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#confirm{{substr($usr['kategori'],0,3)}}Modal" title="Konfirmasi Pengajuan"
                                                    data-id       = "{{$usr['id']}}"
                                                    data-nama     = "{{ $usr['nama'] }}"
                                                    data-ktp     = "{{ $usr['no_ktp']  }}"
                                                    data-iduser     = "{{ json_decode($usr['detail'],true)['id']}}"
                                                    data-debit     = "{{ json_decode($usr['detail'],true)['angsuran']}}"
                                                    data-jumlah     = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2)}}"
                                                    @if($usr['kategori']=="Angsuran Pembiayaan" || $usr['kategori']=="Pelunasan Pembiayaan")
                                                    data-idtab = "{{ json_decode($usr['detail'],true)['id_pembiayaan'] }}"
                                                    data-namatab = "{{ json_decode($usr['detail'],true)['nama_pembiayaan'] }}"
                                                    data-bankuser = "{{ json_decode($usr['detail'],true)['bank_user'] }}"
                                                    data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                    data-atasnama = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                    data-jenis = "{{ json_decode($usr['detail'],true)['angsuran'] }}"
                                                    data-tipe_pem = "{{ json_decode($usr['detail'],true)['tipe_pembayaran'] }}"
                                                    data-pokok = "{{ number_format(json_decode($usr['detail'],true)['pokok'],2) }}"
                                                    data-bank = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                    data-keterangan = "{{ json_decode($usr['detail'],true)['nama_pembiayaan'] }}"
                                                    data-path       = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                    data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                    data-nisbah       = "{{ number_format(json_decode($usr['detail'],true)['nisbah'],2) }}"
                                                    data-ang       = "{{ number_format(json_decode($usr['detail'],true)['bayar_ang'],2) }}"
                                                    data-mar       = "{{ number_format(json_decode($usr['detail'],true)['bayar_mar'],2) }}"
                                                    data-sisa_ang       = "{{ number_format(json_decode($usr['detail'],true)['sisa_ang'],2) }}"
                                                    data-sisa_mar       = "{{ number_format(json_decode($usr['detail'],true)['sisa_mar'],2) }}"
                                                    data-sisa_pinjaman       = "{{ number_format(json_decode($usr['detail'],true)['sisa_pinjaman'],2) }}"
                                                    @endif
                                            >
                                                <i class="fa fa-check-square"></i>
                                            </button>
                                            @endif
                                            <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editStatusModal" title="Ubah Status Pengajuan"
                                                    data-id      = "{{$usr['id']}}"
                                                    data-id_user = "{{$usr['id_user']}}"
                                                    data-nama    = "{{$usr['jenis_pengajuan']}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endif
                                    @else
                                        @if($usr['status']=="Sudah Dikonfirmasi"  || $usr['status']=="Disetujui")
                                        @else
                                            <button type="button" id="active_" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#active{{substr($usr['kategori'],0,3)}}Modal" title="Aktivasi Rekening"    data-id         = "{{$usr['id']}}"
                                                    data-id         = "{{$usr['id']}}"
                                                    data-namauser   = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                    data-ktp     = "{{ $usr['no_ktp'] }}"
                                                    @if($usr['kategori']=="Pembiayaan")
                                                    data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"
                                                    data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                    data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                    data-jenis       = "{{ json_decode($usr['detail'],true)['jenis_Usaha'] }}"
                                                    data-usaha       = "{{ json_decode($usr['detail'],true)['usaha'] }}"
                                                    data-jaminan       = "{{ json_decode($usr['detail'],true)['jaminan'] }}"
                                                    data-waktu       = "{{ str_before(json_decode($usr['detail'],true)['keterangan'],' ')  }}"
                                                    data-ketwaktu       = "{{ str_after(json_decode($usr['detail'],true)['keterangan'],' ') }}"
                                                    data-path       = "{{ url('/storage/public/'.json_decode($usr['detail'],true)['path_jaminan']) }}"
                                                    data-field       = "{{ ($usr['transaksi'])}}"
                                                    data-list       = "{{ ($usr['list'])}}"
                                                    data-kategori   ="{{ $usr['kategori'] }}"
                                                    data-sum   ="{{ $usr['sum'] }}"
                                                    data-idrek   = "{{ $usr['id_rekening'] }}"
                                                    @endif
                                            >
                                                <i class="fa fa-check-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editStatusModal" title="Ubah Status Pengajuan"
                                                    data-id      = "{{$usr['id']}}"
                                                    data-id_user = "{{$usr['id_user']}}"
                                                    data-nama    = "{{$usr['jenis_pengajuan']}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                <div class="row">
                                    <button type="button" id="detail" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#view{{substr($usr['kategori'],0,3)}}Modal" title="View Detail"
                                            data-id         = "{{$usr['id']}}"
                                            data-namauser   = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                            data-ktp     = "{{ $usr['no_ktp'] }}"

                                            data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                            @if($usr['kategori']=="Pembiayaan")
                                            data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                            data-jenis       = "{{ json_decode($usr['detail'],true)['jenis_Usaha'] }}"
                                            data-usaha       = "{{ json_decode($usr['detail'],true)['usaha'] }}"
                                            data-jaminan       = "{{ json_decode($usr['detail'],true)['jaminan'] }}"
                                            data-waktu       = "{{ str_before(json_decode($usr['detail'],true)['keterangan'],' ')  }}"
                                            data-ketwaktu       = "{{ str_after(json_decode($usr['detail'],true)['keterangan'],' ') }}"
                                            data-path       = "{{ url('/storage/public/'.json_decode($usr['detail'],true)['path_jaminan']) }}"
                                            data-field       = "{{ ($usr['transaksi'])}}"
                                            data-list       = "{{ ($usr['list'])}}"
                                            data-kategori   ="{{ $usr['kategori'] }}"
                                            data-sum   ="{{ $usr['sum'] }}"
                                            data-idrek   = "{{ $usr['id_rekening'] }}"
                                            @elseif(str_before($usr['kategori']," ")=="Angsuran" || str_before($usr['kategori']," ")=="Pelunasan")
                                            data-idtab = "{{ json_decode($usr['detail'],true)['id_pembiayaan'] }}"
                                            data-namatab = "{{ json_decode($usr['detail'],true)['nama_pembiayaan'] }}"
                                            data-bankuser = "{{ json_decode($usr['detail'],true)['bank_user'] }}"
                                            data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                            data-atasnama = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                            data-jenis = "{{ json_decode($usr['detail'],true)['angsuran'] }}"
                                            data-tipe_pem = "{{ json_decode($usr['detail'],true)['tipe_pembayaran'] }}"
                                            data-pokok = "{{ number_format(json_decode($usr['detail'],true)['pokok'],2) }}"
                                            data-bank = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                            data-keterangan = "{{ json_decode($usr['detail'],true)['nama_pembiayaan'] }}"
                                            data-path       = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                            data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                            data-nisbah       = "{{ number_format(json_decode($usr['detail'],true)['nisbah'],2) }}"
                                            data-ang       = "{{ number_format(json_decode($usr['detail'],true)['bayar_ang'],2) }}"
                                            data-mar       = "{{ number_format(json_decode($usr['detail'],true)['bayar_mar'],2) }}"
                                            data-sisa_ang       = "{{ number_format(json_decode($usr['detail'],true)['sisa_ang'],2) }}"
                                            data-sisa_mar       = "{{ number_format(json_decode($usr['detail'],true)['sisa_mar'],2) }}"
                                            data-sisa_pinjaman       = "{{ number_format(json_decode($usr['detail'],true)['sisa_pinjaman'],2) }}"
                                            @else
                                            {{--data-kategori   = "{{ $usr['id']_rekening }}"--}}
                                            data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                            {{--data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"--}}
                                            @endif
                                    >
                                        <i class="fa fa-list-alt"></i>
                                    </button>
                                    @if(str_before($usr['status']," ")=="Disetujui" || str_before($usr['status']," ")=="Sudah")
                                        @if($usr['kategori']=="Pembiayaan")
                                            {{--<form @if(Auth::user()->tipe == "admin") action="{{route('akad.pengajuan_pembiayaan')}}" @elseif(Auth::user()->tipe == "teller") action="{{route('teller.akad.pengajuan_pembiayaan')}}" @endif method="post">--}}
                                                {{ csrf_field() }}
                                                {{--<input type="hidden" name="id" value="{{$usr['id']}}"/>--}}
                                            {{--<a href="{{route('akad.pengajuan_pembiayaan')}}" type="submit"  class="btn btn-social btn-fill" title="Lihat Akad">--}}
                                                <a @if(Auth::user()->tipe == "admin") href="{{route('akad.pengajuan_pembiayaan', ['id' => $usr['id_pembiayaan']])}}" @elseif(Auth::user()->tipe == "teller") href="{{route('teller.akad.pengajuan_pembiayaan', [$usr['id_pembiayaan']])}}" @endif  class="btn btn-social btn-fill" title="Download Akad">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            {{--</form>--}}
                                        @endif
                                    @else
                                        <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delModal" title="Delete"
                                                data-id       = "{{$usr['id']}}"
                                                data-nama     = "{{$usr['jenis_pengajuan']}}">
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
        </div>
    </div>
@endsection

@section('modal')
    @include('modal.pengajuan')
    @include('modal.pembiayaan.angsuranss')
    @include('modal.pembiayaan.view_angsuran')
    @include('modal.pembiayaan.konfirmasi_angsuran')
    @include('modal.pembiayaan.view_pelunasan')
    @include('modal.pembiayaan.konfirmasi_pelunasan')
    @include('modal.pembiayaan.pelunasan')
@endsection

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    
    <!-- Angsuran pembiayaan -->
    <script src=" {{  asset('bmtmudathemes/assets/js/modal/angsuran_pembiayaan.js') }}"></script>

    <!-- Pelunasan pembiayaan -->
    <script src=" {{  asset('bmtmudathemes/assets/js/modal/pelunasan.js') }}"></script>

    <script type="text/javascript">

       //  PEMBIAYAAN
        $('#viewPemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#vrekPem').val(button.data('idrek'));
            var selAr = $('#toHide5v');
            var selAr2 = $('#toHide6v');
            if(button.data('atasnama')==="Lembaga"){
                $('#vatasnama3').val(2);
                $('#vidhukum3').val(button.data('iduser'));
                $('#vnamahukum3').val(button.data('namauser'));
                selAr2.show();
                selAr.hide();
            }else if(button.data('atasnama')==="Pribadi"){
                $('#vatasnama3').val(1);
                $('#viduser3').val(button.data('ktp'));
                $('#vnama3').val(button.data('namauser'));
                selAr2.hide();
                selAr.show();
            }

            jQuery('#vdelJam').remove();
            if(button.data('kategori')=="Pembiayaan"){
                var l = (button.data('list'));
                var obj = (button.data('field'));
                var row = '<div class="rowNum" id="vdelJam">';
                for(i=0;i<(button.data('sum'));i++){
                    obj.split(",")[i];
                    l.split(",")[i];
                    row =row+
                        '<div> ' +
                        '<div class="col-md-10 col-md-offset-1" >'+
                        '<div class="form-group" >'+
                        '<label for="id_" class="control-label">'+l.split(",")[i]+'<star>*</star></label>'+
                        '<input type="text" class="form-control text-left" value="'+obj.split(",")[i]+'" disabled />' +
                        '</div>'+
                        '</div>'+
                        '</div>';
                }
                row =row+ '</div>';
                jQuery('#vdetailJam').append(row);
            }

            $('#vjenis').val(button.data('jenis'));
            $('#vjaminan').val(button.data('jaminan'));
            $('#vrekPem2').val(button.data('jaminan'));
            $('#vjumlah').val(button.data('jumlah'));
            $('#vusaha').val(button.data('usaha'));
            $('#vwaktu').val(button.data('waktu'));
            $('#vketWaktu').val(button.data('ketwaktu'));
            $('#vketerangan3').val(button.data('keterangan'));
            $("#vpic5").attr("src",button.data('path'));
        });
        $('#activePemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#arekPem').val(button.data('idrek'));
            var selAr = $('#toHide5a');
            var selAr2 = $('#toHide6a');
            if(button.data('atasnama')==="Lembaga"){
                $('#aatasnama3').val(2);
                $('#aidhukum3').val(button.data('iduser'));
                $('#anamahukum3').val(button.data('namauser'));
                selAr2.show();
                selAr.hide();
            }else if(button.data('atasnama')==="Pribadi"){
                $('#aatasnama3').val(1);
                $('#aiduser3').val(button.data('ktp'));
                $('#anama3').val(button.data('namauser'));
                selAr2.hide();
                selAr.show();
            }
            jQuery('#adelJam').remove();
            if(button.data('kategori')=="Pembiayaan"){
                var l = (button.data('list'));
                var obj = (button.data('field'));
                var row = '<div class="rowNum" id="adelJam">';
                for(i=0;i<(button.data('sum'));i++){
                    obj.split(",")[i];
                    l.split(",")[i];
                    row =row+
                        '<div> ' +
                        '<div class="col-md-10 col-md-offset-1" >'+
                        '<div class="form-group" >'+
                        '<label for="id_" class="control-label">'+l.split(",")[i]+'<star>*</star></label>'+
                        '<input type="text" class="form-control text-left" value="'+obj.split(",")[i]+'" disabled />' +
                        '</div>'+
                        '</div>'+
                        '</div>';
                }
                row =row+ '</div>';
                jQuery('#adetailJam').append(row);
            };
            $('#ajenis').val(button.data('jenis'));
            $('#ajaminan').val(button.data('jaminan'));
            $('#arekPem2').val(button.data('jaminan'));
            $('#ajumlah').val(button.data('jumlah'));
            $('#ausaha').val(button.data('usaha'));
            $('#awaktu').val(button.data('waktu'));
            $('#aketWaktu').val(button.data('ketwaktu'));
            $('#aketerangan3').val(button.data('keterangan'));
            $("#apic5").attr("src", button.data('path'));
            $('#id_act_pem').val(button.data('id'));
        });
        $('#viewAngModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            if(button.data('jenis')=="Tunai"){
                $("#vtoHideAng").hide();
                $("#vtoHideAngBank").hide();
                $("#vtoHideAngBank2").hide();
                $("#vtoHideTabungan").hide();
            }
            else if(button.data('jenis')=="Transfer"){
                $("#vtoHideAng").show();
                $("#vtoHideAngBank").show();
                $("#vtoHideAngBank2").show();
                $("#vtoHideTabungan").hide();
            }
            else if(button.data('jenis')=="Tabungan"){
                $("#vtoHideAng").hide();
                $("#vtoHideAngBank").hide();
                $("#vtoHideAngBank2").hide();
                $("#vtoHideTabungan").show();
            }
            $("#vangidRek").val(button.data('idtab') );
            $("#vjenisAng").val(button.data('jenis') );
            $("#vjenisPAng").val(button.data('tipe_pem') );
            $("#vbankAng").val(button.data('bankuser') );
            $("#vbank").val(button.data('bank') );
            $("#vtabungan").val(button.data('bank') );
            $("#vban").val(button.data('bankuser') );
            $("#vbagi_pokok").val(button.data('pokok') );
            $("#vbagi_margin").val(button.data('nisbah') );
            $("#vbayar_ang").val(button.data('ang') );
            $("#vbayar_margin").val(button.data('mar') );
            $("#vtagihan_pokok").val(button.data('sisa_pinjaman') )
            $("#vtagihan_margin").val(button.data('sisa_mar') );
            $("#vnobankAng").val(button.data('no_bank') );
            $("#vatasnamaAng").val(button.data('atasnama') );
            $("#vpicAng").attr("src", button.data('path') );

        });

        $('#activePengajuanModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var id_user = button.data('id');
            var nama = button.data('nama');
            var kategori = button.data('kategori');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_active').val(id);
            $('#id_active_user').val(id_user);
            $('#ActiveLabel').text("Aktivasi Akun : " + nama);
            $('#toActive').text(nama + "?");
        });
        $('#editStatusModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var id_user = button.data('id');
            var nama = button.data('nama');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_status').val(id);
            $('#id_status_user').val(id_user);
            $('#StatusLabel').text("Ubah Status : " + nama);
            $('#toStatus').text(nama + "?");
        });
        $('#delModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_del').val(id);
            $('#delTabLabel').text("Hapus Pengajuan : " + nama);
            $('#toDelete').text(nama + "?");
        });
    </script>

    <script type="text/javascript">
        var $table = $('#bootstrap-table');
        function readURL5(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic5')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(auto)
                };


                reader.readAsDataURL(input.files[0]);
            }
        }

        $().ready(function(){
            $('#bootstrap-table').dataTable({
                initComplete: function () {
                    $('.buttons-pdf').html('<span class="fas fa-file" data-toggle="tooltip" title="Export To Pdf"/> PDF')
                    $('.buttons-print').html('<span class="fas fa-print" data-toggle="tooltip" title="Print Table"/> Print')
                    $('.buttons-copy').html('<span class="fas fa-copy" data-toggle="tooltip" title="Copy Table"/> Copy')
                    $('.buttons-excel').html('<span class="fas fa-paste" data-toggle="tooltip" title="Export to Excel"/> Excel')
                },
                "processing": true,
//                "dom": 'lBf<"top">rtip<"clear">',
                "order": [[ 1, "desc" ]],
                "scrollX": false,
                "dom": 'lBfrtip',
                "buttons": {
                    "dom": {
                        "button": {
                            "tag": "button",
                            "className": "waves-effect waves-light btn mrm"
//                            "className": "waves-effect waves-light btn-info btn-fill btn mrm"
                        }
                    },
                    "buttons": [
                        'copyHtml5',
                        'print',
                        'excelHtml5',
//                        'csvHtml5',
                        'pdfHtml5' ]
                }
            });
        });

    </script>
    <script type="text/javascript">
        function remove(field){
            for (i=0;i<= field ;i++){
                jQuery('#rowNum'+i).remove();
            }
        }
        function validate(field,jam2){
            var j = jam2.split(",");
            var row =
                '<div class="rowNum" id="rowNum'+0+'"> ' +
                '<div class="col-md-10 col-md-offset-1" >'+
                '<div class="form-group" >'+
                '<label for="id_" class="control-label">'+ j[0]+'<star>*</star></label>'+
                '<input type="text" class="form-control text-left" name="field[]" required="true"/>' +
                '</div>'+
                '</div>'+
                '</div>';
            jQuery('#detailJam').append(row);
            var rowNum = 0;
            for (i=1;i< field ;i++){
                rowNum ++;
                var row =
                    '<div class="rowNum" id="rowNum'+i+'"> ' +
                    '<div class="col-md-10 col-md-offset-1" >'+
                    '<div class="form-group" >'+
                    '<label for="id_" class="control-label">'+ j[i]+'<star>*</star></label>'+
                    '<input type="text" class="form-control text-left" name="field[]" required="true"/>' +
                    '</div>'+
                    '</div>'+
                    '</div>';
                jQuery('#detailJam').append(row);
            }
        }

        $().ready(function(){
            $('#HideJamber').hide();
            $('#ShowJamber').show();
            var field=0;
            var id_jam=0;
            var jam2=0;
            $('#rekPem2').val(0);
            $('#rekPem2').on('change', function () {
                if(field!=0) remove(field);
                id_jam = $('#rekPem2').val().split('.')[0];
                jam2 = $('#rekPem2').val().split(".")[1];
                field = $('#rekPem2').val().split(".")[2];
                var ketjam = $('#rekPem2').val().split(".")[3];
                validate(field,jam2);
                $('#ketjam').val(ketjam)
            });

            $('#showPok').hide()
            $('#angHide').show()
            $('#marginHide').hide()
            var selA4 =$('#toHide_angpok');
            var rekening = 0; var pokok = 0; var margin = 0;var lama = 0; var angke = 0;var angbln = 0;var marbln = 0;
            var selRek = $('#angidRek');
            selRek.on('change', function () {
                var id = $('#idRekA').val(selRek.find(":selected").text().split(']')[0]);
                id = id.val().split('[')[1];
                $('#idRekA').val(id);
                pokok = parseFloat(selRek.val().split(' ')[0]);
                lama = parseFloat(selRek.val().split(' ')[2]);
                margin = parseFloat(selRek.val().split(' ')[1]);
                rekening = parseFloat(selRek.val().split(' ')[3]);
                angke = parseFloat(selRek.val().split(' ')[4]);
                angbln = parseFloat(selRek.val().split(' ')[5]);
                marbln = parseFloat(selRek.val().split(' ')[6]);
                sisa_pinjaman = parseFloat(selRek.val().split(' ')[8]);

                var formatter = new Intl.NumberFormat('en-US', {maximumFractionDigits:2});
                $('#showPok').hide()
                $('#angHide').show()
                $('#marginHide').show()
                if(marbln==0) {
                    $('#marginHide').hide()
                    $('#bagi_margin').attr("required",false);
                }
                if(angbln==0) {
                    $('#angHide').hide()
                    $('#showPok').show()
                    $('#bagi_margin').attr("required",false);
                }
                if(rekening!=2) {
                    $('#marginHide').hide()
                    $('#sisa_mar').show()
                    $('#bayar_mar').hide()
                    // $('#bayar_margin').val(formatter.format(marbln))
                    $('#bagi_pokok').val(formatter.format(angbln))
                    $('#bayar_ang').val(formatter.format(angbln + marbln))
                    $('#bagi_margin').attr("required",false);
                }
                else if(angke == 0) {
                    $('#sisa_mar').hide()
                    $('#bayar_mar').show()
                    if(parseFloat(selRek.val().split(' ')[7]) !== 99)
                    {
                        $('#bayar_ang').val(formatter.format(pokok-(margin/lama)))
                        $('#bagi_pokok').val(formatter.format(pokok-(margin/lama)))
                    }
                    else
                    {
                        $('#bayar_ang').val(formatter.format(angbln))
                        $('#bagi_pokok').val(formatter.format(angbln))
                    }
                    
                    $('#bagi_margin').attr("required",true);
                }
                else {
                    $('#sisa_mar').show()
                    $('#bagi_margin').attr("required",false);
                    // $('#bayar_mar').show()
                    $('#bayar_ang').val(formatter.format(angbln))
                    // $('#bayar_margin').val(formatter.format(marbln))
                    $('#bagi_pokok').val(formatter.format(pokok-(margin/lama)))
                }

                $('#tagihan_pokok').val(formatter.format(sisa_pinjaman))
                $('#tagihan_margin').val(formatter.format(marbln))
                $('#sisa_ang_').val(formatter.format(angbln))
                $('#sisa_mar_').val(formatter.format(marbln))
                $('#jenis_').val(rekening);
                
                if(parseFloat(selRek.val().split(' ')[7]) !== 99)
                {
                    $('#pokok_').val(pokok-(margin/lama))
                }
                else
                {
                    $('#pokok_').val(angbln)
                }
                selA4.hide();
            });


            var selAr = $('#toHideDeb');
            var selArB =$('#toHideDebBank');
            var selArB2 =$('#toHideDebBank2');
            var selA = $('#toHide_pok');
            var selA2 =$('#toHide_ang');
            var selA3 =$('#toHide_mar');
            var selA5 =$('#toHide_cus');

            var atasnama =$('#atasnamaDeb');
            var bank =$('#bankDeb');
            var nobank =$('#nobankDeb');

            var jenis = $('#debit');
            var bukti = $('#bukti');

            selAr.hide(); selArB.hide(); selArB2.hide(); $("#toHideTabungan").hide();
            selA.hide(); selA2.hide(); selA3.hide();selA4.hide();selA5.hide();
            jenis.on('change', function () {
                if(jenis .val() == 1) {
                    bukti.attr("required",true);
                    bank.attr("required",true);
                    atasnama.attr("required",true);
                    nobank.attr("required",true);
                    selAr.show();
                    selArB.show(); selArB2.show()
                }
                else if (jenis .val() == 0) {
                    $('#bank').val(0);
                    bank.attr("required",false);
                    atasnama.attr("required",false);
                    nobank.attr("required",false);
                    bukti.attr("required",false);
                    selAr.hide();
                    selArB.hide();selArB2.hide();
                }
                else if (jenis .val() == 2) {
                    $('#bank').val(0);
                    bank.attr("required",false);
                    atasnama.attr("required",false);
                    nobank.attr("required",false);
                    bukti.attr("required",false);
                    selAr.hide();
                    selArB.hide();selArB2.hide();
                    $("#toHideTabungan").show();
                    $("#tabungan").attr("required", true);
                }
            });


            var selAr3 = $('#toHide5');
            var selAr4 = $('#toHide6');
            var selTip2 = $('#atasnama3');
            var selHk2 = $('#idhukum3');
            var selHkn2 = $('#namahukum3');
            selAr3.hide();
            selAr4.hide();

            selTip2.on('change', function () {
                if (selTip2.val() == 1) {
                    selAr3.show();
                    selAr4.hide();
                    selHk2.val("null");
                    selHkn2.val("null");
                    $('#namauser3').val($('#nasabah3').find(":selected").text())
                    $('#id_user3').val($('#nasabah3').val())
                }
                else {
                    selAr4.show();
                    selAr3.hide();
                    selHk2.val("");
                    selHkn2.val("");
                }
            });


            $("#rekPem").select2({
                dropdownParent: $("#openPemModal")
            });
            $("#nasabah3").select2({
                dropdownParent: $("#openPemModal")
            });
            $("#angidRek").select2({
                dropdownParent: $("#angsurPemModal")
            });

            lbd.checkFullPageBackgroundImage();

            setTimeout(function(){
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700);
        });
    </script>
    <script type="text/javascript">
        $().ready(function(){
            // Init DatetimePicker
            demo.initFormExtendedDatetimepickers();

        });
        type = ['','info','success','warning','danger'];
        demo = {
            showNotification: function(from, align){
                color = Math.floor((Math.random() * 4) + 1);

                $.notify({
                    icon: "pe-7s-gift",
                    message: "<b>Light Bootstrap Dashboard PRO</b> - forget about boring dashboards."

                },{
                    type: type[color],
                    timer: 4000,
                    placement: {
                        from: from,
                        align: align
                    }
                });
            },
            initFormExtendedDatetimepickers: function(){
                $('.datetimepicker').datetimepicker({
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

                $('.datepicker').datetimepicker({
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

                $('.timepicker').datetimepicker({
//          format: 'H:mm',    // use this format if you want the 24hours timepicker
                    format: 'h:mm A',    //use this format if you want the 12hours timpiecker with AM/PM toggle
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
            },
        }
    </script>
     {{--end of MODAL&DATATABLE --}}


    <script src="{{URL::asset('bootstrap/assets/js/moment.min.js')}}"></script>
    <!--  Date Time Picker Plugin is included in this js file -->
    <script src="{{URL::asset('bootstrap/assets/js/bootstrap-datetimepicker.js')}}"></script>

    <script src="{{URL::asset('bootstrap/assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>
    <script type="text/javascript">
        $().ready(function(){

            var $validator = $("#wizardForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true,
                        minlength: 5
                    },
                    first_name: {
                        required: false,
                        minlength: 5
                    },
                    last_name: {
                        required: false,
                        minlength: 5
                    },
                    website: {
                        required: true,
                        minlength: 5,
                        url: true
                    },
                    framework: {
                        required: false,
                        minlength: 4
                    },
                    cities: {
                        required: true
                    },
                    price:{
                        number: true
                    }
                }
            });

            // you can also use the nav-pills-[blue | azure | green | orange | red] for a different color of wizard

            //            PEMBIAYAAN
            $('#wizardCard3').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '#nextpem',
                previousSelector: '#backpem',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForm3').valid();

                    if(!$valid) {
                        $validator.focusInvalid();
                        return false;
                    }
                },
                onInit : function(tab, navigation, index){

                    //check number of tabs and fill the entire row
                    var $total = navigation.find('li').length;
                    $width = 100/$total;

                    $display_width = $(document).width();

                    if($display_width < 600 && $total > 2){
                        $width = 50;
                    }

                    navigation.find('li').css('width',$width + '%');
                },
                onTabClick : function(tab, navigation, index){
                    // Disable the posibility to click on tabs
                    return false;
                },
                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var wizard = navigation.closest('.card-wizard');
                    // If it's the last tab then hide the last button and show the finish instead
                    if($current == 2){
                        $(wizard).find('.btn-close').hide();
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide();
                    }else if($current == 1){
                        $(wizard).find('.btn-close').show();
                        $(wizard).find('.btn-back').hide();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide()
                    }
                    else if($current == 3){
                        $(wizard).find('.btn-close').hide();
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').hide();
                        $(wizard).find('.btn-finish').show();
                    }
                }

            });
            $('#wizardCard3v').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForm3v').valid();

                    if(!$valid) {
                        $validator.focusInvalid();
                        return false;
                    }
                },
                onInit : function(tab, navigation, index){

                    //check number of tabs and fill the entire row
                    var $total = navigation.find('li').length;
                    $width = 100/$total;

                    $display_width = $(document).width();

                    if($display_width < 600 && $total > 3){
                        $width = 50;
                    }

                    navigation.find('li').css('width',$width + '%');
                },
                onTabClick : function(tab, navigation, index){
                    // Disable the posibility to click on tabs
                    return false;
                },
                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var wizard = navigation.closest('.card-wizard');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current == 2){
                        $(wizard).find('.btn-close').hide();
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').hide();
                        $(wizard).find('.btn-finish').hide();
                    }else if($current == 1){
                        $(wizard).find('.btn-close').show();
                        $(wizard).find('.btn-back').hide();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide()
                    }
                }

            });
            $('#wizardCard3a').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForm3a').valid();

                    if(!$valid) {
                        $validator.focusInvalid();
                        return false;
                    }
                },
                onInit : function(tab, navigation, index){

                    //check number of tabs and fill the entire row
                    var $total = navigation.find('li').length;
                    $width = 100/$total;

                    $display_width = $(document).width();

                    if($display_width < 600 && $total > 3){
                        $width = 50;
                    }

                    navigation.find('li').css('width',$width + '%');
                },
                onTabClick : function(tab, navigation, index){
                    // Disable the posibility to click on tabs
                    return false;
                },
                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var wizard = navigation.closest('.card-wizard');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current == 2){
                        $(wizard).find('.btn-close').hide();
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide();
                    }else if($current == 1){
                        $(wizard).find('.btn-close').show();
                        $(wizard).find('.btn-back').hide();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide()
                    }
                    else if($current == 3){
                        $(wizard).find('.btn-close').hide();
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').hide();
                        $(wizard).find('.btn-finish').show();
                    }
                }

            });
            $('#wizardCardAng').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormAng').valid();

                    if(!$valid) {
                        $validator.focusInvalid();
                        return false;
                    }
                },
                onInit : function(tab, navigation, index){

                    //check number of tabs and fill the entire row
                    var $total = navigation.find('li').length;
                    $width = 100/$total;

                    $display_width = $(document).width();

                    if($display_width < 600 && $total > 3){
                        $width = 50;
                    }

                    navigation.find('li').css('width',$width + '%');
                },
                onTabClick : function(tab, navigation, index){
                    // Disable the posibility to click on tabs
                    return false;
                },
                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var wizard = navigation.closest('.card-wizard');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current >= $total) {
                        $(wizard).find('.btn-next').hide();
                        $(wizard).find('.btn-finish').show();
                    } else if($current == 1){
                        $(wizard).find('.btn-back').hide();
                    } else {
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide();
                    }
                }

            });
            $('#wizardCardAngv').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormAngv').valid();

                    if(!$valid) {
                        $validator.focusInvalid();
                        return false;
                    }
                },
                onInit : function(tab, navigation, index){

                    //check number of tabs and fill the entire row
                    var $total = navigation.find('li').length;
                    $width = 100/$total;

                    $display_width = $(document).width();

                    if($display_width < 600 && $total > 3){
                        $width = 50;
                    }

                    navigation.find('li').css('width',$width + '%');
                },
                onTabClick : function(tab, navigation, index){
                    // Disable the posibility to click on tabs
                    return false;
                },
                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var wizard = navigation.closest('.card-wizard');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current >= $total) {
                        $(wizard).find('.btn-next').hide();
                        $(wizard).find('.btn-finish').show();
                    } else if($current == 1){
                        $(wizard).find('.btn-back').hide();
                    } else {
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide();
                    }
                }

            });
            $('#wizardCardAnga').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormAnga').valid();

                    if(!$valid) {
                        $validator.focusInvalid();
                        return false;
                    }
                },
                onInit : function(tab, navigation, index){

                    //check number of tabs and fill the entire row
                    var $total = navigation.find('li').length;
                    $width = 100/$total;

                    $display_width = $(document).width();

                    if($display_width < 600 && $total > 3){
                        $width = 50;
                    }

                    navigation.find('li').css('width',$width + '%');
                },
                onTabClick : function(tab, navigation, index){
                    // Disable the posibility to click on tabs
                    return false;
                },
                onTabShow: function(tab, navigation, index) {
                    var $total = navigation.find('li').length;
                    var $current = index+1;

                    var wizard = navigation.closest('.card-wizard');

                    // If it's the last tab then hide the last button and show the finish instead
                    if($current >= $total) {
                        $(wizard).find('.btn-next').hide();
                        $(wizard).find('.btn-finish').show();
                    } else if($current == 1){
                        $(wizard).find('.btn-back').hide();
                    } else {
                        $(wizard).find('.btn-back').show();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide();
                    }
                }

            });

        });

        function onFinishWizard(){
            //here you can do something, sent the form to server via ajax and show a success message with swal

            swal("Data disimpan!", "Terima kasih telah melengkapi data diri anda!", "success");
        }
    </script>
@endsection
@section('footer')
    @include('layouts.footer')
@endsection