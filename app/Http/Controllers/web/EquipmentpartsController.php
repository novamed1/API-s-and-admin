<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Customertype;
use Illuminate\Http\Request;
use App\Models\Equipmentparts;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use Excel;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;

//use Request;

class EquipmentpartsController extends Controller {
     public function __construct() {
         
        $this->equipmentparts = new Equipmentparts();
       
    }
    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Equipment Parts';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tep.*','te.model_name');
            $data = $this->equipmentparts->AllEquipmentParts('', '', 'tep.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('te.model_name', 'tep.name', 'tep.description'));

        } else {
            $select = array('tep.*','te.model_name');
            $data = $this->equipmentparts->AllEquipmentParts('', '', 'tep.id', 'DESC', array('select' => $select));

        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//        print_r($paginatedItems);die;
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.parts.equipmentpartslist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

     public function form(Request $request, $id = false) {
         $input = Input::all();
//      echo'<pre>';print_r($input);die;
         $title ='Novamed-Equipment Creation';
        $modelname = DB::table('tbl_equipment_model')->pluck('model_name', 'id');
        $modelname->prepend('-Select model-');
         $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'description'=>isset($input['description']) ? $input['description']:'',
             'make'=>isset($input['make'])? $input['make']:'',
             'quantity'=>isset($input['quantity']) ? $input['quantity']:'',
             'modelId'=>isset($input['modelId']) ? $input['modelId']:'',
             'item_number'=>isset($input['item_number']) ? $input['item_number']:'',
             'price'=>isset($input['price']) ? $input['price']:'',
             'is_active'=>isset($input['is_active']) ? $input['is_active']:'',
             
             ];
         if($id){
              $equipment = $this->equipmentparts->getEquipment($data['id']);
//            echo'<pre>'; print_r($equipment);die;
            if (!$equipment) {
                return redirect('admin/partslist')->with('message', 'Sorry! Details are not found.');
            }else{
             $data['id'] = $equipment->id;
             $data['name']=$equipment->name;
             $data['description']=$equipment->description;
             $data['make']=$equipment->make;
             $data['quantity']=$equipment->quantity;
             $data['modelId']=$equipment->equipment_id;
             $data['item_number']=$equipment->item_number;
             $data['price']=$equipment->price;
             $data['is_active']=$equipment->is_active;
         }
         }
         $rules = [
            'name' => 'required',
            'description' => 'required',
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
         return view('master.parts.equipmentpartsform')->with('modelname',$modelname)->with('title',$title)->with('input', $data);
        }
        else{
            $data = Input::all();
//           echo'<pre>'; print_r($data);die;
            $save = array();

            $save['id'] = $id;
            $save['name']=$data['name'];
            $save['description']=$data['description'];
            $save['equipment_id']=$data['modelId'];
            $save['item_number']=$data['item_number'];
            $save['make']=$data['make'];
            $save['price']=$data['price'];
            $save['quantity']=$data['quantity'];
             if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
             $Saveresult = $this->equipmentparts->saveEquipmentpart($save);
              return redirect('admin/partslist')->with('message', 'Added Successfully!');
        }
     }

}
