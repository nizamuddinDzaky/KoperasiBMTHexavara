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
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="header text-center">
                           <h4 id="titlePrint" class="title"><b>Laporan Laba/Rugi</b> </h4>
                            <p id="titlePrint2"  class="category">Laporan Pendapatan periode {{date("F Y")}}</p>
                                <br />
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <form action="{{route('periode.labarugi')}}" method="post">
                                {{ csrf_field() }}
                                <div align="center">
                                    <select required  name="periode" class="select pull-center" style="height: 1.9em">
                                        <option disabled selected > - Periode -</option>
                                        @foreach($periode as $p)
                                            <option value="{{ $p }}"> {{substr($p,0,4)}} - {{substr($p,4,6)}}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-info btn-fill btn-sm"> <i class="pe-7s-search"></i> Search</button>

                                </div>
                            </form> <span></span>
                            <span></span>
                        </div>

                        <table id="bootstrap-table" class="table ">
                            <thead>
                                <th class="text-left">ID</th>
                                <th> Keterangan</th>
                                <th> Jumlah</th>
                            </thead>
                            <tbody>
                            @foreach ($data as $usr)
                                <tr>
                                    @if( $usr->tipe_rekening =="detail" && number_format(floatval($usr->saldo))!=0)
                                    <td class="text-left">{{ $usr->id_bmt }}</td>
                                    <td class="text-left">
                                        @for ($i=0; $i<($usr->point) ;$i++)
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endfor
                                        {{ $usr->nama  }}</td>
                                    @if($usr->tipe_rekening =="detail")
                                        <td class="text-right">Rp {{number_format(floatval($usr->saldo),2) }}</td>
                                    @else <td></td>
                                    @endif
                                    
                                     @elseif( $usr->tipe_rekening !="detail")
                                    <td class="text-left">{{ $usr->id_bmt }}</td>
                                    <td class="text-left">
                                        @for ($i=0; $i<($usr->point) ;$i++)
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endfor
                                        {{ $usr->nama  }}</td>
                                    <td></td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td class="text-center text-uppercase"><h6><b>Jumlah Pendapatan</b>  </h6></td>
                                <td class="text-right"><b>Rp {{number_format($laba,2)}}</b></td>
                            </tr>

                            @foreach ($data2 as $usr)
                                <tr>
                                    @if( $usr->tipe_rekening =="detail" && number_format(floatval($usr->saldo))!=0)
                                    <td class="text-left">{{ $usr->id_bmt }}</td>
                                    <td class="text-left">
                                        @for ($i=0; $i<($usr->point) ;$i++)
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endfor
                                        {{ $usr->nama  }}</td>
                                    @if($usr->tipe_rekening =="detail")
                                        <td class="text-right">Rp {{number_format(floatval($usr->saldo),2) }}</td>
                                    @else <td></td>
                                    @endif
                                    
                                     @elseif( $usr->tipe_rekening !="detail")
                                    <td class="text-left">{{ $usr->id_bmt }}</td>
                                    <td class="text-left">
                                        @for ($i=0; $i<($usr->point) ;$i++)
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        @endfor
                                        {{ $usr->nama  }}</td>
                                    <td></td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td class="text-center text-uppercase"><h6>Jumlah Biaya  </h6></td>
                                <td class="text-right"><b>Rp {{number_format($rugi,2)}}</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-center text-uppercase"><h5><b>Laba/Rugi</b>  </h5></td>
                                <td class="text-right"><b>Rp {{number_format($laba-$rugi,2)}}</b></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>

                        </table>

                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->
        </div>
    </div>
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
                "processing": true,
//                "dom": 'lBf<"top">rtip<"clear">',
                "order": [],
                "scrollX": false,
                "dom": 'lBfrtp',
                "paging": false,
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
//                pagination: true,
//                searchAlign: 'left',
//                pageSize: 100,
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