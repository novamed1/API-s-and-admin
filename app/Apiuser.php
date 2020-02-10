<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Apiuser extends Model
{

    public function __construct() {
        $this->table = DB::table('users');

//        print_r($this->SMS);die;
    }
    public function login($inputs){

        $query= $this->table->where('name',$inputs['name'])->first();

        return $query;
    }

    function saveUser($data)
    {
        if(isset($data['id']) && $data['id'])
        {
            $this->table->where('id',$data['id'])
                ->update($data);

            return $data['id'];

        }
        else
        {
            $insertId =   $this->table->insertGetId($data);
            return $insertId;

        }

    }
}
