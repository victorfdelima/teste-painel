<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Vamo for Business - Reduza custos de entregas com a Vamo</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="/vendors/feather/feather.css">
    <link rel="stylesheet" href="/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="/vendors/css/vendor.bundle.base.css">

    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="/vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="/js/select.dataTables.min.css">
    <!-- <link rel="stylesheet" href="path-to/node_modules/bootstrap-table/dist/bootstrap-table.min.css"> -->

    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="/css/vertical-layout-light/style.map.css">
    <link rel="stylesheet" href="/css/vertical-layout-light/style.css">
    <!-- endinject -->

    <!--script-->
    <script src="{{asset('asset/new/js/jquery.min.js')}}"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="/js/jq.tablesort.js"></script>

    <!--map-->
    <script src="/js/map.js" defer></script>
    <script src="//maps.googleapis.com/maps/api/js?key={{ Config::get('constants.map_key') }}&libraries=places&callback=initMap" defer></script>

</head>

<body>

    <div class="content">
        @yield('content')
    </div>


</body>
<script>
    var current_latitude = 13.0574400;
    var current_longitude = 80.2482605;

    function success(position) {
        document.getElementById('long').value = position.coords.longitude;
        document.getElementById('lat').value = position.coords.latitude

        if (position.coords.longitude != "" && position.coords.latitude != "") {
            current_longitude = position.coords.longitude;
            current_latitude = position.coords.latitude;
        }
        initMap();
    }

    function fail() {
        // Could not obtain location
        console.log('incapaz de obter a sua localização');
        initMap();
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, fail);
    } else {
        console.log('Desculpe, seu navegador não suporta serviços de geolocalização');
        initMap();
    }
</script>
<!-- plugins:js -->
<script src="/vendors/js/vendor.bundle.base.js" defer></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="/vendors/chart.js/Chart.min.js" defer></script>
<script src="/vendors/datatables.net/jquery.dataTables.js" defer></script>
<script src="/vendors/datatables.net-bs4/dataTables.bootstrap4.js" defer></script>
<script src="/js/dataTables.select.min.js" defer></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="/js/off-canvas.js" defer></script>
<script src="/js/hoverable-collapse.js" defer></script>
<script src="/js/template.js" defer></script>
<script src="/js/settings.js" defer></script>
<script src="/js/todolist.js" defer></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="/js/dashboard.js" defer></script>
<script src="/js/Chart.roundedBarCharts.js" defer></script>
<!-- End custom js for this page-->

</html>