<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use DB;
use Carbon\Carbon;

class Technicianuser extends Authenticatable
{
    use Notifiable,EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];
    protected $table = 'tbl_users';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];



    public function checkRole($id){
//print_r("fdas");die;

        $query= DB::table('tbl_group_user')->where('users_id',$id)->where('user_group_id',3)->first();

        return $query;
    }

    public function getUserTechnician($id){
//print_r("fdas");die;
        DB::enableQueryLog();

        $query= DB::table('tbl_technician as T')
            ->where('U.id',$id)
            ->select('T.id','U.id as user_id','T.first_name','T.last_name','T.email','T.phone_number','T.address','T.city','T.state','T.zip_code')
            ->join('tbl_users as U','U.id','=','T.user_id')
            ->join('tbl_group_user as G','U.id','=','G.users_id')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }
    function getWorkorder($id)
    {
        $query= DB::table('tbl_work_order as W')
            ->where('W.id',$id)
            ->select('W.*')->first();
            //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getWorkorderDetails($id)
    {
        $query= DB::table('tbl_work_order as W')
            ->where('W.id',$id)
            ->join('tbl_service_plan as S','S.id','=','W.plan_id')
            ->join('tbl_service_request as R','R.id','=','W.request_id')
            ->join('tbl_customer as C','C.id','=','R.customer_id')
            ->join('tbl_customer_contacts as CC','CC.customer_id','=','C.id','left')
            ->select('W.*','S.service_plan_name','C.customer_name','C.address1','CC.contact_name','CC.location','CC.email_id','CC.phone_no','S.as_found','S.as_calibrate','R.billing_address_id','R.shipping_address_id','C.id as customer_id','W.as_found as as_founded','S.calibration_outside','C.customer_type_id','R.on_site')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function instrumentLists($workorderid)
    {
        $query= DB::table('tbl_work_order_items as oi')
            ->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
            ->join('tbl_due_equipments as de','de.id','=','ri.due_equipments_id')
            ->join('tbl_frequency as f','f.id','=','de.frequency_id','left')
            ->join('tbl_equipment as e','e.id','=','de.equipment_id')
            ->join('tbl_equipment_model as em','em.id','=','e.equipment_model_id')
            ->join('tbl_product_type as pt','pt.product_type_id','=','em.product_id')
            ->join('tbl_service_plan as sp','sp.id','=','ri.test_plan_id')
            ->where('oi.work_order_id',$workorderid)
            ->select('e.id','e.asset_no','e.serial_no','em.model_name','oi.id as work_order_item_id','em.id as model_id','pt.product_type_name','em.model_description'
                ,'sp.service_plan_name','oi.as_found_status','oi.maintenance_status','oi.as_calibrated_status','oi.despatched_status'
                ,'f.name as frequency_name','de.last_cal_date','sp.as_found','sp.as_calibrate')->orderby('oi.id','ASC')->get();
        return $query;
    }

    function getBilling($id)
    {
        $query= DB::table('tbl_customer_billing_address as B')
            ->where('B.id',$id)
            ->select('B.*')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getShipping($id)
    {
        $query= DB::table('tbl_customer_shipping_address as S')
            ->where('S.id',$id)
            ->select('S.*')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    function getCustomerLabels($customer_id)
    {
        $query = DB::table('tbl_customer_setups as S')
            ->where('S.customer_id',$customer_id)
            ->select('S.asset_label')->first();
        $label = (isset($query->asset_label) && $query->asset_label)?explode(',',$query->asset_label):'';
        $labelname = '';
        if($label)
        {
            $query1 = DB::table('tbl_labeling as L')
                ->wherein('L.id',$label)
                ->select('L.name')->get();
            if(count($query1))
            {
                $labelarr = array();
                foreach ($query1 as $key=>$row)
                {
                    $labelarr[$key] = $row->name;
                }
                $labelname = implode(',',$labelarr);
            }
        }
        return $labelname;
    }

}
