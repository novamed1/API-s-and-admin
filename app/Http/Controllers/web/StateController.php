<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\State;
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

class StateController extends Controller
{

    public function __construct()
    {
        $this->state = new State();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'State';
        // print_r($title);die;

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('s.*');
            $data = $this->state->AllState('', '', 's.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tm.manufacturer_name', 'tm.manufacturer_phone', 'tm.manufacturer_email', 'tm.manufacturer_address'));
        } else {
            $select = array('s.*');
            $data = $this->state->AllState('', '', 's.id', 'DESC', array('select' => $select));
        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.state.statelist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }




  public function form(Request $request,$id=false)
  {

   // print_r($id);die;
   $title = "Add State";
   $data['id'] = '';
   $data['state_name'] = '';
   if(isset($id) && $id!='')
   {
    $state = $this->state->getstate($id);
    $data['id'] = $id;
    $data['state_name'] = $state->state_name;

   }

   return view('master.state.addState')->with('title', $title)->with('input',$data);

  }




    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['state_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
      
       // echo '<pre>';print_r($param);die;

        $select = array('s.*');
        $data = $this->state->AllStateGrid($param['limit'], $param['offset'], 's.id', 'DESC', array('select' => $select, 'search' => $search), false, array('s.id,s.state_name'));
        $count = $this->state->AllStateGrid('', '', 's.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('s.id,s.state_name'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->state_name;
                $values[$key]['1'] = "<a href=" . url("admin/editState/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $values[$key]['2'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletestate/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


  public function saveState(Request $request,$id=false)
  {


   $input = Input::all();

   $id =  isset($id)?$id:'';

    // print_r($input);die;

     $rules = [
            'state_name'=>'required|unique:tbl_state,state_name,' . $id . ',id',
          
        ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->WithInput();
        }

  $saveState = array();
  $saveState['id'] = $id;
  $saveState['state_name'] = $input['state_name'];
  $saveState['country_id'] = 1;
  $state = $this->state->saveState($saveState);
  if($state)
  {
    $title = "Statelist";
    return redirect('admin/statelist')->with('message', 'Created Successfully!');
  }

  }



     public function delete($id)
    {

        $getstate = $this->state->getstate($id);

        if ($getstate) {

            $state = $this->state->deletestate($id);
            $message = Session::flash('message', 'Deleted Successfully!');

            return redirect('admin/statelist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/statelist')->with('data', $error);
        }
    }




}