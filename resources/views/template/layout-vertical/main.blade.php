<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="description" content="App">
    <meta name="keywords" content="App">
    <meta name="author" content="Muhammad Agung Mahardhika">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/unand.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">

    {{-- Swet Alert --}}
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.all.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <!-- Font Awesome -->
    <script src="{{ asset('assets/extensions/fontawesome/fontawesome.js') }}" crossorigin="anonymous"></script>

    <!-- Gsap -->
    {{-- <script src="{{ asset('assets/extensions/gsap/gsap.min.js') }}"></script>
        <script src="{{ asset('assets/extensions/gsap/ScrollTrigger.min.js') }}"></script> --}}

    {{-- Jquery --}}
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatable/dataTables.css') }}">

    {{-- Global js --}}
    <script src="{{ asset('assets/app/global.js') }}"></script>
    @yield('header')
    <title>Si Pilmapres</title>
</head>

<body class="light">
    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    <div id="app">
        @include('components.large-modal')
        @include('components.normal-modal')
        {{-- Sidebar --}}
        @include('template.layout-vertical.sidebar')
        <div id="main" class="layout-navbar navbar-fixed">
            <header>
                {{-- Navbar --}}
                @include('template.layout-vertical.navbar')
            </header>
            <div id="main-content">
                <div class="page-heading">

                    {{-- Main Content --}}
                    <div class="page-content">
                        @yield('container')
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            @include('template.layout-vertical.footer')

        </div>
    </div>


    <script src="{{ asset('assets/static/js/components/dark.js') }} "></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }} "></script>
</body>

</html>
