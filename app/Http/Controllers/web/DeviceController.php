<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Device;
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

class DeviceController extends Controller
{
    public function __construct()
    {
        $this->device = new Device();

    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Device Model';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('td.*','tdm.name');
            $data = $this->device->Alldevice('', '', 'td.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('td.description'));

        } else {
            $select = array('td.*','tdm.name');
            $data = $this->device->Alldevice('', '', 'td.id', 'DESC', array('select' => $select));

        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//        print_r($paginatedItems);die;
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.device.devicelist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }


    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['tdm.name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['td.serial_no'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['f.name'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['td.last_cal_date'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['td.next_due_date'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';
        $select = array('td.serial_no','td.id','td.description', 'tdm.name','f.name as fname','td.last_cal_date','td.next_due_date');
        $data = $this->device->AlldeviceGrid($param['limit'], $param['offset'], 'td.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tc.name', 'tc.description'));
        $count = $this->device->AlldeviceGrid('', '', 'td.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tc.name', 'tc.description'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = $row->serial_no;
                $values[$key]['2'] = $row->fname;
                $values[$key]['3'] = date('m/d/Y',strtotime(str_replace('-','/',$row->last_cal_date)));
                $values[$key]['4'] = date('m/d/Y',strtotime(str_replace('-','/',$row->next_due_date)));
                $values[$key]['5'] = "<a href=" . url("admin/editdevice/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $values[$key]['6'] = '<button data-attr='.$row->id.'
                                                                id="changeTechnician" type="button"
                                                                class="btn btn-space btn-primary">
                                                            Assign
                                                        </button>';
                $values[$key]['7'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletedevice/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";

                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


    public function form(Request $request, $id = false)
    {
        $input = Input::all();
//      echo'<pre>'; print_r($input);die;
        $title = 'Novamed-Device Creation';
//        $devicemodel = DB::table('tbl_device_model')->pluck('name', 'id');
//        $devicemodel->prepend('Please choose device model');

        $devicemodel_drop = array();
        $devicemodel = DB::table('tbl_device_model')->select('name', 'id')->where('is_active','=',1)->get();
//        $devicemodel_drop[0] = 'Please choose device model';
        if(count($devicemodel))
        {
            foreach($devicemodel as $key => $row){
                $devicemodel_drop[$row->id] = $row->name;
            }
        }


        $sensitivity = DB::table('tbl_sensitivity')->pluck('name', 'id');
        $sensitivity->prepend('Please choose sensitivity');

        $unit = DB::table('tbl_units')->pluck('unit', 'id');
        $unit->prepend('Please choose unit');

        $frequency = DB::table('tbl_frequency')->pluck('name', 'id');
//        $frequency->prepend('Please choose frequency');
        $data = [
            'id' => $id,
            'description' => isset($input['description']) ? $input['description'] : '',
            'sensitivity' => isset($input['sensitivity']) ? $input['sensitivity'] : '',
            'serial_no' => isset($input['serial_no']) ? $input['serial_no'] : '',
            'last_cal_date' => isset($input['last_cal_date']) ? $input['last_cal_date'] : '',
            'devicemodelId' => isset($input['devicemodelId']) ? $input['devicemodelId'] : '',
            'unit' => isset($input['unit']) ? $input['unit'] : '',
            'frequencyId' => isset($input['frequencyId']) ? $input['frequencyId'] : '',
            'nextduedate' => isset($input['nextduedate']) ? $input['nextduedate'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',

        ];
        if ($id) {
            $getdevice = $this->device->getdevice($data['id']);
//            echo'<pre>'; print_r($getdevice);die;
            if (!$getdevice) {
                return redirect('admin/devicelist')->with('message', 'Sorry! Details are not found.');
            } else {

                $data['id'] = $getdevice->id;
                $data['devicemodelId'] = $getdevice->device_model_id;
                $data['deviceno'] = $getdevice->device_no;
                $data['sensitivity'] = $getdevice->sensitivity_id;
                $data['unit'] = $getdevice->unit_id;
                $data['description'] = $getdevice->description;
                $data['serial_no'] = $getdevice->serial_no;
                $data['frequencyId'] = $getdevice->frequency_id;
                $data['Calibrationmode'] = $getdevice->calibration_mode;
                $data['last_cal_date'] = Carbon::parse($getdevice->last_cal_date)->format('m/d/Y');
                $data['nextduedate'] = Carbon::parse($getdevice->next_due_date)->format('m/d/Y');
                $data['is_active'] = $getdevice->is_active;
            }
        }
        $rules = [
            'devicemodelId' => 'required',

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
            return view('master.device.deviceForm')->with('input', $data)->with('unit', $unit)->with('sensitivity', $sensitivity)->with('title', $title)->with('devicemodel', $devicemodel_drop)->with('frequency', $frequency);
        } else {
            $data = Input::all();

//          echo'<pre>';print_r($data);die;
            $save = array();
            $save['id'] = $id;
            $save['device_model_id'] = $data['devicemodelId'];

            $save['description'] = isset($data['description'])?$data['description']:'';
            $save['serial_no'] = $data['serial_no'];
            $save['frequency_id'] = $data['frequencyId'];
            $lastdate = str_replace('-', '/', $data['last_cal_date']);
            $nextdate = str_replace('-', '/', $data['nextduedate']);

            $finallastdate = date('Y-m-d', strtotime($lastdate));
            $finalnextdate = date('Y-m-d', strtotime($nextdate));

            $save['last_cal_date'] = $finallastdate;
            $save['next_due_date'] = $finalnextdate;

            if($data['devicemodelId'] == 1){
                $save['sensitivity_id'] = $data['sensitivity'];
                $save['unit_id']=$data['unit'];
            }

            if (isset($data['is_active']) ? $data['is_active'] : '0') {

                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
//           echo'<pre>'; print_r($save);die;
            $Saveresult = $this->device->saveDevice($save);
        }

        if($id){
            return redirect('admin/devicelist')->with('message', 'Updated Successfully');

        }else{
            return redirect('admin/devicelist')->with('message', 'Added Successfully');

        }    }



    public
    function delete($id)
    {

        $getdetail = $this->device->getdevice($id);

        if ($getdetail) {

//            $getSubdetail = DB::table('tbl_equipment_model')->where('brand_id','=',$id)->select('*')->first();
//            if($getModel){
//                $message = Session::flash('error', "You can't able to delete this brand");
//                return redirect('admin/devicelist')->with(['data', $message], ['message', $message]);
//            }

            $message = Session::flash('message', 'Deleted Successfully');
            $member = $this->device->deletedevice($id);

            return redirect('admin/devicelist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully');
            return redirect('admin/devicelist')->with('data', $error);
        }
    }


    //check serial number exits are not
   public function checkSerialNumber()
    {
        $post = Input::all();
//        echo'<pre>';print_r($post);'</pre>';die;
        if($post)
        {
            $checkcombination = $this->device->getSerialNo($post['serialno'],$post['id']);
            if(($checkcombination))
            {
                die(json_encode(array('result' =>false , 'message' => 'Serial number already exist')));
            }
            else
            {
                die(json_encode(array('result' => true)));
            }
        }

    }
}



