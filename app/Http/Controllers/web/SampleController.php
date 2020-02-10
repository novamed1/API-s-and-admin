<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Sample;
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

class SampleController extends Controller
{

    public function __construct()
    {
        $this->sample = new Sample();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Samples';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('ts.*');
            $data = $this->sample->Allsample('', '', 'ts.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('ts.name', 'ts.description', 'ts.mode'));
        } else {
            $select = array('ts.*');
            $data = $this->sample->Allsample('', '', 'ts.id', 'DESC', array('select' => $select));
        }


        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//        print_r($paginatedItems);die;
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('sample.samplelist')->with('data', $paginatedItems)->with('keyword', $keyword)->with('title', $title);
    }


    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;

        $search['name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['description'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['mode'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';

        $select = array('ts.*');
        $data = $this->sample->AllsampleGrid($param['limit'], $param['offset'], 'ts.id', 'DESC', array('select' => $select, 'search' => $search), false, array('ts.name', 'ts.description', 'ts.mode'));


        $count = $this->sample->AllsampleGrid('', '', 'ts.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true),
            true);
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = $row->description;
                $values[$key]['2'] = $row->mode;
                $values[$key]['3'] = "<a href=" . url('admin/editsample/' . $row->id) . "><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Sample Creation';
        $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'description' => isset($input['description']) ? $input['description'] : '',
            'mode' => isset($input['mode']) ? $input['mode'] : '',
            'temperature' => isset($input['temperature']) ? $input['temperature'] : '',
            'relative_humidity' => isset($input['relative_humidity']) ? $input['relative_humidity'] : '',
            'barometric_pressure' => isset($input['barometric_pressure']) ? $input['barometric_pressure'] : '',
            'accuracy' => isset($input['accuracy']) ? $input['accuracy'] : '',
            'precision' => isset($input['precision']) ? $input['precision'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
        ];

        $mode = array('' => '-Select-', '1' => "1", '2' => '2', '3' => '3', '4' => '4');
        if ($id) {
            $sample = $this->sample->getsample($data['id']);

            if (!$sample) {
                return redirect('admin/samplelist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $sample->id;
                $data['name'] = $sample->name;
                $data['mode'] = $sample->mode;
                $data['description'] = $sample->description;
                $data['temperature'] = $sample->temperature;
                $data['relative_humidity'] = $sample->relative_humidity;
                $data['barometric_pressure'] = $sample->barometric_pressure;
                $data['accuracy'] = $sample->accuracy;
                $data['precision'] = $sample->precision;
                $data['is_active'] = $sample->is_active;
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
            return view('sample.sampleForm')->with('samples', $mode)->with('title', $title)->with('input', $data);
        } else {
            $data = Input::all();

            $save = array();

            $save['id'] = $id;
            $save['name'] = $data['name'];
            $save['mode'] = $data['mode'];
            $save['description'] = $data['description'];

            if (isset($data['temperature']) ? $data['temperature'] : '0') {
                $save['temperature'] = 1;
            } else {
                $save['temperature'] = 0;
            }

            if (isset($data['barometric_pressure']) ? $data['barometric_pressure'] : '0') {
                $save['barometric_pressure'] = 1;
            } else {
                $save['barometric_pressure'] = 0;
            }

            if (isset($data['relative_humidity']) ? $data['relative_humidity'] : '0') {
                $save['relative_humidity'] = 1;
            } else {
                $save['relative_humidity'] = 0;
            }

            if (isset($data['accuracy']) ? $data['accuracy'] : '0') {
                $save['accuracy'] = 1;
            } else {
                $save['accuracy'] = 0;
            }

            if (isset($data['precision']) ? $data['precision'] : '0') {
                $save['precision'] = 1;
            } else {
                $save['precision'] = 0;
            }

            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
            $Saveresult = $this->sample->savesample($save);
            return redirect('admin/samplelist')->with('message', 'Added Successfully!');
        }
    }

}
