<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::DASHBOARD;

    public function redirectTo() {
        $user = Auth::user();
    
        switch(true) {        
            case $user->isAdmin():
                return '/admin';
            case $user->isStudent():
                return '/studentprofile';
            case $user->isFaculty():
                return '/myclasses';
            case $user->isApplicant():
                
                if(auth()->user()->member != null)                
                    return '/appstatus';
                else
                    return '/admissionform';      
                                                                      
            default:
                return '/home';
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
