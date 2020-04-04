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
                           <h4 id="titlePrint" class="title"><b>Buku Besar</b> </h4>
                            <p id="titlePrint2" class="category">Laporan Buku Besar <b>{{isset($data['id_rek'])?$data['id_rek']:""}} {{isset($data['nama_rek'])?$data['nama_rek']:""}}</b></p>
                            <p class="category">Saldo Total : <b>Rp {{isset($data['data'][0]['saldo'])?number_format($data['data'][0]['saldo'],2):""}}</b></p>
                            <br />
                        </div>
                        <div class="toolbar">
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    {{--<h5 class="text-center">Periode Laporan</h5>--}}
                                    <form id="buku_besar" @if(Auth::user()->tipe == "admin") action="{{route('rekening.buku_besar')}}" @else action="{{route('teller.rekening.buku_besar')}}" @endif method="post">
                                        {{ csrf_field() }}
                                        <div align="center">
                                            <div class="row">
                                                <div class="form-group col-md-5">
                                                    <select class="form-control select2" id="idRekS" name="rekening" style="width: 100%;" required>
                                                        <option disabled selected > - Rekening -</option>
                                                        @foreach($rekening as $rek)
                                                            <option value="{{$rek['id']}}"> [{{isset($rek['id_rekening'])?$rek['id_rekening']:""}}] {{isset($rek['nama_rekening'])?$rek['nama_rekening']:""}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-5">
                                                    <select class="form-control select2" id="periode" name="periode" style="width: 100%;" required>
                                                        <option disabled selected > - Periode -</option>
                                                        @foreach($periode as $p)
                                                            <option value="{{$p}}"> {{substr($p,0,4)}} - {{substr($p,4,6)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <button type="submit" class="btn btn-info btn-fill btn-sm"> <i class="pe-7s-search"></i> Search</button>
                                                </div>
                                            </div>
                                            {{--<div class="row">--}}
                                                {{--<div class="col-md-3">--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<label class="control-label">Start Date</label>--}}
                                                        {{--<input class="form-control datepicker"--}}
                                                               {{--style="height: 2em;width:8em"--}}
                                                               {{--type="text"--}}
                                                               {{--id="etgl"--}}
                                                               {{--name="tgl"--}}
                                                               {{--required="true"--}}
                                                        {{--/>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                                {{--<div class="col-md-3">--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<label class="control-label">End Date</label>--}}
                                                        {{--<input class="form-control datepicker"--}}
                                                               {{--style="height: 2em;width:8em"--}}
                                                               {{--type="text"--}}
                                                               {{--id="etgl"--}}
                                                               {{--name="tgl"--}}
                                                               {{--required="true"--}}
                                                        {{--/>--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-8">
                                    {{--<h5 class="text-center">Periode Laporan</h5>--}}
                                    <form id="buku_besar" @if(Auth::user()->tipe == "admin") action="{{route('rekening.buku_besar')}}" @else action="{{route('teller.rekening.buku_besar_')}}" @endif method="post">
                                        {{ csrf_field() }}
                                        <div align="center">
                                            <div class="row">
                                                <div class="form-group col-md-4">
                                                    <label class="control-label">Rekening</label>
                                                    <select class="form-control select2" id="idRekSs" name="rekening" style="width: 100%;" required>
                                                        <option disabled selected > - Rekening -</option>
                                                        @foreach($rekening as $rek)
                                                            <option value="{{$rek['id']}}"> [{{isset($rek['id_rekening'])?$rek['id_rekening']:""}}] {{isset($rek['nama_rekening'])?$rek['nama_rekening']:""}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">Start Date</label>
                                                        <input class="form-control datepicker"
                                                               style="height: 2em;width:7.5em"
                                                               type="text"
                                                               id="etgl"
                                                               name="startdate"
                                                               required="true"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="control-label">End Date</label>
                                                        <input class="form-control datepicker"                                                               style="height: 2em;width:7.5em"
                                                               style="height: 2em;width:7.5em"
                                                               type="text"
                                                               id="etgl"
                                                               name="enddate"
                                                               required="true"
                                                        />
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-2">
                                                        <label class="control-label">Periodik</label>
                                                    <button type="submit" class="btn btn-info btn-fill btn-sm"> <i class="pe-7s-search"></i> Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <span></span>
                        </div>
                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th class="text-center"></th>
                            <th class="text-center" data-sortable="true">Tgl Transaksi</th>
                            <th class="text-center" data-sortable="true" >Nama User</th>
                            <th class="text-center" data-sortable="true" >Dari Rekening</th>
                            <th class="text-center" data-sortable="true">Ke Rekening</th>
                            <th class="text-center" data-sortable="true">Jenis Transaksi</th>
                            <th class="text-center" data-sortable="true">Jumlah</th>
                            <th class="text-center" data-sortable="true">Saldo Awal</th>
                            <th class="text-center" data-sortable="true">Saldo Akhir</th>
                            {{--<th>Actions</th>--}}
                            <th></th>
                            </thead>
                            @if(!isset($data))
                            @else
                            <tbody>
                              @foreach ($data['data'] as $usr)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->created_at }}</td>
                                    <td>{{ $usr->nama_user }}</td>
                                    {{--<td class="text-left text-uppercase">-</td>--}}
                                    @if(json_decode($usr->transaksi,true)['jumlah']<0)
                                        <td class="text-left text-uppercase">[{{ $usr->id_rek }}] {{ $usr->nama }}</td>
                                        <td class="text-left text-uppercase">-</td>
                                    @elseif(json_decode($usr->transaksi,true)['jumlah']>=0)
                                        <td class="text-left text-uppercase">-</td>
                                        <td class="text-left text-uppercase">[{{ $usr->id_rek }}] {{ $usr->nama }}</td>
                                    @endif
                                    <td class="text-center text-uppercase">{{$usr->status}}</td>
                                    @if(json_decode($usr->transaksi,true)['jumlah']<0)
                                        <td class="text-right">({{ number_format(-json_decode($usr->transaksi,true)['jumlah'],2) }})</td>
                                    @elseif(json_decode($usr->transaksi,true)['jumlah']>=0)
                                        <td class="text-right">{{ number_format(json_decode($usr->transaksi,true)['jumlah'],2) }}</td>
                                    @endif

                                    <td class="text-right">{{ number_format(floatval(isset(json_decode($usr->transaksi,true)['saldo_awal'])?json_decode($usr->transaksi,true)['saldo_awal']:0),2) }}</td>
                                    <td class="text-right">{{ number_format(floatval(isset(json_decode($usr->transaksi,true)['saldo_akhir'])?json_decode($usr->transaksi,true)['saldo_akhir']:0),2) }}</td>
                                    <td></td>
                                </tr>

                            @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center text-uppercase"><h5><b>Jumlah Total Bulanan</b>  </h5></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><b>Rp</b></td>
                                    <td class="text-right"><b> {{number_format(isset($data['total'])?$data['total']:0,2)}}</b></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                                    <td></td>
                                </tr>
                            </tbody>
                            @endif
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
        $(document).ready(function() {
            $("#idRekS").select2({
                dropdownParent: $("#buku_besar")
            });
            $("#idRekSs").select2({
                dropdownParent: $("#buku_besar")
            });
            $("#periode").select2({
                dropdownParent: $("#buku_besar")
            });

            lbd.checkFullPageBackgroundImage();

            setTimeout(function(){
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700);
        });
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
                    format: 'YYYY-MM-DD',
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