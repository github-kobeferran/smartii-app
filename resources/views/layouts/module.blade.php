<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ ucfirst(strtolower(config('app.name', 'Laravel'))) }}</title>

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


    @include('inc.navbar')

    <div class="container-fluid mx-0 p-0" >
    
       
                @if (!Auth::guest() && Auth::user()->isAdmin())


                <div class="row vh-100 no-gutters">    
            
                    <div class="col-sm-1 mx-auto">
                        @include('inc.admin.sidebar')

                    </div>

                
                    <div class="col-11 mt-2 mx-auto" >
                                        
                        @yield('content')
                    
                    </div> 
                </div> 


                @elseif(!Auth::guest() && Auth::user()->isStudent())

                    @include('inc.student.sidebar')

                @elseif(!Auth::guest() && Auth::user()->isStudent())

                    @include('inc.faculty.sidebar')            

                @elseif(!Auth::guest() && Auth::user()->isApplicant())

                    @yield('content')
                    
                @endif

           
                                                          

          
    </div>

    

    
    
  
    <script src="{{ asset('js/app.js') }}" defer></script>  

    @yield('javascript')
 
</body>
</html>


