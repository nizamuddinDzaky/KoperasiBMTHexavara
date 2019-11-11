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
    <div class="content">
        <div class="container-fluid">
            <div class="col-md-4">
                <div class="row-md-6">
                    <div class="col-md-12">
                        <div class="card card-wizard" style="">
                            <form id="wizardForm" method="" action="#">
                                <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:darkcyan"></i>
                                    <i class="fas fa-archive fa-stack-1x fa-inverse"></i>
                                </span>
                                    <h3 class="title">Tabungan</h3>
                                    <p class="category">Pembukaan Rekening Tabungan</p>
                                </div>

                                <div class="footer">
                                    <button type="button" class="btn btn-fill btn-wd btn-info center-block" data-toggle="modal" data-target="#openTabModal">Tabungan</button>
                                    <div class="clearfix"></div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="row-md-6">
                    <div class="col-md-12">
                        <div class="card card-wizard " style="">
                            <form id="wizardForm" method="" action="#">
                                <div class="header text-center">
                                <span class="fa-stack fa-3x">
                                    <i class="fas fa-square fa-stack-2x" style="color:darkslateblue"></i>
                                    <i class="fas fa-newspaper fa-stack-1x fa-inverse"></i>
                                </span>
                                    <h3 class="title">Transfer</h3>
                                    <p class="category">Transfer antar Rekening </p>
                                </div>

                                <div class="footer">
                                    <button type="button" class="btn btn-fill btn-wd btn-info center-block">Transfer</button>
                                    <div class="clearfix"></div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">

                            <div class="header text-center">
                                <h4 class="title">Daftar Rekening Tabungan </h4>
                                <p class="category">berikut ini adalah daftar rekening tabungan anda</p>
                                <br />
                            </div>
                            <div class="toolbar">
                                <!--        Here you can write extra buttons/actions for the toolbar              -->
                                <span></span>
                            </div>

                            <table id="bootstrap-table" class="table">
                                <thead>
                                <th></th>
                                <th data-field="id" data-sortable="true" class="text-left">ID</th>
                                <th data-field="nama" data-sortable="true">Jenis Pengajuan</th>
                                <th data-field="alamat" data-sortable="true">Keterangan</th>
                                <th data-field="jenis" data-sortable="true">Tgl Pengajuan</th>
                                <th data-field="registrasi" data-sortable="true">Status</th>
                                <th>Actions</th>
                                </thead>
                                <tbody>
                                @foreach ($data as $usr)
                                    <tr>
                                        <td></td>
                                        <td>{{ $usr->id }}</td>
                                        <td>{{ $usr->jenis_pengajuan   }}</td>
                                        <td>{{ json_decode($usr->detail,true)['keterangan'] }}</td>
                                        <td>{{ $usr->created_at }}</td>
                                        <td>{{ $usr->status }}</td>

                                        <td class="td-actions text-center">
                                            <button type="button" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#editPassUsrModal" title="Ubah Password"
                                                    data-id      = "{{$usr->no_ktp}}"
                                                    data-nama    = "{{$usr->nama}}">
                                                <i class="fa fa-key"></i>
                                            </button>

                                            <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editUsrModal" title="Edit"
                                                    data-id      = "{{$usr->no_ktp}}"
                                                    data-nama    = "{{$usr->nama}}"
                                                    data-alamat    = "{{$usr->alamat}}"
                                                    data-tipe    = "{{$usr->tipe}}"
                                                    data-p    = "{{$usr->password}}">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delUsrModal" title="Delete"
                                                    data-id         = "{{$usr->no_ktp}}"
                                                    data-nama       = "{{$usr->nama}}">
                                                <i class="fa fa-remove"></i>
                                            </button>
                                            {{--<a class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#deleteUsrModal" title="Delete">--}}
                                            {{--<i class="fa fa-remove"></i>--}}
                                            {{--</a>--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div><!--  end card  -->
                    </div> <!-- end col-md-12 -->
                </div> <!-- end row -->
            </div>
        </div>
    </div>
    @include('modal.pengajuan')
@endsection

@section('extra_script')


    <!--  Plugin for Date Time Picker and Full Calendar Plugin-->

@section('extra_script')
    {{-- MODAL&DATATABLE --}}

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $('#editOrgModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var idd = button.data('idd');
            var idOrg = button.data('idorg');
            var namaOrg = button.data('namaorg');
            var tipeOrg = button.data('tipeorg');
            var alamatOrg = button.data('alamatorg');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_edit').val(idd);
            $('#idOrg').val(idOrg);
            $('#namaOrg').val(namaOrg);
            $('#tipeOrg').val(tipeOrg);
            $('#alamatOrg').val(alamatOrg);
            if(tipeOrg===2)
                $('#editOrgLabel').text("Edit Organisasi: Area " + namaOrg);
            else if(tipeOrg===3)
                $('#editOrgLabel').text("Edit Organisasi: Rayon " + namaOrg);
        });
    </script>

    <script type="text/javascript">
        var $table = $('#bootstrap-table');


        $().ready(function(){

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
    <script type="text/javascript">
        url_add = "{{route('admin.datamaster.pembiayaan.add_pembiayaan')}}";
        url_edit = "{{route('admin.datamaster.pembiayaan.edit_pembiayaan')}}";
        url_delete = "{{route('admin.datamaster.pembiayaan.delete_pembiayaan')}}";
    </script>

    <script type="text/javascript">
        $().ready(function(){
            var selAr = $('#toHide');
            var selAr2 = $('#toHide2');
            selAr.hide();
            selAr2.hide();
            var selTip = $('#atasnama');
            selTip.on('change', function () {
                if(selTip .val() == 1) {
                    selAr.show();selAr2.hide();
                }
                else {
                    selAr2.show();selAr.hide();
                }
            });

            $("#rekAkad").select2({
                dropdownParent: $("#openTabModal")
            });
            $("#rekTab").select2({
                dropdownParent: $("#openTabModal")
            });

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