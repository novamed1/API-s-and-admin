<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Serviceplantype;
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

class ServiceplantypeController extends Controller
{

    public function __construct()
    {
        $this->type = new Serviceplantype();
    }

    public function index(Request $request)
    {
        $input = Input::all();

        $title = 'Novamed - Service Plan Type';
        return view('master.serviceplantype.serviceplantypelist')->with('title', $title);
    }


    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
//        $search['description'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';

        $select = array('tp.*');
        $data = $this->type->plantypeGrid($param['limit'], $param['offset'], 'tp.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tp.name', 'tp.description'));
        $count = $this->type->plantypeGrid('', '', 'tp.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tp.name', 'tp.description'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->name;
//                $values[$key]['1'] = $row->description;
                $values[$key]['1'] = "<a href=" . url("admin/editserviceplantype/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Service Plan Type';
        $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'description' => isset($input['description']) ? $input['description'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
        ];
        //echo'<pre>';print_r($data);die;
        if ($id) {
            $getvalue = $this->type->getserviceplantype($data['id']);
            if (!$getvalue) {
                return redirect('admin/serviceplantypelist')->with('message', 'Sorry! Details are not found.');
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
            return view('master.serviceplantype.serviceplantypeform')->with('title', $title)->with('input', $data)->with('errors', $error);
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

            $Saveresult = $this->type->saveserviceplantype($save);
            if ($id) {
                return redirect('admin/serviceType')->with('message', 'Updated Successfully!');
            } else {
                return redirect('admin/serviceType')->with('message', 'Added Successfully!');
            }

        }
    }

}
