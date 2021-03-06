<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#636b6f">
	<meta name="description" content="Weather stations website.">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Weather Stations')</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    @if(isset($_COOKIE['style']) && $_COOKIE['style'] == 'dark')
        <link id="pagestyle" href="{{ asset('css/dark_theme.css') }}" rel="stylesheet">
    @else
        <link id="pagestyle" href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endif
    @yield('styles')
</head>
<body>
    <div id="app">
        @include('layouts.partials.nav')

        <main class="py-4 container">
            @include('layouts.partials.messages')
            @yield('content')
        </main>
    </div>
    
    <script>
        window.onload = function() {
            var dark = "{{ asset('css/dark_theme.css') }}";
            var light = "{{ asset('css/app.css') }}";
            if(Cookies.get('style') == 'dark') {
                document.getElementById("dark_theme").checked = true;
                document.getElementById('pagestyle').setAttribute('href', dark);
            } else {
                document.getElementById('pagestyle').setAttribute('href', light);
            }
        };
        
        document.getElementById('dark_theme').addEventListener('change', () => {
            swapStyle();
            setTimeout(() => {
                chartColorSwap();
            }, 100);
        });

        function swapStyle() {
            var toggler = document.getElementById("dark_theme");
            var dark = "{{ asset('css/dark_theme.css') }}";
            var light = "{{ asset('css/app.css') }}";
            if(toggler.checked == true) {
                document.getElementById('pagestyle').setAttribute('href', dark);
                Cookies.set('style', 'dark');
            } else {
                document.getElementById('pagestyle').setAttribute('href', light);
                Cookies.remove('style');
            }
            return true;
        }
    </script>
    @stack('scripts')
</body>
</html>
