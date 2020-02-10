<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class Equipment extends Model
{
    protected $table = 'tbl_equipment_model';

    public function AllEquipment($limit = 0, $offset = 0, $order_by = 'te.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_equipment_model as te');

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
                            $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;

                        }
                        $flag = false;
                    }
//                    DB::enableQueryLog();
                    $query->whereRaw('(' . $like . ')');
                }
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
                            $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;

                        }
                        $flag = false;
                    }
//                    DB::enableQueryLog();
                    $query->whereRaw('(' . $like . ')');
                }
            }

        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_manufacturer as tm', 'tm.manufacturer_id', '=', 'te.manufacturer_ids');
        $query->join('tbl_brand as tb', 'tb.brand_id', '=', 'te.brand_id');
        $query->join('tbl_operations as tbo', 'tbo.id', '=', 'te.brand_operation');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
//          $query = DB::getQueryLog();
//        print_r($query);
//        die;


//
        return $result;
    }


    public function saveEquipment($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_equipment_model')->insertGetId($input);
            return $result;
        }
    }

    public function getEquipment($equipmentId)
    {
        $query = DB::table('tbl_equipment')->where('id', '=', $equipmentId)->select('*')->first();
        return $query;
    }
    public function saveTolerance($input)
    {


        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_limit_tolerance')->where('id', $input['id'])->update($input);
            return $input['model_id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_limit_tolerance')->insertGetId($input);
            return $result;
        }
    }

    public function getmodel($Id)
    {

        $result = DB::table('tbl_equipment_model as te')->where('te.id', '=', $Id)->select('*')->first();

        return $result;
    }

    public function getlimits($Id)
    {
        $query = DB::table('tbl_limit_tolerance')->where('model_id', $Id)->get();
        return $query;
    }

    public function getspares($Id)
    {
        $query = DB::table('tbl_equipment_model_spares')->where('model_id', $Id)->get();
        return $query;
    }

    public function deleteTolerance($id)
    {
        $delete = DB::table('tbl_limit_tolerance')->where('model_id', $id)->delete();
        return $delete;


    }

    public function getTestPlanModel($Id)
    {

        $result = DB::table('tbl_test_plan')->where('tbl_test_plan.model_id', '=', $Id)->first();

        return $result;
    }

    public function saveSpares($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model_spares')->where('id', $input['id'])->update($input);
            return $input['model_id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model_spares')->insertGetId($input);
            return $result;
        }
    }

    public function saveAccessories($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model_accessories')->where('id', $input['id'])->update($input);
            return $input['model_id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model_accessories')->insertGetId($input);
            return $result;
        }
    }

    public function saveEquipTips($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model_tips')->where('id', $input['id'])->update($input);
            return $input['model_id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model_tips')->insertGetId($input);
            return $result;
        }
    }

    public function saveDocs($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_model_documents')->where('id', $input['id'])->update($input);
            return $input['model_id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_model_documents')->insertGetId($input);
            return $result;
        }
    }



    public function saveInstrumentDetails($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_instrument_plan_details')->where('id', $input['id'])->update($input);
            return $input['model_id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_instrument_plan_details')->insertGetId($input);
            return $result;
        }
    }

    public function deleteInstrumentDetails($insturmentId)
    {
        $query = DB::table('tbl_instrument_plan_details')->where('equipment_id', '=', $insturmentId)->delete();
        return $query;
    }

    public function getInstrumentDetails($insturmentId)
    {
        $query = DB::table('tbl_instrument_plan_details')->where('equipment_id', '=', $insturmentId)->first();
        return $query;
    }

    public function getSelectedServicePricing($insturmentId)
    {
        $query = DB::table('tbl_instrument_plan_details')->where('equipment_id', '=', $insturmentId)->first();
        return $query;
    }


    public function saveInstrumentLogDetails($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_instrument_plan_log')->where('id', $input['id'])->update($input);
            return $input['model_id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_instrument_plan_log')->insertGetId($input);
            return $result;
        }
    }

    public function saveModelImages($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model_images')->where('id', $input['id'])->update($input);
            return $input['model_id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_equipment_model_images')->insertGetId($input);
            return $result;
        }
    }

    public function getModelDocs($modelId, $docType)
    {
        $query = DB::table('tbl_model_documents')->where([['model_id', '=', $modelId], ['document_type', '=', $docType]])->select('*')->get();
        return $query;
    }
    public function getspareMode($id)
    {
        $query = DB::table('tbl_spare_mode')->where('id', '=', $id)->select('*')->first();
        return $query;
    }

    public function getAccessory($Id)
    {
        $query = DB::table('tbl_equipment_model_accessories')->where('model_id', '=', $Id)->select('*')->get();
        return $query;
    }

    public function getPartTips($Id)
    {
        $query = DB::table('tbl_equipment_model_tips')->where('model_id', '=', $Id)->select('*')->get();
        return $query;

    }

  
    public function deleteModelImages($modelId)
    {
        $query = DB::table('tbl_equipment_model_images')->where('model_id', '=', $modelId)->delete();
        return $query;
    }

    public function deleteSpares($modelId)
    {
        $query = DB::table('tbl_equipment_model_spares')->where('model_id', '=', $modelId)->delete();
        return $query;
    }
    public function deleteAccessories($modelId)
    {
        $query = DB::table('tbl_equipment_model_accessories')->where('model_id', '=', $modelId)->delete();
        return $query;
    }



    public function deleteEquipTips($modelId)
    {
        $query = DB::table('tbl_equipment_model_tips')->where('model_id', '=', $modelId)->delete();
        return $query;
    }

    public function getProduct($Id)
    {
        $query = DB::table('tbl_product_type')->where('product_type_id', '=', $Id)->select('*')->first();
        return $query;
    }

    public function deleteModelDocs($modelId, $docType)
    {
        $query = DB::table('tbl_model_documents')->where([['model_id', '=', $modelId], ['document_type', '=', $docType]])->delete();
        return $query;
    }

    public function getmanufacturerbySerialNo($serailNo)
    {
        $query = DB::table('tbl_manufacturer')->where('serial_no', '=', $serailNo)->select('*')->first();
        return $query;
    }
    public function getmodelimages($modelId)
    {
        $query = DB::table('tbl_equipment_model_images')->where('model_id', '=', $modelId)->select('*')->get();
        return $query;
    }


    public function getmanufacturerbyId($serailNo)
    {
        $query = DB::table('tbl_manufacturer')->where('manufacturer_id', '=', $serailNo)->select('*')->first();
        return $query;
    }

    public function deleteDueEquipment($equipId){
        $query = DB::table('tbl_due_equipments')->where('equipment_id','=',$equipId)->delete();
        return $query;
    }
    public function getDueEquipment($dueequipId)
    {
        $query = DB::table('tbl_due_equipments')->where('id', '=', $dueequipId)->select('*')->first();
        return $query;
    }

    public function deleteSparesbyId($spareId)
    {
        $query = DB::table('tbl_equipment_model_spares')->where('id', '=', $spareId)->delete();
        return $query;
    }

    public function deleteAccessoriesbyId($accessoryId)
    {
        $query = DB::table('tbl_equipment_model_accessories')->where('id', '=', $accessoryId)->delete();
        return $query;
    }

    public function deleteEquipTipsbyId($tipId)
    {
        $query = DB::table('tbl_equipment_model_tips')->where('id', '=', $tipId)->delete();
        return $query;
    }


//    public function dueequipments($limit = 0, $offset = 0, $cond = array())
//    {
//
//
//        DB::enableQueryLog();
//
//        $query = DB::table('tbl_due_equipments as DE');
//        if ($limit > 0) {
//            $query->limit($limit);
//            $query->offset($offset);
//        }
//        if (isset($cond['search']) && $cond['search'] != '') {
//
//            $likeArrayFields = array('E.asset_no','E.serial_no','E.	pref_contact','E.location','EM.model_name');
//            if (!empty($likeArrayFields)) {
//
//                $flag = true;
//                $like = '';
//                foreach ($likeArrayFields as $value) {
//                    if ($flag) {
//
//                        $like .= $value . " LIKE '%" . trim($cond['search']) . "%' ";
//                    } else {
//                        $like .= " OR " . $value . " LIKE '%" . trim($cond['search']) . "%' ";
//                    }
//                    $flag = false;
//                }
//                $query->whereRaw('(' . $like . ')');
//            }
//        }
//        if (isset($cond['cus_id']) && $cond['cus_id'] != '') {
//            $query->where('E.customer_id', $cond['cus_id']);
//        }
//        if (isset($cond['calibrate_process']) && $cond['calibrate_process'] != '') {
//            $query->where('DE.calibrate_process','!=',1);
//        }
//        $query->join('tbl_equipment as E', 'E.id', '=', 'DE.equipment_id');
//        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
//        $query->join('tbl_customer_setups as CS', 'CS.customer_id', '=', 'E.customer_id','left');
//        $query->join('tbl_frequency as F', 'F.id', '=', 'CS.cal_frequnecy','left');
//
//        $query->orderBy('DE.next_due_date', 'ASC');
//        $query->select('E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_name','F.name as call_frequency','E.id as equipment_id','DE.next_due_date','DE.last_cal_date','DE.id as due_equipment_id');
//
//        if(isset($cond['status']) && $cond['status'] != '')
//        {
//            if($cond['status']==1)
//            {
//                $query->where('DE.next_due_date','<', date('Y-m-d'));
//            }
//            else
//            {
//                $query->where('DE.next_due_date','>=', date('Y-m-d'));
//            }
//        }
//        if(isset($cond['startCallDate']) && $cond['startCallDate'] != '')
//        {
//            $query->where('DE.last_cal_date','>=', $cond['startCallDate']);
//        }
//        if(isset($cond['endCallDate']) && $cond['endCallDate'] != '')
//        {
//            $query->where('DE.last_cal_date','<=', $cond['endCallDate']);
//        }
//
//        $result = $query->get();
//        //print_r(DB::getQueryLog());die;
//        return $result;
//    }

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
        $query->select('E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_name','F.name as call_frequency','E.id as equipment_id','DE.next_due_date','DE.last_cal_date','DE.id as due_equipment_id',DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%Y-%m') as d"));
        $query->orderBy('DE.next_due_date', 'ASC');
        $query->groupBy("d");
       // $query->limit(4);

        if(isset($cond['status']) && $cond['status'] != '')
        {
            if($cond['status']==1)
            {
                $query->where('DE.next_due_date','<=', date('Y-m-d'));
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

        $result = $query->get();
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    public function viewAlldueEquipments($limit = 0, $offset = 0, $cond = array(),$count)
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
        $query->join('tbl_instrument_plan_details as D', 'E.id', '=', 'D.equipment_id','left');
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->join('tbl_customer_setups as CS', 'CS.customer_id', '=', 'E.customer_id','left');
        $query->join('tbl_frequency as F', 'F.id', '=', 'CS.cal_frequnecy','left');
        $query->select('E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_name','F.name as call_frequency','E.id as equipment_id','DE.next_due_date','DE.last_cal_date','DE.id as due_equipment_id',DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%m-%Y') as d"));

        if (isset($cond['monthYear']) && $cond['monthYear'] != '') {
            if(isset($cond['monthAndYear']) && $cond['monthAndYear'] != '')
            {
                if($cond['monthYear']==date('m-Y'))
                {
                    $query->where(DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%m-%Y')"),'<=',$cond['monthYear']);
                }
                else
                {
                    $query->where(DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%m-%Y')"),'=',$cond['monthYear']);
                }
            }
            else
            {
                if($cond['monthYear']==date('Y-m'))
                {
                    $query->where(DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%Y-%m')"),'<=',$cond['monthYear']);
                }
                else
                {
                    $query->where(DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%Y-%m')"),'=',$cond['monthYear']);
                }
            }


        }


        $query->orderBy('DE.next_due_date', 'ASC');

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
        if(!$count)
        {
            $result = $query->get();
        }
        else
        {
            $result = $query->count();
        }
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    public function monthWiseDueEquipment($limit = 0, $offset = 0, $cond = array())
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
        $query->select('E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'EM.model_name','F.name as call_frequency','E.id as equipment_id','DE.next_due_date','DE.last_cal_date','DE.id as due_equipment_id',DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%Y-%m') as d"));
        $query->orderBy('DE.next_due_date', 'ASC');
        $query->where(DB::raw("date_format(str_to_date(DE.next_due_date, '%Y-%m-%d'), '%Y-%m')"),'=',$cond['monthYear']);
       // $query->groupBy("d");
        // $query->limit(4);

        if(isset($cond['status']) && $cond['status'] != '')
        {
            if($cond['status']==1)
            {
                $query->where('DE.next_due_date','<=', date('Y-m-d'));
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

        $result = $query->get();
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    function equipmentsCount($limit,$offset,$cond = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_due_equipments as DE');
        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }
         //$query->select('id');
        if (isset($cond['search']) && $cond['search'] != '') {

            $likeArrayFields = array('E.asset_no','E.serial_no','E.location','E.pref_contact','E.pref_tel','E.pref_email');
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
      if(isset($cond['customer_id']) && $cond['customer_id'])
      {
          $query->where('E.customer_id','=',$cond['customer_id']);
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
        $query->join('tbl_equipment as E', 'E.id', '=', 'DE.equipment_id');
        $query->join('tbl_equipment_model as EM', 'EM.id', '=', 'E.equipment_model_id');
        $query->join('tbl_customer_setups as CS', 'CS.customer_id', '=', 'E.customer_id','left');
        $query->join('tbl_frequency as F', 'F.id', '=', 'CS.cal_frequnecy','left');

      $result = $query->get();
      //  print_r(DB::getQueryLog());die;
      return $result;
    }

    public function getchannelnumber($model_id)
    {
        $query = DB::table('tbl_equipment_model as m')
            ->join('tbl_channel_numbers as n','n.id','=','m.channel_number')
            ->select('n.channel_number','m.brand_operation')
            ->where('m.id', '=', $model_id)->first();
        return $query;
    }

    public function getpricing($pricing_id)
    {
        $query = DB::table('tbl_service_pricing')
            ->select('price')
            ->where('id', '=', $pricing_id)->first();
        return $query;
    }

}

    
   




