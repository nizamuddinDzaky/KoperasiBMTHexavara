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
                <h4 class="title">Datamaster Mudharabah Berjangka BMT</h4>

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
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#addDepModal"><i class="fa fa-user-plus"></i> Tambah Mudharabah Berjangka</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header text-center">
                            <h4 class="title"><b>Datamaster Mudharabah Berjangka BMT</b></h4>
                            <p class="category">Daftar Rekening Mudharabah Berjangka</p>
                            {{--<br />--}}
                        </div>

                        {{-- <div class="toolbar"> --}}
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            {{-- <div class="col-md-12 btn-group">
                                <button type="button" class="btn btn-primary btn-fill" style="margin-bottom:1em" data-toggle="modal" data-target="#addDepModal" title="Tambah Deposito">Tambah Mudharabah Berjangka &nbsp;
                                    <i class="pe-7s-add-user"></i>
                                </button>
                            </div>
                            <span></span>
                        </div> --}}

                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th></th>
                            {{--<th data-field="state" data-checkbox="true"></th>--}}
                            <th data-field="id" data-sortable="true" class="text-left">ID MDB</th>
                            <th data-field="idRek" data-sortable="true">ID Rekening</th>
                            <th data-field="nama" data-sortable="true">Jenis MDB</th>
                            <th data-field="nisbah" data-sortable="true">Nisbah</th>
                            {{--<th data-field="actions" class="td-actions text-right" data-events="operateEvents" data-formatter="operateFormatter">Actions</th>--}}
                            <th>Actions</th>
                            </thead>
                            <tbody>
                            @foreach ($data as $dep)
                                <tr>
                                    <td></td>
                                    <td>{{ $dep->id }}</td>
                                    <td>{{ $dep->id_rekening }}</td>
                                    <td>{{ $dep->nama_rekening }}</td>
                                    <td>{{ json_decode($dep->detail)->nisbah_anggota }}%</td>
                                    <td class="td-actions text-center">
                                        <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editDepModal" title="Edit"
                                                data-id         = "{{$dep->id}}"
                                                data-iddep      = "{{$dep->id_rekening}}"
                                                data-idrek      = "{{$dep->id_rekening}}"
                                                data-tipe      = "{{$dep->tipe_rekening}}"
                                                data-rekmar    = "{{ json_decode($dep->detail,true)['rek_margin'] }}"
                                                data-rekpaj    = "{{ json_decode($dep->detail,true)['rek_pajak_margin'] }}"
                                                data-rektemp    = "{{ json_decode($dep->detail,true)['rek_jatuh_tempo'] }}"
                                                data-rekcad      = "{{ json_decode($dep->detail,true)['rek_cadangan_margin'] }}"
                                                data-rekpin      = "{{ json_decode($dep->detail,true)['rek_pinalti'] }}"
                                                data-waktu      = "{{ json_decode($dep->detail,true)['jangka_waktu'] }}"
                                                data-nisbah    = "{{ json_decode($dep->detail,true)['nisbah_anggota'] }}"
                                                data-wajib    = "{{ json_decode($dep->detail,true)['nasabah_wajib_pajak'] }}">
                                        <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delDepModal" title="Edit"
                                                data-id         = "{{$dep->id}}"
                                                data-idrek      = "{{$dep->id_rekening}}"
                                                data-namadep    = "{{$dep->nama_rekening}}">
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
    @include('modal.deposito')
@endsection

@section('extra_script')

    <script type="text/javascript">
        $('#editDepModal').on('show.bs.modal', function (event) {
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
            $('#editrekPaj').val(button.data('rekpaj'));
            $('#editrekPaj').select2().trigger('change');
            $('#editrekTemp').val(button.data('rektemp'));
            $('#editrekTemp').select2().trigger('change');
            $('#editrekCad').val(button.data('rekcad'));
            $('#editrekCad').select2().trigger('change');
            $('#editrekPin').val(button.data('rekpin'));
            $('#editrekPin').select2().trigger('change');

            $('#edwaktu').val(button.data('waktu'));
            $('#ednisbah').val(button.data('nisbah'));
            $('#edwajib').val(button.data('wajib'));
            $('#edwajib').select2().trigger('change');
            $('#editDepLabel').text("Edit : " + nama);
        });

        $('#delDepModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('idrek');
            var nama = button.data('namadep');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_del').val(id);
            $('#delDepLabel').text("Hapus : " + nama);
            $('#toDelete').text(nama + " akan dihapus!");
        });

    </script>

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#idRek").select2({
                dropdownParent: $("#addDepModal")
            });
            $("#addrekMar").select2({
                dropdownParent: $("#addDepModal")
            });
            $("#addrekPaj").select2({
                dropdownParent: $("#addDepModal")
            });
            $("#addrekPin").select2({
                dropdownParent: $("#addDepModal")
            });
            $("#addrekCad").select2({
                dropdownParent: $("#addDepModal")
            });
            $("#addrekTemp").select2({
                dropdownParent: $("#addDepModal")
            });

            $("#editrekMar").select2({
                dropdownParent: $("#editDepModal")
            });
            $("#editrekPaj").select2({
                dropdownParent: $("#editDepModal")
            });
            $("#editrekCad").select2({
                dropdownParent: $("#editDepModal")
            });
            $("#editrekTemp").select2({
                dropdownParent: $("#editDepModal")
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

    <script type="text/javascript">
        $().ready(function(){
            @if(session('success'))
                notif.statusSuccess();
            @elseif(session('message'))
                notif.statusFail();
            @endif
        });
    </script>

@endsection

@section('footer')
    @include('layouts.footer')
@endsection

