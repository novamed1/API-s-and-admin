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

class FrequencyController extends Controller
{
      public function __construct()
    {
        $this->devicemodel = new Devicemodel();
        
    }
    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Frequency';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tf.*');
            $data = $this->devicemodel->Allfrequency('', '', 'tf.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tf.name', 'tf.no_of_days'));

        } else {
            $select = array('tf.*');
            $data = $this->devicemodel->Allfrequency('', '', 'tf.id', 'DESC', array('select' => $select));

        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//        print_r($paginatedItems);die;
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.frequency.frequencylist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['no_of_days'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';

        $select = array('tf.*');
        $data = $this->devicemodel->AllfrequencyGrid($param['limit'], $param['offset'], 'tf.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tc.name', 'tc.description'));
        $count = $this->devicemodel->AllfrequencyGrid('', '', 'tf.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tc.name', 'tc.description'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = $row->no_of_days;
                $values[$key]['2'] ="<a href=".url("admin/editfrequency/".$row->id)."><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

   public function form(Request $request, $id = false)
    {
       $input = Input::all();
        $title = 'Novamed-Frequency';
       $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'day' => isset($input['day']) ? $input['day'] : '',
            
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
            
        ];
       if($id){
           $frequency = $this->devicemodel->getfrequency($data['id']);
//            echo'<pre>'; print_r($equipment);die;
            if (!$frequency) {
                return redirect('admin/frequency')->with('message', 'Sorry! Details are not found.');
            } else {
               
                $data['id'] = $frequency->id;
                $data['name'] = $frequency->name;
                $data['day'] = $frequency->no_of_days; 
                $data['is_active'] = $frequency->is_active;
       }
       }
        $rules = [
            'name' => 'required',
            'day' => 'required',
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
     return view('master.frequency.frequencyForm')->with('input', $data)->with('title', $title)->with('errors', $error);
        }else{
            $data = Input::all();
        
//          echo'<pre>';print_r($data);die;
            $save = array();
            $save['id'] = $id;
            $save['name'] = $data['name'];
            $save['no_of_days'] = $data['day'];
            if (isset($data['is_active']) ? $data['is_active'] : '0') {

                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
//           echo'<pre>'; print_r($save);die;
            $Saveresult = $this->devicemodel->savefrequency($save);
        }
        if($id){
            return redirect('admin/frequency')->with('message', 'Updated Successfully!');
        }else{
            return redirect('admin/frequency')->with('message', 'Added Successfully!');
        }
   }
}

