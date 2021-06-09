<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="https://kit.fontawesome.com/6421dddc90.js" crossorigin="anonymous"></script>
   
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com"> 

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
                
</head>
<body>
  

    <script>
        var APP_URL = {!! json_encode(url('/')) !!}
    </script>

    <div id="app">



        @include('inc.navbar')

        <div class="container-fluid">
            <div class="row min-vh-100 flex-column flex-md-row" >           

            @if (!Auth::guest() && Auth::user()->isAdmin())
                @include('inc.admin.sidebar')
            @elseif(!Auth::guest() && Auth::user()->isStudent())
                @include('inc.student.sidebar')
            @elseif(!Auth::guest() && Auth::user()->isStudent())
                @include('inc.faculty.sidebar')            
            @elseif(!Auth::guest() && Auth::user()->isApplicant())
                {{-- @include('inc.applicant.sidebar') --}}
            @endif
            
            <main class="col bg-faded py-3 flex-grow-1 shadow">                
                @yield('content')
            </main>                            

            </div>    
        </div>

    </div>

    
    
  
    <script src="{{ asset('js/app.js') }}" defer></script>  

    @yield('javascript')
 
</body>
</html>


