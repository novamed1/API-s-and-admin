<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class Process extends Authenticatable
{
    public function getModelSpares($model_id)
    {


        DB::enableQueryLog();

        $query = DB::table('tbl_equipment_model_spares as MS');
        $query->where('model_id',$model_id);
        $query->where('part_sell',1);
        $query->select('part_name','sku_number','service_price as price','id','image');
        $result = $query->get();

        return $result;
    }



}
