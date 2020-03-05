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
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>

                <div class="button-group right">
                    @if($status == false)
                        <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#distribusiModal"><i class="fa fa-share-square"></i> Distribusi Pendapatan</button>
                    @else
                    <button class="btn btn-danger rounded right shadow-effect" onclick="document.getElementById('distribusi-form').submit()"><i class="fa fa-trash"></i> Hapus Distribusi Pendapatan</button>
                    @endif

                    <form action="{{route('delete.pendapatan')}}" method="post" id="distribusi-form">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                @if($status == true)
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
                                <th rowspan="2"> % Nasabah</th>
                            </tr>
                            <tr>
                                <th>Nasabah</th>
                                <th>BMT</th>
                                <th>Nasabah</th>
                                <th>BMT</th>
                            </tr>
                            <tr>
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                                <th>E</th>
                                <th>F</th>
                                <th>G</th>
                                <th>H</th>
                                <th>I</th>
                            </tr>
                        </thead>
                        <tbody>
                        @for($i=0;$i <count($data['tabungan']);$i++)
                            <tr>
                                <td>{{$i+1}}</td>
                                <td>{{$data['tabungan'][$i]->nama_rekening}}</td>
                                <td class="text-right">{{number_format($data['tabungan'][$i]->saldo,2)}}</td>
                                <td class="text-right">{{ number_format($data['tabungan'][$i]['D'],2) }}</td>
                                <td>{{json_decode($data['tabungan'][$i]->detail,true)['nisbah_anggota']}}</td>
                                <td>{{json_decode($data['tabungan'][$i]->detail,true)['nisbah_bank']}}</td>
                                <td class="text-right">{{ number_format($data['tabungan'][$i]['G'],2)  }}</td>
                                <td class="text-right">{{ number_format($data['tabungan'][$i]['H'],2) }}</td>
                                <td>-</td>
                            </tr>
                        @endfor
                        @for($j=0;$j <count($data['deposito']);$j++)
                            <tr>
                                <td>{{$j+1+$i}}</td>
                                <td>{{$data['deposito'][$j]->nama_rekening}}</td>
                                <td class="text-right">{{number_format($data['deposito'][$j]->saldo,2)}}</td>
                                <td class="text-right">{{ number_format($data['deposito'][$j]['D'],2) }}</td>
                                <td>{{json_decode($data['deposito'][$j]->detail,true)['nisbah_anggota']}}</td>
                                <td>{{json_decode($data['deposito'][$j]->detail,true)['nisbah_bank']}}</td>
                                <td class="text-right">{{ number_format($data['deposito'][$j]['G'],2)  }}</td>
                                <td class="text-right">{{ number_format($data['deposito'][$j]['H'],2) }}</td>
                                <td>-</td>
                            </tr>
                        @endfor
                        <tr>
                            <td>{{$j+$i+1}}</td>
                            <td>KEKAYAAN</td>
                            <td class="text-right">{{number_format($data['kekayaan'],2)}}</td>
                            <td class="text-right">{{ number_format(($data['kekayaan']/$data['total']*$data['pendapatan']),2) }}</td>
                            <td>{{0}}</td>
                            <td>{{100}}</td>
                            <td></td>
                            <td class="text-right">{{number_format(($data['kekayaan']/$data['total']*$data['pendapatan'])*100,2) }}</td>
                            <td>-</td>
                        </tr>


                        <tr>
                            <td></td>
                            <td>TOTAL</td>
                            <td class="text-right">{{number_format($data['total'],2)}}</td>
                            <td class="text-right">{{number_format($data['pendapatan'],2)}}</td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{number_format($data['nasabah'],2)}}</td>
                            <td class="text-right">{{number_format($data['bmt'],2)}}</td>
                            <td>-</td>
                        </tr>

                        </tbody>

                    </table>

                </div><!--  end card  -->
            </div> <!-- end col-md-12 -->
        </div> <!-- end row -->
    </div>

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