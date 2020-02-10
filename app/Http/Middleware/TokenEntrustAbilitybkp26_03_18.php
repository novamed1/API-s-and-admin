<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Zizaco\Entrust\Middleware\EntrustAbility;

class TokenEntrustAbility extends BaseMiddleware
{

    public function handle($request, Closure $next, $roles, $permissions, $validateAll = false)
    {

        header('Access-Control-Allow-Origin: *');
        $authenticateToken = app('request')->header('token');
        if (! $token = $authenticateToken) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }
       // print_r($user->ability(explode('|', $roles), explode('|', $permissions), array('validate_all' => $validateAll)));die;
        //print_r($validateAll);die;
        if (!$user->ability(explode('|', $roles), explode('|', $permissions), array('validate_all' => $validateAll))) {
            return $this->respond('tymon.jwt.invalid', 'You not having permission to access', 401, 'Unauthorized');
        }
        //print_r($user);die;
        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}