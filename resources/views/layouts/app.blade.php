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

    @include('partials.back-to-top')

    @include('partials.js')

    @stack('scripts')
</body>

</html>