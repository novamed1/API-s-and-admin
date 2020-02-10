<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class ServicePlan extends Model
{
    protected $table = 'tbl_service_plan';

    public function AllServicePlan($limit = 0, $offset = 0, $order_by = 'tsp.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_service_plan as tsp');

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
                $likeArrayFields = array('tsp.id');
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

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }


        return $result;
    }

    public function AllServicePlanGrid($limit = 0, $offset = 0, $order_by = 'tsp.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_service_plan as tsp');

//        print_r($cond);die;
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }


        if (isset($cond['search']) && $cond['search'] != '') {


            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key => $value) {
                    if ($value) {
                        if ($flag) {

                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
                        } else {
                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
                        }
                        $flag = false;
                    }
                }
                if (array_filter($cond['search'])) {
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }


        $query->join('tbl_product_type as tpt', 'tpt.product_type_id', '=', 'tsp.product_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }


        return $result;
    }


    public function saveServicePlan($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_service_plan')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_service_plan')->insertGetId($input);
            return $result;
        }
    }


    public function getPlan($Id)
    {

        $result = DB::table('tbl_service_plan')->where('id', '=', $Id)->first();
        return $result;
    }

    public function getServicePricing($planid)
    {
        $result = DB::table('tbl_service_pricing')->where('plan_id', '=', $planid)->get();
        return $result;
    }
    public function servicePricing($priceId)
    {
        $result = DB::table('tbl_service_pricing')->where('id', '=', $priceId)->first();
        return $result;
    }

    public function deletePlanPricing($planId)
    {
        $query = DB::table('tbl_service_pricing')->where('plan_id', '=', $planId)->delete();
        return $query;
    }

    public function deleteServicePricingByPriceId($servicePriceId)
    {
        $query = DB::table('tbl_service_pricing')->where('id', '=', $servicePriceId)->delete();
        return $query;
    }


    public function savePlanPricing($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_service_pricing')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_service_pricing')->insertGetId($input);
            return $result;
        }
    }

    public function getOperations($operationId)
    {
        $query = DB::table('tbl_operations')->where('id', '=', $operationId)->select('*')->first();
        return $query;
    }

    public function getVolume($volumeId)
    {
        $query = DB::table('tbl_volume')->where('id', '=', $volumeId)->select('*')->first();
        return $query;
    }

    public function getChannel($channelId)
    {
        $query = DB::table('tbl_channels')->where('id', '=', $channelId)->select('*')->first();
        return $query;
    }

    public function getChannelPoint($pointId)
    {
        $query = DB::table('tbl_channel_points')->join('tbl_channel_numbers','tbl_channel_numbers.id','=','tbl_channel_points.point_name')->where('tbl_channel_points.id', '=', $pointId)->select('*')->first();
        return $query;
    }
    public function getChannelNumber($pointId)
    {
        $query = DB::table('tbl_channel_numbers')->where('id', '=', $pointId)->select('*')->first();
        return $query;
    }
    public function chooseServicePricing($planId, $channel)
    {

        $query = DB::table('tbl_service_pricing')->where([['plan_id', '=', $planId], ['channel', '=', $channel]])->select('*')->get();

        return $query;
    }

    //for deleting user management
    public function deletePlan($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;

        DB::table('tbl_service_pricing')->where('plan_id', $id)->delete();

        $delete = DB::table('tbl_service_plan')->where('id', $id)->delete();

        if ($delete == 1) {
//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }
}

    
   




