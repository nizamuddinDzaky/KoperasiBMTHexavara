@extends('layouts.apps')

@section('side-navbar')
    @include('layouts.side_navbar')
@endsection

@section('top-navbar')
    @include('layouts.top_navbar')
@endsection
@section('extra_style')

@endsection
@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card side-image">
                <div class="side background-image" style="background-image: url({{ asset('storage/public/rapat/' . $rapat->foto) }})">
                    
                </div>
                <div class="card-body">
                    <div class="date">
                        <div>
                            <span class="label large">Tanggal Dibuat</span>
                            <p class="content large">{{ Carbon\Carbon::parse($rapat->tanggal_dibuat)->format('D, d F Y') }}</p>
                        </div>
                        <div>
                            <span class="label large">Tanggal Berakhir</span>
                            <p class="content large">{{ Carbon\Carbon::parse($rapat->tanggal_berakhir)->format('D, d F Y') }}</p>
                        </div>
                    </div>
                    <h4 class="title large">{{ $rapat->judul }}</h4>
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

                        @if(count($rapat->vote) > 0)
                            @foreach ($rapat->vote as $item)
                                @if($item->id_user == Auth::user()->id && $item->id_rapat == $id_rapat)
                                <div class="col-sm-12">
                                    <p style="color: red;">Anda sudah mem-voting rapat ini</p>
                                </div>
                                @endif
                            @endforeach
                        @endif

                    </div>
                    

                    @if(Auth::user()->tipe == "anggota")
                        @if(count($rapat->vote) > 0)
                            @foreach ($rapat->vote as $item)
                                @if($item->id_user !== Auth::user()->id && $item->id_rapat !== $id_rapat)
                                <div class="button-group">
                                    <button class="btn btn-primary background primary rounded" data-toggle="modal" data-target="#voteModal" data-id_rapat="{{ $rapat->id }}" data-vote="setuju">SETUJU</button>
                                    <button class="btn btn-primary background danger rounded" data-toggle="modal" data-target="#voteModal" data-id_rapat="{{ $rapat->id }}" data-vote="tidak_setuju">TIDAK</button>
                                </div>
                                @endif
                            @endforeach
                        @else
                            <div class="button-group">
                                <button class="btn btn-primary background primary rounded" data-toggle="modal" data-target="#voteModal" data-id_rapat="{{ $rapat->id }}" data-vote="setuju">SETUJU</button>
                                <button class="btn btn-primary background danger rounded" data-toggle="modal" data-target="#voteModal" data-id_rapat="{{ $rapat->id }}" data-vote="tidak_setuju">TIDAK</button>
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@include('modal.rapat.vote')

@endsection

@section('extra_script')

    <script src="{{ asset('bmtmudathemes/assets/js/modal/rapat.js') }}"></script>

@endsection