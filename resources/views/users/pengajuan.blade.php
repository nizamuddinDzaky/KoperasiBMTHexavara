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

@section('custom-component')
    <!-- Loader component -->
    @include('components.loader')
@endsection

@section('content')
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Riwayat Pengajuan Anggota</h4>

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
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#openTabModal"><i class="fa fa-archive"></i> Buka Tabungan</button>
                    <button class="btn btn-warning rounded right shadow-effect" data-toggle="modal" data-target="#openDepModal"><i class="fa fa-credit-card"></i> Buka Mudharabah Berjangka</button>
                    <button class="btn btn-success rounded right shadow-effect" data-toggle="modal" data-target="#openPemModal"><i class="fa fa-handshake-o"></i> Buka Pembiayaan</button>
                    <button class="btn btn-danger rounded right shadow-effect close-rekening-action"><i class="fa fa-close"></i> Keluar Jadi Anggota</button>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="header text-center">
                        {{-- <h4 class="title">Riwayat Pengajuan </h4>
                        <p class="category">daftar pengajuan permohonan Tabungan, Mudharabah Berjangka & Pembiayaan</p>
                        <br /> --}}
                    </div>
                
                    <table id="bootstrap-table" class="table">
                        <thead>
                            <th></th>
                            <th data-field="id" data-sortable="true" class="text-left">ID Pengajuan</th>
                            <th data-field="nama" data-sortable="true">Jenis Pengajuan</th>
                            <th data-field="alamat" data-sortable="true">Keterangan</th>
                            <th data-field="jenis" data-sortable="true">Tgl Pengajuan</th>
                            <th data-field="registrasi" data-sortable="true">Status</th>
                            <th>Actions</th>
                            <th></th>
                        </thead>
                        <tbody>
                        @foreach ($data as $usr)
                            <tr>
                                <td></td>
                                <td>{{ $usr['id'] }}</td>
                                <td>{{ $usr['jenis_pengajuan']   }}</td>
                                @if(str_before($usr['kategori'],' ')=="Debit" || str_before($usr['kategori'],' ')=="Kredit" )
                                    <td>{{json_decode($usr['detail'],true)['nama_tabungan']." [ID : ".json_decode($usr['detail'],true)['id_tabungan']."]"  }}</td>
                                @elseif(str_before($usr['kategori'],' ')=="Angsuran")
                                    <td>{{json_decode($usr['detail'],true)['nama_pembiayaan']." [ID : ".json_decode($usr['detail'],true)['id_pembiayaan']."]"  }}</td>
                                @elseif(str_before($usr['kategori'],' ')=="Donasi" ||str_before($usr['kategori'],' ')=="Simpanan")
                                    <td>{{ $usr['kategori']}}</td>
                                @else
                                    <td>{{ json_decode($usr['detail'],true)['keterangan'] }}</td>
                                @endif
                                <td>{{ $usr['created_at'] }}</td>
                                <td>{{ $usr['status'] }}</td>

                                <td class="td-actions text-center">
                                    <div class="row">
                                        <button type="button" id="detail" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#view{{substr($usr['kategori'],0,3)}}Modal" title="View Detail"
                                                data-id         = "{{$usr['id']}}"
                                                data-namauser   = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                data-ktp     = "{{ $usr['no_ktp'] }}"

                                                @if(str_before($usr['kategori'],' ')=="Debit" || str_before($usr['kategori'],' ')=="Kredit")
                                                data-iduser     = "{{ json_decode($usr['detail'],true)['id']}}"
                                                data-debit     = "{{ json_decode($usr['detail'],true)[strtolower(str_before($usr['kategori'],' '))]}}"
                                                data-jumlah     = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2)}}"
                                                @if(str_before($usr['kategori'],' ')=="Kredit")
                                                data-path     = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'])}}"
                                                data-idtab     = "{{ json_decode($usr['detail'],true)['id_tabungan'] }}"
                                                @elseif(str_before($usr['kategori'],' ')=="Debit")
                                                data-atasnama     = "{{ json_decode($usr['detail'],true)['bank']}}"
                                                data-no_bank   = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                data-idtab     = "{{ json_decode($usr['detail'],true)['id_tabungan'] }}"
                                                @endif
                                                data-bank     = "{{ json_decode($usr['detail'],true)['bank']}}"
                                                @elseif(str_before($usr['kategori']," ")=="Angsuran")
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
                                                @elseif($usr['jenis_pengajuan'] =="Perpanjangan Deposito")
                                                data-iduser     = "{{ json_decode($usr['detail'],true)['id']}}"
                                                data-atasnama   = "Pribadi"
                                                data-kategori   = "{{ json_decode($usr['detail'],true)['id_rekening_baru'] }}"
                                                data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"
                                                data-iddep     = "{{ json_decode($usr['detail'],true)['id_deposito']}}"
                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                @elseif(str_before($usr['kategori'],' ')=="Pencairan")
                                                data-iddep     = "{{ json_decode($usr['detail'],true)['id_deposito']}}"
                                                {{-- data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}" --}}
                                                {{-- data-bank   = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                data-nobank   = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                data-jenis   = "{{ json_decode($usr['detail'],true)['pencairan'] }}" --}}
                                                data-kategori   = "{{ $usr['kategori']}}"
                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"
                                                @elseif(str_before($usr['kategori'],' ')=="Simpanan")
                                                data-bankuser = "{{ json_decode($usr['detail'],true)['daribank'] }}"
                                                data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                data-jenis = "{{ json_decode($usr['detail'],true)['jenis'] }}"
                                                data-iduser = "{{ json_decode($usr['detail'],true)['id'] }}"
                                                data-atasnama = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                data-bank = "{{ json_decode($usr['detail'],true)['bank_tujuan_transfer'] }}"
                                                data-path       = "{{ url('/storage/public/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                @elseif(str_before($usr['kategori'],' ')=="Donasi")
                                                data-bankuser = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                data-atasnama = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                data-kegiatan = "{{ json_decode($usr['detail'],true)['id_maal'] }}"
                                                data-jenis = "{{ json_decode($usr['detail'],true)['debit'] }}"
                                                {{-- data-kebank = "{{ json_decode($usr['detail'],true)['dari'] }}" --}}
                                                data-path       = "{{ url('/storage/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                data-keterangan = "{{ $usr['kategori'] }}"
                                                @else
                                                data-iduser     = "{{ json_decode($usr['detail'],true)['id']}}"
                                                data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"
                                                data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                @endif

                                                @if($usr['kategori']=="Tabungan" || $usr['kategori']=="Tabungan Awal")
                                                data-akad       = "{{ json_decode($usr['detail'],true)['akad'] }}"
                                                @elseif($usr['kategori']=="Pembiayaan")
                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                data-jenis       = "{{ json_decode($usr['detail'],true)['jenis_Usaha'] }}"
                                                data-usaha       = "{{ json_decode($usr['detail'],true)['usaha'] }}"
                                                data-jaminan       = "{{ json_decode($usr['detail'],true)['jaminan'] }}"
                                                data-waktu       = "{{ str_before(json_decode($usr['detail'],true)['keterangan'],' ')  }}"
                                                data-ketwaktu       = "{{ str_after(json_decode($usr['detail'],true)['keterangan'],' ') }}"
                                                data-path       = "{{url('/storage/public/'. json_decode($usr['detail'],true)['path_jaminan']) }}"
                                                data-field       = "{{ ($usr['transaksi'])}}"
                                                data-list       = "{{ ($usr['list'])}}"
                                                data-kategori   ="{{ $usr['kategori'] }}"
                                                data-sum   ="{{ $usr['sum'] }}"
                                                @elseif($usr['kategori']=="Deposito")
                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                data-rek_tab       = "{{ isset(json_decode($usr['detail'],true)['id_pencairan'])?json_decode($usr['detail'],true)['id_pencairan']:"" }}"
                                                data-nisbah       = "{{ json_decode($usr['deposito'],true)['nisbah_anggota'] }}"
                                                data-perpanjang_otomatis       = "{{ json_decode($usr['detail'],true)['perpanjangan_otomatis'] }}"
                                                @endif
                                        >
                                            <i class="fa fa-list-alt"></i>
                                        </button>
                                        @if($usr['status'] =="Disetujui" || $usr['status'] =="Sudah Dikonfirmasi")
                                        @else
                                        <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delModal" title="Delete"
                                                data-id       = "{{$usr['id']}}"
                                                data-nama     = "{{$usr['jenis_pengajuan']}}">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
    </div>

    <!-- For checking pembiyaan to use in penutupan rekening -->
    @foreach($pembiayaanUser as $pembiayaan)
        <input type="hidden" id="pembiayaan" value="{{ $pembiayaan->status }}">
    @endforeach

    @include('modal.pengajuan')
    @include('modal.user_tabungan')
    @include('modal.pembiayaan.angsuran')
    @include('modal.pembiayaan.view_angsuran')
    @include('modal.pembiayaan.konfirmasi_angsuran')
    @include('modal.tutup_rekening')
@endsection

    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    <script type="text/javascript">
        {{--url_add = "{{route('anggota.add_pembiayaan')}}";--}}
        {{--url_edit = "{{route('anggota.edit_pengajuan')}}";--}}
        {{--url_delete = "{{route('anggota.delete_pengajuan')}}";--}}
    </script>

    <script src="{{ asset('bmtmudathemes/assets/js/modal/tutup_rekening.js') }}"></script>

    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('#viewAngModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            if(button.data('jenis')=="Tunai"){
                $("#vtoHideAng").hide();
                $("#vtoHideAngBank").hide();
                $("#vtoHideAngBank2").hide();
            }
            else if(button.data('jenis')=="Transfer"){
                $("#vtoHideAng").show();
                $("#vtoHideAngBank").show();
                $("#vtoHideAngBank2").show();
            }
            $("#vangidRek").val(button.data('idtab') );
            $("#vjenisAng").val(button.data('jenis') );
            $("#vjenisPAng").val(button.data('tipe_pem') );
            $("#vbankAng").val(button.data('bankuser') );
            $("#vbank").val(button.data('bank') );
            $("#vban").val(button.data('bankuser') );
            $("#vbagi_pokok").val(button.data('pokok') );
            $("#vbagi_margin").val(button.data('nisbah') );
            $("#vbayar_ang").val(button.data('ang') );
            $("#vbayar_margin").val(button.data('mar') );
            $("#vtagihan_pokok").val(button.data('sisa_ang') )
            $("#vtagihan_margin").val(button.data('sisa_mar') );
            $("#vnobankAng").val(button.data('no_bank') );
            $("#vatasnamaAng").val(button.data('atasnama') );
            $("#vpicAng").attr("src", button.data('path') );

        });
        $('#viewSimModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#vtoHideW');
            var selAr2 = $('#vtoHideW2');
            var selAr3 = $('#vtoHideWB');
            console.log(button.data('jenis'))

            if(button.data('jenis') === 0) {
                $('#vjwajib').val(0);
                selAr.hide();
                selAr2.hide();
                selAr3.hide();
            }else {
                $('#vjwajib').val(1);
                selAr.show();
                selAr2.show();
                selAr3.show();
            }
            $('#vnasabah_wajib').val(button.data('iduser'));
            $('#vbankW').val(button.data('bankuser'));
            $('#vnobankW').val(button.data('no_bank'));
            $('#vatasnamaW').val(button.data('bankuser'));
            $('#vjumlahW').val(button.data('jumlah'));
            $('#vpicw')
                .attr('src',  button.data('path'))
        });

        $('#viewDonModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHideBankDon');
            var selAr2 = $('#toHideBank2Don');
            var selAr3 = $('#toHideTabDon');
            var selAr4 = $('#RekBank');
            if(button.data('jenis')==="Transfer") {
                selAr.show();
                selAr2.show();
                selAr3.hide();
                selAr4.show();
            }else if(button.data('jenis')==="Tabungan"){
                selAr.hide();
                selAr2.hide();
                selAr3.show();
                selAr4.hide();
            }
            else if(button.data('jenis')==="Tunai"){
                selAr.hide();
                selAr2.hide();
                selAr3.hide();
                selAr4.hide();
            }
            $('#vidRekDon').val(button.data('kegiatan'));
            $('#vjenisDon').val(button.data('jenis'));
            $('#vatasnamaDon').val(button.data('atasnama'));
            $('#vnobankDon').val(button.data('no_bank'));
            $('#vjumlahDon').val(button.data('jumlah'));
            $('#vbankDon').val(button.data('bankuser'));
            $('#vbank_').val(button.data('kebank'));
            $('#vbuktiDon').val(button.data('path'));
            $('#vpicDon')
                .attr('src',  button.data('path'))
        });

        $('#viewTabModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#vrekAkad').val(button.data('akad'));
            $('#vrekTab').val(button.data('kategori'));
            var selAr = $('#toHidev');
            var selAr2 = $('#toHide2v');
            if(button.data('atasnama')==="Lembaga"){
                $('#vatasnama').val(2);
                $('#vidhukum').val(button.data('iduser'));
                $('#vnamahukum').val(button.data('namauser'));
                selAr2.show();
                selAr.hide();
            }else if(button.data('atasnama')==="Pribadi"){
                $('#vatasnama').val(1);
                $('#viduser').val(button.data('ktp'));
                $('#vnama').val(button.data('namauser'));
                selAr.show();
                selAr2.hide();
            }
            $('#vketerangan').val(button.data('keterangan'));
        });
        $('#viewDepModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#vrekDep').val(button.data('kategori'));
            var selAr = $('#toHide3v');
            var selAr2 = $('#toHide4v');
              if(button.data('atasnama')==="Lembaga"){
                $('#vatasnama2').val(2);
                $('#vidhukum2').val(button.data('iduser'));
                $('#vnamahukum2').val(button.data('namauser'));
                selAr2.show();
                selAr.hide();
            }else if(button.data('atasnama')==="Pribadi"){
                $('#vatasnama2').val(1);
                $('#viduser2').val(button.data('ktp'));
                $('#vnama2').val(button.data('namauser'));
                selAr2.hide();
                selAr.show();
            }
            $('#vket_nisbah').val(button.data('nisbah'));
            $('#vrek_tabungan').val(button.data('rek_tab'));
            $('#vketerangan2').val(button.data('keterangan'));
            $('#vjumlahdep').val(button.data('jumlah'));

            if(button.data('perpanjang_otomatis') == true)
            {
                $('#vPerpanjanganOtomatisDeposito').attr('checked', 'checked');
            }
        });
        $('#viewPemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#vrekPem').val(button.data('kategori'));
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
//                jQuery('#vdetailJam').append(row);
                for(i=0;i<=(button.data('sum'));i++){
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
        $('#viewKreModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHideDebv');
            var selB = $('#toHideDebBankv');
            var selB2 = $('#toHideDebBank2v');
            selAr.hide();
            if(button.data('debit')=="Tunai"){
                selAr.hide();
                selB.hide();
                selB2.hide();
            }else{
                selAr.show();
                selB.show();
                selB2.show();
                $('#vbankdeb').val(button.data('bank'));

                $('#vatasnamaDeb').val(button.data('atasnamabank'));
                $('#vbankDeb').val(button.data('banktr'));
                $('#vnobankDeb').val(button.data('no_banktr'));
            }
            $('#vdebnama').val(button.data('namauser'));
            $('#vdebktp').val(button.data('ktp'));

            $('#vRekDeb').val(button.data('idtab'));
            $('#vdebitdeb').val(button.data('debit'));
            $('#vjumlahdeb').val(button.data('jumlah'));
            $('#vbuktideb').val(button.data('path'));
            $('#picDeb')
                .attr('src', button.data('path'))
        });
        $('#viewDebModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHideKrev');
            var selAr2 = $('#toHideKre2v');
            selAr.hide();
            selAr2.hide();
            if(button.data('debit') === "Tunai"){
                selAr.hide();
                selAr2.hide();
            }
            else if(button.data('debit') === "Transfer"){
                selAr.show();
                selAr2.show();
            }
            console.log(button.data('idtab'));
            console.log("da");
            $('#vRekKre').val(button.data('idtab'));
            $('#vkredit').val(button.data('debit'));
            $('#vnobankKre').val(button.data('no_bank'));
            $('#vatasnamaKre').val(button.data('atasnama'));
            $('#vjumlahKre').val(button.data('jumlah'));
            $('#vsaldo_kre').val(button.data('saldo'));
            $('#vbankKre').val(button.data('bank'));
        });
        $('#viewPenModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHidePenv');
            var selAr2 = $('#toHidePen2v');
            if(button.data('jenis')==="Transfer"){
                selAr2.show();
                selAr.show();
            }else{
                selAr2.hide();
                selAr.hide();
            }

            $('#vjenisPen').val(button.data('jenis'));
            $('#vatasnamaPen').val(button.data('atasnama'));
            $('#vnobankPen').val(button.data('nobank'));
            $('#vbankPen').val(button.data('bank'));

            $('#vwidRek').val(button.data('iddep'));
            $('#vwketerangan').val(button.data('keterangan'));
            $('#vwjumlah').val(button.data('jumlah'));
        });
        $('#delModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            console.log(nama);
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_del').val(id);
            $('#delTabLabel').text("Hapus Pengajuan : " + nama);
            $('#toDelete').text(nama + "?");
        });
    </script>

    <script type="text/javascript">
        var $table = $('#bootstrap-table');
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

            var reloadTable = function(employees) {
                var table = $('#employeesTable');
                table.find("tbody tr").remove();
                employees.forEach(function (employee) {
                    table.append("<tr><td>" + employee.id + "</td><td>" + employee.name + "</td></tr>");
                });
            };

            var jam2 =0;
            var id_jam =0;
            var field = 0;
            $('#HideJamber').hide();
            $('#ShowJamber').show();
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

            var selNisbah = $('#rekDep');
            var id = 0;
            var nisbah =0;
            selNisbah.on('change', function () {
                id = parseFloat(selNisbah.val().split(' ')[0]);
                nisbah = parseFloat(selNisbah.val().split(' ')[1]);
                $('#deposito_id').val(id);
                $('#ket_nisbah').val(nisbah);
            });

            var selHk = $('#idhukum');
            var selHk2 = $('#idhukum2');
            var selHk3 = $('#idhukum3');
            var selHkn = $('#namahukum');
            var selHkn2 = $('#namahukum2');
            var selHkn3 = $('#namahukum3');
            var selAr = $('#toHide');
            var selAr2 = $('#toHide2');
            var selAr3 = $('#toHide3');
            var selAr4 = $('#toHide4');
            var selAr5 = $('#toHide5');
            var selAr6 = $('#toHide6');
            selAr.hide();
            selAr3.hide();
            selAr5.hide();
            selAr2.hide();
            selAr4.hide();
            selAr6.hide();
            var selTip = $('#atasnama');
            var selTip2 = $('#atasnama2');
            var selTip3 = $('#atasnama3');
            selTip.on('change', function () {
                if (selTip.val() == 1) {
                    selAr.show();selAr2.hide();
                    selHk.val("null");
                    selHkn.val("null");
                }
                else if (selTip.val() == 2) {
                    selAr2.show();selAr.hide();
                    selHk.val("");
                    selHkn.val("");
                }
            });
            selTip2.on('change', function () {
                    if (selTip2.val() == 1) {
                        selAr3.show();selAr4.hide();
                        selHk2.val("null");
                        selHkn2.val("null");
                    }
                    else {
                        selAr4.show();selAr3.hide();
                        selHk2.val("");
                        selHkn2.val("");
                    }
            });
            selTip3.on('change', function () {
                if(selTip3.val() == 1) {
                    selAr5.show();selAr6.hide();
                    selHk3.val("null");
                    selHkn3.val("null");
                }
                else{
                    selAr6.show();selAr5.hide();
                    selHk3.val("");
                    selHkn3.val("");
                }
            });

            var selKr = $('#toHidePen');
            var selKr2 = $('#toHidePen2');
            var jenisK = $('#jenisPen');
            jenisK.val(0);
            selKr.hide();
            selKr2.hide();
            var aPen  =$('#atasnamaPen');
            var nbPen  =$('#nobankPen');
            var bPen =$('#bankPen');
            jenisK.on('change', function () {
                if(jenisK .val() == 1) {
                    selKr.show()
                    selKr2.show()
                    aPen.attr("required",true);
                    bPen.attr("required",true);
                    nbPen.attr("required",true);
                }
                else if (jenisK .val() == 0) {
                    aPen.attr("required",false);
                    bPen.attr("required",false);
                    nbPen.attr("required",false);
                    selKr.hide();
                    selKr2.hide();
                }
            });


            $("#rekAkad").select2({
                dropdownParent: $("#openTabModal")
            });
            $("#rekTab").select2({
                dropdownParent: $("#openTabModal")
            });
            $("#rekDep").select2({
                dropdownParent: $("#openDepModal")
            });
            $("#rekPem").select2({
                dropdownParent: $("#openPemModal")
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

            //               WAJIB
            $('#wizardCardWS').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormWS').valid();

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
            $('#wizardCard').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForm').valid();

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
            $('#wizardCardv').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormv').valid();

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
            $('#wizardCard2').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForm2').valid();

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
            $('#wizardCard2v').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForm2v').valid();

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
                        $(wizard).find('#closepem').hide();
                        $(wizard).find('#backpem').show();
                        $(wizard).find('#nextpem').hide();
                        $(wizard).find('#finishpem').show();
                    }else if($current == 1){
                        $(wizard).find('.btn-close').show();
                        $(wizard).find('.btn-back').hide();
                        $(wizard).find('.btn-next').show();
                        $(wizard).find('.btn-finish').hide();
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
            $('#wizardCardDebv').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormDebv').valid();

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
            $('#wizardCardKrev').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormKrev').valid();

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
            $('#wizardCardDepv').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormDepv').valid();

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
            $('#wizardCardW').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormW').valid();

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
            $('#wizardCardWv').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormWv').valid();

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
            $('#wizardCardDv').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormDv').valid();

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

        });

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
        function onFinishWizard(){
            //here you can do something, sent the form to server via ajax and show a success message with swal

            swal("Data disimpan!", "Terima kasih telah melengkapi data diri anda!", "success");
        }
    </script>


@endsection
@section('footer')
    @include('layouts.footer')
@endsection