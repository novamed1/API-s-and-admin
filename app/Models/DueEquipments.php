<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class DueEquipments extends Model
{
    protected $table = 'tbl_equipment';

    public function AllEquipments($limit = 0, $offset = 0, $order_by = 'te.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_equipment as te');

//        print_r($cond);die;
//        DB::enableQueryLog();
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
            if (isset($cond['search']['key']) && $cond['search']['key'] != '') {
                 $customerId =$cond['search']['key'];
//                echo'<pre>'; print_r($startdate);die;
                 $query->where('te.customer_id', $customerId);
            }
        }
       if (isset($cond['customerId']) && $cond['customerId'] != '') {
            $query->where('te.customer_id', '=', $cond['customerId']);
        }
        if (isset($cond['userGroupBy']) && $cond['userGroupBy'] != '') {
            $query->groupBy('te.customer_id');
        }
        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_due_equipments as td', 'td.equipment_id', '=', 'te.id');
        $query->join('tbl_equipment_model as tem', 'tem.id', '=', 'te.equipment_model_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
//          $query = DB::getQueryLog();
//        print_r($query);
//        die;


        return $result;
    }
    public function AllEquipmentsGrid($limit = 0, $offset = 0, $order_by = 'te.id', $direction = 'DESC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_equipment as te');

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

        if (isset($cond['customer']) && $cond['customer'] != '') {
            $query->where('tc.id', '=', $cond['customer']);
        }
        if (isset($cond['userGroupBy']) && $cond['userGroupBy'] != '') {
            $query->groupBy('te.customer_id');
        }
        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_due_equipments as td', 'td.equipment_id', '=', 'te.id');
        $query->join('tbl_equipment_model as tem', 'tem.id', '=', 'te.equipment_model_id');
        $query->join('tbl_customer as tc', 'tc.id', '=', 'te.customer_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
//          $query = DB::getQueryLog();
//        print_r($query);
//        die;


        return $result;
    }


    public function saveEquipments($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_equipment')->insertGetId($input);
            return $result;
        }
    }

    public function saveDueequipments($input)
    {


        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_due_equipments')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_due_equipments')->insertGetId($input);
            return $result;
        }
    }

    public function getequipments($Id)
    {

        $result = DB::table('tbl_equipment')->where('id', '=', $Id)->first();

        return $result;
    }

    public function getvalues($Id)
    {
        $query = DB::table('tbl_due_equipments')->where('equipment_id', $Id)->first();
        return $query;
    }

    public function AllDueEquipmentsGrid($limit = 0, $offset = 0, $order_by = 'te.id', $direction = 'ASC', $cond = array(), $count = false)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_due_equipments as DE');
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            if (!isset($cond['count'])) {
                $query->limit($limit);
                $query->offset($offset);
            }
        }


        if (isset($cond['search']) && $cond['search'] != '') {

            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key => $value) {
                    if ($value) {
                    if($key == 'DE.last_cal_date'){
                                                if($value != '') {
                                                    $value = date('Y-m-d', strtotime(str_replace('-', '/', $value)));
                                                }
                                            }
                                            if($key == 'DE.next_due_date'){
                                                if($value != '') {
                                                    $value = date('Y-m-d', strtotime(str_replace('-', '/', $value)));
                                                }
                                            }
                        if ($key != 'due_status') {
                            if ($flag) {

                                $like .= $key . " LIKE '%" . trim($value) . "%' ";
//                       print_r($like);die;
                            } else {
                                $like .= " AND " . $key . " LIKE '%" . trim($value) . "%' ";
//                       print_r($like);die;

                            }
                            $flag = false;
                        }
                    }
                }
//                    DB::enableQueryLog();
                if (array_filter($cond['search']) && $cond['search']['due_status'] == '') {
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }
        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_equipment as E', 'E.id', '=', 'DE.equipment_id');
        $query->join('tbl_customer as C', 'C.id', '=', 'E.customer_id');
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->whereNOTIn('DE.id', function ($query) {
            $query->select('due_equipments_id')->from('tbl_service_request_item');
        });
        // $query->where('DE.next_due_date','<=',date('Y-m-d', strtotime('last day of current month')));
        if ($cond['search']['due_status'] == '')
        {
//            print_r('byevhv');die;
            $query->where('DE.next_due_date','<=',date('Y-m-t'));
        }
        elseif($cond['search']['due_status'] == "1")
        {
            $query->where('DE.next_due_date','<=',date('Y-m-d', strtotime('last day of previous month')));
        }
        elseif($cond['search']['due_status'] == "2")
        {
            $query->where('DE.next_due_date','>',date('Y-m-d', strtotime('last day of previous month')));
            $query->where('DE.next_due_date','<=',date('Y-m-t'));
        }else{

//            print_r('hi');die;

            $dateRange = explode('/',$cond['search']['due_status']);

            $start = $dateRange[0];
            $end = $dateRange[1];
            $startdate = Carbon::parse($start)->format('Y-m-d');
            $enddate = Carbon::parse($end)->format('Y-m-d');

//            print_r($startdate);
//            print_r($enddate);die;
            $query->whereBetween('DE.next_due_date', [$startdate, $enddate]);
        }


        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
        $query = DB::getQueryLog();
//        print_r($query);
//        die;


        return $result;
    }
 //check asset Number exists r not

    public function getAssetNumber($assetno, $customerID, $ID)
    {
        if ($ID) {
            $results = DB::table('tbl_equipment')->where('id', $ID)->where('customer_id', '=', $customerID)->where('asset_no', '=', $assetno)->first();
            if (!$results) {
                $result = DB::table('tbl_equipment')->where('customer_id', '=', $customerID)->where('asset_no', '=', $assetno)->first();
            } else {
                $result = '';
            }
        } else {
            $result = DB::table('tbl_equipment')->where('customer_id', '=', $customerID)->where('asset_no', '=', $assetno)->first();

        }
        return $result;
    }

    //check Serial Number exists r not

    public function getSerialNumber($serialno, $customerID, $ID)
    {
        if ($ID) {
            $results = DB::table('tbl_equipment')->where('id', $ID)->where('customer_id', '=', $customerID)->where('serial_no', '=', $serialno)->first();
            if (!$results) {
                $result = DB::table('tbl_equipment')->where('customer_id', '=', $customerID)->where('serial_no', '=', $serialno)->first();
            } else {
                $result = '';
            }
        } else {
            $result = DB::table('tbl_equipment')->where('customer_id', '=', $customerID)->where('serial_no', '=', $serialno)->first();
        }
        return $result;
    }


}

    
   





