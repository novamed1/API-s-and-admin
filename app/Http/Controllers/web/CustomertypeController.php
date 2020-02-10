<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Customertype;
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

//use Request;

class CustomertypeController extends Controller
{

    public function __construct()
    {
        $this->customer = new Customertype();
    }

    public function index(Request $request)
    {
        $input = Input::all();

        $title = 'Novamed - Customer Type';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tc.*');
            $data = $this->customer->AllCustomer('', '', 'tc.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tc.name', 'tc.description'));
        } else {
            $select = array('tc.*');
            $data = $this->customer->AllCustomer('', '', 'tc.id', 'DESC', array('select' => $select));
        }
//echo'<pre>';print_r($data);die;

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.customertype.customertypelist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }


    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
//        $search['description'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';


        $select = array('tc.*');
        $data = $this->customer->AllCustomerTypeGrid($param['limit'], $param['offset'], 'tc.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tc.name', 'tc.description'));
        $count = $this->customer->AllCustomerTypeGrid('', '', 'tc.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tc.name', 'tc.description'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->name;
//                $values[$key]['1'] = $row->description;
                if ($row->is_active == 1) {

                    $values[$key]['1'] = "<span class='label label-success'>Active</span>";
                } else {
                    $values[$key]['1'] = "<span class='label label-warning'>InActive</span>";
                }
                $values[$key]['2'] = "<a href=" . url("admin/editcustomertype/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Customer Type Creation';
        $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'description' => isset($input['description']) ? $input['description'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '1',
        ];
        //echo'<pre>';print_r($data);die;
        if ($id) {
            $getvalue = $this->customer->getcustomer($data['id']);
            if (!$getvalue) {
                return redirect('admin/customertypelist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['name'] = $getvalue->name;
                $data['description'] = $getvalue->description;
                $data['is_active'] = $getvalue->is_active;
            }

        }

        $rules = [
            'name' => 'required'
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
            return view('master.customertype.customertypeform')->with('title', $title)->with('input', $data)->with('errors', $error);
        } else {
            $data = Input::all();
            $save = array();

            $save['id'] = $id;
            $save['name'] = $data['name'];
            $save['description'] = $data['description'];

            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
//echo'<pre>';print_r($save);die;
            $Saveresult = $this->customer->savecustomer($save);
            if ($id) {
                return redirect('admin/customertypelist')->with('message', 'Updated Successfully!');
            } else {
                return redirect('admin/customertypelist')->with('message', 'Added Successfully!');
            }

        }
    }

}
