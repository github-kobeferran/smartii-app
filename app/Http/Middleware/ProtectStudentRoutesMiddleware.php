<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProtectStudentRoutesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::guest()){
            return redirect('home');
        }


        if (auth()->user()->user_type == 'student') {
        return $next($request);
        }
        
        return redirect()->back();
    }
}
