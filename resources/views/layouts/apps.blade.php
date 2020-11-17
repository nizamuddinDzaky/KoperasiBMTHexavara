<!doctype html>
<html lang="en">

<!-- Mirrored from demos.creative-tim.com/light-bootstrap-dashboard-pro/examples/tables/bootstrap-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 01 Mar 2017 13:33:44 GMT -->
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="http://demos.creative-tim.com/light-bootstrap-dashboard-pro/assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>BMT MUDA (Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada)</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!-- Canonical SEO -->
    <link rel="canonical" href="https://www.creative-tim.com/product/light-bootstrap-dashboard-pro"/>

    <!--  Social tags      -->
    <meta name="keywords" content="creative tim, html dashboard, html css dashboard, web dashboard, bootstrap dashboard, bootstrap, css3 dashboard, bootstrap admin, light bootstrap dashboard, frontend, responsive bootstrap dashboard">

    <meta name="description" content="Forget about boring dashboards, get an admin template designed to be simple and beautiful.">

    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content=" BMT MUDA (Baitul Maal Wat Tamwil Mandiri Ukhuwah Persada)">
    <meta itemprop="description" content="Forget about boring dashboards, get an admin template designed to be simple and beautiful.">

    <meta itemprop="image" content="../../../../s3.amazonaws.com/creativetim_bucket/products/34/original/opt_lbd_pro_thumbnail.jpg">
    <!-- Twitter Card data -->

    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@creativetim">
    <meta name="twitter:title" content="Light Bootstrap Dashboard PRO by Creative Tim">

    <meta name="twitter:description" content="Forget about boring dashboards, get an admin template designed to be simple and beautiful.">
    <meta name="twitter:creator" content="@creativetim">
    <meta name="twitter:image" content="../../../../s3.amazonaws.com/creativetim_bucket/products/34/original/opt_lbd_pro_thumbnail.jpg">
    <meta name="twitter:data1" content="Light Bootstrap Dashboard PRO by Creative Tim">
    <meta name="twitter:label1" content="Product Type">
    <meta name="twitter:data2" content="$29">
    <meta name="twitter:label2" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="Light Bootstrap Dashboard PRO by Creative Tim" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="../dashboard.html" />
    <meta property="og:image" content="../../../../s3.amazonaws.com/creativetim_bucket/products/34/original/opt_lbd_pro_thumbnail.jpg"/>
    <meta property="og:description" content="Forget about boring dashboards, get an admin template designed to be simple and beautiful." />
    <meta property="og:site_name" content="Creative Tim" />


    <!-- Bootstrap core CSS     -->
    <link href="{{ URL::asset('bootstrap/assets/css/bootstrap.min.css') }}" rel="stylesheet" />

    <!--  Light Bootstrap Dashboard core CSS    -->
    <link href="{{ URL::asset('bootstrap/assets/css/light-bootstrap-dashboard.css') }}" rel="stylesheet"/>

    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{{ URL::asset('bootstrap/assets/css/demo.css') }}" rel="stylesheet" />

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="{{ URL::asset('bootstrap/assets/css/pe-icon-7-stroke.css') }}" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="{{ URL::asset('web/css/fontawesome.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('web/css/fa-solid.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('web/css/fa-regular.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('web/css/fa-brands.css') }}" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="{{URL::asset('datatables/buttons-1.5.1/css/buttons.dataTables.min.css')}}">
    {{--<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">--}}

    <link rel="stylesheet" href="{{ asset('ChartJS/Chart.min.css') }}">

    <!-- BMTMUDA themes -->
    <link rel="stylesheet" href="{{ asset('bmtmudathemes/assets/css/main.css') }}">

    <!-- Materials Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- datepicker plugin -->
    <link rel="stylesheet" href="{{ asset('bmtmudathemes/assets/jquery-ui/jquery-ui.min.css') }}">

    <!-- Summernote plugin -->
    <link href="{{ asset('bmtmudathemes/assets/summernote/dist/summernote.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Loading component -->
    <link rel="stylesheet" href="{{ asset('bmtmudathemes/assets/css/loading.css') }}">
    
    <style>
        .dataTables_wrapper  {
            margin: 1em;
        }
    </style>
    @yield('extra_style')
</head>
<body>

<div class="wrapper">

    <div class="wrapper">
         @yield('side-navbar')
        <div class="main-panel">
            @yield('top-navbar')
            @yield('custom-component')
            @yield('content')
            @yield('footer')
        </div>
    </div>
</div>

@yield('modal')
@include('modal.reset_password')




</body>

<!--   Core JS Files and PerfectScrollbar library inside jquery.ui   -->
<script src="{{ URL::asset('bootstrap/assets/js/jquery.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('bootstrap/assets/js/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('bootstrap/assets/js/bootstrap.min.js') }}" type="text/javascript"></script>

<!-- Datepicker plugin -->
<script src="{{ asset('bmtmudathemes/assets/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Summernote plugin -->
<script src="{{ asset('bmtmudathemes/assets/summernote/dist/summernote.min.js') }}"></script>

<!--  Forms Validations Plugin -->
<script src=" {{  URL::asset('bootstrap/assets/js/jquery.validate.min.js') }}"></script>

<!--  Notifications Plugin    -->
<script src=" {{  URL::asset('bootstrap/assets/js/bootstrap-notify.js') }}"></script>

<!-- Sweet Alert 2 plugin -->
<script src=" {{  URL::asset('bootstrap/assets/js/sweetalert2.js') }}"></script>

<!--  Bootstrap Table Plugin    -->
<script src="{{ URL::asset('bootstrap/assets/js/bootstrap-table.js') }}"></script>

<!--  Plugin for DataTables.net  -->
<script src="{{ URL::asset('bootstrap/assets/js/jquery.datatables.js') }}"></script>

<!-- Light Bootstrap Dashboard Core javascript and methods -->
<script src="{{ URL::asset('bootstrap/assets/js/light-bootstrap-dashboard.js') }}"></script>

<script src="{{ URL::asset('datatables/buttons-1.5.1/js/buttons.print.min.js')}}"></script>
{{--<script src="{{URL::asset('datatables/buttons-1.5.1/js/buttons.flash.min.js')}}"></script>--}}
<script src="{{URL::asset('datatables/buttons-1.5.1/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('datatables/jszip-2.5.0/jszip.min.js')}}"></script>
<script src="{{URL::asset('datatables/pdfmake-0.1.32/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('datatables/pdfmake-0.1.32/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('datatables/buttons-1.5.1/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::to('maskmoney/src/jquery.maskMoney.js')}}"></script>

{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        @if (session('notification'))
        $.notify({
            icon: '{{ session('icon') }}',
            message: "{{ session('notification') }}"
        },{
            type: 'info',
            timer: 2000
        });
        @endif
    });
</script>

<script>
    notif ={
        statusSuccess: function () {
            $.notify({
                icon: "pe-7s-check",
                message: "<b>{{ session('success') }}</b>"

            }, {
                type: "success",
                timer: 4000
            });
        },
        statusFail: function () {
            $.notify({
                icon: "pe-7s-close-circle",
                message: "<b>{{ session('message') }}</b>"

            }, {
                type: "danger",
                timer: 4000
            });
        }
    }

</script>

<script type="text/javascript">
    $().ready(function(){
        @if(session('success'))
        notif.statusSuccess();
        @elseif(session('message'))
        notif.statusFail();
        @endif
    });
    $('.modal').on('show.bs.modal', function (event) {

        $('.currency').maskMoney({
            allowZero: true,
            precision: 0,
            thousands: ","
        });

        $('.currencyDecimal').maskMoney({
            allowZero: true,
            precision: 2,
            thousands: ","
        });


    });
    $('.resetDatabase').on('click', function (event){
        var r = confirm("Apakah anda yakin ingin reset database?");

        if(r== true)
        {
            window.location.href = "{{ route('admin.reset.database') }}";
        }
    });
</script>

<script>
//    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
//            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
//        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
//    })(window,document,'script','http://www.google-analytics.com/analytics.js','ga');
//
//    ga('create', 'UA-46172202-1', 'auto');
//    ga('send', 'pageview');

</script>

@include('layouts/partials/scripts')

@yield('extra_script')

<!-- Mirrored from demos.creative-tim.com/light-bootstrap-dashboard-pro/examples/tables/bootstrap-table.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 01 Mar 2017 13:33:44 GMT -->
</html>
