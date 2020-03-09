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
    
    @if(Request::is('anggota/menu/deposito'))
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Mudharabah Berjangka Nasabah</h4>

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
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#extendDepModal"><i class="fa fa-external-link-alt"></i> Perpanjangan MDB</button>
                    <button class="btn btn-danger rounded right shadow-effect" data-toggle="modal" data-target="#withdrawDepModal"><i class="fa fa-donate"></i> Pencairan MDB</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Rekening Mudharabah Berjangka </h4>
                        <p class="category">Berikut ini adalah daftar rekening mudharabah berjangka anda</p>
                        <br />
                    </div>
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                        <span></span>
                    </div>

                    <table id="bootstrap-table" class="table">
                        <thead>
                        <th></th>
                        <th class="text-center" data-sortable="true">ID</th>
                        <th class="text-center" data-sortable="true">Jenis Mudharabah Berjangka</th>
                        <th class="text-center" data-sortable="true">Tgl Pembuatan</th>
                        <th class="text-center" data-sortable="true">Jumlah</th>
                        <th class="text-center" data-sortable="true">Tgl Tempo</th>
                        <th class="text-center" data-sortable="true">Status</th>
                        <th class="text-center">Actions</th>
                        </thead>
                        <tbody>
                        @foreach ($data as $usr)
                            <tr>
                                <td></td>
                                <td class="text-left">{{ $usr->id_deposito }}</td>
                                <td class="text-left">{{ $usr->jenis_deposito   }}</td>
                                <td class="text-center">{{ date_format($usr->created_at,'Y-m-d') }}</td>
                                <td class="text-left">Rp{{" ". number_format(json_decode($usr->detail,true)['saldo'],2) }}</td>
                                <td class="text-center">{{ date_format(date_create($usr->tempo),'Y-M-d') }}</td>
                                <td class="text-center text-uppercase">{{ $usr->status }}</td>
                                <td class="td-actions text-center">
                                    <form  method="post" action="{{route('anggota.detail_deposito')}}">
                                        <input type="hidden" id="id_status" name="id_" value="{{$usr->id}}">
                                        {{csrf_field()}}
                                        <button type="submit" class="btn btn-social @if($usr->status=="blocked" ||$usr->status=="not active")btn-danger @else btn-info @endif  btn-fill" title="Detail"
                                                data-id      = "{{$usr->no_ktp}}"
                                                data-nama    = "{{$usr->nama}}" name="id">
                                            @if($usr->status=="blocked")
                                                <i class="fa fa-close"></i>
                                            @elseif($usr->status=="active")
                                                <i class="fa fa-clipboard-list"></i>
                                            @elseif($usr->status=="not active")
                                                <i class="fa fa-minus-square"></i>
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
        </div>
        <!-- end row -->
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">

                    <div class="header text-center">
                        <h4 class="title">Riwayat Mudharabah Berjangka </h4>
                        <p class="category">Berikut adalah riwayat pengajuan mudharabah berjangka anda</p>
                        <br />
                    </div>
                    <div class="toolbar">
                        <!--        Here you can write extra buttons/actions for the toolbar              -->
                        <span>
                            {{--<button id=expandTable class="btn btn-social btn-success btn-fill" style="margin-right: 0.3em"> <i class="fa fa-eye"></i></button>--}}
                    </span>
                    </div>

                    <table id="bootstrap-table2" class="table">
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
                                <td class="text-left">{{ json_decode($usr->detail,true)['keterangan'] }}</td>
                                <td class="text-left">{{ $usr->created_at }}</td>
                                <td class="text-left">{{ $usr->status }}</td>

                                <td class="td-actions text-center">
                                    <div class="row">
                                        <button type="button" id="detail" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#view{{substr($usr->kategori,0,3)}}Modal" title="View Detail"
                                                data-id         = "{{$usr->id}}"
                                                data-namauser   = "{{ json_decode($usr->detail,true)['nama'] }}"
                                                data-ktp     = "{{ $usr->no_ktp }}"
                                                data-iduser     = "{{ json_decode($usr->detail,true)['id']}}"
                                                data-jumlah     = "{{ number_format(json_decode($usr->detail,true)['jumlah'],2)}}"
                                                data-keterangan = "{{ json_decode($usr->detail,true)['keterangan'] }}"
                                                @if($usr->jenis_pengajuan =="Perpanjangan Deposito")
                                                data-iddep     = "{{ json_decode($usr->detail,true)['id_deposito']}}"
                                                data-atasnama   = "Pribadi"
                                                data-saldo    = "{{ number_format(json_decode($usr->detail,true)['saldo'],2)}}"
                                                data-kategori   = "{{ json_decode($usr->detail,true)['id_rekening_baru'] }}"
                                                @elseif(str_before($usr->kategori,' ')=="Pencairan")
                                                data-iddep     = "{{ json_decode($usr->detail,true)['id_deposito']}}"
                                                data-atasnama   = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                data-bank   = "{{ json_decode($usr->detail,true)['bank'] }}"
                                                data-nobank   = "{{ json_decode($usr->detail,true)['no_bank'] }}"
                                                data-jenis   = "{{ json_decode($usr->detail,true)['pencairan'] }}"
                                                data-kategori   = "{{ $usr->kategori}}"
                                                @else
                                                data-kategori   = "{{ $usr->id_rekening }}"
                                                data-atasnama   = "{{ json_decode($usr->detail,true)['atasnama'] }}"
                                                data-rek_tab       = "{{ isset(json_decode($usr->detail,true)['id_pencairan'])?json_decode($usr->detail,true)['id_pencairan']:"" }}"
                                                data-nisbah       = "{{ json_decode($usr->deposito,true)['nisbah_anggota'] }}"
                                                @endif

                                        >
                                            <i class="fa fa-list-alt"></i>
                                        </button>
                                        @if($usr->status =="Disetujui" || $usr->status =="Sudah Dikonfirmasi")
                                        @else
                                            <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delModal" title="Delete"
                                                data-id       = "{{$usr->id}}"
                                                data-nama     = "{{$usr->jenis_pengajuan}}">
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
        </div>
        <!-- end row -->
    </div>

    @include('modal.pengajuan')
    @include('modal.user_deposito')
@endsection

    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
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
            if (button.data('keterangan') === "Perpanjangan Deposito") {
                $('#titleVDep').text("Perpanjangan Mudharabah Berjangka");
            }else
                $('#titleVDep').text("Pembukaan Mudharabah Berjangka");
            $('#vket_nisbah').val(button.data('nisbah'));
            $('#vrek_tabungan').val(button.data('rek_tab'));
            $('#vketerangan2').val(button.data('keterangan'));
            $('#vjumlahdep').val(button.data('jumlah'));
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
        var $table2 = $('#bootstrap-table2');


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
            $('#bootstrap-table2').dataTable({
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

            var selTip3 = $('#widRek');
            selTip3.on('change', function () {
                var id = $('#idRekWD').val(selTip3.find(":selected").text().split(']')[0]);
                id = id.val().split('[')[1];
                $('#idRekWD').val(id);
                console.log(id);
                $('#wjumlah').val(selTip3.val())
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


            var selHk2 = $('#idhukum2');
            var selHkn2 = $('#namahukum2');
            var selAr3 = $('#toHide3');
            var selAr4 = $('#toHide4');
            selAr3.hide();
            selAr4.hide();
            var selTip2 = $('#atasnama2');
            var tohidenasabah = $('#toHideNasabah2');
            tohidenasabah.hide();
            selTip2.on('change', function () {
                if(selTip2.val() == 1) {
                    selAr3.show();selAr4.hide();
                    selHk2.val("null");
                    selHkn2.val("null");
                }
                else{
                    selAr4.show();selAr3.hide();
                    selHk2.val("");
                    selHkn2.val("");
                }
            });
            $("#rekDep").select2({
                dropdownParent: $("#openDepModal")
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