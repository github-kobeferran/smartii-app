<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{url('/storage/images/system/logo/smartii.png')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">    

    <title>
        @hasSection('page-title')
            @yield('page-title') / Smartii
        @else
            Smartii
        @endif
    </title> 

    <meta name="description" content="@yield('meta-content')">
    <meta property="og:url"  content="{{url('/')}}" />

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://kit.fontawesome.com/6421dddc90.js" crossorigin="anonymous"></script>    
    <script src="https://cdn.ckeditor.com/4.16.1/basic/ckeditor.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
</head>
<body class="@yield('class')">
           

    <script>
        var APP_URL = {!! json_encode(url('/')) !!}
    </script>
                                    
    @include('inc.navbar')

    @yield('studentprofile')
    
        <main>
            @yield('content')
        </main> 
                
</body>
</html>
