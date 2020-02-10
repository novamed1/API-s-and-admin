<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class Serviceplantype extends Model
{
    protected $table = 'tbl_plan_type';

    public function plantypeGrid($limit = 0, $offset = 0, $order_by = 'tp.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_plan_type as tp');

//        print_r($cond);die;
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }

        if (isset($cond['search']) && $cond['search'] != '') {


            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key=>$value) {
                    if($value) {
                        if ($flag) {

                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
                        } else {
                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
                        }
                        $flag = false;
                    }
                }
                if(array_filter($cond['search'])) {
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }

        return $result;
    }



    public function saveserviceplantype($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_plan_type')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_plan_type')->insertGetId($input);
            return $result;
        }
    }
     public function getserviceplantype($Id)
    {
       
            $result = DB::table('tbl_plan_type')->where('id', '=', $Id)->first();
       
        return $result;
    }

    
}