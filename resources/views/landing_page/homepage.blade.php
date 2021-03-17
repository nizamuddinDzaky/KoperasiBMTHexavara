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
        .desc-mitra-kerja {
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
<header id="header" class="fixed-top header-transparent">
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-xl-11 d-flex align-items-center">
                <h1 class="logo mr-auto"><a href=""></a></h1>
                <!-- Uncomment below if you prefer to use an image logo -->


                <nav class="nav-menu d-none d-lg-block">
                    <ul>
                        <li class="active"><a href="{{url('/')}}">Home</a></li>
                        <li><a href="{{url('/about')}}">Tentang Kami</a></li>
                        <li><a href="{{url('/maal')}}">Maal</a></li>
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
<section id="intro">
    <div class="intro-container">
        <div id="introCarousel" class="carousel  slide carousel-fade" data-ride="carousel">

            {{--            <ol class="carousel-indicators"></ol>--}}

            <div class="carousel-inner" role="listbox">

                <div class="carousel-item active" style="background-image: url({{$homepage->gambar}})">
                    <div class="carousel-container">
                        <div class="container">
                            <h2 class="animate__animated animate__fadeInDown">{{$homepage->title}}</h2>
                            <h2 class="animate__animated animate__fadeInDown">{{$homepage->subtitle}}</h2>
                            <p class="animate__animated animate__fadeInUp">{!! $homepage->deskripsi !!}</p>
                            <a href="{{url('/register')}}" class="btn-get-started scrollto animate__animated animate__fadeInUp">Daftar Anggota</a>
                            {{--                            <a href="#featured-services" class="btn-get-started scrollto animate__animated animate__fadeInUp">Get Started</a>--}}
                        </div>
                    </div>
                </div>

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

            </div>

            {{--            <a class="carousel-control-prev" href="#introCarousel" role="button" data-slide="prev">--}}
            {{--                <span class="carousel-control-prev-icon ion-chevron-left" aria-hidden="true"></span>--}}
            {{--                <span class="sr-only">Previous</span>--}}
            {{--            </a>--}}

            {{--            <a class="carousel-control-next" href="#introCarousel" role="button" data-slide="next">--}}
            {{--                <span class="carousel-control-next-icon ion-chevron-right" aria-hidden="true"></span>--}}
            {{--                <span class="sr-only">Next</span>--}}
            {{--            </a>--}}

        </div>
    </div>
</section><!-- End Intro Section -->

<main id="main">

<!-- ======= About Us Section ======= -->
<section id="about">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h3>Moto Visi Misi</h3>
            </header>

            <div class="row about-cols">



                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="about-col">
                        <div class="img">
                            <img src="assets/img/about-plan.jpg" alt="" class="img-fluid">
                            <div class="icon"><i class="ion-ios-list-outline"></i></div>
                        </div>
                        <h2 class="title"><a href="#">Moto</a></h2>
                        <div style="text-align: center">
                            {!! $homepage->moto  !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="about-col">
                        <div class="img">
                            <img src="assets/img/about-mission.jpg" alt="" class="img-fluid">
                            <div class="icon"><i class="ion-ios-speedometer-outline"></i></div>
                        </div>
                        <h2 class="title"><a href="#">Misi</a></h2>
                        <div style="text-align: center">
                       {!! $homepage->misi !!}
                        </div>

                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="about-col">
                        <div class="img">
                            <img src="assets/img/about-vision.jpg" alt="" class="img-fluid">
                            <div class="icon"><i class="ion-ios-eye-outline"></i></div>
                        </div>
                        <h2 class="title"><a href="#">Visi</a></h2>
                       <div style="text-align: center">
                           {!! $homepage->visi !!}
                       </div>

                    </div>
                </div>

            </div>

        </div>
    </section><!-- End About Us Section -->
{{--    <section id="skills">--}}
{{--        <div class="container" data-aos="fade-up">--}}
{{--            <header class="section-header">--}}
{{--                <h3>Produk Baitul Tanwi</h3>--}}
{{--                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip</p>--}}
{{--            </header>--}}
{{--        </div>--}}
{{--    </section><!-- End Skills Section -->--}}
{{--    <section id="skills">--}}
{{--        <div class="container" data-aos="fade-up">--}}
{{--            <header class="section-header">--}}
{{--                <h3>Produk Baitul Maal</h3>--}}
{{--                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip</p>--}}
{{--            </header>--}}
{{--        </div>--}}
{{--    </section><!-- End Skills Section -->--}}


{{--    <section id="keanggotaan" class="mt-5 mb-5">--}}
{{--        <div class="container" data-aos="fade-up">--}}
{{--            <header class="section-header">--}}
{{--                <h3>Keanggotaan</h3>--}}
{{--                <h4 style="font-weight: bold; color: black; text-align: center">Syarat</h4>--}}
{{--                <p></p>--}}
{{--                <h4 style="font-weight: bold; color: black; text-align: center">Hak</h4>--}}
{{--                <p></p>--}}
{{--                <a class="btn" style="display: flex; justify-content: center; background-color: #18d26e!important; color: white" href="{{url('/register')}}">Daftar Sekarang</a>--}}
{{--            </header>--}}
{{--        </div>--}}
{{--    </section><!-- End Skills Section -->--}}
{{--    --}}

{{--    <section id="skills">--}}
{{--        <div class="container" data-aos="fade-up">--}}
{{--            <header class="section-header">--}}
{{--                <h3>Mitra Binaan</h3>--}}
{{--                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip</p>--}}
{{--            </header>--}}
{{--        </div>--}}
{{--    </section><!-- End Skills Section -->--}}
    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="section-bg">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h3 class="section-title">Kegiatan</h3>
            </header>

            <div class="row" data-aos="fade-up" data-aos-delay="100">
                <div class=" col-lg-12">
                    <ul id="portfolio-flters">
                        <li data-filter="*" class="filter-active">All</li>
                        @foreach($kategori as $keys => $value)
                            <li data-filter=".{{$value->class}}">{{$value->nama}}</li>
                            @endforeach
                    </ul>
                </div>
            </div>

            <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

                @foreach($kegiatan as $keys => $value)
                    <div class="col-lg-4 col-md-6 portfolio-item {{$value->class}}">
                        <div class="portfolio-wrap">
                            <figure>
                                <img src="{{asset($value->gambar)}}" class="img-fluid" alt="">
                                <a href="{{asset($value->gambar)}}" class="link-preview venobox" data-gall="portfolioGallery" title="{{$value->keterangan}}"><i class="ion ion-eye"></i></a>
                            </figure>

                            <div class="portfolio-info">
                                <h4>{{$value->keterangan}}</h4>
                            </div>
                        </div>
                    </div>
                    @endforeach
            </div>

        </div>
    </section><!-- End Portfolio Section -->
    <section id="tausiah" class="section-bg">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h3 class="section-title">Tausiah</h3>
            </header>



            <div class="row about-cols" >

                @foreach($tausiah as $keys => $value)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="about-col">
                        <div class="img">
                            <img src="{{asset($value->gambar)}}" alt="" class="img-fluid">
                        </div>
                        <form>
                            <h2 class="title"><a href="{{route('tausiah', $value->id)}}">{{$value->judul}}</a></h2>
                        </form>

                        <div style="text-align: center">
                            {!! substr($value->isi, 0 ,200)  !!}...
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-md-4 offset-md-6">
                {{$tausiah->links()}}
            </div>

        </div>
    </section>

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