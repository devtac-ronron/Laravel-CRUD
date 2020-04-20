<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
        @include('inc.navbar')

    <div class="container mt-2">
        @include('inc.error_message')
        @yield('content')
    </div>
        
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('script')
</body>
</html>
