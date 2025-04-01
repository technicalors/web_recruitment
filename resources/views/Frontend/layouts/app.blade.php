<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('storage/' . \App\Models\Config::getIcon()) }}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle', 'Trang tuyển dụng')</title>
    @stack('meta')

    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <link href="{{ asset('assets/css/stylecd4e.css') }}" id="stylesheet" rel="stylesheet" type="text/css">
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" /> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uicons@2.0.0/css/uicons-regular-rounded.min.css" /> --}}

    {{-- <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/css/perfect-scrollbar.min.css" /> --}}

    <style>
        .banner-main {
            width: 100%;
            height: 80%;
        }

        .banner-slider {
            width: 100vw;
            overflow: visible;
        }

        .banner-slide img {
            max-height: 400px;
            -o-object-fit: cover;
            object-fit: cover;
            width: 100%;
            z-index: 0;
        }
    </style>
</head>

<body>
    <!-- Header -->
    @include('Frontend.layouts.header')

    <!-- Main -->
    @yield('content')

    <!-- Footer -->
    @include('Frontend.layouts.footer')

    @include('Frontend.layouts.script_default')

    @stack('scripts')
</body>

</html>
