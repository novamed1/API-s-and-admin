<?php

namespace App\Models;

use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Input;
use DateTime;

class Dashboard extends Model
{



    function dashboardCounts($cond=array())
    {
        //DB::enableQueryLog();
        $query = DB::table('tbl_due_equipments as DE');
        if(isset($cond['cus_id']) && $cond['cus_id'])
        {
            $query->where('E.customer_id','=',$cond['cus_id']);
        }
        if(isset($cond['overdue']) && $cond['overdue'])
        {
            $query->where('DE.next_due_date','<=', date('Y-m-d'));
        }
        if(isset($cond['upcoming']) && $cond['upcoming'])
        {
            $query->where('DE.next_due_date','>', date('Y-m-d'));
        }
        $query->join('tbl_equipment as E', 'E.id', '=', 'DE.equipment_id');
        $query->join('tbl_instrument_plan_details as D', 'D.equipment_id', '=', 'E.id','left');
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->join('tbl_customer_setups as CS', 'CS.customer_id', '=', 'E.customer_id','left');
        $query->join('tbl_frequency as F', 'F.id', '=', 'CS.cal_frequnecy','left');
        $query->select('E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_description as model_name','F.name as call_frequency','E.id as equipment_id','DE.next_due_date','DE.last_cal_date','DE.id as due_equipment_id',DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%Y-%m') as d"),DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%Y-%m-%d') as day"));
        $query->orderBy('DE.next_due_date', 'ASC');
       // $query->groupBy("DE.id");
        $result = $query->count();
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    public function dueequipments($limit = 0, $offset = 0, $cond = array())
    {


        DB::enableQueryLog();

        $query = DB::table('tbl_due_equipments as DE');
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
        if (isset($cond['cus_id']) && $cond['cus_id'] != '') {
            $query->where('E.customer_id', $cond['cus_id']);
        }
        if (isset($cond['calibrate_process']) && $cond['calibrate_process'] != '') {
            $query->where('DE.calibrate_process','!=',1);
        }
        $query->join('tbl_equipment as E', 'E.id', '=', 'DE.equipment_id');
        $query->join('tbl_instrument_plan_details as D', 'D.equipment_id', '=', 'E.id','left');
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->join('tbl_customer_setups as CS', 'CS.customer_id', '=', 'E.customer_id','left');
        $query->join('tbl_frequency as F', 'F.id', '=', 'CS.cal_frequnecy','left');
        $query->join('tbl_service_plan as SP', 'SP.id', '=', 'E.plan_id','left');

        $query->orderBy('DE.next_due_date', 'ASC');
        $query->select('E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_description as model_name','F.name as call_frequency_value','E.id as equipment_id','DE.next_due_date','DE.last_cal_date','DE.id as due_equipment_id','D.price','D.plan_id','D.pricing_criteria_id','D.plan_name','F.no_of_days','SP.service_plan_name');

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
        $query->groupBy('DE.id');

        $result = $query->get();
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    function customerPayment($cond)
    {
        $query = DB::table('tbl_purchase_order as PO');

        $query->where('customer_id','=',$cond['cus_id']);
        if(isset($cond['payment']) && $cond['payment'] == 'paid')
        {
            $query->where('payment_flg','=',1);
        }
        //$query->sum('order_amt');
        //$result = $query->first();
        return $query->sum('grand_total');



    }

    function workorders($type)
    {
        $query = DB::table('tbl_work_order as wo');
        if(isset($type['type'])&&$type['type'])
        {
            if($type['type']=='complete')
            {
                $query->where('wo.status','=',5);
            }
        }
        $result =$query->get();
        return $result;
    }

    function portalRequests($type)
    {
        $query = DB::table('tbl_customer_buy_service as bs');
        $query->join('tbl_customer as c','c.id','=','bs.customer_id');
        $query->join('tbl_customer_type as ct','ct.id','=','c.customer_type_id');
        $query->select('c.customer_name','c.unique_id','c.customer_telephone',
            'c.customer_email','bs.total_models','bs.is_created','ct.name','bs.created_date','bs.id');
        if(isset($type['type'])&&$type['type'])
        {
            if($type['type']=='list')
            {
                $query->limit(5);
                $query->where('bs.is_created','!=',1);
            }
        }
        $query->orderby('bs.id','DESC');
        $result =$query->get();
        return $result;
    }



}
