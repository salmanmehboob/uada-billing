<!-- Favicon icon -->
<link rel="icon" type="image/png" sizes="16x16" href="">
<!-- Global stylesheets -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
<link href="{{asset('assets/global_assets/css/icons/icomoon/styles.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/global_assets/css/icons/fontawesome/styles.min.css')}}" rel="stylesheet" type="text/css">

 <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/bootstrap_limitless.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/layout.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/components.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/css/colors.min.css')}}" rel="stylesheet" type="text/css">
<link href="{{asset('assets/custom/css/custom.css')}}" rel="stylesheet" type="text/css">

<!-- /global stylesheets -->
<link href="{{asset('assets/global_assets/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}"/>

@stack('style')

<script>
    var urlPath = '{{ url("") }}';
    var CSRF_TOKEN = '{{ csrf_token() }}';
    var getProvinceByCountry = '{{url('get-province-by-country')}}';
    var getDistrictByProvince = '{{url('get-district-by-province')}}';
    var getCityByProvince = '{{url('get-city-by-province')}}';
    var pdfIcon = '{{asset('assets/pdf-icon.png')}}';
</script>


<!-- Core JS files -->
<script src="{{asset('assets/global_assets/js/main/jquery.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="{{asset('assets/global_assets/js/main/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/global_assets/js/plugins/loaders/blockui.min.js')}}"></script>
<!-- /core JS files -->

<script src="{{asset('assets/js/app.js')}}"></script>

<!-- Theme JS files -->
<script src="{{asset('assets/global_assets/js/plugins/visualization/d3/d3.min.js')}}"></script>
<script src="{{asset('assets/global_assets/js/plugins/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{asset('assets/global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
<script src="{{asset('assets/global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/global_assets/js/plugins/pickers/daterangepicker.js')}}"></script>

<script src="{{asset('assets/global_assets/js/demo_pages/dashboard.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/streamgraph.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/sparklines.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/lines.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/areas.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/donuts.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/bars.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/progress.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/heatmaps.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/pies.js')}}"></script>
<script src="{{asset('assets/global_assets/js/demo_charts/pages/dashboard/light/bullets.js')}}"></script>

<!-- /theme JS files -->
<script src="{{asset('assets/global_assets/sweetalert2/dist/sweetalert2.min.js')}}"></script>

@stack('script')

<script src="{{ asset('assets/custom/js/custom.js') }}" type="text/javascript"></script>

