<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use App\Http\Requests;
use Closure;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Cors
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
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With, token');
        header('Access-Control-Allow-Credentials: true');

        if (!$request->isMethod('options')) {
            return $next($request);
        }
    }
}
