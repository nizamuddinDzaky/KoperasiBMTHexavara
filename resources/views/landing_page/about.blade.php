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
                        <li class="active"><a href="{{url('/maal')}}">Maal</a></li>
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
               <p>BMT MUDA <br>
                   Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada <br>
                   Jalan Kedinding Lor Gang Tanjung 49 <br>
                   Kelurahan Tanah Kali Kedinding, Kecamatan Kenjeran<br>
                   Kota Surabaya <br>
                   <strong>Phone:</strong> (031) 371 9610 / 0858-5081-9919   <br></p>

            </header>
        </div>
    </section>


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
                        <h2 class="title"><a href="#">Moto Kami</a></h2>
                        <p style="text-align: center">
                            “Powerful, Independent, Prosperous”
                        </p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="about-col">
                        <div class="img">
                            <img src="assets/img/about-mission.jpg" alt="" class="img-fluid">
                            <div class="icon"><i class="ion-ios-speedometer-outline"></i></div>
                        </div>
                        <h2 class="title"><a href="#">Misi Kami</a></h2>
                        <p class="desc-misi">
                            Providing financial services based on sharia, professional, trustworthy, and accountable.
                        </p>
                        <p class="desc-misi">
                            Empowering the populist economy that can give benefit to the ummah.
                        </p>
                        <p class="desc-misi">
                            Improve the quality of professional employees and fully understand the aspects of BMT.
                        </p>
                        <p class="desc-misi">
                            Improve the performance of BMT with information technology-based systems.
                        </p>
                        <p class="desc-misi">
                            Uphold consistency in applying Sharia principles in BMT operations.
                        </p>
                    </div>
                </div>

                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="about-col">
                        <div class="img">
                            <img src="assets/img/about-vision.jpg" alt="" class="img-fluid">
                            <div class="icon"><i class="ion-ios-eye-outline"></i></div>
                        </div>
                        <h2 class="title"><a href="#">Visi Kami</a></h2>
                       <p style="text-align: center">
                           Being a leading BMT, professional and can provide benefits to the people of Surabaya in particular and East Java in general.
                       </p>
                    </div>
                </div>

            </div>

        </div>
    </section><!-- End About Us Section -->

    <!-- ======= Services Section ======= -->
    <section id="services">
        <div class="container" data-aos="fade-up">

            <header class="section-header wow fadeInUp">
                <h3>PENDIRI</h3>
{{--                <p>Laudem latine persequeris id sed, ex fabulas delectus quo. No vel partiendo abhorreant vituperatoribus, ad pro quaestio laboramus. Ei ubique vivendum pro. At ius nisl accusam lorenta zanos paradigno tridexa panatarel.</p>--}}
            </header>

            <div class="row">

                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
{{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                    <h4 class="title">Shochrul Rohmatul Ajija</h4>
{{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="200">
{{--                    <div class="icon"><i class="ion-ios-bookmarks-outline"></i></div>--}}
                    <h4 class="title">Sri Cahyaning Umi Salama</h4>
{{--                    <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat tarad limino ata</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="300">
{{--                    <div class="icon"><i class="ion-ios-paper-outline"></i></div>--}}
                    <h4 class="title">Nur Aulia</h4>
{{--                    <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="200">
{{--                    <div class="icon"><i class="ion-ios-speedometer-outline"></i></div>--}}
                    <h4 class="title">Abdul Malik</h4>
{{--                    <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="300">
{{--                    <div class="icon"><i class="ion-ios-barcode-outline"></i></div>--}}
                    <h4 class="title">Sunoyo</h4>
{{--                    <p class="description">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
{{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Umu Kholifah</h4>
{{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Ratna Dyah Apsari</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Ragil</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Farlian</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Harmonia</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Promptia</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Khalid</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Sudarmaji</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Yulian Poesoko</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Ristya Paraisuda</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Vina</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Rifki Hamdani</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Rizal Arddi</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">M. Anugerah</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Oby Pratama</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Mas Abay</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Indra mbak Nia</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>
                <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="400">
                    {{--                    <div class="icon"><i class="ion-ios-people-outline"></i></div>--}}
                    <h4 class="title">Novita Ariani</h4>
                    {{--                    <p class="description">Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi</p>--}}
                </div>














            </div>

        </div>
    </section><!-- End Services Section -->

    <!-- ======= Download Section ======= -->
    <section id="services">
        <div class="container" data-aos="fade-up">

            <header class="section-header wow fadeInUp">
                <h3>Rapat</h3>
{{--                <p>Laudem latine persequeris id sed, ex fabulas delectus quo. No vel partiendo abhorreant vituperatoribus, ad pro quaestio laboramus. Ei ubique vivendum pro. At ius nisl accusam lorenta zanos paradigno tridexa panatarel.</p>--}}
            </header>

            <div class="row">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon"><i class="ion-ios-paper-outline"></i></div>
                    <h4 class="title" style="color: white!important;"><a href="" class="btn btn-primary">Download RAB</a></h4>
                    <p class="description">Rencana Anggaran Belanja Tahun Depan</p>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon"><i class="ion-ios-paper-outline"></i></div>
                    <h4 class="title" style="color: white!important;"><a href="" class="btn btn-primary">Download RAT</a></h4>
                    <p class="description">Rapat Anggota Tahunan</p>
                </div>
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="icon"><i class="ion-ios-paper-outline" ></i></div>
                    <h4 class="title" style="color: white!important;"><a href="" class="btn btn-primary">Download RALB</a></h4>
                    <p class="description">Rapat Anggota Luar Biasa </p>
                </div>


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

                <div class="col-lg-6 col-md-6 portfolio-item">
                    <div class="portfolio-wrap">
                        <figure>
                            <img src="{{asset('images/scope.png')}}" class="img-fluid" alt="">
                            <a href="{{asset('images/scope.png')}}" class="link-preview venobox" data-gall="portfolioGallery" title="Web 3" style="margin-left: 5%!important;"><i class="ion ion-eye"></i></a>
                        </figure>

                        <div class="portfolio-info">
                            <h4><a href="portfolio-details.html">Scopes</a></h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 portfolio-item">
                    <div class="portfolio-wrap">
                        <figure>
                            <img src="{{asset('images/syariah.png')}}" class="img-fluid" alt="">
                            <a href="{{asset('images/syariah.png')}}" class="link-preview venobox" data-gall="portfolioGallery" title="App 2" style="margin-left: 5%!important;"><i class="ion ion-eye"></i></a>
                        </figure>

                        <div class="portfolio-info">
                            <h4><a href="portfolio-details.html">Finasial Syariah BMT MUDA</a></h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section><!-- End Portfolio Section -->

    <!-- ======= Skills Section ======= -->
    <section id="skills">
        <div class="container" data-aos="fade-up">

            <header class="section-header">
                <h3>Struktur Organisasi</h3>
                <h4 style="font-weight: bold; color: black">Advisory Board</h4>
                <div class="row">
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Prof. Dr. Raditya Sukmana</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div> <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Dr. Karjadi Mintaroem</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div> <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Siti Nur Indah Rofiqoh, S.E. MM</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div> <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Rahmat Heru Setianto, SE.,M.Sc</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div>
                </div>
                <h4 style="font-weight: bold; color: black">Shariah Advisory Board</h4>
                <div class="row">
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Robiatul Adawiyah, Lc, MIRKH</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div>
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Ali Hamdan, S.Si.,M.EI</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div>
                </div>
                <h4 style="font-weight: bold; color: black">Board</h4>
                <div class="row">
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Chair <br> Shochrul Rohmatul Ajija, S.E.,M.Ec</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div>
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Secretary <br> Siti Mudawamah, S.Kom</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div>
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Treasurer <br> H.Sunoyo, S.Sos, Apr</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div>
                </div>
                <h4 style="font-weight: bold; color: black">Executive</h4>
                <div class="row">
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Director <br> H.Sunoyo, S.Sos, Apr</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div>
                    <div class="col-lg-3 col-md-3 box" data-aos="fade-up" data-aos-delay="100">
                        {{--                    <div class="icon"><i class="ion-ios-analytics-outline"></i></div>--}}
                        <h4 class="title">Chief Marketing <br> Sri Cahyaning Umi Salama S.E.I., M.Si.</h4>
                        {{--                    <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>--}}
                    </div>
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

            <div class="col-lg-4 col-md-6 portfolio-item">
                <div class="portfolio-wrap">
                    <figure>
                        <img src="{{asset('images/nib_bmt_muda.png')}}" class="img-fluid" alt="">
                        <a href="{{asset('images/nib_bmt_muda.png')}}" class="link-preview venobox" data-gall="portfolioGallery" title="Web 3" ><i class="ion ion-eye"></i></a>
                    </figure>

                    <div class="portfolio-info">
                        <h4><a href="portfolio-details.html">Nomor Induk Berusaha</a></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 portfolio-item">
                <div class="portfolio-wrap">
                    <figure>
                        <img src="{{asset('images/nik_bmt_muda.png')}}" class="img-fluid" alt="">
                        <a href="{{asset('images/nik_bmt_muda.png')}}" class="link-preview venobox" data-gall="portfolioGallery" title="App 2"><i class="ion ion-eye"></i></a>
                    </figure>

                    <div class="portfolio-info">
                        <h4><a href="portfolio-details.html">Nomor Induk Koperasi</a></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 portfolio-item">
                <div class="portfolio-wrap">
                    <figure>
                        <img src="{{asset('images/siu_simpanpinjam.jpeg')}}" class="img-fluid" alt="">
                        <a href="{{asset('images/siu_simpanpinjam.jpeg')}}" class="link-preview venobox" data-gall="portfolioGallery" title="App 2"><i class="ion ion-eye"></i></a>
                    </figure>

                    <div class="portfolio-info">
                        <h4><a href="portfolio-details.html">Izin Usaha Simpan Pinjam</a></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 portfolio-item">
                <div class="portfolio-wrap">
                    <figure>
                        <img src="{{asset('images/surat_keterangan_domisili.jpeg')}}" class="img-fluid" alt="">
                        <a href="{{asset('images/surat_keterangan_domisili.jpeg')}}" class="link-preview venobox" data-gall="portfolioGallery" title="App 2"><i class="ion ion-eye"></i></a>
                    </figure>

                    <div class="portfolio-info">
                        <h4><a href="portfolio-details.html">Surat Keterangan Domisili</a></h4>
                    </div>
                </div>
            </div>
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
                    <a href="{{asset('/')}}"><img src="{{asset('bootstrap/assets/img/bmt_logo.jpg')}}" alt="logo bmt muda" class="img-fluid" style="height: 30%; width:70%"></a>
                    <p class="font-weight-bold">Tanggal Pendirian</p>
                    <p>30 Januari 2012</p>
                    <p class="font-weight-bold">No & Tanggal Pendirian</p>
                    <p>No 44 Tanggal 30 Januari 2012</p>
                    <p class="font-weight-bold">No & Tanggal Legal Entity</p>
                    <p>NO P2T/10/09.01/01/V/2012 8th Mei 2012</p>
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
                    <p>
                        Head Office : Jalan Kedinding Lor Gang Tanjung 49 <br>
                        Kelurahan Tanah Kali Kedinding, Kecamatan Kenjeran<br>
                        Kota Surabaya <br>
                        Branch Office : Jl.Raya Bungah No.18, Gresik <br>
                        <strong>Phone:</strong> (031) 371 9610121212 / 0858-5081-9919	<br>
                    </p>

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