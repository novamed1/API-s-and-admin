<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Devicemodel;
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

class DevicemodelController extends Controller
{
      public function __construct()
    {
        $this->devicemodel = new Devicemodel();
        
    }
    public function index(Request $request)
    {
        $input = Input::all();

        $title = 'Novamed-Device Model';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tm.*');
            $data = $this->devicemodel->Alldevicemodel('', '', 'tm.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tm.name', 'tm.description'));

        } else {
            $select = array('tm.*');
            $data = $this->devicemodel->Alldevicemodel('', '', 'tm.id', 'DESC', array('select' => $select));

        }
//echo'<pre>';print_r($data);die;

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//        print_r($paginatedItems);die;
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.devicemodel.devicemodellist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }
    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;

        $search['name'] = isset($input['sSearch_0'])?$input['sSearch_0']:'';
        $search['description'] = isset($input['sSearch_1'])?$input['sSearch_1']:'';

        $select = array('tm.*');
        $data = $this->devicemodel->AlldevicemodelGrid( $param['limit'], $param['offset'], 'tm.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tm.name', 'tm.description'));


        $count = $this->devicemodel->AlldevicemodelGrid('', '', 'tm.id', 'DESC', array('select' => $select, 'search' => $search,'count'=>true),
            true);
        if($data)
        { $values = array();
            $i = 0;
            foreach ($data as $key=>$row)
            {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = $row->description;
                $values[$key]['2'] = "<a href=".url('admin/editdevicemodel/'.$row->id)."><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho'=>$input['sEcho'],'iTotalRecords'=>$count,'iTotalDisplayRecords'=>$count,'aaData'=>$values));

    }

   public function form(Request $request, $id = false)
    {
       $input = Input::all();
        $title  ='Novamed-Device Model Creation';
       $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'description' => isset($input['description']) ? $input['description'] : '',
            
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '1',
            
        ];
       if($id){
           $devicemodel = $this->devicemodel->getdevice($data['id']);
//            echo'<pre>'; print_r($equipment);die;
            if (!$devicemodel) {
                return redirect('admin/devicemodellist')->with('message', 'Sorry! Details are not found.');
            } else {
               
                $data['id'] = $devicemodel->id;
                $data['name'] = $devicemodel->name;
                $data['description'] = $devicemodel->description;
                $data['is_active'] = $devicemodel->is_active;
       }
       }
        $rules = [
            'name' => 'required',
//            'description' => 'required',
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
     return view('master.devicemodel.devicemodelForm')->with('input', $data)->with('title', $title)->with('errors', $error);
        }else{
            $data = Input::all();
        
//          echo'<pre>';print_r($data);die;
            $save = array();
            $save['id'] = $id;
            $save['name'] = $data['name'];
            $save['description'] = $data['description'];
            if (isset($data['is_active']) ? $data['is_active'] : '0') {

                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
//           echo'<pre>'; print_r($save);die;
            $Saveresult = $this->devicemodel->saveDevicemodel($save);
        }
        if($id){
            return redirect('admin/devicemodellist')->with('message', 'Updated Successfully');
        }else{
            return redirect('admin/devicemodellist')->with('message', 'Added Successfully');
        }

   }
}
