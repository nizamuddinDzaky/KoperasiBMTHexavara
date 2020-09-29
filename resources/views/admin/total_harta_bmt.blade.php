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
                <h4 class="title">Laporan Total Harta BMT</h4>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card">
                        <div class="header text-center">
                            <h4 id="titlePrint3" class="title"><b>Laporan Total Harta BMT</b> </h4>
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <span></span>
                        </div>
                        <table id="bootstrap-table2" class="table">
                            <thead>
                            <th class="text-left">ID</th>
                            <th> Nama </th>
                            <th> Simpanan Pokok</th>
                            <th> Simpanan Wajib</th>
                            <th> Simpanan Khusus</th>
                            <th> Total Harta</th>
                            </thead>
                            <tbody>

                            @foreach($data as $item)
                                <tr>
                                    <td class="text-left">{{ $item['no_ktp'] }}</td>
                                    <td class="text-left">{{ $item['nama']  }}</td>
                                    <td class="text-right">{{number_format(floatval(json_decode($item->wajib_pokok)->pokok),2) }}</td>
                                    <td class="text-right">{{number_format(floatval(json_decode($item->wajib_pokok)->wajib),2) }}</td>
                                    <td class="text-right">{{number_format(floatval(json_decode($item->wajib_pokok)->khusus),2) }}</td>
                                    <td class="text-right"></td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td>TOTAL</td>
                                <td class="text-right">{{number_format(floatval($total_pokok),2) }}</td>
                                <td class="text-right">{{number_format(floatval($total_wajib),2) }}</td>
                                <td class="text-right">{{number_format(floatval($total_khusus),2) }}</td>
                                <td class="text-right" style="font-weight: bold">{{number_format(floatval($total_harta),2) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            </tbody>

                                                </table>
                                            </div><!--  end card  -->
                                        </div>

                        {{--                <div class="card">--}}
{{--                    <div class="card">--}}
{{--                        <div class="header text-center">--}}
{{--                            <h4 id="titlePrint3" class="title"><b>Laporan Total Harta BMT</b> </h4>--}}
{{--                            <br/>--}}
{{--                        </div>--}}
{{--                        <div class="toolbar">--}}
{{--                            <!--        Here you can write extra buttons/actions for the toolbar              -->--}}
{{--                            <span></span>--}}
{{--                        </div>--}}
{{--                        <div class="table-responsive">--}}
{{--                            <table id="bootstrap-table2" class="table">--}}
{{--                                <thead>--}}
{{--                                <th class="text-left">ID</th>--}}
{{--                                <th>Nama</th>--}}
{{--                                <th>Simpanan Pokok</th>--}}
{{--                                <th>Simpanan Wajib</th>--}}
{{--                                <th>Simpanan Khusus</th>--}}
{{--                                <th>Total</th>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                @foreach($data as $item)--}}
{{--                                    <tr>--}}
{{--                                        <td class="text-left">{{ $item['no_ktp'] }}</td>--}}
{{--                                        <td class="text-left">{{ $item['nama']  }}</td>--}}
{{--                                        <td class="text-right">{{number_format(floatval(json_decode($item->wajib_pokok)->pokok),2) }}</td>--}}
{{--                                        <td class="text-right">{{number_format(floatval(json_decode($item->wajib_pokok)->wajib),2) }}</td>--}}
{{--                                        <td class="text-right">{{number_format(floatval(json_decode($item->wajib_pokok)->khusus),2) }}</td>--}}
{{--                                        <td></td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                <tr>--}}
{{--                                    <td></td>--}}
{{--                                    <td></td>--}}
{{--                                    <td class="text-right">{{number_format(floatval($total_pokok),2) }}</td>--}}
{{--                                    <td class="text-right">{{number_format(floatval($total_wajib),2) }}</td>--}}
{{--                                    <td class="text-right">{{number_format(floatval($total_khusus),2) }}</td>--}}
{{--                                    <td class="text-right" style="font-weight: bold">{{number_format(floatval($total_harta),2) }}</td>--}}

{{--                                </tr>--}}
{{--                                </tbody>--}}

{{--                            </table>--}}
{{--                        </div>--}}
{{--                    </div>--}}


{{--                    </div><!--  end card  -->--}}

            </div>
            </div><!--  end card  -->
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
                "processing": true,
//                "dom": 'lBf<"top">rtip<"clear">',
                "order": [],
                "scrollX": true,
                "paging": false,
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
                            title: function () { return  $('#titlePrint3').text()+"\n"+$('#titlePrint4').text(); },
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

            var $validator = $("#wizardForm2").validate({
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