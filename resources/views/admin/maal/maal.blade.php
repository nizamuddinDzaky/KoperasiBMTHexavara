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
		.select2-selection__rendered {
			line-height: 36px !important;
		}
		.select2-selection {
			height: 38px !important;
		}
	</style>
@endsection
@section('content')
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Kegiatan Donasi</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>

                    @if(Auth::user()->tipe == 'admin')
                    <div class="button-group right">
                        <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#addMaalModal"><i class="fa fa-plus"></i> Tambah Kegiatan</button>
                    </div>
                        @endif
                </div>
            </div>
        </div>
    </div>
	<div class="content">
		<div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header text-center">
                            <h4 class="title"><b>Daftar Kegiatan Maal</b></h4>
                            <p class="category">Kegiatan Maal</p>
                            {{--<br />--}}
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <div class="col-md-12 btn-group">
{{--                                @if(Auth::user()->tipe! ="admin")--}}
{{--                                <a type="button" href="{{route('admin.maal')}}" class="btn btn-primary btn-fill" style="margin-bottom:1em" data-toggle="modal" data-target="#addMaalModal" title="Tambah Kegiatan">Tambah Kegiatan--}}
{{--                                </a>--}}
{{--                                @endif--}}
                                {{--<div class="col-md-2">--}}
                                {{--<button class="btn btn-default btn-block" onclick="demo.showNotification('top','right')">Top Right</button>--}}
                                {{--</div>--}}
                            </div>
                            <span></span>
                        </div>

                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th></th>
                            <th data-sortable="true" class="text-left">ID</th>
                            <th data-sortable="true">Nama Kegiatan</th>
                            <th data-sortable="true">Teller</th>
                            <th data-sortable="true">Detail Kegiatan</th>
                            <th data-sortable="true">Tanggal Pelaksanaan</th>
                            <th data-sortable="true">Rekening Donasi</th>
                            <th data-sortable="true">Jumlah Dana Yang Dibutuhkan </th>
                            <th data-sortable="true">Jumlah Dana Terkumpul</th>
                            <th data-sortable="true">Jumlah Dana Tersisa</th>
                            <th class="text-center">Actions</th>
                            </thead>
                            <tbody>
                            @foreach ($data as $usr)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->id }}</td>
                                    <td>{{ $usr->nama_kegiatan   }}</td>
                                    <td>{{ $usr->tName  }}</td>
                                    <td>{!! json_decode($usr->detail,true)['detail'] !!}</td>
                                    <td>{{ $usr->tanggal_pelaksaaan }}</td>
                                    <td>{{ $usr->nama_rekening }}</td>
                                    <td class="text-right">{{ number_format(json_decode($usr->detail,true)['dana'],2) }}</td>
                                    <td class="text-right">{{ number_format(isset(json_decode($usr->detail,true)['terkumpul'])?json_decode($usr->detail,true)['terkumpul']:0,2) }}</td>
                                    <td class="text-right">{{ number_format(isset(json_decode($usr->detail,true)['sisa'])?json_decode($usr->detail,true)['sisa']:0,2) }}</td>

                                    <td class="td-actions text-center">
                                        <form  method="post" @if(Auth::user()->tipe=="admin")action="{{route('admin.detail_transaksi')}}" @else action="{{route('teller.detail_transaksi')}}" @endif>
                                            <input type="hidden" id="id_status" name="id_" value="{{$usr->id}}">
                                            {{csrf_field()}}

                                            <button type="submit" class="btn btn-social btn-info btn-fill" title="Detail Transaksi">
                                                <i class="fa fa-list-alt"></i>
                                            </button>
                                            @if(Auth::user()->tipe=="admin")
                                            <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" title="Pencairan Dana" data-target="#pencairanMaalModal"
                                                    data-id      = "{{$usr->id}}"
                                                    data-nama    = "{{$usr->nama_kegiatan}}"
                                                    data-idrek   = "{{$usr->id_rekening}}"
                                                    data-tersisa = "{{ number_format(isset(json_decode($usr->detail,true)['sisa'])?json_decode($usr->detail,true)['sisa']:0,2) }}"
                                            >
                                                <i class="fa fa-usd"></i>
                                            </button>
                                            @endif
                                            @if(Auth::user()->tipe=="admin")

                                            <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editMaalModal" title="Edit"
                                                data-id      = "{{$usr->id}}"
                                                data-nama    = "{{$usr->nama_kegiatan}}"
                                                data-idrek   = "{{$usr->id_rekening}}"
                                                data-dana    = "{{json_decode($usr->detail,true)['dana']}}"
                                                data-path    = "{{url('/storage/public/maal'.json_decode($usr->detail,true)['path_poster'])}}"
                                                data-detail  = "{{ json_decode($usr->detail,true)['detail'] }}"
                                                data-tgl     = "{{$usr->tanggal_pelaksaaan}}"
                                            >
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delMaalModal" title="Delete"
                                                data-id         = "{{$usr->id}}"
                                                data-nama       = "{{$usr->nama_kegiatan}}">
                                                <i class="fa fa-remove"></i>
                                            </button>
                                            @endif
                                        </form>
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
@endsection

@section('modal')
    @include('modal.maal')
@endsection

@section('extra_script')


	<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
	<script src="{{URL::asset('bootstrap/assets/js/moment.min.js')}}"></script>
	<!--  Date Time Picker Plugin is included in this js file -->
	<script src="{{URL::asset('bootstrap/assets/js/bootstrap-datetimepicker.js')}}"></script>

	<script src="{{URL::asset('bootstrap/assets/js/jquery.validate.min.js')}}"></script>
	<script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>
	<script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
	<script type="text/javascript">
        $('#addMaalModal').on('show.bs.modal', function (event) {
            $('#id_maals').val(178);
        });
        $('#editMaalModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_edit').val(button.data('id'));
            $('#ekegiatan').val(button.data('nama'));
            $('#etgl').val(button.data('tgl'));
            $('#edetail').val(button.data('detail'));
            $('#eidRekMaal').val(button.data('idrek'));
            $('#edana').val(button.data('dana'));
            if(button.data('path')!=="http://localhost:8000/storage/public/maal")
                $('#epic').attr("src",button.data('path'));
            else  $('#epic').attr("src","");
        });
        $('#delMaalModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_del').val(id);
            $('#delTabLabel').text("Hapus Kegiatan Maal : " + nama);
            $('#toDelete').text(nama + "?");
        });
        $('#pencairanMaalModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_kegiatan').val(button.data('id'));
            $('#id_rekening').val(button.data('idrek'));
            $('#namaKegiatan').html(button.data('nama'));
            $('#danaTersisa').val(button.data('tersisa'));
        });

        $().ready(function(){
            $("#eidRekMaal").select2({
                dropdownParent: $("#editMaalModal")
            });
            $("#idRekMaal").select2({
                dropdownParent: $("#addMaalModal")
            });

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
            $('#wizardCardE').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormE').valid();

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

        $('#wizardCardP').bootstrapWizard({
            tabClass: 'nav nav-pills',
            nextSelector: '.btn-next',
            previousSelector: '.btn-back',
            onNext: function(tab, navigation, index) {
                var $valid = $('#wizardFormP').valid();

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
                "scrollX": false,
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
                        'copyHtml5',
                        'print',
                        'excelHtml5',
//                        'csvHtml5',
                        'pdfHtml5' ]
                }
            });
            // Init DatetimePicker
            demo.initFormExtendedDatetimepickers();
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#pic')
                        .attr('src', e.target.result)
                        .width(300)
                        .height(auto)
                };
                reader.readAsDataURL(input.files[0]);
            }
        }


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
                $('.date-picker').datetimepicker({
//                    defaultDate: "11/1/2013",
                    defaultDate: '{{isset(json_decode(Auth::user()->detail,true)['tgl_lahir'])?json_decode(Auth::user()->detail,true)['tgl_lahir']:""}}',
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

	<script type="text/javascript">

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
        }

        document.onkeypress = stopRKey;

	</script>
@endsection
@section('footer')
	@include('layouts.footer')
@endsection