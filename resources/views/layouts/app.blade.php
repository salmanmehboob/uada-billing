<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>SDDA</title>
    <!-- Favicon icon -->
     @include('layouts.header_files')
{{--    <link rel="shortcut icon" href="{{showImage(getSettingValue('favicon'),'favicon')}}">--}}
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
@include('layouts.header_menu')

<!-- Page content -->
<div class="page-content">

@include('layouts.sidebar')

<!-- Main content -->
    <div class="content-wrapper">


        @yield('content')

        @include('layouts.footer_files')

    </div>
    <!-- /main content -->

</div>

</body>

</html>
