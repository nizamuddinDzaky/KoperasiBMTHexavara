
<li @if(Request::is('admin/datamaster/*'))class="active"@endif>

    @if(Request::is('admin/datamaster/*','admin/datamaster/anggota/*'))
    <a data-toggle="collapse" href="#nav_datamaster" aria-expanded="true">
    @else
    <a data-toggle="collapse" href="#nav_datamaster">
    @endif
        <i class="pe-7s-server"></i>
        <p>Data master
            <b class="caret"></b>
        </p>
    </a>

    @if(Request::is('admin/datamaster/*'))
        <div class="collapse in" id="nav_datamaster">
    @else
        <div class="collapse" id="nav_datamaster">
    @endif
        <ul class="nav">
            <li @if(Request::is('admin/datamaster/anggota'))class="active"@endif><a href="{{route('data_anggota')}}">Master Anggota</a></li>
            <li @if(Request::is('admin/datamaster/rekening'))class="active"@endif><a href="{{route('data_rekening')}}">Master Rekening</a></li>
            <li @if(Request::is('admin/datamaster/tabungan'))class="active"@endif><a href="{{route('data_tabungan')}}">Master Tabungan</a></li>
            <li @if(Request::is('admin/datamaster/deposito'))class="active"@endif><a href="{{route('data_deposito')}}">Master Mudharabah Berjangka</a></li>
            <li @if(Request::is('admin/datamaster/pembiayaan'))class="active"@endif><a href="{{route('data_pembiayaan')}}">Master Pembiayaan</a></li>
            <li @if(Request::is('admin/datamaster/shu'))class="active"@endif><a href="{{route('data_shu')}}">Master SHU</a></li>
            <li @if(Request::is('admin/datamaster/jaminan'))class="active"@endif><a href="{{route('data_jaminan')}}">Master Jaminan</a></li>
        </ul>
    </div>
</li>

<li @if(Request::is('admin/transfer/*'))class="active"@endif>
    @if(Request::is('admin/transfer/*'))
        <a data-toggle="collapse" href="#nav_transaksi" aria-expanded="true">
    @else
        <a data-toggle="collapse" href="#nav_transaksi">
    @endif
            <i class="pe-7s-expand1"></i>
            <p>Transfer
                <b class="caret"></b>
            </p>
        </a>

    @if(Request::is('admin/transfer/*'))
    <div class="collapse in" id="nav_transaksi">
    @else
    <div class="collapse" id="nav_transaksi">
    @endif
        <ul class="nav">
            <li @if(Request::is('admin/transfer/transfer'))class="active"@endif><a href="{{route('admin.transaksi.transfer')}}">Transfer Antar Rekening</a></li>
            {{-- <li><a href="#">Kas Masuk</a></li>
            <li><a href="#">Kas Keluar</a></li> --}}
            {{--<li @if(Request::is('admin/transaksi/pengajuan'))class="active"@endif><a href="{{route('admin.transaksi.pengajuan')}}">Daftar Pengajuan</a></li>--}}
            {{--<li><a href="#">Deposito</a></li>--}}
            {{--<li><a href="#">Pembiayaan</a></li>--}}
        </ul>
    </div>
</li>

<li  @if(Request::is('admin/tabungan/*','admin/pembiayaan/*','admin/deposito/*','admin/transaksi/*'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_tabungan">
        <i class="pe-7s-monitor"></i>
        <p>Monitor Transaksi
            <b class="caret"></b>
        </p>
    </a>
    @if(Request::is('admin/transaksi/*'))
    <div class="collapse in" id="nav_tabungan">
    @else
    <div class="collapse" id="nav_tabungan">
    @endif
        <ul class="nav">
            <li @if(Request::is('admin/transaksi/simpanan'))class="active"@endif><a href="{{route('admin.transaksi.simpanan')}}">Simpanan Anggota</a></li>
            <li @if(Request::is('admin/transaksi/tabungan'))class="active"@endif><a href="{{route('admin.transaksi.tabungan')}}">Tabungan</a></li>
            <li @if(Request::is('admin/transaksi/deposito'))class="active"@endif><a href="{{route('admin.transaksi.deposito')}}">Mudharabah Berjangka</a></li>
            <li @if(Request::is('admin/transaksi/kolektibilitas'))class="active"@endif><a href="{{route('admin.transaksi.kolektibilitas')}}">Kolektibilitas</a></li>
            <li @if(Request::is('admin/transaksi/realisasi'))class="active"@endif><a href="{{route('admin.transaksi.realisasi_pembiayaan')}}">Realisasi Pembiayaan</a></li>
        </ul>
    </div>
</li>

<li @if(Request::is('admin/laporan/*'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_laporan">
        <i class="pe-7s-folder"></i>
        <p>Laporan
            <b class="caret"></b>
        </p>
    </a>
    @if(Request::is('admin/laporan/*'))
    <div class="collapse in" id="nav_laporan">
    @else
    <div class="collapse" id="nav_laporan">
    @endif
        <ul class="nav">
            <li class="@if(Request::is('admin/laporan/distribusi')) active @endif"><a href="{{route('distribusi')}}">Distribusi Pendapatan</a></li>
            <li class="@if(Request::is('admin/laporan/buku')) active @endif"><a href="{{route('buku_besar')}}">Buku Besar</a></li>
            <li class="@if(Request::is('admin/laporan/neraca')) active @endif"><a href="{{route('neraca')}}">Neraca Saldo</a></li>
            <li class="@if(Request::is('admin/laporan/laba_rugi')) active @endif"><a href="{{route('laba_rugi')}}">Laporan SHU</a></li>
            <li class="@if(Request::is('admin/laporan/shu')) active @endif"><a href="{{route('shu')}}">Distribusi SHU Tahunan</a></li>
            <li class="@if(Request::is('admin/laporan/quitas')) active @endif"><a href="{{route('quitas')}}">Laporan Perubahan Quitas</a></li>
            <li class="@if(Request::is('admin/laporan/saldo_zis')) active @endif"><a href="{{route('admin.saldo.zis')}}">Saldo ZIS</a></li>
            <li class="@if(Request::is('admin/laporan/saldo_donasi')) active @endif"><a href="{{route('admin.saldo.donasi')}}">Saldo Donasi</a></li>
            <li class="@if(Request::is('admin/laporan/saldo_wakaf')) active @endif"><a href="{{route('admin.saldo.wakaf')}}">Saldo Wakaf</a></li>
            <li><a href="{{route('quitas')}}">Laporan Keuangan</a></li>


            {{-- <li><a href="{{route('kas_harian')}}">Kas Harian</a></li>
            <li><a href="{{route('laba_rugi')}}">Laba Rugi</a></li> --}}
            {{--<li><a href="{{route('pengajuan_pem')}}">Pengajuan Pembiayaan</a></li>--}}
            {{--<li><a href="{{route('realisasi_pem')}}">Realisasi Pembiayaan</a></li>--}}
            {{--<li><a href="{{route('daftar_kolektibilitas')}}">Daftar Kolektibilitas</a></li>--}}
            {{--<li><a href="{{route('rekap_jurnal')}}">Rekapitulasi Jurnal</a></li>--}}
            {{--<li><a href="{{route('rekapitulasi_kas')}}">Rekapitulasi Kas</a></li>--}}
            {{--<li><a href="{{route('pendapatan')}}">Pendapatan</a></li>
            <li><a href="{{route('aktiva')}}">Aktiva</a></li>--}}
            {{--<li><a href="{{route('jatuh_tempo')}}">Jatuh Tempo</a></li>--}}
            {{--<li><a href="{{route('kredit_macet')}}">Kredit Macet</a></li>--}}
            {{--<li><a href="{{route('transaksi_kas')}}">Transaksi Kas</a></li>--}}
            {{--<li><a href="{{route('simpanan')}}">Kas Simpanan</a></li>--}}
            {{--<li><a href="{{route('pinjaman')}}">Kas Pinjaman</a></li>--}}
            {{--<li><a href="{{route('saldo')}}">Saldo Kas</a></li>--}}
        </ul>
    </div>
</li>

<li @if(Request::is('admin/proses_akhir_bulan/*'))class="active"@endif>
    <a href="{{ route('admin.proses_akhir_bulan.index') }}">
        <i class="pe-7s-medal"></i>
        <p>Proses Akhir Bulan</p>
    </a>
</li>

<li @if(Request::is('admin/proses/akhirtahun*'))class="active"@endif>
    <a href="{{ route('admin.proses_akhir_tahun.index') }}">
        <i class="pe-7s-wallet"></i>
        <p>Proses Akhir Tahun</p>
    </a>
</li>

<li @if(Request::is('admin/rapat/*')) class="active"@endif>
    <a href="{{route('admin.rapat.index')}}">
        <i class="pe-7s-users"></i>
        <p>RAPAT</p>
    </a>
</li>

<li @if(Request::is('admin/maal*')) class="active"@endif>
    <a data-toggle="collapse" href="#nav_maal">
        <i class="pe-7s-home"></i>
        <p>Maal
        <b class="caret"></b></p>
    </a>
    @if(Request::is('admin/maal*'))
    <div class="collapse in" id="nav_maal">
    @else
    <div class="collapse" id="nav_maal">
    @endif
        <ul class="nav">
            <li @if(Request::is('admin/maal/daftar*'))class="active"@endif><a @if(Auth::user()->tipe=="admin") href="{{route('admin.maal')}}" @elseif(Auth::user()->tipe=="teller") href="{{route('teller.maal')}}" @endif>Daftar Kegiatan</a></li>
            <li @if(Request::is('admin/maal/transaksi*'))class="active"@endif><a @if(Auth::user()->tipe=="admin") href="{{route('admin.transaksi.maal')}}" @elseif(Auth::user()->tipe=="teller") href="{{route('teller.transaksi.maal')}}" @endif>Riwayat Transaksi</a></li>
        </ul>
    </div>
</li>