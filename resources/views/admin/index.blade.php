<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>@yield('title') - GOLD MMC</title>
    <meta name="description" content="GOLD MMC">
    <meta name="author" content="Alvin Blade">
    <meta name="robots" content="noindex, nofollow">

    <!-- Open Graph Meta -->
    <meta property="og:title" content="GOLD MMC">
    <meta property="og:site_name" content="GOLD MMC">
    <meta property="og:description" content="GOLD MMC">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <!-- Page JS Plugins CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <script src="{{ asset('assets/admin/js/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    @yield('page_styles')
    <!-- END Icons -->
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/admin/css/oneui.min.css') }}">
    <!-- END Stylesheets -->
</head>

<body>
@include('sweetalert::alert')
<!-- Page Container -->
<div id="page-container"
     class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
    @include('admin.partials.sidebar')

    @include('admin.partials.header')

    @yield('content')

    @include('admin.partials.footer')
</div>
<!-- END Page Container -->
<script src="{{ asset('assets/admin/js/oneui.app.min.js') }}"></script>

@yield('page_scripts')
</body>

</html>
