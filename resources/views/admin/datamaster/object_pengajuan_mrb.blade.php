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
                <h4 class="title">Datamaster Object Pengajuan MRB</h4>

                <div class="head-filter">
                    <p class="filter-title"> </p>
                    
                </div>

                <div class="button-group right">
                    <button class="btn btn-primary rounded right shadow-effect" data-toggle="modal" data-target="#addObjectPengajuanMRB"><i class="fa fa-user-plus"></i> Tambah Object Pengajuan MRB</button>
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
                            <h4 class="title"><b>Object Pengajuan MRB</b></h4>
                            <p class="category">Object Pengajuan MRB</p>
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
                            <th data-field="nama" data-sortable="true" class="col-xs-7">Nama</th>
                            <th data-field="nama" data-sortable="true" class="col-xs-2">Status</th>
                            <th>Actions</th>
                            </thead>
                            <tbody>
                                @foreach ($data as $obj)
                                <tr>
                                    <td></td>
                                    
                                    <td>{{ $obj->nama   }}</td>
                                    <td><span class="label label-{{ ($obj->is_active === 1 ? 'success' : 'danger') }}">{{ ($obj->is_active === 1 ? "Aktif" : "Tidak Aktif") }}</span></td>
                                    <td>
                                        <button type="button" class="btn btn-social btn-success btn-fill" data-toggle="modal" data-target="#editObjectPengajuanMRB" title="Edit" data-id="{{ $obj->id }}" data-nama="{{ $obj->nama }}" data-is_active='{{ $obj->is_active }}'>
                                            <i class="fa fa-edit"></i>
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
    @include('modal.object_pengajuan_mrb')
@endsection

@section('extra_script')
    <script type="text/javascript">
        $('#editObjectPengajuanMRB').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var nama = button.data('nama');
            var is_active = button.data('is_active'); 
            console.log(nama)
            $('#input-edit-nama').val(nama);
            $('#input-edit-id').val(id);
            if(is_active == 1)
                $('#input-edit-is_active').prop('checked', true);
            else
                $('#input-edit-is_active').prop('checked', false);
        });
    </script>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

