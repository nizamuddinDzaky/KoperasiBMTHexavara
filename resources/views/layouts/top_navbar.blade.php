<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-minimize">
            <button id="minimizeSidebar" class="btn btn-white btn-fill btn-round btn-icon">
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

        <ul class="nav navbar-nav navbar-left hidden-md hidden-lg">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell"></i>
                  <div class="badges">
                      <span>31</span>
                  </div>
              </a>
              <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1" style="max-height: 400px; overflow: auto;">

                <li>
                    <a tabindex="-1" href="#">
                        <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                        <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                    </a>
                </li>
                <li>
                    <a tabindex="-1" href="#">
                        <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                        <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                    </a>
                </li>
                <li>
                    <a tabindex="-1" href="#">
                        <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                        <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                    </a>
                </li>
                <li>
                    <a tabindex="-1" href="#">
                        <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                        <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                    </a>
                </li>
                <li>
                    <a tabindex="-1" href="#">
                        <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                        <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                    </a>
                </li>
          
              
              </ul>
            </li>
        </ul>

        <div class="collapse navbar-collapse">
          
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown hidden-sm">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <div class="badges">
                            <span>31</span>
                        </div>
                    </a>
                    <ul class="dropdown-menu" style="max-height: 400px; overflow: auto;">
                        <li>
                            <a tabindex="-1" href="#">
                                <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                                <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                            </a>
                        </li>
                        <li>
                            <a tabindex="-1" href="#">
                                <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                                <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                            </a>
                        </li>
                        <li>
                            <a tabindex="-1" href="#">
                                <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                                <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                            </a>
                        </li>
                        <li>
                            <a tabindex="-1" href="#">
                                <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                                <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                            </a>
                        </li>
                        <li>
                            <a tabindex="-1" href="#">
                                <p style="font-size: 14px; font-weight: bold;">Pembukaan Rekening Baru</p><br />
                                <p style="font-size: 12px;" class="notif-content">Ditemukan 50 Pengajuan Pembukaan Rekening Baru, Segera Tanggapi Demi Kepuasan Anggota</p><br />
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Medium and large screen profile navbar -->
                <li class="dropdown dropdown-with-icons hidden-sm">
                    
                    <a href="#" class="dropdown-toggle navbar-image" data-toggle="dropdown" style="
                        @if(json_decode(Auth::user()->pathfile,true)['profile']) != null) background-image: url({{ asset('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['profile']) }})
                        @else background-image: url({{ asset('bmtmudathemes/assets/images/avatar.jpg') }})
                        @endif
                    "></a>

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
                            <a @if(Auth::user()->tipe=="admin") href="{{route('profile')}}" @elseif(Auth::user()->tipe=="teller") href="{{route('teller.datadiri')}}" @else href="{{route('datadiri')}}" @endif>
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

                <!-- Small screen profile navbar -->
                <li class="dropdown dropdown-with-icons hidden-md hidden-lg">
                    <a href="#" class="dropdown-toggle navbar-image" data-toggle="dropdown" style="
                        @if(json_decode(Auth::user()->pathfile,true)['profile']) != null) background-image: url({{ asset('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['profile']) }})
                        @else background-image: url({{ asset('bmtmudathemes/assets/images/avatar.jpg') }})
                        @endif
                    "></a>
                    <p class="navbar-username hidden-md hidden-lg">{{ Auth::user()->nama }}</p>
                    <p class="navbar-type hidden-md hidden-lg">Akun {{ Auth::user()->tipe }} BMT</p>
                </li>

            </ul>
        </div>
    </div>
</nav>