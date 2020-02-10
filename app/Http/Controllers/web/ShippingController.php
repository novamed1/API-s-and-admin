<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;

class ShippingController extends Controller
{

    public function __construct()
    {
        $this->shipping = new Shipping();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Shipping';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('ts.*');
            $data = $this->shipping->AllShipping('', '', 'ts.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('ts.name'));
        } else {
            $select = array('ts.*');
            $data = $this->shipping->AllShipping('', '', 'ts.id', 'DESC', array('select' => $select));
        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//        print_r($paginatedItems);die;
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.shipping.shippinglistdetails')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['shipping_charge'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';

//        echo '<pre>';print_r($search);exit;
        $select = array('ts.*');
        $data = $this->shipping->AllShippingGrid($param['limit'], $param['offset'], 'ts.id', 'DESC', array('select' => $select, 'search' => $search), false, array('ts.name', 'ts.shipping_charge'));
        $count = $this->shipping->AllShippingGrid('', '', 'ts.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('ts.name', 'ts.shipping_charge'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = '$'.$row->shipping_charge;

                if ($row->is_active == 1) {

                    $values[$key]['2'] = "<span class='label label-success'>Active</span>";
                } else {
                    $values[$key]['2'] = "<span class='label label-warning'>InActive</span>";
                }

                $values[$key]['3'] = "<a href=" . url("admin/editshipping/" . $row->id) . "><i class='fa fa-pencil'></a>";

                $values[$key]['4'] = " <a href='javascript:void(0)' data-src=" . url('admin/deleteshipping/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Shipping';
        $data = [
            'id' => $id,
            'shipping_charge' => isset($input['shipping_charge']) ? $input['shipping_charge'] : '',
            'name' => isset($input['name']) ? $input['name'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '1',
        ];

        if ($id) {
            $getvalue = $this->shipping->getshipping($data['id']);
            if (!$getvalue) {
                return redirect('admin/shippinglist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['name'] = $getvalue->name;
                $data['shipping_charge'] = $getvalue->shipping_charge;
                $data['is_active'] = $getvalue->is_active;
            }
        }
        $rules = [
            'name' => 'required',
            'shipping_charge' => 'required',
        ];
        $error = array();

        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($data, $rules);
            if ($checkValid->fails()) {
                $checkStatus = true;
                $error = $checkValid->errors()->all();
            }
        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
        }
        if ($checkStatus) {
            return view('master.shipping.shippingForm')->with('input', $data)
                ->with('title', $title)
                ->with('errors', $error);
        } else {
            $data = Input::all();

            $save = array();

            $save['id'] = $id;
            $save['name'] = $data['name'];
            $save['shipping_charge'] = $data['shipping_charge'];

            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
            $Saveresult = $this->shipping->saveShipping($save);
            if($id){
                return redirect('admin/shippinglist')->with('message', 'Updated Successfully');
            }else{
                return redirect('admin/shippinglist')->with('message', 'Added Successfully');
            }

        }
    }


    public
    function delete($id)
    {

        $getshipping = $this->shipping->getshipping($id);

        if ($getshipping) {

            $getShipping = DB::table('tbl_customer_setups')->where('shipping', '=', $id)->select('*')->first();
            if ($getShipping) {
                $message = Session::flash('error', "You can't able to delete this Shipping Name");
                return redirect('admin/shippinglist')->with(['error', $message], ['error', $message]);
            }

            $message = Session::flash('message', 'Deleted Successfully!');
            $member = $this->shipping->deletebrand($id);

            return redirect('admin/shippinglist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/shippinglist')->with('data', $error);
        }
    }

}
