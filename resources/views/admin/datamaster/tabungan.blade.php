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

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header text-center">
                            <h4 class="title"><b>Datamaster Tabungan BMT</b></h4>
                            <p class="category">Daftar Rekening Tabungan</p>
                            {{--<br />--}}
                        </div>

                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <div class="col-md-12 btn-group">
                                <button type="button" class="btn btn-primary btn-fill" style="margin-bottom:1em" data-toggle="modal" data-target="#addTabModal" title="Tambah Tabungan">Tambah Tabungan
                                    <i class="pe-7s-add-user"></i>
                                </button>
                            </div>
                            <span></span>
                        </div>

                        <table id="bootstrap-table" class="table">
                            <thead>
                            {{--<th data-field="state" data-checkbox="true"></th>--}}
                            <th width="10%" data-field="id" data-sortable="true" class="text-center">ID Tabungan</th>
                            <th width="15%" data-field="idRek" data-sortable="true">ID Rekening</th>
                            <th width="30%" data-field="nama" data-sortable="true">Jenis Tabungan</th>
                            <th width="15%" data-field="nisbah" data-sortable="true">Kategori</th>
                            <th width="20%" data-field="saldo" data-sortable="true">Saldo Minimal</th>
                            {{--<th data-field="actions" class="td-actions text-right" data-events="operateEvents" data-formatter="operateFormatter">Actions</th>--}}
                            <th>Actions</th>
                            </thead>
                            <tbody>

                            @foreach ($data as $sim)
                                <tr>
                                    <td class="text-center">{{ $sim->id }}</td>
                                    <td>{{ $sim->id_rekening }}</td>
                                    <td>{{ $sim->nama_rekening }}</td>
                                    <td>{{ $sim->katagori_rekening }}</td>
                                    <td>{{ number_format(json_decode($sim->detail,true)['saldo_min']) }}</td>
                                    <td class="td-actions text-center">
                                        <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editTabModal" title="Edit"
                                                data-id         = "{{$sim->id}}"
                                                data-idsim      = "{{$sim->id_tabungan}}"
                                                data-idrek      = "{{$sim->id_rekening}}"
                                                data-tipe      = "{{$sim->jenis_tabungan}}"
                                                data-nisbah    = "{{ json_decode($sim->detail,true)['nisbah_anggota'] }}"
                                                data-rekmar    = "{{ json_decode($sim->detail,true)['rek_margin'] }}"
                                                data-rekpen      = "{{ json_decode($sim->detail,true)['rek_pendapatan'] }}"
                                                data-wajib    = "{{ json_decode($sim->detail,true)['nasabah_wajib_pajak'] }}"
                                                data-zis    = "{{ json_decode($sim->detail,true)['nasabah_bayar_zis'] }}"
                                                data-saldo      = "{{ json_decode($sim->detail,true)['saldo_min'] }}"
                                                data-awal    = "{{ json_decode($sim->detail,true)['setoran_awal'] }}"
                                                data-set_min      = "{{ (json_decode($sim->detail,true)['setoran_min']) }}"
                                                data-min_mar    = "{{ (json_decode($sim->detail,true)['saldo_min_margin']) }}"
                                                data-tutup    = "{{ json_decode($sim->detail,true)['adm_tutup_tab'] }}"
                                                data-pemeliharaan   = "{{ json_decode($sim->detail,true)['pemeliharaan'] }}"
                                                data-passif    = "{{ json_decode($sim->detail,true)['adm_passif'] }}"
                                                data-baru      = "{{ json_decode($sim->detail,true)['adm_buka_baru'] }}"
                                                data-buku    = "{{ json_decode($sim->detail,true)['adm_ganti_buku'] }}" >
                                        <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delTabModal" title="Edit"
                                                data-id         = "{{$sim->id}}"
                                                data-idrek      = "{{$sim->id_rekening}}"
                                                data-namasim    = "{{$sim->jenis_tabungan}}">
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
    </div>

    @include('modal.tabungan')

@endsection

@section('extra_script')


    <script type="text/javascript">
//        $('#editRekModal').on('hidden.bs.modal', function () {
//            if (!$('#editRekModal').hasClass('no-reload')) {
//                location.reload();
//            }
//        });
        $('#editTabModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var idrek = button.data('idrek');
            var idsim = button.data('idsim');
            var nama = button.data('tipe');
            var nisbah = button.data('nisbah');
            var rekMar = button.data('rekmar');
            var rekPen = button.data('rekpen');
            var wajib = button.data('wajib');
            console.log(rekPen);
            var zis = button.data('zis');
            var saldo = button.data('saldo');
            var awal = button.data('awal');
            var setMin = button.data('set_min');
            var minMar = button.data('min_mar');
            var tutup = button.data('tutup');
            var pemeliharaan = button.data('pemeliharaan');
            var passif = button.data('passif');
            var baru = button.data('baru');
            var buku = button.data('buku');
                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_edit').val(idrek);
            $('#id_simp').val(idsim);
            $('#id_rek').val(idrek);
            $('#namaSim').val(nama);
            $('#nisbah').val(nisbah);
            $('#editrekMar').val(rekMar);
            $('#editrekPen').val(rekPen);

            $('#wajib').val(wajib);
            $('#zis').val(zis);
            $('#saldo').val(saldo);
            $('#awal').val(awal);
            $('#setMin').val(setMin);
            $('#minMar').val(minMar);
            $('#tutup').val(tutup);
            $('#pemeliharaan').val(pemeliharaan);
            $('#passif').val(passif);
            $('#baru').val(baru);
            $('#buku').val(buku);
            $('#editTabLabel').text("Edit : " + nama);
        });

        $('#delTabModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('idrek');
            var nama = button.data('namasim');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_del').val(id);
            $('#delTabLabel').text("Hapus : " + nama);
            $('#toDelete').text(nama + " akan dihapus!");
        });

    </script>

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            $("#idRek").select2({
                dropdownParent: $("#addTabModal")
            });

            $("#addrekMar").select2({
                dropdownParent: $("#addTabModal")
            });
            $("#addrekPen").select2({
                dropdownParent: $("#addTabModal")
            });

            $("#editrekMar").select2({
                dropdownParent: $("#editTabModal")
            });
            $("#editrekPen").select2({
                dropdownParent: $("#editTabModal")
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
            var selTip = $('#tipeRekAdd');
            selTip.on('change', function () {
                if(selTip .val() == 1) {
                    selAr.show();
                }
                else {
                    selAr.hide();
                }
            });


        });
    </script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

