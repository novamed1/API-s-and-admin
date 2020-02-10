<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class Devicemodel extends Model
{
    protected $table = 'tbl_device_model';
    public function Alldevicemodel($limit = 0, $offset = 0, $order_by = 'tm.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_device_model as tm');

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
        if (isset($cond['except_balance_id']) && $cond['except_balance_id'] != '') {
            $query->where('id','!=',1);
        }
        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
     return $result;
    }

    public function AlldevicemodelGrid($limit = 0, $offset = 0, $order_by = 'tm.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_device_model as tm');

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
        if (isset($cond['except_balance_id']) && $cond['except_balance_id'] != '') {
            $query->where('id', '!=', 1);
        }
        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
        return $result;
    }



    public function saveDevicemodel($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_device_model')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_device_model')->insertGetId($input);
            return $result;
        }
    }
     public function getdevice($Id)
    {
       
            $result = DB::table('tbl_device_model')->where('id', '=', $Id)->first();
       
        return $result;
    }
    
     public function savefrequency($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_frequency')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_frequency')->insertGetId($input);
            return $result;
        }
    }
    
     public function Allfrequency($limit = 0, $offset = 0, $order_by = 'tf.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_frequency as tf');

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
        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
     return $result;
    }
    public function AllfrequencyGrid($limit = 0, $offset = 0, $order_by = 'tf.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_frequency as tf');

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
                foreach ($cond['search'] as $key => $value) {
                    if ($value) {
                        if ($flag) {

                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
                        } else {
                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
                        }
                        $flag = false;
                    }
                }
                if (array_filter($cond['search'])) {
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
    public function getfrequency($Id)
    {
       
            $result = DB::table('tbl_frequency')->where('id', '=', $Id)->first();
       
        return $result;
    }

    
    
}

