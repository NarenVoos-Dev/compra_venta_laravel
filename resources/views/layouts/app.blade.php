<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>
    @include('partials.head')

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('partials.header')
        @include('partials.menu')
        <div class="vertical-overlay"></div>

        <div class="main-content">
            <!-- Pagina del contenido -->
             @yield('content')

            <!-- fin contenido -->

            @include('partials.footer')
        </div>

    </div>

    @include('partials.back-to-top')

    <div class="customizer-setting d-none d-md-block">
        <div class="p-2 shadow-lg btn-info btn-rounded btn btn-icon btn-lg" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas">
            <i class='mdi mdi-spin mdi-cog-outline fs-22'></i>
        </div>
    </div>

    @include('partials.js')
</body>

</html>