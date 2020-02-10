<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use DB;
use Carbon\Carbon;

class Workorderprocessupdate extends Authenticatable
{
    use Notifiable,EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function save_workorder_status_updation($input,$user_id)
    {
        $query = DB::table('tbl_workorder_status_move')->where([['workorder_item_id', $input['workorder_item_id']],
            ['workorder_status',$input['workorder_status']]])->first();
        //echo '<pre>';print_r($query);die;
        $id = (isset($query->id) && $query->id)?$query->id:'';
        if ($id) {
            $input['id'] = $id;
            $input['modified_on'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $user_id;
            $result = DB::table('tbl_workorder_status_move')->where('id', $id)->update($input);
            return $id;
        } else {
            $input['created_on'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $user_id;

            $result = DB::table('tbl_workorder_status_move')->insertGetId($input);
            return $result;
        }
    }

    public function save_maintenance_log($input,$user_id)
    {

        if ($input['id']) {
            $input['modified_on'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $user_id;
            $result = DB::table('tbl_workorder_maintenance_log')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_on'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $user_id;

            $result = DB::table('tbl_workorder_maintenance_log')->insertGetId($input);
            return $result;
        }
    }

    public function save_asfound_log($input,$user_id)
    {

        if ($input['id']) {
            $input['modified_on'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $user_id;
            $result = DB::table('tbl_workorder_asfound_log')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_on'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $user_id;

            $result = DB::table('tbl_workorder_asfound_log')->insertGetId($input);
            return $result;
        }
    }

    public function save_ascalibrated_log($input,$user_id)
    {

        if ($input['id']) {
            $input['modified_on'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $user_id;
            $result = DB::table('tbl_workorder_ascalibrated_log')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_on'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $user_id;

            $result = DB::table('tbl_workorder_ascalibrated_log')->insertGetId($input);
            return $result;
        }
    }

    public function save_asdespatch_log($input,$user_id)
    {

        if ($input['id']) {
            $input['modified_on'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $user_id;
            $result = DB::table('tbl_workorder_despatch_log')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_on'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $user_id;

            $result = DB::table('tbl_workorder_despatch_log')->insertGetId($input);
            return $result;
        }
    }

    public function save_request_item($input)
    {

        if ($input['id']) {
              $result = DB::table('tbl_service_request_item')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {

            $result = DB::table('tbl_service_request_item')->insertGetId($input);
            return $result;
        }
    }

    function getWorkOrderProcess($work_order_item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_status_move')
            ->where('workorder_item_id',$work_order_item_id)->where('workorder_status',2)->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkOrderProcessStatus($work_order_item_id,$status)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_status_move')
            ->where('workorder_item_id',$work_order_item_id)->where('workorder_status','=',$status)->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkOrderMaintenanceOperation($work_order_item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_maintenance_log as ml')
            ->join('tbl_workorder_status_move as sm','sm.id','=','ml.workorder_status_id')
            ->where('sm.workorder_item_id',$work_order_item_id)->orderBy('ml.id','DESC')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getSpare($spare_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_equipment_model_spares')
            ->where('id',$spare_id)->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkOrderAsFoundOperation($work_order_item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_asfound_log as fl')
            ->join('tbl_workorder_status_move as sm','sm.id','=','fl.workorder_status_id')
            ->where('sm.workorder_item_id',$work_order_item_id)->orderBy('fl.id','ASC')
            ->select('fl.*')
            ->get();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkOrderAsCalibratedOperation($work_order_item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_ascalibrated_log as fl')
            ->join('tbl_workorder_status_move as sm','sm.id','=','fl.workorder_status_id')
            ->where('sm.workorder_item_id',$work_order_item_id)->orderBy('fl.id','ASC')
            ->select('fl.*')
            ->get();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkOrderAsFoundOperationChannel($work_order_item_id,$channel,$test_point_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_asfound_log as fl')
            ->join('tbl_workorder_status_move as sm','sm.id','=','fl.workorder_status_id')
            ->where('sm.workorder_item_id',$work_order_item_id)
            ->where('reading_channel',$channel)
            ->where('test_point_id',$test_point_id)
            ->orderBy('fl.id','ASC')
            ->select('fl.*')
            ->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkOrderAsCalibratedOperationChannel($work_order_item_id,$channel,$test_point_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_ascalibrated_log as fl')
            ->join('tbl_workorder_status_move as sm','sm.id','=','fl.workorder_status_id')
            ->where('sm.workorder_item_id',$work_order_item_id)
            ->where('reading_channel',$channel)
            ->where('test_point_id',$test_point_id)
            ->orderBy('fl.id','ASC')
            ->select('fl.*')
            ->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkorderStatus($workorder_item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_status_move')
            ->where([['workorder_item_id',$workorder_item_id],['workorder_status',1]])->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkorderStatusCalibrated($workorder_item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_workorder_status_move')
            ->where([['workorder_item_id',$workorder_item_id],['workorder_status',3]])->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkorder($workorder_item_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order_items')
            ->join('tbl_work_order','tbl_work_order.id','=','tbl_work_order_items.work_order_id')
            ->select('tbl_work_order_items.*','tbl_work_order.*','tbl_work_order.id as work_order_id')
            ->where([['tbl_work_order_items.id',$workorder_item_id]])->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    public function saveWorkOrder($input)
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

    function getWorkOrderItems($workorder_id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_work_order_items')
            ->where([['tbl_work_order_items.work_order_id',$workorder_id]])->get();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

}
