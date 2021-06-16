<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicantSubmitted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guest()){
            return redirect('home');
        }

        if (auth()->user()->member != null) {
            
            if(auth()->user()->user_type == 'applicant')
                return $next($request);
            else                
                return redirect()->route('studentProfile');

        }

        return redirect()->back();
    }
}
