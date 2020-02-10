<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;

use App\Models\Sentinel\User;
use App\Models\Testplan;
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

class TestplanController extends Controller
{
    public function __construct()
    {
        $this->testplan = new Testplan();

    }

    public function index(Request $request)
    {
        $input = Input::all();
        //echo'<pre>';print_r($input);die;
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $postvalue = isset($input['postvalue']) ? $input['postvalue'] : '';
        $posttestplanid = isset($input['postTestPlanId']) ? $input['postTestPlanId'] : '';
        $data['search']['keyword'] = $keyword;
        $select = array('tp.*', 'tbl_equipment_model.model_name');
        $data = $this->testplan->AllTestplan('', '', 'tp.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tbl_equipment_model.model_name', 'tp.name'));

//echo'<pre>';print_r($data);die;

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );

        $userDetail->setPath($request->url());

        return view('testplan.testplanlist')->with('data', $paginatedItems)->with('keyword',$keyword)->with('postvalue',$postvalue)->with('posttestplanid',$posttestplanid);


    }


    public function form(Request $request, $id = false)
    {
        $input = Input::all();
//           echo'<pre>';print_r($input);die;
        $modelunits = array('' => '-Select-', '1' => "ul", '2' => 'kg', '3' => 'mg', '4' => 'ltrs');
        $equipmentModels = DB::table('tbl_equipment_model')->pluck('model_name', 'id');
        $equipmentModels->prepend('-Select model-');
        $data = [
            'id' => $id,
            'testPlanName' => isset($input['testPlanName']) ? $input['testPlanName'] : '',
            'testPlanUnit' => isset($input['testPlanUnit']) ? $input['testPlanUnit'] : '',
            'testPlanDescription' => isset($input['testPlanDescription']) ? $input['testPlanDescription'] : '',
            'testPlanEquipmentModel' => isset($input['testPlanEquipmentModel']) ? $input['testPlanEquipmentModel'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
            'test_Tolerences' => array(),
            'model_id' => '',
            'model_name' => '',
        ];
//        print_r($data);die;

        if ($id) {
            $getplan = $this->testplan->getTest($data['id']);

            $data['id'] = $getplan->testPlanId;
            $data['model_id'] = $getplan->model_id;
            $data['testPlanName'] = $getplan->name;
            $data['testPlanDescription'] = $getplan->description;
            $data['testPlanUnit'] = $getplan->unit;
            $data['is_active'] = $getplan->is_active;
            $data['test_Tolerences'] = $this->testplan->getTestTolerences($id);
            $data['model_name'] = $getplan->model_name;


//                  echo'<pre>';  print_r($data);die;

        }

        $rules = [
            'testPlanName' => 'required',
            'testPlanUnit' => 'required',
            'testPlanDescription' => 'required',
            'testPlanEquipmentModel' => 'required'
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
            return view('testplan.testplanForm')->with('input', $data)->with('modelunits', $modelunits)->with('equipmentModels', $equipmentModels)->with('errors', $error);
        } else {
            $data = Input::all();
            $save = array();
            $save['id'] = $data['testPlanId'];
            $save['name'] = $data['testPlanName'];
            $save['description'] = $data['testPlanDescription'];
            $save['unit'] = $data['testPlanUnit'];
            $save['no_of_points'] = count($data['testtolerence']);
            $save['model_id'] = $data['testPlanEquipmentModel'];
            if (isset($data['is_active'])) {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
             //echo '<pre>';print_r($save);'</pre>';die;
            $saveTestPlan = $this->testplan->saveTestPlan($save);
            //$saveTestPlan = 1;
            if ($saveTestPlan) {
                if (isset($data['testtolerence']) && !empty($data['testtolerence'])) {
                    if($save['id'])
                    {
                        DB::table('tbl_test_tolerance')->where('test_plan_id', '=', $save['id'])->delete();
                    }
                    foreach ($data['testtolerence'] as $row) {
                        $saveTolerence['id'] = false;
                        $saveTolerence['target_value'] = $row['target'];
                        $saveTolerence['description'] = $row['description'];
                        $saveTolerence['accuracy'] = $row['accuracy'];
                        $saveTolerence['precision'] = $row['precision'];
                        if (isset($data['is_active'])) {
                            $saveTolerence['is_active'] = 1;
                        } else {
                            $saveTolerence['is_active'] = 0;
                        }
                        $saveTolerence['test_plan_id'] = $saveTestPlan;
                        $this->testplan->saveTestPlanTolerence($saveTolerence);
                    }
                }
            }
           $message =  Session::flash('message', 'Test plan has been saved');
            die(json_encode(array("result" => true, "msg" => $message)));
        }
    }

    function limittolerence()
    {
        $input = Input::all();
//       echo'<pre>'; print_r($input);die;
        $modelId = $input['modelId'];
        if ($modelId) {
            $tolerences = $this->testplan->getLimitTolerences($modelId);
            $view = View::make('testplan.testplanTolerenceAjax', ['data' => $tolerences]);
            $formData = $view->render();
            die(json_encode(array("result" => true, "formData" => trim($formData))));

        }
    }
}
