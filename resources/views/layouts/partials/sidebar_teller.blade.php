{{-- <li @if(Request::is('teller/transaksi/*'))class="active"@endif>
    @if(Request::is('teller/transaksi/*','teller/transaksi/pengajuan*','teller/transaksi/transfer'))
        <a data-toggle="collapse" href="#nav_transaksi" aria-expanded="true">
    @else
        <a data-toggle="collapse" href="#nav_transaksi">
    @endif
            <i class="pe-7s-expand1"></i>
            <p>Transfer
                <b class="caret"></b>
            </p>
        </a>
    @if(Request::is('teller/transaksi/pengajuan*','teller/transaksi/transfer'))
    <div class="collapse in" id="nav_transaksi">
    @else
    <div class="collapse" id="nav_transaksi">
    @endif
        <ul class="nav">
            <li @if(Request::is('teller/transaksi/transfer'))class="active"@endif><a href="{{route('teller.transaksi.transfer')}}">Transfer Antar Rekening</a></li>
            <li @if(Request::is('teller/transaksi/pengajuan'))class="active"@endif><a href="{{route('teller.transaksi.pengajuan')}}">Daftar Pengajuan</a></li>
        </ul>
    </div>
</li> --}}


<li @if(Request::is('teller/menu*', 'teller/transaksi/pengajuan/daftar_penutupan_rekening'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_tabteller">
        <i class="pe-7s-monitor"></i>
        <p>Monitor Transaksi
            <b class="caret"></b></p>
    </a>
    @if(Request::is('teller/menu/maal*','teller/menu/tabungan*','teller/menu/deposito*','teller/menu/pembiayaan*','teller/menu/pengajuan_simpanan*','teller/transaksi/pengajuan/daftar_penutupan_rekening'))
        <div class="collapse in" id="nav_tabteller">
    @else
        <div class="collapse" id="nav_tabteller">
    @endif
        <ul class="nav">
            <li @if(Request::is('teller/menu/maal*'))class="active"@endif><a href="{{route('teller.pengajuan_maal')}}">Pengajuan Maal</a></li>
            <li @if(Request::is('teller/menu/tabungan*'))class="active"@endif><a href="{{route('pengajuan_tabungan')}}">Pengajuan Tabungan</a></li>
            <li @if(Request::is('teller/menu/deposito*'))class="active"@endif><a href="{{route('pengajuan_deposito')}}">Pengajuan Mudharabah Berjangka</a></li>
            {{-- <li @if(Request::is('teller/nasabah/deposito*'))class="active"@endif><a href="{{route('nasabah_deposito')}}">Nasabah Mudharabah Berjangka</a></li> --}}
            <li @if(Request::is('teller/menu/pembiayaan*'))class="active"@endif><a href="{{route('pengajuan_pembiayaan')}}">Pengajuan Pembiayaan</a></li>
            <li @if(Request::is('teller/menu/pengajuan_simpanan*'))class="active"@endif><a href="{{ route('teller.transaksi.pengajuan_simpanan') }}">Pengajuan Simpanan Anggota</a></li>
            <li @if(Request::is('teller/transaksi/pengajuan/daftar_penutupan_rekening'))class="active"@endif><a href="{{ route('teller.transaksi.pengajuan_penutupan_rekening') }}">Pengajuan Penutupan Rekening</a></li>
            {{-- <li @if(Request::is('teller/nasabah/pembiayaan*'))class="active"@endif><a href="{{route('nasabah_pembiayaan')}}">Nasabah Pembiayaan</a></li> --}}
        </ul>
        </div>
</li>

<li @if(Request::is('teller/transaksi/transfer'))class="active"@endif>
    @if(Request::is('teller/transaksi/transfer'))
        <a data-toggle="collapse" href="#nav_transaksi" aria-expanded="true">
    @else
        <a data-toggle="collapse" href="#nav_transaksi">
    @endif
            <i class="pe-7s-expand1"></i>
            <p>Transaksi
                <b class="caret"></b>
            </p>
        </a>
    @if(Request::is('teller/transaksi/transfer'))
    <div class="collapse in" id="nav_transaksi">
    @else
    <div class="collapse" id="nav_transaksi">
    @endif
        <ul class="nav">
            <li @if(Request::is('teller/transaksi/transfer'))class="active"@endif><a href="{{route('teller.transaksi.transfer')}}">Kelola Kas</a></li>
            {{--<li @if(Request::is('teller/transaksi/pengajuan'))class="active"@endif><a href="{{route('teller.transaksi.pengajuan')}}">Daftar Pengajuan</a></li>--}}
        </ul>
    </div>
</li>


{{--<li @if(Request::is('teller/kolektibilitas*'))class="active"@endif>
    <a href="{{route('teller.daftar_kolektibilitas')}}">
        <i class="pe-7s-album"></i>
        <p>Kolektibilitas
        </p>
    </a>
</li>--}}

<li @if(Request::is('teller/nasabah/*', 'teller/kolektibilitas', 'teller/laporan/*'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_laporan">
        <i class="pe-7s-folder"></i>
        <p>Laporan
            <b class="caret"></b>
        </p>
    </a>
    @if(Request::is('teller/laporan/*','teller/nasabah/*', 'teller/kolektibilitas', 'rapat/admin'))
    <div class="collapse in" id="nav_laporan">
    @else
    <div class="collapse" id="nav_laporan">
    @endif
        <ul class="nav">
            <li @if(Request::is('teller/laporan/kas_harian')) class="active" @endif><a href="{{route('teller.kas_harian')}}">Kas Harian</a></li>
            <li @if(Request::is('teller/laporan/realisasi')) class="active" @endif><a href="{{route('teller.realisasi_pem')}}">Realisasi Pembiayaan</a></li>
            <li class="@if(Request::is('admin/laporan/buku')) active @endif"><a href="{{route('teller.buku_besar')}}">Buku Besar</a></li>
            <li @if(Request::is('teller/kolektibilitas')) class="active" @endif><a href="{{route('teller.daftar_kolektibilitas')}}">Kolektibilitas</a></li>
            <li @if(Request::is('teller/laporan/neraca')) class="active" @endif><a href="{{route('teller.neraca')}}">Neraca Saldo</a></li>
            <li><a href="{{route('teller.laba_rugi')}}">Laba Rugi</a></li>
            <li @if(Request::is('teller/nasabah/tabungan')) class="active" @endif><a href="{{route('nasabah_tabungan')}}">Saldo Tabungan</a></li>
            <li @if(Request::is('teller/nasabah/deposito')) class="active" @endif><a href="{{route('nasabah_deposito')}}">Saldo Mudharabah Berjangka</a></li>
            <li @if(Request::is('teller/nasabah/pembiayaan')) class="active" @endif><a href="{{route('nasabah_pembiayaan')}}">Saldo Pembiayaan</a></li>
            <li @if(Request::is('teller/laporan/saldo_zis')) class="active" @endif><a href="{{ route('teller.saldo.zis') }}">Saldo ZIS</a></li>
            <li @if(Request::is('teller/laporan/saldo_donasi')) class="active" @endif><a href="{{ route('teller.saldo.donasi') }}">Saldo Donasi</a></li>
            <li @if(Request::is('teller/laporan/saldo_wakaf')) class="active" @endif><a href="{{ route('teller.saldo.wakaf') }}">Saldo Wakaf</a></li>
            <li @if(Request::is('rapat/admin')) class="active" @endif><a href="{{ route('admin.rapat.index') }}">Rapat</a></li>

            {{--<li><a href="{{route('teller.pengajuan_pem')}}">Pengajuan Pembiayaan</a></li>
            <li><a href="{{route('teller.realisasi_pem')}}">Realisasi Pembiayaan</a></li>
            <li><a href="{{route('teller.kas_harian')}}">Kas Harian</a></li>
            <li><a href="{{route('teller.neraca')}}">Neraca Saldo</a></li>
            <li><a href="{{route('distribusi')}}">Distribusi Pendapatan</a></li>--}}
            
            {{-- <li><a href="{{route('teller.daftar_kolektibilitas')}}">Daftar Kolektibilitas</a></li>--}}
            {{--<li><a href="{{route('teller.rekap_jurnal')}}">Rekapitulasi Jurnal</a></li>--}}
            {{--<li><a href="{{route('teller.rekapitulasi_kas')}}">Rekapitulasi Kas</a></li>--}}
            {{--<li><a href="{{route('pendapatan')}}">Pendapatan</a></li>--}}
            {{--<li><a href="{{route('aktiva')}}">Aktiva</a></li>--}}
            {{--<li><a href="{{route('teller.jatuh_tempo')}}">Jatuh Tempo</a></li>--}}
            {{--<li><a href="{{route('teller.kredit_macet')}}">Kredit Macet</a></li>--}}
            {{--<li><a href="{{route('teller.transaksi_kas')}}">Transaksi Kas</a></li>--}}
            {{-- <li><a href="{{route('teller.buku_besar')}}">Buku Besar</a></li>-->
            {{--<li><a href="{{route('teller.simpanan')}}">Kas Simpanan</a></li>--}}
            {{--<li><a href="{{route('teller.pinjaman')}}">Kas Pinjaman</a></li>--}}
            {{--<li><a href="{{route('teller.saldo')}}">Saldo Kas</a></li>--}}
            {{--<li><a href="{{route('shu')}}">SHU</a></li>--}}

        </ul>
    </div>
</li>

{{--<li @if(Request::is('anggota/maal*','teller/maal*')) class="active"@endif>
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
</li>--}}