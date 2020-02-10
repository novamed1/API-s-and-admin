<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\ProductType;
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

class ProductTypeController extends Controller
{

    public function __construct()
    {
        $this->product = new ProductType();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-ProductType';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tp.*');
            $data = $this->product->AllProductType('', '', 'tp.product_type_id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tp.product_type_name'));
        } else {
            $select = array('tp.*');
            $data = $this->product->AllProductType('', '', 'tp.product_type_id', 'DESC', array('select' => $select));
        }


        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//        print_r($paginatedItems);die;
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.producttype.producttypelist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['product_type_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';

        $select = array('tp.*');
        $data = $this->product->AllProductTypeGrid($param['limit'], $param['offset'], 'tp.product_type_id', 'DESC', array('select' => $select, 'search' => $search), false, array('tc.name', 'tc.description'));
        $count = $this->product->AllProductTypeGrid('', '', 'tp.product_type_id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tc.name', 'tc.description'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->product_type_name;

                if ($row->is_active == 1) {

                    $values[$key]['1'] = "<span class='label label-success'>Active</span>";
                } else {
                    $values[$key]['1'] = "<span class='label label-warning'>InActive</span>";
                }

                $values[$key]['2'] = "<a href=" . url("admin/editproduct/" . $row->product_type_id) . "><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-ProductType Creation';
        $data = [
            'product_type_id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
        ];


        if ($id) {
            $getproduct = $this->product->getproduct($data['product_type_id']);
            if (!$getproduct) {
                return redirect('admin/productlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['product_type_id'] = $getproduct->product_type_id;
                $data['name'] = $getproduct->product_type_name;

                $data['is_active'] = $getproduct->is_active;
            }
        }
        $rules = [
            'name' => 'required',

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
            return view('master.producttype.producttypeForm')->with('title', $title)->with('input', $data)->with('errors', $error);
        } else {
            $data = Input::all();

            $save = array();

            $save['product_type_id'] = $id;
            $save['product_type_name'] = $data['name'];

            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
            $Saveresult = $this->product->saveProduct($save);
            return redirect('admin/productlist')->with('message', 'Added Successfully!');
        }
    }
}


