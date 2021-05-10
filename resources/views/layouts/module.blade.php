<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
</head>
<body>
    <div id="app">
        @include('inc.navbar')

        <div class="container-fluid"  >
            <div class="row min-vh-100 flex-column flex-md-row">           

            @if (!Auth::guest() && Auth::user()->isAdmin())
                @include('inc.admin.sidebar')
            @elseif(!Auth::guest() && Auth::user()->isStudent())
                @include('inc.student.sidebar')
            @elseif(!Auth::guest() && Auth::user()->isStudent())
                @include('inc.faculty.sidebar')            
            @elseif(!Auth::guest() && Auth::user()->isApplicant())
                @include('inc.applicant.sidebar')
            @endif
            
            <main class="col bg-faded py-3 flex-grow-1">                
                @yield('content')
            </main>                            

            </div>    
        </div>

    </div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
