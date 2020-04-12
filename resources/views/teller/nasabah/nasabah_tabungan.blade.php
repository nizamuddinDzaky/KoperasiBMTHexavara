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
                <h4 class="title">Tabungan Anggota</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option selected disabled>- Periode -</option>
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
                        <h4 class="title"> Tabungan Anggota</h4>
                        <p class="category">Daftar  Tabungan Anggota</p>
                        <br />
                    </div>

                    <table id="bootstrap-table" class="table">
                        <thead>
                        <th></th>
                        <th data-sortable="true" class="text-left">ID Tabungan</th>
                        <th data-sortable="true">Jenis Tabungan</th>
                        <th data-sortable="true">Nama Anggota</th>
                        <th data-sortable="true">Saldo</th>
                        <th data-sortable="true">Tgl Pembuatan</th>
                        <th data-sortable="true">Status</th>
                        <th>Actions</th>
                        </thead>
                        @foreach ($data as $usr)
                            <tr>
                                <td></td>
                                <td>{{ $usr->id_tabungan }}</td>
                                <td>{{ $usr->jenis_tabungan  }}</td>
                                <td>{{ $usr->nama   }}</td>
                                <td>Rp{{" ". number_format(json_decode($usr->detail,true)['saldo'],2)  }}</td>
                                <td>{{ $usr->created_at }}</td>
                                <td class="text-uppercase text-center">{{ $usr->status }}</td>
                                <td class="td-actions text-center">
                                    <form  method="post" action="{{route('teller.detail_tabungan')}}">
                                        <input type="hidden" id="id_status" name="id_" value="{{$usr->id}}">
                                        {{csrf_field()}}
                                        <button type="submit" class="btn btn-social btn-info btn-fill" title="Detail"
                                                data-id      = "{{$usr->no_ktp}}"
                                                data-nama    = "{{$usr->nama}}" name="id">
                                            <i class="fa fa-clipboard-list"></i>

                                        @if($usr->status!="closed")
                                        <button type="button"  class="btn btn-social btn-fill 
                                            @if($usr->status=="active") btn-danger" title="Blokir Rekening"
                                                @elseif($usr->status=="blocked") btn-success title="Activasi Rekening"  @endif
                                                @if($usr->status!="not active")data-toggle="modal" data-target="#blockRekModal" @else title="Rekening Tidak Aktif" @endif
                                                data-id         = "{{$usr->id}}"
                                                data-nama       = "{{$usr->jenis_tabungan}}"
                                                data-status       = "{{$usr->status}}">
                                            @if($usr->status=="active")
                                                <i class="fa fa-minus-square"></i>
                                            @elseif($usr->status=="blocked")
                                                <i class="fa fa-check-square"></i>
                                            @elseif($usr->status=="not active")
                                                <i class="fa fa-remove"></i>
                                            @endif
                                        </button>
                                        @endif
                                        @if($usr->status!="closed" && Auth::user()->tipe=="teller")
                                        <button type="button"  class="btn btn-social btn-fill btn-danger" title="Tutup Rekening"
                                                data-toggle="modal" data-target="#tutupTabModal2"
                                                data-nama         = "{{$usr->jenis_tabungan}}"
                                                data-usr         = "{{$usr->nama}}"
                                                data-saldo        = "{{json_decode($usr->detail,true)['saldo']}}"
                                                data-id         = "{{$usr->id_tabungan}}">
                                                <i class="fa fa-close"></i>
                                        </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div><!--  end card  -->
        </div> <!-- end col-md-12 -->
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
            $('#tipeRek').val("Tabungan");
            $('#st_block').val(status);
            $('#toBlock').text(nama + "?");
        });
        $('#tutupTabModal2').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            $('#idRekC2').val(button.data('id')+" "+button.data('nama')+" ["+button.data('usr')+"]");
            $('#idRekCls2').val(button.data('id'));
            $('#jumlahClse2').val(button.data('saldo'));
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        });
    </script>

    <script type="text/javascript">
        var $table = $('#bootstrap-table');


        $().ready(function(){
            var selArc = $('#toHidecls2');
            var selArBc =$('#toHideBankcls2');
            var selArB2c =$('#toHideBank2cls2');
            var atasnamac =$('#atasnamacls2');
            var bankc =$('#bankCls2');
            var kebankc =$('#bankcls2');
            var nobankc =$('#nobankcls2');

            var jenisc = $('#jeniscls2');
            var buktic = $('#bukticls2');
            selArc.hide(); selArBc.hide(); selArB2c.hide();


            jenisc.on('change', function () {
                if(jenisc.val() == 1) {
                    buktic.attr("required",true);
                    bankc.attr("required",true);
                    atasnamac.attr("required",true);
                    nobankc.attr("required",true);
                    kebankc.attr("required",true);
                    selArc.show();
                    selArBc.show(); selArB2c.show()
                }
                else if (jenisc.val() == 0) {
                    kebankc.attr("required",false);
                    bankc.attr("required",false);
                    atasnamac.attr("required",false);
                    nobankc.attr("required",false);
                    buktic.attr("required",false);
                    selArc.hide();
                    selArBc.hide();selArB2c.hide();
                }
            });


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

            $('#wizardCardClose2').bootstrapWizard({
                tabClass: 'nav nav-pills',
                nextSelector: '.btn-next',
                previousSelector: '.btn-back',
                onNext: function(tab, navigation, index) {
                    var $valid = $('#wizardFormClose2').valid();

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