<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use DB;
use Carbon\Carbon;

class Processoperation extends Authenticatable
{
    use Notifiable,EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    function instrumentLists($workorderid)
    {
        $query= DB::table('tbl_work_order_items as oi')
            ->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id')
            ->join('tbl_equipment as e','e.id','=','de.equipment_id')
            ->join('tbl_equipment_model as em','em.id','=','e.equipment_model_id')
            ->join('tbl_product_type as pt','pt.product_type_id','=','em.product_id')
            ->join('tbl_service_plan as sp','sp.id','=','ri.test_plan_id')
            ->where('oi.work_order_id',$workorderid)
            ->select('e.id','e.asset_no','e.serial_no','em.model_name',
                'oi.id as work_order_item_id','em.id as model_id','pt.product_type_name',
                'em.model_description','sp.service_plan_name','oi.as_found_status',
                'oi.maintenance_status','oi.as_calibrated_status','oi.despatched_status')->get();
        return $query;
    }

    function get_service_plan($id)
    {
        $query = DB::table('tbl_service_plan')
            ->where('id',$id)
            ->get()->first();
        return $query;
    }

    function instrumentDetail($work_order_item_id)
    {
        $query = DB::table('tbl_work_order_items as oi')
            ->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id')
            ->join('tbl_equipment as e','e.id','=','de.equipment_id')
            ->join('tbl_equipment_model as em','em.id','=','e.equipment_model_id')
            ->join('tbl_frequency as f','f.id','=','ri.frequency_id')
            ->join('tbl_customer as c','c.id','=','e.customer_id')
            ->select('oi.*','ri.*','de.*','e.*','em.*','f.*','f.name as fname','c.*')
            ->where('oi.id',$work_order_item_id)->get()->first();
        return $query;

    }

}
