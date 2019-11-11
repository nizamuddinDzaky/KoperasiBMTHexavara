<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>BMT MUDA (Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada)</title>

    <!-- Bootstrap core CSS -->
    <link href="{{URL::asset('slider/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{URL::asset('css/full-slider.css')}}" rel="stylesheet">

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
       <a class="navbar-brand" href="#">BMT MUDA</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item ">
                    <a class="nav-link" href="{{url('/')}}">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://www.bmtmuda.com/2012/01/profile-bmt.html">Profile</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{route('maal')}}">Maal</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <!-- Slide One - Set the background image for this slide in the line below -->
            <div class="carousel-item active" style="background-image: url('{{"bootstrap/assets/img/default_poster.jpg"}}')">
                <div class="carousel-caption d-none d-md-block">
                    <h3>{{$data[0]->nama_kegiatan}}</h3>
                    <h2>{{$data[0]->tanggal_pelaksaaan}}</h2>
                    <p>{{json_decode($data[0]->detail,true)['detail']}}</p>
                    @if(!Auth::check())
                    <a type="button" href="{{route('anggota.donasi.maal')}}" class="btn btn-fill btn-info center-block">Donasi Sekarang</a>
                    @else
                    <a type="button" @if(Auth::user()->tipe=="anggota")href="{{route('anggota.donasi.maal')}}" @elseif(Auth::user()->tipe=="teller")href="{{route('teller.donasi.maal')}}" @endif class="btn btn-fill btn-info center-block">Donasi Sekarang</a>
                    @endif
                </div>
            </div>
            @for($i=1;$i< count($data); $i++)
            @if(json_decode($data[$i]->detail,true)['path_poster']=="")
            <div class="carousel-item" style="background-image: url('{{ "bootstrap/assets/img/default_poster.jpg"}}')">
            @else
            <div class="carousel-item" style="background-image: url('{{ "storage/public/maal".json_decode($data[$i]->detail,true)['path_poster']}}')">
            @endif
                <div class="carousel-caption d-none d-md-block">
                    <h3>{{$data[$i]->nama_kegiatan}}</h3>
                    <h2>{{$data[$i]->tanggal_pelaksaaan}}</h2>
                    <p>{{json_decode($data[$i]->detail,true)['detail']}}</p>
                    @if(!Auth::check())
                        <a type="button" href="{{route('anggota.donasi.maal')}}" class="btn btn-fill btn-info center-block">Donasi Sekarang</a>
                    @else
                        <a type="button" @if(Auth::user()->tipe=="anggota")href="{{route('anggota.donasi.maal')}}" @elseif(Auth::user()->tipe=="teller")href="{{route('teller.donasi.maal')}}" @endif class="btn btn-fill btn-info center-block">Donasi Sekarang</a>
                    @endif
                </div>
            </div>
            @endfor
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</header>

<!-- Page Content -->
<section class="py-5">
    <div class="container">
        <h21>Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada</h21>
        <p> BMT MUDA Jatim juga turut membantu masyarakat lewat kegiatan-kegitan maal yang dilakukan
            untuk meningkatkan kesejahteraan masyarakat.</p>
    </div>
</section>

<!-- Footer -->
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">  &copy;  <a href="{{url('/')}}">BMT MUDA</a>, Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada
        </p>
    </div>
    <!-- /.container -->
</footer>

<!-- Bootstrap core JavaScript -->
<script src="{{URL::asset('slider/jquery/jquery.min.js')}}"></script>
<script src="{{URL::asset('slider/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

</body>

</html>
