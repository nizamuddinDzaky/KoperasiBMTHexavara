<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-minimize">
            <button id="minimizeSidebar" class="btn btn-default btn-fill btn-round btn-icon">
                <i class="fa fa-ellipsis-v visible-on-sidebar-regular"></i>
                <i class="fa fa-navicon visible-on-sidebar-mini"></i>
            </button>
        </div>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand"  href="#">Dashboard PRO </a>
            {{--<a class="navbar-brand"  href="#">Dashboard PRO -- <span><i><strong>@if(Auth::user()->tipe=="teller")Rp {{ number_format($teller->saldo,2)  }}@endif</strong></i></span></a>--}}
        </div>

        <div class="collapse navbar-collapse">
          
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bell"></i>
                    <div class="badges">
                        <span>31</span>
                    </div>
                    <p class="hidden-md hidden-lg">
                        Notification
                        <b class="caret"></b>
                    </p>
                </a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="max-height: 400px; overflow: auto;">

                    <li>
                        <a tabindex="-1" href="#">
                            <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                            <p style="font-size: 12px;">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Nasabah</p><br />
                        </a>
                    </li>

                    <li>
                        <a tabindex="-1" href="#">
                            <p style="font-size: 14px; font-weight: bold;">Pembukaan Deposito Baru</p><br />
                            <p style="font-size: 12px;">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Nasabah</p><br />
                        </a>
                    </li>

                    <li>
                        <a tabindex="-1" href="#">
                            <p style="font-size: 14px; font-weight: bold;">Pengajuan Pendanaan Baru</p><br />
                            <p style="font-size: 12px;">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Nasabah</p><br />
                        </a>
                    </li>

                    <li>
                        <a tabindex="-1" href="#">
                            <p style="font-size: 14px; font-weight: bold;">Pengajuan Setoran Baru</p><br />
                            <p style="font-size: 12px;">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Nasabah</p><br />
                        </a>
                    </li>
                    <li>
                        <a tabindex="-1" href="#">
                            <p style="font-size: 14px; font-weight: bold;">Pengajuan Penarikan Dana Baru</p><br />
                            <p style="font-size: 12px;">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Nasabah</p><br />
                        </a>
                    </li>
                    <li>
                        <a tabindex="-1" href="#">
                            <p style="font-size: 14px; font-weight: bold;">Pengajuan Pencairan Deposito Baru</p><br />
                            <p style="font-size: 12px;">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Nasabah</p><br />
                        </a>
                    </li>
            
                
                </ul>
              </li>

                <li class="dropdown dropdown-with-icons">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-list"></i>
                        <p class="hidden-md hidden-lg">
                            More
                            <b class="caret"></b>
                        </p>
                    </a>
                        <ul class="dropdown-menu dropdown-with-icons">
                            <li>
                                <a @if(Auth::user()->tipe=="admin")href="{{route('admin.transaksi.pengajuan')}}" @elseif(Auth::user()->tipe=="teller")href="{{route('teller.transaksi.pengajuan')}}" @elseif(Auth::user()->tipe=="anggota")href="{{route('pengajuan')}}" @endif>
                                    <i class="pe-7s-mail"></i> Pengajuan
                                </a>
                            </li>
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="pe-7s-help1"></i> Help Center--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li>
                                <a @if(Auth::user()->tipe=="admin") href="{{route('profile')}}" @else href="{{route('datadiri')}}" @endif>
                                    <i class="pe-7s-tools"></i> Settings
                                </a>
                            </li>
                            <li class="divider"></li>
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="pe-7s-lock"></i> Lock Screen--}}
                                {{--</a>--}}
                            {{--</li>--}}
                            <li>
    
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                                <a href="#" class="text-danger" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    <i class="pe-7s-close-circle"></i>
                                    Log out
                                </a>
                            </li>
                        </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>