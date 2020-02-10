<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Zizaco\Entrust\Middleware\EntrustAbility;
use DB;

class TokenEntrustAbility extends BaseMiddleware
{

    public function handle($request, Closure $next, $roles, $permissions=false,$roleCategory=false, $validateAll = false)
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
        $query = DB::table('tbl_group_user');
        $query->select('tbl_group_user.*','tbl_user_group.*','tbl_roles.*','tbl_user_group.name as group_name','tbl_roles.name as role_name','tbl_permissions.name as permission_name');
        $query->where('users_id',$user->id);
        $query->join('tbl_user_group','tbl_user_group.id','=','tbl_group_user.user_group_id');
        $query->join('tbl_roles','tbl_roles.id','=','tbl_group_user.role_id');
        $query->join('tbl_permission_group','tbl_permission_group.role_id','=','tbl_user_group.id');
        $query->join('tbl_permissions','tbl_permissions.id','=','tbl_permission_group.permission_id');
        $result = $query->first();

        if($result)
        {
            if($permissions=="")
            {
                if($result->group_name != $roles || $result->role_name != $roleCategory)
                {
                    return $this->respond('tymon.jwt.invalid', 'You not having permission to access', 401, 'Unauthorized');
                }
            }
            elseif($roleCategory=="")
            {
                if($result->group_name != $roles || $result->permission_name != $permissions)
                {
                    return $this->respond('tymon.jwt.invalid', 'You not having permission to access', 401, 'Unauthorized');
                }
            }

        }
        else
        {
            return $this->respond('tymon.jwt.invalid', 'You not having permission to access', 401, 'Unauthorized');
        }
//        if (!$user->ability(explode('|', $roles), explode('|', $permissions), array('validate_all' => $validateAll))) {
//            return $this->respond('tymon.jwt.invalid', 'You not having permission to access', 401, 'Unauthorized');
//        }
        //print_r($user);die;
        $this->events->fire('tymon.jwt.valid', $user);

        return $next($request);
    }
}