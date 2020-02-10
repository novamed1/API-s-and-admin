<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class Device extends Model
{
    protected $table = 'tbl_device';
    public function Alldevice($limit = 0, $offset = 0, $order_by = 'td.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_device as td');

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
                            $like .= " AND " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;

                        }
                        $flag = false;
                    }
//                    DB::enableQueryLog();
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }
        $query->join('tbl_device_model as tdm','tdm.id','=','td.device_model_id');

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
    
 public function AlldeviceGrid($limit = 0, $offset = 0, $order_by = 'td.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_device as td');

//        print_r($cond['search']['td.last_cal_date']);die;
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
//                 echo '<pre>';print_r($cond['search']);exit;
                 if($key == 'td.last_cal_date'){
                     if($value != '') {
                         $value = date('Y-m-d', strtotime(str_replace('-', '/', $value)));
                     }
                 }

                 if($key == 'td.next_due_date'){
                     if($value != '') {
                         $value = date('Y-m-d', strtotime(str_replace('-', '/', $value)));
                     }
                 }
                 if($value) {
                     if ($flag) {

                         $like .= $key . " LIKE '%" . trim($value) . "%' ";
                     } else {
                         $like .= " AND " . $key . " LIKE '%" . trim($value) . "%' ";
                     }
                     $flag = false;
                 }
             }
             if(array_filter($cond['search'])) {
                 $query->whereRaw('(' . $like . ')');
             }
         }
     }

     $query->join('tbl_device_model as tdm','tdm.id','=','td.device_model_id');
     $query->join('tbl_frequency as f','f.id','=','td.frequency_id','left');

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

    
     public function saveDevice($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_device')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_device')->insertGetId($input);
            return $result;
        }
    }
     public function getdevice($Id)
    {
       
            $result = DB::table('tbl_device')->where('id', '=', $Id)->first();
        return $result;
    }

    public function getdeviceAsFound($Id)
    {

        $result = DB::table('tbl_device as d')->where('d.id', '=', $Id)
            ->join('tbl_device_model as dm','dm.id','=','d.device_model_id')
            ->join('tbl_units as u','u.id','=','d.unit_id','left')
            ->join('tbl_sensitivity as s','s.id','=','d.sensitivity_id','left')
            ->select('dm.name as model_name','d.serial_no','s.name as sensitivity','u.unit','d.id as device_id')
            ->first();
        return $result;
    }

    //for deleting user management
    public function deletedevice($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;


        $delete = DB::table('tbl_device')->where('id', $id)->delete();

        if ($delete == 1) {
//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }

    //check Manufacturer serial Number exists r not

    public function getSerialNo($serialno,$ID)
    {

        if ($ID) {
            $results = DB::table('tbl_device')->where('id', $ID)->where('serial_no', '=', $serialno)->first();
            if (!$results) {
                $result = DB::table('tbl_device')->where('serial_no', '=', $serialno)->first();
            } else {
                $result = '';
            }
        } else {
            $result = DB::table('tbl_device')->where('serial_no', '=', $serialno)->first();

        }
//        print_r($result);exit;
        return $result;
    }


}
    
