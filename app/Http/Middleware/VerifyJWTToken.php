<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use App\Http\Requests;
use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class VerifyJWTToken
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
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        try{
            $user = JWTAuth::toUser($token);
        }catch (JWTException $e) {
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['token_expired'], $e->getStatusCode());
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['Invalid token'], $e->getStatusCode());
            }else{
                return response()->json(['error'=>'Token is required']);
            }
        }
        return $next($request);
    }
}
