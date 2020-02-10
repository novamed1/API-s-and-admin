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

class MenuController extends Controller
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





        return view('developer.menus')->with('title', $title);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['m.name'] = isset($input['sSearch_0'])?$input['sSearch_0']:'';
        $search['m.icon'] = isset($input['sSearch_1'])?$input['sSearch_1']:'';
        $search['m.order'] = isset($input['sSearch_2'])?$input['sSearch_2']:'';

        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('m.*');
        $data = $this->developer->AllMenuGrid($param['limit'], $param['offset'], 'm.id', 'DESC', array('select' => $select, 'search' => $search),
            false);

        $count = $this->developer->AllMenuGrid($param['limit'], $param['offset'], 'm.id', 'DESC', array('select' => $select, 'search' => $search,'count'=>true),
            true);
        if($data)
        { $values = array();
            $i = 0;
            foreach ($data as $key=>$row)
            {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = $row->icon;
                $values[$key]['2'] = $row->order;
                $values[$key]['3'] = "<a href=".url('admin/editMenu/'.$row->id)." class=''><i class='fa fa-pencil'></a>";
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
        $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'icon' => isset($input['icon']) ? $input['icon'] : '',
            'order' => isset($input['order']) ? $input['order'] : '',
        ];

        if ($id) {
            $getvalue = $this->developer->getmenu($data['id']);
            if (!$getvalue) {
                return redirect('admin/menulist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['name'] = $getvalue->name;
                $data['icon'] = $getvalue->icon;
                $data['order'] = $getvalue->order;
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
            return view('developer.menuform')->with('title', $title)->with('input', $data)->with('errors', $error);
        } else {
            //$data = Input::all();
            $save = array();

            $save['id'] = $id;
            $save['name'] = $input['name'];
            $save['icon'] = $input['icon'];
            $save['order'] = $input['order'];

            $Saveresult = $this->developer->savemenu($save);
            if ($id) {
                return redirect('admin/menulist')->with('message', 'Updated Successfully!');
            } else {
                return redirect('admin/menulist')->with('message', 'Added Successfully!');
            }

        }
    }
}
