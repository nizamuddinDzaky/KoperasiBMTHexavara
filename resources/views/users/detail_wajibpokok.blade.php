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
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header text-center">
                            <h4 class="title">Riwayat Transaksi </h4>
                            <p class="category">Simpanan Pokok </p>
                            <p class="category"> <b>Total Simpanan Pokok : Rp {{number_format($pokok,2)}}</b> </p>

                            <br />
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <span></span>
                        </div>

                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th class="text-center"></th>
                            <th class="text-center" data-sortable="true">Tgl Transaksi</th>
                            <th class="text-center" data-sortable="true" >Dari Rekening</th>
                            <th class="text-center" data-sortable="true">Ke Rekening</th>
                            <th class="text-center" data-sortable="true">Jenis Transaksi</th>
                            <th class="text-center" data-sortable="true">Jumlah</th>
                            <th class="text-center" data-sortable="true">Saldo Awal</th>
                            <th class="text-center" data-sortable="true">Saldo Akhir</th>
                            {{--<th>Actions</th>--}}
                            <th></th>
                            </thead>
                            <tbody>
                            @if(count($data['data2']>1))
                            @foreach ($data['data2'] as $usr)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->created_at }}</td>
                                    <td class="text-left text-uppercase">{{ json_decode($usr->transaksi,true)['dari_rekening'] }}</td>
                                    <td class="text-left text-uppercase">[{{ $usr->id_rek}}] {{" ". $usr->untuk_rekening }}</td>
                                    <td class="text-center text-uppercase">{{$usr->status}}</td>
                                    <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['jumlah'],2) }}</td>
                                    <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['saldo_awal'],2) }}</td>
                                    <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['saldo_akhir'],2) }}</td>
                                    <td></td>
                                </tr>

                            @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="header text-center">
                            <h4 class="title">Riwayat Transaksi </h4>
                            <p class="category">Simpanan Wajib </p>
                            <p class="category"> <b>Total Simpanan Wajib : Rp {{number_format($wajib,2)}}</b> </p>

                            <br />
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                                          <span></span>
                    </div>

                        <table id="bootstrap-table2" class="table">
                            <thead>
                            <th class="text-center"></th>
                            <th class="text-center" data-sortable="true">Tgl Transaksi</th>
                            <th class="text-center" data-sortable="true" >Dari Rekening</th>
                            <th class="text-center" data-sortable="true">Ke Rekening</th>
                            <th class="text-center" data-sortable="true">Jenis Transaksi</th>
                            <th class="text-center" data-sortable="true">Jumlah</th>
                            <th class="text-center" data-sortable="true">Saldo Awal</th>
                            <th class="text-center" data-sortable="true">Saldo Akhir</th>
                            {{--<th>Actions</th>--}}
                            <th></th>
                            </thead>
                            <tbody>
                            @if(count($data['data']>1))
                                @foreach ($data['data'] as $usr)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->created_at }}</td>
                                    <td class="text-left text-uppercase">{{ json_decode($usr->transaksi,true)['dari_rekening'] }}</td>
                                    <td class="text-left text-uppercase">[{{ $usr->id_rek}}] {{" ". $usr->untuk_rekening }}</td>
                                    <td class="text-center text-uppercase">{{$usr->status}}</td>
                                    <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['jumlah'],2) }}</td>
                                    <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['saldo_awal'],2) }}</td>
                                    <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['saldo_akhir'],2) }}</td>
                                    <td></td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->
        </div>
    </div>
@endsection



    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    <script type="text/javascript">
        {{--url_add = "{{route('anggota.add_pembiayaan')}}";--}}
        {{--url_edit = "{{route('anggota.edit_pengajuan')}}";--}}
        {{--url_delete = "{{route('anggota.delete_pengajuan')}}";--}}
    </script>

    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">

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
        var $table2 = $('#bootstrap-table');


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
                "paging": false,
                "dom": 'lBfrtp',
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
                "paging": false,
                "dom": 'lBfrtp',
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

        });
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

        function readURL5(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic5')
                        .attr('src', e.target.result)
                        .width(100)
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