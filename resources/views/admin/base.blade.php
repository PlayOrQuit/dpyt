<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Auto Create PlayList Youtube @yield('page-title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <base id="baseUrl" href="{{ url('/') }}">
    <link href="{{ asset('images/logo.png') }}" rel="icon" type="image/x-icon"/>
    <link rel="stylesheet" href="css/app.css">
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
<noscript>
    You need to enable JavaScript to run this app.
</noscript>
<div class="page">
    <div class="flex-fill">
        @include('admin.header')
        @include('admin.nav')
        <div class="my-3 my-md-5">
            @yield('page-content')
        </div>
    </div>
    @include('admin.footer')
</div>
</body>
</html>
