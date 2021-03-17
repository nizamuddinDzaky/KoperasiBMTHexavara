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
{{--                <h1 class="logo mr-auto"><a href=""></a></h1>--}}
                <a href="{{asset('/')}}"><img src="{{asset('bootstrap/assets/img/bmt_logo.jpg')}}" alt="logo bmt muda" class="img-fluid" style="height: 10%; width:10%"</a>
                <!-- Uncomment below if you prefer to use an image logo -->


                <nav class="nav-menu d-none d-lg-block">
                    <ul>
                        <li class="active"><a href="{{url('/')}}">Home</a></li>
                        <li><a href="{{url('/about')}}">Tentang Kami</a></li>
                        <li><a href="{{url('/maal')}}">Maal</a></li>
                        <li><a href="{{url('/login')}}">Login</a></li>

                    </ul>
                </nav><!-- .nav-menu -->
            </div>
        </div>

    </div>
</header><!-- End Header -->

<!-- ======= Intro Section ======= -->
<section id="intro_tausiah">
</section><!-- End Intro Section -->

<main id="main">

    <!-- ======= About Us Section ======= -->
    <section id="tausiah">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h3>{{$tausiah->judul}}</h3>
            </header>

            <div class="col-md-12" style="text-align: center">
                <img src="{{asset($tausiah->gambar)}}" class="img-fluid" alt="">
            </div>

            <div class="col-md-12 mt-3">

                {!! $tausiah->isi !!}


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
<script src="{{asset('assets/js/main.js')}}"></script>

</body>

</html>