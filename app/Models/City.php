<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Sentinel;
use Carbon\Carbon;
use DateTime;

class City extends Model
{
    protected $table = 'tbl_city';
    public function AllCity($limit = 0, $offset = 0, $order_by = 'c.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_city as c');

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

        $query->join('tbl_state as s', 's.id', '=', 'c.state_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
     return $result;
    }
    public function AllCityGrid($limit = 0, $offset = 0, $order_by = 'c.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_city as c');

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
        $query->join('tbl_state as s', 's.id', '=', 'c.state_id');
        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
        return $result;
    }




    public function saveCity($input)
    {

        if ($input['id']) {
            $result = DB::table('tbl_city')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_city')->insertGetId($input);
            return $result;
        }
    }
     public function getcity($Id)
    {
       
       $result = DB::table('tbl_city')->where('id', '=', $Id)->first();
       
        return $result;
    }


    //for deleting user management
    public function deletecity($id)
    {
        $delete = DB::table('tbl_city')->where('id', $id)->delete();

        if ($delete == 1) {
//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }
}