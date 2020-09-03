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
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Daftar Kolektibilitas</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card">

                    <div class="header text-center">
                        <h4 id="titlePrint" class="title"> Daftar Kolektibilitas</h4>
                        <p id="titlePrint2" class="category">Daftar Kolektibilitas</p>
                        <br />
                    </div>

                    <table id="bootstrap-table" class="table">
                        <thead>
                        <th></th>
                        <th data-sortable="true" class="text-left">ID</th>
                        <th data-sortable="true">Jenis Pembiayaan</th>
                        <th data-sortable="true">Nama Anggota</th>
                        <th data-sortable="true">Total Pinjaman*</th>
                        <th data-sortable="true">Lama Pinjaman</th>
                        <th data-sortable="true">Angsuran Pokok</th>
                        <th data-sortable="true">Sisa Pinjaman</th>
                        <th data-sortable="true">Jatuh Tempo</th>
                        <th data-sortable="true">Keterlambatan Hari)</th>
                        <th data-sortable="true">Status Pembayaran</th>
                        </thead>
                        <tbody>
                        @foreach ($data['data'] as $usr)
                            @if($usr->status_== 0)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->id_pembiayaan }}</td>
                                    <td>{{ $usr->jenis_pembiayaan  }}</td>
                                    <td>{{ $usr->nama   }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['total_pinjaman'],2) }}</td>
                                    <td class="text-center">{{ json_decode($usr->detail,true)['lama_angsuran'] ." Bulan"}}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['angsuran_pokok'],2)  }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['sisa_pinjaman'],2)  }}</td>

                                    <td class="text-center">{{ $usr->tempo }}</td>
                                    <td class="text-center">{{ $usr->hari }}</td>
                                    @if($usr->status_ == 0 )
                                        <td class="text-uppercase text-center">Lancar tanpa tunggakkkan</td>
                                    @elseif($usr->status_ == 1 )
                                        <td class="text-uppercase text-center">Lancar</td>
                                    @elseif($usr->status_ == 2 )
                                        <td class="text-uppercase text-center">Kurang Lancar</td>
                                    @elseif($usr->status_ == 3 )
                                        <td class="text-uppercase text-center">Diragukan</td>
                                    @elseif($usr->status_ == 4 )
                                        <td class="text-uppercase text-center">Macet</td>
                                    @endif
                                </tr>
                            @endif


                        @endforeach

                        @foreach ($data['data'] as $usr)
                            @if($usr->status_== 1)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->id_pembiayaan }}</td>
                                    <td>{{ $usr->jenis_pembiayaan  }}</td>
                                    <td>{{ $usr->nama   }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['total_pinjaman'],2) }}</td>
                                    <td class="text-center">{{ json_decode($usr->detail,true)['lama_angsuran'] ." Bulan"}}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['angsuran_pokok'],2)  }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['sisa_pinjaman'],2)  }}</td>

                                    <td class="text-center">{{ $usr->tempo }}</td>
                                    <td class="text-center">{{ $usr->hari }}</td>
                                    @if($usr->status_ == 0 )
                                        <td class="text-uppercase text-center">Lancar tanpa tunggakkkan</td>
                                    @elseif($usr->status_ == 1 )
                                        <td class="text-uppercase text-center">Lancar</td>
                                    @elseif($usr->status_ == 2 )
                                        <td class="text-uppercase text-center">Kurang Lancar</td>
                                    @elseif($usr->status_ == 3 )
                                        <td class="text-uppercase text-center">Diragukan</td>
                                    @elseif($usr->status_ == 4 )
                                        <td class="text-uppercase text-center">Macet</td>
                                    @endif
                                </tr>
                            @endif


                        @endforeach

                        @foreach ($data['data'] as $usr)
                            @if($usr->status_== 2)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->id_pembiayaan }}</td>
                                    <td>{{ $usr->jenis_pembiayaan  }}</td>
                                    <td>{{ $usr->nama   }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['total_pinjaman'],2) }}</td>
                                    <td class="text-center">{{ json_decode($usr->detail,true)['lama_angsuran'] ." Bulan"}}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['angsuran_pokok'],2)  }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['sisa_pinjaman'],2)  }}</td>

                                    <td class="text-center">{{ $usr->tempo }}</td>
                                    <td class="text-center">{{ $usr->hari }}</td>
                                    @if($usr->status_ == 0 )
                                        <td class="text-uppercase text-center">Lancar tanpa tunggakkkan</td>
                                    @elseif($usr->status_ == 1 )
                                        <td class="text-uppercase text-center">Lancar</td>
                                    @elseif($usr->status_ == 2 )
                                        <td class="text-uppercase text-center">Kurang Lancar</td>
                                    @elseif($usr->status_ == 3 )
                                        <td class="text-uppercase text-center">Diragukan</td>
                                    @elseif($usr->status_ == 4 )
                                        <td class="text-uppercase text-center">Macet</td>
                                    @endif
                                </tr>
                            @endif


                        @endforeach

                        @foreach ($data['data'] as $usr)
                            @if($usr->status_== 3)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->id_pembiayaan }}</td>
                                    <td>{{ $usr->jenis_pembiayaan  }}</td>
                                    <td>{{ $usr->nama   }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['total_pinjaman'],2) }}</td>
                                    <td class="text-center">{{ json_decode($usr->detail,true)['lama_angsuran'] ." Bulan"}}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['angsuran_pokok'],2)  }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['sisa_pinjaman'],2)  }}</td>

                                    <td class="text-center">{{ $usr->tempo }}</td>
                                    <td class="text-center">{{ $usr->hari }}</td>
                                    @if($usr->status_ == 0 )
                                        <td class="text-uppercase text-center">Lancar tanpa tunggakkkan</td>
                                    @elseif($usr->status_ == 1 )
                                        <td class="text-uppercase text-center">Lancar</td>
                                    @elseif($usr->status_ == 2 )
                                        <td class="text-uppercase text-center">Kurang Lancar</td>
                                    @elseif($usr->status_ == 3 )
                                        <td class="text-uppercase text-center">Diragukan</td>
                                    @elseif($usr->status_ == 4 )
                                        <td class="text-uppercase text-center">Macet</td>
                                    @endif
                                </tr>
                            @endif


                        @endforeach

                        @foreach ($data['data'] as $usr)
                            @if($usr->status_== 4)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->id_pembiayaan }}</td>
                                    <td>{{ $usr->jenis_pembiayaan  }}</td>
                                    <td>{{ $usr->nama   }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['total_pinjaman'],2) }}</td>
                                    <td class="text-center">{{ json_decode($usr->detail,true)['lama_angsuran'] ." Bulan"}}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['angsuran_pokok'],2)  }}</td>
                                    <td class="text-right">{{" ". number_format(json_decode($usr->detail,true)['sisa_pinjaman'],2)  }}</td>

                                    <td class="text-center">{{ $usr->tempo }}</td>
                                    <td class="text-center">{{ $usr->hari }}</td>
                                    @if($usr->status_ == 0 )
                                        <td class="text-uppercase text-center">Lancar tanpa tunggakkkan</td>
                                    @elseif($usr->status_ == 1 )
                                        <td class="text-uppercase text-center">Lancar</td>
                                    @elseif($usr->status_ == 2 )
                                        <td class="text-uppercase text-center">Kurang Lancar</td>
                                    @elseif($usr->status_ == 3 )
                                        <td class="text-uppercase text-center">Diragukan</td>
                                    @elseif($usr->status_ == 4 )
                                        <td class="text-uppercase text-center">Macet</td>
                                    @endif
                                </tr>
                            @endif


                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>[LANCAR TANPA TUNGGAKKAN]</strong></td>
                            <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td></td>
                            <td class="text-right"><strong>{{number_format($data['total'][0],2)}}</strong></td>
                            <td></td>
                            <td><strong>PERSENTASE</strong></td>
                            @if(array_sum($data['total']) != 0)
                            <td class="text-right"><strong>{{number_format($data['total'][0]/array_sum($data['total'])*100,2)}}%</strong></td>
                                @else
                            <td class="text-right"><strong>0%</strong></td>
                            @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>[LANCAR]</strong></td>
                            <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td></td>
                            <td class="text-right"><strong>{{number_format($data['total'][1],2)}}</strong></td>
                            <td></td>
                            <td><strong>PERSENTASE</strong></td>
                            @if(array_sum($data['total']) != 0.0)
                            <td class="text-right"><strong>{{number_format($data['total'][1]/array_sum($data['total'])*100,2)}}%</strong></td>
                            @else
                                <td class="text-right"><strong>0%</strong></td>
                            @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>[KURANG LANCAR]</strong></td>
                            <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td></td>
                            <td class="text-right"><strong>{{number_format($data['total'][2],2)}}</strong></td>
                            <td></td>
                            <td><strong>PERSENTASE</strong></td>
                            @if(array_sum($data['total']) != 0.0)
                            <td class="text-right"><strong>{{number_format($data['total'][2]/array_sum($data['total'])*100,2)}}%</strong></td>
                            @else
                                <td class="text-right"><strong>0%</strong></td>
                            @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>[DIRAGUKAN]</strong></td>
                            <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td></td>
                            <td class="text-right"><strong>{{number_format($data['total'][3],2)}}</strong></td>
                            <td></td>
                            <td><strong>PERSENTASE</strong></td>
                            @if(array_sum($data['total']) != 0.0)
                            <td class="text-right"><strong>{{number_format($data['total'][3]/array_sum($data['total'])*100,2)}}%</strong></td>
                                @else
                                <td class="text-right"><strong>0%</strong></td>
                                @endif
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td><strong>[MACET]</strong></td>
                            <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td></td>
                            <td class="text-right"><strong>{{number_format($data['total'][4],2)}}</strong></td>
                            <td></td>
                            <td><strong>PERSENTASE</strong></td>
                            @if(array_sum($data['total']) != 0.0)
                            <td class="text-right"><strong>{{number_format($data['total'][4]/array_sum($data['total'])*100,2)}}%</strong></td>
                                @else
                                <td class="text-right"><strong>0%</strong></td>
                                @endif
                        </tr>
                        </tbody>
                    </table>

                </div><!--  end card  -->
            </div> <!-- end row -->
        </div>
    </div>
    {{-- @include('modal.pengajuan') --}}
@endsection

    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('#blockRekModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            var status = button.data('status');
            if(status=="blocked"){
                status ="active";
                $('#blockRekLabel').text("Activasi Rekening : " + nama);
                $('#btn_block').hide();
            }
            else if(status=="active"){
                status ="blocked";
                $('#blockRekLabel').text("Blokir Rekening : " + nama);
                $('#btn_active').hide();
            }
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_block').val(id);
            $('#tipeRek').val("Pembiayaan");
            $('#st_block').val(status);
            $('#toBlock').text(nama + "?");
        });
    </script>

    <script type="text/javascript">
        var $table = $('#bootstrap-table');


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
                "scrollX": true,
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

        function onFinishWizard(){
            //here you can do something, sent the form to server via ajax and show a success message with swal

            swal("Data disimpan!", "Terima kasih telah melengkapi data diri anda!", "success");
        }
    </script>

@endsection
@section('footer')
    @include('layouts.footer')
@endsection