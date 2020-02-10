<?php

namespace App\Http\Controllers\web\services;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Customer;
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
use App\Models\Servicerequest;

//use Request;

class WorkorderController extends Controller
{

    public function __construct()
    {
        $this->equipment = new Equipment();
        $this->customer = new Customer();
        $this->servicerequest = new Servicerequest();
         }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Equipment';

        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customer->prepend('-Choose Customer-');
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $customerId = isset($input['customerId']) ? $input['customerId'] : '0';


        if ($keyword != "" || $customerId != '0') {

            $datas['search']['keyword'] = $keyword;
            $datas['search']['key'] = $customerId;
            $select = array('te.*', 'td.as_found', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name');
            $data = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'userGroupBy' => true, 'search' => $datas['search']), false, array('te.asset_no', 'te.location', 'te.serial_no', 'te.pref_contact', 'te.pref_tel', 'te.pref_email', 'tem.model_name'));

        } else {

            $select = array('te.*', 'td.as_found', 'td.as_calibrate', 'td.last_cal_date', 'td.frequency_id', 'td.next_due_date', 'tem.model_name');
            $data = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'userGroupBy' => true));
        }

        $temp = array();
        if ($data) {
            foreach ($data as $key => $value) {

                $temp[$key]['Id'] = $value->id;
                $temp[$key]['customerId'] = $value->customer_id;
                $countdata = $this->dueequipments->AllEquipments('', '', 'te.id', 'DESC', array('select' => $select, 'customerId' => $temp[$key]['customerId']));
                if (!$countdata->isEmpty()) {
                    $temp[$key]['count'] = count($countdata);
                } else {
                    $temp[$key]['count'] = 0;
                }

                $CustomernameId = $value->customer_id;
                $getcustomer = $this->customer->getCustomer($CustomernameId);
                if ($getcustomer) {
                    $temp[$key]['customerName'] = $getcustomer->customer_name;
                } else {
                    $temp[$key]['customerName'] = '';
                }
            }
        }
//       echo'<pre>'; print_r($customerId);die;
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($temp, count($temp), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('equipment.equipmentlist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword)->with('customer', $customer)->with('customerId', $customerId);
    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Equipment Creation';

        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customer->prepend('Please Choose Customer', '');



        $requests = array('' => 'Select Request');
        $data = [
            'id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'description' => isset($input['description']) ? $input['description'] : '',
            'planName' => isset($input['planName']) ? $input['planName'] : '',
            'customerId' => isset($input['customerId']) ? $input['customerId'] : '',
            'requestNum' => isset($input['requestNum']) ? $input['requestNum'] : '',
        ];
        if ($id) {
            $getequipmet = $this->dueequipments->getequipments($data['id']);
            $getvalue = $this->dueequipments->getvalues($data['id']);

            if (!$getequipmet) {
                return redirect('admin/viewlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getequipmet->id;
                $data['name'] = $getequipmet->name;
                $data['description'] = $getequipmet->description;
                $data['assetno'] = $getequipmet->asset_no;
                $data['serial_no'] = $getequipmet->serial_no;
                $data['modelId'] = $getequipmet->equipment_model_id;
                $data['customerId'] = $getequipmet->customer_id;
                $data['location'] = $getequipmet->location;
                $data['pref_contact'] = $getequipmet->pref_contact;
                $data['pref_tel'] = $getequipmet->pref_tel;
                $data['pref_email'] = $getequipmet->pref_email;
                $data['is_active'] = $getequipmet->is_active;
                $data['as_found'] = $getvalue->as_found;
                $data['as_calibrate'] = $getvalue->as_calibrate;
                $data['frequencyId'] = $getvalue->frequency_id;
                $data['lastDate'] = Carbon::parse($getvalue->last_cal_date)->format('d-m-Y');
                $data['nextDate'] = Carbon::parse($getvalue->next_due_date)->format('d-m-Y');
            }
        }

        $rules = [
            'name' => 'required',
            'assetno' => 'required',
        ];
        $error = array();
        $equipmentDetail = array();

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
            return view('workorder.workorderform')->with('equipmentDetail', $equipmentDetail)->with('title', $title)->with('input', $data)->with('requests', $requests)->with('customer', $customer);
            $data = Input::all();

            return redirect('admin/viewlist')->with('message', 'Added Successfully!');
        }
    }


    function getServiceRequestCustomer()
    {
        $data = Input::all();
        $customerId = $data['customerId'];

        $requests = DB::table('tbl_service_request')->where('customer_id','=',$customerId)->select('request_no','id')->get();
        $data   = '';
        $element = '<option value="">-Select-</option>';
        foreach ($requests as $val) {
            $element .= '<option value="'.$val->id.'">'.$val->request_no.'</option>';
        }
        $data = $element;
        die(json_encode(array('result' => true, 'data' => $data)));

    }

    function getServiceRequestItems()
    {
        $data = Input::all();
        $requestNum = $data['requestNum'];
        $items = $this->servicerequest->serviceItemEquipments(0,0,array('request_id'=>$requestNum));
        die(json_encode(array('result' => true, 'data' => $items)));
    }
}
