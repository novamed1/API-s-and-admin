<?php

namespace App\Http\Controllers\web\orders;


use App\Http\Controllers\Controller;
use App\Models\Devicemodel;
use App\Models\Sentinel\User;
use App\Models\Order;
use App\Models\Equipment;
use App\Models\Payment;

use App\Models\Customer;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use DateInterval;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use PDF;
use Mail;


class InvoiceController extends controller
{
    public function __construct()
    {

        $this->equipment = new Equipment();
        $this->frequency = new Devicemodel();
        $this->order = new Order();
        $this->customer = new Customer();
        $this->payment = new Payment();
    }

    public function index(Request $request, $id)
    {

        $get = Input::all();
//        $comments['comments'] = $get['comments'];
        DB::table('tbl_customer_orders')->where('id', $get['id'])->update([
            'comments' =>$get['comments'],
        ]);
//        DB::table('tbl_customer_orders')->where('id', $get['id'])->update([
//            'shipping_price' =>$get['shipping_cost'],
//            'sales_tax_price' =>$get['service_tax'],
//            'grand_total'   =>$get['total'],
//            ]);

        $purchase = $this->payment->customerOrders($id);
//        echo '<pre>';print_r($get);die;
        $temp = array();
        // print_r($purchase);die;
        if ($purchase) {
            $billingAddress = $this->customer->getCustomerBilling($purchase->customer_id);
            $shippingAddress = $this->customer->getCustomerShipping($purchase->customer_id);
            $totalReturned = $this->order->totalOrderItems($id);
            $setups = $this->payment->setups($purchase->customer_id);
            $select = array('OI.order_item_amt', 'E.asset_no', 'E.serial_no', 'E.pref_contact',
                'E.pref_tel', 'E.pref_email', 'E.location',
                'EM.model_description');
            $orderItems = $this->order->orderItems($id, array('select' => $select));
//            echo '<pre>';print_r($orderItems);die;
            if ($orderItems) {
                foreach ($orderItems as $key => $row) {
                    if ($row->type == 'model') {
                        $detail = $this->order->getModels($row->model_id);
                        $name = $detail->model_description;
                        $price = $detail->model_price;
                        $type = 'Models';
                    } elseif ($row->type == 'parts') {
                        $detail = $this->order->getParts($row->model_id);
                        $name = $detail->part_name;
                        $price = $detail->price;
                        $type = 'Parts';
                    } else {
                        $detail = $this->order->getAccessories($row->model_id);
                        $name = $detail->accessories_name;
                        $price = $detail->price;
                        $type = 'Accessories';
                    }
                    $temp[$key] = $row;
                    $temp[$key]->name = $name;
                    $temp[$key]->price = $price;
                    $temp[$key]->type = $type;
                    $totalCost = $row->totalcost;

                }
            }

//            echo '<pre>';
//            print_r($temp);
//            '</pre>';
//            die;
        }
        $path = base_path() . '/public/orders/invoices';


        $view['purchase'] = $purchase;
        $view['billing'] = $billingAddress;
        $view['shipping'] = $shippingAddress;
//        $view['shipping_price'] = isset($get['shipping_cost']) ? $get['shipping_cost'] : '';
//        $view['sales_tax_price'] =isset($get['service_tax']) ? $get['service_tax'] : '';
//        $view['grand_total'] = isset($get['total'] ) ? $get['total'] : '';
        view()->share('data', $view);
        view()->share('comments', $get['comments']);
        view()->share('orderItems', $temp);
        view()->share('totalCost', $totalCost);
        $pdf = PDF::loadView('orders.invoice')
            ->save($path . '/' . $purchase->order_number . '.pdf', 'F');

        $save['id'] = $get['id'];
        $save['invoice_generation'] = 1;
        $save['invoice_name'] = $purchase->order_number . '.pdf';
//        print_r($save);die;
        $this->order->saveorder($save);


        $pathToFile = base_path() . '/public/orders/invoices/' . $purchase->order_number . '.pdf';
        $query = DB::table('tbl_email_template');
        $query->where('tplid', '=', 2);
        $result = $query->first();

        $result->tplmessage = str_replace('{name}', $billingAddress->billing_contact, $result->tplmessage);

        $param['message'] = $result->tplmessage;
        $param['name'] = $billingAddress->billing_contact;
        $param['title'] = $result->tplsubject;

        $data = array('data'=>$param);
//        if($billingAddress->email)
//        {
//            Mail::send(['html' => 'email/template'], ['data' => $param], function($message) use ($param,$billingAddress,$pathToFile) {
//                $message->to($billingAddress->email)->subject
//                ($param['title']);
//                $message->attach($pathToFile,['as' => 'Serviceinvoice.pdf', 'mime' => 'pdf']);
//            });
//        }
        return redirect()->back()->with('message','Invoice Generated Successfully');
//        return response()->file($pathToFile);


    }


    function openinvoice(Request $request, $id)
    {
        if ($id) {
            $order = DB::table('tbl_customer_orders')->where('id', $id)->select('*')->first();
            if ($order) {
                $pathToFile = base_path() . '/public/orders/invoices/' . $order->invoice_name;
//                return response()->download(public_path('orders/invoices/' . $order->invoice_name));
                return response()->file($pathToFile);
            }
        }
    }


}