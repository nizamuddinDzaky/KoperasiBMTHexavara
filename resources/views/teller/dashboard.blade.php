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
        <div class="col-md-3">
            <div class="card dashboard link" style="height: 160px; background-color: #8892D6">
                <div class="card-body">
                    <span class="card-title card-number-large" id="harta">{{ number_format($teller->saldo) }}</span>
                    <p class="card-category" style="margin-bottom:0">Total Dana Dalam Kas Anda</p>
                    <span class="card-description">Bersumber dari kas dalam akun teller 1</span>
                    
                    <a href="#"><i class="fa fa-cog card-icon top right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard link" style="height: 160px; background-color: #45BBE0">
                <div class="card-body">
                    <span class="card-title card-number-large">5,750 M</span>
                    <p class="card-category" style="margin-bottom:0">Total Tabungan Nasabah</p>
                    <span class="card-description">Bersumber dari total tabungan semua nasabah</span>
                    
                    <a href="#"><i class="fa fa-cog card-icon top right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard link" style="height: 160px; background-color: #F06292">
                <div class="card-body">
                    <span class="card-title card-number-large">90,000,000</span>
                    <p class="card-category" style="margin-bottom:0">Total Mudharabah Berjangka</p>
                    <span class="card-description">Bersumber dari total simpanan mudharabah berjangka nasabah</span>

                    <a href="#"><i class="fa fa-cog card-icon top right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card dashboard link" style="height: 160px; background-color: #78C350">
                <div class="card-body">
                    <span class="card-title card-number-large">100,000,000</span>
                    <p class="card-category" style="margin-bottom:0">Total Pembiayaan</p>
                    <span class="card-description">Bersumber dari total pembiayaan nasabah</span>

                    <a href="#"><i class="fa fa-cog card-icon top right"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-4 col-lg-4">
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
                                        <span class="content-title">rata-rata pembiayaan anda per bulan</span>
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
        </div>
        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="panel panel-primary">
                <div class="panel-body card-group">
                    <table class="table table-striped">
                        <thead>
                            <th>No</th>
                            <th>Jenis Pengajuan</th>
                            <th>Tanggal Dibuat</th>
                            <th>Status</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Lorem ipsum dolor</td>
                                <td>14-02-2020</td>
                                <td>Dikonfirmasi</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Lorem ipsum dolor</td>
                                <td>14-02-2020</td>
                                <td>Dikonfirmasi</td>
                            </tr><tr>
                                <td>3</td>
                                <td>Lorem ipsum dolor</td>
                                <td>14-02-2020</td>
                                <td>Dikonfirmasi</td>
                            </tr><tr>
                                <td>4</td>
                                <td>Lorem ipsum dolor</td>
                                <td>14-02-2020</td>
                                <td>Dikonfirmasi</td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Lorem ipsum dolor</td>
                                <td>14-02-2020</td>
                                <td>Dikonfirmasi</td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td>Lorem ipsum dolor</td>
                                <td>14-02-2020</td>
                                <td>Dikonfirmasi</td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>Lorem ipsum dolor</td>
                                <td>14-02-2020</td>
                                <td>Dikonfirmasi</td>
                            </tr>
                        </tbody>
                    </table>


                    <span class="panel-group-description">Daftar Pengajuan Terbaru</span>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-wizard " style="">
                           <div class="footer">
                               <br>
                               <h4 for="id_" class="text-center"><strong>{{ $teller->nama  }}</strong></h4>
                               @if($teller->saldo!="")
                                   <h3 for="id_" class="text-center"><strong>Rp {{  number_format($teller->saldo,2)  }}</strong></h3>
                               @else
                                   <h3 for="id_" class="text-center"><strong>Rp {{number_format(0,2)}}</strong></h3>
                               @endif
                                <div class="clearfix"></div>
                            </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-wizard " style="">
                        <form id="wizardForm" method="" action="#">
                            <div class="col-md-4">
                                <div class="header text-center">
                                        <span class="fa-stack fa-3x">
                                            <i class="fas fa-square fa-stack-2x" style="color:darkslateblue"></i>
                                            <i class="fas fa-gear fa-stack-1x fa-inverse"></i>
                                        </span>
                                    <h3 class="title">{{$pending}}</h3>
                                    <p class="category">Belum Diproses</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="header text-center">
                                        <span class="fa-stack fa-3x">
                                            <i class="fas fa-square fa-stack-2x" style="color:darkgreen"></i>
                                            <i class="fas fa-check-square fa-stack-1x fa-inverse"></i>
                                        </span>
                                    <h3 class="title">{{$setuju}}</h3>
                                    <p class="category">Pengajuan Disetujui </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="header text-center">
                                        <span class="fa-stack fa-3x">
                                            <i class="fas fa-square fa-stack-2x" style="color:darkred"></i>
                                            <i class="fas fa-remove fa-stack-1x fa-inverse"></i>
                                        </span>
                                    <h3 class="title">{{$tolak}}</h3>
                                    <p class="category">Pengajuan Ditolak </p>
                                </div>
                            </div>

                            <div class="footer">
                                <button type="button" class="btn btn-fill btn-wd btn-info center-block">Detail Pengajuan</button>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <style>
                        .ui.card {
                            display: inline-block;
                            margin: 10px;
                        }

                        .ui.card,
                        .ui.cards>.card {
                            background-color: #5C5D5F;
                            color: white;
                        }

                        .ui.card.matthew {
                            background-color: #2B4B64;
                        }

                        .ui.card.kristy {
                            background-color: #253E54;
                        }

                        .ui.card>.content>a.header,
                        .ui.cards>.card>.content>a.header,
                        .ui.card .meta,
                        .ui.cards>.card .meta,
                        .ui.card>.content>.description,
                        .ui.cards>.card>.content>.description,
                        .ui.card>.extra a:not(.ui),
                        .ui.cards>.card>.extra a:not(.ui) {
                            color: white;
                        }
                    </style>

                    <div class="ui card matthew">
                        <div class="image">
                            <img style="" src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['profile'])}}">
                        </div>
                        <div class="content">
                            <a class="header">{{Auth::user()->nama}}</a>
                            <div>
                                <span class="date">Joined in {{date_format(Auth::user()->created_at,"Y F d")}}</span>
                            </div>
                            <div class="description">
                                {{Auth::user()->nama}}
                            </div>
                        </div>
                        <div class="extra content">
                            <a>

                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div> --}}
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