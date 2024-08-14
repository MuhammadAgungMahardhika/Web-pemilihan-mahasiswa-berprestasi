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
    <link rel="shortcut icon" href="{{ url('images/LOGO-POKDARWIS.png') }}">

    {{--  --}}
    <link rel="shortcut icon" href="{{ asset('assets/compiled/svg/favicon.svg') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    {{-- Swet Alert --}}
    <script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <!-- Font Awesome -->
    <script src="{{ asset('assets/extensions/fontawesome/fontawesome.js') }}" crossorigin="anonymous"></script>
    {{-- Jquery --}}
    <script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>

    {{-- global js --}}
    <script src="{{ asset('assets/app/global.js') }}"></script>
    <title>Si Pilmapres</title>
</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    @include('components.large-modal')
    @include('components.normal-modal')
    {{-- Main Content --}}
    <div class="page-content">
        @yield('container')
    </div>


    <script src="{{ asset('assets/static/js/components/dark.js') }} "></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }} "></script>
</body>

</html>
