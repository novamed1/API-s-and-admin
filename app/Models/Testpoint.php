<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Sentinel;
use Carbon\Carbon;
use DateTime;

class Testpoint extends Model
{
    protected $table = 'tbl_test_points';

    public function AllTestPointGrid($limit = 0, $offset = 0, $order_by = 'tp.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_test_points as tp');

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




    public function savetestpoint($input)
    {

        if ($input['id']) {
            $result = DB::table('tbl_test_points')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_test_points')->insertGetId($input);
            return $result;
        }
    }
     public function gettestpoint($Id)
    {
       
       $result = DB::table('tbl_test_points')->where('id', '=', $Id)->first();
       
        return $result;
    }


    //for deleting user management
    public function deletetestpoint($id)
    {
        $delete = DB::table('tbl_test_points')->where('id', $id)->delete();

        if ($delete == 1) {
//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }
}