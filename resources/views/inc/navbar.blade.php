  <nav style="background-image: linear-gradient(145deg, rgb(101, 202, 128)  , rgb(253, 253, 253), #E6C694);" id="navbar" class="navbar navbar-expand-md navbar-light bg-white shadow-sm ">    
    <div class="container pl-0">
        <a style="font-family: 'Raleway', sans-serif; font-weight: 900px; color:#044716 !important;" class="navbar-brand mr-0 pl-0" href="{{ url('/') }}">
            <img style="filter: drop-shadow(0 0 .2em #FFD13A); height: 40px; width: 40px; " src="{{url('/storage/images/system/logo/smartii.png')}}" alt="" srcset="">
            <span class="ml-2">SMARTII</span>
        </a>
        <button class="navbar-toggler "type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span style="font-size: 1em;" class="navbar-toggler-icon"></span>
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
                            @empty(Auth::user()->member)

                                {{Auth::user()->name}}
                                
                            @else                          

                                @switch(Auth::user()->member->member_type)
                                    @case('admin')                                        
                                        {{\App\Models\Admin::find(auth()->user()->member->member_id)->name}}
                                        @break
                                    @case('faculty')
                                        {{ucfirst(\App\Models\Faculty::find(auth()->user()->member->member_id)->first_name) . ' ' . ucfirst(\App\Models\Faculty::find(auth()->user()->member->member_id)->last_name)}}
                                        @break
                                    @case('student')
                                        {{ucfirst(\App\Models\Student::find(auth()->user()->member->member_id)->first_name) . ' ' . ucfirst(\App\Models\Student::find(auth()->user()->member->member_id)->last_name)}}
                                        @break
                                    @case('applicant')
                                        {{ucfirst(\App\Models\Applicant::find(auth()->user()->member->member_id)->first_name) . ' ' . ucfirst(\App\Models\Applicant::find(auth()->user()->member->member_id)->last_name)}}
                                        @break
                                    @default
                                        {{ucfirst(Auth::user()->name)}}
                                        
                                @endswitch
                            @endempty
       


                        </a>

                        <div class="dropdown-menu dropdown-menu-right color-custom-green" aria-labelledby="navbarDropdown">

                            <?php $role = auth()->user()->user_type; ?>

                            @if (!Auth::guest() && !Auth::user()->isApplicant())
                                
                                <a href="/home" class="dropdown-item">Homepage</a>

                            @endif


                            <a class="dropdown-item"
                            
                            @if(!Auth::guest() && Auth::user()->isApplicant())
                               
                                <?php $submitted = auth()->user()->member; ?>

                                @isset($submitted)

                                    href="{{ route("appStatus") }}"
                            
                                @else
                                    
                                    href="{{ route("admissionForm") }}"
                                
                                @endisset

                                
                                
                            @elseif(!Auth::guest() && Auth::user()->isStudent())

                                href="{{ route('studentProfile') }}"

                            @elseif(!Auth::guest() && Auth::user()->isFaculty())

                                href="{{ route('facultyClasses') }}"


                            @elseif(!Auth::guest() && Auth::user()->isAdmin())
                            
                            <?php  $admin = \App\Models\Admin::find(auth()->user()->member->member_id); ?>

                                @if($admin->position == 'superadmin')
                                    href="{{ route('adminDashboard') }}"
                                @elseif($admin->position == 'accounting')
                                    href="{{ route('adminPayment') }}"
                                @elseif($admin->position == 'registrar')
                                    href="{{ route('adminView') }}"
                                @endif


                            @endif  

                            >                         

                                @if(!Auth::guest() && Auth::user()->isApplicant())
                                    @isset($submitted)

                                        Application Status
                                
                                    @else
                                        
                                        Application Form
                                    
                                    @endisset

                                @elseif(!Auth::guest() && Auth::user()->isStudent())
                                    Profile
                                @elseif(!Auth::guest() && Auth::user()->isFaculty())
                                    Classes
                                @else
                                {{ __(ucfirst($role) . ' Module') }}
                                @endif

                            </a>

                            @if(!Auth::guest() && Auth::user()->isFaculty())

                                <a class="dropdown-item" href="/facultydetails"> Personal Details</a>

                            @endif


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