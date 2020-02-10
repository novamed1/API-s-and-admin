<?php

namespace App\Http\Controllers\web\orders;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductType;
use App\Models\Sentinel\User;
use App\Models\Equipment;
use App\Models\ServicePlan;
use App\Models\Testplan;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use File;
use Image;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;

//use Request;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->equipment = new Equipment();
        $this->order = new Order();
        $this->customer = new Customer();

    }

    public function customerOrders(Request $request)

    {

        $input = Input::all();

        $title = 'Novamed-Customer Orders';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $startdate = isset($input['startdate']) ? $input['startdate'] : '';
        $enddate = isset($input['enddate']) ? $input['enddate'] : '';
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;

        if ($keyword != "" || $startdate != '' || $enddate != '') {

            $search['search']['keyword'] = $keyword;
            $search['search']['start'] = $startdate;
            $search['search']['end'] = $enddate;

            $data['search']['keyword'] = $keyword;
            $select = array('tco.*', 'tc.customer_name');
            $data = $this->order->AllOrder($perPage, $offset, 'tco.id', 'DESC', array('select' => $select,
                'orderType' => '1', 'search' => $search['search']), false, array('tco.order_number','tc.customer_name'));
            $dataCount = $this->order->AllOrder('', '', 'tco.id', 'DESC', array('select' => $select, 'orderType' => '1', 'search' => $search['search']), true, array('tco.order_number', 'tc.customer_name', 'tco.totalcost'));

        } else {
            $select = array('tco.*', 'tc.customer_name');
            $data = $this->order->AllOrder($perPage, $offset, 'tco.id', 'DESC', array('select' => $select, 'orderType' => '1'));
            $dataCount = $this->order->AllOrder('', '', 'tco.id', 'DESC', array('select' => $select, 'orderType' => '1'), true);

        }


        $temp = array();
        if (!$data->isEmpty()) {
            foreach ($data as $datakey => $dataval) {
                $temp[$datakey]['orderId'] = $dataval->id;
                $temp[$datakey]['orderNumber'] = $dataval->order_number;
                $temp[$datakey]['customerId'] = $dataval->customer_id;
                $temp[$datakey]['customerName'] = $dataval->customer_name;
                $orderProducts = $dataval->order_products;

                $temp[$datakey]['orderDate'] = $dataval->order_date;
                $temp[$datakey]['paymentStatus'] = $dataval->order_payment_status;
                $temp[$datakey]['totalCost'] = $dataval->totalcost;
                $temp[$datakey]['paymentDate'] = $dataval->payment_date;
            }

        }


        $itemCollection = collect($temp);
        $paginatedItems = new LengthAwarePaginator($itemCollection, $dataCount, $perPage);
        $paginatedItems->setPath($request->url());

        return view('orders.customerOrders')->with('data', $paginatedItems)->with('startdate', $startdate)->with('enddate', $enddate)->with('keyword', $keyword)->with('title', $title);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['tco.order_number'] = isset($input['sSearch_0'])?$input['sSearch_0']:'';
        $search['tc.customer_name'] = isset($input['sSearch_1'])?$input['sSearch_1']:'';
        $search['tco.totalcost'] = isset($input['sSearch_2'])?$input['sSearch_2']:'';
        $search['DATE_FORMAT(`tco`.`created_date`,"%d-%M-%Y")'] = isset($input['sSearch_3'])?$input['sSearch_3']:'';


        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('tco.*', 'tc.customer_name');
        $data = $this->order->AllOrderGrid($param['limit'], $param['offset'], 'tco.id', 'DESC', array('select' => $select, 'search' => $search,'admin'=>1),
            false);

        $count = $this->order->AllOrderGrid($param['limit'], $param['offset'], 'tco.id', 'DESC', array('select' => $select, 'search' => $search,'admin'=>1,'count'=>true),
            true);

//        echo '<pre>';print_r($data);die;


        if($data)
        { $values = array();
            $i = 0;
            foreach ($data as $key=>$row)
            {
                $values[$key]['0'] = $row->order_number;
                $values[$key]['1'] = $row->customer_name;
//                if($row->grand_total){
//                    $values[$key]['2'] = '$'.$row->grand_total;
//                }else{
                    $values[$key]['2'] = '$'.$row->totalcost;
//                }
                $values[$key]['3'] = date('d-M-Y',strtotime(str_replace('/','-',$row->created_date)));
                $values[$key]['4'] = "<a href=".url('admin/customerOrderViewDetails/'.$row->id.'/'.$row->customer_id.'')." class='btn btn-space btn-primary'><i class='fa fa-eye'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho'=>$input['sEcho'],'iTotalRecords'=>$count,'iTotalDisplayRecords'=>$count,'aaData'=>$values));

    }

    public function customerpartOrders(Request $request)

    {

        $input = Input::all();

        $title = 'Novamed-Customer Part Orders';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $startdate = isset($input['startdate']) ? $input['startdate'] : '';
        $enddate = isset($input['enddate']) ? $input['enddate'] : '';
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;

        if ($keyword != "" || $startdate != '' || $enddate != '') {

            $search['search']['keyword'] = $keyword;
            $search['search']['start'] = $startdate;
            $search['search']['end'] = $enddate;

            $data['search']['keyword'] = $keyword;
            $select = array('tco.*', 'tc.customer_name');
            $data = $this->order->AllOrder($perPage, $offset, 'tco.id', 'DESC', array('orderType' => '2',
                'select' => $select, 'search' => $search['search']), false, array('tco.order_number','tc.customer_name'));
            $dataCount = $this->order->AllOrder('', '', 'tco.id', 'DESC', array('orderType' => '2', 'select' => $select, 'search' => $search['search']), true, array('tco.order_number', 'tc.customer_name', 'tco.totalcost'));

        } else {
            $select = array('tco.*', 'tc.customer_name');
            $data = $this->order->AllOrder($perPage, $offset, 'tco.id', 'DESC', array('orderType' => '2', 'select' => $select));
            $dataCount = $this->order->AllOrder('', '', 'tco.id', 'DESC', array('orderType' => '2', 'select' => $select), true);


        }


        $temp = array();
        if (!$data->isEmpty()) {
            foreach ($data as $datakey => $dataval) {
                $temp[$datakey]['orderId'] = $dataval->id;
                $temp[$datakey]['orderNumber'] = $dataval->order_number;
                $temp[$datakey]['customerId'] = $dataval->customer_id;
                $temp[$datakey]['customerName'] = $dataval->customer_name;
                $orderProducts = $dataval->order_products;

                $temp[$datakey]['orderDate'] = $dataval->order_date;
                $temp[$datakey]['paymentStatus'] = $dataval->order_payment_status;
                $temp[$datakey]['totalCost'] = $dataval->totalcost;
                $temp[$datakey]['paymentDate'] = $dataval->payment_date;
            }

        }


        $itemCollection = collect($temp);
        $paginatedItems = new LengthAwarePaginator($itemCollection, $dataCount, $perPage);
        $paginatedItems->setPath($request->url());

        return view('orders.customerpartOrders')->with('data', $paginatedItems)->with('startdate', $startdate)->with('enddate', $enddate)->with('keyword', $keyword)->with('title', $title);
    }

    public function customerAccessoryOrders(Request $request)

    {

        $input = Input::all();

        $title = 'Novamed-Customer Part Orders';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $startdate = isset($input['startdate']) ? $input['startdate'] : '';
        $enddate = isset($input['enddate']) ? $input['enddate'] : '';
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;

        if ($keyword != "" || $startdate != '' || $enddate != '') {

            $search['search']['keyword'] = $keyword;
            $search['search']['start'] = $startdate;
            $search['search']['end'] = $enddate;

            $data['search']['keyword'] = $keyword;
            $select = array('tco.*', 'tc.customer_name');
            $data = $this->order->AllOrder($perPage, $offset, 'tco.id', 'DESC', array('orderType' => '3',
                'select' => $select, 'search' => $search['search']), false, array('tco.order_number','tc.customer_name'));
            $dataCount = $this->order->AllOrder('', '', 'tco.id', 'DESC', array('orderType' => '3', 'select' => $select, 'search' => $search['search']), true, array('tco.order_number', 'tc.customer_name', 'tco.totalcost'));

        } else {
            $select = array('tco.*', 'tc.customer_name');
            $data = $this->order->AllOrder($perPage, $offset, 'tco.id', 'DESC', array('orderType' => '3',
                'select' => $select));
            $dataCount = $this->order->AllOrder('', '', 'tco.id', 'DESC', array('orderType' => '3', 'select' => $select), true);

        }


        $temp = array();
        if (!$data->isEmpty()) {
            foreach ($data as $datakey => $dataval) {
                $temp[$datakey]['orderId'] = $dataval->id;
                $temp[$datakey]['orderNumber'] = $dataval->order_number;
                $temp[$datakey]['customerId'] = $dataval->customer_id;
                $temp[$datakey]['customerName'] = $dataval->customer_name;
                $orderProducts = $dataval->order_products;

                $temp[$datakey]['orderDate'] = $dataval->order_date;
                $temp[$datakey]['paymentStatus'] = $dataval->order_payment_status;
                $temp[$datakey]['totalCost'] = $dataval->totalcost;
                $temp[$datakey]['paymentDate'] = $dataval->payment_date;
            }

        }


        $itemCollection = collect($temp);
        $paginatedItems = new LengthAwarePaginator($itemCollection, $dataCount, $perPage);
        $paginatedItems->setPath($request->url());

        return view('orders.customeraccessoryOrders')->with('data', $paginatedItems)->with('startdate', $startdate)->with('enddate', $enddate)->with('keyword', $keyword)->with('title', $title);
    }

    public function customerOrderViewDetails(Request $request, $orderId, $customerId)
    {

        $title = 'Customer Order Products';
        $temp = array();
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;
        $select = array('tcp.*', 'tc.customer_name');

        $data = $this->order->AllProducts($perPage, $offset, 'tcp.id', 'DESC', array('select' => $select, 'orderId' => $orderId), false);
        $dataCount = $this->order->AllProducts('', '', 'tcp.id', 'DESC', array('select' => $select, 'orderId' => $orderId), true);

       // echo '<pre>';print_r($data);die;
//        getorder

        $orderdetails = $this->order->getOrder($orderId);
        //            for customer info
        $customer['getCustomer'] = $this->customer->getCustomer($customerId);
//            for customer billing details
        $customer['getCustomerBilling'] = $this->customer->getCustomerBilling($customerId);
//            for customer shipping details
        $customer['getCustomerShipping'] = $this->customer->getCustomerShipping($customerId);

        if($data)
        {
               foreach ($data as $key=>$row)
               {
                   if($row->type =='model')
                   {
                       $detail = $this->order->getModels($row->model_id);
                       $name = $detail->model_name;
                       $type = 'Models';
                   }
                   elseif($row->type =='parts')
                   {
                       $detail = $this->order->getParts($row->model_id);
                       $name = $detail->part_name;
                       $type = 'Parts';
                   }
                   else
                   {
                       $detail = $this->order->getAccessories($row->model_id);
                       $name = $detail->accessories_name;
                       $type = 'Accessories';
                   }
                   $temp[$key] = $row;
                   $temp[$key]->name = $name;
                   $temp[$key]->type = $type;
               }
        }
        $totalamount = 0;
        foreach($temp as $row){
            $totalamount = $row->total_price + $totalamount;
        }

        $itemCollection = collect($temp);
        $paginatedItems = new LengthAwarePaginator($itemCollection, $dataCount, $perPage);
        $paginatedItems->setPath($request->url());

//        echo '<prE>';print_r($orderdetails);die;

        return view('orders.orderviewdetails')->with('totalamount', $totalamount)->with('data', $paginatedItems)->with('title', $title)->with('orderdetails', $orderdetails)->with('customerinfo', $customer);
//        return view('orders.orderviewdetails')->with('data', $paginatedItems)->with('title', $title)->with('orderdetails', $orderdetails)->with('customerinfo', $customer);


    }

    public function customerpartOrderViewDetails(Request $request, $orderId, $customerId)
    {

        $title = 'Customer Order Products';
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;
        $select = array('tcp.*', 'tc.customer_name', 'tems.part_name');

        $data = $this->order->AllPartProducts($perPage, $offset, 'tcp.id', 'DESC', array('select' => $select, 'orderId' => $orderId), false);
        $dataCount = $this->order->AllPartProducts('', '', 'tcp.id', 'DESC', array('select' => $select, 'orderId' => $orderId), true);

//        echo '<pre>';print_r($data);die/;
//        getorder

        $orderdetails = $this->order->getOrder($orderId);
        //            for customer info
        $customer['getCustomer'] = $this->customer->getCustomer($customerId);
//            for customer billing details
        $customer['getCustomerBilling'] = $this->customer->getCustomerBilling($customerId);
//            for customer shipping details
        $customer['getCustomerShipping'] = $this->customer->getCustomerShipping($customerId);

        $itemCollection = collect($data);
        $paginatedItems = new LengthAwarePaginator($itemCollection, $dataCount, $perPage);
        $paginatedItems->setPath($request->url());

//        echo '<prE>';print_r($orderdetails);die;

        return view('orders.orderpartviewdetails')->with('data', $paginatedItems)->with('title', $title)->with('orderdetails', $orderdetails)->with('customerinfo', $customer);


    }


    public function customerAccOrderViewDetails(Request $request, $orderId, $customerId)
    {

        $title = 'Customer Order Products';
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $offset = ($currentPage * $perPage) - $perPage;
        $select = array('tcp.*', 'tc.customer_name', 'tema.accessories_name');

        $data = $this->order->AllAccessoryProducts($perPage, $offset, 'tcp.id', 'DESC', array('select' => $select, 'orderId' => $orderId), false);
        $dataCount = $this->order->AllAccessoryProducts('', '', 'tcp.id', 'DESC', array('select' => $select, 'orderId' => $orderId), true);

//        echo '<pre>';print_r($data);die/;
//        getorder

        $orderdetails = $this->order->getOrder($orderId);
        //            for customer info
        $customer['getCustomer'] = $this->customer->getCustomer($customerId);
//            for customer billing details
        $customer['getCustomerBilling'] = $this->customer->getCustomerBilling($customerId);
//            for customer shipping details
        $customer['getCustomerShipping'] = $this->customer->getCustomerShipping($customerId);

        $itemCollection = collect($data);
        $paginatedItems = new LengthAwarePaginator($itemCollection, $dataCount, $perPage);
        $paginatedItems->setPath($request->url());


        return view('orders.orderaccviewdetails')->with('data', $paginatedItems)->with('title', $title)->with('orderdetails', $orderdetails)->with('customerinfo', $customer);


    }


}
