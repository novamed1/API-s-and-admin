<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class ServiceRequest extends Model
{
    protected $table = 'tbl_service_request';

    public function AllServiceRequest($limit = 0, $offset = 0, $order_by = 'tsr.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_service_request as tsr');

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
                        } else {
                            $like .= " AND " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";

                        }
                        $flag = false;
                    }
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }
        if (isset($cond['requestId']) && $cond['requestId'] != '') {
            $query->where('tsr.id', '=', $cond['requestId']);
        }


        $query->join('tbl_customer as tc', 'tc.id', '=', 'tsr.customer_id');

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

    public function AllServiceRequestGrid($limit = 0, $offset = 0, $order_by = 'tb.brand_id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_service_request as tsr');

//        print_r($cond);die;


//echo '<pre>';print_R();exit;
        if (isset($cond['search']) && $cond['search'] != '') {

            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key=>$value) {
                    //print_r($key);die;
                    if($key == 'tsr.created_date'){
                        if($value != '') {
                            $value = date('Y-m-d', strtotime(str_replace('-', '/', $value)));
                        }
                    }

                        if($value) {
                            if($key!='totalInstruments') {
                                if ($flag) {

                                    $like .= $key . " LIKE '%" . trim($value) . "%' ";
//                       print_r($like);die;
                                } else {
                                    $like .= " AND " . $key . " LIKE '%" . trim($value) . "%' ";
//                       print_r($like);die;

                                }
                            }
                            else
                            {

                            }


                            $flag = false;
                        }


                }

//                    DB::enableQueryLog();
                if($like)
                {
                    if(array_filter($cond['search'])) {
                        $query->whereRaw('(' . $like . ')');
                    }
                }

            }
        }
        if (isset($cond['groupBY']) && $cond['groupBY'] != '') {
            $query->join('tbl_service_request_item as tsri', 'tsri.service_request_id', '=', 'tsr.id');
            $query->groupBy('tsr.id');
        }

        $query->join('tbl_customer as tc', 'tc.id', '=', 'tsr.customer_id');

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        if (isset($cond['equipments']) && $cond['equipments'] != '') {
            $query->havingRaw('totalInstruments > 0');

        }
        $totalInstrumentsSearch = $cond['totalInstruments'];


            $query->where(function ($q) use ($totalInstrumentsSearch) {
                if(!$totalInstrumentsSearch)
                {
                    $q->whereRaw(DB::raw("(SELECT COUNT(ri.id) FROM tbl_service_request_item as ri 
        JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id JOIN tbl_equipment as e ON e.id=d.equipment_id where ri.service_request_id=tsr.id) > 0"));
                }
                else
                {
                    $q->whereRaw(DB::raw("(SELECT COUNT(ri.id) FROM tbl_service_request_item as ri 
        JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id JOIN tbl_equipment as e ON e.id=d.equipment_id where ri.service_request_id=tsr.id) = ".$totalInstrumentsSearch));
                }

            });

        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            if(!isset($cond['count']))
            {
                $query->limit($limit);
                $query->offset($offset);
            }
        }
        if (!$count) {
            $result = $query->get();

        } else {
           // print_r($cond['select']);die;
            $result = $query->count();

            //print_r(DB::getQueryLog());die;
        }
        return $result;
    }

    public function RequestItems($limit = 0, $offset = 0, $order_by = 'tb.brand_id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {

        $query = DB::table('tbl_service_request_item as tsri');

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
                        } else {
                            $like .= " AND " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                        }
                        $flag = false;
                    }
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }
        if (isset($cond['requestId']) && $cond['requestId'] != '') {
            $query->where('tsri.service_request_id', '=', $cond['requestId']);
        }


        $query->join('tbl_service_request as tsr', 'tsr.id', '=', 'tsri.service_request_id');


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

    function serviceItemEquipments($limit = 0, $offset = 0, $cond = array(),$count=false)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_service_request_item as RI');
        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }

        if (isset($cond['search']) && $cond['search'] != '') {

            $likeArrayFields = array('E.asset_no', 'E.serial_no', 'E.location', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'EM.model_name');
            if (!empty($likeArrayFields)) {

                $flag = true;
                $like = '';
                foreach ($likeArrayFields as $value) {
                    if ($flag) {

                        $like .= $value . " LIKE '%" . trim($cond['search']) . "%' ";
                    } else {
                        $like .= " OR " . $value . " LIKE '%" . trim($cond['search']) . "%' ";
                    }
                    $flag = false;
                }
                $query->whereRaw('(' . $like . ')');
            }
        }

        if (isset($cond['request_id']) && $cond['request_id']) {
            $query->where('service_request_id', '=', $cond['request_id']);
        }
        if (isset($cond['planId']) && $cond['planId']) {
            $query->where('RI.test_plan_id', '=', $cond['planId']);
        }
        if (isset($cond['cus_id']) && $cond['cus_id']) {
            $query->where('SR.customer_id', '=', $cond['cus_id']);
        }
        if (isset($cond['status']) && $cond['status'] != '') {
            if ($cond['status'] == 1) {
                $query->where('DE.next_due_date', '<', date('Y-m-d'));
            } else {
                $query->where('DE.next_due_date', '>=', date('Y-m-d'));
            }
        }
        if (isset($cond['startCallDate']) && $cond['startCallDate'] != '') {
            $query->where('DE.last_cal_date', '>=', $cond['startCallDate']);
        }
        if (isset($cond['plangroupBy']) && $cond['plangroupBy'] != '') {
            $query->groupBy('RI.test_plan_id');
        }
        if (isset($cond['endCallDate']) && $cond['endCallDate'] != '') {
            $query->where('DE.last_cal_date', '<=', $cond['endCallDate']);
        }
        if (isset($cond['servicePriceId']) && $cond['servicePriceId'] != '') {
            $query->where('SPR.id', '=', $cond['servicePriceId']);
        }
        if (isset($cond['workorder']) && $cond['workorder'] != '') {
            $query->whereNotIn('RI.id', (function ($query) {
                $query->select('request_item_id')->from('tbl_work_order_items');
            }));
        }
        $query->join('tbl_service_request as SR', 'SR.id', '=', 'RI.service_request_id');
        $query->join('tbl_due_equipments as DE', 'DE.id', '=', 'RI.due_equipments_id');
        $query->join('tbl_equipment as E', 'E.id', '=', 'DE.equipment_id');
        $query->join('tbl_service_plan as SP', 'SP.id', '=', 'RI.test_plan_id');
        $query->join('tbl_frequency as F', 'F.id', '=', 'RI.frequency_id','left');
        $query->join('tbl_service_pricing as SPR', 'SPR.id', '=', 'RI.service_price_id');
        $query->join('tbl_channel_points as CP', 'CP.id', '=', 'SPR.channel_point');
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->select('RI.id as request_item_id', 'RI.service_request_id as service_request_id', 'RI.test_plan_id as test_plan_id','E.name as equipmentName','E.id as equipmentId', 'E.asset_no', 'E.serial_no', 'E.pref_contact', 'E.pref_email', 'E.pref_tel', 'E.location', 'SP.service_plan_name', 'SP.service_plan_type', 'EM.model_description', 'SPR.price', 'RI.comments', 'F.name as frequency_name', 'CP.point_name', 'CP.point_channel', 'DE.next_due_date','DE.next_due_date_up', 'DE.last_cal_date','SP.id as service_plan_id');
        if (!$count) {
            $result = $query->get();
            //print_r(DB::getQueryLog());die;
        } else {
            $result = $query->count();
        }

        return $result;
    }


    public function getserviceRequest($requestId)
    {

        $query = DB::table('tbl_service_request')->where('id', '=', $requestId)->select('*')->first();
        return $query;
    }

    public function requestItemByPlan($planId, $requestId)
    {

        $query = DB::table('tbl_service_request_item')->where([['test_plan_id', '=', $planId], ['service_request_id', '=', $requestId]])->select('*')->get();
        return $query;
    }

    public function saveRequest($input)
    {


        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_service_request')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_service_request')->insertGetId($input);
            return $result;
        }
    }
    public function saveStatus($input)
    {


        if ($input['id']) {
            $result = DB::table('tbl_service_statuses')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_service_statuses')->insertGetId($input);
            return $result;
        }
    }

    public function saveItems($input)
    {


        if ($input['id']) {
            $result = DB::table('tbl_service_request_item')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_service_request_item')->insertGetId($input);
            return $result;
        }
    }
    public function saveReqLog($input)
    {


        if ($input['id']) {
            $result = DB::table('tbl_service_request_items_log')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_service_request_items_log')->insertGetId($input);
            return $result;
        }
    }


    public function checkWorkOrder($servicerequestId)
    {

        $query = DB::table('tbl_work_order')->where('request_id', '=', $servicerequestId)->first();
        return $query;
    }

    public function checkWorkOrderItems($servicerequestItemId)
    {

        $query = DB::table('tbl_work_order_items')->where('request_item_id', '=', $servicerequestItemId)->first();
        return $query;
    }



}