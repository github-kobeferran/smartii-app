 <nav style="background-image: linear-gradient(90deg, #c6f5d3 , rgb(253, 253, 253), rgb(233, 222, 185));" id="navbar" class="navbar navbar-expand-md navbar-light bg-white shadow ">    
    <div class="container pl-0">
        <a style="font-family: 'Raleway', sans-serif; font-weight: 900px; color:#044716 !important;" class="navbar-brand mr-0 pl-0" href="{{ url('/') }}">
            <img style="height: 40px; width: 40px; " src="{{url('/storage/images/system/logo/smartii.png')}}" alt="" srcset="">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a style="font-family: 'Raleway', sans-serif; font-weight: 900px; color: #044716;" class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a style="font-family: 'Raleway', sans-serif; font-weight: 900px; color: #044716;" class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="name nav-item dropdown">
                        <a style="font-weight: 900px; color:#044716" id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-right color-custom-green" aria-labelledby="navbarDropdown">

                            <?php $role = auth()->user()->user_type ?>
                            <a href="/home" class="dropdown-item">Home</a>

                            <a class="dropdown-item"
                            
                            @if(!Auth::guest() && Auth::user()->isApplicant())
                               
                                <?php $submitted = auth()->user()->member; ?>

                                @isset($submitted)

                                    href="{{ route("appStatus") }}"
                            
                                @else
                                    
                                    href="{{ route("admissionForm") }}"
                                
                                @endisset

                                
                                
                            @else
                                href="{{ route($role . 'Dashboard') }}"
                            @endif  

                                >
                                @if(!Auth::guest() && Auth::user()->isApplicant())
                                @isset($submitted)

                                    Application Status
                            
                                @else
                                    
                                    Application Form
                                
                                @endisset

                                @else
                                {{ __(ucfirst($role) . ' Module') }}
                                @endif

                            </a>


                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>  