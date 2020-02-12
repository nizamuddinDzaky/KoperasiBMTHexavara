<li @if(Request::is('teller/transaksi/*'))class="active"@endif>
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
</li>

<li @if(Request::is('teller/menu*','teller/nasabah*'))class="active"@endif>
    <a data-toggle="collapse" href="#nav_tabteller">
        <i class="pe-7s-monitor"></i>
        <p>Monitor Transaksi
            <b class="caret"></b></p>
    </a>
    @if(Request::is('teller/menu/maal*','teller/menu/tabungan*','teller/menu/deposito*','teller/menu/pembiayaan*','teller/nasabah/tabungan*','teller/nasabah/deposito*','teller/nasabah/pembiayaan*'))
        <div class="collapse in" id="nav_tabteller">
    @else
        <div class="collapse" id="nav_tabteller">
    @endif
        <ul class="nav">
            <li @if(Request::is('teller/menu/maal*'))class="active"@endif><a href="{{route('teller.pengajuan_maal')}}">Pengajuan Maal</a></li>
            <li @if(Request::is('teller/menu/tabungan*'))class="active"@endif><a href="{{route('pengajuan_tabungan')}}">Pengajuan Tabungan</a></li>
            <li @if(Request::is('teller/nasabah/tabungan*'))class="active"@endif><a href="{{route('nasabah_tabungan')}}">Nasabah Tabungan</a></li>
            <li @if(Request::is('teller/menu/deposito*'))class="active"@endif><a href="{{route('pengajuan_deposito')}}">Pengajuan Mudharabah Berjangka</a></li>
            <li @if(Request::is('teller/nasabah/deposito*'))class="active"@endif><a href="{{route('nasabah_deposito')}}">Nasabah Mudharabah Berjangka</a></li>
            <li @if(Request::is('teller/menu/pembiayaan*'))class="active"@endif><a href="{{route('pengajuan_pembiayaan')}}">Pengajuan Pembiayaan</a></li>
            <li @if(Request::is('teller/nasabah/pembiayaan*'))class="active"@endif><a href="{{route('nasabah_pembiayaan')}}">Nasabah Pembiayaan</a></li>
        </ul>
        </div>
</li>

<li @if(Request::is('teller/kolektibilitas*'))class="active"@endif>
    <a href="{{route('teller.daftar_kolektibilitas')}}">
        <i class="pe-7s-album"></i>
        <p>Kolektibilitas
        </p>
    </a>
</li>

<li @if(Request::is('teller/laporan/*'))class="active"@endif>
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
            <li><a href="{{route('teller.pengajuan_pem')}}">Pengajuan Pembiayaan</a></li>
            <li><a href="{{route('teller.realisasi_pem')}}">Realisasi Pembiayaan</a></li>
            <li><a href="{{route('teller.kas_harian')}}">Kas Harian</a></li>
            <li><a href="{{route('teller.neraca')}}">Neraca Saldo</a></li>
            <li><a href="{{route('distribusi')}}">Distribusi Pendapatan</a></li>
            {{-- <li><a href="{{route('teller.daftar_kolektibilitas')}}">Daftar Kolektibilitas</a></li>--}}
            {{--<li><a href="{{route('teller.rekap_jurnal')}}">Rekapitulasi Jurnal</a></li>--}}
            {{--<li><a href="{{route('teller.rekapitulasi_kas')}}">Rekapitulasi Kas</a></li>--}}
            {{--<li><a href="{{route('pendapatan')}}">Pendapatan</a></li>--}}
            {{-- <li><a href="{{route('teller.laba_rugi')}}">Laba Rugi</a></li>-->
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