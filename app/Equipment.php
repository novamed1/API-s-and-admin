<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class Equipment extends Authenticatable
{
    public function equipments($limit = 0, $offset = 0, $cond = array())
    {


        DB::enableQueryLog();

        $query = DB::table('tbl_due_equipments as DE');
        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }
        if (isset($cond['search']) && $cond['search'] != '') {

            $likeArrayFields = array('E.asset_no','E.serial_no','E.location','E.pref_contact','E.pref_tel','E.pref_email','EM.model_description');
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

        if(isset($cond['asset_num']) && $cond['asset_num'] != '')
        {
            $query->where('E.asset_no','like', $cond['asset_num'].'%');
        }
        if(isset($cond['serial_num']) && $cond['serial_num'] != '')
        {
            $query->where('E.serial_no','like', $cond['serial_num'].'%');
        }
        if(isset($cond['instrument']) && $cond['instrument'] != '')
        {
            $query->where('EM.model_description','like', $cond['instrument'].'%');
        }
        if(isset($cond['location']) && $cond['location'] != '')
        {
            $query->where('E.location','like', $cond['location'].'%');
        }
        if(isset($cond['service_plan']) && $cond['service_plan'] != '')
        {
            $query->where('SP.service_plan_name','like', $cond['service_plan'].'%');
        }
        if(isset($cond['cal_frequency']) && $cond['cal_frequency'] != '')
        {
            $query->where('F.name','like', $cond['cal_frequency'].'%');
        }
        if(isset($cond['last_cal_date']) && $cond['last_cal_date'] != '')
        {
            $query->where(DB::raw("DATE_FORMAT(DE.last_cal_date,'%m/%d/%Y')"),'like', $cond['last_cal_date'].'%');
        }
        if(isset($cond['next_due']) && $cond['next_due'] != '')
        {
            $query->where(DB::raw("DATE_FORMAT(DE.next_due_date,'%m/%d/%Y')"),'like', $cond['next_due'].'%');
        }
        if(isset($cond['pref_contact']) && $cond['pref_contact'] != '')
        {
            $query->where('E.pref_contact','like', $cond['pref_contact'].'%');
        }

        if(isset($cond['statusHeader']) && $cond['statusHeader'] != '')
        {
            if($cond['statusHeader']==2)
            {
                $query->where('DE.next_due_date','<', date('Y-m-d'));
            }
            else
            {
                $query->where('DE.next_due_date','>=', date('Y-m-d'));
            }
        }

        $query->join('tbl_equipment as E', 'E.id', '=', 'DE.equipment_id');
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->join('tbl_customer_setups as CS', 'CS.customer_id', '=', 'E.customer_id','left');
        $query->join('tbl_frequency as F', 'F.id', '=', 'DE.frequency_id','left');
        $query->join('tbl_service_plan as SP', 'SP.id', '=', 'E.plan_id');


        $query->orderBy('E.id', 'desc');
        $query->select('E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_description as model_name','F.name','E.id','SP.service_plan_name as service_plan','F.no_of_days');
        $result = $query->get();
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    public function countequipments($cond = array(), $cid)
    {
        $query = DB::table('tbl_equipment as E');
        if (isset($cond['search']) && $cond['search'] != '') {

            $likeArrayFields = array('E.asset_no','E.serial_no');
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
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->where('E.customer_id', $cid);
        $query->orderBy('E.id', 'desc');
        $query->select('E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_name');
        $result = $query->get();
        //print_r(DB::getQueryLog());die;
        return $result;
    }
    public function getDueequipments($id)
    {
        $query = DB::table('tbl_due_equipments as DE');
        $query->join('tbl_equipment as E', 'E.id', '=', 'DE.equipment_id');
        $query->where('E.id', $id);
        $query->orderby('DE.id', 'DESC');
        $result = $query->get()->first();
        //print_r(DB::getQueryLog());die;
        return $result;
    }
    public function equipmentDetail($id)
    {
        $query = DB::table('tbl_equipment as E');
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->join('tbl_brand as b', 'b.brand_id', '=', 'EM.brand_id','left');
        $query->join('tbl_manufacturer as m', 'm.manufacturer_id', '=', 'b.manufacturer_id','left');
        $query->join('tbl_due_equipments as DE', 'DE.equipment_id', '=', 'E.id','left');
        $query->join('tbl_instrument_plan_details as PD', 'PD.equipment_id', '=', 'E.id','left');
        $query->join('tbl_units as U', 'U.id', '=', 'EM.unit');
        $query->join('tbl_channels as C', 'C.id', '=', 'EM.channel');
        $query->join('tbl_operations as O', 'O.id', '=', 'EM.brand_operation');
        $query->where('E.id', $id);
        $query->select('E.id','E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_name','U.unit','C.channel_name','O.operation_name','EM.model_image','E.created_date','EM.volume_value','PD.plan_id','E.photo','E.name','E.description','DE.last_cal_date','DE.frequency_id','DE.next_due_date','EM.brand_operation','PD.pricing_criteria_id','EM.id as model_id','DE.as_found','DE.as_calibrate','DE.frequency_id','DE.exact_date','b.brand_id','m.manufacturer_id','E.pref_contact_id','EM.model_description');
        $result = $query->get()->first();
        //print_r(DB::getQueryLog());die;
        return $result;
    }




}
