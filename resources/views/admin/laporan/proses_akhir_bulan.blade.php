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
                <h4 class="title">Distribusi Pendapatan</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <div class="col-sm-12 col-md-4 col-lg-3">
                        <input type="text" class="form-control without-day" name="dateYearAndMonth" placeholder="Filter" />
                    </div>

                    @if($status == false)
                    <div class="button-group right">
                        <button class="btn btn-primary rounded right shadow-effect" onclick="document.getElementById('form_net_profit').submit()"><i class="fa fa-share"></i> Distribusi Profit Sharing</button>
                        <button class="btn btn-success rounded right shadow-effect" onclick="document.getElementById('form_revenue').submit()"><i class="fa fa-share"></i> Distribusi Revenue Sharing</button>
                    </div>
                    @endif

                    <form action="{{route('admin.proses_akhir_bulan.do_pendistribusian')}}" method="post" id="form_net_profit">
                        {{ csrf_field() }}
                        <input type="hidden" name="jenis" value="net_profit"/>
                    </form>

                    <form action="{{route('admin.proses_akhir_bulan.do_pendistribusian')}}" method="post" id="form_revenue">
                        {{ csrf_field() }}
                        <input type="hidden" name="jenis" value="revenue"/>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <div class="content">
{{-- you can remove stuff from here--}}
{{--        <div class="row">--}}
{{--            <div class="col-sm-12 col-md-12 col-lg-12">--}}
{{--                <div class="card text-center">--}}
{{--                    <h4 id="titlePrint" class="title"><b>Tabungan Anggota Revenue</b> </h4>--}}
{{--                    <table id="bootstrap-table" class="table">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th rowspan="2" class="text-center">Nama</th>--}}
{{--                            <th rowspan="2" class="text-center">Saldo Rata-Rata</th>--}}
{{--                            <th rowspan="2" class="text-center">Bagi Hasil</th>--}}
{{--                            <th rowspan="2" class="text-center">Nama Tabungan</th>--}}

{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                            @foreach($dataSaldoTabungan as $keys => $value)--}}
{{--                                <tr>--}}
{{--                                    <td>{{$value[0]}}</td>--}}
{{--                                    <td>{{number_format($value[1],2)}}</td>--}}
{{--                                    <td>{{number_format($value[2],2)}}</td>--}}
{{--                                    <td>{{$value[3]}}</td>--}}
{{--                                </tr>--}}
{{--                        @endforeach--}}

{{--                        </tbody>--}}

{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="col-sm-12 col-md-12 col-lg-12">--}}
{{--                <div class="card text-center">--}}
{{--                    <h4 id="titlePrint" class="title"><b>Tabungan Anggota Net</b> </h4>--}}
{{--                    <table id="bootstrap-table" class="table">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th rowspan="2" class="text-center">Nama</th>--}}
{{--                            <th rowspan="2" class="text-center">Saldo Rata-Rata</th>--}}
{{--                            <th rowspan="2" class="text-center">Bagi Hasil</th>--}}
{{--                            <th rowspan="2" class="text-center">Nama Tabungan</th>--}}

{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($dataSaldoTabunganNet as $keys => $value)--}}
{{--                            <tr>--}}
{{--                                <td>{{$value[0]}}</td>--}}
{{--                                <td>{{number_format($value[1],2)}}</td>--}}
{{--                                <td>{{number_format($value[2],2)}}</td>--}}
{{--                                <td>{{$value[3]}}</td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}

{{--                        </tbody>--}}

{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="col-sm-12 col-md-12 col-lg-12">--}}
{{--                <div class="card text-center">--}}
{{--                    <h4 id="titlePrint" class="title"><b>Deposito Anggota Revenue</b> </h4>--}}
{{--                    <table id="bootstrap-table" class="table">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th rowspan="2" class="text-center">Nama</th>--}}
{{--                            <th rowspan="2" class="text-center">Saldo Rata-Rata</th>--}}
{{--                            <th rowspan="2" class="text-center">Bagi Hasil</th>--}}
{{--                            <th rowspan="2" class="text-center">Nama Deposito</th>--}}

{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($dataSaldoDeposito as $keys => $value)--}}
{{--                            <tr>--}}
{{--                                <td>{{$value[0]}}</td>--}}
{{--                                <td>{{number_format($value[1],2)}}</td>--}}
{{--                                <td>{{number_format($value[2],2)}}</td>--}}
{{--                                <td>{{$value[3]}}</td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}

{{--                        </tbody>--}}

{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="row">--}}
{{--            <div class="col-sm-12 col-md-12 col-lg-12">--}}
{{--                <div class="card text-center">--}}
{{--                    <h4 id="titlePrint" class="title"><b>Deposito Anggota Net</b> </h4>--}}
{{--                    <table id="bootstrap-table" class="table">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th rowspan="2" class="text-center">Nama</th>--}}
{{--                            <th rowspan="2" class="text-center">Saldo Rata-Rata</th>--}}
{{--                            <th rowspan="2" class="text-center">Bagi Hasil</th>--}}
{{--                            <th rowspan="2" class="text-center">Nama Deposito</th>--}}

{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @foreach($dataSaldoDepositoNet as $keys => $value)--}}
{{--                            <tr>--}}
{{--                                <td>{{$value[0]}}</td>--}}
{{--                                <td>{{number_format($value[1],2)}}</td>--}}
{{--                                <td>{{number_format($value[2],2)}}</td>--}}
{{--                                <td>{{$value[3]}}</td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}

{{--                        </tbody>--}}

{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                
                @if($status)
                    <div class="alert alert-success text-center">
                        <span><b>Distribusi Pendapatan bulan ini telah dilakukan</b> !</span>
                    </div>
                @endif

                <div class="card">
                    <div class="header text-center">
                        <h4 id="titlePrint" class="title"><b>Distribusi Pendapatan</b> </h4>
                        <p id="titlePrint2" class="category">Laporan Distribusi Pendapatan periode {{date("F Y")}}</p>
                            <br />
                    </div>

                    <table id="bootstrap-table" class="table">
                        <thead>
                            <tr>
                                <th rowspan="2" class="text-left">No</th>
                                <th rowspan="2"> Produk</th>
                                <th rowspan="2"> Saldo Rata-rata</th>
                                <th rowspan="2"> Pendapatan</th>
                                <th colspan="2" class="text-center"> Nisbah</th>
                                <th colspan="2" class="text-center"> Porsi</th>
                                <th rowspan="2"> % Anggota</th>
                            </tr>
                            <tr>
                                <th class="text-center">Anggota</th>
                                <th class="text-center">BMT</th>
                                <th class="text-center">Anggota</th>
                                <th class="text-center">BMT</th>
                            </tr>
                        </thead>
                        <tbody>

                        {{-- @php
                            $i = 1;
                            $total_pendapatan = 0;
                            $total_rata_rata = 0;
                            $total_porsi_anggota = 0;
                            $total_porsi_bmt = 0;
                            $total_persentase_anggota = 0;
                        @endphp --}}
                        @foreach($data as $item)
                            @php
                                $i = 1;
                                $total_pendapatan = 0;
                                $total_rata_rata = 0;
                                $total_porsi_anggota = 0;
                                $total_porsi_bmt = 0;
                                $total_persentase_anggota = 0;
                            @endphp
                            @foreach(json_decode($item['transaksi']) as $value)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{ $value->jenis_rekening }}</td>
                                    <td class="text-right">{{number_format($value->rata_rata,2)}}</td>
                                    <td class="text-right">{{ number_format($value->pendapatan_product,2) }}</td>
                                    <td class="text-center">{{ $value->nisbah_anggota }}</td>
                                    <td class="text-center">{{ $value->nisbah_bmt }}</td>
                                    <td class="text-right">{{ number_format($value->porsi_anggota,2)  }}</td>
                                    <td class="text-right">{{ number_format($value->porsi_bmt,2) }}</td>
                                    <td class="text-center">{{ $value->total_pendapatan > 0 ? round($value->porsi_anggota / $value->total_pendapatan * 100, 2) : 0.00 }}%</td>
                                </tr>
                                @php 
                                    $total_pendapatan = $value->total_pendapatan;
                                    $total_rata_rata = $value->total_rata_rata;
                                    $total_porsi_anggota += $value->porsi_anggota;
                                    $total_porsi_bmt += $value->porsi_bmt;
                                    $total_persentase_anggota += $value->total_pendapatan > 0 ? $value->porsi_anggota / $value->total_pendapatan * 100 : 0;
                                    $i++;
                                @endphp

                            @endforeach
                            <tr>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td class="text-right">{{number_format(json_decode($total_rata_rata),2)}}</td>
                                <td class="text-right">{{number_format($total_pendapatan,2)}}</td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{number_format($total_porsi_anggota,2)}}</td>
                                <td class="text-right">{{number_format($total_porsi_bmt,2)}}</td>
                                {{-- <td class="text-center">{{round($total_persentase_anggota,2)}}%</td> --}}
                                <td>-</td>
                            </tr>
                        @endforeach

                        </tbody>

                    </table>

                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
    </div>
@endsection

@section('modal')
    @include('modal.distribusi')
@endsection


@section('extra_script')


    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
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
                "processing": false,
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