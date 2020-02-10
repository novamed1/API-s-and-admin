<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Operation;
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

class OperationController extends Controller
{

    public function __Construct(){
        $this->operation =new Operation();
    }
    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Operation';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('to.*');
            $data = $this->operation->AllOperation('', '', 'to.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('to.operation_name'));
        } else {
            $select = array('to.*');
            $data = $this->operation->AllOperation('', '', 'to.id', 'DESC', array('select' => $select));
        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.operation.operationlist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function listData(Request $request)
    {
        $input = Input::all();

        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['operation_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';

        $data = $this->operation->AllOperationGrid($param['limit'], $param['offset'], 'to.id', 'DESC', array('search' => $search), false, array('to.operation_name'));
        $count = $this->operation->AllOperationGrid('', '', 'to.id', 'DESC', array('search' => $search, 'count' => true), true, array('to.operation_name'));

        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->operation_name;
                if ($row->is_active == 1) {

                    $values[$key]['1'] = "<span class='label label-success'>Active</span>";
                } else {
                    $values[$key]['1'] = "<span class='label label-warning'>InActive</span>";
                }

                $values[$key]['2'] = "<a href=" . url("admin/editoperation/" . $row->id) . "><i class='fa fa-pencil'></a>";

                $values[$key]['3'] = " <a href='javascript:void(0)' data-src=" . url('admin/deleteoperation/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
                $i++;
            }

        }
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Operation';
        $data = [
            'id' => $id,
            'operation_name' => isset($input['operation_name']) ? $input['operation_name'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
        ];

        if ($id) {
            $getvalue = $this->operation->getoperation($data['id']);
            if (!$getvalue) {
                return redirect('admin/operationlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['operation_name'] = $getvalue->operation_name;
                $data['is_active'] = $getvalue->is_active;
            }
        }
        $rules = [
            'operation_name' => 'required',
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
            return view('master.operation.operationForm')->with('input', $data)
                ->with('title', $title)
                ->with('errors', $error);
        } else {
            $data = Input::all();

            $save = array();

            $save['id'] = $id;
            $save['operation_name'] = $data['operation_name'];

            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
            $Saveresult = $this->operation->saveOperation($save);
            if($id){
                return redirect('admin/operationlist')->with('message', 'Updated Successfully');
            }else{
                return redirect('admin/operationlist')->with('message', 'Added Successfully');
            }

        }
    }


    public
    function delete($id)
    {

        $getoperation = $this->operation->getoperation($id);

        if ($getoperation) {

            $getModel = DB::table('tbl_equipment_model')->where('brand_operation', '=', $id)->select('*')->first();
            if ($getModel) {
                $message = Session::flash('error', "You can't able to delete this Operation");
                return redirect('admin/operationlist')->with(['error', $message], ['error', $message]);
            }

            $message = Session::flash('message', 'Deleted Successfully');
            $delete = $this->operation->deleteoperation($id);

            return redirect('admin/operationlist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully');
            return redirect('admin/operationlist')->with('data', $error);
        }
    }

}

