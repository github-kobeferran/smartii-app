<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{url('/storage/images/system/logo/smartii.png')}}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Smartii</title>

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
        
        function ucfirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function getMeta(metaName) {
            const metas = document.getElementsByTagName('meta');

            for (let i = 0; i < metas.length; i++) {
                if (metas[i].getAttribute('name') === metaName) {
                return metas[i].getAttribute('content');
                }
            }

            return '';
        }


        function isEmpty(obj) {
            return Object.keys(obj).length === 0;
        }
    </script>


    @include('inc.navbar')

    <div class="container-fluid mx-0 p-0" >
    
                {{-- ******************************* IF USER IS ADMIN --}}
                @if (!Auth::guest() && Auth::user()->isAdmin())


                    <div class="row vh-100 no-gutters">    
                
                        <div class="col-sm-1 mx-auto">
                            @include('inc.admin.sidebar')

                        </div>

                    
                        <div class="col-11 mt-3 mx-auto" >
                                            
                            @yield('content')
                        
                        </div> 
                    </div> 


                {{-- ******************************* IF USER IS STUDENT --}}
                @elseif(!Auth::guest() && Auth::user()->isStudent())

                    <?php 
                    
                    return redirect()->route('studentProfile');

                    ?>


                {{-- ******************************* IF USER IS FACULTY --}}
                @elseif(!Auth::guest() && Auth::user()->isFaculty())

                    @include('inc.faculty.sidebar') 



                {{-- ******************************* IF USER IS APPLICANT --}}
                @elseif(!Auth::guest() && Auth::user()->isApplicant()) 
                                
                    <?php 
                    
                        if(auth()->user()->member != null){
                            $submitted = true;
                        } else {
                            $submitted = false;
                        }
                                        
                    ?>

                    @if($submitted)

                        @yield('status')
                
                    @else
                        
                        @yield('admission')
                    
                    @endisset
            
                    

                @endif

           
                                                          

          
    </div>

    

    
    
  
    <script src="{{ asset('js/app.js') }}" defer></script>  

    @yield('javascript')
 
</body>
</html>


