
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

<li @if(Request::is('admin/transaksi/*'))class="active"@endif>
    @if(Request::is('admin/transaksi/*'))
        <a data-toggle="collapse" href="#nav_transaksi" aria-expanded="true">
    @else
        <a data-toggle="collapse" href="#nav_transaksi">
    @endif
            <i class="pe-7s-expand1"></i>
            <p>Transfer
                <b class="caret"></b>
            </p>
        </a>

    @if(Request::is('admin/transaksi/*'))
    <div class="collapse in" id="nav_transaksi">
    @else
    <div class="collapse" id="nav_transaksi">
    @endif
        <ul class="nav">
            <li @if(Request::is('admin/transaksi/transfer'))class="active"@endif><a href="{{route('admin.transaksi.transfer')}}">Transfer Antar Rekening</a></li>
            {{--<li @if(Request::is('admin/transaksi/pengajuan'))class="active"@endif><a href="{{route('admin.transaksi.pengajuan')}}">Daftar Pengajuan</a></li>--}}
            {{--<li><a href="#">Deposito</a></li>--}}
            {{--<li><a href="#">Pembiayaan</a></li>--}}
        </ul>
    </div>
</li>

<li  @if(Request::is('admin/tabungan/*','admin/pembiayaan/*','admin/deposito/*'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_tabungan">
        <i class="pe-7s-monitor"></i>
        <p>Monitor Transaksi
            <b class="caret"></b>
        </p>
    </a>
    @if(Request::is('admin/tabungan/*','admin/pembiayaan/*','admin/deposito/*','admin/maal/*'))
    <div class="collapse in" id="nav_tabungan">
    @else
    <div class="collapse" id="nav_tabungan">
    @endif
        <ul class="nav">
            <li @if(Request::is('admin/maal'))class="active"@endif><a href="{{route('admin.pengajuan.maal')}}">Pengajuan Maal</a></li>
            <li  @if(Request::is('admin/tabungan/pengajuan'))class="active"@endif><a href="{{route('admin.pengajuan_tabungan')}}">Pengajuan Tabungan</a></li>
            <li  @if(Request::is('admin/tabungan/nasabah'))class="active"@endif><a href="{{route('admin.nasabah_tabungan')}}">Nasabah Tabungan</a></li>
            <li @if(Request::is('admin/deposito/pengajuan'))class="active"@endif><a href="{{route('admin.pengajuan_deposito')}}">Pengajuan Mudharabah Berjangka</a></li>
            <li @if(Request::is('admin/deposito/nasabah'))class="active"@endif><a href="{{route('admin.nasabah_deposito')}}">Nasabah Mudharabah Berjangka</a></li>
            <li @if(Request::is('admin/pembiayaan/pengajuan'))class="active"@endif><a href="{{route('admin.pengajuan_pembiayaan')}}">Pengajuan Pembiayaan</a></li>
            <li @if(Request::is('admin/pembiayaan/nasabah'))class="active"@endif><a href="{{route('admin.nasabah_pembiayaan')}}">Nasabah Pembiayaan</a></li>
        </ul>
    </div>
</li>

<li @if(Request::is('admin/kolektibilitas*'))class="active"@endif>
        <a href="{{route('daftar_kolektibilitas')}}">
            <i class="pe-7s-album"></i>
            <p>Kolektibilitas
            </p>
        </a>
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
            <li @if(Request::is('admin/laporan/anggota'))class="active"@endif><a href="{{route('detail_anggota')}}">Detail Anggota</a></li>
            <li><a href="{{route('kas_harian')}}">Kas Harian</a></li>
            <li><a href="{{route('neraca')}}">Neraca Saldo</a></li>
            <li><a href="{{route('laba_rugi')}}">Laba Rugi</a></li>
            <li><a href="{{route('quitas')}}">Laporan Perubahan Quitas</a></li>
            <li><a href="{{route('buku_besar')}}">Buku Besar</a></li>
            <li><a href="{{route('distribusi')}}">Distribusi Pendapatan</a></li>
            <li><a href="{{route('shu')}}">SHU Tahunan</a></li>
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

<li @if(Request::is('admin/proses/akhirbulan*'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_prosest">
        <i class="pe-7s-medal"></i>
        <p>Proses Akhir Bulan
            <b class="caret"></b>
        </p>
    </a>
</li>

<li @if(Request::is('admin/proses/akhirtahun*'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_prosest">
        <i class="pe-7s-wallet"></i>
        <p>Proses Akhir Tahun
            <b class="caret"></b>
        </p>
    </a>
</li>