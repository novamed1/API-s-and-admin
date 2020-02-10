<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
class CustomerSpecification extends Model
{
    public function AllCustomerSpecification($limit = 0, $offset = 0, $order_by = 'c.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer as c');

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


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_customer_type as t', 't.id', '=', 'c.customer_type_id');

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
    public function AllCustomerSpecificationGrid($limit = 0, $offset = 0, $order_by = 'c.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {


        $query = DB::table('tbl_customer as c');

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


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_customer_type as t', 't.id', '=', 'c.customer_type_id');
        $query->join('tbl_customer_setups as cs','cs.customer_id','=','c.id')->where('cal_spec','=',3);
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

    public function getcustomer($customerId)
    {
      $query = DB::table('tbl_customer as tc')->where('tc.id', '=', $customerId)
                ->join('tbl_customer_type as tct', 'tct.id', '=', 'tc.customer_type_id')->
                select('tc.id', 'tc.customer_name', 'tct.name', 'tc.customer_type_id', 'tc.customer_email',
                    'tc.customer_main_telephone', 'tc.address1', 'tc.state', 'tc.city', 'tc.customer_email','tc.unique_id',
                    'tc.customer_telephone','tc.address2','tc.primary_contact')->first();
            return $query;
    }

    public function getcalspecification($customerId,$limit = 0, $offset = 0, $order_by = 'is.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {

        // $likeArray = array('model_name','channel','operation_name','manufacturer_name','model_price');
        $query = DB::table('tbl_iso_specifications as is');

//        print_r($cond);die;
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            if(!isset($cond['count']))
            {
                $query->limit($limit);
                $query->offset($offset);
            }
        }
//        if (isset($cond['search']) && $cond['search'] != '') {


//            if (!empty($cond['search'])) {
//
//                $flag = true;
//                $like = '';
//                foreach ($cond['search'] as $key=>$value) {
//                    if($value) {
//                        if ($flag) {
//
//                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
////                       print_r($like);die;
//                        } else {
//                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
////                       print_r($like);die;
//
//                        }
//                        $flag = false;
//                    }
//                }
////                    DB::enableQueryLog();
//                if(array_filter($cond['search'])) {
//                    $query->whereRaw('(' . $like . ')');
//                }
//            }
//
//        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_channels as c', 'c.id', '=', 'is.channel_id');
        $query->join('tbl_operations as o', 'o.id', '=', 'is.operation_id');
        $query->join('tbl_volume as v', 'v.id', '=', 'is.volume_id');
        $query->join('tbl_units as u', 'u.id', '=', 'is.unit');
        $query->where('is.customer_id', '=', $customerId);
        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }

//        print_r($result);die;
        return $result;
    }
//   public function getcalspecification($customerId)
//    {
//
//        $query = DB::table('tbl_iso_specifications as tis')
//            ->join('tbl_channels as c', 'c.id', '=', 'tis.channel_id')
//            ->join('tbl_operations as o', 'o.id', '=', 'tis.operation_id')
//            ->join('tbl_volume as v', 'v.id', '=', 'tis.volume_id')
//            ->join('tbl_units as u', 'u.id', '=', 'tis.unit')
//            ->where('customer_id', '=', $customerId)->select('tis.*','c.channel_name','o.operation_name','v.volume_name','u.unit')->get();
//        return $query;
//    }


    function checkcustomercombination($post)
    {
        $query = DB::table('tbl_iso_specifications');
        if($post['volumeRange'])
        {
            $query->where([
                ['customer_id', '=', $post['customerID']],
                ['channel_id', '=', $post['channel']],
                ['operation_id', '=', $post['operation']],
                ['volume_id', '=', $post['volume']],
                ['volume_value', '=', $post['volumeRange']]
            ]);
        }
        else
        {
            $query->where([
                ['customer_id', '=', $post['customerID']],
                ['channel_id', '=', $post['channel']],
                ['operation_id', '=', $post['operation']],
                ['volume_id', '=', $post['volume']],
                ['volume_value', '=', $post['volumeFrom']]
            ]);
        }

        $query->where('id','!=',$post['id']);

        return $query->get();
    }

}
