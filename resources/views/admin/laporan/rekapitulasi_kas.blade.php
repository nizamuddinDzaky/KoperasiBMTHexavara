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
                           <h4 class="title"><b>Rekapitulasi Laporan Kas & Bank</b> </h4>
                            <p class="category">Laporan Kas Tunai</p>
                                <br />
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <span></span>
                        </div>

                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th></th>
                            <th data-sortable="true" class="text-left">ID</th>
                            <th data-sortable="true" class="text-center">Status</th>

                            <th data-sortable="true">Keterangan</th>
                            <th data-sortable="true">Tgl Transaksi</th>
                            <th data-sortable="true">Jumlah</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                                <td class="text-center text-uppercase"><h5><b> KAS Tunai</b>  </h5></td>
                                <td></td>

                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @foreach ($data as $usr)
                                <tr>
                                    <td></td>
                                    <td class="text-left">{{ $usr->id }}</td>
                                    <td class="text-center text-uppercase">{{ $usr->status }}</td>
                                    {{--<td class="text-left">{{ json_decode($usr->transaksi,true)['keterangan']  }}</td>--}}

                                    <td class="text-left">[{{$usr->id_bmt}}] {{ $usr->nama }}</td>
                                    <td class="text-center">{{$usr->created_at }}</td>
                                    @if(json_decode($usr->transaksi,true)['jumlah']>0)
                                    <td class="text-right">Rp {{number_format(json_decode($usr->transaksi,true)['jumlah'],2) }}</td>
                                    @else
                                    <td class="text-right">Rp ({{number_format(abs(json_decode($usr->transaksi,true)['jumlah']),2) }})</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td class="text-center text-uppercase"><h6><b>Jumlah KAS TUNAI</b>  </h6></td>
                                <td></td>
                                @if($tunai>0)
                                <td class="text-right"><b>Rp {{number_format($tunai,2) }}</b></td>
                                @else
                                <td class="text-right"><b>Rp ({{number_format(abs($tunai),2) }})</b></td>
                                @endif
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-center text-uppercase"><h5><b> KAS Bank</b>  </h5></td>
                                <td></td>

                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @foreach ($data2 as $usr)
                                <tr>
                                    <td></td>
                                    <td class="text-left">{{ $usr->id }}</td>
                                    <td class="text-center text-uppercase">{{ $usr->status }}</td>
                                    {{--<td class="text-left">{{ json_decode($usr->transaksi,true)['keterangan']  }}</td>--}}

                                    <td class="text-left">[{{$usr->id_bmt}}] {{ $usr->nama }}</td>
                                    <td class="text-center">{{$usr->created_at }}</td>
                                    @if(json_decode($usr->transaksi,true)['jumlah']>0)
                                    <td class="text-right">Rp {{number_format(json_decode($usr->transaksi,true)['jumlah'],2) }}</td>
                                    @else
                                    <td class="text-right">Rp ({{number_format(abs(json_decode($usr->transaksi,true)['jumlah']),2) }})</td>
                                    @endif
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>

                                <td class="text-center text-uppercase"><h6><b>Jumlah KAS Bank</b>  </h6></td>
                                <td></td>
                                @if($bank>0)
                                <td class="text-right"><b>Rp {{number_format($bank,2) }}</b></td>
                                @else
                                <td class="text-right"><b>Rp ({{number_format(abs($bank),2) }})</b></td>
                                @endif
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-center text-uppercase"><h5><b>Jumlah TOTAL KAS </b>  </h5></td>

                                <td></td>
                                <td></td>
                                @if($bank+$tunai>0)
                                    <td class="text-right"><b>Rp {{number_format($bank+$tunai,2) }}</b></td>
                                @else
                                    <td class="text-right"><b>Rp ({{number_format(abs($bank+$tunai),2) }})</b></td>
                                @endif
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
                "paging": false,
                "scrollX": false,
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