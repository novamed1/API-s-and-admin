<?php

namespace App\Http\Controllers\web\developersettings;

use App\Http\Controllers\Controller;

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
use App\Developer;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->developer = new Developer();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Developer Settings';


        $temp = array();





        return view('developer.permission')->with('title', $title);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['p.name'] = isset($input['sSearch_0'])?$input['sSearch_0']:'';
        $search['p.display_name'] = isset($input['sSearch_1'])?$input['sSearch_1']:'';
        $search['m.name'] = isset($input['sSearch_2'])?$input['sSearch_2']:'';


        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('p.*','m.name as menuName');
        $data = $this->developer->AllPermissionGrid($param['limit'], $param['offset'], 'p.id', 'DESC', array('select' => $select, 'search' => $search),
            false);

        $count = $this->developer->AllPermissionGrid($param['limit'], $param['offset'], 'p.id', 'DESC', array('select' => $select, 'search' => $search,'count'=>true),
            true);
        if($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key=>$row)
            {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = $row->display_name;
                $values[$key]['2'] = $row->menuName;
                if ($row->is_active == 1) {

                    $values[$key]['3'] = "<span class='label label-success'>Active</span>";
                } else {
                    $values[$key]['3'] = "<span class='label label-warning'>InActive</span>";
                }
                $values[$key]['4'] = "<a href=".url('admin/editPermission/'.$row->id)." class=''><i class='fa fa-pencil'></a>";
//                $values[$key]['5'] = "<a href=".url('admin/deletePermission/'.$row->id)." class=''><i class='fa fa-trash'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho'=>$input['sEcho'],'iTotalRecords'=>$count,'iTotalDisplayRecords'=>$count,'aaData'=>$values));

    }




    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Developer Permission Creation';
        $menus = DB::table('tbl_menus')->pluck('name', 'id');
        $menus->prepend('Select Menu', '');
        $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'alias_name' => isset($input['alias_name']) ? $input['alias_name'] : '',
            'menu' => isset($input['menu']) ? $input['menu'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
        ];

        if ($id) {
            $getvalue = $this->developer->getpermission($data['id']);
//            print_r($getvalue);exit;
            if (!$getvalue) {
                return redirect('admin/permissionlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['name'] = $getvalue->name;
                $data['alias_name'] = $getvalue->display_name;
                $data['menu'] = $getvalue->menu;
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
            return view('developer.permissionform')->with('title', $title)->with('input', $data)->with('errors', $error)->with('menus', $menus);
        } else {
//            $data = Input::all();
            $save = array();

            $save['id'] = $id;
            $save['name'] = $input['name'];
            $save['display_name'] = $input['alias_name'];
            $save['menu'] = $input['menu'];
            if (isset($input['is_active']) ? $input['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }

            $Saveresult = $this->developer->savepermission($save);
            if ($id) {
                return redirect('admin/permissionlist')->with('message', 'Updated Successfully!');
            } else {
                return redirect('admin/permissionlist')->with('message', 'Added Successfully!');
            }

        }
    }


//    public function deletePermission($id)
//    {
//        $getpermission = $this->developer->getpermission($id);
//
//        if ($getpermission) {
//
//            $delete = $this->developer->deletepermission($id);
//            $message = Session::flash('message', 'Deleted Successfully!');
//
//            return redirect('admin/permissionlist')->with(['data', $message], ['message', $message]);
//        } else {
//            $error = Session::flash('message', 'Deleted not successfully!');
//            return redirect('admin/permissionlist')->with('data', $error);
//        }
//    }
}
