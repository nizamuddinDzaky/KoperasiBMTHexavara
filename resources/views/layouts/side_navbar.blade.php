<div class="sidebar" data-color="default" data-image="{{ URL::asset('bootstrap/assets/img/full-screen-image-1.jpg') }}">
    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    <div class="logo">
        <a href="#" class="logo-text">
            KOPERASI BMT MUDA
        </a>
    </div>
    <div class="logo logo-mini">
        <a href="#" class="logo-text">
            BMT
        </a>
    </div>

    <div class="sidebar-wrapper">
        <div class="content">
            <div class="container-fluid">
                 <div class="user">
                <div class="photo">
                    @if(Auth::user()->tipe=="admin")
                    <img src="{{ URL::asset('bootstrap/assets/img/man.svg') }}">
                    @else
                    <img src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['profile'])}}">
                    @endif
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                        {{ Auth::user()->nama}}
                        <b class="caret"></b>
                    </a>
                    <div class="collapse" id="collapseExample">
                        <ul class="nav">
                            <li><a @if(Auth::user()->tipe=="admin") href="{{route('profile')}}" @elseif(Auth::user()->tipe=="teller") href="{{route('teller.datadiri')}}" @else href="{{route('datadiri')}}" @endif>
                                    Edit Profile
                                </a>
                            </li>
                            <li>
                                 <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                            <a href="#" onclick="event.preventDefault();
                                				document.getElementById('logout-form').submit();">
                                Log out
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
                <ul class="nav">
                    <li @if(Request::is('admin','admin/dashboard*','teller','teller/dashboard*','anggota','anggota/dashboard*'))class="active"@endif>
                        <a href="{{route('dashboard')}}">
                            <i class="pe-7s-graph"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    @if(Auth::user()->tipe=="admin")
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
                                <li @if(Request::is('admin/datamaster/deposito'))class="active"@endif><a href="{{route('data_deposito')}}">Master Deposito</a></li>
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
                                {{--<li @if(Request::is('admin/transaksi/pengajuan'))class="active"@endif><a href="{{route('admin.transaksi.pengajuan')}}">Daftar Pengajuan</a></li>--}}
                                <li @if(Request::is('admin/transaksi/transfer'))class="active"@endif><a href="{{route('admin.transaksi.transfer')}}">Transfer Antar Rekening</a></li>
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
                                <li @if(Request::is('admin/deposito/pengajuan'))class="active"@endif><a href="{{route('admin.pengajuan_deposito')}}">Pengajuan Deposito</a></li>
                                <li @if(Request::is('admin/deposito/nasabah'))class="active"@endif><a href="{{route('admin.nasabah_deposito')}}">Nasabah Deposito</a></li>
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
                                    {{--<li><a href="{{route('pengajuan_pem')}}">Pengajuan Pembiayaan</a></li>--}}
                                    {{--<li><a href="{{route('realisasi_pem')}}">Realisasi Pembiayaan</a></li>--}}
                                    <li><a href="{{route('kas_harian')}}">Kas Harian</a></li>
                                    <li><a href="{{route('neraca')}}">Neraca Saldo</a></li>
                                    <li><a href="{{route('laba_rugi')}}">Laba Rugi</a></li>
                                    <li><a href="{{route('quitas')}}">Laporan Perubahan Quitas</a></li>
{{--                                    <li><a href="{{route('daftar_kolektibilitas')}}">Daftar Kolektibilitas</a></li>--}}
                                    {{--<li><a href="{{route('rekap_jurnal')}}">Rekapitulasi Jurnal</a></li>--}}
{{--                                    <li><a href="{{route('rekapitulasi_kas')}}">Rekapitulasi Kas</a></li>--}}
                                <!--<li><a href="{{route('pendapatan')}}">Pendapatan</a></li>-->
                                <!--<li><a href="{{route('aktiva')}}">Aktiva</a></li>-->

                                    {{--<li><a href="{{route('jatuh_tempo')}}">Jatuh Tempo</a></li>--}}
                                    {{--<li><a href="{{route('kredit_macet')}}">Kredit Macet</a></li>--}}
                                    {{--<li><a href="{{route('transaksi_kas')}}">Transaksi Kas</a></li>--}}
                                    <li><a href="{{route('buku_besar')}}">Buku Besar</a></li>
                                    {{--<li><a href="{{route('simpanan')}}">Kas Simpanan</a></li>--}}
                                    {{--<li><a href="{{route('pinjaman')}}">Kas Pinjaman</a></li>--}}
                                    {{--<li><a href="{{route('saldo')}}">Saldo Kas</a></li>--}}
                                    <li><a href="{{route('distribusi')}}">Distribusi Pendapatan</a></li>
                                    <li><a href="{{route('shu')}}">SHU Tahunan</a></li>
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
                    @elseif(Auth::user()->tipe == "teller")

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
                                    <li @if(Request::is('teller/menu/deposito*'))class="active"@endif><a href="{{route('pengajuan_deposito')}}">Pengajuan Deposito</a></li>
                                    <li @if(Request::is('teller/nasabah/deposito*'))class="active"@endif><a href="{{route('nasabah_deposito')}}">Nasabah Deposito</a></li>
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
{{--                                                <li><a href="{{route('teller.daftar_kolektibilitas')}}">Daftar Kolektibilitas</a></li>--}}
                                                {{--<li><a href="{{route('teller.rekap_jurnal')}}">Rekapitulasi Jurnal</a></li>--}}
                                                {{--<li><a href="{{route('teller.rekapitulasi_kas')}}">Rekapitulasi Kas</a></li>--}}
                                                <li><a href="{{route('teller.kas_harian')}}">Kas Harian</a></li>
                                            <!--<li><a href="{{route('pendapatan')}}">Pendapatan</a></li>-->
                                                {{-- <li><a href="{{route('teller.laba_rugi')}}">Laba Rugi</a></li>-->
                                                <!--<li><a href="{{route('aktiva')}}">Aktiva</a></li>-->
                                                <li><a href="{{route('teller.neraca')}}">Neraca Saldo</a></li>

                                                {{--<li><a href="{{route('teller.jatuh_tempo')}}">Jatuh Tempo</a></li>--}}
                                                {{--<li><a href="{{route('teller.kredit_macet')}}">Kredit Macet</a></li>--}}
                                                {{--<li><a href="{{route('teller.transaksi_kas')}}">Transaksi Kas</a></li>--}}
                                                {{-- <li><a href="{{route('teller.buku_besar')}}">Buku Besar</a></li>-->
                                                {{--<li><a href="{{route('teller.simpanan')}}">Kas Simpanan</a></li>--}}
                                                {{--<li><a href="{{route('teller.pinjaman')}}">Kas Pinjaman</a></li>--}}
                                                {{--<li><a href="{{route('teller.saldo')}}">Saldo Kas</a></li>--}}
                                                {{--<li><a href="{{route('shu')}}">SHU</a></li>
                                                <li><a href="{{route('distribusi')}}">Distribusi Pendapatan</a></li>--}}
                                            </ul>
                                        </div>
                        </li>

                    @elseif(Auth::user()->tipe == "anggota")

                    <li @if(Request::is('anggota/pengajuan*'))class="active"@endif>
                        <a href="{{route('pengajuan')}}">
                            <i class="pe-7s-next-2"></i>
                            <p>Pengajuan</p>
                        </a>
                    </li>
                    <li @if(Request::is('anggota/menu*'))class="active"@endif>
                        <a data-toggle="collapse" href="#nav_tabuser">
                            <i class="pe-7s-tools"></i>
                            <p>Menu Anggota
                            <b class="caret"></b></p>
                        </a>
                        @if(Request::is('anggota/menu/tabungan*','anggota/menu/deposito*','anggota/menu/pembiayaan*'))
                        <div class="collapse in" id="nav_tabuser">
                        @else
                        <div class="collapse" id="nav_tabuser">
                        @endif
                            <ul class="nav">
                                <li @if(Request::is('anggota/menu/tabungan*'))class="active"@endif><a href="{{route('tabungan_anggota')}}">Tabungan</a></li>
                                <li @if(Request::is('anggota/menu/deposito*'))class="active"@endif><a href="{{route('deposito_anggota')}}">Deposito</a></li>
                                <li @if(Request::is('anggota/menu/pembiayaan*'))class="active"@endif><a href="{{route('pembiayaan_anggota')}}">Pembiayaan</a></li>
                            </ul>
                        </div>
                    @endif
                    @if(Auth::user()->tipe=="admin")
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
                    @elseif(Auth::user()->tipe=="anggota" || Auth::user()->tipe=="teller")
                    <li @if(Request::is('anggota/maal*','teller/maal*')) class="active"@endif>
                        <a data-toggle="collapse" href="#nav_maalt">
                            <i class="pe-7s-home"></i>
                            <p>Maal
                                <b class="caret"></b></p>
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
                    @endif
                    @if(Auth::user()->tipe=="anggota")
                    <li @if(Request::is('anggota/datadiri*','teller/datadiri*')) class="active"@endif>
                        <a @if(Auth::user()->tipe=="anggota")href="{{route('datadiri')}}"@elseif(Auth::user()->tipe=="teller")href="{{route('teller.datadiri')}}@endif">
                            <i class="pe-7s-user"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                    @endif
                </ul>

            </div>
        </div>
    </div>
</div>

