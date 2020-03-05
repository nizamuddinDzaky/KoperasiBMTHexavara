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
                            <h4 id="titlePrint" class="title"><b>Detail Anggota BMT</b></h4>
                            <p id="titlePrint2" class="category">Daftar Nasabah</p>
                            {{--<br />--}}
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <div class="col-md-12 btn-group">
                                <button type="button" class="btn btn-primary btn-fill" style="margin-bottom:1em" data-toggle="modal" data-target="#addUsrModal" title="Tambah Anggota">Tambah User
                                    <i class="pe-7s-add-user"></i>
                                </button>
                                {{--<div class="col-md-2">--}}
                                    {{--<button class="btn btn-default btn-block" onclick="demo.showNotification('top','right')">Top Right</button>--}}
                                {{--</div>--}}
                            </div>
                            <span></span>
                        </div>

                        <table id="bootstrap-table" class="table">
                            <thead>
                            <th></th>
                            <th data-field="id" data-sortable="true" class="text-left">No KTP</th>
                            <th data-field="id" data-sortable="true" class="text-left">NIK</th>
                            <th data-field="nama" data-sortable="true">Nama</th>
                            <th data-field="nama" data-sortable="true">Telepon</th>
                            <th data-field="nama" data-sortable="true">Jenis Kelamin</th>
                            <th data-field="nama" data-sortable="true">TTL</th>
                            <th data-field="alamat" data-sortable="true">Tgl Daftar</th>
                            <th data-field="alamat" data-sortable="true">Alamat KTP</th>
                            <th data-field="alamat" data-sortable="true">Alamat Domisili</th>
                            <th data-field="jenis" data-sortable="true">Pendidikan</th>
                            <th data-field="jenis" data-sortable="true">Pekerjaan</th>
                            <th data-field="jenis" data-sortable="true">Pendapatan/bln</th>
                            <th data-field="jenis" data-sortable="true">Alamat Pekerjaan</th>
                            <th data-field="jenis" data-sortable="true">Status Pernikahan</th>
                            <th data-field="jenis" data-sortable="true">Suami/Istri</th>
                            <th data-field="jenis" data-sortable="true">Ayah</th>
                            <th data-field="jenis" data-sortable="true">Ibu</th>
                            <th data-field="jenis" data-sortable="true">Jumlah Istri/Suami</th>
                            <th data-field="jenis" data-sortable="true">Jumlah Anak</th>
                            <th data-field="jenis" data-sortable="true">Jumlah Orang Tua</th>
                            <th data-field="jenis" data-sortable="true">Lain-lain</th>
                            <th data-field="jenis" data-sortable="true">Status Rumah</th>
                            <th data-field="registrasi" data-sortable="true">Detail</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $usr)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->no_ktp }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['nik'])?json_decode($usr->detail,true)['nik']:"" }}</td>
                                    <td>{{ $usr->nama   }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['telepon'])?json_decode($usr->detail,true)['telepon']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['jenis_kelamin'])?json_decode($usr->detail,true)['jenis_kelamin']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['tempat_lahir'])?json_decode($usr->detail,true)['tempat_lahir']:"" }}, {{ isset(json_decode($usr->detail,true)['tgl_lahir'])?json_decode($usr->detail,true)['tgl_lahir']:"" }}</td>
                                    <td>{{ $usr->created_at }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['alamat_ktp'])?json_decode($usr->detail,true)['alamat_ktp']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['alamat_domisili'])?json_decode($usr->detail,true)['alamat_domisili']:"" }}</td>
                                    @if(!isset( json_decode($usr->detail,true)['pendidikan']))
                                        <td>-</td>
                                    @elseif( json_decode($usr->detail,true)['pendidikan']== 0 )
                                        <td>Tidak Bekerja</td>
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

                                    <td>{{ isset(json_decode($usr->detail,true)['alamat_pekerjaan'])?json_decode($usr->detail,true)['alamat_pekerjaan']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['status'])?json_decode($usr->detail,true)['status']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['nama_wali'])?json_decode($usr->detail,true)['nama_wali']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['ayah'])?json_decode($usr->detail,true)['ayah']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['ibu'])?json_decode($usr->detail,true)['ibu']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['jml_sumis'])?json_decode($usr->detail,true)['jml_sumis']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['jml_anak'])?json_decode($usr->detail,true)['jml_anak']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['jml_ortu'])?json_decode($usr->detail,true)['jml_ortu']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['lain'])?json_decode($usr->detail,true)['lain']:"" }}</td>
                                    <td>{{ isset(json_decode($usr->detail,true)['rumah'])?json_decode($usr->detail,true)['rumah']:"" }}</td>

                                    

                                    {{--<td class="text-uppercase text-center">{{ $usr->tipe }}</td>--}}
                                    {{--<td class="text-uppercase text-center">{{ $usr->role }}</td>--}}

                                    {{--@if($usr->status == 2)--}}
                                        {{--<td class="text-uppercase text-center">Anggota Aktif</td>--}}
                                    {{--@elseif($usr->tipe=="admin")--}}
                                        {{--<td class="text-uppercase text-center">-</td>--}}
                                    {{--@else--}}
                                        {{--<td class="text-uppercase text-center"> Belum Mengisi Identitas</td>--}}
                                    {{--@endif--}}
                                    <td class="text-uppercase text-center">
                                        @if($usr->tipe=="admin")
                                            <a href="{{route('profile')}}" >
                                            <button type="submit" class="btn btn-social btn-info btn-fill" title="Detail Anggota">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            </a>
                                        @else
                                        <form id="wizardForm" method="POST"  action="{{route('showdetailanggota')}}" enctype="multipart/form-data">
                                            {{csrf_field()}}
                                            <button type="submit" class="btn btn-social btn-info btn-fill" title="Detail Anggota">
                                                <i class="fa fa-list"></i>
                                            </button>
                                            <input type="hidden" name="noktp" value="{{ $usr->no_ktp }}">
                                        </form>
                                        @endif

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

    @include('modal.anggota')

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
               // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
               // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
               if( tipe=="teller")
                   $('#toShow2').show();
               else $('#toShow2').hide();

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
                "scrollX": true,
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
                        {
                            extend: 'print',
                            title: function () { return  $('#titlePrint2').text()+"\n"+$('#titlePrint2').text(); },
                        },

                        'copyHtml5',
                        {
                            extend: 'excelHtml5',
                            messageTop: function () { return  $('#titlePrint').text(); },
                            messageTop: function () { return  $('#titlePrint2').text(); },
                        },
                        {
                            extend:'pdfHtml5',
                            title: function () { return  $('#titlePrint').text()+"\n"+$('#titlePrint2').text(); },
                            customize: function(doc) {
                                doc.defaultStyle.fontSize = 7;
                                doc.styles.title = {
                                    fontSize: '11',
                                    alignment: 'center'
                                };
                                doc.content.layout='Border';
                            }
                        }
                    ]
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

