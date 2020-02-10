<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class Order extends Model
{
    protected $table = 'tbl_customer_orders';

    public function AllOrder($limit = 0, $offset = 0, $order_by = 'tco.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer_orders as tco');

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

            if ((isset($cond['search']['start']) && $cond['search']['start'] != '') || isset($cond['search']['end']) && $cond['search']['end']) {
                if ($cond['search']['start'] != '' && $cond['search']['end'] != '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] != '' && $cond['search']['end'] == '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = date('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] == '' && $cond['search']['end'] != '') {
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $dateend = $enddate . ' 23:59:59';
                    $query->where('tco.order_date', '<', $dateend);
                } else {

                }
            }
        }
        if (isset($cond['orderType']) && $cond['orderType'] != '') {
            $query->where('tco.order_type', '=', $cond['orderType']);
        }

        $query->join('tbl_customer as tc','tco.customer_id','=','tc.id');


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


    public function AllOrderGrid($limit = 0, $offset = 0, $order_by = 'tco.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer_orders as tco');

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


            if (isset($cond['search']) && $cond['search'] != '') {
                if (!empty($cond['search'])) {

                    $flag = true;
                    $like = '';
                    foreach ($cond['search'] as $key=>$value) {
                        if($value)
                        {
                        if ($flag) {

                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
//                       print_r($like);die;
                        } else {
                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
//                       print_r($like);die;

                        }
                        $flag = false;
                        }
                    }
//                    DB::enableQueryLog();

                    if(array_filter($cond['search'])) {
                        $query->whereRaw('(' . $like . ')');
                    }
                }
            }

            if ((isset($cond['search']['start']) && $cond['search']['start'] != '') || isset($cond['search']['end']) && $cond['search']['end']) {
                if ($cond['search']['start'] != '' && $cond['search']['end'] != '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] != '' && $cond['search']['end'] == '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = date('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] == '' && $cond['search']['end'] != '') {
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $dateend = $enddate . ' 23:59:59';
                    $query->where('tco.order_date', '<', $dateend);
                } else {

                }
            }
        }
        if (isset($cond['orderType']) && $cond['orderType'] != '') {
            $query->where('tco.order_type', '=', $cond['orderType']);
        }

        $query->join('tbl_customer as tc','tco.customer_id','=','tc.id');


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

    public function AllProducts($limit = 0, $offset = 0, $order_by = 'tcp.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_customer_products as tcp');



        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }


        if (isset($cond['search']) && $cond['search'] != '') {

            if (isset($likeArray) && $likeArray != '') {
                $likeArrayFields = $likeArray;
            } else {
                $likeArrayFields = array('tcp.id');
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

            if ((isset($cond['search']['start']) && $cond['search']['start'] != '') || isset($cond['search']['end']) && $cond['search']['end']) {
                if ($cond['search']['start'] != '' && $cond['search']['end'] != '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] != '' && $cond['search']['end'] == '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = date('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] == '' && $cond['search']['end'] != '') {
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $dateend = $enddate . ' 23:59:59';
                    $query->where('tco.order_date', '<', $dateend);
                } else {

                }
            }
        }
       // print_r($cond['orderId']);die;
        if(isset($cond['orderId']) && $cond['orderId'] != ''){
            $query->where('tcp.order_id','=',$cond['orderId']);
        }
        $query->join('tbl_customer_orders as to','to.id','=','tcp.order_id');

        $query->join('tbl_customer as tc','tc.id','=','to.customer_id');
        //$query->join('tbl_equipment_model as tem','tem.id','=','tcp.model_id');


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

      //  print_r(DB::getQueryLog());

        return $result;
    }


    public function AllPartProducts($limit = 0, $offset = 0, $order_by = 'tcp.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer_products as tcp');

        DB::enableQueryLog();

        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }


        if (isset($cond['search']) && $cond['search'] != '') {

            if (isset($likeArray) && $likeArray != '') {
                $likeArrayFields = $likeArray;
            } else {
                $likeArrayFields = array('tcp.id');
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

            if ((isset($cond['search']['start']) && $cond['search']['start'] != '') || isset($cond['search']['end']) && $cond['search']['end']) {
                if ($cond['search']['start'] != '' && $cond['search']['end'] != '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] != '' && $cond['search']['end'] == '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = date('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] == '' && $cond['search']['end'] != '') {
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $dateend = $enddate . ' 23:59:59';
                    $query->where('tco.order_date', '<', $dateend);
                } else {

                }
            }
        }

        if (isset($cond['orderId']) && $cond['orderId'] != '') {
            $query->where('tcp.order_id', '=', $cond['orderId']);
        }

        $query->join('tbl_customer as tc', 'tcp.customer_id', '=', 'tc.id');
        $query->join('tbl_equipment_model_spares as tems', 'tems.id', '=', 'tcp.model_id');


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

    public function AllAccessoryProducts($limit = 0, $offset = 0, $order_by = 'tcp.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer_products as tcp');

        DB::enableQueryLog();

        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }


        if (isset($cond['search']) && $cond['search'] != '') {

            if (isset($likeArray) && $likeArray != '') {
                $likeArrayFields = $likeArray;
            } else {
                $likeArrayFields = array('tcp.id');
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

            if ((isset($cond['search']['start']) && $cond['search']['start'] != '') || isset($cond['search']['end']) && $cond['search']['end']) {
                if ($cond['search']['start'] != '' && $cond['search']['end'] != '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] != '' && $cond['search']['end'] == '') {
                    $startdate = Carbon::parse($cond['search']['start'])->format('Y-m-d');
                    $enddate = date('Y-m-d');
                    $datestart = $startdate . ' 00:00:00';
                    $dateend = $enddate . ' 23:59:59';
                    $query->whereBetween('tco.order_date', [$datestart, $dateend]);
                } elseif ($cond['search']['start'] == '' && $cond['search']['end'] != '') {
                    $enddate = Carbon::parse($cond['search']['end'])->format('Y-m-d');
                    $dateend = $enddate . ' 23:59:59';
                    $query->where('tco.order_date', '<', $dateend);
                } else {

                }
            }
        }

        if (isset($cond['orderId']) && $cond['orderId'] != '') {
            $query->where('tcp.order_id', '=', $cond['orderId']);
        }

        $query->join('tbl_customer as tc', 'tcp.customer_id', '=', 'tc.id');
        $query->join('tbl_equipment_model_accessories as tema', 'tema.id', '=', 'tcp.model_id');


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


    public function getOrder($orderId){

        $query = DB::table('tbl_customer_orders')->where('id','=',$orderId)->select('*')->first();
        return $query;
    }

    public function getModels($id){

        $query = DB::table('tbl_equipment_model')->where('id','=',$id)->select('*')->first();
        return $query;
    }

    public function getParts($id){

        $query = DB::table('tbl_equipment_model_spares')->where('id','=',$id)->select('*')->first();
        return $query;
    }
    public function getAccessories($id){

        $query = DB::table('tbl_equipment_model_accessories')->where('id','=',$id)->select('*')->first();
        return $query;
    }

    public function orderDetails($id){

        $query = DB::table('tbl_customer_orders')->where('id','=',$id)->select('*')->first();
        return $query;
    }

    function totalOrderItems($orderId)
    {
        DB::enableQueryLog();

        $query = DB::table('tbl_customer_products as OI');
        $query->where('OI.order_id',$orderId);
        $result = $query->count();
        return $result;
    }

    function orderItems($id)
    {
        $query = DB::table('tbl_customer_products as tcp');
        $query->join('tbl_customer_orders as to','to.id','=','tcp.order_id');

        $query->join('tbl_customer as tc','tc.id','=','to.customer_id');
        $query->where('tcp.order_id','=',$id);
        $result = $query->get();
        return $result;
    }

    public function modelOrdersDetails($id)
    {


//        DB::enableQueryLog();

        $query = DB::table('tbl_purchase_order as O');
        $query->join('tbl_customer as c', 'c.id', '=', 'O.customer_id');
        $query->join('tbl_customer_setups as s', 's.customer_id', '=', 'O.customer_id', 'left');
        $query->join('tbl_pay_method as p', 'p.id', '=', 's.pay_method', 'left');
        $query->where('O.id', $id);
        $result = $query->first();
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    public function saveorder($input)
    {

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_customer_orders')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();

            $result = DB::table('tbl_customer_orders')->insertGetId($input);
            return $result;
        }
    }


}

    
   




