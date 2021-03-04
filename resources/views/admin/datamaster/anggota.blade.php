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
                <h4 class="title">Datamaster Anggota BMT</h4>

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
                    <a href="{{route('download.excel.data_anggota')}}" class="btn btn-success rounded right shadow-effect"><i class="fa fa-file-excel"></i> Download Excel</a>
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#addUsrModal"><i class="fa fa-user-plus"></i> Tambah User</button>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
            @if($errors)
                @foreach ($errors->all() as $error)
                    <div class="row ">
                        <div class="alert-danger text-center">{{ $error }}</div>
                    </div>
                @endforeach
                    <br>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="header text-center">
                            <h4 class="title"><b>Datamaster Anggota BMT</b></h4>
                            <p class="category">Daftar Anggota</p>
                            {{-- <br /> --}}
                        </div>
                        {{-- <div class="toolbar"> --}}
                            <!--Here you can write extra buttons/actions for the toolbar-->
                            {{-- <div class="col-md-12 btn-group">
                                <button type="button" class="btn btn-primary btn-fill" style="margin-bottom:1em" data-toggle="modal" data-target="#addUsrModal" title="Tambah Anggota">Tambah User
                                    <i class="pe-7s-add-user"></i>
                                </button>
                                <div class="col-md-2">
                                    <button class="btn btn-default btn-block" onclick="demo.showNotification('top','right')">Top Right</button>
                                </div>
                            </div>
                            <span></span>
                        </div> --}}

                        <table class="table bootstrap-table">
                            <thead>
                            <th></th>
                            <th data-field="id" data-sortable="true" class="text-left">No KTP</th>
                            <th data-field="nama" data-sortable="true">Nama</th>
                            <th data-field="jenis" data-sortable="true">Jenis Kelamin</th>
                            <th data-field="nohp" data-sortable="true">No Telepon</th>
                            <th data-field="alamat" data-sortable="true">Alamat</th>
                            <th data-field="jenis" data-sortable="true">Pendidikan</th>
                            <th data-field="jenis" data-sortable="true">Pekerjaan</th>
                            <th data-field="jenis" data-sortable="true">Pendapatan/bln</th>
{{--                            <th data-field="jenis" data-sortable="true">Tipe</th>--}}
{{--                            <th data-field="jenis" data-sortable="true">Role</th>--}}
                            <th data-field="registrasi" data-sortable="true">Status Keanggotaan</th>
                            <th data-field="registrasi" data-sortable="true">Detail</th>
                            <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $usr)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->no_ktp }}</td>
                                    <td>{{ $usr->nama   }}</td>
                                    @if(isset(json_decode($usr->detail,true)['jenis_kelamin']))
                                        <td>{{json_decode($usr->detail,true)['jenis_kelamin']}}</td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    @if(isset(json_decode($usr->detail,true)['telepon']))
                                        <td>{{json_decode($usr->detail,true)['telepon']}}</td>
                                    @else
                                        <td>-</td>
                                    @endif

                                    <td>{{ $usr->alamat }}</td>
                                    
                                    @if(!isset( json_decode($usr->detail,true)['pendidikan']))
                                    <td>-</td>
                                    @elseif( json_decode($usr->detail,true)['pendidikan'] == "0" )
                                    <td>Tidak Sekolah</td>
                                    @elseif( json_decode($usr->detail,true)['pendidikan']=="SD")
                                    <td>SD</td>
                                    @elseif( json_decode($usr->detail,true)['pendidikan']=="SMP")
                                    <td>SMP</td>
                                    @elseif( json_decode($usr->detail,true)['pendidikan']=="SMA")
                                    <td>SMA</td>
                                    @elseif( json_decode($usr->detail,true)['pendidikan']=="D1")
                                    <td>D1/D3</td>
                                    @elseif( json_decode($usr->detail,true)['pendidikan']=="S1")
                                    <td>S1/D4</td>
                                    @elseif( json_decode($usr->detail,true)['pendidikan']=="S2")
                                    <td>S2/S3</td>
                                    @endif
                                    @if(!isset( json_decode($usr->detail,true)['pekerjaan']))
                                    <td>-</td>
                                    @else
                                    <td>{{ json_decode($usr->detail,true)['pekerjaan']}}</td>
                                    @endif
                                    <td class="text-uppercase text-center">{{ isset(json_decode($usr->detail,true)['pendapatan'])?number_format(json_decode($usr->detail,true)['pendapatan'],2):"" }}</td>

{{--                                    <td class="text-uppercase text-center">{{ $usr->tipe }}</td>--}}
{{--                                    <td class="text-uppercase text-center">{{ $usr->role }}</td>--}}

                                    @if($usr->status == 2 && $usr->is_active == 1)
                                        <td class="text-uppercase text-center">Anggota Aktif</td>
                                    @elseif($usr->tipe=="admin")
                                        <td class="text-uppercase text-center">-</td>
                                    @elseif($usr->status != 2 && $usr->is_active == 1)
                                        <td class="text-uppercase text-center"> Belum Mengisi Identitas</td>
                                    @elseif($usr->status != 2 && $usr->is_active == 0)
                                        <td class="text-uppercase text-center"> Anggota Keluar</td>
                                    @endif

                                    <td class="text-uppercase text-center">
                                        <form id="wizardForm" method="POST" action="{{route('detailanggota')}}" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <button type="submit" class="btn btn-social btn-info btn-fill" title="Detail Anggota">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <input type="hidden" name="noktp" value="{{ $usr->no_ktp }}">
                                        </form>

                                    </td>
                                    <td class="td-actions text-center">
                                        <button type="button" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#editPassUsrModal" title="Ubah Password"
                                                data-id      = "{{$usr->no_ktp}}"
                                                data-nama    = "{{$usr->nama}}">
                                            <i class="fa fa-key"></i>
                                        </button>
                                        @if($usr->tipe!="admin")
                                        <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editUsrModal" title="Edit"
                                                          data-id      = "{{$usr->no_ktp}}"
                                                          data-nama    = "{{$usr->nama}}"
                                                          data-alamat    = "{{$usr->alamat}}"
                                                data-tipe    = "{{$usr->tipe}}"
                                                data-role    = "{{$usr->role}}"
                                                data-id_kas    = "{{ isset(json_decode($usr->detail,true)['id_rekening'])?json_decode($usr->detail,true)['id_rekening']:"" }}"
                                                data-p    = "{{$usr->password}}"
                                                @if($usr->tipe == "teller")
                                                    @if(isset(json_decode($usr->detail,true)['kota']))
                                                        data-kota="{{json_decode($usr->detail,true)['kota']}}"
                                                    @endif
                                                    @endif
                                                >
                                            <i class="fa fa-edit"></i>
                                        </button>

                                        @if($usr->is_active == 0)
                                        <button type="button"  class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#reactiveUsrModal" title="Reactive User"
                                                data-id         = "{{$usr->no_ktp}}"
                                                data-nama       = "{{$usr->nama}}">
                                            <i class="fa fa-undo"></i>
                                        </button>
                                        @endif

{{--                                        <button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delUsrModal" title="Delete"--}}
{{--                                                data-id         = "{{$usr->no_ktp}}"--}}
{{--                                                data-nama       = "{{$usr->nama}}">--}}
{{--                                            <i class="fa fa-remove"></i>--}}
{{--                                        </button>--}}
                                        @endif
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
@endsection

@section('modal')
    @include('modal.anggota')
    @include('modal.penutupan_rekening.reactive_account')
@endsection

@section('extra_script')

    <script type="text/javascript">
           //        $('#editUsrModal').on('hidden.bs.modal', function () {
        //            if (!$('#editUsrModal').hasClass('no-reload')) {
        //                location.reload();
        //            }
        //        });
           $('#editPassUsrModal').on('show.bs.modal', function (event) {
               var button = $(event.relatedTarget); // Button that triggered the modal
               var id = button.data('id');
               var nama = button.data('nama');
               // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
               // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
               $('#id_usr_p').val(id);
               $('#editPassUsrlabel').text("Edit Password User: " + nama);
           });

           $('#editUsrModal').on('show.bs.modal', function (event) {
               var button = $(event.relatedTarget); // Button that triggered the modal
               var id = button.data('id');
               var alamat = button.data('alamat');
               var nama = button.data('nama');
               var tipe = button.data('tipe');
               if(tipe == "teller"){
                   var kota = button.data('kota');
                   console.log(kota);
                   $('#kotateller2').val(kota);
               }
               // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
               // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
               if( tipe=="teller"){
                   $('#toShow2').show();
                   $('#toShow3').show();
               } else{
                   $('#toShow2').hide();
                   $('#toShow3').hide();
               }

               $('#id_edit').val(id);
               $('#idrekteller2').val(button.data('id_kas'));
               console.log($('#idrekteller2').val());
               $('#id_usr_edit').val(id);
               $('#nama_usr').val(nama);
               $('#alamat_usr').val(alamat);
               $('#tipe_usr').val(tipe);
               $('#role').val(button.data('role'));
               $('#editUsrLabel').text("Edit User: " + nama);
           });

        $('#delUsrModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_del').val(id);
            $('#delUsrLabel').text("Hapus Anggota: " + nama);
            $('#toDelete').text("Anggota " + nama + " akan dihapus!");
        });

        $('#reactiveUsrModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            $('#id_usr_react').val(id);
            $('#reactiveUsrLabel').text("Aktifkan Anggota: " + nama);
            $('#toDelete').text("Anggota " + nama + " akan dihapus!");
        });

    </script>
    

    <script type="text/javascript">
        var $table = $('#bootstrap-table');

        $().ready(function(){
            var jenis = $('#toShow');
            var rek_teller = $('#toShow2');
            var jenis2 = $('#toHideUsr');
            var selRek = $('#jenistipe');
            var selRek2 = $('#tipe_usr');
            var noktp = $('#idUsr');
            jenis.hide();
            jenis2.hide();
            selRek.on('change', function () {
                if(selRek.val()=="teller"){
                    jenis2.hide();
                    jenis.show();
                    noktp.val("teller");
                    $('#idrekteller').attr("required",true);
                }
                else if(selRek.val()=="anggota"){
                    jenis2.show();
                    jenis.hide();
                    $('#idrekteller').attr("required",false);
                }
            });

            rek_teller.hide();

            selRek2.on('change', function () {
                if(selRek2.val()=="teller"){
                    rek_teller.show();
                    $('#idrekteller2').attr("required",true);
                }
                else if(selRek2.val()=="anggota"){
                    rek_teller.hide();
                    $('#idrekteller2').attr("required",false);
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


//            $table.bootstrapTable({
//                toolbar: ".toolbar",
//                clickToSelect: true,
//                showRefresh: true,
//                search: true,
//                showToggle: true,
//                showColumns: true,
//                pagination: true,
//                searchAlign: 'left',
//                pageSize: 8,
//                clickToSelect: false,
//                pageList: [8,10,25,50,100],
//
//                formatShowingRows: function(pageFrom, pageTo, totalRows){
//                    //do nothing here, we don't want to show the text "showing x of y from..."
//                },
//                formatRecordsPerPage: function(pageNumber){
//                    return pageNumber + " rows visible";
//                },
//                icons: {
//                    refresh: 'fa fa-refresh',
//                    toggle: 'fa fa-th-list',
//                    columns: 'fa fa-columns',
//                    detailOpen: 'fa fa-plus-circle',
//                    detailClose: 'fa fa-minus-circle'
//                }
//            });
//
//            //activate the tooltips after the data table is initialized
//            $('[rel="tooltip"]').tooltip();
//
//            $(window).resize(function () {
//                $table.bootstrapTable('resetView');
//            });
//

        });

    </script>

    <script type="text/javascript">
        $().ready(function(){
//            var selAr =  document.getElementById$('toHide');
//            selAr.hide();
            $('#addUrs').validate();
            $('#editUsr').validate();
            $('#delUsr').validate();
        });
        type = ['','info','success','warning','danger'];

        demo = {

                    showSwal: function(type){
                        if(type == 'basic'){
                            swal("Here's a message!");

                        }else if(type == 'title-and-text'){
                            swal("Here's a message!", "It's pretty, isn't it?")

                        }else if(type == 'success-message'){
                            swal("Good job!", "You clicked the button!", "success")

                        }else if(type == 'warning-message-and-confirmation'){
                            swal({  title: "Are you sure?",
                                text: "You will not be able to recover this imaginary file!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonClass: "btn btn-info btn-fill",
                                confirmButtonText: "Yes, delete it!",
                                cancelButtonClass: "btn btn-danger btn-fill",
                                closeOnConfirm: false,
                            },function(){
                                swal("Deleted!", "Your imaginary file has been deleted.", "success");
                            });

                        }else if(type == 'warning-message-and-cancel'){
                            swal({  title: "Are you sure?",
                                text: "You will not be able to recover this imaginary file!",
                                type: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Yes, delete it!",
                                cancelButtonText: "No, cancel plx!",
                                closeOnConfirm: false,
                                closeOnCancel: false
                            },function(isConfirm){
                                if (isConfirm){
                                    swal("Deleted!", "Your imaginary file has been deleted.", "success");
                                }else{
                                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                                }
                            });

                        }else if(type == 'custom-html'){
                            swal({  title: 'HTML example',
                                html:
                                'You can use <b>bold text</b>, ' +
                                '<a href="http://github.com">links</a> ' +
                                'and other HTML tags'
                            });

                        }else if(type == 'auto-close'){
                            swal({ title: "Auto close alert!",
                                text: "I will close in 2 seconds.",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else if(type == 'input-field'){
                            swal({
                                    title: 'Input something',
                                    html: '<p><input id="input-field" class="form-control">',
                                    showCancelButton: true,
                                    closeOnConfirm: false,
                                    allowOutsideClick: false
                                },
                                function() {
                                    swal({
                                        html:
                                        'You entered: <strong>' +
                                        $('#input-field').val() +
                                        '</strong>'
                                    });
                                })
                        }
                    },
                    showNotification: function(from, align,msg){
                        color = Math.floor((Math.random() * 4) + 1);

                        $.notify({
                            icon: "pe-7s-gift",
                            message: "<b>msg</b>"

                        },{
                            type: type[color],
                            timer: 1000,
                            placement: {
                                from: from,
                                align: align
                            }
                        });
                    },
                };
    </script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

