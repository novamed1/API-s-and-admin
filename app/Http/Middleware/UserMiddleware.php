<?php

namespace App\Http\Middleware;

use Closure;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;


class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() != '') {

           
            $role = $request->user()->id;
//echo'<pre>';print_r($role);die;
            if (Sentinel::check() == false) {
                return redirect('admin/login');
                //print_r('hai');die;
            }
            switch ($role) {
                case '1':
                    break;
                
            }


            return $next($request);
        }
        return redirect('admin/login');


    }
}


