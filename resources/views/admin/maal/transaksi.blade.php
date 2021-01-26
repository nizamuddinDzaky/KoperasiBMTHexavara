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
                <h4 class="title">Riwayat Transaksi Maal</h4>
                @if(Auth::user()->tipe != "anggota")
                <div class="head-noted right">
                    <span>Saldo Maal Terkumpul = <b> Rp. {{ number_format($saldo_terkumpul->saldo> 0 ? $saldo_terkumpul->saldo : 0, 2) }}</b></span>
                </div>
                    @endif
            </div>
        </div>
    </div>

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
                            <h4 class="title"><b>Daftar Transaksi Maal</b></h4>
                            <p class="category">Daftar Transaksi Rekening Maal</p>
                            {{--<br />--}}
                        </div>
                        <div class="toolbar">
                            <!--        Here you can write extra buttons/actions for the toolbar              -->
                            <div class="col-md-12 btn-group">
                                 {{--<div class="col-md-2">--}}
                                    {{--<button class="btn btn-default btn-block" onclick="demo.showNotification('top','right')">Top Right</button>--}}
                                {{--</div>--}}
                            </div>
                            <span></span>
                        </div>

                        <table class="table bootstrap-table-asc">
                            <thead>
                            <th></th>
                            <th data-sortable="true" class="text-left">ID</th>
                            <th class="text-left" data-sortable="true">Tgl Transaksi</th>
                            <th class="text-left" data-sortable="true">Donatur</th>
                            <th class="text-left" data-sortable="true">Kegiatan</th>
                            @if(Auth::user()->tipe!="anggota")
                            <th class="text-left" data-sortable="true">Dari Rekening</th>
{{--                            <th class="text-left" data-sortable="true">Ke Rekening</th>--}}
                            @endif
                            <th class="text-left" data-sortable="true">Jenis Transaksi</th>
                            <th class="text-left" data-sortable="true">Keterangan</th>
                            <th class="text-left" data-sortable="true">Jumlah</th>
                            @if(Auth::user()->tipe!="anggota")
                            <th class="text-left" data-sortable="true">Saldo Akhir</th>
                            @endif
                            {{--<th>Actions</th>--}}
                            </thead>
                            <tbody>
                            @foreach ($data as $usr)
                                <tr>
                                    <td></td>
                                    <td>{{ $usr->id }}</td>
                                    <td>{{ $usr->created_at  }}</td>
                                    @if($usr->nama == 'Umum')
                                        <td style="text-transform: uppercase;">{{ json_decode($usr->transaksi)->nama }}</td>
                                    @else
                                        <td style="text-transform: uppercase;">{{ $usr->nama }}</td>
                                        @endif
                                    <td style="text-transform: uppercase;">{{ $usr->nama_kegiatan  }}</td>
                                    @if(Auth::user()->tipe!="anggota")
                                        @if($usr->nama == 'Umum')
                                            <td style="text-transform: uppercase;">{{ json_decode($usr->transaksi)->no_bank }}</td>
                                        @else
                                            <td>{{ json_decode($usr->transaksi,true)['dari_rekening'] }}</td>
                                            @endif

{{--                                    <td>{{ json_decode($usr->transaksi,true)['untuk_rekening'] }}</td>--}}
                                    @endif
                                    <td>{{ $usr->status }}</td>
                                    @if(!isset(json_decode($usr->transaksi)->keterangan))
                                        <td>-</td>
                                    @else
                                        <td>{{json_decode($usr->transaksi)->keterangan}}</td>
                                    @endif

                                    @if($usr->status == "Pencairan Donasi")
                                        <td class="text-left">({{ number_format(json_decode($usr->transaksi,true)['jumlah'],2) }})</td>
                                    @else
                                        <td class="text-left">{{ number_format(json_decode($usr->transaksi,true)['jumlah'],2) }}</td>
                                        @endif
                                    @if(Auth::user()->tipe!="anggota")
                                    <td class="text-left">{{ number_format(json_decode($usr->transaksi,true)['saldo_akhir'],2) }}</td>
                                    @endif

                                    {{--<td class="td-actions text-center">--}}
                                        {{--<button type="button" class="btn btn-social btn-info btn-fill" data-toggle="modal" data-target="#editPassUsrModal" title="Ubah Password"--}}
                                                {{--data-id      = "{{$usr->no_ktp}}"--}}
                                                {{--data-nama    = "{{$usr->nama}}">--}}
                                            {{--<i class="fa fa-key"></i>--}}
                                        {{--</button>--}}

                                        {{--<button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editUsrModal" title="Edit"--}}
                                                          {{--data-id      = "{{$usr->no_ktp}}"--}}
                                                          {{--data-nama    = "{{$usr->nama}}"--}}
                                                          {{--data-alamat    = "{{$usr->alamat}}"--}}
                                                          {{--data-tipe    = "{{$usr->tipe}}"--}}
                                                          {{--data-p    = "{{$usr->password}}">--}}
                                            {{--<i class="fa fa-edit"></i>--}}
                                        {{--</button>--}}
                                        {{--<button type="button"  class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#delUsrModal" title="Delete"--}}
                                                {{--data-id         = "{{$usr->no_ktp}}"--}}
                                                {{--data-nama       = "{{$usr->nama}}">--}}
                                            {{--<i class="fa fa-remove"></i>--}}
                                        {{--</button>--}}
                                        {{--<a class="btn btn-social btn-danger btn-fill" data-toggle="modal" data-target="#deleteUsrModal" title="Delete">--}}
                                        {{--<i class="fa fa-remove"></i>--}}
                                        {{--</a>--}}
                                    {{--</td>--}}
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
               $('#id_edit').val(id);
               $('#id_usr_edit').val(id);
               $('#nama_usr').val(nama);
               $('#alamat_usr').val(alamat);
               $('#tipe_usr').val(tipe);
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

        $().ready(function(){
            var jenis = $('#toShow');
            var jenis2 = $('#toHideUsr');
            var selRek = $('#jenistipe');
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

        });

    </script>

    <script type="text/javascript">
        $().ready(function(){
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

