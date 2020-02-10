<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Brand;
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

class BrandController extends Controller
{

    public function __construct()
    {
        $this->brand = new Brand();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Brand';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tb.*');
            $data = $this->brand->AllBrand('', '', 'tb.brand_id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tb.brand_name'));
        } else {
            $select = array('tb.*');
            $data = $this->brand->AllBrand('', '', 'tb.brand_id', 'DESC', array('select' => $select));
        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//        print_r($paginatedItems);die;
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.brand.brandlist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['brand_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['manufacturer_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';

        $select = array('tb.*','tbl_manufacturer.manufacturer_name');
        $data = $this->brand->AllBrandGrid($param['limit'], $param['offset'], 'tb.brand_id', 'DESC', array('select' => $select, 'search' => $search), false, array('tc.name', 'tc.description'));
        $count = $this->brand->AllBrandGrid('', '', 'tb.brand_id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tc.name', 'tc.description'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->brand_name;
                $values[$key]['1'] = $row->manufacturer_name;

                if ($row->is_active == 1) {

                    $values[$key]['2'] = "<span class='label label-success'>Active</span>";
                } else {
                    $values[$key]['2'] = "<span class='label label-warning'>InActive</span>";
                }

                $values[$key]['3'] = "<a href=" . url("admin/editbrand/" . $row->brand_id) . "><i class='fa fa-pencil'></a>";

                $values[$key]['4'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletebrand/' . $row->brand_id) . "
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
        $title = 'Novamed-Brand';
        $data = [
            'brand_id' => $id,
            'manufacturer_id' => isset($input['manufacturer']) ? $input['manufacturer'] : '',
            'name' => isset($input['name']) ? $input['name'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '1',
        ];
        //Manufacturer


        $manufacturer_drop = array();
        $manufacturer = DB::table('tbl_manufacturer')->select('manufacturer_name', 'manufacturer_id')->where('is_active','=',1)->get();
        $manufacturer_drop[0] = 'Select Manufacturer';
        if(count($manufacturer))
        {
            foreach($manufacturer as $key => $row){
                $manufacturer_drop[$row->manufacturer_id] = $row->manufacturer_name;
            }
        }
        //$manufacturer = DB::table('tbl_manufacturer')->pluck('manufacturer_name', 'manufacturer_id');
        //$manufacturer->prepend('Select Manufacturer', '');

        if ($id) {
            $getvalue = $this->brand->getbrand($data['brand_id']);
            if (!$getvalue) {
                return redirect('admin/brandlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['brand_id'] = $getvalue->brand_id;
                $data['manufacturer_id'] = $getvalue->manufacturer_id;
                $data['name'] = $getvalue->brand_name;

                $data['is_active'] = $getvalue->is_active;
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
            return view('master.brand.brandForm')->with('input', $data)
                ->with('title', $title)
                ->with('errors', $error)
                ->with('manufacturer',$manufacturer_drop);
        } else {
            $data = Input::all();

            $save = array();

            $save['brand_id'] = $id;
            $save['manufacturer_id'] = $data['manufacturer'];
            $save['brand_name'] = $data['name'];

            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
            $Saveresult = $this->brand->saveBrand($save);
            if($id){
                return redirect('admin/brandlist')->with('message', 'Updated Successfully');
            }else{
                return redirect('admin/brandlist')->with('message', 'Added Successfully');
            }

        }
    }


    public
    function delete($id)
    {

        $getbrand = $this->brand->getbrand($id);

        if ($getbrand) {

            $getModel = DB::table('tbl_equipment_model')->where('brand_id', '=', $id)->select('*')->first();
            if ($getModel) {
                $message = Session::flash('error', "You can't able to delete this brand");
                return redirect('admin/brandlist')->with(['error', $message], ['error', $message]);
            }

            $message = Session::flash('message', 'Deleted Successfully');
            $member = $this->brand->deletebrand($id);

            return redirect('admin/brandlist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully');
            return redirect('admin/brandlist')->with('data', $error);
        }
    }

}
