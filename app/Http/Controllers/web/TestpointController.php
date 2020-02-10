<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Testpoint;
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
use Image;
use Illuminate\Support\Facades\Redirect;

class TestpointController extends Controller
{

    public function __construct()
    {
        $this->testpoint = new Testpoint();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Test Point';
        // print_r($title);die;

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';

        return view('master.testpoint.testpoint')->with('title', $title)->with('keyword', $keyword);
    }




  public function form(Request $request,$id=false)
  {

   // print_r($id);die;
   $title = "Add Test Point";
   $data['id'] = '';
   $data['name'] = '';
   if(isset($id) && $id!='')
   {
    $testpoint = $this->testpoint->gettestpoint($id);
    $data['id'] = $id;
    $data['name'] = $testpoint->name;

   }

   return view('master.testpoint.addtestpoint')->with('title', $title)->with('input',$data);

  }




    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
      
       // echo '<pre>';print_r($param);die;

        $select = array('tp.*');
        $data = $this->testpoint->AllTestPointGrid($param['limit'], $param['offset'], 'tp.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tp.id,tp.name'));
        $count = $this->testpoint->AllTestPointGrid('', '', 'tp.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tp.id,tp.name'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = "<a href=" . url("admin/edittestpoint/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $values[$key]['2'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletetestpoint/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


  public function savetestpoint(Request $request,$id=false)
  {


   $input = Input::all();

   $id =  isset($id)?$id:'';

    // print_r($input);die;

     $rules = [
            'name'=>'required|unique:tbl_test_points,name,' . $id . ',id',
          
        ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->WithInput();
        }

  $saveState = array();
  $saveState['id'] = $id;
  $saveState['name'] = $input['name'];
  $testpoint = $this->testpoint->savetestpoint($saveState);
  if($testpoint)
  {
    $title = "Test Point";
    return redirect('admin/testpointlist')->with('message', 'Created Successfully!');
  }

  }



     public function delete($id)
    {

        $gettestpoint = $this->testpoint->gettestpoint($id);

        if ($gettestpoint) {

            $state = $this->testpoint->deletetestpoint($id);
            $message = Session::flash('message', 'Deleted Successfully!');

            return redirect('admin/testpointlist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/testpointlist')->with('data', $error);
        }
    }




}