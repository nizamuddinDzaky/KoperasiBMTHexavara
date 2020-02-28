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
        <div>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">DONASI KEGIATAN</a></li>
              <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">ZIS</a></li>
              <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">WAKAF</a></li>
            </ul>
          
            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="home">
                <div class="row">

                    @foreach($kegiatan as $kegiatan)

                    @php
                        $dana = json_decode($kegiatan['detail'],true)['dana'];
                        $tanggal_pelaksanaan = Carbon\Carbon::parse($kegiatan['tanggal_pelaksanaan']);
                    @endphp

                    <div class="col-sm-12 col-md-6 col-lg-6" data-toggle="modal" data-target="#donasi">
                        <div class="panel panel-default event" 
                            style="
                                @if(json_decode($kegiatan['detail'], true)['path_poster'] != "") 
                                    background-image: url({{ asset('storage/file' . json_decode($kegiatan['detail'], true)['path_poster']) }}) 
                                @else
                                    background-image: url({{ asset('bmtmudathemes/assets/images/no-image-available.png') }}) 
                                @endif
                            ">
                            <div class="panel-body">
                                <div class="card-title">
                                    <p class="event-name" style="font-size: 11px; text-align: left">Nama Kegiatan</p>
                                    <p class="event-name">{{ $kegiatan['nama_kegiatan'] }}</p>
                                    <p class="event-name" style="font-size: 11px; text-align: right">Rp {{ number_format($dana) }}</p>
                                </div>
                                <div class="event-date">
                                    <p class="title">Tanggal pelaksanaan</p>
                                    <p class="date">{{ $tanggal_pelaksanaan->format('D, d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    
                    <div class="row" style="text-align: right;">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                <li>
                                    <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li><a href="#">1</a></li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#">5</a></li>
                                <li>
                                    <a href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="profile">...</div>
              <div role="tabpanel" class="tab-pane" id="messages">...</div>
              <div role="tabpanel" class="tab-pane" id="settings">...</div>
            </div>
          
          </div>
        </div>
    </div>
    
    {{-- @include('../modal/donasi') --}}

@endsection

@section('extra_script')


	<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
	<script src="{{URL::asset('bootstrap/assets/js/moment.min.js')}}"></script>
	<!--  Date Time Picker Plugin is included in this js file -->
	<script src="{{URL::asset('bootstrap/assets/js/bootstrap-datetimepicker.js')}}"></script>
    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script src="{{URL::asset('bootstrap/assets/js/jquery.validate.min.js')}}"></script>
	<script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>
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

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic2')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(auto)
                };


                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic3')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(auto)
                };


                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL4(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic4')
                        .attr('src', e.target.result)
                        .width(400)
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
//                    defaultDate: "11/1/2013",
                    defaultDate: '{{isset(json_decode(Auth::user()->detail,true)['tgl_lahir'])?json_decode(Auth::user()->detail,true)['tgl_lahir']:""}}',
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

	<script type="text/javascript">

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
        }

        document.onkeypress = stopRKey;

	</script>
@endsection
@section('footer')
	@include('layouts.footer')
@endsection