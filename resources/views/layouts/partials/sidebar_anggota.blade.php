<li @if(Request::is('anggota/pengajuan*'))class="active"@endif>
    <a href="{{route('pengajuan')}}">
        <i class="pe-7s-next-2"></i>
        <p>Pengajuan</p>
    </a>
</li>
<li @if(Request::is('anggota/menu*'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_tabuser">
        <i class="pe-7s-tools"></i>
        <p>Transaksi Anggota
        <b class="caret"></b></p>
    </a>
    @if(Request::is('anggota/menu/tabungan*','anggota/menu/deposito*','anggota/menu/pembiayaan*'))
    <div class="collapse in" id="nav_tabuser">
    @else
    <div class="collapse" id="nav_tabuser">
    @endif
        <ul class="nav">
            <li @if(Request::is('anggota/menu/tabungan*'))class="active"@endif><a href="{{route('tabungan_anggota')}}">Tabungan</a></li>
            <li @if(Request::is('anggota/menu/deposito*'))class="active"@endif><a href="{{route('deposito_anggota')}}">Mudharabah Berjangka</a></li>
            <li @if(Request::is('anggota/menu/pembiayaan*'))class="active"@endif><a href="{{route('pembiayaan_anggota')}}">Pembiayaan</a></li>
            <li><a href="#">Simpanan Wajib</a></li>
            <li><a href="#">Simpanan Khusus</a></li>
        </ul>
    </div>
</li>

<li @if(Request::is('anggota/maal*','teller/maal*')) class="active"@endif>
    <a data-toggle="collapse" href="#nav_maalt">
        <i class="pe-7s-home"></i>
        <p>Maal
            <b class="caret"></b>
        </p>
    </a>
    @if(Request::is('anggota/maal*','teller/maal*'))
    <div class="collapse in" id="nav_maalt">
    @else
    <div class="collapse" id="nav_maalt">
    @endif
        <ul class="nav">
            <li @if(Request::is('anggota/maal/donasi*','teller/maal/donasi*'))class="active"@endif><a @if(Auth::user()->tipe=="anggota") href="{{route('anggota.donasi.maal')}}" @elseif(Auth::user()->tipe=="teller") href="{{route('teller.donasi.maal')}}" @endif>Donasi Kegiatan</a></li>
            @if(Auth::user()->tipe=="teller")
            <li @if(Request::is('anggota/maal/daftar*'))class="active"@endif><a @if(Auth::user()->tipe=="teller") href="{{route('teller.maal')}}" @endif>Daftar Kegiatan</a></li>
            @endif
            <li @if(Request::is('teller/maal/transaksi*'))class="active"@endif><a @if(Auth::user()->tipe=="teller") href="{{route('teller.transaksi.maal')}}" @elseif(Auth::user()->tipe=="anggota") href="{{route('anggota.transaksi.maal')}}" @endif>Riwayat Transaksi</a></li>
        </ul>
    </div>
</li>

<li @if(Request::is('anggota/datadiri*','teller/datadiri*')) class="active"@endif>
    <a @if(Auth::user()->tipe=="anggota")href="{{route('datadiri')}}"@elseif(Auth::user()->tipe=="teller")href="{{route('teller.datadiri')}}@endif">
        <i class="pe-7s-id"></i>
        <p>Rapat</p>
    </a>
</li>

{{-- <li @if(Request::is('anggota/datadiri*','teller/datadiri*')) class="active"@endif>
    <a @if(Auth::user()->tipe=="anggota")href="{{route('datadiri')}}"@elseif(Auth::user()->tipe=="teller")href="{{route('teller.datadiri')}}@endif">
        <i class="pe-7s-user"></i>
        <p>Profile</p>
    </a>
</li> --}}