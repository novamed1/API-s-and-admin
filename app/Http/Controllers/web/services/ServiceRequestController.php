<?php

namespace App\Http\Controllers\web\services;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Devicemodel;
use App\Models\Payment;
use App\Models\Sentinel\User;
use App\Models\ServicePlan;
use App\Models\Servicerequest;
use App\Models\Equipment;
use App\Models\Workorder;
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
        $this->customer = new Customer();
        $this->frequency = new Devicemodel();
        $this->workorder = new Workorder();
        $this->payment = new Payment();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Service Request';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tsr.*', 'tc.customer_name');
            $data = $this->servicerequest->AllServiceRequest('', '', 'tsr.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tc.customer_name', 'tsr.request_no'));
        } else {
            $select = array('tsr.*', 'tc.customer_name');
            $data = $this->servicerequest->AllServiceRequest('', '', 'tsr.id', 'DESC', array('select' => $select));
        }

//        echo '<pre>';print_r($data);die;

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

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['request_no'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['customer_name'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['unique_id'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['totalInstruments'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : 0;
//        $search['tsr.service_schedule_date'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';
        $search['tsr.created_date'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';

        //echo'<pre>';print_r($search);'</pre>';die;
        //$select = array('tsr.*', 'tc.customer_name','tc.unique_id', DB::raw('COUNT(`tsri`.`id`) as totalInstruments'));
        $select = array('tsr.*', 'tc.customer_name', 'tc.unique_id', DB::raw("(SELECT COUNT(ri.id) FROM tbl_service_request_item as ri
        JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id JOIN tbl_equipment as e ON e.id=d.equipment_id where ri.service_request_id=tsr.id) as totalInstruments"));
        $data = $this->servicerequest->AllServiceRequestGrid($param['limit'], $param['offset'], 'tsr.id', 'DESC', array('equipments' => true, 'select' => $select, 'search' => $search, 'totalInstruments' => $input['sSearch_4']),
            false);

        $count = $this->servicerequest->AllServiceRequestGrid($param['limit'], $param['offset'], 'tsr.id', 'DESC', array('equipments' => false, 'select' => $select, 'search' => $search, 'totalInstruments' => $input['sSearch_4'], 'count' => true),
            true);

//        echo '<pre>';print_r($data);die;
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {

                //$workorders = DB::table('tbl_work_order as w')->where('request_id',$row->id);

                $values[$key]['0'] = '<span class="lead_numbers" data-id=' . $row->id . '>
                                                   <a href="javascript:void(0)"
                                                      id="workOrderItems"
                                                      rel=' . $row->id . '
                                                      data-toggle="collapse"
                                                      data-target="#workOrderDetail' . $row->id . '"
                                                      data-id=' . $row->id . '
                                                      data-parent="#tagcollapse"
                                                      class="accordion-toggle hov"
                                                   ><i
                                                               class="fa fa-plus-circle ordericon collapseico"
                                                               data-widget-collapsed="true" data-attr=' . $row->id . '></i> <span
                                                               class="menu-item-parent"></span></a>
                                                      </span>';
                $values[$key]['1'] = $row->request_no;

                $values[$key]['2'] = $row->customer_name;
                $values[$key]['3'] = $row->unique_id ? $row->unique_id : '---';
                $values[$key]['4'] = $row->totalInstruments;
                $values[$key]['5'] = date('m-d-Y', strtotime(str_replace('/', '-', $row->created_date)));
                if ($row->on_site == 1) {
                    $values[$key]['6'] = "<label class='switch' id='switch_{$row->id}'><input type='checkbox' name='email_verify' class='email_verify' id='email_verify' data-id='$row->id' title='Verify Email' checked><div class='slider round'><span class='on'>YES</span><div class='off'>NO</div></div></label>";
                } else {
                    $values[$key]['6'] = "<label class='switch' id='switch_{$row->id}'><input type='checkbox' name='email_verify' class='email_verify' id='email_verify' data-id='$row->id' title='Verify Email'><div class='slider round'><span class='on'>YES</span><div class='off'>NO</div></div></label>";
                }
//                $values[$key]['6'] = " <div class='mid'> <label class='rocker rocker-small'><input type='checkbox'><span class='switch-left'>Yes</span><span class='switch-right'>No</span> </label>";
//               if($row->on_site == 2){
//                $values[$key]['6'] = " <div class='onoffswitch'><input type='checkbox' name='onoffswitch' class='onoffswitch-checkbox' id='myonoffswitch_{$row->id}' data-id='$row->id' checked><label class='onoffswitch-label' for='myonoffswitch'> <span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span></label></div>";
//               }else{
//                   $values[$key]['6'] = " <div class='onoffswitch'><input type='checkbox' name='onoffswitch' class='onoffswitch-checkbox' id='myonoffswitch_{$row->id}' data-id='$row->id'><label class='onoffswitch-label' for='myonoffswitch'> <span class='onoffswitch-inner'></span><span class='onoffswitch-switch'></span></label></div>";
////
//               }

                $values[$key]['7'] = "<a href=" . url('admin/requestViewDetails/' . $row->id) . " class=''><i class='fa fa-eye'></a>";
                $values[$key]['8'] = "<a href=" . url('admin/viewServiceRequestSummary/' . $row->id) . " class=''><i class='fa fa-eye'></a>";
                $i++;
            }
        }
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    function servicerequestsublist(Request $request)
    {
        $input = Input::all();
        $serviceRequestId = $input['id'];
        $serviceRequest = DB::table('tbl_service_request')->select('customer_id')->where('id', $serviceRequestId)->first();
        $customerId = (isset($serviceRequest->customer_id) && $serviceRequest->customer_id) ? $serviceRequest->customer_id : '';
        $customer_plans = DB::table('tbl_customer_setups')->select('plan_id')->where('customer_id', $customerId)->first();

        $customerPlans = (isset($customer_plans->plan_id) && $customer_plans->plan_id) ? explode(',', $customer_plans->plan_id) : array();

        $items = $this->servicerequest->serviceItemEquipments('', '', array('request_id' => $serviceRequestId));
        if ($customerPlans) {
            $service_plans = DB::table('tbl_service_plan')->select('service_plan_name', 'id')->whereIn('id', $customerPlans)->get();
        } else {
            $service_plans = DB::table('tbl_service_plan')->select('service_plan_name', 'id')->get();
        }
//        $items = $this->servicerequest->serviceItemEquipments('', '', array('request_id' => $serviceRequestId));
//        $service_plans = DB::table('tbl_service_plan')->select('service_plan_name', 'id')->get();
        //$plan_drop[0] = 'Select plan';
        foreach ($service_plans as $key => $row) {
            $plan_drop[$row->id] = $row->service_plan_name;
        }
        $workDetails = array();
        if (!$items->isEmpty()) {
            foreach ($items as $itemkey => $itemval) {

                $workDetails[$itemkey]['assetNo'] = $itemval->asset_no;
                $workDetails[$itemkey]['request_item_id'] = $itemval->request_item_id;
                $workDetails[$itemkey]['service_request_id'] = $itemval->service_request_id;
                $workDetails[$itemkey]['serialNo'] = $itemval->serial_no;
                $workDetails[$itemkey]['instrument'] = $itemval->model_description;
                $workDetails[$itemkey]['location'] = $itemval->location;
                $workDetails[$itemkey]['prefContact'] = $itemval->pref_contact;
                $workDetails[$itemkey]['plan'] = $itemval->service_plan_name;
                $workDetails[$itemkey]['planId'] = $itemval->service_plan_id;
                $workDetails[$itemkey]['nextDueDate'] = Carbon::parse($itemval->next_due_date_up)->format('d-M-Y');

                $checkWorkOrder = $this->servicerequest->checkWorkOrderItems($itemval->request_item_id);

                if ($checkWorkOrder) {
                    $workDetails[$itemkey]['status'] = '1';
                } else {
                    $workDetails[$itemkey]['status'] = '2';
                }
            }
        }

        $view = view('serviceRequest.serviceRequestSubAjax', ['items' => $workDetails, 'service_plans' => $plan_drop])->render();

        echo json_encode(array('result' => true, 'data' => $view));
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
                            if ($itemtemp[$itemkey]['asFound'] == 1) {
                                $itemtemp[$itemkey]['asFoundName'] = 'Yes';
                            } else {
                                $itemtemp[$itemkey]['asFoundName'] = 'N/A';
                            }
                            $itemtemp[$itemkey]['asCalibrated'] = $getDueEquipmentDetails->as_calibrate;
                            if ($itemtemp[$itemkey]['asCalibrated'] == 1) {
                                $itemtemp[$itemkey]['asCalibratedName'] = 'Yes';
                            } else {
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

    public function ViewDetail(Request $request, $requestId)
    {

//        get service request details

        $data = array();
        $title = 'Work Order';
        $getServiceRequest = $this->servicerequest->getserviceRequest($requestId);
        $customerId = $getServiceRequest->customer_id;
        if ($customerId) {
//            for customer info
            $data['getCustomer'] = $this->customer->getCustomer($customerId);
//            for customer billing details
            if (isset($getServiceRequest->billing_address_id) && $getServiceRequest->billing_address_id) {
                $data['getCustomerBilling'] = $this->customer->getBilling($getServiceRequest->billing_address_id);
            } else {
                $data['getCustomerBilling'] = $this->customer->getCustomerBilling($customerId);
            }
            if (isset($getServiceRequest->shipping_address_id) && $getServiceRequest->shipping_address_id) {
                $data['getCustomerShipping'] = $this->customer->getShipping($getServiceRequest->shipping_address_id);
            } else {
                $data['getCustomerShipping'] = $this->customer->getCustomerShipping($customerId);
            }

        }

//        get service Request item details
        $items = $this->servicerequest->serviceItemEquipments('', '', array('request_id' => $requestId, 'plangroupBy' => true));
//        echo '<pre>';print_r($items);die;
        $planDetails = array();
        if ($items) {
            foreach ($items as $itemkey => $itemval) {

                $planId = $itemval->test_plan_id;
                $getworkOrder = $this->workorder->getWorkOrder($planId, $requestId);
                $workOrderCount = 0;
                if (!$getworkOrder->isEmpty()) {
                    $workOrderCount = count($getworkOrder);

                } else {
                }
                $planType = $itemval->service_plan_type;
                if ($planId) {
                    $getServiceRequestItemsbyPlanId = $this->servicerequest->requestItemByPlan($planId, $requestId);
                    $requestItems = $this->servicerequest->serviceItemEquipments('', '', array('request_id' => $requestId, 'planId' => $planId));
//                    $requestItems = $this->servicerequest->serviceItemEquipments('', '', array('request_id' => $requestId, 'planId' => $planId, 'workorder' => true));
//                    echo '<pre>';print_r($requestItems);die;

                    $checkWorkOrderCount = 0;
                    if (!$requestItems->isEmpty()) {
                        $checkWorkOrderCount = count($requestItems);
                        if ($workOrderCount == $checkWorkOrderCount) {
                            $planDetails[$itemkey]['totalWorkOrderStatus'] = 1;
                        } else {
                            $planDetails[$itemkey]['totalWorkOrderStatus'] = 0;
                        }
                        $planDetails[$itemkey]['totalInstruments'] = count($requestItems);
                        $planDetails[$itemkey]['planId'] = $planId;
                        $planDetails[$itemkey]['planName'] = $itemval->service_plan_name;
//                        if ($planType == 1) {
//                            $planDetails[$itemkey]['planType'] = 'Basic';
//                        } elseif ($planType == 2) {
//                            $planDetails[$itemkey]['planType'] = 'Pharamatical';
//                        } elseif ($planType == 3) {
//                            $planDetails[$itemkey]['planType'] = 'Clinical';
//                        } else {
//                            $planDetails[$itemkey]['planType'] = 'N/A';
//                        }
                        $plan_name = DB::table('tbl_customer_type')->where('id', $planType)->first();
//                        echo '<pre>';print_r($plan_name->name);exit;
                        $planDetails[$itemkey]['planType'] = $plan_name->name;

                        foreach ($requestItems as $equipkey => $equipval) {
                            $requestItemId = $equipval->request_item_id;
                            $checkWorkOrder = $this->workorder->checkWorkOrders($requestId, $planId, $requestItemId);
                            if ($checkWorkOrder) {
                                $planDetails[$itemkey]['instrumentDetails'][$equipkey]['workOrderStatus'] = 1;
                                $planDetails[$itemkey]['instrumentDetails'][$equipkey]['workOrderNumber'] = $checkWorkOrder->work_order_number;
                            } else {
                                $planDetails[$itemkey]['instrumentDetails'][$equipkey]['workOrderNumber'] = 'Not Created';
                                $planDetails[$itemkey]['instrumentDetails'][$equipkey]['workOrderStatus'] = 0;
                            }
                            $planDetails[$itemkey]['instrumentDetails'][$equipkey]['equipmentId'] = $equipval->equipmentId;
                            $planDetails[$itemkey]['instrumentDetails'][$equipkey]['request_item_id'] = $equipval->request_item_id;
                            $planDetails[$itemkey]['instrumentDetails'][$equipkey]['equipmentName'] = $equipval->equipmentName;
                            $planDetails[$itemkey]['instrumentDetails'][$equipkey]['assetNo'] = $equipval->asset_no;
                            $planDetails[$itemkey]['instrumentDetails'][$equipkey]['serialNo'] = $equipval->serial_no;
                            $planDetails[$itemkey]['instrumentDetails'][$equipkey]['modelName'] = $equipval->model_description;
                            $planDetails[$itemkey]['instrumentDetails'][$equipkey]['location'] = $equipval->location;
                            $planDetails[$itemkey]['instrumentDetails'][$equipkey]['pref_contact'] = $equipval->pref_contact;

                        }

                    } else {
                        $planDetails = '';
                    }
                }
            }
        }

        $customer = DB::table('tbl_customer')->pluck('customer_name', 'id');
        $customer->prepend('Please Choose Customer', '');

        $technician = DB::table('tbl_technician')->pluck('first_name', 'id');
        $technician->prepend('Please Choose Technician', '');


        return view('serviceRequest.workOrderAssign')->with('customerDetails', $data)->with('planDetails', $planDetails)->with('getServiceRequest', $getServiceRequest)->with('technician', $technician)->with('customer', $customer)->with('requestId', $requestId)->with('title', $title);


    }

    function requestDetailsEdit(Request $request)
    {
        $input = Input::all();
        $data = array();
        $data['curr_id'] = $input['curr_id'];
        $data['customer_id'] = $input['customer_id'];
        $keyattr = $input['attr'];
        $temp = array();
        $customer = $this->customer->getCustomer($data['customer_id']);
        if ($keyattr == 'billing') {
            $customer_properties = $this->customer->getCustomerBillingAll($data['customer_id']);
            $title = 'Billing address lists';
        } else {
            $customer_properties = $this->customer->getCustomerShippingAll($data['customer_id']);
            $title = 'Shipping address lists';
        }
        if ($customer_properties) {
            foreach ($customer_properties as $key => $row) {
                $temp[$key]['id'] = $row->id;
                $temp[$key]['name'] = $keyattr == 'billing' ? $row->billing_contact : $row->customer_name;
                $temp[$key]['phone'] = $keyattr == 'billing' ? $row->phone : $row->phone_num;
                $temp[$key]['email'] = $keyattr == 'billing' ? $row->email : $row->email;
                $temp[$key]['address1'] = $keyattr == 'billing' ? $row->address1 : $row->address1;
                $temp[$key]['address2'] = $keyattr == 'billing' ? $row->address2 : $row->address2;
                $temp[$key]['city'] = $keyattr == 'billing' ? $row->city : $row->city;
                $temp[$key]['state'] = $keyattr == 'billing' ? $row->state : $row->state;
                $temp[$key]['zip'] = $keyattr == 'billing' ? $row->zip_code : $row->zip_code;
            }
        }
        $data['customer_properties'] = $temp;
        $view = view('serviceRequest.editPropertiesAjax', ['data' => $data])->render();

        echo json_encode(array('result' => true, 'data' => $view, 'customer' => $customer->customer_name, 'title' => $title, 'attr' => $keyattr));

    }


    function saveajaxcustomerproperty()
    {
        $post = Input::all();
        //echo'<pre>';print_r($post);'</pre>';die;
        if ($post) {
            if ($post['keyattr'] == 'billing') {
                $save['id'] = $post['id'];
                $save['billing_contact'] = $post['name'];
                $save['address1'] = $post['address1'];
                $save['address2'] = $post['address2'];
                $save['city'] = $post['city'];
                $save['state'] = $post['state'];
                $save['zip_code'] = $post['zipcode'];
                $save['phone'] = $post['phone'];
                $save['email'] = $post['email'];
                $this->customer->saveBilling($save);
                $msg = 'Billing details are updated';
            } elseif ($post['keyattr'] == 'shipping') {
                $save['id'] = $post['id'];
                $save['customer_name'] = $post['name'];
                $save['address1'] = $post['address1'];
                $save['address2'] = $post['address2'];
                $save['city'] = $post['city'];
                $save['state'] = $post['state'];
                $save['zip_code'] = $post['zipcode'];
                $save['phone_num'] = $post['phone'];
                $save['email'] = $post['email'];
                $this->customer->saveShipping($save);
                $msg = 'Shipping details are updated';
            }

            die(json_encode(array('result' => true, 'message' => $msg)));

        }
    }

    function updateProperty(Request $request)
    {
        $input = Input::all();
        $data = array();
        if ($input['keyattr'] == 'billing') {
            $save['id'] = $input['serviceRequestId'];
            $save['billing_address_id'] = $input['id'];
        } elseif ($input['keyattr'] == 'shipping') {
            $save['id'] = $input['serviceRequestId'];
            $save['shipping_address_id'] = $input['id'];
        }
        $id = $this->servicerequest->saveRequest($save);
        if ($input['keyattr'] == 'billing') {
            $property = $this->customer->getBilling($input['id']);
            $msg = 'Billing address has been updated';
        } elseif ($input['keyattr'] == 'shipping') {
            $property = $this->customer->getShipping($input['id']);
            $msg = 'Shipping address has been updated';
        }
        $keyattr = $input['keyattr'];

        $temp['id'] = $property->id;
        $temp['name'] = $keyattr == 'billing' ? $property->billing_contact : $property->customer_name;
        $temp['phone'] = $keyattr == 'billing' ? $property->phone : $property->phone_num;
        $temp['email'] = $keyattr == 'billing' ? $property->email : $property->email;
        $temp['address1'] = $keyattr == 'billing' ? $property->address1 : $property->address1;
        $temp['address2'] = $keyattr == 'billing' ? $property->address2 : $property->address2;
        $temp['city'] = $keyattr == 'billing' ? $property->city : $property->city;
        $temp['state'] = $keyattr == 'billing' ? $property->state : $property->state;
        $temp['zip'] = $keyattr == 'billing' ? $property->zip_code : $property->zip_code;
        echo json_encode(array('result' => true, 'data' => $temp, 'keyattr' => $keyattr, 'id' => $input['id'], 'msg' => $msg));

    }

    function mainDetailsEdit(Request $request)
    {
        $input = Input::all();
        $data = array();
        $data['id'] = $input['id'];
        $data['attr'] = $input['attr'];
        $keyattr = $input['attr'];
        $temp = array();
        $customer = $this->customer->getCustomer($data['id']);
        if ($keyattr == 'customer') {
            $property = $this->customer->getCustomer($data['id']);
            $title = 'Customer details';
        } else {
            $property = $this->customer->getCustomer($data['id']);
            $title = 'Payment details';
        }
        //echo'<pre>';print_r($property);'</pre>';die;
        if ($property) {
            $temp['id'] = $property->id;
            $temp['name'] = $property->customer_name;
            $temp['phone'] = $property->customer_telephone;
            $temp['email'] = $property->customer_email;
            $temp['address1'] = $property->address1;
            $temp['address2'] = $property->address2;
            $temp['primarycontact'] = $property->primary_contact;
            $temp['city'] = $property->city;
            $temp['state'] = $property->state;
            $temp['zip'] = $property->zip_code;
        }
        $data['details'] = $temp;
        $view = view('serviceRequest.editCustomerPropertiesAjax', ['details' => $temp])->render();

        echo json_encode(array('result' => true, 'data' => $view, 'attr' => $keyattr));

    }

    function customerDetailsSubmit(Request $request)
    {

        $post = Input::All();

//        echo '<pre>';print_r($post);die;
        if ($post) {
            $save['id'] = $post['id'];
            $save['customer_name'] = $post['name'];
            $save['address1'] = $post['address1'];
            $save['address2'] = $post['address2'];
            $save['primary_contact'] = $post['primarycontact'];
//            $save['primary_contact'] = $post['primarycontact'];
            $save['city'] = $post['city'];
            $save['state'] = $post['state'];
            $save['zip_code'] = $post['zip'];
            $save['customer_telephone'] = $post['tel'];
//            echo '<pre>';print_r($save);die;

            $id = $this->customer->saveCustomerProfile($save);
            $customer = $this->customer->getCustomer($id);

        }
        echo json_encode(array('result' => true, 'data' => $customer));
    }

    function requestItemsPlanEdit()
    {
        $post = Input::all();
        $save['id'] = $post['request_item_id'];
        $save['test_plan_id'] = $post['plan_id'];
        $this->servicerequest->saveItems($save);
        die(json_encode(array('result' => true)));

    }

    function paymentedit(Request $request)
    {
        $input = Input::all();

//        echo '<pre>';print_r($input);die;
        $data = array();
        $data['id'] = $input['id'];
        $data['attr'] = $input['attr'];
        $keyattr = $input['attr'];
//        print_r($keyattr);die;
        $temp = array();
        $customer = $this->customer->getCustomer($data['id']);
        if ($keyattr == 'customer') {
            $property = $this->customer->getCustomerSetup($data['id']);
            $title = 'Customer details';
        } else {
            $property = $this->customer->getCustomer($data['id']);
            $title = 'Payment details';
        }

//        echo'<pre>';print_r($property);'</pre>';die;
        if ($property) {
            $temp['id'] = isset($property->id) ? $property->id : '';
            $temp['customer_id'] = isset($property->customer_id) ? $property->customer_id : '';
            $temp['pay_method'] = isset($property->pay_method) ? $property->pay_method : '';
            $temp['pay_terms'] = isset($property->pay_terms) ? $property->pay_terms : '';

        } else {
            $temp['id'] = '';
            $temp['customer_id'] = $data['id'];
            $temp['pay_method'] = '';
            $temp['pay_terms'] = '';
        }
        $selectPayMethods = DB::table('tbl_pay_method')->select('*')->get();

        $value['details'] = $temp;
        $value['paymethods'] = $selectPayMethods;

//        echo '<pre>';print_r($value);die;

        $view = view('serviceRequest.editPayment', ['details' => $value])->render();

        echo json_encode(array('result' => true, 'data' => $view, 'attr' => $keyattr));

    }

    function paymentDetailsSubmit(Request $request)
    {

        $post = Input::All();

//        echo "<pre>";print_r($post);"</pre>";die;

        if ($post) {
            $save['customer_id'] = $post['id'];
            $save['pay_method'] = $post['payMethod'];
            $save['pay_terms'] = $post['paymentTerms'];
            if ($post['id']) {
                DB::table('tbl_customer_setups')->where('customer_id', '=', $post['id'])->update($save);

            } else {
                DB::table('tbl_customer_setups')->insertGetId($save);
            }
        }

        $getCustomerSetup = $this->customer->getCustomerSetup($post['id']);


        echo json_encode(array('result' => true, 'data' => $getCustomerSetup));
    }

    public function ViewSummary(Request $request, $requestId)
    {

//        get service request details

        $data = array();
        $title = 'Work Order';
        $getServiceRequest = $this->servicerequest->getserviceRequest($requestId);
        $customerId = $getServiceRequest->customer_id;
        if ($customerId) {
//            for customer info
            $data['getCustomer'] = $this->customer->getCustomer($customerId);
//            for customer billing details
            if (isset($getServiceRequest->billing_address_id) && $getServiceRequest->billing_address_id) {
                $data['getCustomerBilling'] = $this->customer->getBilling($getServiceRequest->billing_address_id);
            } else {
                $data['getCustomerBilling'] = $this->customer->getCustomerBilling($customerId);
            }
            if (isset($getServiceRequest->shipping_address_id) && $getServiceRequest->shipping_address_id) {
                $data['getCustomerShipping'] = $this->customer->getShipping($getServiceRequest->shipping_address_id);
            } else {
                $data['getCustomerShipping'] = $this->customer->getCustomerShipping($customerId);
            }

        }
        if ($customerId) {
            $shipping_id = DB::table('tbl_customer_setups')->where('customer_id', '=', $customerId)->first();
            if ($shipping_id) {
                $shipping_cost = DB::table('tbl_shipping')->where('id', '=', $shipping_id->shipping)->first();
                $Shipping_Charge = isset($shipping_cost->shipping_charge) ? $shipping_cost->shipping_charge : 0;
            }
        }

        $getServiceRequests = DB::table('tbl_service_request_item')->where('service_request_id', $requestId)->get();
//        $workorderid = $requestId;
//        $select = array('E.asset_no', 'E.serial_no', 'E.pref_contact', 'S.request_no', 'SP.price',
//            'E.pref_tel', 'E.pref_email', 'E.location', 'SR.id as serviceReqItemId', 'SR.service_request_id as reqId', 'WOI.request_item_id as servicerequestitemid',
//            'SR.due_equipments_id as dueequipmentid', 'EM.model_description', 'DE.id as dueEquipmentId', 'E.id as equipmentId',
//            'DE.equipment_id as equipmentid', 'EM.id as equipmentModelId', 'E.equipment_model_id as equipmentmodelid'
//        );
        $select = array('E.asset_no', 'E.serial_no', 'E.pref_contact', 'S.request_no', 'SP.price',
            'E.pref_tel', 'E.pref_email', 'E.location', 'SR.id as serviceReqItemId', 'SR.service_request_id as reqId',
            'SR.due_equipments_id as dueequipmentid', 'EM.model_description', 'DE.id as dueEquipmentId', 'E.id as equipmentId',
            'DE.equipment_id as equipmentid', 'EM.id as equipmentModelId', 'E.equipment_model_id as equipmentmodelid'
        );
        $servicerequest = DB::table('tbl_service_request')->where('id', $requestId)->first();
        $data['servicerequest'] = $servicerequest->request_no;
        $data['request_id'] = $requestId;
        $data['discount'] = $servicerequest->discount;
        $data['on_site'] = $servicerequest->on_site;
//        $a=0;
        $temp = array();
        $orderItemAmount = 0;
        $i = 1;
        $totalSpareAmt = 0;
        foreach ($getServiceRequests as $orderkey => $ordervalue) {
//            echo '<pre>';
//            print_r($getServiceRequests);
//            exit;
//            $orderItems = $this->payment->workorderItems($ordervalue->id, array('select' => $select));
            $orderItems = $this->payment->Servicerequestitems($ordervalue->id, array('select' => $select));
//            Servicerequestitems
//            echo '<pre>';
//            print_r($orderItems);
//            exit;

            if ($orderItems) {


//                foreach ($orderItems as $orderkey => $orderval) {
                $orderItemAmount += $orderItems->price;
                $serviceReqItemId = $orderItems->serviceReqItemId;
                $param = array();
                if ($serviceReqItemId) {
                    $getSpareParts = $this->payment->getParts($serviceReqItemId);


                    $y = 1;
                    $partAmount = 0;
                    if ($getSpareParts) {
                        $workOrderSpares = json_decode($getSpareParts->workorder_spares);
                        $workOrderchecklist = explode(',', $getSpareParts->workorder_checklists);
//                        echo '<pre>';print_r($workOrderchecklist);exit;
                        if ($workOrderchecklist) {
                            foreach ($workOrderchecklist as $row) {
                                $ID = str_replace('~', '', $row);
                                $getchecklist = $this->payment->getChecklist($ID);
                                $checklist[$orderkey][] = $getchecklist->title;
                            }
                        }

                        if ($workOrderSpares) {

                            foreach ($workOrderSpares as $sparekey => $spareval) {
                                $partAmount += $spareval->amount;
                                $partId = $spareval->id;
                                $getPartDetails = $this->payment->getPartDetails($partId);
                                if ($getPartDetails) {
                                    $param[$sparekey]['spareId'] = $getPartDetails->id;
                                    $param[$sparekey]['model_id'] = $getPartDetails->model_id;
                                    $param[$sparekey]['SKU'] = $getPartDetails->sku_number;
                                    $param[$sparekey]['spareMode'] = $getPartDetails->mode_name;
                                    $param[$sparekey]['partName'] = $getPartDetails->part_name;
                                    $param[$sparekey]['partPrice'] = $getPartDetails->service_price;
                                    $param[$sparekey]['totalAmount'] = $spareval->amount;
                                    $param[$sparekey]['totalQuantity'] = $spareval->quantity;
                                    $param[$sparekey]['serialNumber'] = $i . '.' . $y;


                                    $y++;
                                }
//                                $param['partAmount'] =$partAmount;
                            }
                        }
                        foreach ($checklist as $row) {
                            $temp[$orderkey]['checklistName'] = implode(',', $row);
                        }
                    }
                }


                $temp[$orderkey]['order_item_amt'] = $orderItems->price;
                $servicerequest = $orderItems->request_no;
//                    $temp[$orderkey]['request_no'] = $orderval->request_no;
                $temp[$orderkey]['asset_no'] = $orderItems->asset_no;
                $temp[$orderkey]['serial_no'] = $orderItems->serial_no;
                $temp[$orderkey]['pref_contact'] = $orderItems->pref_contact;
                $temp[$orderkey]['pref_tel'] = $orderItems->pref_tel;
                $temp[$orderkey]['pref_email'] = $orderItems->pref_email;
                $temp[$orderkey]['location'] = $orderItems->location;
//                $temp[$orderkey]['serviceReqItemId'] = $orderItems->serviceReqItemId;
//                $temp[$orderkey]['servicerequestitemid'] = $orderItems->servicerequestitemid;
                $temp[$orderkey]['dueequipmentid'] = $orderItems->dueequipmentid;
                $temp[$orderkey]['model_description'] = $orderItems->model_description;
                $temp[$orderkey]['dueEquipmentId'] = $orderItems->dueEquipmentId;
                $temp[$orderkey]['equipmentId'] = $orderItems->equipmentId;
                $temp[$orderkey]['equipmentid'] = $orderItems->equipmentid;
                $temp[$orderkey]['equipmentModelId'] = $orderItems->equipmentModelId;
                $temp[$orderkey]['equipmentmodelid'] = $orderItems->equipmentmodelid;
                $temp[$orderkey]['partdetails'] = $param;

//                echo '<pre>';print_r($temp);exit;

                $totalSpareAmt += $partAmount;


                $totalAmount = $totalSpareAmt + $orderItemAmount;

                $data['orderItems'] = $temp;
                $data['shipping_Charge'] = $Shipping_Charge;
                $data['totalAmount'] = $totalAmount;


                if ($data['discount']) {
                    if ($data['on_site'] == 2) {
                        $data['grand_total'] = $totalAmount + $Shipping_Charge - $data['discount'];
                        $data['total'] = $totalAmount + $Shipping_Charge;
                    } else {
                        $data['grand_total'] = $totalAmount - $data['discount'];
                        $data['total'] = $totalAmount;
                    }


                } else {
                    if ($data['on_site'] == 2) {
                        $data['grand_total'] = $totalAmount + $Shipping_Charge;
                        $data['total'] = $totalAmount + $Shipping_Charge;
                    } else {
                        $data['grand_total'] = $totalAmount;
                        $data['total'] = $totalAmount;
                    }

                }

                $i++;
            }
        }


       // echo '<pre>';print_r($data);exit;


        return view('serviceRequest.serviceRequestSummary')->with('customerDetails', $data)->with('title', $title);

    }

//save discount in service request:
    public
    function saveDiscountPrice()
    {

        $post = Input::all();
//        echo '<pre>';print_r($post);exit;
        if ($post) {
            $save['discount'] = $post['discountPrice'];
            $saveRequest = DB::table('tbl_service_request')->where('id', $post['id'])->update([
                'discount' => $post['discountPrice'],
            ]);
        }
        if (($post['discountPrice'])) {
            die(json_encode(array('result' => true, 'message' => 'DiscountPrice is updated', 'updated' => true)));
        } else {
            die(json_encode(array('result' => false, 'message' => 'DiscountPrice is not updated', 'updated' => false)));
        }

    }
    public function onSiteChange(Request $request){
            $input = Input::all();

            if ($input['Id']) {
                $result = DB::table('tbl_service_request')->where('id', '=', $input['Id'])->first();
//                echo '<pre>';print_r($result->on_site);exit;
                if ($result->on_site == 2) {
                    $result = DB::table('tbl_service_request')->where('id', '=', $input['Id'])->update([
                            'on_site' => 1,
                    ]);
                }else{
                    $result = DB::table('tbl_service_request')->where('id', '=', $input['Id'])->update([
                        'on_site' => 2,
                    ]);
                }
            }
            if ($result) {
                die(json_encode(array('result' => true, "message" => 'Changed Successfully')));

            } else {
                die(json_encode(array('result' => false, "message" => 'Not Changed Successfully')));

            }
    }

}
