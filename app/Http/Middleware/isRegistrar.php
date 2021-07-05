<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;

class isRegistrar
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
        $admin = Admin::find(auth()->user()->member->member_id);

        if($admin->position == 'accounting')
            return redirect()->route('adminPayment');       

        return $next($request);
    }
}
