@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection
@section('extra_style')
    <link rel="stylesheet" href="{{asset('css/jquery.signature.css')}}">
    <style>

        #defaultSignature canvas{
            width: 100% !important;
            height: auto;
        }
    </style>
@endsection
@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card side-image">
                <div class="side background-image" style="background-image: url({{ asset('storage/public/rapat/' . $rapat->foto) }})">
                    
                </div>
                <div class="card-body">
                    <h4 class="title large">{{ $rapat->judul }}</h4>
                    <p class="subtitle">Dipublikasikan : {{ Carbon\Carbon::parse($rapat->tanggal_berakhir)->format('D, d F Y') }}</p>
                    <div class="content">
                        <p>{!! $rapat->description !!}</p>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <p style="font-size: 13px; font-weight: bold;">Total Sudah Vote</p>
                            <p style="font-size: 14px;">{{ $rapat->vouting . "/" . $rapat->total_vouter . " (" . $rapat->percentage_vouting . "%)" }}</p>
                        </div>
                        <div class="col-sm-3">
                            <p style="font-size: 13px; font-weight: bold;">Total Belum Vote</p>
                            <p style="font-size: 14px;">{{ $rapat->not_vouting . "/" . $rapat->total_vouter . " (" . $rapat->percentage_not_vouting . "%)" }}</p>
                        </div>
                        <div class="col-sm-3">
                            <p style="font-size: 13px; font-weight: bold;">Vote Setuju</p>
                            <p style="font-size: 14px;">{{ $rapat->total_agree . "/" . $rapat->vouting . " (" . $rapat->percentage_agree . "%)" }}</p>
                        </div>
                        <div class="col-sm-3">
                            <p style="font-size: 13px; font-weight: bold;">Vote Tidak Setuju</p>
                            <p style="font-size: 14px;">{{ $rapat->total_disagree . "/" . $rapat->vouting . " (" . $rapat->percentage_disagree . "%)" }}</p>
                        </div>

                        @if($is_finish_voting)
                            <div class="col-sm-12">
                                <p style="color: red;">Anda sudah mem-voting rapat ini</p>
                            </div>
                        @endif

                    </div>
                    

                    @if(Auth::user()->tipe == "anggota")
                        @if($is_finish_voting == false)
                        <div class="button-group">
                            <button class="btn btn-primary background primary rounded" data-toggle="modal" data-target="#voteModal" data-id_rapat="{{ $rapat->id }}" data-vote="setuju">SETUJU</button>
                            <button class="btn btn-primary background danger rounded" data-toggle="modal" data-target="#voteModal" data-id_rapat="{{ $rapat->id }}" data-vote="tidak_setuju">TIDAK</button>
                        </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>

        @if(Auth::user()->tipe != "anggota")
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="header text-center">
                    <h4 class="title">Data Voter Rapat </h4>
                    <p class="category">Berikut adalah data Voter rapat anda</p>
                    <br />
                </div>

                <table class="table bootstrap-table">
                    <thead>
                        <th></th>
                        <th class="text-left" data-sortable="true" >NIK</th>
                        <th class="text-left" data-sortable="true">NAMA USER</th>
                        <th class="text-left" data-sortable="true">ALAMAT</th>
                        <th class="text-left" data-sortable="true">VOTE</th>
                        <th class="text-left" data-sortable="true">Tanda Tangan</th>
                    </thead>
                    <tbody id="data_rapat">
                        @foreach ($vote as $item)
                            <tr>
                                <td></td>
                                <td>{{ $item->user->no_ktp }}</td>
                                <td>{{ $item->user->nama }}</td>
                                <td>{{ $item->user->alamat }}</td>
                                <td>{{ $item->flag == 1 ? "Setuju" : "Tidak Setuju" }}</td>
                                @if($item->tanda_tangan != "")
                                    <td>
                                    <button type="button" class="btn btn-social btn-primary btn-fill" data-toggle="modal" data-target="#showTandaTanganRapat" title="Edit"
                                            data-gambar      = "{{asset('storage/public/rapat/'.$item->tanda_tangan)}}"
                                            data-nama        = "{{$item->user->nama}}">
                                        <i class="fa fa-list-alt"></i>
                                    </button>
                                    </td>

                                @else
                                    <td>Belum ada tanda tangan</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>


            </div><!--  end card  -->
        </div>
        @endif
    </div>
</div>


@endsection
@include('modal.rapat.vote')
@include('modal.rapat.tanda_tangan')
@section('extra_script')
    <script type="text/javascript" src="{{asset('js/jquery.ui.touch-punch.min.js')}}"></script>
    <script src="{{ asset('bmtmudathemes/assets/js/modal/rapat.js') }}"></script>
    <script src="{{ asset('js/jquery.signature.js') }}"></script>
    <script type="text/javascript">
        var tt = $('#defaultSignature').signature({syncField: '#signature64', syncFormat: 'PNG'});
        $('#clear').click(function(e) {
            e.preventDefault();
            tt.signature('clear');
            $("#signature64").val('');
        });

        $('#voting').click(function(e) {
            var tt = document.getElementById("signature64").value;

            if(tt = null || tt == "")
            {
                window.alert('Tanda tangan di tempat yang tersedia')
            }
        });



    </script>
@endsection