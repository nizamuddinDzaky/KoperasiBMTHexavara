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
                    @if($status == true)
                        <div class="alert alert-success text-center">
                            <span><b>Pembagian SHU Akhir Tahun telah dilakukan</b> !</span>
                        </div>
                    @else
                        <div class="alert alert-danger text-center">
                            <span><b>Pembagian SHU Akhir Tahun belum dilakukan</b> !</span>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card">
                            <div class="header text-center">
                                <h4 id="titlePrint" class="title"><b>Laporan SHU</b> </h4>
                                <p id="titlePrint2" class="category">Laporan Pembagian SHU Akhir Tahun periode @if($status == true){{date("Y")}} @else {{date("Y")-1}}@endif</p>
                                <br />
                            </div>
                            <div class="toolbar">
                                {{--<form action="{{route('distribusi.shu')}}" method="post">--}}
                                    {{ csrf_field() }}
                                {{--</form>--}}
                              <form action="{{route('delete.shu')}}" method="post">
                                    {{ csrf_field() }}
                                    @if($status == false)
                                    <button type="button" class="btn btn-primary btn-fill" style="margin-bottom:1em;margin-left:1em" data-toggle="modal" data-target="#distribusiSHUModal" title="Distribusi Pendapatan">Distribusi SHU
                                    {{--<button type="submit" class="btn btn-primary btn-fill" style="margin-bottom:1em;margin-left:1em"  title="Distribusi Pendapatan">Distribusi SHU--}}
                                    <i class="pe-7s-add-user"></i>
                                    </button>
                                    @else
                                        <button type="submit" class="btn btn-primary btn-fill" style="margin-bottom:1em;margin-left:1em"  title="Hapus Distribusi Pendapatan">Hapus Distribusi SHU
                                            <i class="pe-7s-add-user"></i>
                                        </button>
                                    @endif
                                </form>

                                <!--        Here you can write extra buttons/actions for the toolbar              -->
                                <span></span>
                            </div>
                            <table id="bootstrap-table" class="table">
                                <thead>
                                <th class="text-left">ID</th>
                                <th> Keterangan</th>
                                <th> Persentase</th>
                                <th> Jumlah</th>
                                </thead>
                                <tbody>
                                @for ($i=0;$i<count($data['shu']);$i++)
                                    <tr>
                                        <td class="text-left">{{ $data['shu'][$i]->id }}</td>
                                        <td class="text-left">{{ $data['shu'][$i]->nama_shu  }}</td>
                                        <td class="text-center">{{ $data['shu'][$i]->persentase*100  }}%</td>
                                        <td class="text-right">Rp {{number_format(floatval($data['array_shu'][$i]),2) }}</td>
                                    </tr>
                                @endfor
                                <tr>
                                    <td></td>
                                    <td class="text-center text-uppercase"><h5>Jumlah SHU Yang Harus Dibagikan  </h5></td>
                                    <td></td>
                                    <td class="text-right">Rp {{number_format($data['total'],2)}}</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                </tr>
                                </tbody>

                            </table>
                        </div><!--  end card  -->
                    </div><!--  end card  -->

                    <div class="card">
                        <div class="card">
                            <div class="header text-center">
                                <h4 id="titlePrint3" class="title"><b>Laporan SHU Nasabah</b> </h4>
                                <p id="titlePrint4" class="category">Laporan Pembagian SHU Akhir Tahun periode @if($status == true){{date("Y")}} @else {{date("Y")-1}}@endif</p>
                                <br />
                            </div>
                            <div class="toolbar">
                                <!--        Here you can write extra buttons/actions for the toolbar              -->
                                <span></span>
                            </div>
                            <table id="bootstrap-table2" class="table">
                                <thead>
                                <th class="text-left">ID</th>
                                <th> Nama </th>
                                <th> Simpanan Wajib</th>
                                <th> Simpanan Pokok</th>
                                <th> Margin</th>
                                <th> SHU Pengelolah</th>
                                <th> SHU Pengurus</th>
                                <th> SHU Anggota</th>
                                <th> Total Pendapatan SHU</th>
                                </thead>
                                <tbody>
                                @for ($i=0;$i<count($data['user']);$i++)
                                    <tr>
                                        <td class="text-left">{{ $data['user'][$i]->no_ktp }}</td>
                                        <td class="text-left">{{ $data['user'][$i]->nama  }}</td>
                                        <td class="text-right">{{number_format(floatval(json_decode($data['user'][$i]['penyimpanan'],true)['wajib']),2) }}</td>
                                        <td class="text-right">{{number_format(floatval(json_decode($data['user'][$i]['penyimpanan'],true)['pokok']),2) }}</td>
                                        <td class="text-right">{{number_format(floatval(isset(json_decode($data['user'][$i]['penyimpanan'],true)['margin'])?json_decode($data['user'][$i]['penyimpanan'],true)['margin']:0),2) }}</td>
                                        <td class="text-right">{{number_format(floatval($data['user'][$i]['saldo_olah']),2) }}</td>
                                        <td class="text-right">{{number_format(floatval($data['user'][$i]['saldo_urus']),2) }}</td>
                                        <td class="text-right">{{number_format(floatval($data['user'][$i]['saldo_ang']),2) }}</td>
                                        <td class="text-right">{{number_format(floatval($data['user'][$i]['total']),2) }}</td>
                                    </tr>
                                @endfor
                                <tr>
                                    <td></td>
                                    <td class="text-center text-uppercase"><h5>Total SHU Nasabah </h5></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td  class="text-right">Rp</td>
                                    <td class="text-right"> {{number_format($data['total_usr'],2)}}</td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                    </div><!--  end card  -->

                </div><!--  end card  -->
            </div> <!-- end row -->
        </div>
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