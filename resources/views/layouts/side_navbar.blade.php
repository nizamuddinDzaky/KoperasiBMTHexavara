<div class="sidebar" data-color="blue" data-image="{{ URL::asset('bmtmudathemes/assets/images/background.jpg') }}">
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

        {{-- User photo profile section --}}
        <div class="user">
			<div class="info">
				<div class="photo">
                    @if(Auth::user()->tipe=="admin")
                    <img src="{{ URL::asset('bootstrap/assets/img/man.svg') }}">
                    @else
                    <img src="{{ url('storage/public/file/'.json_decode(Auth::user()->pathfile,true)['profile'])}}">
                    @endif
                </div>

				<a data-toggle="collapse" href="#collapseExample" class="collapsed">
					<span style="text-transform: capitalize">
						{{ Auth::user()->nama}}
                        <b class="caret"></b>
					</span>
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
                @include('layouts.partials.sidebar_admin')
            @elseif(Auth::user()->tipe == "teller")
                @include('layouts.partials.sidebar_teller')
            @elseif(Auth::user()->tipe == "anggota")
                @include('layouts.partials.sidebar_anggota')
            @endif
        </ul>
    </div>
</div>

