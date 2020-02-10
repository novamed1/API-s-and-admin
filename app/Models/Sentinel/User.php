<?php

namespace App\Models\Sentinel;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Cartalyst\Sentinel\Users\EloquentUser as SentryUserModel;

class User extends SentryUserModel implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $timestamps=false;
    protected $table = 'tbl_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'email',
        'password',
        'name',
        'last_name',
        'first_name',
        'mobile_no',
        'created_date',
        'modified_date',
        'created_by',
        'photo',
        'is_email_verified',
        "modified_by",
        'permissions',
        'is_active',

    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    protected $persistableKey = 'user_id';

    protected $loginNames = [ 'email'];

}
