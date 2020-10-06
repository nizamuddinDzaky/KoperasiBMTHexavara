@extends('layouts.donasi')
@section('extra_style')
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('css/donasi_nav.css')}}">
    <script src="{{ asset('js/bootstrap.js') }}"></script>
@endsection
@section('content')

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{url('/')}}">BMT MUDA</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{url('/')}}">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://www.bmtmuda.com/2012/01/profile-bmt.html">Profile</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="row">
        <button type="button" class="btn btn-primary text-right" data-toggle="modal" data-target="#donasiZis" style="float: right">Pembayaran ZIS<i class="fa fa-external-link-alt"></i></button>
    </div>


    <div class="row">
            <h2 style="font-weight: bold;" class="text-center">Daftar Kegiatan Maal</h2>
    </div>
    <div class="row">
        @foreach($kegiatan as $item)

            @php
                $dana = json_decode($item['detail'],true)['dana'];
                $dana_terkumpul = json_decode($item['detail'],true)['terkumpul'];
                $persen = ($dana_terkumpul / $dana) * 100;
                $tanggal_pelaksanaan = Carbon\Carbon::parse($item['tanggal_pelaksanaan']);
            @endphp
            <div class="col-sm-12 col-md-4 col-lg-3">
                <div class="card hover" data-toggle="modal" data-target="#donasiKegiatan"
                     data-id="{{ $item['id'] }}"
                     data-jenis="donasi kegiatan"
                     data-nama="{{$item['nama_kegiatan']}}"
                >
                    <div class="card-image">
                        @if(json_decode($item['detail'], true)['path_poster'] != "")
                            <img src="{{ asset('storage/public/maal/' . json_decode($item['detail'], true)['path_poster']) }}">
                        @else
                            <img src="{{ asset('bmtmudathemes/assets/images/no-image-available.png') }}">
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="date">
                            <p class="content">DANA DIBUTUHKAN : {{ number_format($dana) }}</p>
                            <p class="content">DANA TERKUMPUL : {{ number_format($dana_terkumpul) }}</p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{$persen}}%; background-color: #00ff00" aria-valuenow="{{$persen}}" aria-valuemin="0" aria-valuemax="100">{{$persen}}</div>
                            </div>
                        </div>
                        <h4 class="title">{{ $item['nama_kegiatan'] }}</h4>
                        <p class="description">
                        <div class="summernote-content">{!! json_decode($item['detail'],true)['detail'] !!}</div>
                    </div>

                    <div class="overlay"></div>
                </div>
            </div>
        @endforeach


        <div class="row" style="text-align: right;">
            <div class="col-sm-12 col-md-12 col-lg-12">
                {{ $kegiatan->links() }}
            </div>
        </div>
    </div>
    <div class="row">
            <h2 style="font-weight: bold" class="text-center">Daftar Kegiatan Wakaf</h2>
    </div>
    <div class="row">
        @foreach($kegiatan_wakaf as $item)

            @php
                $dana = json_decode($item['detail'],true)['dana'];
                $dana_terkumpul = json_decode($item['detail'],true)['terkumpul'];
                $persen = ($dana_terkumpul / $dana) * 100;
                $tanggal_pelaksanaan = Carbon\Carbon::parse($item['tanggal_pelaksanaan']);
            @endphp
            <div class="col-sm-12 col-md-4 col-lg-3">
                <div class="card hover" data-toggle="modal" data-target="#donasiKegiatanWakaf"
                     data-id="{{ $item['id'] }}"
                     data-jenis="donasi kegiatan wakaf"
                     data-nama = "{{ $item['nama_kegiatan'] }}"
                >
                    <div class="card-image">
                        @if(json_decode($item['detail'], true)['path_poster'] != "")
                            <img src="{{ asset('storage/public/wakaf/' . json_decode($item['detail'], true)['path_poster']) }}">
                        @else
                            <img src="{{ asset('bmtmudathemes/assets/images/no-image-available.png') }}">
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="date">
                            <p class="content">DANA DIBUTUHKAN : {{ number_format($dana) }}</p>
                            <p class="content">DANA TERKUMPUL : {{ number_format($dana_terkumpul) }}</p>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{$persen}}%; background-color: #00ff00" aria-valuenow="{{$persen}}" aria-valuemin="0" aria-valuemax="100">{{$persen}}</div>
                            </div>
                        </div>
                        <h4 class="title">{{ $item['nama_kegiatan'] }}</h4>
                        <p class="description">
                        <div class="summernote-content">{!! json_decode($item['detail'],true)['detail'] !!}</div>
                        </p>
                    </div>

                    <div class="overlay"></div>
                </div>
            </div>
        @endforeach


        <div class="row" style="text-align: right;">
            <div class="col-sm-12 col-md-12 col-lg-12">
                {{ $kegiatan_wakaf->links() }}
            </div>
        </div>
    </div>
{{--    <div class="row">--}}
{{--        <div class="col-md-3" style="display: inline">--}}
{{--            <h2 style="font-weight: bold  ;margin: 0" class="mt-2">Zis</h2>--}}
{{--           --}}
{{--        </div>--}}
{{--    </div>--}}

    <footer class="footer mt-5" style="background-color: #3097D1">
        <div class="container">
            <div class="row">
                    <h1 class="display-5 font-weight-bold text-center" style="font-size: 1.25em; color: white">BMT MUDA (Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada)</h1>
            </div>

        </div>

    </footer>


@endsection

@section('modal')
    @include('modal/donasi_umum/kegiatan')
    @include('modal/donasi_umum/zis')
    @include('modal/donasi_umum/wakaf')
@endsection

@section('extra_script')

    <!-- Tab selected index -->
    <script src="{{ asset('bmtmudathemes/assets/js/pages/donasi_maal.js') }}"></script>

    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->
    <script src="{{URL::asset('bootstrap/assets/js/moment.min.js')}}"></script>
    <!--  Date Time Picker Plugin is included in this js file -->
    <script src="{{URL::asset('bootstrap/assets/js/bootstrap-datetimepicker.js')}}"></script>
    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script src="{{URL::asset('bootstrap/assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>

    <!-- Donasi script -->
    <script src="{{ asset('bmtmudathemes/assets/js/modal/donasi.js') }}"></script>

    <script type="text/javascript">
        $().ready(function(){

            $('.currency').maskMoney({
                allowZero: true,
                precision: 0,
                thousands: ","
            });

            $('#idRekT').attr("required",false);
            $('#rekdon').on('change', function () {
                if($('#rekdon').val() == 1) {
                    $('#idRekT').attr("required",true);
                    $('#Hide').hide();
                    $('#tipdon').val(1);
                }
                else if($('#rekdon').val() == 0) {
                    $('#idRekT').attr("required",false);
                    $('#Hide').show();
                    $('#tipdon').val(0);
                }
            });

            $('#bank').attr("required",false);
            $('#RekBank').hide();
            var rekening = 0; var pokok = 0; var margin = 0;var lama = 0; var angke = 0;var angbln = 0;var marbln = 0;
            var saldo = $('#idRekTab');
            saldo.on('change', function () {
                $('#idTab_').val(parseInt(saldo.val().split(' ')[0]));
                $('#saldo').val((saldo.val().split(' ')[1]));
            });

            var selAr = $('#toHideTab');
            var selArB =$('#toHideBank');
            var selArB2 =$('#toHideBank2');
            var atasnama =$('#atasnamaDeb');
            var bank =$('#bankDeb');
            var nobank =$('#nobankDeb');
            var rekTab =$('#idRekTab')
            var jenis = $('#jenis');
            var bukti = $('#bukti');
            selAr.hide(); selArB.hide(); selArB2.hide();
            jenis.on('change', function () {
                if(jenis .val() == 0) {
                    bukti.attr("required",true);
                    bank.attr("required",true);
                    atasnama.attr("required",true);
                    nobank.attr("required",true);
                    rekTab.attr("required",false);
                    selAr.hide();
                    selArB.show(); selArB2.show()
                    $('#bank').attr("required",true);
                    $('#RekBank').show();
                }
                else if (jenis .val() == 1) {
                    $('#bank').val(0);
                    rekTab.attr("required",true);
                    bank.attr("required",false);
                    atasnama.attr("required",false);
                    nobank.attr("required",false);
                    bukti.attr("required",false);
                    selAr.show();
                    selArB.hide();selArB2.hide();
                    $('#bank').attr("required",false);
                    $('#RekBank').hide();
                }
                else if (jenis .val() == 2) {
                    $('#bank').val(0);
                    rekTab.attr("required",false);
                    bank.attr("required",false);
                    atasnama.attr("required",false);
                    nobank.attr("required",false);
                    bukti.attr("required",false);
                    selAr.hide();
                    selArB.hide();selArB2.hide();
                    $('#bank').attr("required",false);
                    $('#RekBank').hide();
                }
            });

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

        });

        function onFinishWizard(){
            //here you can do something, sent the form to server via ajax and show a success message with swal

            swal("Data disimpan!", "Terima kasih telah melengkapi data diri anda!", "success");
        }
    </script>

    <script type="text/javascript">
        $().ready(function(){
            $("#idRekTab").select2({
                dropdownParent: $("#wizardCard")
            });
            $("#idRekT").select2({
                dropdownParent: $("#wizardCard")
            });

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

    <script type="text/javascript">

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
        }

        document.onkeypress = stopRKey;

    </script>
@endsection


