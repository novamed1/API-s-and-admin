<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class CustomerService extends Model
{
    protected $table = 'tbl_customer_buy_service';


    public function AllBuyService($limit = 0, $offset = 0, $order_by = 'tcbs.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer_buy_service as tcbs');

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
                $likeArrayFields = array('');
            }


            if (!empty($likeArray)) {

                $flag = true;
                $like = '';
                foreach ($likeArray as $value) {
                    if ($flag) {

                        $like .= $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                    } else {
                        $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                    }
                    $flag = false;
                }
                $query->whereRaw('(' . $like . ')');
            }
        }

        if (isset($cond['customerId']) && $cond['customerId'] != '') {
            $query->where('c.id', '=', $cond['customerId']);
        }
        if (isset($cond['serviceStatus']) && $cond['serviceStatus'] != '') {
            $query->where('tcbs.service_status', '=', $cond['serviceStatus']);
        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        $query->join('tbl_customer as tc', 'tc.id', '=', 'tcbs.customer_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }

        return $result;
    }

    public function AllBuyServiceGrid($limit = 0, $offset = 0, $order_by = 'tcbs.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer_buy_service as tcbs');

//        print_r($cond);die;
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            if(!isset($cond['count']))
            {
                $query->limit($limit);
                $query->offset($offset);
            }
        }


        if (isset($cond['search']) && $cond['search'] != '') {

            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key=>$value) {
                    if($key == 'tcbs.created_date'){
                        if($value != '') {
                            $value = date('Y-m-d', strtotime(str_replace('-', '/', $value)));
                        }
                    }
                    if($value) {
                        if ($flag) {

                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
                        } else {
                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
                        }
                        $flag = false;
                    }
                }
                if(array_filter($cond['search'])) {
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }

        if (isset($cond['customerId']) && $cond['customerId'] != '') {
            $query->where('c.id', '=', $cond['customerId']);
        }
        if (isset($cond['serviceStatus']) && $cond['serviceStatus'] != '') {
            $query->where('tcbs.service_status', '=', $cond['serviceStatus']);
        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->where('is_created',0);

        $query->join('tbl_customer as tc', 'tc.id', '=', 'tcbs.customer_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }

        return $result;
    }

    public function AllModels($limit = 0, $offset = 0, $order_by = 'tcsm.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer_service_model as tcsm');

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
                $likeArrayFields = array('');
            }


            if (!empty($likeArray)) {

                $flag = true;
                $like = '';
                foreach ($likeArray as $value) {
                    if ($flag) {

                        $like .= $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                    } else {
                        $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                    }
                    $flag = false;
                }
                $query->whereRaw('(' . $like . ')');
            }
        }

        if (isset($cond['customerId']) && $cond['customerId'] != '') {
            $query->where('c.id', '=', $cond['customerId']);
        }
        if (isset($cond['serviceId']) && $cond['serviceId'] != '') {
            $query->where('tcsm.service_id', '=', $cond['serviceId']);
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        $query->join('tbl_customer_buy_service as tcbs', 'tcbs.id', '=', 'tcsm.service_id');
        $query->join('tbl_equipment_model as tem', 'tem.id', '=', 'tcsm.model_id');
        $query->join('tbl_frequency as fr', 'fr.id', '=', 'tcsm.frequency_id','left');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }

        return $result;
    }

    public function getService($serviceId)
    {

        $query = DB::table('tbl_customer_buy_service')->where('id', '=', $serviceId)->select('*')->first();
        return $query;
    }

    public function getCustomerPlans($customerId)
    {

        $query = DB::table('tbl_customer_plans')->where('customer_id', '=', $customerId)->select('*')->get();
        return $query;
    }


    public function getPlan($planId)
    {


        $query = DB::table('tbl_service_plan')->where('id', '=', $planId)->select('*')->first();
        return $query;
    }

    public function customer_service_model($id)
    {

        $query = DB::table('tbl_customer_service_model as sm')
             ->join('tbl_equipment_model as em', 'em.id', '=', 'sm.model_id')
            ->where('sm.id', '=', $id)
            ->select('em.*')->first();
        return $query;
    }

    function service_price($plan_id,$volume,$operation,$channel,$channel_number)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_service_pricing')
            ->join('tbl_operations', 'tbl_operations.id', '=', 'tbl_service_pricing.operation')
            ->join('tbl_channel_points', 'tbl_channel_points.id', '=', 'tbl_service_pricing.channel_point')
            ->join('tbl_channel_numbers', 'tbl_channel_numbers.id', '=', 'tbl_channel_points.point_name')
            ->join('tbl_service_plan', 'tbl_service_plan.id', '=', 'tbl_service_pricing.plan_id')
            ->where([['tbl_service_pricing.plan_id', '=', $plan_id],
                ['tbl_service_pricing.volume', '=', $volume],
                ['tbl_service_pricing.operation', '=', $operation],
                ['tbl_service_pricing.channel', '=', $channel],
                ['tbl_channel_numbers.id', '=', $channel_number]])->select('tbl_service_pricing.id','price')->first();
        return $query;
    }
//save enquiry
    public function saveServiceModels($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_customer_buy_service')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_customer_buy_service')->insertGetId($input);
            return $result;
        }
    }

    public function saveBuyService($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_customer_buy_service')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_customer_buy_service')->insertGetId($input);
            return $result;
        }
    }



}



