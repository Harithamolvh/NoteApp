<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class CheckUser
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
        $user=Auth::user();
        if(!empty($user)){

            return $next($request);

        }else{
           
            // return view('auth.login');
            return response()->view('auth.login');
        }
       
    }
}
