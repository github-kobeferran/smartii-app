@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">

            @if (\App\Models\Admin::count() > 0)

                @if (\App\Models\Setting::first()->enrollment_mode)
                    <div class="card">
                        <div class="card-header"><h5 class="text-center">REGISTER</h5></div>
        
                        <div class="card-body ">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
        
                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right"><strong>{{ __('Name') }}</strong></label>
        
                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
        
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right"><strong>{{ __('E-Mail Address') }}</strong></label>
        
                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
        
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right"><strong>{{ __('Password') }}</strong></label>
        
                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
        
                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right"><strong>{{ __('Confirm Password') }}</strong></label>
        
                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
        
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Register') }}
                                        </button>
        
                                        
                                    </div>                            
                                </div>      
        
                                <div class="form-group row mb-0 ml-2">
                                    <div class="col-md-6 offset-md-4">
                                    
                                        or
                                        
                                    </div>                            
                                </div>                        
        
                                <div class="form-group row mb-0 ">
                                    <div class="col-md-6 offset-md-4">
                                        <a href="{{ url('auth/google') }}">
                                            <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" >
                                        </a>
                                    </div>
                                </div>
        
                                
                            </form>
                        </div>                
                    </div>
        
                    <div class="border border-primary rounded p-4 mt-1 text-center mb-2">
        
                        Note: This registration is only for <strong> New Applicants </strong>, if you are already an existing Student or Faculty. Please go to the registrar for your User Details.
        
                    </div>
                @else
                <div class="card shadow">
                    <div class="card-body">
                      <h5 class="card-title">ENROLLMENT IS NOW OFFICIALY CLOSED</h5>
                      <h6 class="card-subtitle mb-2 text-muted">at {{\Carbon\Carbon::parse(\App\Models\Setting::first()->enrollment_mode_updated_at)->isoFormat("MMMM DD, YYYY h:mm A")}}</h6>
                      <p class="card-text">for inquiries please <a href="{{url('/contactus')}}" class="card-link">contact us</a></p>
                     
                    </div>
                  </div>
                @endif
                
            @endif
            
        </div>
    </div>
</div>
@endsection
