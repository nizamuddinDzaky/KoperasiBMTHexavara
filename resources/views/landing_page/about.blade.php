<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>BMT MUDA | Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="http://demos.creative-tim.com/light-bootstrap-dashboard-pro/assets/img/favicon.ico">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/icofont/icofont.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/animate.css/animate.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/venobox/venobox.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <style>
        .desc-misi {
            text-align: center;
        }
        
    </style>

    <!-- =======================================================
    * Template Name: BizPage - v3.2.1
    * Template URL: https://bootstrapmade.com/bizpage-bootstrap-business-template/
    * Author: BootstrapMade.com
    * License: https://bootstrapmade.com/license/
    ======================================================== -->
</head>

<body>

<!-- ======= Header ======= -->
<header id="header" class="fixed-top">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-11 d-flex align-items-center">
            {{--                <h1 class="logo mr-auto"><a href="index.html">BizPage</a></h1>--}}
            <!-- Uncomment below if you prefer to use an image logo -->
                <a href="{{asset('/')}}"><img src="{{asset('bootstrap/assets/img/bmt_logo.jpg')}}" alt="logo bmt muda" class="img-fluid" style="height: 10%; width:10%"</a>

                <nav class="nav-menu d-none d-lg-block">
                    <ul>
                        <li><a href="{{url('/')}}">Home</a></li>
                        <li class="active"><a href="{{url('/about')}}">Tentang Kami</a></li>
                        <li ><a href="{{url('/maal')}}">Maal</a></li>
                        <li><a href="{{url('/login')}}">Login</a></li>
                        {{--                        <li><a href="#services">Services</a></li>--}}
                        {{--                        <li><a href="#portfolio">Portfolio</a></li>--}}
                        {{--                        <li><a href="#team">Team</a></li>--}}
                        {{--                        <li class="drop-down"><a href="">Drop Down</a>--}}
                        {{--                            <ul>--}}
                        {{--                                <li><a href="#">Drop Down 1</a></li>--}}
                        {{--                                <li><a href="#">Drop Down 3</a></li>--}}
                        {{--                                <li><a href="#">Drop Down 4</a></li>--}}
                        {{--                                <li><a href="#">Drop Down 5</a></li>--}}
                        {{--                            </ul>--}}
                        {{--                        </li>--}}
                        {{--                        <li><a href="#contact">Contact Us</a></li>--}}

                    </ul>
                </nav><!-- .nav-menu -->
            </div>
        </div>

    </div>
</header><!-- End Header -->

<!-- ======= Intro Section ======= -->
{{--<section id="intro">--}}
{{--    <div class="intro-container">--}}

{{--        <div id="introCarousel" class="carousel  slide carousel-fade" data-ride="carousel">--}}

{{--            <ol class="carousel-indicators"></ol>--}}

{{--            <div class="carousel-inner" role="listbox">--}}

{{--                <div class="carousel-item active" style="background-image: url(assets/img/intro-carousel/1.jpg)">--}}
{{--                    <div class="carousel-container">--}}
{{--                        <div class="container">--}}
{{--                            <h2 class="animate__animated animate__fadeInDown">BMT MUDA</h2>--}}
{{--                            <h2 class="animate__animated animate__fadeInDown">Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada</h2>--}}
{{--                            <p class="animate__animated animate__fadeInUp">Jalan Kedinding Lor Gang Tanjung 49 <br>--}}
{{--                                Kelurahan Tanah Kali Kedinding, Kecamatan Kenjeran<br>--}}
{{--                                Kota Surabaya <br>--}}
{{--                                <strong>Phone:</strong> (031) 371 9610<br></p>--}}
{{--                            <a href="#featured-services" class="btn-get-started scrollto animate__animated animate__fadeInUp">Get Started</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="carousel-item" style="background-image: url(assets/img/intro-carousel/2.jpg)">--}}
{{--                    <div class="carousel-container">--}}
{{--                        <div class="container">--}}
{{--                            <h2 class="animate__animated animate__fadeInDown">At vero eos et accusamus</h2>--}}
{{--                            <p class="animate__animated animate__fadeInUp">Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut.</p>--}}
{{--                            <a href="#featured-services" class="btn-get-started scrollto animate__animated animate__fadeInUp">Get Started</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="carousel-item" style="background-image: url(assets/img/intro-carousel/3.jpg)">--}}
{{--                    <div class="carousel-container">--}}
{{--                        <div class="container">--}}
{{--                            <h2 class="animate__animated animate__fadeInDown">Temporibus autem quibusdam</h2>--}}
{{--                            <p class="animate__animated animate__fadeInUp">Beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt omnis iste natus error sit voluptatem accusantium.</p>--}}
{{--                            <a href="#featured-services" class="btn-get-started scrollto animate__animated animate__fadeInUp">Get Started</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="carousel-item" style="background-image: url(assets/img/intro-carousel/4.jpg)">--}}
{{--                    <div class="carousel-container">--}}
{{--                        <div class="container">--}}
{{--                            <h2 class="animate__animated animate__fadeInDown">Nam libero tempore</h2>--}}
{{--                            <p class="animate__animated animate__fadeInUp">Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum.</p>--}}
{{--                            <a href="#featured-services" class="btn-get-started scrollto animate__animated animate__fadeInUp">Get Started</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="carousel-item" style="background-image: url(assets/img/intro-carousel/5.jpg)">--}}
{{--                    <div class="carousel-container">--}}
{{--                        <div class="container">--}}
{{--                            <h2 class="animate__animated animate__fadeInDown">Magnam aliquam quaerat</h2>--}}
{{--                            <p class="animate__animated animate__fadeInUp">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>--}}
{{--                            <a href="#featured-services" class="btn-get-started scrollto animate__animated animate__fadeInUp">Get Started</a>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--            </div>--}}

{{--            <a class="carousel-control-prev" href="#introCarousel" role="button" data-slide="prev">--}}
{{--                <span class="carousel-control-prev-icon ion-chevron-left" aria-hidden="true"></span>--}}
{{--                <span class="sr-only">Previous</span>--}}
{{--            </a>--}}

{{--            <a class="carousel-control-next" href="#introCarousel" role="button" data-slide="next">--}}
{{--                <span class="carousel-control-next-icon ion-chevron-right" aria-hidden="true"></span>--}}
{{--                <span class="sr-only">Next</span>--}}
{{--            </a>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</section><!-- End Intro Section -->--}}

<main id="main" style="margin-top: 7.5%; margin-bottom: -5%">

    <section id="services">
        <div class="container" data-aos="fade-up">

            <header class="section-header wow fadeInUp">
                <h3>Tentang Kami</h3>
                {!! $homepage->deskripsi !!}

            </header>
        </div>
    </section>

     <!-- ======= About Us Section ======= -->
     <section id="about">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h3>Mitra Kerja</h3>
            </header>

            <div class="row about-cols">
                @foreach($mitrakerja as $keys => $value)
                 <div class="col-md-4" data-aos="fade-up" data-aos-delay="700">
                    <div class="about-col">
                        <h2 class="title pt-3">{{$value->nama}}</h2>
                        <p style="text-align: center">
                            {{$value->keterangan}}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </section><!-- End About Us Section -->

    <!-- ======= Services Section ======= -->
    <section id="services">
        <div class="container" data-aos="fade-up">

            <header class="section-header wow fadeInUp">
                <h3>PENDIRI</h3>
            </header>

            <div class="row">
                @foreach($pendiri as $keys => $value)
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        <h4 class="title">{{$value->nama}}</h4>
                    </div>
                    @endforeach
            </div>

        </div>
    </section><!-- End Services Section -->

    <!-- ======= Download Section ======= -->
    <section id="services">
        <div class="container" data-aos="fade-up">

            <header class="section-header wow fadeInUp">
                <h3>Rapat</h3>
            </header>

            <div class="row">
                @foreach($rapat as $keys => $value)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="icon"><i class="ion-ios-paper-outline"></i></div>
                        <h4 class="title" style="color: white!important;"><a href="{{url('landing_page/tentang_kami/downloadrapat').'/'.$value->id}}" class="btn btn-primary">Download {{$value->nama}}</a></h4>
                        <p class="description">{{$value->nama}}</p>
                    </div>
                    @endforeach


            </div>

        </div>
    </section><!-- End Download Section -->

    <!-- ======= Skills Section ======= -->
{{--    <section id="skills">--}}
{{--        <div class="container" data-aos="fade-up">--}}

{{--            <header class="section-header">--}}
{{--                <h3>Ruang Lingkup</h3>--}}
{{--                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip</p>--}}
{{--            </header>--}}
{{--        </div>--}}
{{--    </section><!-- End Skills Section -->--}}

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="section-bg">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h3 class="section-title">Cara Kerja</h3>
            </header>

            <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

                @foreach($carakerja as $keys => $value)
                    <div class="col-lg-6 col-md-6 portfolio-item">
                        <div class="portfolio-wrap">
                            <figure>
                                <img src="{{asset($value->gambar)}}" class="img-fluid" alt="">
                                <a href="{{asset($value->gambar)}}" class="link-preview venobox" data-gall="portfolioGallery" title="Web 3" style="margin-left: 5%!important;"><i class="ion ion-eye"></i></a>
                            </figure>

                            <div class="portfolio-info">
                                <h4><a>{{$value->keterangan}}</a></h4>
                            </div>
                        </div>
                    </div>
                    @endforeach
            </div>

        </div>
    </section><!-- End Portfolio Section -->

    <!-- ======= Skills Section ======= -->
    <section id="skills">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h3>Struktur Organisasi</h3>
                <h4 style="font-weight: bold; color: black">Pembina</h4>
                <div class="row">
                    @foreach($pembina as $value)
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        <h4 class="title">@if($value->jabatan != null)<b>{{$value->jabatan}}</b> <br>@endif{{$value->nama}}</h4>
                        </div>
                        @endforeach
                </div>
                <h4 style="font-weight: bold; color: black">Pengawas</h4>
                <div class="row">
                    @foreach($pengawas as $value)
                        <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                            <h4 class="title">@if($value->jabatan != null)<b>{{$value->jabatan}}</b> <br>@endif{{$value->nama}}</h4>
                        </div>
                    @endforeach
                </div>
                <h4 style="font-weight: bold; color: black">Dewan Pengawas Syariah</h4>
                <div class="row">
                    @foreach($dewanpengawas as $value)
                        <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                            <h4 class="title">@if($value->jabatan != null)<b>{{$value->jabatan}}</b> <br>@endif{{$value->nama}}</h4>
                        </div>
                    @endforeach
                </div>
                <h4 style="font-weight: bold; color: black">Pengurus</h4>
                <div class="row">
                    @foreach($pengurus as $value)
                        <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                            <h4 class="title">@if($value->jabatan != null)<b>{{$value->jabatan}}</b> <br>@endif{{$value->nama}}</h4>
                        </div>
                    @endforeach
                </div>
                <h4 style="font-weight: bold; color: black">Pengelola</h4>
                <div class="row">
                    @foreach($pengelola as $value)
                        <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                            <h4 class="title">@if($value->jabatan != null)<b>{{$value->jabatan}}</b> <br>@endif{{$value->nama}}</h4>
                        </div>
                    @endforeach
                </div>
            </header>
        </div>
    </section><!-- End Skills Section -->

    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="section-bg">
        <div class="container mb-5" data-aos="fade-up">

            <header class="section-header">
                <h3 class="section-title">Izin Pendirian</h3>
            </header>

        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
            @foreach($izin_pendirian as $value)
            <div class="col-lg-4 col-md-6 portfolio-item">
                <div class="portfolio-wrap">
                    <figure>
                        <img src="{{asset($value->gambar)}}" class="img-fluid" alt="">
                        <a href="{{asset($value->gambar)}}" class="link-preview venobox" data-gall="portfolioGallery" title="Web 3" ><i class="ion ion-eye"></i></a>
                    </figure>

                    <div class="portfolio-info">
                        <h4><a href="portfolio-details.html">{{$value->keterangan}}</a></h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        </div>
    </section><!-- End Portfolio Section -->



</main><!-- End #main -->

<!-- ======= Footer ======= -->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-lg-3 col-md-6 footer-info">
                    <a href="{{asset('/')}}"><img src="{{asset($footer->logo)}}" alt="logo bmt muda" class="img-fluid" style="height: 30%; width:70%"></a>
                    {!! $footer->keterangan !!}

                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Links</h4>
                    <ul>
                        <li><i class="ion-ios-arrow-right"></i> <a href="{{url('/')}}">Home</a></li>
                        <li><i class="ion-ios-arrow-right"></i> <a href="{{url('/about')}}">Tentang Kami</a></li>
                        <li><i class="ion-ios-arrow-right"></i> <a href="{{url('/maal')}}">Maal</a></li>
                        <li><i class="ion-ios-arrow-right"></i> <a href="{{url('/login')}}">Login</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-contact">
                    <h4>Alamat Kami</h4>
                    {!! $footer->alamat !!}

                    <div class="social-links">
                        <a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
                        <a href="#" class="facebook"><i class="fa fa-facebook"></i></a>
                        <a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
                        <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>
                        <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>
                    </div>

                </div>

                {{--                <div class="col-lg-3 col-md-6 footer-newsletter">--}}
                {{--                    <h4>Our Newsletter</h4>--}}
                {{--                    <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna veniam enim veniam illum dolore legam minim quorum culpa amet magna export quem marada parida nodela caramase seza.</p>--}}
                {{--                    <form action="" method="post">--}}
                {{--                        <input type="email" name="email"><input type="submit" value="Subscribe">--}}
                {{--                    </form>--}}
                {{--                </div>--}}

            </div>
        </div>
    </div>

    <div class="container">
        <div class="copyright">
            &copy; Copyright <strong>BizPage</strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!--
            All the links in the footer should remain intact.
            You can delete the links only if you purchased the pro version.
            Licensing information: https://bootstrapmade.com/license/
            Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=BizPage
          -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
        </div>
    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
<!-- Uncomment below i you want to use a preloader -->
<!-- <div id="preloader"></div> -->

<!-- Vendor JS Files -->
<script src="{{asset('assets/vendor/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/vendor/jquery.easing/jquery.easing.min.js')}}"></script>
<script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
<script src="{{asset('assets/vendor/waypoints/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/vendor/counterup/counterup.min.js')}}"></script>
<script src="{{asset('assets/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
<script src="{{asset('assets/vendor/venobox/venobox.min.js')}}"></script>
<script src="{{asset('assets/vendor/owl.carousel/owl.carousel.min.js')}}"></script>
<script src="{{asset('assets/vendor/aos/aos.js')}}"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>

</body>

</html>