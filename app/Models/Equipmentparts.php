<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class Equipmentparts extends Model
{
    protected $table = 'tbl_equipment_parts';

    public function AllEquipmentParts($limit = 0, $offset = 0, $order_by = 'tep.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_equipment_parts as tep');

//        print_r($cond);die;
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }


        if (isset($cond['search']) && $cond['search'] != '') {

            if (isset($likeArray) && $likeArray != '') {
                $likeArrayFields = $likeArray;
            } else {
                $likeArrayFields = array('te.id');
            }
            if (isset($cond['search']['keyword']) && $cond['search']['keyword'] != '') {
                if (!empty($likeArray)) {

                    $flag = true;
                    $like = '';
                    foreach ($likeArray as $value) {
                        if ($flag) {

                            $like .= $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;
                        } else {
                            $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;

                        }
                        $flag = false;
                    }
//                    DB::enableQueryLog();
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_equipment_model as te', 'te.id', '=', 'tep.id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
//          $query = DB::getQueryLog();
//        print_r($query);
//        die;


//
        return $result;
    }


    public function saveEquipmentpart($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_parts')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_equipment_parts')->insertGetId($input);
            return $result;
        }
    }

    

    public function getEquipment($Id)
    {

        $result = DB::table('tbl_equipment_parts')->where('id', '=', $Id)->first();

        return $result;
    }

}

    
   




