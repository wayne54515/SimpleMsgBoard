<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title or '留言板' }}</title>
    <link rel=stylesheet type="text/css" href="{{ asset('css/all.css') }}">
</head>
<body>
    {{-- Vue.js 掛載點 --}}
    <div id="app">
        {{--  @section('navbar')
            @include('layouts.navbar')  --}}
        @show

        @yield('content')
    </div>
 <script src="{{ asset('js/main.js')}}"></script>
</body>
</html>