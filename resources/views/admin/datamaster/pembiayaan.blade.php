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
                <h4 class="title">Datamaster Pembiayaan BMT</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>

                <div class="button-group right">
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#addPemModal"><i class="fa fa-user-plus"></i> Tambah Pembiayaan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header text-center">
                            <h4 class="title"><b>Datamaster Pembiayaan BMT</b></h4>
                            <p class="category">Daftar Rekening Pembiayaan</p>
                            {{--<br />--}}
                        </div>

                        {{-- <div class="toolbar"> --}}
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            {{-- <div class="col-md-12 btn-group">
                                <button type="button" class="btn btn-primary btn-fill" style="margin-bottom:1em" data-toggle="modal" data-target="#addPemModal" title="Tambah Pembiayaan">Tambah Pembiayaan
                                    <i class="pe-7s-add-user"></i>
                                </button>
                            </div>
                            <span></span>
                        </div> --}}

                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th></th>
                            {{--<th data-field="state" data-checkbox="true"></th>--}}
                            <th data-field="id" data-sortable="true" class="text-left">ID Pembiayaan</th>
                            <th data-field="idRek" data-sortable="true">ID Rekening</th>
                            <th data-field="nama" data-sortable="true">Jenis Pembiayaan</th>
                            <th data-field="nisbah" data-sortable="true">Nisbah</th>
                            <th data-field="saldo" data-sortable="true">Saldo Minimal</th>
                            {{--<th data-field="actions" class="td-actions text-right" data-events="operateEvents" data-formatter="operateFormatter">Actions</th>--}}
                            <th>Actions</th>
                            </thead>
                            <tbody>
                            @foreach ($data as $pem)
                                <tr>
                                    <td></td>
                                    <td>{{ $pem->id }}</td>
                                    <td>{{ $pem->id_rekening }}</td>
                                    <td>{{ $pem->nama_rekening }}</td>
                                    <td>{{ $pem->nisbah }}%</td>
                                    <td>{{ number_format($pem->saldo_minimal) }}</td>
                                    <td class="td-actions text-center">
                                        <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editPemModal" title="Edit"
                                                data-id         = "{{$pem->id}}"
                                                data-idpemb      = "{{$pem->id_rekening}}"
                                                data-idrek      = "{{$pem->id_rekening}}"
                                                data-tipe      = "{{$pem->tipe_rekening}}"
                                                data-rekmar    = "{{ json_decode($pem->detail,true)['rek_margin'] }}"
                                                data-rekmt    = "{{ json_decode($pem->detail,true)['m_ditangguhkan'] }}"
                                                data-rekden    = "{{ json_decode($pem->detail,true)['rek_denda'] }}"
                                                data-rekadm      = "{{ json_decode($pem->detail,true)['rek_administrasi'] }}"
                                                data-reknot      = "{{ json_decode($pem->detail,true)['rek_notaris'] }}"
                                                data-rekwo      = "{{ json_decode($pem->detail,true)['rek_pend_WO'] }}"
                                                data-rekmat    = "{{ json_decode($pem->detail,true)['rek_materai'] }}"
                                                data-rekasu    = "{{ json_decode($pem->detail,true)['rek_asuransi'] }}"
                                                data-rekprov      = "{{ json_decode($pem->detail,true)['rek_provisi'] }}"
                                                data-rekppro   = "{{ json_decode($pem->detail,true)['rek_pend_prov'] }}"
                                                data-rekzis   = "{{ json_decode($pem->detail,true)['rek_zis'] }}"
                                                data-piutang      = "{{ json_decode($pem->detail,true)['piutang'] }}"
                                                data-pinjam    = "{{ json_decode($pem->detail,true)['jenis_pinjaman'] }}">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delPemModal" title="Edit"
                                                data-id         = "{{$pem->id}}"
                                                data-idrek      = "{{$pem->id_rekening}}"
                                                data-namapem    = "{{$pem->jenis_pembiayaan}}">
                                            <i class="fa fa-remove"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->

    </div>
@endsection

@section('modal')
    @include('modal.pembiayaan')
@endsection

@section('extra_script')

    <script type="text/javascript">
//        $('#editRekModal').on('hidden.bs.modal', function () {
//            if (!$('#editRekModal').hasClass('no-reload')) {
//                location.reload();
//            }
//        });
        $('#editPemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var idrek = button.data('idrek');
            var iddep = button.data('iddep');
            var nama = button.data('tipe');
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_edit').val(idrek);
            $('#id_depo').val(iddep);
            $('#id_rek').val(idrek);
            $('#nama').val(nama);
            $('#editrekMar').val(button.data('rekmar'));
            $('#editrekMar').select2().trigger('change');
            $('#editrekMt').val(button.data('rekmt'));
            $('#editrekMt').select2().trigger('change');
            $('#editrekDen').val(button.data('rekden'));
            $('#editrekDen').select2().trigger('change');
            $('#editrekAdm').val(button.data('rekadm'));
            $('#editrekAdm').select2().trigger('change');
            $('#editrekNot').val(button.data('reknot'));
            $('#editrekNot').select2().trigger('change');
            $('#editrekWO').val(button.data('rekwo'));
            $('#editrekWO').select2().trigger('change');
            $('#editrekMat').val(button.data('rekmat'));
            $('#editrekMat').select2().trigger('change');
            $('#editrekAsu').val(button.data('rekasu'));
            $('#editrekAsu').select2().trigger('change');
            $('#editrekProv').val(button.data('rekprov'));
            $('#editrekProv').select2().trigger('change');
            $('#editrekPpro').val(button.data('rekppro'));
            $('#editrekPpro').select2().trigger('change');
            $('#editrekZis').val(button.data('rekzis'));
            $('#editrekZis').select2().trigger('change');
            $('#editpiutang').val(button.data('piutang'));
            $('#editpiutang').select2().trigger('change');
            $('#editpinjam').val(button.data('pinjam'));
            $('#editpinjam').select2().trigger('change');
            $('#editPemLabel').text("Edit : " + nama);
            var mTangguh2 = $('#toHideMedit');
            if($('#editpiutang').val() == 1) {
                mTangguh2.show();
                $('#editrekMt').attr("required",true);
            }
            else if($('#editpiutang').val() == 0) {
                mTangguh2.hide();
                $('#editrekMt').attr("required",false);
            }
        });

        $('#delPemModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('idrek');
            var nama = button.data('namapem');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_del').val(id);
            $('#delPemLabel').text("Hapus : " + nama);
            $('#toDelete').text(nama + " akan dihapus!");
        });

    </script>

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script src="{{URL::asset('bootstrap/assets/js/jquery.bootstrap.wizard.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            var mTangguh = $('#toHideM');
            var piutang = $('#piutang');
            mTangguh.hide();
            piutang.on('change', function () {
                if(piutang .val() == 1) {
                    mTangguh.show();
                    $('#addrekMt').attr("required",true);
                }
                else if(piutang .val() == 0) {
                    mTangguh.hide();
                    $('#addrekMt').attr("required",false);
                }
            });
            var mTangguh2 = $('#toHideMedit');
            var piutang2 = $('#editpiutang');
            mTangguh2.hide();
            piutang2.on('change', function () {
                if(piutang2 .val() == 1) {
                    mTangguh2.show();
                    $('#editrekMt').attr("required",true);
                }
                else if(piutang2 .val() == 0) {
                    mTangguh2.hide();
                    $('#editrekMt').attr("required",false);
                }
            });


                $("#idRek").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekMar").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekDen").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekMt").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekAdm").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekNot").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekWO").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekMat").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekAsu").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekProv").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekPpro").select2({
                dropdownParent: $("#addPemModal")
            });
            $("#addrekZis").select2({
                dropdownParent: $("#addPemModal")
            });

            $("#editrekMar").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekDen").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekMt").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekAdm").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekNot").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekWO").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekMat").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekAsu").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekProv").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekPpro").select2({
                dropdownParent: $("#editPemModal")
            });
            $("#editrekZis").select2({
                dropdownParent: $("#editPemModal")
            });

            lbd.checkFullPageBackgroundImage();

            setTimeout(function(){
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700);

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
        });

    </script>

    <script>

        type = ['','info','success','warning','danger'];
        demo={
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
        }

    </script>

@endsection

@section('footer')
    @include('layouts.footer')
@endsection

