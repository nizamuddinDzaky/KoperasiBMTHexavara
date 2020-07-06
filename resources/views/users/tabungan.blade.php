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
    @if(Request::is('anggota/menu/tabungan'))
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Tabungan Anggota</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>

                <div class="button-group right">
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#debitTabModal"><i class="fa fa-credit-card"></i> Setor Tunai</button>
                    <button class="btn btn-success rounded right shadow-effect" data-toggle="modal" data-target="#kreditTabModal"><i class="fa fa-sign-out-alt"></i> Tarik Tunai</button>
                    <button class="btn btn-danger rounded right shadow-effect" data-toggle="modal" data-target="#transferTabModal"><i class="fa fa-sign-out-alt"></i> Transfer Antar Anggota</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12" id="ShowTable">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Rekening Tabungan </h4>
                        <p class="category">Berikut adalah daftar rekening tabungan anda</p>
                        <br />
                    </div>

                    <table class="table bootstrap-table">
                        <thead>
                        <th></th>
                        <th class="text-center" data-sortable="true">ID</th>
                        <th class="text-center" data-sortable="true">Jenis Tabungan</th>
                        <th class="text-center" data-sortable="true">Tgl Pembuatan</th>
                        <th class="text-center" data-sortable="true">Saldo</th>
                        <th class="text-center" data-sortable="true">Status</th>
                        <th class="text-center">Actions</th>
                        </thead>
                        <tbody>
                        @foreach ($data as $usr)
                            <tr>
                                <td></td>
                                <td class="text-left">{{ $usr->id_tabungan }}</td>
                                <td class="text-left">{{ $usr->jenis_tabungan   }}</td>
                                <td class="text-left">{{ $usr->created_at }}</td>
                                <td class="text-left">Rp{{" ". number_format(json_decode($usr->detail,true)['saldo'],2) }}</td>
                                <td class="text-center text-uppercase">{{ $usr->status }}</td>
                                <td class="td-actions text-center">
                                    <form  method="post" action="{{route('anggota.detail_tabungan')}}">
                                        <input type="hidden" id="id_status" name="id_" value="{{$usr->id}}">
                                        {{csrf_field()}}
                                        <button type="submit" class="btn btn-social @if($usr->status=="blocked")btn-danger @else btn-info @endif  btn-fill" title="Detail"
                                                data-id      = "{{$usr->no_ktp}}"
                                                data-nama    = "{{$usr->nama}}" name="id">
                                            @if($usr->status=="blocked")
                                            <i class="fa fa-close"></i>
                                            @elseif($usr->status=="active")
                                            <i class="fa fa-clipboard-list"></i>
                                            @endif
                                        </button>
                                        {{--<button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delUsrModal" title="Delete"--}}
                                                {{--data-id         = "{{$usr->no_ktp}}"--}}
                                                {{--data-nama       = "{{$usr->nama}}">--}}
                                            {{--<i class="fa fa-remove"></i>--}}
                                        {{--</button>--}}
                                    </form>
                                    </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Riwayat Pengajuan </h4>
                        <p class="category">Berikut adalah riwayat pengajuan tabungan anda</p>
                        <br />
                    </div>
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                        <span>
                                {{--<button id=expandTable class="btn btn-social btn-success btn-fill" style="margin-right: 0.3em"> <i class="fa fa-eye"></i></button>--}}
                        </span>
                    </div>

                    <table class="table bootstrap-table">
                        <thead>
                        <th></th>
                        <th class="text-center" data-sortable="true" >ID Pengajuan</th>
                        <th class="text-center" data-sortable="true">Jenis Pengajuan</th>
                        <th class="text-center" data-sortable="true">Keterangan</th>
                        <th class="text-center" data-sortable="true">Tgl Pengajuan</th>
                        <th class="text-center" data-sortable="true">Status</th>
                        <th class="text-center">Actions</th>
                        <th></th>
                        </thead>
                        <tbody>
                        @foreach ($data2 as $usr)
                            <tr>
                                <td></td>
                                <td class="text-center">{{ $usr->id }}</td>
                                <td class="text-left">{{ $usr->jenis_pengajuan   }}</td>
                                @if(str_before($usr->kategori,' ')=="Debit" || str_before($usr->kategori,' ')=="Kredit")
                                    <td class="text-left">{{json_decode($usr->detail,true)['nama_tabungan']." [ID : ".json_decode($usr->detail,true)['id_tabungan']."]"  }}</td>
                                @else
                                    <td class="text-left">{{ json_decode($usr->detail,true)['keterangan'] }}</td>
                                @endif
                                <td class="text-left">{{ $usr->created_at }}</td>
                                <td class="text-left">{{ $usr->status }}</td>
                                
                                @if($usr->kategori == "Transfer Antar Anggota")
                                <td class="td-actions text-center">
                                    <div class="row">
                                        <button type="button" id="detail" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#view{{substr($usr->kategori,0,3)}}Modal" title="View Detail"
                                            data-id         = "{{ $usr->id }}"
                                            data-id_penerima   = "{{ json_decode($usr->detail)->user_penerima }}"
                                            data-id_pengirim   = "{{ json_decode($usr->detail)->user_pengirim }}"
                                            data-tabungan_penerima   = "{{ json_decode($usr->detail)->tabungan_penerima }}"
                                            data-tabungan_pengirim   = "{{ json_decode($usr->detail)->tabungan_pengirim }}"
                                            data-jumlah   = "{{ json_decode($usr->detail)->nominal }}"
                                            data-keterangan   = "{{ json_decode($usr->detail)->keterangan }}"
                                        >
                                            <i class="fa fa-list-alt"></i>
                                        </button>
                                    </div>
                                </td>
                                @else
                                <td class="td-actions text-center">
                                    <div class="row">
                                        <button type="button" id="detail" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#view{{substr($usr->kategori,0,3)}}Modal" title="View Detail"
                                                data-id         = "{{$usr->id}}"
                                                data-namauser   = "{{ json_decode($usr->detail,true)['nama'] }}"
                                                data-ktp     = "{{ $usr->no_ktp }}"
                                
                                                @if(str_before($usr->kategori,' ')=="Debit" || str_before($usr->kategori,' ')=="Kredit")
                                                data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                data-debit     = "{{ json_decode($usr->detail,true)[strtolower(str_before($usr->kategori,' '))] }}"
                                                data-jumlah     = "{{ number_format(json_decode($usr->detail,true)['jumlah'],2)}}"
                                                    @if(str_before($usr->kategori,' ')=="Kredit")
                                                    data-path     = "{{ url('/storage/public/transfer/'.json_decode($usr->detail,true)['path_bukti'])}}"
                                                    data-idtab     = "{{ json_decode($usr->detail,true)['id_tabungan'] }}"
                                                    @elseif(str_before($usr->kategori,' ')=="Debit")
                                                    data-atasnama     = "{{ json_decode($usr->detail,true)['atasnama']}}"
                                                    data-saldo     = "{{ json_decode($usr->detail_tabungan,true)['saldo']}}"
                                                    data-no_bank   = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                    data-idtab     = "{{ json_decode($usr->detail,true)['id_tabungan'] }}"
                                                    @endif
                                                data-bank     = "{{ json_decode($usr->detail,true)['daribank']}}"
                                                @else
                                                data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                data-atasnama   = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                @endif
                                
                                                @if($usr->kategori=="Tabungan Awal" || str_before($usr->kategori,' ') == "Kredit" || str_before($usr->kategori,' ') == "Debit" )
                                                data-kategori   = "tabungan"
                                                @else
                                                data-kategori   = "{{ json_decode($usr->detail,true)[strtolower($usr->kategori)] }}"
                                                @endif
                                
                                                @if($usr->kategori=="Tabungan" || $usr->kategori=="Tabungan Awal")
                                                data-akad       = "{{ json_decode($usr->detail,true)['akad'] }}"
                                                @endif
                                        >
                                            <i class="fa fa-list-alt"></i>
                                        </button>
                                        {{-- @if($usr->status =="Disetujui" || $usr->status =="Sudah Dikonfirmasi")
                                        @else
                                        <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delModal" title="Delete"
                                                data-id       = "{{$usr->id}}"
                                                data-nama     = "{{$usr->jenis_pengajuan}}">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                        @endif --}}
                                    </div>
                                </td>
                                @endif

                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
    </div>
    @include('modal.pengajuan')
    @include('modal.user_tabungan')
@endsection
    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <script src="{{ asset('bmtmudathemes/assets/js/modal/transfer_antar_tabungan.js') }}"></script>

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
            $('#vdebnama').val(button.data('namauser'));
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
        $('#confirmDebModal').on('show.bs.modal', function (event) {
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
            $('#cjumlahdeb').val(button.data('saldo'));
            $('#cpicDeb')
                .attr('src', button.data('path'))
        });
        $('#viewDebModal').on('show.bs.modal', function (event) {
            var formatter = new Intl.NumberFormat();
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
            $('#vjumlahKre').val(button.data('saldo'));
            console.log(button.data('saldo'));
            $('#vsaldo_kre').val(formatter.format(button.data('saldo')));
            $('#vbankKre').val(button.data('bank'));
        });
        $('#confirmKreModal').on('show.bs.modal', function (event) {
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
        $('#simpWajibModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#toHideNasabah2').hide();
            $('#nasabah_wajib').attr("required",false);

            $('#toDelete').text(nama + "?");
        });

    </script>
    
    <script type="text/javascript">
        $().ready(function(){
            var formatter = new Intl.NumberFormat();
            var selRek = $('#kreidRek');
            selRek.on('change', function () {
                var id = $('#idRekKR').val(selRek.find(":selected").text().split(']')[0]);
                id = id.val().split('[')[1];
                $('#idRekKR').val(id);
                $('#krejumlah').val(formatter.format(selRek.val()))
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

            var selAr = $('#toHideDeb');
            var selArB =$('#toHideDebBank');
            var selArB2 =$('#toHideDebBank2');
            var atasnama =$('#atasnamaDeb');
            var bank =$('#bankDeb');
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
            });

            // var selW = $('#toHideW');
            // var selW2 = $('#toHideW2');
            // var selWB =$('#toHideWB');
            // var jenisW = $('#jwajib');
            // var bankW =$('#bankrek');
            // jenisW.val(0);
            // selW.hide();
            // selW2.hide();
            // var aW  =$('#atasnamaW');
            // var nbW  =$('#nobankW');
            // var bW =$('#bankW');
            // jenisW.on('change', function () {
            //     if(jenisW .val() == 1) {
            //         selW.show();selW2.show();selWB.show();
            //         bankW.attr("required",true);
            //         aW.attr("required",true);
            //         bW.attr("required",true);
            //         nbW.attr("required",true);
            //     }
            //     else if (jenisW .val() == 0) {
            //         $('#bankrek').val(0);
            //         bankW.attr("required",false);
            //         aW.attr("required",false);
            //         bW.attr("required",false);
            //         nbW.attr("required",false);
            //         selW.hide();
            //         selW2.hide();selWB.hide();
            //     }
            // });
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
            var tohidenasabah = $('#toHideNasabah');
            selAr_.hide();
            tohidenasabah.hide();
            selTip.on('change', function () {
                if (selTip.val() == 1) {
                    selAr_.show();selAr2_.hide();
                    selHk.val("null");
                    selHkn.val("null");
                }
                else if (selTip.val() == 2) {
                    selAr2_.show();selAr_.hide();
                    selHk.val("");
                    selHkn.val("");
                }
            });

            $("#idUsrT").select2({
                dropdownParent: $("#transferTabModal")
            });
            $("#idRek").select2({
                dropdownParent: $("#debitTabModal")
            });
            $("#kreidRek").select2({
                dropdownParent: $("#kreditTabModal")
            });
            $("#rekAkad ").select2({
                dropdownParent: $("#openTabModal")
            });
            $("#rekTab").select2({
                dropdownParent: $("#openTabModal")
            });
            $("#bank").select2({
                dropdownParent: $("#kreditTabModal")
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
            }
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

            $('#wizardCardTrans').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormTrans').valid();

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
            $('#wizardCardTransv').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormTransv').valid();

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