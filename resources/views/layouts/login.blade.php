<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>SDDA</title>
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="{{ asset('frontend/images/favicon.png') }}">

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="{{asset('assets/global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/custom/css/custom.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/global_assets/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">

    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script src="{{asset('assets/global_assets/js/main/jquery.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
    <!-- /core JS files -->

    <script src="{{asset('assets/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>

    <!-- /theme JS files -->
    <script src="{{asset('assets/global_assets/sweetalert2/dist/sweetalert2.min.js')}}"></script>


    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/login_validation.js')}}"></script>
    @stack('script')
    <script>
        var getProvinceByCountry = '{{url('get-province-by-country')}}';
        var getDistrictByProvince = '{{url('get-district-by-province')}}';
        var getCityByProvince = '{{url('get-city-by-province')}}';
    </script>
    <script src="{{asset('assets/custom/js/custom.js')}}"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-L14RHXC1KV"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-L14RHXC1KV');
    </script>
</head>

<body>

<!--**********************************
                Content body start
     ***********************************-->
@yield('content')


<!-- <script src="{{ asset('assets/custom/js/custom.js') }}" type="text/javascript"></script> -->
</body>

</html>
