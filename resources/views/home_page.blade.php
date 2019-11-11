<!doctype html>
<html lang="en">

<!-- Mirrored from demos.creative-tim.com/light-bootstrap-dashboard-pro/examples/pages/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 01 Mar 2017 13:33:45 GMT -->
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="http://demos.creative-tim.com/light-bootstrap-dashboard-pro/assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>BMT MUDA (Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada)</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

     <!-- Canonical SEO -->
    <link rel="canonical" href="https://www.creative-tim.com/product/light-bootstrap-dashboard-pro"/>

    <!--  Social tags      -->
    <meta name="keywords" content="creative tim, html dashboard, html css dashboard, web dashboard, bootstrap dashboard, bootstrap, css3 dashboard, bootstrap admin, light bootstrap dashboard, frontend, responsive bootstrap dashboard">

    <meta name="description" content="Forget about boring dashboards, get an admin template designed to be simple and beautiful.">

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Light Bootstrap Dashboard PRO by Creative Tim">
    <meta itemprop="description" content="Forget about boring dashboards, get an admin template designed to be simple and beautiful.">

    <meta itemprop="image" content="bootstrap/assetss3.amazonaws.com/creativetim_bucket/products/34/original/opt_lbd_pro_thumbnail.jpg">
    <!-- Twitter Card data -->

    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@creativetim">
    <meta name="twitter:title" content="Light Bootstrap Dashboard PRO by Creative Tim">

    <meta name="twitter:description" content="Forget about boring dashboards, get an admin template designed to be simple and beautiful.">
    <meta name="twitter:creator" content="@creativetim">
    <meta name="twitter:image" content="bootstrap/assetss3.amazonaws.com/creativetim_bucket/products/34/original/opt_lbd_pro_thumbnail.jpg">
    <meta name="twitter:data1" content="Light Bootstrap Dashboard PRO by Creative Tim">
    <meta name="twitter:label1" content="Product Type">
    <meta name="twitter:data2" content="$29">
    <meta name="twitter:label2" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="Light Bootstrap Dashboard PRO by Creative Tim" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="../dashboard.html" />
    <meta property="og:image" content="bootstrap/assetss3.amazonaws.com/creativetim_bucket/products/34/original/opt_lbd_pro_thumbnail.jpg"/>
    <meta property="og:description" content="Forget about boring dashboards, get an admin template designed to be simple and beautiful." />
    <meta property="og:site_name" content="Creative Tim" />


    <!-- Bootstrap core CSS     -->
    <link href="bootstrap/assets/css/bootstrap.min.css" rel="stylesheet" />

    <!--  Light Bootstrap Dashboard core CSS    -->
    <link href="bootstrap/assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="bootstrap/assets/css/demo.css" rel="stylesheet" />


    <!--     Fonts and icons     -->
    <link href="bootstrap/assetsmaxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="bootstrap/assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

</head>
<body>

<nav class="navbar navbar-transparent navbar-absolute">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse">

            <ul class="nav navbar-nav navbar-right">
                <li>
                    @guest
                    <a href="{{url('/login')}}">
                        Login
                    </a>
                    @else
                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Log out
                        </a>
                    @endguest
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="wrapper wrapper-full-page">
    <div class="full-page register-page" data-color="default" data-image="bootstrap/assets/img/bmt_logo.jpg">

    <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="header-text">
                            <h2>BMT MUDA</h2>
                            <h4>Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada</h4>
                            <hr />
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <div class="media">
                            <div class="media-left">
                                <div class="icon">
                                    <i class="pe-7s-user"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                <h4>Profil BMT</h4>
                                Koperasi Baitul Maal wat Tamwil “Mandiri Ukhuwah Persada” Jawa Timur atau yang lebih dikenal dengan BMT MUDA Jatim
                                menjadi wadah yang cukup ampuh untuk secara bersama-sama meningkatkan harkat hidup masyarakat kecil.<br>
                                <a href="http://www.bmtmuda.com/2012/01/profile-bmt.html" class="text-light">Detail info !</a>
                            </div>
                        </div>

                        <div class="media">
                            <div class="media-left">
                                <div class="icon">
                                    <i class="pe-7s-portfolio"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                <h4>Koperasi BMT</h4>
                                BMT Muda adalah koperasi berbasis syariah dengan solusi pembiayaan NON RIBA. Lebih Menentramkan hati.
                                <a href="{{url('/login')}}" class="text-light">Kunjungi halaman koperasi</a>
                            </div>
                        </div>

                        <div class="media">
                            <div class="media-left">
                                <div class="icon">
                                    <i class="pe-7s-home"></i>
                                </div>
                            </div>
                            <div class="media-body">
                                <h4>Maal</h4>
                                BMT MUDA Jatim juga turut membantu masyarakat lewat kegiatan-kegitan maal yang dilakukan
                                untuk meningkatkan kesejahteraan masyarakat.
                                <a href="{{route('maal')}}" class="text-light">Detail info !</a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    	 <footer class="footer footer-transparent">
            <div class="container">
                <p class="copyright text-center">
                    &copy;  <a href="http://www.creative-tim.com/">BMT MUDA</a>,
                    <a> Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada</a>
                    {{--&copy; 2016 <a href="http://www.creative-tim.com/">Creative Tim</a>, made with love for a better web--}}
                </p>
            </div>
        </footer>

    </div>

</div>


</body>

    <!--   Core JS Files and PerfectScrollbar library inside jquery.ui   -->
    <script src="bootstrap/assets/js/jquery.min.js" type="text/javascript"></script>
    <script src="bootstrap/assets/js/jquery-ui.min.js" type="text/javascript"></script>
	<script src="bootstrap/assets/js/bootstrap.min.js" type="text/javascript"></script>


	<!--  Forms Validations Plugin -->
	<script src="bootstrap/assets/js/jquery.validate.min.js"></script>

	<!--  Plugin for Date Time Picker and Full Calendar Plugin-->
	<script src="bootstrap/assets/js/moment.min.js"></script>

    <!--  Date Time Picker Plugin is included in this js file -->
    <script src="bootstrap/assets/js/bootstrap-datetimepicker.js"></script>

    <!--  Select Picker Plugin -->
    <script src="bootstrap/assets/js/bootstrap-selectpicker.js"></script>

	<!--  Checkbox, Radio, Switch and Tags Input Plugins -->
	<script src="bootstrap/assets/js/bootstrap-checkbox-radio-switch-tags.js"></script>

	<!--  Charts Plugin -->
	<script src="bootstrap/assets/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="bootstrap/assets/js/bootstrap-notify.js"></script>

    <!-- Sweet Alert 2 plugin -->
	<script src="bootstrap/assets/js/sweetalert2.js"></script>

    <!-- Vector Map plugin -->
	<script src="bootstrap/assets/js/jquery-jvectormap.js"></script>

    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js"></script>

	<!-- Wizard Plugin    -->
    <script src="bootstrap/assets/js/jquery.bootstrap.wizard.min.js"></script>

	<!--  Bootstrap Table Plugin    -->
	<script src="bootstrap/assets/js/bootstrap-table.js"></script>

	<!--  Plugin for DataTables.net  -->
	<script src="bootstrap/assets/js/jquery.datatables.js"></script>


    <!--  Full Calendar Plugin    -->
    <script src="bootstrap/assets/js/fullcalendar.min.js"></script>

    <!-- Light Bootstrap Dashboard Core javascript and methods -->
	<script src="bootstrap/assets/js/light-bootstrap-dashboard.js"></script>

	<!--   Sharrre Library    -->
    <script src="bootstrap/assets/js/jquery.sharrre.js"></script>

	<!-- Light Bootstrap Dashboard DEMO methods, don't include it in your project! -->
	<script src="bootstrap/assets/js/demo.js"></script>

    <script type="text/javascript">
        $().ready(function(){
            lbd.checkFullPageBackgroundImage();

            setTimeout(function(){
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 1000)
        });
    </script>

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','bootstrap/assetswww.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-46172202-1', 'auto');
      ga('send', 'pageview');

    </script>


<!-- Mirrored from demos.creative-tim.com/light-bootstrap-dashboard-pro/examples/pages/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 01 Mar 2017 13:33:45 GMT -->
</html>
