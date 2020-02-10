@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">

                        <div class="header text-center">
                            <h3 class="title"><b>Kas Anggota</b></h3>
                            <p class="category">Daftar Kas Anggota Bulan {{date("F")}}</p>
                            <br />
                        </div>

                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            {{--<div class="col-md-12 btn-group">--}}
                                {{--<button type="button" class="btn btn-info btn-fill">Tambah</button>--}}
                                {{--<button type="button" class="btn btn-success btn-fill">Middle</button>--}}
                                {{--<button type="button" class="btn btn-danger btn-fill">Right</button>--}}
                            {{--</div>--}}
                            <span></span>
                        </div>

                        <table id="bootstrap-table" class="table table-striped">
                            <thead>
                            <th  data-checkbox="true"></th>
                            <th  class="text-center">ID</th>
                            <th  data-sortable="true">Nama</th>
                            <th  data-sortable="true">Saldo Simpanan</th>
                            <th  data-sortable="true">Tagihan Kredit</th>
                            <th >Keterangan</th>
                            <th class="td-actions text-right" data-events="operateEvents" data-formatter="operateFormatter">Actions</th>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                                <td>1</td>
                                <td>Dakota Rice</td>
                                <td>Rp 36,738</td>
                                <td>Rp 36,738</td>
                                <td>Jatuh tempo : {{date("D M j Y")}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>2</td>
                                <td>Minerva Hooper</td>
                                <td>Rp 23,789</td>
                                <td>Rp 16,747</td>
                                <td>Jatuh tempo : {{date("D M j Y")}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>3</td>
                                <td>Sage Rodriguez</td>
                                <td>Rp 56,142</td>
                                <td>Rp 33,748</td>
                                <td>Jatuh tempo : {{date("D M j Y")}}</td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!--  end card  -->
                </div> <!-- end col-md-12 -->
            </div> <!-- end row -->

        </div>
    </div>
@endsection

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
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

