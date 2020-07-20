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
            <div class="col-md-2 small-card">
                <a href="#">
                    <div class="card dashboard link" style="background-color: #3097D1">
                        <div class="card-body">
                            <span class="card-title card-number-large" id="harta" style="font-size: 25px;">{{ number_format($total_kekayaan) }}</span>
                            <p class="card-category" style="margin-bottom:0; font-size: 12px;">Total Harta Rekening BMT</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 small-card">
                <a href="{{ route('admin.transaksi.teller_list') }}">
                    <div class="card dashboard link" style="background-color: #8892D6">
                        <div class="card-body">
                            <span class="card-title card-number-large" id="harta" style="font-size: 25px;">{{ number_format($total_kas) }}</span>
                            <p class="card-category" style="margin-bottom:0; font-size: 12px;">Total Kas Dalam Rekening BMT</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 small-card">
                <a href="{{ route('admin.transaksi.tabungan') }}">
                    <div class="card dashboard link" style="background-color: #45BBE0">
                        <div class="card-body">
                            <span class="card-title card-number-large" style="font-size: 25px;">{{ number_format($total_tabungan) }}</span>
                            <p class="card-category" style="margin-bottom:0; font-size: 12px;">Total Tabungan Anggota</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 small-card">
                <a href="{{ route('admin.transaksi.deposito') }}">
                    <div class="card dashboard link" style="background-color: #F06292">
                        <div class="card-body">
                            <span class="card-title card-number-large" style="font-size: 25px;">{{ number_format($total_deposito) }}</span>
                            <p class="card-category" style="margin-bottom:0; font-size: 12px;">Total Mudharabah Berjangka</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-2 small-card">
                <div class="card dashboard link" style="background-color: #78C350">
                    <div class="card-body">
                        <span class="card-title card-number-large" style="font-size: 25px;">{{ number_format($total_pembiayaan) }}</span>
                        <p class="card-category" style="margin-bottom:0; font-size: 12px;">Total Pembiayaan</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <canvas id="myChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <canvas id="myhorizBar"></canvas>
                        </div>
                    </div>
                </div>
        </div> --}}

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jenis Pengajuan</th>
                                <th>Tanggal Pengajuan</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Lorem</td>
                                    <td>Lorem ipsum dolor</td>
                                    <td>14-02-2020</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Lorem</td>
                                    <td>Lorem ipsum dolor</td>
                                    <td>14-02-2020</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Lorem</td>
                                    <td>Lorem ipsum dolor</td>
                                    <td>14-02-2020</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Lorem</td>
                                    <td>Lorem ipsum dolor</td>
                                    <td>14-02-2020</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Lorem</td>
                                    <td>Lorem ipsum dolor</td>
                                    <td>14-02-2020</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Lorem</td>
                                    <td>Lorem ipsum dolor</td>
                                    <td>14-02-2020</td>
                                </tr>
                            </tbody>
                        </table>

                        <span class="panel-group-description">Daftar pengajuan terbaru</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')

    <script src="{{ asset('ChartJS/Chart.bundle.js') }}"></script>
    <script>
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
                datasets: [{
                    label: 'Laporan Perkembangan Pengguna Januari',
                    data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3, 5],
                    backgroundColor: [
                        'transparent'
                    ],
                    borderColor: '#8892D6',
                    borderWidth: 2
                }]
            },
            options: {
                legend: {
                    display: true,
                    align: 'start',
                    position: 'bottom',
                    labels: {
                        boxWidth: 12
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: false
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
        var horiz = document.getElementById('myhorizBar');
        var myChart = new Chart(horiz, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Laporan Perkembangan Pengguna Dalam Setahun',
                    data: [12, 19, 3, 5, 2, 3, 12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        '#8892D6',
                        '#45BBE0',
                        '#F06292',
                        '#78C350',
                        '#8892D6',
                        '#45BBE0',
                        '#F06292',
                        '#78C350',
                        '#8892D6',
                        '#45BBE0',
                        '#F06292',
                        '#78C350',
                    ],
                    borderColor: [
                        '#8892D6',
                        '#45BBE0',
                        '#F06292',
                        '#78C350',
                        '#8892D6',
                        '#45BBE0',
                        '#F06292',
                        '#78C350',
                        '#8892D6',
                        '#45BBE0',
                        '#F06292',
                        '#78C350',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: true,
                    align: 'start',
                    position: 'bottom',
                    labels: {
                        boxWidth: 12
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: false
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
    </script>

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