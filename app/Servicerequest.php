<?php

namespace App;

use GuzzleHttp\Psr7\Request;

use DB;
use Carbon\Carbon;
use Input;
use DateTime;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Servicerequest extends Authenticatable
{

    public function equipmentModel($id)
    {
        $query = DB::table('tbl_equipment');
        $query->select('tbl_equipment_model.id','tbl_equipment_model.volume','tbl_equipment_model.channel','tbl_equipment_model.channel_number','tbl_equipment_model.brand_operation');
        $query->join('tbl_equipment_model','tbl_equipment_model.id','=','tbl_equipment.equipment_model_id');
        $query->where('tbl_equipment.id',$id);
        $result = $query->get()->first();
        return $result;
    }

    public function pricing($model,$planId)
    {
        $query = DB::table('tbl_service_pricing');
        $query->select('tbl_service_pricing.*','tbl_channel_numbers.channel_number','tbl_channel_points.point_channel');
        $query->join('tbl_service_plan','tbl_service_plan.id','=','tbl_service_pricing.plan_id');
        $query->where('tbl_service_plan.id',$planId);
        $query->where('tbl_service_pricing.volume',$model->volume);
        $query->where('tbl_service_pricing.channel',$model->channel);
        $query->where('tbl_service_pricing.operation',$model->brand_operation);
        $query->where('tbl_channel_numbers.id',$model->channel_number);
        $query->join('tbl_channel_points','tbl_channel_points.id','=','tbl_service_pricing.channel_point');
        $query->join('tbl_channel_numbers','tbl_channel_numbers.id','=','tbl_channel_points.point_name');
        $result = $query->get();
        return $result;
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

    public function saveDueEqu($input)
    {


        if ($input['id']) {
            $result = DB::table('tbl_due_equipments')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_due_equipments')->insertGetId($input);
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

    public function allServiceRequests($limit = 0, $offset = 0, $cond = array())
    {


        DB::enableQueryLog();

        $query = DB::table('tbl_service_request as SR');
        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }
        if (isset($cond['search']) && $cond['search'] != '') {

            $likeArrayFields = array('E.asset_no','E.serial_no','E.	pref_contact','E.location','EM.model_name');
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
        if($cond['role_id'] == 1)
        {
            if (isset($cond['cus_id']) && $cond['cus_id'] != '') {
                $query->where('SR.customer_id', $cond['cus_id']);
            }
        }
        else
        {
            $query->where('SR.created_by', $cond['user_id']);
        }

        $query->join('tbl_notes as N', 'N.status_id', '=', 'SR.service_customer_status','left');
        $query->join('tbl_users as U', 'U.id', '=', 'SR.created_by','left');
        $query->orderBy('SR.id', 'DESC');
        $query->select('SR.id', 'SR.request_no', 'SR.service_schedule_date', 'SR.service_customer_status', 'SR.created_date', 'N.status_name','U.name','SR.is_accessed');

        $result = $query->get();
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    function totalItems($cond=array())
    {
        $query = DB::table('tbl_service_request_item');
        if(isset($cond['request_id']) && $cond['request_id'])
        {
            $query->where('service_request_id','=',$cond['request_id']);
        }
        $result = $query->count();
        return $result;
    }

    function totalItemsCalibrated($cond=array())
    {
        $query = DB::table('tbl_workorder_status_move as sm');
        $query->join('tbl_work_order_items as oi','oi.id','=','sm.workorder_item_id');
        $query->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id');
        if(isset($cond['request_id']) && $cond['request_id'])
        {
            $query->where('ri.service_request_id','=',$cond['request_id']);
        }
        $query->where('sm.workorder_status','=',5);
        $result = $query->count();
        return $result;
    }

    function serviceItemEquipments($limit = 0, $offset = 0, $cond = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_service_request_item as RI');
        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }

        if (isset($cond['search']) && $cond['search'] != '') {

            $likeArrayFields = array('E.asset_no','E.serial_no','E.location','E.pref_contact','E.pref_tel','E.pref_email','EM.model_name','EM.model_description');
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

        if(isset($cond['request_id']) && $cond['request_id'])
        {
            $query->where('SR.request_no','=',$cond['request_id']);
        }
        if(isset($cond['cus_id']) && $cond['cus_id'])
        {
            $query->where('SR.customer_id','=',$cond['cus_id']);
        }
        if(isset($cond['status']) && $cond['status'] != '')
        {
            if($cond['status']==1)
            {
                $query->where('DE.next_due_date','<', date('Y-m-d'));
            }
            else
            {
                $query->where('DE.next_due_date','>=', date('Y-m-d'));
            }
        }
        if(isset($cond['startCallDate']) && $cond['startCallDate'] != '')
        {
            $query->where('DE.last_cal_date','>=', $cond['startCallDate']);
        }
        if(isset($cond['endCallDate']) && $cond['endCallDate'] != '')
        {
            $query->where('DE.last_cal_date','<=', $cond['endCallDate']);
        }
        if(isset($cond['item_status']) && $cond['item_status'])
        {
             $query->whereIn('RI.is_calibrated', $cond['item_status']);

        }
        if(isset($cond['cal_frequency']) && $cond['cal_frequency'])
        {
              $query->whereIn('DE.frequency_id', $cond['cal_frequency']);
        }

        $query->join('tbl_service_request as SR','SR.id','=','RI.service_request_id');
        $query->join('tbl_due_equipments as DE','DE.id','=','RI.due_equipments_id');
        $query->join('tbl_equipment as E','E.id','=','DE.equipment_id');
        $query->join('tbl_service_plan as SP','SP.id','=','RI.test_plan_id');
        $query->join('tbl_frequency as F','F.id','=','RI.frequency_id','left');
        $query->join('tbl_service_pricing as SPR','SPR.id','=','RI.service_price_id');
        $query->join('tbl_channel_points as CP','CP.id','=','SPR.channel_point');
        $query->join('tbl_channel_numbers as CN','CN.id','=','CP.point_name','left');
        $query->join('tbl_equipment_model as EM','EM.id','=','E.equipment_model_id');
        $query->select('RI.id as request_item_id','E.asset_no','E.serial_no','E.pref_contact','E.pref_email','E.pref_tel','E.location','SP.service_plan_name','EM.model_name','SPR.price','RI.comments','F.name as frequency_name','CP.point_name','CP.point_channel',
            'DE.next_due_date','DE.last_cal_date','RI.is_calibrated','RI.order_status','RI.payment_status','EM.model_description','CN.channel_number');
        $result = $query->get();
        //echo '<pre>';print_r(DB::getQueryLog());die;
        return $result;
    }

    function serviceStatusCounts($cond=array())
    {
         $query = DB::table('tbl_service_request as SR');
         if(isset($cond['cus_id']) && $cond['cus_id'] != '')
         {
             $query->where('SR.customer_id','=',$cond['cus_id']);
         }
        if($cond['role_id'] == 1)
        {
            if (isset($cond['cus_id']) && $cond['cus_id'] != '') {
                $query->where('SR.customer_id', $cond['cus_id']);
            }
        }
        else
        {
            $query->where('SR.created_by', $cond['user_id']);
        }
         $result = $query->count();
         return $result;

    }

    function as_found_history($id,$date)
    {
        $query = DB::table('tbl_workorder_asfound_log as fl');
        $query->join('tbl_workorder_status_move as sm','sm.id','=','fl.workorder_status_id');
        $query->join('tbl_work_order_items as oi','oi.id','=','sm.workorder_item_id');
        $query->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id');
        $query->join('tbl_service_request as sr','sr.id','=','ri.service_request_id');
        $query->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id');
        $query->join('tbl_equipment as e','e.id','=','de.equipment_id');
        $query->select('fl.*');
        $query->where('e.id',$id);
        $query->where('fl.cali_date',$date);
        $result = $query->get();
        return $result;

    }

    function as_calibrated_history($id,$date)
    {
        $query = DB::table('tbl_workorder_ascalibrated_log as cl');
        $query->join('tbl_workorder_status_move as sm','sm.id','=','cl.workorder_status_id');
        $query->join('tbl_work_order_items as oi','oi.id','=','sm.workorder_item_id');
        $query->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id');
        $query->join('tbl_service_request as sr','sr.id','=','ri.service_request_id');
        $query->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id');
        $query->join('tbl_equipment as e','e.id','=','de.equipment_id');
        $query->select('cl.*');
        $query->where('e.id',$id);
        $query->where('cl.cali_date',$date);
        $result = $query->get();
        return $result;

    }

    function as_maintenance_history($id,$date)
    {
        $query = DB::table('tbl_workorder_maintenance_log as ml');
        $query->join('tbl_workorder_status_move as sm','sm.id','=','ml.workorder_status_id');
        $query->join('tbl_work_order_items as oi','oi.id','=','sm.workorder_item_id');
        $query->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id');
        $query->join('tbl_service_request as sr','sr.id','=','ri.service_request_id');
        $query->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id');
        $query->join('tbl_equipment as e','e.id','=','de.equipment_id');
        $query->select('ml.*');
        $query->where('e.id',$id);
        $query->where('ml.cali_date',$date);
        $result = $query->get();
        return $result;

    }

    function historyRequestItem($id,$date)
    {
        $query = DB::table('tbl_service_request_item as ri');
        $query->join('tbl_service_request as r','r.id','=','ri.service_request_id');
        $query->join('tbl_service_plan as sp','sp.id','=','ri.test_plan_id');
        $query->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id');
        $query->join('tbl_equipment as e','e.id','=','de.equipment_id');
        $query->select('sp.*');
        $query->where('e.id',$id);
        $query->where('r.service_schedule_date',$date);
        $result = $query->first();
        return $result;

    }

    function workorder_status($work_order_item_id,$status)
    {
        $query = DB::table('tbl_workorder_status_move as sm');
        $query->select('sm.*');
        $query->where('sm.workorder_item_id',$work_order_item_id);
        $query->where('sm.workorder_status',$status);
        $result = $query->first();
        return $result;
    }

    function workorder_asfound_log($work_order_item_id)
    {
        $query = DB::table('tbl_workorder_asfound_log as f');
        $query->join('tbl_workorder_status_move as sm','sm.id','=','f.workorder_status_id');
        $query->select('f.*');
        $query->where('sm.workorder_item_id',$work_order_item_id);
        $result = $query->get();
        return $result;
    }

    function workorder_calibrated_log($work_order_item_id)
    {
        $query = DB::table('tbl_workorder_ascalibrated_log as c');
        $query->join('tbl_workorder_status_move as sm','sm.id','=','c.workorder_status_id');
        $query->select('c.*');
        $query->where('sm.workorder_item_id',$work_order_item_id);
        $result = $query->get();
        return $result;
    }

    public function save_workorder_items($input)
    {


        if ($input['id']) {
            $result = DB::table('tbl_work_order_items')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_work_order_items')->insertGetId($input);
            return $result;
        }
    }

    function serviceWorkorderCounts($cond=array())
    {
        $query = DB::table('tbl_work_order as W');
        $query->join('tbl_service_request as SR','SR.id','=','W.request_id');
        if(isset($cond['cus_id']) && $cond['cus_id'] != '')
        {
            $query->where('SR.customer_id','=',$cond['cus_id']);
        }
        if($cond['role_id'] == 1)
        {
            if (isset($cond['cus_id']) && $cond['cus_id'] != '') {
                $query->where('SR.customer_id', $cond['cus_id']);
            }
        }
        else
        {
            $query->where('SR.created_by', $cond['user_id']);
        }
        if(isset($cond['status']) && $cond['status'] != '')
        {
                $query->where('W.work_progress','=',$cond['status']);
        }
        $query->select('W.id');
        $query->groupby('W.request_id');
        $result = $query->get();
        return $result;

    }

    public  function getServiceRequest($id)
    {
        $query = DB::table('tbl_service_request');
        $query->where('id',$id);
        $result = $query->first();
        return $result;

    }

}
