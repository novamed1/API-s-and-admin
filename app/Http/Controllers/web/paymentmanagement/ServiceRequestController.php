<?php

namespace App\Http\Controllers\web\services;

use App\Http\Controllers\Controller;
use App\Models\Devicemodel;
use App\Models\Sentinel\User;
use App\Models\ServicePlan;
use App\Models\Servicerequest;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use DateInterval;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceRequestController extends Controller
{

    public function __construct()
    {
        $this->servicerequest = new Servicerequest();
        $this->servicePlan = new ServicePlan();
        $this->equipment = new Equipment();
        $this->frequency = new Devicemodel();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Service Request';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tsr.*', 'tc.customer_name');
            $data = $this->servicerequest->AllServiceRequest('', '', 'tsr.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tc.customer_name'));
        } else {
            $select = array('tsr.*', 'tc.customer_name');
            $data = $this->servicerequest->AllServiceRequest('', '', 'tsr.id', 'DESC', array('select' => $select));
        }

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        if ($request->ajax()) {
            return view('serviceRequest.servicerequestAjax', ['data' => $paginatedItems])->render();
        }
        return view('serviceRequest.servicerequest')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function getRequestItems(Request $request)
    {

        $input = Input::all();

        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Details are not found')));
        }
        $requestId = $input['requestId'];
        $select = array('tsri.*');

        $data = $this->servicerequest->RequestItems('', '', 'tsri.id', 'DESC', array('requestId' => $requestId, 'select' => $select));

        $temp = array();
        if (!$data->isEmpty()) {
            foreach ($data as $key => $row) {
                $dueEquipmentId = $row->due_equipments_id;
                $planId = $row->test_plan_id;
                $frequencyId = $row->frequency_id;
                $priceId = $row->service_price_id;
                $servicerequestId = $row->id;

                if ($planId && $dueEquipmentId && $frequencyId && $priceId) {
                    $getPlanDetails = $this->servicePlan->getPlan($planId);
                    if ($getPlanDetails) {
                        $temp[$key]['planName'] = $getPlanDetails->service_plan_name;
                    } else {
                        $temp[$key]['planName'] = '';
                    }
//                    get equipment from due equipment
                    $getDueEquipmentDetails = $this->equipment->getDueEquipment($dueEquipmentId);
                    if ($getDueEquipmentDetails) {
                        $equipmentId = $getDueEquipmentDetails->equipment_id;

                        if ($equipmentId) {
                            $getEquipment = $this->equipment->getEquipment($equipmentId);
                            $temp[$key]['equipmentName'] = $getEquipment->name;
                        } else {
                            $temp[$key]['equipmentName'] = '';
                        }
                    } else {
                        $temp[$key]['equipmentName'] = '';
                    }

//                    get frequency
                    $getFrequency = $this->frequency->getfrequency($frequencyId);
                    if ($getFrequency) {
                        $temp[$key]['frequency'] = $getFrequency->name;
                    } else {
                        $temp[$key]['frequency'] = '';
                    }

//                    get price

                    $getPricing = $this->servicePlan->servicePricing($priceId);
                    if ($getPricing) {
                        $temp[$key]['price'] = $getPricing->price;
                    } else {
                        $temp[$key]['price'] = '';
                    }
                }


            }
            die(json_encode(array('result' => true, 'data' => $temp)));
        } else {
            die(json_encode(array('result' => false)));

        }


    }


    public function detailPage(Request $request, $requestId)
    {

        $select = array('tsr.*', 'tc.customer_name');
        $getServiceRequestDetails = $this->servicerequest->AllServiceRequest('', '', 'tsr.id', 'DESC', array('select' => $select, 'requestId' => $requestId));
        $title = 'Novamed-Service Request';

//        get service request details
        $temp = array();
        if ($getServiceRequestDetails) {
            foreach ($getServiceRequestDetails as $key => $row) {
                $temp[$key]['id'] = $row->id;
                $temp[$key]['requestNumber'] = $row->request_no;
                $temp[$key]['customerName'] = $row->customer_name;
                $temp[$key]['serviceSchedule'] = $row->service_schedule_date;
                $temp[$key]['requestedDate'] = $row->created_date;
            }
        }

//        get service request items
        $select = array('tsri.*');
        $getServiceReqItems = $this->servicerequest->RequestItems('', '', 'tsri.id', 'DESC', array('requestId' => $requestId, 'select' => $select));
        $itemtemp = array();
        if ($getServiceReqItems) {
            foreach ($getServiceReqItems as $itemkey => $itemval) {
                $dueEquipmentId = $itemval->due_equipments_id;
                //                    get equipment from due equipment
                $equipmentCount = 0;
                $getDueEquipmentDetails = $this->equipment->getDueEquipment($dueEquipmentId);
                if ($getDueEquipmentDetails) {
                    $equipmentId = $getDueEquipmentDetails->equipment_id;

                    if ($equipmentId) {
                        $getEquipment = $this->equipment->getEquipment($equipmentId);
                        if ($getEquipment) {
                            $itemtemp[$itemkey]['equipmentName'] = $getEquipment->name;
                            $itemtemp[$itemkey]['assetNumber'] = $getEquipment->asset_no;
                            $itemtemp[$itemkey]['serialNumber'] = $getEquipment->serial_no;
                            $itemtemp[$itemkey]['location'] = $getEquipment->location;
                            $equipmentModelId = $getEquipment->equipment_model_id;
                            $getEquipmentModel = $this->equipment->getmodel($equipmentModelId);
                            $itemtemp[$itemkey]['modelName'] = $getEquipmentModel->model_name;
                            $itemtemp[$itemkey]['lastCalDate'] = Carbon::parse($getDueEquipmentDetails->last_cal_date)->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('j-M-Y');;
                            $itemtemp[$itemkey]['asFound'] = $getDueEquipmentDetails->as_found;
                            if($itemtemp[$itemkey]['asFound'] == 1){
                                $itemtemp[$itemkey]['asFoundName'] = 'Yes';
                            }else{
                                $itemtemp[$itemkey]['asFoundName'] = 'N/A';
                            }
                            $itemtemp[$itemkey]['asCalibrated'] = $getDueEquipmentDetails->as_calibrate;
                            if($itemtemp[$itemkey]['asCalibrated'] == 1){
                                $itemtemp[$itemkey]['asCalibratedName'] = 'Yes';
                            }else{
                                $itemtemp[$itemkey]['asCalibratedName'] = 'N/A';
                            }
                            $equipmentCount = $equipmentCount + 1;
                        }
                    } else {
                        $itemtemp[$itemkey]['equipmentName'] = '';
                    }
                } else {
                    $itemtemp[$itemkey]['equipmentName'] = '';
                    $itemtemp[$itemkey]['assetNumber'] = '';
                    $itemtemp[$itemkey]['serialNumber'] = '';
                    $itemtemp[$itemkey]['location'] = '';
                    $itemtemp[$itemkey]['modelName'] = '';
                    $itemtemp[$itemkey]['lastCalDate'] = '';
                    $itemtemp[$itemkey]['asFound'] = '';
                    $itemtemp[$itemkey]['asCalibrated'] = '';
                    $itemtemp[$itemkey]['asCalibratedName'] = 'N/A';
                    $itemtemp[$itemkey]['asFoundName'] = 'N/A';


                }
                $testPlanId = $itemval->test_plan_id;

//                getplan

                $getPlanDetails = $this->servicePlan->getPlan($testPlanId);
                if ($getPlanDetails) {
                    $itemtemp[$itemkey]['planName'] = $getPlanDetails->service_plan_name;
                } else {
                    $itemtemp[$itemkey]['planName'] = '';
                }

                $priceId = $itemval->service_price_id;
                //                    get price
                $getPricing = $this->servicePlan->servicePricing($priceId);
                if ($getPricing) {
                    $itemtemp[$itemkey]['price'] = $getPricing->price;
                } else {
                    $itemtemp[$itemkey]['price'] = '';
                }


                $frequencyId = $itemval->frequency_id;
                //                    get frequency
                $getFrequency = $this->frequency->getfrequency($frequencyId);
                if ($getFrequency) {
                    $itemtemp[$itemkey]['frequency'] = $getFrequency->name;
                } else {
                    $itemtemp[$itemkey]['frequency'] = '';
                }

                $itemtemp[$itemkey]['comments'] = $itemval->comments;

            }

//            echo '<pre>';print_r($itemtemp);die;
            return view('serviceRequest.requestDetaiilPage')->with('details', $itemtemp)->with('requestData', $temp)->with('title', $title);


        }
    }
}
