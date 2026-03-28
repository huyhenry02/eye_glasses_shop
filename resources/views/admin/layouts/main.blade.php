<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="flexilecode" />
    <title>Quản lý WEB shop</title>

    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/theme.min.css" />
</head>
<body>
@include('admin.layouts.navbar')
@include('admin.layouts.header')

<main class="nxl-container">
    <div class="nxl-content">
        @yield('content')
    </div>
</main>

<script src="/assets/vendors/js/vendors.min.js"></script>
<script src="/assets/vendors/js/daterangepicker.min.js"></script>
<script src="/assets/vendors/js/apexcharts.min.js"></script>
<script src="/assets/vendors/js/circle-progress.min.js"></script>
<script src="/assets/js/common-init.min.js"></script>
<script src="/assets/js/dashboard-init.min.js"></script>
<script src="/assets/js/theme-customizer-init.min.js"></script>
</body>
</html>
