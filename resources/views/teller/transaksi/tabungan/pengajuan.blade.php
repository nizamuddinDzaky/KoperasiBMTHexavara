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
                <h4 class="title">Pengajuan Tabungan Nasabah</h4>

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
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#kreditTabModal"><i class="fa fa-credit-card"></i> Setor Tunai</button>
                    <button class="btn btn-warning rounded right shadow-effect" data-toggle="modal" data-target="#debitTabModal"><i class="fa fa-sign-out-alt"></i> Tarik Tunai</button>
                    <button class="btn btn-success rounded right shadow-effect" data-toggle="modal" data-target="#openTabModal"><i class="fa fa-archive"></i> Buka Tabungan</button>
                    <button class="btn btn-danger rounded right shadow-effect" data-toggle="modal" data-target="#tutupTabModal"><i class="fa fa-close"></i> Tutup Tabungan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12" id="ShowTable">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title"><b>Pengajuan Tabungan</b> </h4>
                        <p class="category">Daftar Pengajuan Tabungan Nasabah</p>
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
                                @if(str_before($usr->kategori,' ')=="Debit" || str_before($usr->kategori,' ')=="Kredit")
                                    <td class="text-center">{{json_decode($usr->detail,true)['nama']  }}</td>
                                @else    <td class="text-center">{{json_decode($usr->detail,true)['nama'] }}</td>
                                @endif
                                <td>{{ $usr->created_at }}</td>
                                <td class="text-center text-uppercase">{{ $usr->status }}</td>
                                <td class="text-center text-uppercase">{{ $usr->teller }}</td>

                                <td class="td-actions text-center">
                                    <div class="row">
                                        @if(str_before($usr->kategori,' ')=="Debit" || str_before($usr->kategori,' ')=="Kredit")
                                            @if($usr->status=="Sudah Dikonfirmasi" || $usr->status=="Disetujui")
                                            @else
                                                {{--KONFIRMASI--}}
                                            @if(Auth::user()->tipe=="teller")
                                                <button type="button" id="konfirm" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#confirm{{substr($usr->kategori,0,3)}}Modal" title="Konfirmasi Pengajuan"
                                                        data-id       = "{{$usr->id}}"
                                                        data-nama     = "{{ $usr->nama }}"
                                                        data-ktp     = "{{ $usr->no_ktp  }}"
                                                        data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                        data-debit     = "{{ json_decode($usr->detail,true)[strtolower(str_before($usr->kategori,' '))]}}"
                                                        data-jumlah     = "{{ number_format(json_decode($usr->detail,true)['jumlah'])}}"
                                                        @if(str_before($usr->kategori,' ')=="Kredit")
                                                        data-path     = "{{ url('/storage/public/transfer/'.json_decode($usr->detail,true)['path_bukti'])}}"
                                                        data-idtab     = "{{ json_decode($usr->detail,true)['id_tabungan'] }}"
                                                        data-bank     = "{{ json_decode($usr->detail,true)['bank']}}"
                                                        data-atasnamabank     = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                        data-banktr     = "{{ json_decode($usr->detail,true)['daribank']}}"
                                                        data-no_banktr     = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                        @elseif(str_before($usr->kategori,' ')=="Debit")
                                                        data-saldo     = "{{ number_format(isset(json_decode($usr->detail_tabungan,true)['saldo'])?json_decode($usr->detail_tabungan,true)['saldo']:0)}}"
                                                        data-atasnama     = "{{ json_decode($usr->detail,true)['atasnama']}}"
                                                        data-no_bank   = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                        data-idtab     = "{{ $usr->id_tabungan }}"
                                                        data-bank     = "{{ json_decode($usr->detail,true)['bank']}}"
                                                        @endif
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
                                                <button type="button" id="active_" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#active{{substr($usr->kategori,0,3)}}Modal" title="Aktivasi Rekening"
                                                        data-id         = "{{$usr->id}}"
                                                        data-namauser   = "{{ json_decode($usr->detail,true)['nama'] }}"
                                                        data-ktp     = "{{ $usr->no_ktp }}"

                                                        @if(str_before($usr->kategori,' ')=="Debit" || str_before($usr->kategori,' ')=="Kredit")
                                                        data-nama     = "{{ $usr->nama }}"
                                                        data-ktp     = "{{ $usr->no_ktp  }}"
                                                        data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                        data-debit     = "{{ json_decode($usr->detail,true)[strtolower(str_before($usr->kategori,' '))]}}"
                                                        data-jumlah     = "{{ number_format(json_decode($usr->detail,true)['jumlah'])}}"
                                                        @if(str_before($usr->kategori,' ')=="Kredit")
                                                        data-path     = "{{ url('/storage/public/transfer/'.json_decode($usr->detail,true)['path_bukti'])}}"
                                                        data-idtab     = "{{ json_decode($usr->detail,true)['id_tabungan'] }}"
                                                        @elseif(str_before($usr->kategori,' ')=="Debit")
                                                        data-atasnama     = "{{ json_decode($usr->detail,true)['bank']}}"
                                                        data-no_bank   = "{{ json_decode($usr->detail,true)['id_tabungan'] }}"
                                                        data-idtab     = "{{$usr->id_tabungan}}"
                                                        @endif
                                                        data-bank     = "{{ json_decode($usr->detail,true)['bank']}}"
                                                        @elseif($usr->jenis_pengajuan =="Perpanjangan Deposito")
                                                        data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                        data-atasnama   = "Pribadi"
                                                        data-kategori   = "{{ json_decode($usr->detail,true)['id_rekening_baru'] }}"
                                                        data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                        @else
                                                        data-kategori   = "{{ $usr->id_rekening }}"
                                                        data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                        data-atasnama   = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                        @endif

                                                        @if($usr->kategori=="Tabungan" || $usr->kategori=="Tabungan Awal")
                                                        data-akad       = "{{ json_decode($usr->detail,true)['akad'] }}"
                                                        data-akad       = "{{ json_decode($usr->detail,true)['keterangan'] }}"
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
                                                @if(str_before($usr->kategori,' ')=="Kredit")
                                                data-path     = "{{ url('/storage/public/transfer/'.json_decode($usr->detail,true)['path_bukti'])}}"
                                                data-idtab     = "{{ json_decode($usr->detail,true)['id_tabungan'] }}"
                                                data-atasnamabank     = "{{ isset(json_decode($usr->detail,true)['atasnama'])?json_decode($usr->detail,true)['atasnama']:'' }}"
                                                data-banktr     = "{{ isset(json_decode($usr->detail,true)['daribank'])?json_decode($usr->detail,true)['daribank']:'' }}"
                                                data-no_banktr     = "{{ isset(json_decode($usr->detail,true)['no_bank'])?json_decode($usr->detail,true)['no_bank']:'' }}"
                                                @elseif(str_before($usr->kategori,' ')=="Debit")
                                                data-saldo     = "{{ number_format(isset(json_decode($usr->detail_tabungan,true)['saldo'])?json_decode($usr->detail_tabungan,true)['saldo']:0)}}"
                                                data-atasnama     = "{{ json_decode($usr->detail,true)['atasnama']}}"
                                                data-no_bank   = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                data-idtab     = "{{ $usr->id_tabungan }}"
                                                @endif
                                                data-bank     = "{{ json_decode($usr->detail,true)['bank']}}"
                                                @elseif($usr->kategori =="Perpanjangan Deposito")
                                                data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                data-atasnama   = "Pribadi"
                                                data-kategori   = "{{ json_decode($usr->detail,true)['id_rekening_baru'] }}"
                                                data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                @else
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
                                                @endif
                                        >
                                            <i class="fa fa-list-alt"></i>
                                        </button>
                                        @if(str_before($usr->status," ")=="Disetujui" || str_before($usr->status," ")=="Sudah")
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
            </div>
        </div>
    </div>
    @include('modal.pengajuan')
    @include('modal.user_tabungan')
@endsection

@section('extra_script')


    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
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
        $('#activeDebModal').on('show.bs.modal', function (event) {
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
        $('.modal').on('show.bs.modal', function (event) {

            $('.currency').maskMoney({
                allowZero: true,
                precision: 0,
                thousands: "."
            });

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
                $('#daribank').attr("required",false);
                $('#dariteller').attr("required",true);
                console.log(button.data('idtab'));
            }

            else if(button.data('debit') === "Transfer"){
                selAr.show();
                selAr2.show();
                selBank2.hide();selBank.show();
                $('#dariteller').attr("required",false);
                $('#daribank').attr("required",true);
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
                        'pdfHtml5' ]
                }
            });
        });

    </script>
    <script type="text/javascript">
        $().ready(function(){

            var selWjb = $('#nasabah_wajib');
            var Wjb = $('#toHideWB');

            selWjb.on('change', function () {
                var id  = (selWjb.val().split(' ')[0]);
                var wajib  = (selWjb.val().split(' ')[1]);
                var nama  = (selWjb.val().split(' ')[2]);
                $('#idRekW').val(id);
                $('#NamaW').val(nama);
                $('#total_wajib').val(wajib);
                console.log($('#idRekW').val());
                console.log($('#NamaW').val());
                console.log($('#total_wajib').val());
            });


            var selW = $('#toHideW');
            var selW2 = $('#toHideW2');
            var jenisW = $('#jwajib');
            jenisW.val(0);
            selW.hide();
            selW2.hide();
            var aW  =$('#atasnamaW');
            var nbW  =$('#nobankW');
            var bW =$('#bankW');
            Wjb.hide()
            jenisW.on('change', function () {
                if(jenisW .val() == 1) {
                    selW.show()
                    Wjb.show()
                    selW2.show()
                    $('#bankrek').attr("required",true);
                    $('#bankW').attr("required",true);
                    $('#buktiW').attr("required",true);
                    aW.attr("required",true);
                    bW.attr("required",true);
                    nbW.attr("required",true);

                }
                else if (jenisW .val() == 0) {
                    $('#bankrek').attr("required",false);
                    $('#bankW').attr("required",false);
                    $('#buktiW').attr("required",false);
                    aW.attr("required",false);
                    bW.attr("required",false);
                    nbW.attr("required",false);
                    selW.hide();
                    Wjb.hide();
                    selW2.hide();
                }
            });

            $('#saldo_kre').on('keyup keydown', function(e){
                if ($(this).val() > parseInt(selRek.val())
                    && e.keyCode != 46
                    && e.keyCode != 8
                ) {
                    e.preventDefault();
                    $(this).val(parseInt(selRek.val()));
                }
            });

            var selRekC = $('#idRekC');
            selRekC.on('change', function () {
                var id = $('#idRekCls').val(selRekC.find(":selected").text().split(']')[0]);
                id = id.val().split('[')[1];
                $('#idRekCls').val(id);
                $('#jumlahcls').val(selRekC.val())
            });

            var selRek = $('#kreidRek');
            selRek.on('change', function () {
                var id = $('#idRekKR').val(selRek.find(":selected").text().split(']')[0]);
                id = id.val().split('[')[1];
                $('#idRekKR').val(id);
                $('#krejumlah').val(selRek.val())
            });
            $('#saldo_kre').on('keyup keydown', function(e){
                if ($(this).val() > parseInt(selRek.val())
                    && e.keyCode != 46
                    && e.keyCode != 8
                ) {
                    e.preventDefault();
                    $(this).val(parseInt(selRek.val()));
                }
            });

            var selArc = $('#toHidecls');
            var selArBc =$('#toHideBankcls');
            var selArB2c =$('#toHideBank2cls');
            var atasnamac =$('#atasnamacls');
            var bankc =$('#bankCls');
            var kebankc =$('#bankcls');
            var nobankc =$('#nobankcls');

            var jenisc = $('#jeniscls');
            var buktic = $('#bukticls');
            selArc.hide(); selArBc.hide(); selArB2c.hide();


            jenisc.on('change', function () {
                if(jenisc.val() == 1) {
                    buktic.attr("required",true);
                    bankc.attr("required",true);
                    atasnamac.attr("required",true);
                    nobankc.attr("required",true);
                    kebankc.attr("required",true);
                    selArc.show();
                    selArBc.show(); selArB2c.show()
                }
                else if (jenisc.val() == 0) {
                    kebankc.attr("required",false);
                    bankc.attr("required",false);
                    atasnamac.attr("required",false);
                    nobankc.attr("required",false);
                    buktic.attr("required",false);
                    selArc.hide();
                    selArBc.hide();selArB2c.hide();
                }
            });

            var selAr = $('#toHideDeb');
            var selArB =$('#toHideDebBank');
            var selArB2 =$('#toHideDebBank2');
            var atasnama =$('#atasnamaDeb');
            var bank =$('#bankDeb');
            var kebank =$('#bank');
            var nobank =$('#nobankDeb');

            var jenis = $('#debit');
            var bukti = $('#bukti');
            selAr.hide(); selArB.hide(); selArB2.hide();
            jenis.on('change', function () {
                if(jenis .val() == 1) {
                    bukti.attr("required",true);
                    bank.attr("required",true);
                    atasnama.attr("required",true);
                    nobank.attr("required",true);
                    kebank.attr("required",true);
                    selAr.show();
                    selArB.show(); selArB2.show()
                }
                else if (jenis .val() == 0) {
                    kebank.attr("required",false);
                    bank.attr("required",false);
                    atasnama.attr("required",false);
                    nobank.attr("required",false);
                    bukti.attr("required",false);
                    selAr.hide();
                    selArB.hide();selArB2.hide();
                }
            });

            var selKr = $('#toHideKre');
            var selKr2 = $('#toHideKre2');
            var jenisK = $('#kredit');
            jenisK.val(0);
            selKr.hide();
            selKr2.hide();
            var aKre  =$('#atasnamaKre');
            var nbKre  =$('#nobankKre');
            var bKre =$('#bankKre');
            jenisK.on('change', function () {
                if(jenisK .val() == 1) {
                    selKr.show()
                    selKr2.show()
                    aKre.attr("required",true);
                    bKre.attr("required",true);
                    nbKre.attr("required",true);
                }
                else if (jenisK .val() == 0) {
                    aKre.attr("required",false);
                    bKre.attr("required",false);
                    nbKre.attr("required",false);
                    selKr.hide();
                    selKr2.hide();
                }
            });

            var selHk = $('#idhukum');
            var selHkn = $('#namahukum');
            var selAr_ = $('#toHide');
            var selAr2_ = $('#toHide2');
            var selTip = $('#atasnama');
            selAr_.hide();
            selTip.on('change', function () {
                if (selTip.val() == 1) {
                    selAr_.show();selAr2_.hide();
                    selHk.val("null");
                    selHkn.val("null");
                    $('#namauser').val($('#nasabah').find(":selected").text().split(/ (.+)/)[1])
                    $('#id_user').val($('#nasabah').val())
                    console.log($('#id_user').val());
                    console.log($('#namauser').val());
                    console.log($('#nasabah').val());
              }
                else if (selTip.val() == 2) {
                    selAr2_.show();selAr_.hide();
                    selHk.val("");
                    selHkn.val("");
                }
            });
            $('#toHideKreBankA').hide();
            $('#daribank2').attr('required',false);

            $('#kredit').on('change',function(){
                if($('#kredit').val() == 0){
                    $('#toHideKreTellA').show();
                    $('#toHideKreBankA').hide();
                    $('#daribank2').attr("required",false);
                }
                else if($('#kredit').val() == 1){
                    $('#toHideKreTellA').hide();
                    $('#dariteller2').attr("required",false);
                    $('#toHideKreBankA').show();
                }
            });

            $("#idRekC").select2({
                dropdownParent: $("#tutupTabModal")
            });
            $("#idRek").select2({
                dropdownParent: $("#kreditTabModal")
            });
            $("#kreidRek").select2({
                dropdownParent: $("#debitTabModal")
            });
            $("#nasabah").select2({
                dropdownParent: $("#openTabModal")
            });
            $("#nasabah_wajib").select2({
                dropdownParent: $("#simpWajibModal")
            });
            $("#rekAkad").select2({
                dropdownParent: $("#openTabModal")
            });
            $("#rekDep").select2({
                dropdownParent: $("#openDepModal")
            });
            $("#rekPem").select2({
                dropdownParent: $("#openPemModal")
            });
            $("#rekTab").select2({
                dropdownParent: $("#openTabModal")
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(auto)
                };


                reader.readAsDataURL(input.files[0]);
            }
        }
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#picw')
                        .attr('src', e.target.result)
                        .width(200)
                        .height(auto)
                };


                reader.readAsDataURL(input.files[0]);
            }
        }
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

            $('#wizardCardClose').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormClose').valid();

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
            $('#wizardCardDeb').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormDeb').valid();

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
            $('#wizardCardKre').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormKre').valid();

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