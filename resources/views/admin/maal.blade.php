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
                <h4 class="title">Pengajuan Maal</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode Pengajuan</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                            @foreach($periode as $p)
                                <option value="{{ substr($p,0,4)."/".substr($p,5,6)}}"> {{substr($p,0,4)}} - {{substr($p,5,6)}}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="button-group right">
                    <button class="btn btn-primary rounded right shadow-effect"><i class="fa fa-plus"></i> Tambah Pengajuan</button>
                </div>
                
            </div>
        </div>
    </div>

    <div class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="header text-center">
                            <h4 class="title">Pengajuan Donasi Maal </h4>
                            <p class="category">Daftar Pengajuan Nasabah</p>
                            <br />
                        </div>
                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th></th>
                            <th class="text-center" data-sortable="true">ID Pengajuan</th>
                            <th class="text-center" data-sortable="true">Keterangan</th>
                            <th class="text-center" data-sortable="true">Kategori</th>
                            <th class="text-center" data-sortable="true">Jenis Pengajuan</th>
                            <th class="text-center" data-sortable="true">Tgl Pengajuan</th>
                            <th class="text-center" data-sortable="true">Status</th>
                            <th class="text-center">Actions</th>
                            </thead>
                            <tbody>
                            @foreach ($data as $usr)
                                <tr>
                                    <td></td>
                                    <td class="text-left">{{ $usr['id'] }}</td>
                                    <td class="text-center">{{ json_decode($usr['detail'],true)['nama'] }}</td>
                                    <td class="text-left">{{ $usr['jenis_pengajuan']   }}</td>
                                     <td class="text-center">{{$usr['kategori'] }}</td>
                                    <td>{{ $usr['created_at'] }}</td>
                                    <td class="text-center text-uppercase">{{ $usr['status'] }}</td>
                                    <td class="td-actions text-center">
                                        <div class="row">
                                            @if(str_before($usr['kategori'],' ')=="Donasi")
                                                @if($usr['status']=="Sudah Dikonfirmasi" || $usr['status']=="Disetujui")
                                                @else
                                                    @if(Auth::user()->tipe=="teller")
                                                        {{--KONFIRMASI UNTUK TRANSAKSI--}}
                                                        <button type="button" id="konfirm" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#confirm{{substr($usr['kategori'],0,3)}}Modal" title="Konfirmasi Pengajuan"
                                                                data-id       = "{{$usr['id']}}"
                                                                data-nama     = "{{ $usr['nama'] }}"
                                                                data-ktp     = "{{ $usr['no_ktp']  }}"
                                                                data-iduser     = "{{ json_decode($usr['detail'],true)['id']}}"
                                                                data-debit     = "{{ json_decode($usr['detail'],true)[strtolower(str_before($usr['kategori'],' '))]}}"
                                                                data-jumlah     = "{{ number_format(json_decode($usr['detail'],true)['jumlah'])}}"
                                                                @if(str_before($usr['kategori'],' ')=="Kredit")
                                                                data-path     = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'])}}"
                                                                data-idtab     = "{{ json_decode($usr['detail'],true)['id_tabungan'] }}"
                                                                data-bank     = "{{ json_decode($usr['detail'],true)['bank']}}"
                                                                data-atasnamabank     = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                                data-banktr     = "{{ json_decode($usr['detail'],true)['daribank']}}"
                                                                data-no_banktr     = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                @elseif(str_before($usr['kategori'],' ')=="Debit")
                                                                data-saldo     = "{{ number_format( isset(json_decode($usr['detail'],true)['saldo'])?json_decode($usr['detail'],true)['saldo']:"0" )}}"
                                                                data-atasnama     = "{{ json_decode($usr['detail'],true)['atasnama']}}"
                                                                data-no_bank   = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                data-idtab     = "{{ json_decode($usr['detail'],true)['id_tabungan'] }}"
                                                                data-bank     = "{{ json_decode($usr['detail'],true)['bank']}}"
                                                                @elseif(str_before($usr['kategori'],' ')=="Pencairan")
                                                                data-iddep     = "{{ json_decode($usr['detail'],true)['id_deposito']}}"
                                                                data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
                                                                data-bank   = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                                data-nobank   = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                data-jenis   = "{{ json_decode($usr['detail'],true)['pencairan'] }}"
                                                                data-kategori   = "{{ $usr['kategori']}}"
                                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                                data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"
                                                                @elseif($usr['kategori']=="Angsuran Pembiayaan")
                                                                data-idtab = "{{ json_decode($usr['detail'],true)['id_pembiayaan'] }}"
                                                                data-namatab = "{{ json_decode($usr['detail'],true)['nama_pembiayaan'] }}"
                                                                data-bankuser = "{{ json_decode($usr['detail'],true)['bank_user'] }}"
                                                                data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                data-atasnama = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                                data-jenis = "{{ json_decode($usr['detail'],true)['angsuran'] }}"
                                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                                data-nisbah       = "{{ number_format(json_decode($usr['detail'],true)['nisbah'],2) }}"
                                                                data-pokok       = "{{ number_format(json_decode($usr['detail'],true)['pokok'],2) }}"
                                                                data-ang       = "{{ number_format(json_decode($usr['detail'],true)['bayar_ang'],2) }}"
                                                                data-mar       = "{{ number_format(json_decode($usr['detail'],true)['bayar_mar'],2) }}"
                                                                data-sisa_ang       = "{{ number_format(json_decode($usr['detail'],true)['sisa_ang'],2) }}"
                                                                data-sisa_mar       = "{{ number_format(json_decode($usr['detail'],true)['sisa_mar'],2) }}"
                                                                data-bank = "{{ json_decode($usr['detail'],true)['bank'] }}"
                                                                data-keterangan = "{{ json_decode($usr['detail'],true)['nama_pembiayaan'] }}"
                                                                data-path       = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                                @elseif(str_before($usr['kategori'],' ')=="Donasi")
                                                                data-bankuser = "{{ json_decode($usr['detail'],true)['daribank'] }}"
                                                                data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                                data-bank = "{{ json_decode($usr['detail'],true)['dari'] }}"
                                                                data-atasnama = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                                data-kegiatan = "{{ json_decode($usr['detail'],true)['kegiatan'] }}"
                                                                data-jenis = "{{ json_decode($usr['detail'],true)['donasi'] }}"
                                                                data-path       = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                                data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                                data-keterangan = "{{ $usr['kategori'] }}"
                                                                {{--data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"--}}
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
                                                    {{--AKTIFASI UNTUK BUKA REKENING BARU AJA--}}
                                                    <button type="button" id="active_" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#active{{substr($usr['kategori'],0,3)}}Modal" title="Aktivasi Rekening"
                                                            data-id         = "{{$usr['id']}}"
                                                            data-namauser   = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                            data-ktp     = "{{ $usr['no_ktp'] }}"

                                                            {{--data-kategori   = "{{ $usr['id_rekening'] }}"--}}
                                                            data-keterangan = "{{ json_decode($usr['detail'],true)['keterangan'] }}"
                                                            data-atasnama   = "{{ json_decode($usr['detail'],true)['atasnama'] }}"
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
                                                    data-bankuser = "{{ json_decode($usr['detail'],true)['daribank'] }}"
                                                    data-no_bank = "{{ json_decode($usr['detail'],true)['no_bank'] }}"
                                                    data-atasnama = "{{ json_decode($usr['detail'],true)['nama'] }}"
                                                    data-kegiatan = "{{ json_decode($usr['detail'],true)['kegiatan'] }}"
                                                    data-jenis = "{{ json_decode($usr['detail'],true)['donasi'] }}"
                                                    data-bank = "{{ json_decode($usr['detail'],true)['dari'] }}"
                                                    data-path       = "{{ url('/storage/public/transfer/'.json_decode($usr['detail'],true)['path_bukti'] )}}"
                                                    data-jumlah       = "{{ number_format(json_decode($usr['detail'],true)['jumlah'],2) }}"
                                                    data-keterangan = "{{ $usr['kategori'] }}">
                                                <i class="fa fa-list-alt"></i>
                                            </button>
                                            @if(str_before($usr['status']," ")=="Disetujui" || str_before($usr['status']," ")=="Sudah")
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
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->
    </div>
    @include('modal.pengajuan')
    @include('modal.user_pembiayaan')
    @include('modal.user_tabungan')
    @include('modal.user_deposito')
    {{--@include('modal.user_deposito')--}}
@endsection

<!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
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
        //  DONASI MAAL
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
            $('#vatasnamaDon').val(button.data('atasnama'));
            if(button.data('kegiatan')==0){
                $('#HideRekDon').hide();
                $('#titleDon').text("Waqaf Nasabah");
            }else{
                $('#HideRekDon').show();
                $('#titleDon').text("Donasi Kegiatan Maal");
                $('#vidRekDon').val(button.data('kegiatan'));
            }
            $('#vjenisDon').val(button.data('jenis'));
            $('#vnobankDon').val(button.data('no_bank'));
            $('#vjumlahDon').val(button.data('jumlah'));
            $('#vbankDon').val(button.data('bankuser'));
            $('#vbank_').val(button.data('bank'));
            $('#vbuktiDon').val(button.data('path'));
            $('#vpicDon')
                .attr('src',  button.data('path'))
        });
        $('#confirmDonModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHideBankDonc');
            var selAr2 = $('#toHideBank2Donc');
            var selAr3 = $('#toHideTabDonc');
            var selAr4 = $('#RekBank2');
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
            $('#iddonasi').val(button.data('id'));
            if(button.data('kegiatan')==0){
                $('#cHideRekDon').hide();
                $('#IDdonasi').val("waqaf");
                $('#ctitleDon').text("Waqaf Nasabah");
            }else{
                $('#cHideRekDon').show();
                $('#ctitleDon').text("Donasi Kegiatan Maal");
                $('#IDdonasi').val("maal");
                $('#cidRekDon').val(button.data('kegiatan'));
            }
            $('#catasnamaDon').val(button.data('atasnama'));
            $('#cjenisDon').val(button.data('jenis'));
            $('#cnobankDon').val(button.data('no_bank'));
            $('#cjumlahDon').val(button.data('jumlah'));
            $('#cbank_').val(button.data('kebank'));
            $('#cbankDon').val(button.data('bankuser'));
            $('#cbuktiDon').val(button.data('path'));
            $('#cpicDon')
                .attr('src',  button.data('path'))
        });
        //  DEPOSITO
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
            console.log(button.data('nisbah'))
            $('#vket_nisbah').val(button.data('nisbah'));
            $('#vrek_tabungan').val(button.data('rek_tab'));
            $('#vketerangan2').val(button.data('keterangan'));
            $('#vjumlahdep').val(button.data('jumlah'));
        });
        $('#activeDepModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#arekDep').val(button.data('kategori'));
            var selAr = $('#toHide3a');
            var selAr2 = $('#toHide4a');
            console.log(button.data('iduser'));
            console.log(button.data('namauser'));
            if(button.data('atasnama')==="Lembaga"){
                $('#aatasnama2').val(2);
                $('#aidhukum2').val(button.data('iduser'));
                $('#anamahukum2').val(button.data('namauser'));
                selAr2.show();
                selAr.hide();
            }else if(button.data('atasnama')==="Pribadi"){
                $('#aatasnama2').val(1);
                $('#aiduser2').val(button.data('ktp'));
                $('#anama2').val(button.data('namauser'));
                selAr2.hide();
                selAr.show();
            }
            $('#aket_nisbah').val(button.data('nisbah'));
            $('#arek_tabungan').val(button.data('rek_tab'));
            $('#ajumlahdep').val(button.data('jumlah'));
            $('#aketerangan2').val(button.data('keterangan'));
            $('#id_act_dep').val(button.data('id'));
        });
        $('#viewPenModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHidePenv');
            var selAr2 = $('#toHidePen2v');
            if(button.data('jenis')=== "Transfer"){
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
        $('#confirmPenModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHidePenc');
            var selAr2 = $('#toHidePen2c');
            if(button.data('jenis')=== "Transfer"){
                $('#toHidePenT').hide();
                $('#tellerpen').attr("required",false);
                $('#toHidePenB').show();
                selAr2.show();
                selAr.show();

            }else if(button.data('jenis')=== "Tunai"){
                $('#toHidePenT').show();
                $('#toHidePenB').hide();
                $('#bankpen').attr("required",false);
                selAr2.hide();
                selAr.hide();
            }
            $('#cjenisPen').val(button.data('jenis'));
            $('#catasnamaPen').val(button.data('atasnama'));
            $('#cnobankPen').val(button.data('nobank'));
            $('#cbankPen').val(button.data('bank'));

            $('#cwidRek').val(button.data('iddep'));
            $('#idPen').val(button.data('id'));
            $('#cwketerangan').val(button.data('keterangan'));
            $('#cwjumlah').val(button.data('jumlah'));
            $('#penjumlah').val(button.data('jumlah'));
        });

        //  PEMBIAYAAN
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
            $('#arekPem').val(button.data('kategori'));
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
            }
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
        $('#confirmAngModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            if(button.data('jenis')=="Tunai"){
                $("#atoHideAng").hide();
                $("#atoHideAngBank").hide();
                $("#atoHideAngBank2").hide();
            }
            else if(button.data('jenis')=="Transfer"){
                $("#atoHideAng").show();
                $("#atoHideAngBank").show();
                $("#atoHideAngBank2").show();
            }

            $("#aidRekA").val(button.data('id') );
            $("#aidTabA").val(button.data('idtab') );
            $("#aangidRek").val(button.data('idtab') );
            $("#ajenisAng").val(button.data('jenis') );
            $("#abankAng").val(button.data('bankuser') );
            $("#abank").val(button.data('bank') );
            $("#abagi_pokok").val(button.data('pokok') );
            $("#abagi_margin").val(button.data('nisbah') );
            $("#abayar_ang").val(button.data('ang') );
            $("#abayar_margin").val(button.data('mar') );
            $("#atagihan_pokok").val(button.data('sisa_ang') )
            $("#atagihan_margin").val(button.data('sisa_mar') );
            $("#aatasnamaAng").val(button.data('atasnama') );
            $("#apicAng").attr("src", button.data('path') );

        });

        // TABUNGAN
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
        $('#activeTabModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#arekAkad').val(button.data('akad'));
            $('#arekTab').val(button.data('kategori'));
            var selAr = $('#toHidea');
            var selAr2 = $('#toHide2a');
            if(button.data('atasnama')==="Lembaga"){
                $('#aatasnama').val(2);
                $('#aidhukum').val(button.data('iduser'));
                $('#anamahukum').val(button.data('namauser'));
                selAr2.show();
                selAr.hide();
            }else if(button.data('atasnama')==="Pribadi"){
                $('#aatasnama').val(1);
                $('#aiduser').val(button.data('ktp'));
                $('#anama').val(button.data('namauser'));
                selAr.show();
                selAr2.hide();
            }
            if(button.data('keterangan')=="Tabungan Awal") {
                $('#Awal').show();
                $('#pokokawal').attr("required",true);
                $('#wajibawal').attr("required",true);
            }
            else {
                $('#pokokawal').attr("required",false);
                $('#wajibawal').attr("required",false);
                $('#Awal').hide();
            }

            $('#id_act_tab').val(button.data('id'));
            $('#aketerangan').val(button.data('keterangan'));
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
            $('#vdebnama').val(button.data('nama'));
            $('#vdebktp').val(button.data('ktp'));

            $('#vRekDeb').val(button.data('idtab'));
            $('#vdebitdeb').val(button.data('debit'));
            $('#vjumlahdeb').val(button.data('jumlah'));
            $('#vbuktideb').val(button.data('path'));
            $('#picDeb')
                .attr('src', button.data('path'))
        });
        $('#activeKreModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHideDeba');
            selAr.hide();
            if(button.data('debit')==="Tunai"){
                selAr.hide();
            }else{
                selAr.show();
            }
            $('#adebnama').val(button.data('nama'));
            $('#adebktp').val(button.data('ktp'));

            $('#aRekDeb').val(button.data('idtab'));
            $('#adebitdeb').val(button.data('debit'));
            $('#ajumlahdeb').val(button.data('jumlah'));
            $('#abankdeb').val(button.data('bank'));
            $('#abuktideb').val(button.data('path'));
            $('#picDeba')
                .attr('src', button.data('path'))
        });
        $('#confirmKreModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHideDebc');
            var selB = $('#toHideDebBankc');
            var selB2 = $('#toHideDebBank2c');
            selAr.hide();
            if(button.data('debit')=="Tunai"){
                selAr.hide();
                selB.hide();
                selB2.hide();
            }else{
                selAr.show();
                selB.show();
                selB2.show();
                $('#cbankdeb').val(button.data('bank'));

                $('#catasnamaDeb').val(button.data('atasnamabank'));
                $('#cbankDeb').val(button.data('banktr'));
                $('#cnobankDeb').val(button.data('no_banktr'));
            }
            console.log(button.data('debit'));

            $('#idconfirm').val(button.data('id'));
            $('#cdebnama').val(button.data('nama'));
            $('#cdebktp').val(button.data('ktp'));
            $('#idtab').val(button.data('idtab'));
            $('#cRekDeb').val(button.data('idtab'));
            $('#cdebitdeb').val(button.data('debit'));
            $('#cjumlahdeb').val(button.data('jumlah'));
            $('#cpicDeb')
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
            $('#vRekKre').val(button.data('idtab'));
            $('#vkredit').val(button.data('debit'));
            $('#vnobankKre').val(button.data('no_bank'));
            $('#vatasnamaKre').val(button.data('atasnama'));
            $('#vjumlahKre').val(button.data('jumlah'));
            $('#vsaldo_kre').val(button.data('saldo'));
            $('#vbankKre').val(button.data('bank'));
        });
        $('#confirmDebModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var selAr = $('#toHideKrec');
            var selAr2 = $('#toHideKre2c');
            var selBank = $('#toHideKreBank');
            var selBank2 = $('#toHideKreTell');
            selAr.hide();
            selAr2.hide();
            if(button.data('debit') === "Tunai"){
                selAr.hide();
                selAr2.hide();
                selBank.hide();selBank2.show();
//                selBank2.attr("required",true);
//                selBank.attr("required",false);
            }
            else if(button.data('debit') === "Transfer"){
                selAr.show();
                selAr2.show();
                selBank2.hide();selBank.show();
            }
            $('#idconfirmKre').val(button.data('id'));
            $('#idtabKre').val(button.data('idtab'));
            $('#cRekKre').val(button.data('idtab'));
            $('#ckredit').val(button.data('debit'));
            $('#cnobankKre').val(button.data('no_bank'));
            $('#catasnamaKre').val(button.data('atasnama'));
            $('#cjumlahKre').val(button.data('jumlah'));
            $('#jumlahCK').val(button.data('jumlah'));
            $('#cbankKre').val(button.data('bank'));
            $('#CK').val(button.data('bank'));
            $('#csaldo_kre').val(button.data('saldo'));
            var saldo =button.data('saldo');
            var jumlah =button.data('jumlah');
            var i = 0,j = 0;
            for(i; i < saldo.length; i++) {
                saldo = saldo.replace(",", "");
            }
            for(j; j < jumlah.length; j++) {
                jumlah = jumlah.replace(",", "");
            }
            if( parseFloat(saldo) < parseFloat(jumlah)){
                $('#warning').text("*Saldo tidak cukup");
            }
            else if( parseFloat(saldo) > parseFloat(jumlah)) {
                $('#submit_kredit').removeAttr("disabled");
            }


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

        function onActiveTab(){
//            swal("Good job!", "You clicked the finish button!", "success");
        }

    </script>

    <script type="text/javascript">
        var $table = $('#bootstrap-table');

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
            var jenis = $('#daribank');
            var jenis2 = $('#dariteller');
            jenis.on('change', function () {
                jenis.attr("required",true);
                jenis2.attr("required",false);
            });

            jenis2.on('change', function () {
                jenis2.attr("required",true);
                jenis.attr("required",false);
            });

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
        $().ready(function(){
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
            //               DONASI
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
            $('#wizardCardDc').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormDc').valid();

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


            //            TABUNGAN
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
            $('#wizardCarda').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForma').valid();

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
            $('#wizardCardDebv').bootstrapWizard({
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
            $('#wizardCardDeba').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormDepa').valid();

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
            $('#wizardCardDebc').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormDepc').valid();

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
            $('#wizardCardKrec').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormKrec').valid();

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

            //            DEPOSITO
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
            $('#wizardCard2a').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardForm2a').valid();

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
            $('#wizardCardDep').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormDep').valid();

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
            $('#wizardCardWc').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormWc').valid();

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
    <script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>


@endsection
@section('footer')
    @include('layouts.footer')
@endsection