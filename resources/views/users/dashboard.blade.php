@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection
@section('extra_style')

@endsection
@section('content')
    <div class="content">
        <div class="row">
            <div class="col-md-4">
                <a href="{{ route("anggota.dashboard.harta") }}">
                    <div class="card dashboard link" style="background-color: #8892D6">
                        <div class="card-body">
                            
                            @if(isset($simpanan['wajib']) && isset($simpanan['khusus']) && isset($simpanan['pokok']))
                                <span class="card-title card-number-large" id="harta">{{ number_format($simpanan['wajib'] + $simpanan['pokok'] + $simpanan['khusus'],2)}}</span>
                            @elseif(isset($simpanan['wajib']) && isset($simpanan['pokok']))
                                <span class="card-title card-number-large" id="harta">{{ number_format($simpanan['wajib'] + $simpanan['pokok'],2) }}</span>
                            @elseif(isset($simpanan['wajib']) && isset($simpanan['khusus']))
                                <span class="card-title card-number-large" id="harta">{{ number_format($simpanan['wajib'] + $simpanan['khusus'],2) }}</span>
                            @elseif(isset($simpanan['pokok']) && isset($simpanan['khusus']))
                                <span class="card-title card-number-large" id="harta">{{ number_format($simpanan['pokok'] + $simpanan['khusus'],2) }}</span>
                            @endif

                            <p class="card-category" style="margin-bottom:0">Total Harta Dalam Rekening BMT</p>
                            {{-- <span class="card-description">Bersumber dari simpanan pokok, simpanan wajib & simpanan khusus</span> --}}
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route("anggota.dashboard.tabungan") }}">
                    <div class="card dashboard link" style="background-color: #45BBE0">
                        <div class="card-body">
                            <span class="card-title card-number-large">{{number_format($tab,2)}}</span>
                            <p class="card-category" style="margin-bottom:0">Total Tabungan Anggota</p>
                            {{-- <span class="card-description">Bersumber dari total tabungan semua Anggota</span> --}}
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route("anggota.dashboard.deposito") }}">
                    <div class="card dashboard link" style="background-color: #F06292">
                        <div class="card-body">
                            <span class="card-title card-number-large">{{number_format($deposito,2)}}</span>
                            <p class="card-category" style="margin-bottom:0">Total Mudharabah Berjangka</p>
                            {{-- <span class="card-description">Bersumber dari total simpanan mudharabah berjangka anggota</span> --}}
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route("anggota.dashboard.pembiayaan") }}">
                    <div class="card dashboard link" style="background-color: #78C350">
                        <div class="card-body">
                            <span class="card-title card-number-large">{{number_format($pinjaman,2)}}</span>
                            <p class="card-category" style="margin-bottom:0">Total Pembiayaan</p>
                            {{-- <span class="card-description">Bersumber dari total pembiayaan anggota</span> --}}
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="javascript:void(0)" id="card-shu-user">
                    <div class="card dashboard link" style="background-color: #5066c3">
                        <div class="card-body">
                            <span class="card-title card-number-large">{{number_format($shu_user['shu_user'],2)}}</span>
                            <p class="card-category" style="margin-bottom:0">Total SHU</p>
                            {{-- <span class="card-description">Bersumber dari total pembiayaan anggota</span> --}}
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            {{-- <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-body card-group">
                        <div class="panel panel-primary">
                            <div class="panel-body" style="background-color: #8892D6">
                                <div class="row" style="padding: 0">
                                    <div class="col-sm-3 col-md-3 col-lg-3">
                                        <div class="avatar-icon">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <div class="content" style="justify-content: 'center'">
                                            <span class="content-title">rata-rata setoran anda per bulan</span>
                                            <p class="content-description">Rp. 300,000</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="icon-navigation right">
                                            <a href="#"><i class="fa fa-info-circle" style="color: white"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-body" style="background-color: #45BBE0">
                                <div class="row" style="padding: 0">
                                    <div class="col-sm-3 col-md-3 col-lg-3">
                                        <div class="avatar-icon">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <div class="content" style="justify-content: 'center'">
                                            <span class="content-title">Rata-rata penarikan anda per bulan</span>
                                            <p class="content-description">Rp. 300,000</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="icon-navigation right">
                                            <a href="#"><i class="fa fa-info-circle" style="color: white"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-body" style="background-color: #F06292">
                                <div class="row" style="padding: 0">
                                    <div class="col-sm-3 col-md-3 col-lg-3">
                                        <div class="avatar-icon">
                                            <i class="fas fa-handshake-o"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-7 col-md-7 col-lg-7">
                                        <div class="content" style="justify-content: 'center'">
                                            <span class="content-title">Total ansuran anda (angsuran ke-2)</span>
                                            <p class="content-description">Rp. 300,000</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 col-md-2 col-lg-2">
                                        <div class="icon-navigation right">
                                            <a href="#"><i class="fa fa-info-circle" style="color: white"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <span class="panel-group-description">Laporan keluar masuk tabungan anggota</span>
                    </div>
                </div>
            </div> --}}
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-body card-group">
                        <table class="table bootstrap-table-no-export">
                            <thead>
                                <th>No</th>
                                <th>Jenis Pengajuan</th>
                                <th>Tanggal Dibuat</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                @foreach($pengajuan as $pengajuan)

                                @php
                                    $created = Carbon\Carbon::parse($pengajuan['created_at']);    
                                @endphp

                                <tr>
                                    <td>{{ $pengajuan['id'] }}</td>
                                    <td>{{ $pengajuan['jenis_pengajuan'] }}</td>
                                    <td>{{ $created->format('D, d M Y') }}</td>
                                    <td>{{ $pengajuan['status'] }}</td>
                                </tr>
                                
                                @endforeach
                            </tbody>
                        </table>


                        <span class="panel-group-description">Daftar Pengajuan Terbaru</span>
                    </div>
                </div>
            </div>
        </div>

        
        {{-- <div class="container-fluid">


            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="card card-wizard " style="">
                        <form id="wizardForm" method="" action="#">
                            <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:greenyellow"></i>
                                    <i class="fas fa-adjust fa-stack-1x fa-inverse"></i>
                                </span>
                                <h3 class="title">Rp {{number_format($simpok,2)}}</h3>
                                <p class="category">Total Simpanan Pokok </p>
                            </div>

                            <div class="footer">
                                <!--<button type="button" class="btn btn-fill btn-wd btn-info center-block">Detail Tabungan</button>-->

                                <div class="clearfix"></div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-wizard " style="">
                        <form id="wizardForm" method="" action="#">
                            <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:gold"></i>
                                    <i class="fas fa-money-bill-alt fa-stack-1x fa-inverse"></i>
                                </span>
                                <h3 class="title">Rp {{number_format($simwa,2)}}</h3>
                                <p class="category">Total Simpanan Wajib </p>
                            </div>

                            <div class="footer">
                                <!--<button type="button" class="btn btn-fill btn-wd btn-info center-block">Detail Tagihan</button>-->

                                <div class="clearfix"></div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-2"></div>

            </div>
            <div class="row">

                <div class="col-md-4">
                    <div class="card card-wizard " style="">
                        <form id="wizardForm" method="" action="#">
                            <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:orange"></i>
                                    <i class="fas fa-credit-card fa-stack-1x fa-inverse"></i>
                                </span>
                                <h3 class="title">Rp {{number_format($pinjaman,2)}}</h3>
                                <p class="category">Total Pembiayaan </p>
                            </div>

                            <div class="footer">
                                <!--<button type="button" class="btn btn-fill btn-wd btn-info center-block">Detail Tagihan</button>-->

                                <div class="clearfix"></div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-wizard " style="">
                        <form id="wizardForm" method="" action="#">
                            <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:darkcyan"></i>
                                    <i class="fas fa-credit-card fa-stack-1x fa-inverse"></i>
                                </span>
                                <h3 class="title">Rp {{number_format($bulanan,2)}}</h3>
                                <p class="category">Total Tagihan Bulanan </p>
                            </div>

                            <div class="footer">
                                <!--<button type="button" class="btn btn-fill btn-wd btn-info center-block">Detail Tagihan</button>-->

                                <div class="clearfix"></div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-wizard " style="">
                        <form id="wizardForm" method="" action="#">
                            <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:darkred"></i>
                                    <i class="fas fa-dollar-sign fa-stack-1x fa-inverse"></i>
                                </span>
                                <h3 class="title">Rp {{number_format($tagihan,2)}}</h3>
                                <p class="category">Total Tagihan </p>
                            </div>

                            <div class="footer">
                                <!--<button type="button" class="btn btn-fill btn-wd btn-info center-block">Detail Tagihan</button>-->

                                <div class="clearfix"></div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-4">
                    <div class="card card-wizard " style="">
                        <form id="wizardForm" method="" action="#">
                            <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:darkslateblue"></i>
                                    <i class="fas fa-archive fa-stack-1x fa-inverse"></i>
                                </span>
                                <h3 class="title">Rp {{number_format($tab,2)}}</h3>
                                <p class="category">Total Saldo Tabungan </p>
                            </div>

                            <div class="footer">
                                <!--<button type="button" class="btn btn-fill btn-wd btn-info center-block">Detail Tabungan</button>-->

                                <div class="clearfix"></div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-wizard " style="">
                        <form id="wizardForm" method="" action="#">
                            <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:darkcyan"></i>
                                    <i class="fas fa-bank fa-stack-1x fa-inverse"></i>
                                </span>
                                <h3 class="title">Rp {{number_format($deposito,2)}}</h3>
                                <p class="category">Total Saldo Mudharabah Berjangka </p>
                            </div>

                            <div class="footer">
                                <!--<button type="button" class="btn btn-fill btn-wd btn-info center-block">Detail Tagihan</button>-->

                                <div class="clearfix"></div>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-2"></div>

            </div>
            <!--<div class="row">-->
            <!--    <div class="col-md-4"></div>-->
            <!--    <div class="col-md-4">-->
            <!--        <style>-->
            <!--            .ui.card {-->
            <!--                display: inline-block;-->
            <!--                margin: 10px;-->
            <!--            }-->

            <!--            .ui.card,-->
            <!--            .ui.cards>.card {-->
            <!--                background-color: #5C5D5F;-->
            <!--                color: white;-->
            <!--            }-->

            <!--            .ui.card.matthew {-->
            <!--                background-color: #2B4B64;-->
            <!--            }-->

            <!--            .ui.card.kristy {-->
            <!--                background-color: #253E54;-->
            <!--            }-->

            <!--            .ui.card>.content>a.header,-->
            <!--            .ui.cards>.card>.content>a.header,-->
            <!--            .ui.card .meta,-->
            <!--            .ui.cards>.card .meta,-->
            <!--            .ui.card>.content>.description,-->
            <!--            .ui.cards>.card>.content>.description,-->
            <!--            .ui.card>.extra a:not(.ui),-->
            <!--            .ui.cards>.card>.extra a:not(.ui) {-->
            <!--                color: white;-->
            <!--            }-->
            <!--        </style>-->

            <!--        <div class="ui card matthew">-->
            <!--            <div class="image">-->
        <!--                <img style="" src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['profile'])}}">-->
            <!--            </div>-->
            <!--            <div class="content">-->
        <!--                <a class="header">{{Auth::user()->nama}}</a>-->
            <!--                <div>-->
        <!--                    <span class="date">Joined in {{date_format(Auth::user()->created_at,"Y F d")}}</span>-->
            <!--                </div>-->
            <!--                <div class="description">-->
        <!--                     {{Auth::user()->alamat}}.-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="extra content">-->
            <!--                <a>-->
            <!--                    <i class="fab fa-twitter span">22 Friends</i>-->
            <!--                </a>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="col-md-4"></div>-->
            <!--</div>-->
        </div> --}}
    </div>
@endsection

@section('modal')
    @include('modal.shu_user')
@endsection

@section('extra_script')


    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    <script type="text/javascript">
        var $table = $('#bootstrap-table');

        function operateFormatter(value, row, index) {
            return [
                '<a rel="tooltip" title="View" class="btn btn-simple btn-info btn-icon table-action view" href="javascript:void(0)">',
                '<i class="fa fa-image"></i>',
                '</a>',
                '<a rel="tooltip" title="Edit" class="btn btn-simple btn-warning btn-icon table-action edit" href="javascript:void(0)">',
                '<i class="fa fa-edit"></i>',
                '</a>',
                '<a rel="tooltip" title="Remove" class="btn btn-simple btn-danger btn-icon table-action remove" href="javascript:void(0)">',
                '<i class="fa fa-remove"></i>',
                '</a>'
            ].join('');
        }

        $().ready(function(){

            $('#card-shu-user').click(function(){
                $('#modalShuUser').modal('toggle');
                $('#modalShuUser').modal('show');
            })

            window.operateEvents = {
                'click .view': function (e, value, row, index) {
                    info = JSON.stringify(row);

                    swal('You click view icon, row: ', info);
                    console.log(info);
                },
                'click .edit': function (e, value, row, index) {
                    info = JSON.stringify(row);

                    swal('You click edit icon, row: ', info);
                    console.log(info);
                },
                'click .remove': function (e, value, row, index) {
                    console.log(row);
                    $table.bootstrapTable('remove', {
                        field: 'id',
                        values: [row.id]
                    });
                }
            };

            $table.bootstrapTable({
                toolbar: ".toolbar",
                clickToSelect: true,
                showRefresh: true,
                search: true,
                showToggle: true,
                showColumns: true,
                pagination: true,
                searchAlign: 'left',
                pageSize: 8,
                clickToSelect: false,
                pageList: [8,10,25,50,100],

                formatShowingRows: function(pageFrom, pageTo, totalRows){
                    //do nothing here, we don't want to show the text "showing x of y from..."
                },
                formatRecordsPerPage: function(pageNumber){
                    return pageNumber + " rows visible";
                },
                icons: {
                    refresh: 'fa fa-refresh',
                    toggle: 'fa fa-th-list',
                    columns: 'fa fa-columns',
                    detailOpen: 'fa fa-plus-circle',
                    detailClose: 'fa fa-minus-circle'
                }
            });

            //activate the tooltips after the data table is initialized
            $('[rel="tooltip"]').tooltip();

            $(window).resize(function () {
                $table.bootstrapTable('resetView');
            });


        });

    </script>
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
@endsection
@section('footer')
    @include('layouts.footer')
@endsection