<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\City;
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

class CityController extends Controller
{

    public function __construct()
    {
        $this->city = new City();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'City';
        // print_r($title);die;

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('s.*');
            $data = $this->city->AllCity('', '', 'c.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('s.state_name','c.city_name'));
        } else {
            $select = array('s.*');
            $data = $this->city->AllCity('', '', 'c.id', 'DESC', array('select' => $select));
        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.city.citylist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }




  public function form(Request $request,$id=false)
  {

   // print_r($id);die;
   $title = "Add City";
   $data['id'] = '';
   $data['city_name'] = '';
   $data['state_id'] = '';

   $states = DB::table('tbl_state')->pluck('state_name','id');
   $states->prepend('-Select State-','');
   // print_r($states);die;
   if(isset($id) && $id!='')
   {
    $city = $this->city->getcity($id);
    $data['id'] = $id;
    $data['state_id'] = $city->state_id;
    $data['city_name'] = $city->city_name;
   }

   return view('master.city.addcity')->with('title', $title)->with('input',$data)->with('state',$states);

  }




    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['state_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['city_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
       // echo '<pre>';print_r($param);die;

        $select = array('c.*','s.state_name');
        $data = $this->city->AllCityGrid($param['limit'], $param['offset'], 's.id', 'DESC', array('select' => $select, 'search' => $search), false, array('s.id,s.state_name'));
        $count = $this->city->AllCityGrid('', '', 's.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('c.*,s.state_name'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->state_name;
                $values[$key]['1'] = $row->city_name;
                $values[$key]['2'] = "<a href=" . url("admin/editCity/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $values[$key]['3'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletecity/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


  public function saveCity(Request $request,$id=false)
  {


   $input = Input::all();

   $id =  isset($id)?$id:'';

    // print_r($input);die;

     $rules = [
            'state_id'=>'required',
            'city_name'=>'required|unique:tbl_city,city_name,' . $id . ',id',
          
        ];

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {

            return Redirect::back()
                ->withErrors($validator)
                ->WithInput();
        }

  $saveCity = array();
  $saveCity['id'] = $id;
  $saveCity['state_id'] = $input['state_id'];
  $saveCity['city_name'] = $input['city_name'];
  $city = $this->city->saveCity($saveCity);
  if($city)
  {
    $title = "Citylist";
    return redirect('admin/citylist')->with('message', 'Created Successfully!');
  }

  }



     public function delete($id)
    {

        $getcity = $this->city->getcity($id);

        if ($getcity) {

            $city = $this->city->deletecity($id);
            $message = Session::flash('message', 'Deleted Successfully!');

            return redirect('admin/citylist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/citylist')->with('data', $error);
        }
    }




}