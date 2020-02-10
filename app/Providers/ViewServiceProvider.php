<?php

namespace App\Providers;

use App\Models\Users;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\User;
use Zizaco\Entrust\EntrustFacade as Entrust;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            $user = request()->user();
            //dd($user->attributes['first_name'] != $user->original['first_name']);
            if (!empty($user)) {
                $userid = $user->getOriginal('id');

                $member = new Customer();
                $user = new Users();
                //$user = User::find($userid);
               // echo'<pre>';print_r($user);'</pre>';die;
                //print_r(($user->can('equipments')));die;
                $profileDetails = $member->getUser($userid);
                $getGroupUser = $user->getGroupUser($userid); //print_r($getGroupUser);die;
                $getRole = $user->getRole($getGroupUser->role_id);

//                echo '<pre>';
//                print_r($getGroupUser);
//                die;

//                dd($profileDetails);die;/
                if (!empty($profileDetails->photo)) {
                    $profilePhoto = "users/default/" . $profileDetails->photo;
                } else {

                    $profilePhoto = "/img/admin.png";
                }
                $user_permissions = User::find($userid);


                view()->share('profileImage', $profilePhoto);
                view()->share('name', $profileDetails->name);
                view()->share('role', $getRole->name);
                view()->share('user', $user_permissions);
            }


        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
