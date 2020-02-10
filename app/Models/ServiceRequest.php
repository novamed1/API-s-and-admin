<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class ServiceRequest extends Model
{
    protected $table = 'tbl_service_request';

    public function AllServiceRequest($limit = 0, $offset = 0, $order_by = 'tb.brand_id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_service_request as tsr');

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
        }

        $query->join('tbl_customer as tc', 'tc.id', '=', 'tsr.customer_id');

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

    public function RequestItems($limit = 0, $offset = 0, $order_by = 'tb.brand_id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {

        DB::enableQueryLog();
        $query = DB::table('tbl_service_request_item as tsri');

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
                        } else {
                            $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                        }
                        $flag = false;
                    }
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }
        if (isset($cond['requestId']) && $cond['requestId'] != '') {
            $query->where('tsri.service_request_id', '=', $cond['requestId']);
        }


        $query->join('tbl_service_request as tsr', 'tsr.id', '=', 'tsri.service_request_id');


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

//        $query = DB::getQueryLog();
//        print_r($query);die;
        return $result;
    }

    public function getServiceItems($requestId)
    {

        $query = DB::table('tbl_service_request_item')
            ->where('service_request_id', '=', $requestId);
        $query->select('*')->get();
        return $query;
    }

    public function checkWorkOrder($servicerequestId)
    {

        $query = DB::table('tbl_work_order')->where('request_id', '=', $servicerequestId)->first();
        return $query;
    }



}