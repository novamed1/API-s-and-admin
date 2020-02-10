<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Workorder extends Model
{
    protected $table = 'tbl_work_order as W';
    public function assignedworkorders($limit = 0, $offset = 0,$cond = array(),  $direction = 'DESC',$order_by = 'W.id',  $count = false, $likeArray = array('W.work_order_number','C.customer_name'), $dateField = '', $filter = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order as W');
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }

        if (isset($cond['search']['keyword']) && $cond['search']['keyword'] != '') {

            if (isset($likeArray) && $likeArray != '') {
                $likeArrayFields = $likeArray;
            } else {
                $likeArrayFields = array('W.id');
            }
            if (isset($cond['search']['keyword']) && $cond['search']['keyword']!= '') {

                if (!empty($likeArray)) {

                    $flag = true;
                    $like = '';
                    $keyword = (string)$cond['search']['keyword'];
                    foreach ($likeArray as $value) {
                        if ($flag) {

                            $like .= $value . " LIKE '%" . $keyword . "%' ";
                        } else {
                            $like .= " OR " . $value . " LIKE '%" . $keyword . "%' ";

                        }
                        $flag = false;
                    } //print_r($like);die;


                    $query->whereRaw('(' . $like . ')');
                }
            }

            if ((isset($cond['search']['start']) && $cond['search']['start'] != '') || isset($cond['search']['end']) && $cond['search']['end']) {
                if ($cond['search']['start'] != '' && $cond['search']['end'] != '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('W.workorder_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] != '' && $cond['search']['end'] == '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = date('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('W.workorder_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] == '' && $cond['search']['end'] != '') {
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $dateend = $enddate . ' 23:59:59';
                    $query->where('W.workorder_date', '<', $dateend);
                } else {

                }
            }

        }


        if (isset($cond['maintananceTo']) && $cond['maintananceTo'] != '') {
            $query->where('W.maintanence_to', '=', $cond['maintananceTo']);
        }
        if (isset($cond['calibrateTo']) && $cond['calibrateTo'] != '') {
            $query->where('W.calibration_to', '=', $cond['calibrateTo']);
        }
        if (isset($cond['status']) && $cond['status'] != '') {
            $query->where('W.status', '=', $cond['status']);
        }
        if (isset($cond['service_plan']) && $cond['service_plan'] != '') {
            $query->groupBy('W.plan_id');
        }
        if (isset($cond['workOrderId']) && $cond['workOrderId'] != '') {
            $query->where('W.id', '=', $cond['workOrderId']);
        }
        if (isset($cond['service_plan']) && $cond['service_plan'] != '') {
            $query->groupBy('W.plan_id');
        }
        if (isset($cond['tid']) && $cond['tid'] != '') {
            $tid = $cond['tid'];
            $query->where(function($q) use ($tid) {
                $q->where(DB::raw("W.maintanence_to"), '=', $tid)
                    ->orWhere('W.calibration_to','=', $tid);
            });
        }
        if (isset($cond['week_or_month']) && $cond['week_or_month'] != '') {
            if($cond['week_or_month']==2)
            {
               //$query->where(DB::raw('DATE_FORMAT(workorder_date, "%m-%Y")'),'=',date("m-Y"));
            }
            else
            {
                $query->where('workorder_date', '>', Carbon::now()->startOfWeek());
                $query->where('workorder_date', '<', Carbon::now()->endOfWeek());
            }
        }
        if (isset($cond['work_progress']) && $cond['work_progress'] != '') {
            $query->where('W.work_progress', '=', $cond['work_progress']);
        }
        if (isset($cond['assigned_workorders']) && $cond['assigned_workorders'] != '') {
            $query->where('W.work_progress', '!=', 3);
        }
        if (isset($cond['report_status']) && $cond['report_status'] != '') {
            $report_status = explode(',',$cond['report_status']);
            $query->whereIn('W.status', $report_status);
        }
        $query->join('tbl_service_plan as S','S.id','=','W.plan_id');
        $query->join('tbl_service_request as SR','SR.id','=','W.request_id');
        $query->join('tbl_customer as C','C.id','=','SR.customer_id');
        $query->join('tbl_customer_contacts as cc','cc.customer_id','=','C.id','left');
        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        if (!$count) {
            $result = $query->get();
//            $query = DB::getQueryLog();
//            print_r($query);die;
        } else {
            $result = $query->count();
        }

        return $result;
    }


    public function assignedworkordersGrid($limit = 0, $offset = 0,$order_by = 'W.id',  $direction = 'DESC',$cond = array(),  $count = false, $likeArray = array('W.work_order_number','C.customer_name'), $dateField = '', $filter = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order as W');

        if (isset($cond['search']) && $cond['search'] != '') {


            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key => $value) {
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
                if($like)
                {
                    if(array_filter($cond['search'])) {
                        $query->whereRaw('(' . $like . ')');
                    }
                }
            }
        }


        $query->join('tbl_service_plan as S','S.id','=','W.plan_id');
        $query->join('tbl_service_request as SR','SR.id','=','W.request_id');
        $query->join('tbl_customer as C','C.id','=','SR.customer_id');
        $query->join('tbl_customer_contacts as cc','cc.customer_id','=','C.id','left');
        $query->join('tbl_technician as mTech','mTech.id','=','W.maintanence_to','left');
        $query->join('tbl_technician as cTech','cTech.id','=','W.calibration_to','left');

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        $totalInstrumentsSearch = $cond['totalInstruments'];


        $query->where(function ($q) use ($totalInstrumentsSearch) {
            if(!$totalInstrumentsSearch)
            {
                $q->whereRaw(DB::raw("(SELECT COUNT(oi.id) FROM tbl_work_order_items as oi
            JOIN tbl_service_request_item as ri ON ri.id=oi.request_item_id
            JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id 
            JOIN tbl_equipment as e ON e.id=d.equipment_id where oi.work_order_id=W.id) > 0"));
            }
            else
            {
                $q->whereRaw(DB::raw("(SELECT COUNT(oi.id) FROM tbl_work_order_items as oi
            JOIN tbl_service_request_item as ri ON ri.id=oi.request_item_id
            JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id 
            JOIN tbl_equipment as e ON e.id=d.equipment_id where oi.work_order_id=W.id) =".$totalInstrumentsSearch));
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
//                      $query = DB::getQueryLog();
//        print_r($query);
//        die;
        } else {
            $result = $query->count();
        }

        return $result;
    }


    public function CalibrationDetails($limit = 0, $offset = 0, $cond = array(), $direction = 'DESC', $order_by = 'twoi.id', $count = false, $likeArray = array('twoi.work_order_id'), $dateField = '', $filter = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order_items as twoi');
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }

        $query->join('tbl_service_request_item as ri', 'ri.id', '=', 'twoi.request_item_id');
        $query->join('tbl_due_equipments as de', 'de.id', '=', 'ri.due_equipments_id');
        //$query->join('tbl_work_order as two', 'two.id', '=', 'twoi.work_order_id');
        $query->join('tbl_users as tt', 'tt.id', '=', 'twoi.technician_review','left');
        $query->join('tbl_equipment as e', 'e.id', '=', 'de.equipment_id');
        $query->leftjoin('tbl_frequency as f', 'f.id', '=', 'de.frequency_id');
        $query->leftjoin('tbl_equipment_model as tem', 'tem.id', '=', 'e.equipment_model_id');
        //$query->join('tbl_service_plan as S', 'S.id', '=', 'two.plan_id');

//        $query->join('tbl_service_request as SR', 'SR.id', '=', 'two.request_id');
//        $query->join('tbl_customer as C', 'C.id', '=', 'SR.customer_id');
//        $query->join('tbl_customer_contacts as cc', 'cc.customer_id', '=', 'C.id');


        if (isset($cond['search']) && $cond['search'] != '') {
//            print_r($cond['search']);die;
//            $likeArray = array('W.work_order_number', 'C.customer_name');
            if (!empty($likeArray)) {

                $flag = true;
                $like = '';
                foreach ($likeArray as $value) {
                    if ($flag) {

                        $like .= $value . " LIKE '%" . trim($cond['search']) . "%' ";
//                       print_r($like);die;
                    } else {
                        $like .= " AND " . $value . " LIKE '%" . trim($cond['search']) . "%' ";
//                       print_r($like);die;

                    }
                    $flag = false;
                }
//                    DB::enableQueryLog();
                $query->whereRaw('(' . $like . ')');
            }
        }

        if (isset($cond['groupBy']) && $cond['groupBy'] != '') {
            $query->groupBy('two.id');
        }
        if (isset($cond['status']) && $cond['status'] != '') {
            $query->where('two.work_progress', '=', $cond['status']);
        }
        if (isset($cond['workOrderId']) && $cond['workOrderId'] != '') {
            $query->where('twoi.work_order_id', '=', $cond['workOrderId']);
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        if (!$count) {
            $result = $query->get();
            //print_r(DB::getQueryLog());die;
        } else {
            $result = $query->count();
        }


//        $query = DB::getQueryLog();
//        print_r($query);die;

        return $result;
    }

    public function CalibrationDetailsGrid($limit = 0, $offset = 0, $order_by = 'two.id',$direction = 'DESC', $cond = array(), $count = false)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order as wo');
        $query->leftjoin('tbl_technician as M', 'M.id', '=', 'wo.maintanence_to');
        $query->leftjoin('tbl_technician as CA', 'CA.id', '=', 'wo.maintanence_to');
        $query->join('tbl_service_plan as S', 'S.id', '=', 'wo.plan_id');

        $query->join('tbl_service_request as SR', 'SR.id', '=', 'wo.request_id');
        $query->join('tbl_customer as C', 'C.id', '=', 'SR.customer_id');
        $query->join('tbl_customer_contacts as cc', 'cc.customer_id', '=', 'C.id','left');


        if (isset($cond['search']) && $cond['search'] != '') {

            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key=>$value) {
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

        if (isset($cond['status']) && $cond['status'] != '') {
            $query->where('two.work_progress', '=', $cond['status']);
        }
        if (isset($cond['workOrderId']) && $cond['workOrderId'] != '') {
            $query->where('two.id', '=', $cond['workOrderId']);
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        $totalInstrumentsSearch = $cond['totalInstruments'];


        $query->where(function ($q) use ($totalInstrumentsSearch) {
            if(!$totalInstrumentsSearch)
            {
                $q->whereRaw(DB::raw("(SELECT COUNT(oi.id) FROM tbl_work_order_items as oi
            JOIN tbl_service_request_item as ri ON ri.id=oi.request_item_id
            JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id 
            JOIN tbl_equipment as e ON e.id=d.equipment_id where oi.work_order_id=wo.id) > 0"));
            }
            else
            {
                $q->whereRaw(DB::raw("(SELECT COUNT(oi.id) FROM tbl_work_order_items as oi
            JOIN tbl_service_request_item as ri ON ri.id=oi.request_item_id
            JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id 
            JOIN tbl_equipment as e ON e.id=d.equipment_id where oi.work_order_id=wo.id) =".$totalInstrumentsSearch));
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
            //print_r(DB::getQueryLog());die;
        } else {
            $result = $query->count();
        }


//        $query = DB::getQueryLog();
//        print_r($query);die;

        return $result;
    }


    function totalInstruments($id)
    {
        $query = DB::table('tbl_work_order_items');
        $query->where('work_order_id','=',$id);
        $result = $query->count();
        return $result;
    }

    function totalInstrumentsWorkOrderProcess($id,$workorder_status)
    {
        $query = DB::table('tbl_workorder_status_move as M');
        $query->join('tbl_work_order_items as I','I.id','=','M.workorder_item_id');
        $query->where('I.work_order_id','=',$id);
        $query->where('M.workorder_status','=',$workorder_status);
        $result = $query->count();
        return $result;
    }

    function workordercounts($cond)
    {
        $query = DB::table('tbl_work_order as W');
        if (isset($cond['search']) && $cond['search'] != '') {
            $likeArray = array('W.work_order_number','C.customer_name');
            if (!empty($likeArray)) {

                $flag = true;
                $like = '';
                foreach ($likeArray as $value) {
                    if ($flag) {

                        $like .= $value . " LIKE '%" . trim($cond['search']) . "%' ";
//                       print_r($like);die;
                    } else {
                        $like .= " OR " . $value . " LIKE '%" . trim($cond['search']) . "%' ";
//                       print_r($like);die;

                    }
                    $flag = false;
                }
//                    DB::enableQueryLog();
                $query->whereRaw('(' . $like . ')');
            }
        }
        if (isset($cond['tid']) && $cond['tid'] != '') {
            $tid = $cond['tid'];
            $query->where(function($q) use ($tid) {
                $q->where(DB::raw("W.maintanence_to"), '=', $tid)
                    ->orWhere('W.calibration_to','=', $tid);
            });
        }
        if(isset($cond['weekly']) && $cond['weekly'] != '')
        {
            $query->where('workorder_date', '>', Carbon::now()->startOfWeek());
            $query->where('workorder_date', '<', Carbon::now()->endOfWeek());
        }
        if(isset($cond['monthly']) && $cond['monthly'] != '')
        {
            $query->where(DB::raw('DATE_FORMAT(workorder_date, "%m-%Y")'),'=',date("m-Y"));
        }
        if(isset($cond['as_found']) && $cond['as_found'] != '')
        {
            $query->where('W.status', '=', 1);
        }
        if(isset($cond['maintenance']) && $cond['maintenance'] != '')
        {
            $query->where('W.status', '=', 2);
        }
        if(isset($cond['calibrated']) && $cond['calibrated'] != '')
        {
            $query->where('W.status', '=', 3);
        }
        if(isset($cond['dispatched']) && $cond['dispatched'] != '')
        {
            $query->where('W.status', '=', 4);
        }
        if(isset($cond['pending']) && $cond['pending'])
        {
            $query->where('W.work_progress', '!=', 3);
        }
        $query->join('tbl_service_plan as S','S.id','=','W.plan_id');
        $query->join('tbl_service_request as SR','SR.id','=','W.request_id');
        $query->join('tbl_customer as C','C.id','=','SR.customer_id');
        $query->join('tbl_customer_contacts as cc','cc.customer_id','=','C.id','left');
        $result = $query->count();
        return $result;
    }

    public function saveWorkOrder($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $userId;
            $result = DB::table('tbl_work_order')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;

            $result = DB::table('tbl_work_order')->insertGetId($input);
            return $result;
        }
    }

    public function updateWorkorder($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_work_order')->where('id', $input['id'])->update($input);
            return $input['id'];
        }
    }

    public function saveWorkOrderItem($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = 1;
            $result = DB::table('tbl_work_order_items')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;

            $result = DB::table('tbl_work_order_items')->insertGetId($input);
            return $result;
        }
    }


    public function checkWorkOrders($requestId, $planId, $requestItemId)
    {
        $query = DB::table('tbl_work_order as two')
            ->join('tbl_work_order_items as twos', 'twos.work_order_id', '=', 'two.id')
            ->where([['two.request_id', '=', $requestId], ['two.plan_id', '=', $planId], ['twos.request_item_id', '=', $requestItemId]])->select('two.*')->first();
        return $query;
    }

    public function getWorkOrder($planId, $requestId)
    {

        $query = DB::table('tbl_work_order')->where([['request_id', '=', $requestId], ['plan_id', '=', $planId]])->select('*')->get();
        return $query;
    }
    public function getParticularWorkOrder($workOrderId)
    {
        $query = DB::table('tbl_work_order')->where('id', '=', $workOrderId)->select('*')->first();
        return $query;
    }

    function workorderitemdetails($item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order_items as oi')
            ->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id')
            ->join('tbl_equipment as e','e.id','=','de.equipment_id')
            ->join('tbl_frequency as f','f.id','=','de.frequency_id','left')
            ->join('tbl_equipment_model as em','em.id','=','e.equipment_model_id')
            ->join('tbl_operations as o','o.id','=','em.brand_operation')
            ->join('tbl_channel_numbers as cn','cn.id','=','em.channel_number')
            ->join('tbl_channels as c','c.id','=','cn.channel_id')
            ->where('oi.id',$item_id)
            ->select('e.id','cn.id as channel_id','cn.channel_number','c.channel_name','o.operation_name','ri.comments','e.asset_no','e.serial_no','oi.id as work_order_items_id','de.last_cal_date','f.name','ri.test_plan_id','em.brand_operation','em.volume','em.channel_number as channel_number_id','em.id as emodel_id','em.volume_value')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function channelpoints($id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_channel_points as cp')

            ->where('cp.point_channel',$id)
            ->select('e.id','cn.id as channel_id')->count();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function checklist($status_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_checklist')

            ->where('status_id',$status_id)
            ->select('id','title')->get();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function checklistItem($checklist_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_checklist_item')

            ->where('checklist_id',$checklist_id)
            ->where('parent_id',0)
            ->select('id','title','type','parent_id','checklist_id','add_parts')->get();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function checklistItemSub($parent_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_checklist_item')

            ->where('parent_id',$parent_id)
            ->select('id','title','type','parent_id','checklist_id','add_parts')->get();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function checkProcessUpdation($work_order_item_id,$status)
    {

        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_status_move')
            ->where('workorder_item_id',$work_order_item_id)
            ->where('workorder_status',$status)
            ->select('id','workorder_item_id')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function service_plan($id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_service_plan')
            ->join('tbl_samples as s','s.id','=','tbl_service_plan.as_found_readings')
            ->where('tbl_service_plan.id',$id)
           ->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function service_plan_calibrated($id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_service_plan')
            ->join('tbl_samples as s','s.id','=','tbl_service_plan.as_calibrate_readings')
            ->where('tbl_service_plan.id',$id)
            ->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function servicePlanPricing($service_plan_id,$operation_id,$volume_id,$channel_number_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_service_pricing as s')
            ->join('tbl_channel_points as c','c.id','=','s.channel_point')
            ->where([
                ['s.plan_id', '=', $service_plan_id],
                ['s.volume', '=', $volume_id],
                ['s.operation', '=', $operation_id],
                ['s.channel_number', '=', $channel_number_id],
                ])
            ->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

  function workOrderItems($work_order_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order_items as oi')
            ->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id')
            ->join('tbl_equipment as e','e.id','=','de.equipment_id')
            ->join('tbl_frequency as f','f.id','=','de.frequency_id','left')
            ->join('tbl_equipment_model as em','em.id','=','e.equipment_model_id')
            ->join('tbl_service_plan as sp','sp.id','=','ri.test_plan_id')

            ->where('oi.work_order_id',$work_order_id)
//            ->where('oi.modified_by','=',null)
            ->select('e.id','ri.comments','e.asset_no','e.serial_no','oi.id as work_order_items_id','de.last_cal_date','f.name','ri.test_plan_id','em.brand_operation','em.volume','em.channel_number as channel_number_id','sp.service_plan_name','e.name as equipmentName','e.id as equipmentId','oi.request_item_id','em.model_description','e.pref_contact', 'e.pref_email', 'e.pref_tel', 'e.location','oi.as_calibrated_status')->get();
//        $query = DB::getQueryLog();
//        echo '<pre>';
//        print_r($query); die;
        return $query;
    }

    function getTolerances($model_id)
    {
        $query = DB::table('tbl_limit_tolerance');
        $query->where('model_id',$model_id);
        $query->select('target_value','accuracy','precision');
        $result =  $query->get();
        return $result;
    }

    function workorder($id)
    {

        DB::enableQueryLog();
        $query = DB::table('tbl_work_order')
            ->where('id',$id)
            ->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    public function saveWorkOrderStatus($input)
    {

        if ($input['id']) {
            $result = DB::table('tbl_work_order')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {

            $result = DB::table('tbl_work_order')->insertGetId($input);
            return $result;
        }
    }

    function workorderItemsEquipment($item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order_items as oi')
            ->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id')
            ->join('tbl_equipment as e','e.id','=','de.equipment_id')
            ->where('oi.id',$item_id)
            ->select('e.id','de.id as dId')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }


    function workorderItemsServiceRequest($id,$status=false)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_status_move as Sm');
            $query->join('tbl_work_order_items as oi','oi.id','=','Sm.workorder_item_id');
            $query->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id');
            $query->join('tbl_service_request as SR','SR.id','=','ri.service_request_id');
            $query->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id');
            $query->join('tbl_equipment as e','e.id','=','de.equipment_id');
            $query->where('e.id',$id);
            if($status)
            {
                $query->where('Sm.workorder_status',$status);
            }

            $query->orderby('SR.id','DESC');
           $result= $query->select('SR.*')->get();
        //print_r(DB::getQueryLog()); die;
        return $result;
    }

    function get_technician_model_device($technician_id,$device_model_id)
    {

        $query = DB::table('tbl_technician_fordevice as td')
            ->join('tbl_device as d','d.id','=','td.device_id')
            ->join('tbl_device_model as dm','dm.id','=','d.device_model_id')
            ->join('tbl_units as u','u.id','=','d.unit_id','left')
            ->join('tbl_sensitivity as s','s.id','=','d.sensitivity_id','left')
            ->where([['td.technician_id','=',$technician_id],['dm.id','=',$device_model_id]])
            ->select('dm.name as model_name','d.serial_no','s.name as sensitivity','u.unit','d.id as device_id')->get();
        return $query;

    }

    public function getdevice($Id)
    {

        $result = DB::table('tbl_device as d')
            ->join('tbl_sensitivity as s','s.id','=','d.sensitivity_id','left')
            ->join('tbl_units as u','u.id','=','d.unit_id','left')
            ->where('d.id', '=', $Id)
            ->select('d.*','s.name as sensitivity_name','u.unit')->first();
        return $result;
    }

    function get_used_device($request_item_id,$workorder_status,$device_id,$device_model_name,$select_device_model_name)
    {
        DB::enableQueryLog();
        $result = DB::table('tbl_workorder_status_move')
            ->where([['workorder_item_id', '=', $request_item_id],['workorder_status', '=', $workorder_status],[$device_model_name, '=', $device_id]])
            ->select($select_device_model_name)->first();
        //print_r(DB::getQueryLog()); die;
        return $result;
    }

    function get_used_device_by_workorder($work_order_id,$device_id,$device_model_name,$select_device_model_name)
    {
        DB::enableQueryLog();
        $result = DB::table('tbl_work_order')
            ->where([['id', '=', $work_order_id],[$device_model_name, '=', $device_id]])
            ->select($select_device_model_name)->first();
        //print_r(DB::getQueryLog()); die;
        return $result;
    }

    public function saveWorkOrderComplete($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_work_order')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_work_order')->insertGetId($input);
            return $result;
        }
    }

    function getBalanceAsFound($work_order_item_id,$status)
    {
       $query = DB::table('tbl_workorder_status_move');
       $query->where('workorder_item_id',$work_order_item_id);
       $query->where('workorder_status',$status);
       $query->select('balance_device_id');
       $result = $query->get()->first();
       return $result;
    }

    function totalInstrumentsCalibrated($id)
    {
        $query = DB::table('tbl_work_order_items');
        $query->where([['work_order_id','=',$id],['as_calibrated_status','=',"completed"]]);
        $result = $query->count();
        return $result;
    }
    public function deleteWorkorder($id)
    {

        $delete = DB::table('tbl_work_order')->where('id', $id)->delete();

        if ($delete == 1) {
            return true;

        } else {
            return false;
        }

    }
    public function deleteWorkorderItems($id)
    {

        $delete = DB::table('tbl_work_order_items')->where('work_order_id', $id)->delete();

        if ($delete == 1) {
            return true;

        } else {
            return false;
        }

    }

    public function removeWorkorderItem($id)
    {

        $delete = DB::table('tbl_work_order_items')->where('id', $id)->delete();

        if ($delete == 1) {
            return true;

        } else {
            return false;
        }

    }

    public function CalibrationDetailsItems($limit = 0, $offset = 0, $cond = array(), $direction = 'DESC', $order_by = 'twoi.id', $count = false, $likeArray = array('twoi.work_order_id'), $dateField = '', $filter = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_ascalibrated_log as cl');
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }

        $query->join('tbl_workorder_status_move as sm','sm.id','=','cl.workorder_status_id');
        $query->join('tbl_work_order_items as twoi','twoi.id','=','sm.workorder_item_id');
        $query->join('tbl_work_order as wo','wo.id','=','twoi.work_order_id');
        $query->join('tbl_service_request_item as ri','ri.id','=','twoi.request_item_id');
        $query->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id');
        $query->join('tbl_equipment as e','e.id','=','de.equipment_id');
        $query->leftjoin('tbl_frequency as f', 'f.id', '=', 'de.frequency_id');
        $query->leftjoin('tbl_equipment_model as tem', 'tem.id', '=', 'e.equipment_model_id');
        //$query->join('tbl_service_plan as S', 'S.id', '=', 'two.plan_id');

//        $query->join('tbl_service_request as SR', 'SR.id', '=', 'two.request_id');
//        $query->join('tbl_customer as C', 'C.id', '=', 'SR.customer_id');
//        $query->join('tbl_customer_contacts as cc', 'cc.customer_id', '=', 'C.id');


        if (isset($cond['search']) && $cond['search'] != '') {
//            print_r($cond['search']);die;
//            $likeArray = array('W.work_order_number', 'C.customer_name');
            if (!empty($likeArray)) {

                $flag = true;
                $like = '';
                foreach ($likeArray as $value) {
                    if ($flag) {

                        $like .= $value . " LIKE '%" . trim($cond['search']) . "%' ";
//                       print_r($like);die;
                    } else {
                        $like .= " AND " . $value . " LIKE '%" . trim($cond['search']) . "%' ";
//                       print_r($like);die;

                    }
                    $flag = false;
                }
//                    DB::enableQueryLog();
                $query->whereRaw('(' . $like . ')');
            }
        }

        if (isset($cond['groupBy']) && $cond['groupBy'] != '') {
            $query->groupBy('twoi.id');
        }
        if (isset($cond['status']) && $cond['status'] != '') {
            $query->where('sm.workorder_status', '=', 3);
        }
        if (isset($cond['workOrderId']) && $cond['workOrderId'] != '') {
            $query->where('twoi.work_order_id', '=', $cond['workOrderId']);
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        if (!$count) {
            $result = $query->get();
            //print_r(DB::getQueryLog());die;
        } else {
            $result = $query->count();
        }


//        $query = DB::getQueryLog();
//        print_r($query);die;

        return $result;
    }

    function totalWorkoders($tid,$calibrated=false)
    {
        $query = DB::table('tbl_work_order as W');
       $query->where('calibration_to',$tid);
       if($calibrated)
       {
           $query->where('status',5);
       }
        $result = $query->count();
        return $result;
    }

}

    
   




