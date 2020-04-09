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
                <h4 class="title">Pengajuan Mudharabah Berjangka</h4>

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
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#openDepModal"><i class="fa fa-credit-card"></i> Pembukaan MDB</button>
                    <button class="btn btn-warning rounded right shadow-effect" data-toggle="modal" data-target="#extendDepModal"><i class="fa fa-external-link-alt"></i> Perpanjangan MDB</button>
                    <button class="btn btn-danger rounded right shadow-effect"  data-toggle="modal" data-target="#withdrawDepModal"><i class="fa fa-donate"></i> Pencairan MDB</button>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12" id="ShowTable">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title"><b>Pengajuan Mudharabah Berjangka </b></h4>
                        <p class="category">Daftar Pengajuan Mudharabah Berjangka Anggota</p>
                        <br />
                    </div>

                    <table id="bootstrap-table" class="table">
                        <thead>
                        <th></th>
                        <th data-sortable="true" class="text-left">ID</th>
                        <th data-sortable="true">Jenis Pengajuan</th>
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
                                <td class="text-left">{{ $usr->id }}</td>
                                <td class="text-left">{{ $usr->jenis_pengajuan   }}</td>
                                @if(str_before($usr->kategori,' ')=="Debit" || str_before($usr->kategori,' ')=="Kredit" || str_before($usr->kategori,' ')=="Angsuran")
                                    <td class="text-center">{{$usr->kategori }}</td>
                                @else    <td class="text-center">{{json_decode($usr->detail,true)['keterangan'] }}</td>
                                @endif
                                <td>{{ $usr->created_at }}</td>
                                <td class="text-center text-uppercase">{{ $usr->status }}</td>
                                <td class="text-center text-uppercase">{{ $usr->teller }}</td>
                                {{-- <td>{{ json_decode($usr->detail,true)['perpanjangan_otomatis'] ? "true" : "false" }}</td> --}}
                                <td class="td-actions text-center">
                                    <div class="row">
                                        @if(str_before($usr->kategori,' ')=="Pencairan")
                                            @if($usr->status=="Sudah Dikonfirmasi" || $usr->status=="Disetujui")
                                            @else
                                                {{--KONFIRMASI UNTUK TRANSAKSI--}}
                                                
                                                @if(Auth::user()->tipe=="teller")
                                                    <button type="button" id="konfirm" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#confirm{{substr($usr->kategori,0,3)}}Modal" title="Konfirmasi Pengajuan"
                                                            data-id       = "{{$usr->id}}"
                                                            data-nama     = "{{ $usr->nama }}"
                                                            data-ktp     = "{{ $usr->no_ktp  }}"
                                                            data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                            {{-- data-debit     = "{{ json_decode($usr->detail,true)[strtolower(str_before($usr->kategori,' '))]}}" --}}
                                                            data-iddep     = "{{ json_decode($usr->detail,true)['id_deposito']}}"
                                                            {{-- data-atasnama   = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                            data-bank   = "{{ json_decode($usr->detail,true)['bank'] }}"
                                                            data-nobank   = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                            data-jenis   = "{{ json_decode($usr->detail,true)['pencairan'] }}" --}}
                                                            data-kategori   = "{{ $usr->kategori}}"
                                                            data-jumlah       = "{{ number_format(json_decode($usr->detail,true)['jumlah'],2) }}"
                                                            data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"                       
                                                            data-tabungan_pencairan = {{ json_decode($usr->detail, true)['id_pencairan']}}
                                                        >
                                                        <i class="fa fa-check-square"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editStatusModal" title="Ubah Status Pengajuan"
                                                        data-id      = "{{$usr->id}}"
                                                        data-id_user = "{{$usr->id_user}}"
                                                        data-nama    = "{{$usr->jenis_pengajuan}}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                        @else
                                            @if($usr->status=="Sudah Dikonfirmasi"  || $usr->status=="Disetujui")
                                            @else
                                                {{--AKTIFASI UNTUK BUKA REKENING BARU AJA--}}
                                                <button type="button" id="active_" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#active{{substr($usr->kategori,0,3)}}Modal" title="Aktivasi Rekening"
                                                        data-id         = "{{$usr->id}}"
                                                        data-namauser   = "{{ json_decode($usr->detail,true)['nama'] }}"
                                                        data-ktp     = "{{ $usr->no_ktp }}"
                                                        data-jumlah       = "{{ number_format(json_decode($usr->detail,true)['jumlah']) }}"
                                                        @if($usr->jenis_pengajuan =="Perpanjangan Deposito")
                                                        data-iddep     = "{{ json_decode($usr->detail,true)['id_deposito']}}"
                                                        data-saldo    = "{{ number_format(json_decode($usr->detail,true)['saldo'],2)}}"
                                                        data-jumlah     = "{{ number_format(json_decode($usr->detail,true)['jumlah'])}}"
                                                        data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                        data-atasnama   = "Pribadi"
                                                        data-kategori   = "{{ json_decode($usr->detail,true)['id_rekening_baru'] }}"
                                                        data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                        @elseif(str_before($usr->kategori,' ')=="Pencairan")
                                                        data-iddep     = "{{ json_decode($usr->detail,true)['id_deposito']}}"
                                                        data-tabungan_pencairan     = "{{ json_decode($usr->detail,true)['id_pencairan']}}"
                                                        data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                        @else
                                                        data-bank_bmt_tujuan       = "{{ json_decode($usr->detail,true)['bank_bmt_tujuan'] }}"
                                                        data-kategori   = "{{ $usr->id_rekening }}"
                                                        data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                        data-atasnama   = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                        data-rek_tab       = "{{ isset(json_decode($usr->detail,true)['id_pencairan'])?json_decode($usr->detail,true)['id_pencairan']:"" }}"
                                                        data-kredit = {{ json_decode($usr->detail, true)['kredit']}}
                                                        data-perpanjang_otomatis = "{{ json_decode($usr->detail,true)['perpanjangan_otomatis'] }}"
                                                        @endif
                                                >
                                                    <i class="fa fa-check-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editStatusModal" title="Ubah Status Pengajuan"
                                                        data-id      = "{{$usr->id}}"
                                                        data-id_user = "{{$usr->id_user}}"
                                                        data-nama    = "{{$usr->jenis_pengajuan}}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="row">
                                        {{--VIEW--}}
                                        <button type="button" id="detail" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#view{{substr($usr->kategori,0,3)}}Modal" title="View Detail"
                                                data-id         = "{{$usr->id}}"
                                                data-namauser   = "{{ json_decode($usr->detail,true)['nama'] }}"
                                                data-ktp     = "{{ $usr->no_ktp }}"
                                                @if(str_before($usr->kategori,' ')=="Debit" || str_before($usr->kategori,' ')=="Kredit")
                                                data-nama     = "{{ $usr->nama }}"
                                                data-ktp     = "{{ $usr->no_ktp  }}"
                                                data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                data-debit     = "{{ json_decode($usr->detail,true)[strtolower(str_before($usr->kategori,' '))]}}"
                                                data-jumlah     = "{{ number_format(json_decode($usr->detail,true)['jumlah'])}}"
                                                data-bank     = "{{ json_decode($usr->detail,true)['bank']}}"
                                                    @if(str_before($usr->kategori,' ')=="Debit")
                                                    data-path     = "{{ url('/storage/public/transfer/'.json_decode($usr->detail,true)['path_bukti'])}}"
                                                    data-idtab     = "{{ json_decode($usr->detail,true)['id_tabungan'] }}"
                                                    data-atasnamabank     = "{{ isset(json_decode($usr->detail,true)['atasnama'])?json_decode($usr->detail,true)['atasnama']:'' }}"
                                                    data-banktr     = "{{ isset(json_decode($usr->detail,true)['daribank'])?json_decode($usr->detail,true)['daribank']:'' }}"
                                                    data-no_banktr     = "{{ isset(json_decode($usr->detail,true)['no_bank'])?json_decode($usr->detail,true)['no_bank']:'' }}"
                                                    @elseif(str_before($usr->kategori,' ')=="Kredit")
                                                    data-saldo     = "{{ number_format(json_decode($usr->detail_tabungan,true)['saldo'])}}"
                                                    data-atasnama     = "{{ json_decode($usr->detail,true)['atasnama']}}"
                                                    data-no_bank   = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                    data-idtab     = "{{ $usr->id_tabungan }}"
                                                    @endif
                                                @elseif($usr->kategori =="Perpanjangan Deposito")
                                                data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                data-iddep     = "{{ json_decode($usr->detail,true)['id_deposito']}}"
                                                data-saldo    = "{{ number_format(json_decode($usr->detail,true)['saldo'],2)}}"
                                                data-atasnama   = "Pribadi"
                                                data-kategori   = "{{ json_decode($usr->detail,true)['id_rekening_baru'] }}"
                                                data-jumlah     = "{{ number_format(json_decode($usr->detail,true)['jumlah'])}}"
                                                @elseif(str_before($usr->kategori,' ')=="Pencairan")
                                                data-jumlah     = "{{ number_format(json_decode($usr->detail,true)['jumlah'])}}"
                                                data-iddep     = "{{ json_decode($usr->detail,true)['id_deposito']}}"
                                                {{-- data-atasnama   = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                data-bank   = "{{ json_decode($usr->detail,true)['bank'] }}"
                                                data-nobank   = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                data-jenis   = "{{ json_decode($usr->detail,true)['pencairan'] }}" --}}
                                                data-kategori   = "{{ $usr->kategori}}"
                                                data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                data-tabungan_pencairan = {{ json_decode($usr->detail, true)['id_pencairan']}}
                                                @elseif($usr->kategori=="Angsuran Pembiayaan")
                                                data-idtab = "{{ json_decode($usr->detail,true)['id_pembiayaan'] }}"
                                                data-namatab = "{{ json_decode($usr->detail,true)['nama_pembiayaan'] }}"
                                                data-bankuser = "{{ json_decode($usr->detail,true)['bank_user'] }}"
                                                data-no_bank = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                data-atasnama = "{{ json_decode($usr->detail,true)['nama'] }}"
                                                data-jenis = "{{ json_decode($usr->detail,true)['angsuran'] }}"
                                                data-pokok = "{{ number_format(json_decode($usr->detail,true)['pokok'],2) }}"
                                                data-bank = "{{ json_decode($usr->detail,true)['bank'] }}"
                                                data-keterangan = "{{ json_decode($usr->detail,true)['nama_pembiayaan'] }}"
                                                data-path       = "{{ url('/storage/public/transfer/'.json_decode($usr->detail,true)['path_bukti'] )}}"
                                                data-jumlah       = "{{ number_format(json_decode($usr->detail,true)['jumlah'],2) }}"
                                                @else
                                                data-perpanjang_otomatis = "{{ json_decode($usr->detail,true)['perpanjangan_otomatis'] }}"
                                                data-kategori   = "{{ $usr->id_rekening }}"
                                                data-atasnama   = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                @endif

                                                @if($usr->kategori=="Tabungan" || $usr->kategori=="Tabungan Awal")
                                                data-akad       = "{{ json_decode($usr->detail,true)['akad'] }}"
                                                @elseif($usr->kategori=="Pembiayaan")
                                                data-jumlah       = "{{ number_format(json_decode($usr->detail,true)['jumlah']) }}"
                                                data-jenis       = "{{ json_decode($usr->detail,true)['jenis_Usaha'] }}"
                                                data-usaha       = "{{ json_decode($usr->detail,true)['usaha'] }}"
                                                data-jaminan       = "{{ json_decode($usr->detail,true)['jaminan'] }}"
                                                data-waktu       = "{{ str_before(json_decode($usr->detail,true)['keterangan'],' ')  }}"
                                                data-ketwaktu       = "{{ str_after(json_decode($usr->detail,true)['keterangan'],' ') }}"
                                                data-path       = "{{ url('/storage/public/'.json_decode($usr->detail,true)['path_jaminan']) }}"
                                                @elseif($usr->kategori=="Deposito")
                                                data-jumlah       = "{{ number_format(json_decode($usr->detail,true)['jumlah']) }}"
                                                data-rek_tab       = "{{ isset(json_decode($usr->detail,true)['id_pencairan'])?json_decode($usr->detail,true)['id_pencairan']:"" }}"
                                                data-nisbah       = "{{ json_decode($usr->deposito,true)['nisbah_anggota'] }}"
                                                @endif
                                        >
                                            <i class="fa fa-list-alt"></i>
                                        </button>
                                        @if(str_before($usr['status']," ")=="Disetujui" || str_before($usr['status']," ")=="Sudah")
                                            @if($usr['kategori']=="Deposito")
                                                {{--<form @if(Auth::user()->tipe == "admin") action="{{route('akad.pengajuan_pembiayaan')}}" @elseif(Auth::user()->tipe == "teller") action="{{route('teller.akad.pengajuan_pembiayaan')}}" @endif method="post">--}}
                                                {{ csrf_field() }}
                                                {{--<input type="hidden" name="id" value="{{$usr['id']}}"/>--}}
                                                {{--<a href="{{route('akad.pengajuan_pembiayaan')}}" type="submit"  class="btn btn-social btn-fill" title="Lihat Akad">--}}
                                                <a @if(Auth::user()->tipe == "admin") href="{{route('akad.pengajuan_deposito', [$usr['id']])}}" @elseif(Auth::user()->tipe == "teller") href="{{route('teller.akad.pengajuan_deposito', [$usr['id']])}}" @endif  class="btn btn-social btn-fill" title="Download Akad">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                                {{--</form>--}}
                                            @endif
                                        @else
                                            <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delModal" title="Delete"
                                                    data-id       = "{{$usr->id}}"
                                                    data-nama     = "{{$usr->jenis_pengajuan}}">
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
            </div> <!-- end row -->
        </div>
    </div>
    @include('modal.pengajuan')
    @include('modal.user_deposito')
@endsection

@section('extra_script')


    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on('change', '#jeniscls2', function() {
            $('#penjumlahTeller').val($('#wjumlah').val());
            $('#idDepositoTeller').val($('#widRek').val());
            $('#idPencairanTeller').val($(this).val());
            $('#idUserTeller').val($('#widRek').find(":selected").attr('id-user'));
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
            $('#vket_nisbah').val(button.data('nisbah'));
            $('#vrek_tabungan').val(button.data('rek_tab'));
            $('#vketerangan2').val(button.data('keterangan'));
            $('#vjumlahdep').val(button.data('jumlah'));
            
            if(button.data('perpanjang_otomatis') == true)
            {
                $('#vPerpanjanganOtomatisDeposito').attr('checked', 'checked');
            }
        });
        $('#viewPerModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#vexidRek').val(button.data('iddep'));
            $('#vlama').val(button.data('kategori'));
            $('#vsaldo_per').val(button.data('saldo'));
            $('#vketerangan').val(button.data('keterangan'));
            $('#vextjumlah').val(button.data('jumlah'));
        });
        $('#activePerModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            console.log(button.data('jumlah'))
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#activeexidRek').val(button.data('iddep'));
            $('#id_pengajuan_perpanjangan').val(button.data('id'));
            $('#activeexlama').val(button.data('kategori'));
            $('#activesaldo_per').val(button.data('saldo'));
            $('#vketerangan').val(button.data('keterangan'));
            $('#activeextjumlah').val(button.data('jumlah'));
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
            // $('#vjenisPen').val(button.data('jenis'));
            // $('#vatasnamaPen').val(button.data('atasnama'));
            // $('#vnobankPen').val(button.data('nobank'));
            // $('#vbankPen').val(button.data('bank'));
            $('#vtabunganPencairan').val(button.data('tabungan_pencairan'));

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
            // $('#cjenisPen').val(button.data('jenis'));

            $('#ctabunganPencairan').val(button.data('tabungan_pencairan'));

            $('#catasnamaPen').val(button.data('atasnama'));
            $('#cnobankPen').val(button.data('nobank'));
            $('#cbankPen').val(button.data('bank'));

            $('#cwidRek').val(button.data('iddep'));
            $('#idDeposito').val(button.data('iddep'));
            $('#idPencairan').val(button.data('tabungan_pencairan'));
            $('#idUser').val(button.data('iduser'));

            $('#idPen').val(button.data('id'));
            $('#cwketerangan').val(button.data('keterangan'));
            $('#cwjumlah').val(button.data('jumlah'));
            $('#penjumlah').val(button.data('jumlah'));
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
            if(button.data('kredit') == "Tunai")
            {   
                $('.bank_tujuan').hide();
                $('.jumlah_uang').removeClass('col-md-5');
                $('.jumlah_uang').addClass('col-md-10 col-md-offset-1');
            }
            $('#aket_nisbah').val(button.data('nisbah'));
            $('#bank_tujuan').val(button.data('bank_bmt_tujuan'));
            $('#arek_tabungan').val(button.data('rek_tab'));
            $('#ajumlahdep').val(button.data('jumlah'));
            $('#aketerangan2').val(button.data('keterangan'));
            $('#id_act_dep').val(button.data('id'));

            if(button.data('perpanjang_otomatis') == true)
            {
                $('#activePerpanjanganOtomatisDeposito').attr('checked', 'checked');
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
                "order": [],
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
//            $table.bootstrapTable({
//                toolbar: ".toolbar",
//                clickToSelect: true,
//                showRefresh: true,
//                search: true,
//                showToggle: true,
//                showColumns: true,
//                pagination: true,
//                searchAlign: 'left',
//                pageSize: 8,
//                clickToSelect: false,
//                pageList: [8,10,25,50,100],
//
//                formatShowingRows: function(pageFrom, pageTo, totalRows){
//                    //do nothing here, we don't want to show the text "showing x of y from..."
//                },
//                formatRecordsPerPage: function(pageNumber){
//                    return pageNumber + " rows visible";
//                },
//                icons: {
//                    refresh: 'fa fa-refresh',
//                    toggle: 'fa fa-th-list',
//                    columns: 'fa fa-columns',
//                    detailOpen: 'fa fa-plus-circle',
//                    detailClose: 'fa fa-minus-circle'
//                }
//            });
//
//            //activate the tooltips after the data table is initialized
//            $('[rel="tooltip"]').tooltip();
//
//            $(window).resize(function () {
//                $table.bootstrapTable('resetView');
//            });


        });

    </script>
    <script type="text/javascript">
        $().ready(function(){

            var selNisbah = $('#rekDep');
            var id = 0;
            var nisbah =0;
            selNisbah.on('change', function () {
                id = parseFloat(selNisbah.val().split(' ')[0]);
                nisbah = parseFloat(selNisbah.val().split(' ')[1]);
                $('#deposito_id').val(id);
                $('#ket_nisbah').val(nisbah);
            });

            var selKr = $('#toHidePen');
            var selKr2 = $('#toHidePen2');

            var selKrc = $('#toHidePenBC');
            var selKr2c = $('#toHidePenTC');

            var jenisK = $('#jenisPen');
            jenisK.val(0);
            selKr.hide();
            selKr2.hide();
            selKrc.hide();
            var aPen  =$('#atasnamaPen');
            var nbPen  =$('#nobankPen');
            var bPen =$('#bankPen');
            var tPenc =$('#tellerpenc');
            var bPenc =$('#bankpenc');
            bPenc.attr("required",false);
            jenisK.on('change', function () {
                if(jenisK .val() == 1) {
                    selKr.show();
                    selKr2.show();
                    selKrc.show();
                    selKr2c.hide();
                    selKr2c.val("");
                    tPenc.val("");
                    bPenc.attr("required",true);
                    tPenc.attr("required",false);
                    aPen.attr("required",true);
                    bPen.attr("required",true);
                    nbPen.attr("required",true);
                }
                else if (jenisK .val() == 0) {
                    aPen.attr("required",false);
                    bPen.attr("required",false);
                    nbPen.attr("required",false);
                    bPenc.attr("required",false);
                    tPenc.attr("required",true);
                    bPenc.val("");
                    selKr.hide();
                    selKrc.val("");
                    selKrc.hide();
                    selKr2c.show();
                    selKr2.hide();
                }
            });

            var selTip3 = $('#widRek');
            selTip3.on('change', function () {
                // var id = $('#idRekWD').val(selTip3.find(":selected").text().split(']')[0]);
                var id = $(this).val();
                $('#idRekWD').val(id);
                
                $('#wjumlah').val(selTip3.find(":selected").attr('saldo'));
                $('#saldo_teller').val(selTip3.find(":selected").attr('saldo'));
            });

            var selTip = $('#exidRek');
            selTip.on('change', function () {
                var id = $('#idRekSP').val(selTip.find(":selected").text().split(']')[0]);
                id = id.val().split('[')[1];
                $('#idRekSP').val(id);
                $('#extjumlah').val(selTip.val())
            });

            $('#saldo_per').on('keyup keydown', function(e){
                if ($(this).val() > parseInt(selTip.val())
                    && e.keyCode != 46
                    && e.keyCode != 8
                ) {
                    e.preventDefault();
                    $(this).val(parseInt(selTip.val()));
                }
            });

            var selAr3 = $('#toHide3');
            var selAr4 = $('#toHide4');
            var selTip2 = $('#atasnama2');
            var selHk2 = $('#idhukum2');
            var selHkn2 = $('#namahukum2');
            selAr3.hide();
            selAr4.hide();

            var selTip22 = $('#nasabah2');

            selTip22.on('change', function () {
                $('#namauser2').val($('#nasabah2').find(":selected").text())
                $('#id_user2').val($('#nasabah2').val())
                console.log($('#id_user2').val());
                console.log($('#namauser2').val());

            });

            selTip2.on('change', function () {
                    if (selTip2.val() == 1) {
                        selAr3.show();
                        selAr4.hide();
                        selHk2.val("null");
                        selHkn2.val("null");
                    }
                    else {
                        selAr4.show();
                        selAr3.hide();
                        selHk2.val("");
                        selHkn2.val("");
                    }
            });

            var allOptions = $('#rek_tabungan option')
            $('#nasabah2').change(function () {
                $('#rek_tabungan option').remove()
                var classN = $('#nasabah2 option:selected').prop('class');
                var opts = allOptions.filter('.' + classN);
                $.each(opts, function (i, j) {
                    $(j).appendTo('#rek_tabungan');
                });
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
            $("#nasabah2").select2({
                dropdownParent: $("#openDepModal")
            });
            $("#rek_tabungan").select2({
                dropdownParent: $("#openDepModal")
            });
            $("#exidRek").select2({
                dropdownParent: $("#extendDepModal")
            });
            $("#widRek").select2({
                dropdownParent: $("#withdrawDepModal")
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