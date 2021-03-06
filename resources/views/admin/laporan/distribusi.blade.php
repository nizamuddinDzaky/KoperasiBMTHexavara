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
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.distribusi')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                            @foreach($periode as $item)
                                <option value="{{$item->year}}-{{$item->month}}">{{$item->year}} - {{$item->month}}</option>
                                @endforeach
                        </select>
                        <button type="submit" class="btn btn-info btn-fill btn-sm"> <i class="pe-7s-search"></i> Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                @if($status)
                    <div class="alert alert-success text-center">
                        <span><b>Distribusi Pendapatan telah dilakukan</b> !</span>
                    </div>
                @else
                    <div class="alert alert-danger text-center">
                        <span><b>Distribusi Pendapatan belum dilakukan</b> !</span>
                    </div>
                @endif
                <div class="card">

                    <div class="header text-center">
                        <h4 id="titlePrint" class="title"><b>Distribusi Pendapatan Revenue Sharing</b> </h4>
                        @if(isset($periode_status))
                            <p id="titlePrint2" class="category">Laporan Distribusi Pendapatan Revenue Sharing periode {{$periode_status[0]}} {{$periode_status[1]}}</p>
                        @else
                            <p id="titlePrint2" class="category">Laporan Distribusi Pendapatan Revenue Sharing periode {{date("F Y")}}</p>
                            @endif
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

                        @php
                            $i = 1;
                            $total_pendapatan = 0;
                            $total_rata_rata = 0;
                            $total_porsi_anggota = 0;
                            $total_porsi_bmt = 0;
                            $total_persentase_anggota = 0;
                        @endphp
                        @foreach($data as $item)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{ $item['jenis_rekening'] }}</td>
                                <td class="text-right">{{number_format($item['rata_rata'],2)}}</td>
                                <td class="text-right">{{ number_format($item['pendapatan_product'],2) }}</td>
                                <td class="text-center">{{ $item['nisbah_anggota'] }}</td>
                                <td class="text-center">{{ $item['nisbah_bmt'] }}</td>
                                <td class="text-center">{{ number_format($item['porsi_anggota'],2)  }}</td>
                                <td class="text-center">{{ number_format($item['porsi_bmt'],2) }}</td>
                                <td class="text-center">{{ $item['rata_rata'] > 0 ? round($item['porsi_anggota'] / $item['rata_rata'] * 100, 2) : 0.00 }}%</td>
                            </tr>

                            @php
                                $total_pendapatan = $item['total_pendapatan'];
                                $total_rata_rata = $item['total_rata_rata'];
                                $total_porsi_anggota += $item['porsi_anggota'];
                                $total_porsi_bmt += $item['porsi_bmt'];
                                $total_persentase_anggota += $item['total_pendapatan'] > 0 ? $item['porsi_anggota'] / $item['total_pendapatan'] * 100 : 0;
                                $i++;
                            @endphp

                        @endforeach
                        {{-- <tr>
                            <td>{{$j+$i+1}}</td>
                            <td>KEKAYAAN</td>
                            <td class="text-right">{{number_format($data['kekayaan'],2)}}</td>
                            <td class="text-right">{{ number_format(($data['kekayaan']/$data['total']*$data['pendapatan']),2) }}</td>
                            <td>{{0}}</td>
                            <td>{{100}}</td>
                            <td></td>
                            <td class="text-right">{{number_format(($data['kekayaan']/$data['total']*$data['pendapatan'])*100,2) }}</td>
                            <td>-</td>
                        </tr>--}}


                        <tr>
                            <td></td>
                            <td><b>TOTAL</b></td>
                            <td class="text-right">{{number_format($total_rata_rata,2)}}</td>
                            <td class="text-right">{{number_format($total_pendapatan,2)}}</td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{number_format($total_porsi_anggota,2)}}</td>
                            <td class="text-right">{{number_format($total_porsi_bmt,2)}}</td>
                            {{-- <td class="text-center">{{round($total_persentase_anggota,2)}}%</td> --}}
                            <td>-</td>
                        </tr>

                        </tbody>

                    </table>

                </div><!--  end card  -->
                    <div class="card">

                        <div class="header text-center">

                            <h4 id="titlePrint3" class="title"><b>Distribusi Pendapatan Profit Sharing</b> </h4>
                            @if(isset($periode_status))
                                <p id="titlePrint4" class="category">Laporan Distribusi Pendapatan Profit Sharing periode {{$periode_status[0]}} {{$periode_status[1]}}</p>
                            @else
                                <p id="titlePrint4" class="category">Laporan Distribusi Pendapatan Profit Sharing periode {{date("F Y")}}</p>
                            @endif
                            <br />
                        </div>

                        <table id="bootstrap-table2" class="table">
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

                            @php
                                $i = 1;
                                $total_pendapatan = 0;
                                $total_rata_rata = 0;
                                $total_porsi_anggota = 0;
                                $total_porsi_bmt = 0;
                                $total_persentase_anggota = 0;
                            @endphp
                            @foreach($data_revenue as $item)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{ $item['jenis_rekening'] }}</td>
                                    <td class="text-right">{{number_format($item['rata_rata'],2)}}</td>
                                    <td class="text-right">{{ number_format($item['pendapatan_product'],2) }}</td>
                                    <td class="text-center">{{ $item['nisbah_anggota'] }}</td>
                                    <td class="text-center">{{ $item['nisbah_bmt'] }}</td>
                                    <td class="text-center">{{ number_format($item['porsi_anggota'],2)  }}</td>
                                    <td class="text-center">{{ number_format($item['porsi_bmt'],2) }}</td>
                                    <td class="text-center">{{ $item['rata_rata'] > 0 ? round($item['porsi_anggota'] / $item['rata_rata'] * 100, 2) : 0.00 }}%</td>
                                </tr>

                                @php
                                    $total_pendapatan = $item['total_pendapatan'];
                                    $total_rata_rata = $item['total_rata_rata'];
                                    $total_porsi_anggota += $item['porsi_anggota'];
                                    $total_porsi_bmt += $item['porsi_bmt'];
                                    $total_persentase_anggota += $item['total_pendapatan'] > 0 ? $item['porsi_anggota'] / $item['total_pendapatan'] * 100 : 0;
                                    $i++;
                                @endphp

                            @endforeach
                            {{-- <tr>
                                <td>{{$j+$i+1}}</td>
                                <td>KEKAYAAN</td>
                                <td class="text-right">{{number_format($data['kekayaan'],2)}}</td>
                                <td class="text-right">{{ number_format(($data['kekayaan']/$data['total']*$data['pendapatan']),2) }}</td>
                                <td>{{0}}</td>
                                <td>{{100}}</td>
                                <td></td>
                                <td class="text-right">{{number_format(($data['kekayaan']/$data['total']*$data['pendapatan'])*100,2) }}</td>
                                <td>-</td>
                            </tr>--}}


                            <tr>
                                <td></td>
                                <td><b>TOTAL</b></td>
                                <td class="text-right">{{number_format($total_rata_rata,2)}}</td>
                                <td class="text-right">{{number_format($total_pendapatan,2)}}</td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{number_format($total_porsi_anggota,2)}}</td>
                                <td class="text-right">{{number_format($total_porsi_bmt,2)}}</td>
                                {{-- <td class="text-center">{{round($total_persentase_anggota,2)}}%</td> --}}
                                <td>-</td>
                            </tr>

                            </tbody>

                        </table>

                    </div>
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
            $('#bootstrap-table2').dataTable({
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
                            title: function () { return  $('#titlePrint3').text()+"\n"+$('#titlePrint2').text(); },
                        },

                        'copyHtml5',
                        {
                            extend: 'excelHtml5',
                            messageTop: function () { return  $('#titlePrint3').text(); },
                            messageTop: function () { return  $('#titlePrint4').text(); },
                        },
                        {
                            extend:'pdfHtml5',
                            title: function () { return  $('#titlePrint3').text()+"\n"+$('#titlePrint4').text(); },
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