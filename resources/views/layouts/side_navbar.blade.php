<div class="sidebar">
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
                
        <ul class="nav">
            <li @if(Request::is('admin','admin/dashboard*','teller','teller/dashboard*','anggota','anggota/dashboard*'))class="active"@endif>
                <a href="{{route('dashboard')}}">
                    <i class="pe-7s-graph"></i>
                    <p>Dashboard</p>
                </a>
            </li>

            @if(Auth::user()->tipe=="admin")
                @include('layouts.partials.sidebar_admin')
            @elseif(Auth::user()->tipe == "teller")
                @include('layouts.partials.sidebar_teller')
            @elseif(Auth::user()->tipe == "anggota")
                @include('layouts.partials.sidebar_anggota')
            @endif

            <li class="divider hidden-md hidden-lg"></li>

            <li class="hidden-md hidden-lg @if(Request::is('admin/datadiri*','teller/datadiri*','anggota/datadiri*')) active @endif">
                <a @if(Auth::user()->tipe=="admin") href="{{route('profile')}}" @elseif(Auth::user()->tipe=="teller") href="{{route('teller.datadiri')}}" @else href="{{route('datadiri')}}" @endif>
                    <i class="pe-7s-tools"></i> 
                    <p>Settings</p>
                </a>
            </li>

            <li class="hidden-md hidden-lg">

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
                <a href="#" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    <i class="pe-7s-close-circle"></i>
                    <p>Log out</p>
                </a>
            </li>

        </ul>
    </div>
</div>

