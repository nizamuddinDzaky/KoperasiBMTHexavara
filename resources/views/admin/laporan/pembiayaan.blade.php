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
                <h4 class="title">Realisasi Pembiayaan</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>

                    <div class="col-sm-12 col-md-4 col-lg-3">
                        <input type="text" class="form-control daterange" placeholder="Filter" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">

                    <div class="header text-center">
                        @if(Request::is('admin/laporan/pengajuan'))
                        <h4 id="titlePrint" class="title"><b>Pengajuan Pembiayaan</b> </h4>
                        <p id="titlePrint2" class="category">Daftar Pengajuan Pembiayaan</p>
                        @elseif(Request::is('admin/laporan/realisasi'))
                        <h4 id="titlePrint" class="title"><b>Realisasi Pembiayaan</b> </h4>
                        <p id="titlePrint2" class="category">Daftar Realisasi Pembiayaan</p>
                        @elseif(Request::is('teller/laporan/realisasi'))
                        <h4 id="titlePrint" class="title"><b>Realisasi Pembiayaan</b> </h4>
                        <p id="titlePrint2" class="category">Daftar Realisasi Pembiayaan</p>
                        @endif
                            <br />
                    </div>

                    <table id="bootstrap-table" class="table">
                        <thead>
                            <th></th>
                            <th data-sortable="true" class="text-left">ID</th>
                            <th data-sortable="true" class="text-left">Anggota</th>
                            <th data-sortable="true" class="text-left">KTP</th>
                            <th data-sortable="true">Jenis Pembiayaan</th>
                            <th data-sortable="true">Jumlah</th>
                            <th data-sortable="true">Tgl Pembukaan</th>
                            <th data-sortable="true">Status</th>
                            <th class="text-center">Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $usr)
                                <tr>
                                    <td></td>
                                    <td class="text-left">{{ $usr->id }}</td>
                                    <td class="text-left">{{ $usr->user->nama  }}</td>
                                    <td class="text-left">{{ $usr->user->no_ktp   }}</td>
                                    <td class="text-left">{{ $usr->jenis_pembiayaan   }}</td>
                                    <td class="text-left">{{ number_format(json_decode($usr->detail,true)['pinjaman'],2) }}</td>
                                    <td class="text-left">{{ Carbon\Carbon::parse($usr->created_at)->format("D, d F Y") }}</td>
                                    <td class="text-left text-uppercase">{{ $usr->status }}</td>
                                    <td class="text-center">
                                        <button type="button" id="detail" class="btn btn-social btn-primary btn-fill" title="View Detail">
                                            <i class="fa fa-list-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->
        </div>
    </div>
    {{-- @include('modal.pengajuan') --}}
    {{-- @include('modal.user_pembiayaan') --}}
@endsection

@section('extra_script')


    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
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

            $('#vjenis').val(button.data('jenis'));
            $('#vjaminan').val(button.data('jaminan'));
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
            $('#ajenis').val(button.data('jenis'));
            $('#ajaminan').val(button.data('jaminan'));
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
            $("#vbank").val(button.data('bank') );;
            $("#vban").val(button.data('bankuser') );
            $("#vpokok_ang").val(button.data('pokok') );
            $("#vjumlah_ang").val(button.data('jumlah') );
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
            console.log(button.data('jenis'));
            console.log(button.data('jenis'));
            $("#aidRekA").val(button.data('id') );
            $("#aidTabA").val(button.data('idtab') );
            $("#aangidRek").val(button.data('idtab') );
            $("#ajenisAng").val(button.data('jenis') );
            $("#abankAng").val(button.data('bankuser') );
            $("#abank").val(button.data('bank') );
            $("#apokok_ang").val(button.data('pokok') );
            $("#ajumlah_ang").val(button.data('jumlah') );
            $("#anobankAng").val(button.data('no_bank') );
            $("#aatasnamaAng").val(button.data('atasnama') );
            $("#apicAng").attr("src", button.data('path') );

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
                        {
                            extend: 'print',
                            title: function () { return  $('#titlePrint2').text()+"\n"+$('#titlePrint2').text(); },
                        },

                        'copyHtml5',
                        {
                            extend: 'excelHtml5',
                            messageTop: function () { return  $('#titlePrint').text(); },
                            messageTop: function () { return  $('#titlePrint2').text(); },
                        },
                        {
                            extend:'pdfHtml5',
                            title: function () { return  $('#titlePrint').text()+"\n"+$('#titlePrint2').text(); },
                            customize: function(doc) {
                                doc.defaultStyle.fontSize = 7;
                                doc.styles.title = {
                                    fontSize: '11',
                                    alignment: 'center'
                                };
                                doc.content.layout='Border';
                            }
                        }
                    ]
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
            var selA4 =$('#toHide_angpok');
            var selRek = $('#angidRek');
            selRek.on('change', function () {
                var id = $('#idRekA').val(selRek.find(":selected").text().split(']')[0]);
                id = id.val().split('[')[1];
                $('#idRekA').val(id);
                $('#pokok_ang').val(selRek.val())
                $('#pokok_').val(selRek.val())
                $('#jumlah_pok').val(selRek.val())
                selA.hide(); selA2.hide(); selA3.hide();selA4.hide();
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
            var j_ang =$('#jumlah_ang');
            var j_pok =$('#jumlah_pok');
            var j_mar =$('#jumlah_mar');

            var jenis = $('#debit');
            var bukti = $('#bukti');
            var pem   = $('#pembayaran')
            var pokok = 0;
            var margin = 0;
            var lama = 0;
            var next = 0;

            selAr.hide(); selArB.hide(); selArB2.hide();
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
            });
            pem.on('change', function () {

                pokok = parseFloat(selRek.val().split(' ')[0]);
                lama = parseFloat(selRek.val().split(' ')[2]);
                margin = parseFloat(selRek.val().split(' ')[1]);
                $('#pokok_').val(pokok);
                $('#pokok_ang').val(pokok);selA4.show();
                if(pem.val() == 0) {
                    j_pok.attr("required",true);
                    j_ang.attr("required",false);
                    j_mar.attr("required",false);
                    selA.show();j_pok.val(pokok)
                    selA5.hide();
                    selA2.hide(); selA3.hide()
                    $('#min_').val(pokok);
                    $('#jumlah_').val(pokok);;
                    $('#tipe_').val( 2 );
                }
                else if(pem.val() == 1) {
                    j_pok.attr("required",false);
                    j_ang.attr("required",true);
                    j_mar.attr("required",false);
                    selA.hide();j_ang.val(pokok - ( margin / lama ))
                    selA5.hide();
                    selA2.show(); selA3.hide()
                    $('#min_').val(pokok - ( margin / lama ));
                    $('#jumlah_').val(pokok - ( margin / lama ));
                    $('#tipe_').val( 1 );
                }
                else if(pem.val() == 2) {
                    j_pok.attr("required",false);
                    j_ang.attr("required",false);
                    j_mar.attr("required",true);
                    selA.hide();j_mar.val(margin / lama)
                    selA5.hide();
                    selA2.hide(); selA3.show()
                    $('#min_').val( ( margin / lama ));
                    $('#jumlah_').val( ( margin / lama ));
                    $('#tipe_').val( 0 );
                }
                else if(pem.val() == 3) {
                    j_pok.attr("required",false);
                    j_ang.attr("required",false);
                    j_mar.attr("required",true);
                    selA.hide();
                    selA5.show();
                    selA2.hide(); selA3.hide()
                    $('#min_').val( pokok );
                    $('#tipe_').val( 2 );
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
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
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