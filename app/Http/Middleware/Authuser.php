<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authuser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!Auth::check()) {


            return redirect('/login');

        }
        if(count(auth()->user()->roles)==0)
        {
            return redirect('/login');
        }
        if(!is_null(auth()->user()->roles))
        {
            if(auth()->user()->roles->first()->name =='Admin' || auth()->user()->roles->first()->name =='Manager' || auth()->user()->roles->first()->name =='Employee')
            {   
                
                return $next($request);
            }else{
                Auth::logout();

                return redirect('/login')->withErrors(['msg' => 'Please verify that your information is correct']);
            }
        }

    
       
    }
}