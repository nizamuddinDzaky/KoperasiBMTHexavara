@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection
@section('extra_style')
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet"/>
    <style>
        .fa-3x {
            font-size: 5vmax;}
        h3 {
            font-size: 2vw !important;}
    </style>
@endsection
@section('content')
    
    <div class="head">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <h4 class="title">Data Rapat BMT</h4>

                <div class="head-filter">
                    <p class="filter-title">Periode</p>
                    <form @if(Auth::user()->tipe=="admin")action="{{route('periode.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")action="{{route('teller.periode.pengajuan')}}" @endif method="post">
                    {{ csrf_field() }}
                        <select required  name="periode" class="beautiful-select" style="height: 1.9em">
                            <option disabled selected > - Periode -</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="panel-group">
            <div class="panel panel-primary">
              <div class="panel-heading">Buat Rapat Baru</div>
              <div class="panel-body">
                <form class="wizardForm" method="POST" @if(Auth::user()->tipe=="admin") action="{{route('rapat.store')}}" @ENDIF enctype="multipart/form-data">
                    {{csrf_field()}}
                    @if(Auth::user()->tipe!="anggota")
                        <input type="hidden" name="teller" value="teller">
                    @endif
                                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="namaSim" class="control-label">Judul Rapat <star>*</star></label>
                                <input type="text" class="form-control" name="judul" placeholder="Pilih Judul Rapat">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="namaSim" class="control-label">Tanggal Berakhir <star>*</star></label>
                                <input type="text" name="tanggal_berakhir" class="form-control datepicker" placeholder="Tanggal Berakhir">
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="namaSim" class="control-label">Deskripsi <star>*</star></label>
                                <textarea class="summernote" name="deskripsi"></textarea>
                            </div>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12 {{ !$errors->has('file') ?: 'has-error' }}">
                            <div class="form-group">
                                <label>Upload Cover</label><br>
                                <span class="btn btn-info btn-fill btn-file"> Browse
                                    <input type="file" onchange="readURL(this)" name="file" accept=".jpg, .png, .jpeg|images/*" required="true"/>
                                </span><br><br>
                                <span class="help-block text-danger">{{ $errors->first('file') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 {{ !$errors->has('file') ?: 'has-error' }}">
                            <div class="text-center">
                                <img style="margin: auto; width:500px;" class="pic" src=""/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                          <button type="submit" class="btn btn-info btn-fill btn-wd btn-finish">Buat </button>
                          <button type="button" class="btn btn-secondary" onclick="return window.history.back()" style="margin-right: 0.5em">Batal</button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        </div>
    </div>

@endsection