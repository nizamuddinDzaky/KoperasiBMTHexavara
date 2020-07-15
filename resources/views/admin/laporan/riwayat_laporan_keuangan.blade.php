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
                <h4 class="title">Riwayat Laporan Keuangan</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>

                {{-- <div class="button-group right">
                    <a class="btn btn-primary rounded right shadow-effect" href="{{ route('admin.laporan.keuangan.riwayat') }}"><i class="fa fa-eye"></i> Lihat Riwayat</a>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header text-center">
                            <h4 class="title"><b>Data Rekening BMT</b></h4>
                            <p class="category">Daftar Rekening</p>
                            {{--<br />--}}
                        </div>
                        {{--<div class="toolbar">--}}
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            {{-- <div class="col-md-12 btn-group">
                                <button type="button" class="btn btn-primary btn-fill" style="margin-bottom:1em" data-toggle="modal" data-target="#addRekModal" title="Tambah Rekening">Tambah Rekening
                                    <i class="pe-7s-add-user"></i>
                                </button> --}}

                                {{--<form action="{{route('add.bmt')}}" method="post">--}}
                                    {{--{{ csrf_field() }}--}}
                                    {{--<button type="submit" class="btn btn-primary btn-fill" style="margin-bottom:1em;margin-left:1em" title="Tambah Penyimpanan BMT">Tambah Penyimpanan BMT--}}
                                        {{--<i class="pe-7s-add-user"></i>--}}
                                    {{--</button>--}}
                                {{--</form>--}}

                            {{-- </div>
                            <span></span>
                        </div> --}}

                        <table id="bootstrap-table" class="table">
                            <thead>
                                <th></th>
                                {{--<th data-field="state" data-checkbox="true"></th>--}}
                                <th data-field="detail" data-sortable="true">ID</th>
                                <th data-field="id" data-sortable="true" class="text-left">Admin</th>
                                <th data-field="nama" data-sortable="true">Tanggal Cetak</th>
                                {{--<th data-field="actions" class="td-actions text-right" data-events="operateEvents" data-formatter="operateFormatter">Actions</th>--}}
                                <th class="text-center">Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $rek)
                                    <tr>
                                        <td></td>
                                        <td>{{ $rek->id }}</td>
                                        <td>{{ strtoupper($rek->user->nama) }}</td>
                                        <td>{{ $rek->created_at->format("D, d F Y H:m:s") }}</td>

                                        <td class="td-actions text-center">

                                            <a href="{{ route('admin.laporan.keuangan.riwayat.detail', [$rek->id]) }}" type="button" class="btn btn-social btn-primary btn-fill" title="View Detail"><i class="fa fa-list"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->

    </div>

    @include('modal.catatan_rekening')

@endsection

@section('extra_script')

    <script type="text/javascript">

        $('#editCatatanRekening').on('show.bs.modal', function (event) {

            var button = $(event.relatedTarget); // Button that triggered the modal
            var idrek = button.data('idrek');
            var namarek = button.data('namarek');
            var catatan = button.data('catatan');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $("#namaRekening").val(namarek);
            $("#idRekening").val(idrek);
            $("#catatan").val(catatan);

        });

    </script>

    <!-- Select2 plugin -->
    <script src=" {{  URL::asset('/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#selRekening").select2({
                dropdownParent: $("#addRekModal")
            });
            $("#indukRek").select2({
                dropdownParent: $("#editRekModal")
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
                        'pdfHtml5' 
                    ]
                }
            });


            // Save data to table
            $(".buttons-excel").click(function() {
                $.ajax({
                    type: "POST",
                    url: window.location.href + "/export",
                    dataType: "JSON",
                    success: function (response) {
                        window.location.reload();
                    }
                });
            })
        });

    </script>

    <script type="text/javascript">
        $().ready(function(){
            $('#addRekening').validate();
            var selTip = $('#tipeRekAdd');
            var selAr = $('#Induk_');
            selAr.hide();
            selTip.on('change', function () {
                if(selTip .val() == "master") {
                    selAr.hide();
                    $('#selRekening').val("master");
                }
                else {
                    selAr.show();
                }
            });


        });
    </script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

