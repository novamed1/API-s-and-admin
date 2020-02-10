<?php

namespace App\Http\Controllers\web\calibration;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Device;
use App\Models\Technician;
use App\Models\Workorder;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use App\Servicerequest;

use Response;
use Validator;
use View;
use Mail;
use PDF;
use Image;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Workorderitemmovemodel;
use Storage;


//use Request;

class QualityController extends Controller
{
    public function __construct()
    {
        $this->workorder = new Workorder();
        $this->serviceModel = new Servicerequest();
        $this->technician = new Technician();
        $this->workorderitemmove = new Workorderitemmovemodel();
    }

//    public function qualityCheck(Request $request)
//    {
//        $input = Input::all();
//        $title = 'Novamed-Quality Check';
//        $perPage = 10;
//        $currentPage = LengthAwarePaginator::resolveCurrentPage();
//        $offset = ($currentPage * $perPage) - $perPage;
//        $status = array('' => 'Please Select', '2' => 'In Progress', '3' => 'Complete');
//
//        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
//        $statuskeyword = isset($input['status']) ? $input['status'] : '';
//
//        if ($keyword != ""|| $statuskeyword != '') {
//
//            $select = array('two.id as workOrderId', 'two.work_order_number as workOrderNumber',
//                'two.as_found as workAsFound', 'two.as_calibrated as workAsCalibrated',
//                'S.service_plan_name as planName', 'S.as_found as planAsFound',
//                'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
//                'SR.service_schedule_date as serviceSchedule', 'SR.customer_id as customerId',
//                'C.customer_name', 'two.maintanence_to as maintanenceTo',
//                'two.calibration_to as calibrationTo', 'two.status as workOrderStatus',
//                'two.workorder_date as workOrderDate', 'two.technician_review as technicianReview', 'two.work_progress as workProgress', 'two.admin_review', 'two.report');
//         $data['search'] = $keyword;
//
//            $data = $this->workorder->CalibrationDetails('', '', array('select' => $select,'status' => $statuskeyword, 'groupBy' => true,'search'=>$data['search']), 'DESC', 'two.id', false, array('twoi
//            .report', 'twoi.technician_review_date', 'two.work_order_number', 'e.asset_no', 'e.serial_no',
//                'tt.first_name','C.customer_name','S.service_plan_name','SR.request_no','two.workorder_date'));
//
//        } else {
//
//            $select = array('two.id as workOrderId', 'two.work_order_number as workOrderNumber',
//                'two.as_found as workAsFound', 'two.as_calibrated as workAsCalibrated',
//                'S.service_plan_name as planName', 'S.as_found as planAsFound',
//                'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
//                'SR.service_schedule_date as serviceSchedule', 'SR.customer_id as customerId',
//                'C.customer_name', 'two.maintanence_to as maintanenceTo',
//                'two.calibration_to as calibrationTo', 'two.status as workOrderStatus',
//                'two.workorder_date as workOrderDate', 'two.technician_review as technicianReview', 'two.work_progress as workProgress', 'two.admin_review', 'two.report');
//
//
//            $data = $this->workorder->CalibrationDetails('', '', array('select' => $select, 'groupBy' => true), 'DESC', 'two.id');
//           //print_r($data);die;
//            $count = $this->workorder->CalibrationDetails('', '', array('select' => $select, 'groupBy' => true), 'DESC', 'two.id', true);
//
//        }
//        $temp = array();
//        if (!$data->isEmpty()) {
//
//            foreach ($data as $key => $val) {
//                $workOrderId = $val->workOrderId;
//                $temp[$key]['workOrderId'] = $val->workOrderId;
//                $temp[$key]['workOrderNumber'] = $val->workOrderNumber;
//                $temp[$key]['AsFound'] = $val->workAsFound;
//                $temp[$key]['workProgress'] = $val->workProgress;
//
//                if ($temp[$key]['AsFound'] == 1) {
//                    $temp[$key]['workAsFound'] = 'Yes';
//                } else {
//                    $temp[$key]['workAsFound'] = 'N/A';
//                }
//                $temp[$key]['AsCalibrated'] = $val->workAsCalibrated;
//                if ($temp[$key]['AsCalibrated'] == 1) {
//                    $temp[$key]['workAsCalibrated'] = 'Yes';
//                } else {
//                    $temp[$key]['workAsCalibrated'] = 'N/A';
//                }
//                $temp[$key]['planName'] = $val->planName;
//                $temp[$key]['planAsFound'] = $val->planAsFound;
//                $temp[$key]['planAsCalibrate'] = $val->planAsCalibrate;
//                $temp[$key]['reqNumber'] = $val->reqNumber;
//                $temp[$key]['serviceSchedule'] = $val->serviceSchedule;
//                $temp[$key]['customer_name'] = $val->customer_name;
//                $temp[$key]['maintanenceTo'] = $val->maintanenceTo;
//                $temp[$key]['calibrationTo'] = $val->calibrationTo;
//                $temp[$key]['customerId'] = $val->customerId;
//                $temp[$key]['technicianReview'] = $val->technicianReview;
//                $temp[$key]['admin_review'] = $val->admin_review;
//                $temp[$key]['report'] = $val->report;
//
//                $findMaintanence = $this->technician->getTechnician($temp[$key]['maintanenceTo']);
//                $findCalibarte = $this->technician->getTechnician($temp[$key]['calibrationTo']);
//                if ($findMaintanence) {
//                    $temp[$key]['maintaainedBy'] = $findMaintanence->first_name;
//                } else {
//                    $temp[$key]['maintaainedBy'] = '';
//                }
//                if ($findCalibarte) {
//                    $temp[$key]['calibratedBy'] = $findMaintanence->first_name;
//                } else {
//                    $temp[$key]['calibratedBy'] = '';
//                }
//                $temp[$key]['workOrderDate'] = $val->workOrderDate;
//                $temp[$key]['workOrderStatus'] = $val->workOrderStatus;
//
//                $select = array('twoi.id as workOrderItemId',
//                    'twoi.work_order_id as workOrderId',
//                    'twoi.admin_review as adminReview',
//                    'twoi.request_item_id as requestItemId',
//                    'twoi.report as report', 'twoi.technician_review as reviewdTechnicianId',
//                    'twoi.technician_review_date as technicianReviewDate',
//                    'two.work_order_number as workOrderNumber',
//                    'ri.due_equipments_id as dueEquipId',
//                    'e.asset_no as assetNumber',
//                    'e.serial_no as serialNumber',
//                    'tt.first_name as reviewdTechnician');
//
//                $items = $this->workorder->CalibrationDetails('', '', array('select' => $select, 'workOrderId' => $workOrderId), 'DESC', 'twoi.id');
//
//
//                $workDetails = array();
//                if (!$items->isEmpty()) {
//                    foreach ($items as $itemkey => $itemval) {
//                        $temp[$key]['workDetails'][$itemkey]['assetNumber'] = $itemval->assetNumber;
//                        $temp[$key]['workDetails'][$itemkey]['serialNumber'] = $itemval->serialNumber;
//                        $temp[$key]['workDetails'][$itemkey]['reviewdTechnician'] = $itemval->reviewdTechnician;
//                        $temp[$key]['workDetails'][$itemkey]['report'] = $itemval->report;
//                        $temp[$key]['workDetails'][$itemkey]['adminReview'] = $itemval->adminReview;
//                        $temp[$key]['workDetails'][$itemkey]['workOrderItemId'] = $itemval->workOrderItemId;
////                        $temp[$key]['workDetails'][$itemkey]['doclink'] = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/public/report/technicianreview/' . $itemval->report;
//                        $temp[$key]['workDetails'][$itemkey]['doclink'] = 'http://' . $_SERVER['SERVER_NAME'] . '/public/report/technicianreview/' . $itemval->report;
//                        $temp[$key]['workDetails'][$itemkey]['reviewlink'] = url('admin/reviewWorkOrder/' . $itemval->workOrderItemId . '');
//                    }
//                }
//            }
//        }
//
//
//        $itemCollection = collect($temp);
//        $paginatedItems = new LengthAwarePaginator($itemCollection, count($temp), $perPage);
//        $paginatedItems->setPath($request->url());
//
//
//        return view('calibration.qualitycheck')->with('data', $paginatedItems)->with('statuskeyword', $statuskeyword)->with('status', $status)->with('title', $title)->with('keyword', $keyword);
//    }

    public function qualityCheck(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Quality Check';
        $customer = DB::table('tbl_customer')->pluck('customer_name', 'customer_name');
        $customer->prepend('');
        return view('calibration.qualitycheck')->with('title', $title)->with('customer', $customer);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['C.customer_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['SR.request_no'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['wo.work_order_number'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['totalInstruments'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';
        $search['S.service_plan_name'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';
        $search['M.first_name'] = isset($input['sSearch_6']) ? $input['sSearch_6'] : '';
        $search['CA.first_name'] = isset($input['sSearch_7']) ? $input['sSearch_7'] : '';


        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('wo.id as workOrderId', 'wo.work_order_number as workOrderNumber',
            'wo.as_found as workAsFound', 'wo.as_calibrated as workAsCalibrated',
            'S.service_plan_name as planName', 'S.as_found as planAsFound',
            'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
            'SR.service_schedule_date as serviceSchedule', 'SR.customer_id as customerId',
            'C.customer_name', 'wo.maintanence_to as maintanenceTo',
            'wo.calibration_to as calibrationTo', 'wo.status as workOrderStatus',
            'wo.workorder_date as workOrderDate', 'wo.technician_review as technicianReview', 'wo.work_progress as workProgress', 'wo.admin_review', 'wo.report',
            'M.first_name as mName', 'CA.first_name as cName', 'S.calibration_outside',
            DB::raw("(SELECT COUNT(oi.id) FROM tbl_work_order_items as oi
                JOIN tbl_service_request_item as ri ON ri.id=oi.request_item_id
                JOIN tbl_due_equipments as d ON d.id=ri.due_equipments_id 
                JOIN tbl_equipment as e ON e.id=d.equipment_id where oi.work_order_id=wo.id) as totalInstruments"));
        $data = $this->workorder->CalibrationDetailsGrid($param['limit'], $param['offset'], 'wo.id', 'DESC', array('select' => $select, 'search' => $search, 'admin' => 1, 'totalInstruments' => $input['sSearch_4']),
            false);

        $count = $this->workorder->CalibrationDetailsGrid($param['limit'], $param['offset'], 'wo.id', 'DESC', array('select' => $select, 'search' => $search, 'admin' => 1, 'count' => true, 'totalInstruments' => $input['sSearch_4']),
            false); //print_r(count($count));die;
        $count = count($count);
//        echo '<pre>';print_r($data);exit;
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                if ($row->workProgress == 3) {
                    $qc_status = ' <span class="label label-success">Complete</span>';
                } else {
                    $qc_status = ' <span class="label label-danger">Progress</span>';
                }
                if ($row->calibration_outside != 1) {
                    if ($row->workProgress == 3) {

                        if ($row->technicianReview) {
//                      $view_option = '<a href="'.url("admin/qcConsolidateReview/".$row->workOrderId).'"
//                                           class=""
//                                           id="viewDetails"> <i
//                                           class="fa fa-search review"></i></a>';

                            $report_option = '<div class="share-button openHoverAction-class"
                                                                     style="display: block;">
                                                                    <label class="entypo-export" data-id="' . $row->workOrderId . '"><span>
                                                                    <i class="fa fa-map-o"></i>
</span></label>
                                                                                    
                                                                                    <div class="social show-moreOptions for-five openPops_3379">
                                                                        <ul>
                                                                            <li class="entypo-twitter"
                                                                                data-network="twitter"><a
                                                                                        href="' . url("admin/qcConsolidateReview/" . $row->workOrderId) . '"
                                                                                        data-toggle="tooltip" title=""
                                                                                        data-original-title="View">
                                                                                    <i class="fa fa-view"></i>
                                                                                </a></li>
                                                                            <li class="entypo-facebook indreview"
                                                                                data-network="facebook"><a
                                                                                        href="javascript:void(0);"
                                                                                    data-id="' . $row->workOrderId . '"
                                                                                        data-toggle="tooltip" title=""
                                                                                        data-original-title="Generate PDF">
                                                                                    <img style="width:30px; height:30px" src="' . asset("img/generate.png") . '"
                                                                                         alt="active">
                                                                                </a></li>

                                                                        </ul>
                                                                    </div>
                                                                    
                                                                </div>';

                            $view_option = '<a href="' . url("admin/qcConsolidateReview/" . $row->workOrderId) . '"
                                           class=""
                                           id="viewDetails"> <i
                                           class="fa fa-search review"></i></a>';

                        } else {
                            $report_option = '';
                            $view_option = '';
                        }
                    } else {
                        $report_option = '';
                        $view_option = '';
                    }
                } else {
                    $report_option = '';
                    $view_option = '';
                }
                if ($row->calibration_outside != 1) {
                    if ($row->workProgress == 3) {
                        if ($row->technicianReview) {
                            $mail_option = '<a href="javascript:void(0);"
                                           class="sendMail"
                                           data-id="' . $row->workOrderId . '"
                                           id="viewDetails"> <i
                                           class="fa fa-envelope review"></i></a>';
                        } else {
                            $mail_option = '';
                        }
                    } else {
                        $mail_option = '';
                    }
                } else {
                    $mail_option = '';
                }

                $values[$key]['0'] = '<span class="lead_numbers" data-id=' . $row->workOrderId . '>
                                                   <a href="javascript:void(0)"
                                                      id="isotolerances"
                                                      rel=' . $row->workOrderId . '
                                                      data-toggle="collapse"
                                                      data-target="#isotolerances' . $row->workOrderId . '"
                                                      data-id=' . $row->workOrderId . '
                                                      data-parent="#tagcollapse"
                                                      class="accordion-toggle hov aHrefCollapse"
                                                   ><i
                                                               class="fa fa-plus-circle ordericon collapseico"
                                                               data-widget-collapsed="true"
                                                               data-attr=' . $row->workOrderId . '></i> <span
                                                               class="menu-item-parent"></span></a>
                                                      </span>';
                $values[$key]['1'] = $row->customer_name;
                $values[$key]['2'] = $row->reqNumber;
                $values[$key]['3'] = $row->workOrderNumber;
                $values[$key]['4'] = $row->totalInstruments;
                $values[$key]['5'] = $row->planName;
                $values[$key]['6'] = $row->mName;
                $values[$key]['7'] = $row->cName;
                $values[$key]['8'] = $qc_status;
                $values[$key]['9'] = $report_option;
                $values[$key]['10'] = $view_option;
                $values[$key]['11'] = $mail_option;
                $i++;
            }

        }
        //echo'<pre>';print_r($data);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    function qcsublists(Request $request)
    {
        $input = Input::all();
        $workorderId = $input['id'];
        $select = array('twoi.id as workOrderItemId',
            'twoi.work_order_id as workOrderId',
            'twoi.admin_review as adminReview',
            'twoi.request_item_id as requestItemId',
            'twoi.report as report', 'twoi.technician_review as reviewdTechnicianId',
            'twoi.technician_review_date as technicianReviewDate',
            'ri.due_equipments_id as dueEquipId',
            'e.asset_no as assetNumber', 'e.name as instrumentName', 'e.pref_contact as preferredContact','e.location',
            'tem.model_description as instrumentModel',
            'e.serial_no as serialNumber',
            'tt.first_name as reviewdTechnician');

        $items = $this->workorder->CalibrationDetails('', '', array('select' => $select, 'workOrderId' => $workorderId), 'DESC', 'twoi.id');
        $workDetails = array();
        if (!$items->isEmpty()) {
            foreach ($items as $itemkey => $itemval) {
                $workDetails[$itemkey]['assetNumber'] = $itemval->assetNumber;
                $workDetails[$itemkey]['serialNumber'] = $itemval->serialNumber;
                $workDetails[$itemkey]['reviewdTechnician'] = $itemval->reviewdTechnician;
                $workDetails[$itemkey]['report'] = $itemval->report;

                $workDetails[$itemkey]['instrumentModel'] = $itemval->instrumentModel;
                $workDetails[$itemkey]['location'] = $itemval->location;
                $workDetails[$itemkey]['preferredContact'] = $itemval->preferredContact;
                $workDetails[$itemkey]['workOrderItemId'] = $itemval->workOrderItemId;
                $workDetails[$itemkey]['instrumentName'] = $itemval->instrumentName;

                //                        $temp[$key]['workDetails'][$itemkey]['doclink'] = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/public/report/technicianreview/' . $itemval->report;

                $workDetails[$itemkey]['doclink'] = 'http://' . $_SERVER['SERVER_NAME'] . '/public/report/technicianreview/' . $itemval->report;
                $workDetails[$itemkey]['reviewlink'] = url('admin/reviewWorkOrder/' . $itemval->workOrderItemId . '');
            }
        }

        $view = view('calibration.qcSublistAjax', ['items' => $workDetails])->render();

        echo json_encode(array('result' => true, 'data' => $view));
    }

    public
    function getqualityWorkOrderItems(Request $request)
    {
        $input = Input::all();
        $workOrderId = $input['workOrderId'];
        $getWorkOrder = $this->workorder->getParticularWorkOrder($workOrderId);
        if (!$getWorkOrder) {
            die(json_encode(array('result' => false, 'message' => 'Work Order details are not found')));
        }

        $requestId = $getWorkOrder->request_id;
        $planId = $getWorkOrder->plan_id;

        $select = array('twoi.id as workOrderItemId',
            'twoi.work_order_id as workOrderId',
            'twoi.request_item_id as requestItemId',
            'twoi.report as report', 'twoi.technician_review as reviewdTechnicianId',
            'twoi.technician_review_date as technicianReviewDate',
            'two.work_order_number as workOrderNumber',
            'ri.due_equipments_id as dueEquipId',
            'e.asset_no as assetNumber',
            'e.serial_no as serialNumber',
            'tt.first_name as reviewdTechnician');

        $items = $this->workorder->CalibrationDetails('', '', array('select' => $select, 'workOrderId' => $workOrderId), 'DESC', 'twoi.id');


        $workDetails = array();
        if (!$items->isEmpty()) {
            foreach ($items as $itemkey => $itemval) {
                $workDetails[$itemkey]['assetNumber'] = $itemval->assetNumber;
                $workDetails[$itemkey]['serialNumber'] = $itemval->serialNumber;
                $workDetails[$itemkey]['reviewdTechnician'] = $itemval->reviewdTechnician;
                $workDetails[$itemkey]['report'] = $itemval->report;
                $workDetails[$itemkey]['workOrderItemId'] = $itemval->workOrderItemId;
//                $workDetails[$itemkey]['doclink'] = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/public/report/technicianreview/' . $itemval->report;
                $workDetails[$itemkey]['doclink'] = 'http://' . $_SERVER['SERVER_NAME'] . '/public/report/technicianreview/' . $itemval->report;
                $workDetails[$itemkey]['reviewlink'] = url('admin/reviewWorkOrder/' . $itemval->workOrderItemId . '');
            }
            die(json_encode(array('result' => true, 'data' => $workDetails)));
        } else {
            die(json_encode(array('result' => true, 'message' => 'Sorry! Something went wrong')));
        }


    }


//    for view pdf
    public
    function qcConsolidateReview(Request $request, $workOrderId)
    {
//        echo '<pre>';print_r($workOrderId);exit;
        $title = 'Consolidate qc review';
        $work_order = DB::table('tbl_work_order')->select('*')->where('id', '=', $workOrderId)->get()->first();

        if (!$work_order) {
            echo '404 Workorder not found';
        }

        $report = $work_order->report;
        if (!$report) {
            echo 'Report not ready';
        }
        if ($work_order->customer_review) {
            $reviewedReport = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/report/consolidate/customerreview/' . $report;
            $path = url('/public/report/consolidate/customerreview/' . $report);
        } elseif ($work_order->admin_review && !$work_order->customer_review) {
            $reviewedReport = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/report/consolidate/adminreview/' . $report;
            $path = url('/public/report/consolidate/adminreview/' . $report);
        } else {
            $reviewedReport = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/report/consolidate/technicianreview/' . $report;
            $path = url('/public/report/consolidate/technicianreview/' . $report);
        }
        $data['path'] = $path;
        $data['workorder'] = $work_order;
        $data['workOrderId'] = $work_order->id;
        return view('calibration.consolidateview')->with('data', $data)->with('title', $title);


    }

    public
    function adminReview(Request $request, $workOrderItemId)
    {

        $user = request()->user();
        if ($user) {
            $userId = $user->id;
            $adminsignature = $user->signature;
        } else {
            return redirect('admin/login');
        }


        $get_workorder_item = DB::table('tbl_work_order_items as oi')
            ->select('oi.report', 'oi.technician_review_date', 'oi.admin_review',
                'oi.admin_review_date', 't.signature as techniciansignature', 'oi.comments')
            ->join('tbl_users as t', 't.id', '=', 'oi.technician_review')
            ->where('oi.id', '=', $workOrderItemId)->get()->first();

        if ($get_workorder_item) {

            $dueEquipments = DB::table('tbl_work_order_items as oi')->select('DE.id','ri.frequency_id')->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
                ->join('tbl_due_equipments as DE','DE.id','=','ri.due_equipments_id')->where('oi.id',$workOrderItemId)->first();
            if($dueEquipments)
            {
                $save_due_equip['id'] = $dueEquipments->id;
                $save_due_equip['calibrate_process'] = 0;
                $this->serviceModel->saveDueEqu($save_due_equip);
            }


            if ($get_workorder_item->report) {
//                $reviewedReport = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/report/technicianreview/' . $get_workorder_item->report;
                $reviewedReport = base_path() . '/public/report/technicianreview/' . $get_workorder_item->report;
                if (file_exists($reviewedReport)) {
                    $data['equipment_details'] = DB::table('tbl_work_order_items as oi')
                        ->join('tbl_service_request_item as ri', 'ri.id', '=', 'oi.request_item_id')
                        ->join('tbl_service_request as sr', 'sr.id', '=', 'ri.service_request_id')
                        ->join('tbl_work_order as wo', 'wo.request_id', '=', 'sr.id')
                        ->join('tbl_technician as t', 't.id', '=', 'wo.calibration_to')
                        ->join('tbl_due_equipments as de', 'de.id', '=', 'ri.due_equipments_id')
                        ->join('tbl_equipment as e', 'e.id', '=', 'de.equipment_id')
                        ->join('tbl_equipment_model as em', 'em.id', '=', 'e.equipment_model_id')
                        ->join('tbl_customer as c', 'c.id', '=', 'e.customer_id')
                        ->select('e.*', 'c.*', 'em.model_name','em.model_description', 'de.last_cal_date', 'de.next_due_date',
                            't.first_name as tfname', 't.last_name as tlname', 'ri.id as servicerequestItemId', 'oi.id', 'oi.report')
                        ->where('oi.id', $workOrderItemId)->get()->first();


                    $data['as_found_workorder'] = $this->serviceModel->workorder_status($data['equipment_details']->id, 1);
                    $as_found_workorder_log = $this->serviceModel->workorder_asfound_log($data['equipment_details']->id);
                    $tempfound = array();
                    if ($as_found_workorder_log) {
                        foreach ($as_found_workorder_log as $fkey => $frow) {

                            $tempfound[$fkey]['channel'] = $frow->reading_channel;
                            $tempfound[$fkey]['volume'] = $frow->reading_mean_volume;
                            $tempfound[$fkey]['mean'] = $frow->reading_mean;
                            $tempfound[$fkey]['sd'] = $frow->reading_sd;
                            $tempfound[$fkey]['actual_accuracy'] = $frow->reading_accuracy;
                            $tempfound[$fkey]['target_accuracy'] = $frow->target_accuracy;
                            $tempfound[$fkey]['actual_precision'] = $frow->reading_precision;
                            $tempfound[$fkey]['target_precision'] = $frow->target_precision;
                            $tempfound[$fkey]['status'] = $frow->reading_status == 1 ? 'Passed' : 'Failed';
                            $tempfound[$fkey]['readings'] = json_decode($frow->sample_readings); //echo '<pre>';print_r($tempfound);die;

                        }
                    }
                    $data['calibrated_workorder'] = $this->serviceModel->workorder_status($data['equipment_details']->id, 3);
                    $calibrated_workorder_log = $this->serviceModel->workorder_calibrated_log($data['equipment_details']->id);
                    $tempcalibrated = array();
                    if ($calibrated_workorder_log) {
                        foreach ($calibrated_workorder_log as $ckey => $crow) {

                            $tempcalibrated[$ckey]['channel'] = $crow->reading_channel;
                            $tempcalibrated[$ckey]['volume'] = $crow->reading_mean_volume;
                            $tempcalibrated[$ckey]['mean'] = $crow->reading_mean;
                            $tempcalibrated[$ckey]['sd'] = $crow->reading_sd;
                            $tempcalibrated[$ckey]['actual_accuracy'] = $crow->reading_accuracy;
                            $tempcalibrated[$ckey]['target_accuracy'] = $crow->target_accuracy;
                            $tempcalibrated[$ckey]['actual_precision'] = $crow->reading_precision;
                            $tempcalibrated[$ckey]['target_precision'] = $crow->target_precision;
                            $tempcalibrated[$ckey]['status'] = $crow->reading_status == 1 ? 'Passed' : 'Failed';
                            $tempcalibrated[$ckey]['readings'] = json_decode($crow->sample_readings);

                        }
                    }
                    $data['found_log'] = $tempfound;
                    $data['calibrated_log'] = $tempcalibrated;
                    $data['comments'] = $get_workorder_item->comments;
                    $data['tech_signature'] = $get_workorder_item->techniciansignature;
                    $data['tech_date'] = $get_workorder_item->technician_review_date;
                    $data['admin_signature'] = $adminsignature;
                    $data['admin_date'] = date('Y-m-d');


                    $technicianpath = base_path() . '/public/report/technicianreview';
                    $adminpath = base_path() . '/public/report/adminreview';
                    $reportFile = 'report-' . uniqid();

                    return view('report.adminreport')->with('data', $data);

                    view()->share($data);

                    $pdf = PDF::loadView('report.adminreport')
                        ->save($technicianpath . '/' . $reportFile . '.pdf', 'F');
                    $pdf = PDF::loadView('report.adminreport')
                        ->save($adminpath . '/' . $reportFile . '.pdf', 'F');
                    $pathToFile = base_path() . '/public/report/adminreview/' . $reportFile . '.pdf';

//                    echo '<pre>';print_r($pathToFile);die;

                    $headers = array(
                        'Content-Type: application/pdf',
                    );

                    $save_report['id'] = $data['equipment_details']->id;
                    $save_report['report'] = $reportFile . '.pdf';
                    $save_report['admin_review'] = $userId;
                    $save_report['admin_review_date'] = date('Y-m-d');
                    $this->workorder->saveWorkOrderItem($save_report);
                    $saveReqItem['id'] = $data['equipment_details']->servicerequestItemId;
                    $saveReqItem['is_calibrated'] = '1';

                    DB::table('tbl_service_request_item')->where('id', '=', $data['equipment_details']->servicerequestItemId)->update($saveReqItem);


                    $pathToFile = base_path() . '/public/report/adminreview/' . $reportFile . '.pdf';
                    $query = DB::table('tbl_email_template');
                    $query->where('tplid', '=', 3);
                    $result = $query->first();
                    $email = $data['equipment_details']->customer_email;
                    $result->tplmessage = str_replace('{name}', $data['equipment_details']->customer_name, $result->tplmessage);
                    $result->tplmessage = str_replace('{asset_no}', $data['equipment_details']->asset_no, $result->tplmessage);
                    $result->tplmessage = str_replace('{serial_no}', $data['equipment_details']->serial_no, $result->tplmessage);
                    $result->tplmessage = str_replace('{model_name}', $data['equipment_details']->model_name, $result->tplmessage);

                    $param['message'] = $result->tplmessage;
                    $param['name'] = $data['equipment_details']->customer_name;
                    $param['title'] = $result->tplsubject;

                    $data = array('data' => $param);

                    Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param, $email, $pathToFile) {
                        $message->to($email)->subject
                        ($param['title']);
                        $message->attach($pathToFile, ['as' => 'Serviceinvoice.pdf', 'mime' => 'pdf']);
                    });


//                    Response::download($pathToFile, 'report.pdf', $headers);
                    $pdf->stream();
                    return $pdf->download();


                }

            }
        } else {
            return redirect('admin/qualitycheck')->with('message', 'Details not found');
        }
    }

    public
    function qcIndividualReview(Request $request, $workOrderItemId)
    {

        $input = Input::all();
//        echo '<pre>';print_r($input);exit;
//        print_r($workOrderItemId);die;
        $user = request()->user();
        if ($user) {
            $userId = $user->id;
            $adminsignature = $user->signature;
        } else {
            return redirect('admin/login');
        }


        $get_workorder_item = DB::table('tbl_work_order_items as oi')
            ->select('oi.report', 'oi.technician_review_date', 'oi.admin_review',
                'oi.admin_review_date', 't.signature as techniciansignature', 'oi.comments')
            ->join('tbl_users as t', 't.id', '=', 'oi.technician_review')
            ->where('oi.id', '=', $workOrderItemId)->get()->first();
        if($get_workorder_item)
        {
            $dueEquipments = DB::table('tbl_work_order_items as oi')->select('DE.id','ri.frequency_id')->join('tbl_service_request_item as ri','ri.id','=','oi.request_item_id')
                ->join('tbl_due_equipments as DE','DE.id','=','ri.due_equipments_id')->where('oi.id',$workOrderItemId)->first();
            if($dueEquipments)
            {
                $save_due_equip['id'] = $dueEquipments->id;
                $save_due_equip['calibrate_process'] = 0;
                $this->serviceModel->saveDueEqu($save_due_equip);
            }

        }

        if (!$get_workorder_item) {
            return redirect('admin/qualitycheck')->with('message', 'Details not found');
        }

        if (!$get_workorder_item->report) {
            return redirect('admin/qualitycheck')->with('message', 'Report is not ready');
        }


        $reviewedReport = base_path() . '/public/report/technicianreview/' . $get_workorder_item->report;

        if (file_exists($reviewedReport)) {
            $data['equipment_details'] = DB::table('tbl_work_order_items as oi')
                ->join('tbl_service_request_item as ri', 'ri.id', '=', 'oi.request_item_id')
                ->join('tbl_service_request as sr', 'sr.id', '=', 'ri.service_request_id')
                ->join('tbl_work_order as wo', 'wo.request_id', '=', 'sr.id')
                ->join('tbl_service_plan as sp','sp.id','=','wo.plan_id')
                ->join('tbl_technician as t', 't.id', '=', 'wo.calibration_to')
                ->join('tbl_due_equipments as de', 'de.id', '=', 'ri.due_equipments_id')
                ->join('tbl_equipment as e', 'e.id', '=', 'de.equipment_id')
                ->join('tbl_equipment_model as em', 'em.id', '=', 'e.equipment_model_id')
                ->join('tbl_customer as c', 'c.id', '=', 'e.customer_id')
                ->join('tbl_customer_setups', 'tbl_customer_setups.customer_id', '=', 'e.customer_id')
                ->join('tbl_service_plan', 'tbl_service_plan.id', '=', 'wo.plan_id')
                ->select('tbl_service_plan.calibration_outside', 'tbl_customer_setups.cal_spec', 'e.*', 'c.*', 'em.model_name','em.model_description', 'de.last_cal_date', 'de.next_due_date',
                    't.first_name as tfname', 't.last_name as tlname', 'ri.id as servicerequestItemId', 'oi.id', 'oi.report', 'em.id as emodel_id', 'em.volume', 'em.volume_value', 'em.brand_operation', 'em.channel','sp.calibration_outside','sr.request_no')
                ->where('oi.id', $workOrderItemId)->get()->first();

            $data['as_found_workorder'] = $this->serviceModel->workorder_status($workOrderItemId, 1);
            $data['file_path'] = $input['file_path'];

//           echo '<pre>';print_r($data);exit;
            $as_found_workorder_log = $this->serviceModel->workorder_asfound_log($workOrderItemId);
            $model_id = (isset($data['equipment_details']->emodel_id) && $data['equipment_details']->emodel_id) ? $data['equipment_details']->emodel_id : '';
            $volume_id = (isset($data['equipment_details']->volume) && $data['equipment_details']->volume) ? $data['equipment_details']->volume : '0';
            $test_points = array();
            if ($data['equipment_details']->calibration_outside == 1) {
                $this->calibrationoutsidereview($data);
            } else {

                if ($model_id) {
                    //$test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id',$model_id)->get();

                    switch ($data['equipment_details']->cal_spec) {
                        case 1:
                            if ($volume_id == 1) {
                                $spec = DB::table('tbl_iso_limit_tolerance');
                                $spec->join('tbl_iso_specifications', 'tbl_iso_specifications.id', '=', 'tbl_iso_limit_tolerance.specification_id');
                                $spec->where([
                                    ['channel_id', '=', $data['equipment_details']->channel],
                                    ['operation_id', '=', $data['equipment_details']->brand_operation],
                                    ['volume_id', '=', $data['equipment_details']->volume],
                                    ['tbl_iso_specifications.volume_value', '=', $data['equipment_details']->volume_value],
                                ]);
                                $spec->select('tbl_iso_limit_tolerance.*');
                                $test_points = $spec->get();
                            } else {
                                $spec = DB::table('tbl_iso_limit_tolerance');
                                $spec->join('tbl_iso_specifications', 'tbl_iso_specifications.id', '=', 'tbl_iso_limit_tolerance.specification_id');
                                $spec->where([
                                    ['channel_id', '=', $data['equipment_details']->channel],
                                    ['operation_id', '=', $data['equipment_details']->brand_operation],
                                    ['volume_id', '=', $data['equipment_details']->volume],
                                    ['tbl_iso_specifications.volume_value', '=', $data['equipment_details']->volume_value],
                                ]);
                                $spec->select('tbl_iso_limit_tolerance.*');
                                $test_points = $spec->get();
                            }

                            break;
                        case 2:
                            $test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id', $model_id)->get();
                            break;
                        default:
                            $test_points = DB::table('tbl_limit_tolerance as lt')->where('model_id', $model_id)->get();
                            break;
                    }
                }
                // echo'<pre>';print_r($test_points);'</pre>';die;
                $tempfound = array();
                $foundPassFail = array();
                $foundStatus = '';
                if ($as_found_workorder_log) {
                    foreach ($as_found_workorder_log as $fkey => $frow) {

                        if ($frow->test_point_id == 1) {
                            $test_target = $test_points[0]->target_value;
                        } elseif ($frow->test_point_id == 2) {
                            $test_target = $test_points[1]->target_value;
                        } elseif ($frow->test_point_id == 3) {
                            $test_target = $test_points[2]->target_value;
                        } else {
                            $test_target = $test_points[0]->target_value;
                        }
                        $tempfound[$fkey]['id'] = $frow->id;
                        $tempfound[$fkey]['test_target'] = $test_target;
                        $tempfound[$fkey]['channel'] = $frow->reading_channel;
                        $tempfound[$fkey]['volume'] = $frow->reading_mean_volume;
                        $tempfound[$fkey]['mean'] = $frow->reading_mean;
                        $tempfound[$fkey]['sd'] = $frow->reading_sd;
                        $tempfound[$fkey]['actual_accuracy'] = $frow->reading_accuracy;
                        $tempfound[$fkey]['target_accuracy'] = $frow->target_accuracy;
                        $tempfound[$fkey]['actual_precision'] = $frow->reading_precision;
                        $tempfound[$fkey]['target_precision'] = $frow->target_precision;
                        $tempfound[$fkey]['status'] = $frow->reading_status == 1 ? 'Passed' : 'Failed';
                        $tempfound[$fkey]['readings'] = json_decode($frow->sample_readings);
                        if($frow->reading_status == 1)
                        {
                            $foundPassFail[$fkey] = "Passed";
                        }
                        else{
                            $foundPassFail[$fkey] = "Failed";
                        }

                    }
                }
                if(in_array("Failed",$foundPassFail))
                {
                    $foundStatus = "Failed";
                }
                else
                {
                    $foundStatus = "Passed";
                }
                $data['foundPassFail'] = $foundStatus;
                $data['calibrated_workorder'] = $this->serviceModel->workorder_status($workOrderItemId, 3);

                //last and next due dates for device

                $data['balance_last_date'] = '';
                $data['balance_due_date'] = '';
                $data['barometer_last_date'] = '';
                $data['barometer_due_date'] = '';
                $data['thermometer_last_date'] = '';
                $data['thermometer_due_date'] = '';
                $data['thermocouple_last_date'] = '';
                $data['thermocouple_due_date'] = '';

                if($data['calibrated_workorder'])
                {
                    $balance_device_id = (isset($data['calibrated_workorder']->balance_device_id)&&$data['calibrated_workorder']->balance_device_id)?$data['calibrated_workorder']->balance_device_id:1;
                    $barometer_device_id = (isset($data['calibrated_workorder']->barometer_device_id)&&$data['calibrated_workorder']->barometer_device_id)?$data['calibrated_workorder']->barometer_device_id:1;
                    $thermometer_device_id = (isset($data['calibrated_workorder']->thermometer_device_id)&&$data['calibrated_workorder']->thermometer_device_id)?$data['calibrated_workorder']->thermometer_device_id:1;
                    $thermocouple_device_id = (isset($data['calibrated_workorder']->thermocouple_device_id)&&$data['calibrated_workorder']->thermocouple_device_id)?$data['calibrated_workorder']->thermocouple_device_id:1;

                    $balance = DB::table('tbl_device')->where('id',$balance_device_id)->first();
                    $barometer = DB::table('tbl_device')->where('id',$barometer_device_id)->first();
                    $thermometer = DB::table('tbl_device')->where('id',$thermometer_device_id)->first();
                    $thermocouple = DB::table('tbl_device')->where('id',$thermocouple_device_id)->first();

                    $data['balance_last_date'] = (isset($balance->last_cal_date)&&$balance->last_cal_date)?date('d-M-Y',strtotime(str_replace('/','-',$balance->last_cal_date))):'';
                    $data['balance_due_date'] = (isset($balance->next_due_date)&&$balance->next_due_date)?date('d-M-Y',strtotime(str_replace('/','-',$balance->next_due_date))):'';

                    $data['barometer_last_date'] = (isset($barometer->last_cal_date)&&$barometer->last_cal_date)?date('d-M-Y',strtotime(str_replace('/','-',$barometer->last_cal_date))):'';
                    $data['barometer_due_date'] = (isset($barometer->next_due_date)&&$barometer->next_due_date)?date('d-M-Y',strtotime(str_replace('/','-',$barometer->next_due_date))):'';

                    $data['thermometer_last_date'] = (isset($thermometer->last_cal_date)&&$thermometer->last_cal_date)?date('d-M-Y',strtotime(str_replace('/','-',$thermometer->last_cal_date))):'';
                    $data['thermometer_due_date'] = (isset($thermometer->next_due_date)&&$thermometer->next_due_date)?date('d-M-Y',strtotime(str_replace('/','-',$thermometer->next_due_date))):'';

                    $data['thermocouple_last_date'] = (isset($thermocouple->last_cal_date)&&$thermocouple->last_cal_date)?date('d-M-Y',strtotime(str_replace('/','-',$thermocouple->last_cal_date))):'';
                    $data['thermocouple_due_date'] = (isset($thermocouple->next_due_date)&&$thermocouple->next_due_date)?date('d-M-Y',strtotime(str_replace('/','-',$thermocouple->next_due_date))):'';
                }

                $calibrated_workorder_log = $this->serviceModel->workorder_calibrated_log($workOrderItemId);
                $tempcalibrated = array();
                $calibratedPassFail = array();
                $calibratedStatus = '';
                if ($calibrated_workorder_log) {
                    foreach ($calibrated_workorder_log as $ckey => $crow) {

                        if ($crow->test_point_id == 1) {
                            $test_target = $test_points[0]->target_value;
                        } elseif ($crow->test_point_id == 2) {
                            $test_target = $test_points[1]->target_value;
                        } elseif ($crow->test_point_id == 3) {
                            $test_target = $test_points[2]->target_value;
                        } else {
                            $test_target = $test_points[0]->target_value;
                        }
                        $tempcalibrated[$ckey]['id'] = $crow->id;
                        $tempcalibrated[$ckey]['test_target'] = $test_target;
                        $tempcalibrated[$ckey]['channel'] = $crow->reading_channel;
                        $tempcalibrated[$ckey]['volume'] = $crow->reading_mean_volume;
                        $tempcalibrated[$ckey]['mean'] = $crow->reading_mean;
                        $tempcalibrated[$ckey]['sd'] = $crow->reading_sd;
                        $tempcalibrated[$ckey]['actual_accuracy'] = $crow->reading_accuracy;
                        $tempcalibrated[$ckey]['target_accuracy'] = $crow->target_accuracy;
//                        $tempcalibrated[$ckey]['target_accuracy'] = 10;
                        $tempcalibrated[$ckey]['actual_precision'] = $crow->reading_precision;
                        $tempcalibrated[$ckey]['target_precision'] = $crow->target_precision;
//                        $tempcalibrated[$ckey]['target_precision'] = 10;
                        $tempcalibrated[$ckey]['status'] = $crow->reading_status == 1 ? 'Passed' : 'Failed';
                        $tempcalibrated[$ckey]['readings'] = json_decode($crow->sample_readings);
                        if($crow->reading_status == 1)
                        {
                            $calibratedPassFail[$ckey] = "Passed";
                        }
                        else{
                            $calibratedPassFail[$ckey] = "Failed";
                        }

                    }
                }
                if(in_array("Failed",$calibratedPassFail))
                {
                    $calibratedStatus = "Failed";
                }
                else
                {
                    $calibratedStatus = "Passed";
                }
                $data['calibratedPassFail'] = $calibratedStatus;
                $data['found_log'] = $tempfound;
                $data['calibrated_log'] = $tempcalibrated;
                $data['comments'] = $get_workorder_item->comments;
                $data['tech_signature'] = $get_workorder_item->techniciansignature;
                $data['tech_date'] = $get_workorder_item->technician_review_date;
                $data['admin_signature'] = $adminsignature;
                $data['admin_date'] = date('Y-m-d');

                $data['maintenance_workorder'] = $this->serviceModel->workorder_status($workOrderItemId, 2);

                $maintenance_data = DB::table('tbl_workorder_maintenance_log')->where('workorder_status_id', '=', $data['maintenance_workorder']->id)->first();
                //maintenance data
                $maintenance = (isset($maintenance_data->workorder_checklists) && $maintenance_data->workorder_checklists) ? $maintenance_data->workorder_checklists : '';
                $performed = array();
                if ($maintenance) {
                    $checklist_array = explode(',', $maintenance);
                    if ($checklist_array) {
                        foreach ($checklist_array as $key => $row) {
                            $checklist_id = str_replace('~', '', $row);
                            $checklist = DB::table('tbl_checklist_item')->where('id', '=', $checklist_id)->first();
                            $performed[$key] = isset($checklist->title) ? $checklist->title : '';

                        }
                    }
                }

                $data['performed'] = implode(',', $performed);

                //parts data
                $parts = (isset($maintenance_data->workorder_spares) && $maintenance_data->workorder_spares) ? $maintenance_data->workorder_spares : '';
                $decoded_parts = json_decode($parts);
                $parts_replaced = array();

                if ($decoded_parts) {
                    foreach ($decoded_parts as $key => $row) {
                        $part_id = $row->id;
                        $part = DB::table('tbl_equipment_model_spares')->where('id', '=', $part_id)->first();
                        $parts_replaced[$key] = isset($part->part_name) ? $part->part_name : '';

                    }
                } //print_r($parts_replaced);die;
                //$data['parts_replaced'] = implode(',', $parts_replaced);
                $data['parts_replaced'] = $parts_replaced;

                //echo'<pre>';print_r($data);'</pre>';die;

                $technicianpath = base_path() . '/public/report/technicianreview';
                $adminpath = base_path() . '/public/report/adminreview';
                $reportFile = 'report-' . uniqid();
                view()->share($data);
                //return view('report.adminreport')->with('data', $data); die;




                $pdf = app('dompdf.wrapper');
               // define('DOMPDF_UNICODE_ENABLED',true);
                $pdf->getDomPDF()->set_option("enable_php", true);
                //define("DOMPDF_UNICODE_ENABLED", true);
                $pdf->loadView('report.adminreport')->save($adminpath . '/' . $reportFile . '.pdf', 'F');

                $pdf1 = app('dompdf.wrapper');
                $pdf1->getDomPDF()->set_option("enable_php", true);
                $pdf1->loadView('report.adminreport')->save($technicianpath . '/' . $reportFile . '.pdf', 'F');

//                $pdfs = PDF::loadView('report.adminreport')
//                    ->save($adminpath . '/' . $reportFile . '.pdf', 'F');


//                $pdf = PDF::loadView('report.adminreport')
//                    ->save($adminpath . '/' . $reportFile . '.pdf', 'F');
                $pathToFile = base_path() . '/public/report/adminreview/' . $reportFile . '.pdf';

                //echo'<pre>';print_r(PDF::loadView('report.adminreport'));'</pre>';die;


                $headers = array(
                    'Content-Type: application/pdf',
                );

                $save_report['id'] = $data['equipment_details']->id;
                $save_report['report'] = $reportFile . '.pdf';
                $save_report['admin_review'] = $userId;
                $save_report['admin_review_date'] = date('Y-m-d');
                $this->workorder->saveWorkOrderItem($save_report);
                $saveReqItem['id'] = $data['equipment_details']->servicerequestItemId;
                $saveReqItem['is_calibrated'] = '1';

                DB::table('tbl_service_request_item')->where('id', '=', $data['equipment_details']->servicerequestItemId)
                    ->update($saveReqItem);

                die(json_encode(array('result' => true, 'message' => 'The report is successfully verified.')));

//                return redirect('admin/qualitycheck')->with('message', 'The report is successfully verified!.');

            }
        } else {
            die(json_encode(array('result' => false, 'message' => 'Report not found')));

//            return redirect('admin/qualitycheck')->with('message', 'Report not found');
        }


    }

//    for review
    function qcReview($id)
    {
        $user = request()->user();
        if ($user) {
            $userId = $user->id;
            $adminsignature = $user->signature;
        } else {
            return redirect('admin/login');
        }
        $this->uid = $user->id;


        $workOrderId = $id;
        $work_order = $this->workorder->getParticularWorkOrder($workOrderId);
//        if($work_order->admin_review == ''){
//
//            print_R('hi');die;
//        }else{
//            print_R('bye');die;
//        }


        if (!$work_order->technician_review) {
            return redirect('admin/qualitycheck')->with('message', 'Under technician review');
        }


        if (empty($work_order)) {
            return redirect('admin/qualitycheck')->with('message', 'Work order not found.');
        }
        $technicianReview = DB::table('tbl_technician as t')
            ->join('tbl_users as u', 'u.id', '=', 't.user_id')
            ->where('u.id', '=', $work_order->technician_review)
            ->select('t.id')
            ->get()->first();
        if (empty($technicianReview)) {
            return redirect('admin/qualitycheck')->with('message', 'Technician not found');
        }

        $this->tid = $technicianReview->id;
        $work_order_items = $this->workorder->totalInstruments($workOrderId);
        $work_order_items_calibrated = $this->workorder->totalInstrumentsCalibrated($workOrderId);
        if ($work_order->force_complete != 1) {
            if ($work_order_items != $work_order_items_calibrated) {
                return redirect('admin/qualitycheck')->with('message', 'Some instruments in this workorder are not calibrated. please calibrate it and try');
            }
        }


        $customer = DB::table('tbl_service_request')->join('tbl_customer', 'tbl_customer.id', '=', 'tbl_service_request.customer_id')->where('tbl_service_request.id', '=', $work_order->request_id)->get()->first();
//echo '<pre>';print_r($customer);exit;
        $technician = DB::table('tbl_technician as t')
            ->join('tbl_users as u', 'u.id', '=', 't.user_id')
            ->where('t.id', '=', $this->tid)->get()->first();

        $digital_barometer = $this->workorderitemmove->getTechnicianDeviceBarometer(2, $this->tid, $workOrderId);
        $digital_thermometer = $this->workorderitemmove->getTechnicianDeviceThermometer(3, $this->tid, $workOrderId);
        $digital_thermocouple = $this->workorderitemmove->getTechnicianDeviceThermocouple(4, $this->tid, $workOrderId);
        $digital_balance = $this->workorderitemmove->getTechnicianDeviceBalance(1, $this->tid, $workOrderId);
        $workorder_calibration = $this->workorderitemmove->getWorkorderCalibration($workOrderId);
//        echo '<pre>';print_r($workorder_calibration);die;
        $workorder_calibrations = $this->workorderitemmove->getWorkorderCalibrations($workOrderId);
        $calibrated_technician = DB::table('tbl_technician')->where('id', $work_order->calibration_to)->first();
        $calibration_data = array();
        $multicalibration_data = array();
        $data = array();
        $reviewedReport = base_path() . '/public/report/consolidate/technicianreview/' . $work_order->report;
        if (file_exists($reviewedReport))
            if ($workorder_calibration) {
                $check = '';

                foreach ($workorder_calibration as $key => $row) {

                    $spares_used_array = array();
//                    echo '<pre>';print_r($row->reading_accuracy);die;
                    $calibration_data[$key]['asset_no'] = $row->asset_no;
                    $calibration_data[$key]['serial_no'] = $row->serial_no;
                    $calibration_data[$key]['location'] = $row->location;
                    $calibration_data[$key]['model'] = $row->model_description;
                    $calibration_data[$key]['volume'] = $row->reading_mean_volume;
                    $calibration_data[$key]['mean'] = $row->reading_mean;
                    $calibration_data[$key]['sd'] = $row->reading_sd;
                    $calibration_data[$key]['unc'] = $row->reading_unc;
                    $calibration_data[$key]['actual_accuracy'] = $row->reading_accuracy;
                    $calibration_data[$key]['specification_accuracy'] = $row->target_accuracy;
                    $calibration_data[$key]['actual_precision'] = $row->reading_precision;
                    $calibration_data[$key]['specification_precision'] = $row->target_precision;
                    $calibration_data[$key]['status'] = $row->reading_status == 1 ? 'Passed' : 'Failed';
                    $calibration_data[$key]['last_cal_date'] =  date('d-M-Y',strtotime(str_replace('/','-',$row->last_cal_date)));
                    $calibration_data[$key]['next_due_date'] =  date('d-M-Y',strtotime(str_replace('/','-',$row->next_due_date)));
                    $calibration_data[$key]['technician_name'] = (isset($calibrated_technician->first_name)&&$calibrated_technician->first_name)?$calibrated_technician->first_name.' '.$calibrated_technician->last_name:'';
                    $calibration_data[$key]['reported_date'] = $row->admin_review_date?date('d-M-Y',strtotime(str_replace('/','-',$row->admin_review_date))):'Not applicable';
//                    echo '<pre>';print_r($calibration_data);die;
                    $get_workorder_status = DB::table('tbl_workorder_status_move')->where('workorder_item_id', $row->work_order_item_id)
                        ->where('workorder_status', 2)->first();
                    if ($get_workorder_status) {
                        $spares = DB::table('tbl_workorder_maintenance_log')->where('workorder_status_id', $get_workorder_status->id)->first();
                        if ($spares) {
                            if ($spares->workorder_spares) {
                                $sparesMaterial = json_decode($spares->workorder_spares);
                                if ($sparesMaterial) {

                                    foreach ($sparesMaterial as $spareKey => $spareRow) {
                                        $spares_used_array[$spareKey] = $spareRow->number;
                                    }

                                }
                            }

                            if($spares->workorder_checklists)
                            {
                                $arraycheck = explode(',',str_replace('~','',$spares->workorder_checklists));
                                //$like = "id" . " LIKE '" . str_replace('~','',$spares->workorder_checklists) . "' ";
                                $checklist = DB::table('tbl_checklist_item')->wherein('id',$arraycheck)->get();

                                if(count($checklist))
                                {
                                    foreach ($checklist as $ckey=>$crow)
                                    {
                                        $checks[$ckey] = $crow->title;
                                    }
                                    $check = implode(',', $checks);
                                }
                            }

                        }
                    }
                    $get_workorder_calibration_status = DB::table('tbl_workorder_status_move')->where('workorder_item_id', $row->work_order_item_id)
                        ->where('workorder_status', 3)->first();
                    //print_r($get_workorder_calibration_status->id.'-');
                    if($get_workorder_calibration_status)
                    {
                        $calibration = DB::table('tbl_workorder_ascalibrated_log')->where('workorder_status_id', $get_workorder_calibration_status->id)->get();
                        $calibrated_log = array();
                        if(count($calibration))
                        {
                            foreach ($calibration as $calkey=>$calrow)
                            {
                                $calibrated_log[$calkey]['channel'] = $calrow->reading_channel;

                                $calibrated_log[$calkey]['volume'] = $calrow->reading_mean_volume;
                                $calibrated_log[$calkey]['volume'] = $calrow->reading_mean_volume;
                                $calibrated_log[$calkey]['mean'] = $calrow->reading_mean;
                                $calibrated_log[$calkey]['sd'] = $calrow->reading_sd;
                                $calibrated_log[$calkey]['unc'] = $calrow->reading_unc;
                                $calibrated_log[$calkey]['actual_accuracy'] = $calrow->reading_accuracy;
                                $calibrated_log[$calkey]['specification_accuracy'] = $calrow->target_accuracy;
                                $calibrated_log[$calkey]['actual_precision'] = $calrow->reading_precision;
                                $calibrated_log[$calkey]['specification_precision'] = $calrow->target_precision;
                                $calibrated_log[$calkey]['target_value'] = $calrow->target_data;
                                $calibrated_log[$calkey]['status'] = $calrow->reading_status == 1 ? 'Passed' : 'Failed';
                            }



                        }
                    }
                    $spares_used = implode(',', $spares_used_array);
                    if($spares_used)
                    {
                        $calibration_data[$key]['spares'] = $check.','.$spares_used;
                    }
                    else
                    {
                        $calibration_data[$key]['spares'] = $check;
                    }

                    $calibration_data[$key]['calibrated_log'] = $calibrated_log;


                } //echo '<pre>';print_r($calibration_data);die;
            }
//        if($workorder_calibrations){
//            foreach ($workorder_calibrations as $key => $row) {
//                $multicalibration_data[$key]['channel'] = $row->reading_channel;
//                $multicalibration_data[$key]['volume'] = $row->reading_mean_volume;
//                $multicalibration_data[$key]['mean'] = $row->reading_mean;
//                $multicalibration_data[$key]['sd'] = $row->reading_sd;
//                $multicalibration_data[$key]['unc'] = $row->reading_unc;
//                $multicalibration_data[$key]['actual_accuracy'] = $row->reading_accuracy;
//                $multicalibration_data[$key]['specification_accuracy'] = "";
//                $multicalibration_data[$key]['actual_precision'] = $row->reading_precision;
//                $multicalibration_data[$key]['specification_precision'] = "";
//                $multicalibration_data[$key]['status'] = $row->reading_status == 1 ? 'Passed' : 'Failed';
//
//            }
//        }
        $data['last_cal_date'] = $calibration_data[0]['last_cal_date'];
        $data['next_due_date'] = $calibration_data[0]['next_due_date'];
        $data['asset_no'] = $calibration_data[0]['asset_no'];
        $data['digital_barometer'] = $digital_barometer;
        $data['digital_thermometer'] = $digital_thermometer;
        $data['digital_thermocouple'] = $digital_thermocouple;
        $data['digital_balance'] = $digital_balance;
        $data['customer'] = $customer;
        $data['technician'] = $technician;
        $data['workorder'] = $work_order;
        $data['calibrated_datas'] = $calibration_data;
        $data['multicalibrated_datas'] = $multicalibration_data;
        $data['digital_barometer_device'] = (isset($digital_barometer->serial_no)) ? $digital_barometer->serial_no : '';
        $data['digital_thermometer_device'] = (isset($digital_thermometer->serial_no)) ? $digital_thermometer->serial_no : '';
        $data['digital_thermocouple_device'] = (isset($digital_thermocouple->serial_no)) ? $digital_thermocouple->serial_no : '';
        $data['digital_balance_device'] = (isset($digital_balance->serial_no)) ? $digital_balance->serial_no : '';

        $data['barometer_last_cal_date'] = (isset($digital_barometer->last_cal_date)) ? $digital_barometer->last_cal_date : '';
        $data['thermometer_last_cal_date'] = (isset($digital_thermometer->last_cal_date)) ? $digital_thermometer->last_cal_date : '';
        $data['thermocouple_last_cal_date'] = (isset($digital_thermocouple->last_cal_date)) ? $digital_thermocouple->last_cal_date : '';
        $data['balance_last_cal_date'] = (isset($digital_balance->last_cal_date)) ? $digital_balance->last_cal_date : '';

        $data['barometer_next_due_date'] = (isset($digital_barometer->next_due_date)) ? $digital_barometer->next_due_date : '';
        $data['thermometer_next_due_date'] = (isset($digital_thermometer->next_due_date)) ? $digital_thermometer->next_due_date : '';
        $data['thermocouple_next_due_date'] = (isset($digital_thermocouple->next_due_date)) ? $digital_thermocouple->next_due_date : '';
        $data['balance_next_due_date'] = (isset($digital_balance->next_due_date)) ? $digital_balance->next_due_date : '';

        $data['uploaded_signature'] = $technician->signature;
        $data['admin_signature'] = $adminsignature;

        $data['tech_date'] = $work_order->technician_review_date;
        $data['admin_date'] = date('M-d-Y');
        $data['comments'] = $work_order->comments;

        //echo '<pre>';print_r($data);die;
        $technicianpath = base_path() . '/public/report/consolidate/technicianreview';
        $adminpath = base_path() . '/public/report/consolidate/adminreview';
        $reportFile = 'consolidate_report-' . uniqid();
//echo '<pre>';print_R($data);exit;
        view()->share($data);
        //return view('report.consolidaadminreport')->with('data', $data); die;
//        $pdf = PDF::loadView('report.consolidaadminreport')
//            ->save($adminpath . '/' . $reportFile . '.pdf', 'F');
//        PDF::loadView('report.consolidaadminreport')
//            ->save($technicianpath . '/' . $reportFile . '.pdf', 'F');


        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('report.consolidaadminreport')->save($adminpath . '/' . $reportFile . '.pdf', 'F');

        $pdf1 = app('dompdf.wrapper');
        $pdf1->getDomPDF()->set_option("enable_php", true);
        $pdf1->loadView('report.consolidaadminreport')->save($technicianpath . '/' . $reportFile . '.pdf', 'F');


        $pathToFile = base_path() . '/public/report/consolidate/adminreview/' . $reportFile . '.pdf';

        $headers = array(
            'Content-Type: application/pdf',
        );

//        if ($work_order->admin_review == '') {
//            $pathToFile = base_path() . '/public/report/consolidate/adminreview/' . $reportFile . '.pdf';
//            $query = DB::table('tbl_email_template');
//            $query->where('tplid', '=', 8);
//            $result = $query->first();
//            $email = $customer->customer_email;
//            $result->tplmessage = str_replace('{name}', $customer->customer_name, $result->tplmessage);
////            $result->tplmessage = str_replace('{asset_no}', $data['equipment_details']->asset_no, $result->tplmessage);
////            $result->tplmessage = str_replace('{serial_no}', $data['equipment_details']->serial_no, $result->tplmessage);
////            $result->tplmessage = str_replace('{model_name}', $data['equipment_details']->model_name, $result->tplmessage);
//
//            $param['message'] = $result->tplmessage;
//            $param['name'] = $customer->customer_name;
//            $param['title'] = $result->tplsubject;
//
//            $data = array('data' => $param);
////
////            Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param, $email, $pathToFile) {
////                $message->to($email)->subject
////                ($param['title']);
////                $message->attach($pathToFile, ['as' => 'Serviceinvoice.pdf', 'mime' => 'pdf']);
////            });
//        }


        $save_report['id'] = $workOrderId;
        $save_report['report'] = $reportFile . '.pdf';
        $save_report['admin_review'] = $userId;
        $save_report['admin_review_date'] = date('Y-m-d');
        $this->workorderitemmove->saveWorkOrder($save_report);

        $pdf->stream();
        $pdf->download();
        return redirect('admin/qualitycheck')->with('message', 'The report is successfully verified!.');


    }

    //    for send mail

    public
    function sendMail(Request $request, $workOrderId)
    {

        $work_order = $this->workorder->getParticularWorkOrder($workOrderId);



        if (!$work_order->technician_review) {
            return redirect('admin/qualitycheck')->with('message', 'Under technician review');
        }


        if (empty($work_order)) {
            return redirect('admin/qualitycheck')->with('message', 'Work order not found.');
        }

        $service_request = DB::table('tbl_service_request')->where('id', '=', $work_order->request_id)->select('*')->first();
        $service_request_number = (isset($service_request->request_no)&&$service_request->request_no)?$service_request->request_no:'';

        $technicianReview = DB::table('tbl_technician as t')
            ->join('tbl_users as u', 'u.id', '=', 't.user_id')
            ->where('u.id', '=', $work_order->technician_review)
            ->select('t.id')
            ->get()->first();
//        echo '<pre>';print_r($work_order);die;

        if (empty($technicianReview)) {
            return redirect('admin/qualitycheck')->with('message', 'Technician not found');
        }

        $this->tid = $technicianReview->id;
        $work_order_items = $this->workorder->totalInstruments($workOrderId);
        $work_order_items_calibrated = $this->workorder->totalInstrumentsCalibrated($workOrderId);
        if ($work_order_items != $work_order_items_calibrated) {
            return redirect('admin/qualitycheck')->with('message', 'Some instruments in this workorder are not calibrated. please calibrate it and try');

        }
        $customer = DB::table('tbl_service_request')->join('tbl_customer', 'tbl_customer.id', '=', 'tbl_service_request.customer_id')->where('tbl_service_request.id', '=', $work_order->request_id)->get()->first();


        $reportFile = $work_order->report;

        $headers = array(
            'Content-Type: application/pdf',
        );

        $pathToFile = base_path() . '/public/report/consolidate/adminreview/' . $reportFile;
        if (file_exists($pathToFile)) {
            $query = DB::table('tbl_email_template');
            $query->where('tplid', '=', 8);
            $result = $query->first();
            $email = $customer->customer_email;
            $result->tplsubject = str_replace('{req_num}', $service_request_number, $result->tplmessage);
            $result->tplmessage = str_replace('{name}', $customer->customer_name, $result->tplmessage);
            $result->tplmessage = str_replace('{company_name}', $customer->customer_name, $result->tplmessage);
            $param['message'] = $result->tplmessage;
            $param['name'] = $customer->customer_name;
            $param['title'] = $result->tplsubject;

            $data = array('data' => $param);

            Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param, $email, $pathToFile) {
                $message->to($email)->subject
                ($param['title']);
                $message->attach($pathToFile, ['as' => 'Serviceinvoice.pdf', 'mime' => 'pdf']);
            });


            return redirect('admin/qualitycheck')->with('message', 'Mail send successfully!');
        } else {
            return redirect('admin/qualitycheck')->with('error', 'Report not found!');
        }


    }


//    for item view pdf
    public
    function qcItemReview(Request $request, $workOrderItemId)
    {


//        print_r($workOrderItemId);die;
        $user = request()->user();
        if ($user) {
            $userId = $user->id;
            $adminsignature = $user->signature;
        } else {
            return redirect('admin/login');
        }

        $title = 'Qc review';
        $get_workorder_item = DB::table('tbl_work_order_items as oi')
            ->select('oi.report', 'oi.technician_review_date', 'oi.admin_review',
                'oi.admin_review_date', 't.signature as techniciansignature', 'oi.comments', 'oi.customer_review', 'oi.customer_review_date')
            ->join('tbl_users as t', 't.id', '=', 'oi.technician_review')
            ->where('oi.id', '=', $workOrderItemId)->get()->first();


        if (!$get_workorder_item) {
            return redirect('admin/qualitycheck')->with('message', 'Details not found');
        }


        if (!$get_workorder_item->report) {
            return redirect('admin/qualitycheck')->with('message', 'Report not ready');
        }

        if ($get_workorder_item->customer_review) {
            $reviewedReport = base_path() . '/public/report/customerreview/' . $get_workorder_item->report;
            $path = url('/public/report/customerreview/' . $get_workorder_item->report);
        } elseif ($get_workorder_item->admin_review && !$get_workorder_item->customer_review) {
            $reviewedReport = base_path() . '/public/report/adminreview/' . $get_workorder_item->report;
            $path = url('/public/report/adminreview/' . $get_workorder_item->report);
        } else {
            $reviewedReport = base_path() . '/public/report/technicianreview/' . $get_workorder_item->report;
            $path = url('/public/report/technicianreview/' . $get_workorder_item->report);
        }


        $reviewedReport = base_path() . '/public/report/technicianreview/' . $get_workorder_item->report;
        //echo '<pre>';print_r($reviewedReport);die;

        if (file_exists($reviewedReport)) {
            $data['path'] = $path;
            $data['workorderItem'] = $get_workorder_item;
            $data['workorderItemId'] = $workOrderItemId;
            return view('calibration.qcindividualreview')->with('data', $data)->with('title', $title);
        } else {
            return redirect('admin/qualitycheck')->with('message', 'Report not ready');
        }


    }

    public
    function workOrderItems(Request $request)
    {

        $input = Input::all();


        $data = array();
        $workorderId = $input['workOrderId'];

        $select = array('twoi.id as workOrderItemId',
            'twoi.work_order_id as workOrderId',
//            'twoi.admin_review as adminReview',
//            'twoi.request_item_id as requestItemId',
//            'twoi.report as report',
            'twoi.technician_review as reviewdTechnicianId',
//            'twoi.technician_review_date as technicianReviewDate',
//            'ri.due_equipments_id as dueEquipId',
            'e.asset_no',
            'e.name as instrumentName',
//            'e.pref_contact as preferredContact',
            'tem.model_name as instrumentModel',
            'e.serial_no',
//            'tt.first_name as reviewdTechnician'
        );

        $items = $this->workorder->CalibrationDetails('', '', array('select' => $select, 'workOrderId' => $workorderId), 'DESC', 'twoi.id');
        $workDetails = array();
        if (!$items->isEmpty()) {
            foreach ($items as $itemkey => $itemval) {
                if ($itemval->reviewdTechnicianId) {
                    $workDetails[$itemkey]['instrumentModel'] = $itemval->instrumentModel;
                    $workDetails[$itemkey]['workOrderItemId'] = $itemval->workOrderItemId;
                    $workDetails[$itemkey]['instrumentName'] = $itemval->instrumentName;
                    $workDetails[$itemkey]['asset_no'] = $itemval->asset_no;
                    $workDetails[$itemkey]['serial_no'] = $itemval->serial_no;
                }

            }
        }
        $data['details'] = $workDetails;
        $data['workOrderId'] = $workorderId;
        $view = view('calibration.workOrderItems', ['details' => $data])->render();

        echo json_encode(array('result' => true, 'data' => $view));

    }

//    public function chosenQualityCheck(Request $request)
//    {
//
//        $input = Input::all();
//        $workOrderItemIDs = $input['workOrderItemIDs'];
//
//
//        $user = request()->user();
//        if ($user) {
//            $userId = $user->id;
//            $adminsignature = $user->signature;
//        } else {
//            die(json_encode(array('result' => false, 'msg' => 'Please login')));
//        }
//
//        $i = 0;
//        if ($workOrderItemIDs) {
//            foreach ($workOrderItemIDs as $workOrderkey) {
//                $get_workorder_item = DB::table('tbl_work_order_items as oi')
//                    ->select('oi.id', 'oi.work_order_id', 'oi.report', 'oi.technician_review_date', 'oi.admin_review',
//                        'oi.admin_review_date', 't.signature as techniciansignature', 'oi.comments')
//                    ->join('tbl_users as t', 't.id', '=', 'oi.technician_review')
//                    ->where('oi.id', '=', $workOrderkey)->get()->first();
//
//                if (!$get_workorder_item) {
//                    continue;
//                }
//
//                if (!$get_workorder_item->report) {
//                    continue;
//                }
//
//
//                $reviewedReport = base_path() . '/public/report/technicianreview/' . $get_workorder_item->report;
//
//                if (file_exists($reviewedReport)) {
//                    $data['equipment_details'] = DB::table('tbl_work_order_items as oi')
//                        ->join('tbl_service_request_item as ri', 'ri.id', '=', 'oi.request_item_id')
//                        ->join('tbl_service_request as sr', 'sr.id', '=', 'ri.service_request_id')
//                        ->join('tbl_work_order as wo', 'wo.request_id', '=', 'sr.id')
//                        ->join('tbl_technician as t', 't.id', '=', 'wo.calibration_to')
//                        ->join('tbl_due_equipments as de', 'de.id', '=', 'ri.due_equipments_id')
//                        ->join('tbl_equipment as e', 'e.id', '=', 'de.equipment_id')
//                        ->join('tbl_equipment_model as em', 'em.id', '=', 'e.equipment_model_id')
//                        ->join('tbl_customer as c', 'c.id', '=', 'e.customer_id')
//                        ->select('e.*', 'c.*', 'em.model_name', 'de.last_cal_date', 'de.next_due_date',
//                            't.first_name as tfname', 't.last_name as tlname', 'ri.id as servicerequestItemId', 'oi.id', 'oi.report', 'em.id as emodel_id')
//                        ->where('oi.id', $workOrderkey)->get()->first();
//
//                    $data['as_found_workorder'] = $this->serviceModel->workorder_status($workOrderkey, 1);
//                    $as_found_workorder_log = $this->serviceModel->workorder_asfound_log($workOrderkey);
//                    $tempfound = array();
//                    if ($as_found_workorder_log) {
//                        foreach ($as_found_workorder_log as $fkey => $frow) {
//
//                            $tempfound[$fkey]['channel'] = $frow->reading_channel;
//                            $tempfound[$fkey]['volume'] = $frow->reading_mean_volume;
//                            $tempfound[$fkey]['mean'] = $frow->reading_mean;
//                            $tempfound[$fkey]['sd'] = $frow->reading_sd;
//                            $tempfound[$fkey]['actual_accuracy'] = $frow->reading_accuracy;
//                            $tempfound[$fkey]['target_accuracy'] = '';
//                            $tempfound[$fkey]['actual_precision'] = $frow->reading_precision;
//                            $tempfound[$fkey]['target_precision'] = '';
//                            $tempfound[$fkey]['status'] = $frow->reading_status == 1 ? 'Passed' : 'Failed';
//                            $tempfound[$fkey]['readings'] = json_decode($frow->sample_readings);
//
//                        }
//                    }
//                    $data['calibrated_workorder'] = $this->serviceModel->workorder_status($workOrderkey, 3);
//                    $calibrated_workorder_log = $this->serviceModel->workorder_calibrated_log($workOrderkey);
//                    $tempcalibrated = array();
//                    if ($calibrated_workorder_log) {
//                        foreach ($calibrated_workorder_log as $ckey => $crow) {
//
//                            $tempcalibrated[$ckey]['channel'] = $crow->reading_channel;
//                            $tempcalibrated[$ckey]['volume'] = $crow->reading_mean_volume;
//                            $tempcalibrated[$ckey]['mean'] = $crow->reading_mean;
//                            $tempcalibrated[$ckey]['sd'] = $crow->reading_sd;
//                            $tempcalibrated[$ckey]['actual_accuracy'] = $crow->reading_accuracy;
//                            $tempcalibrated[$ckey]['target_accuracy'] = '';
//                            $tempcalibrated[$ckey]['actual_precision'] = $crow->reading_precision;
//                            $tempcalibrated[$ckey]['target_precision'] = '';
//                            $tempcalibrated[$ckey]['status'] = $crow->reading_status == 1 ? 'Passed' : 'Failed';
//                            $tempcalibrated[$ckey]['readings'] = json_decode($crow->sample_readings);
//
//                        }
//                    }
//                    $data['found_log'] = $tempfound;
//                    $data['calibrated_log'] = $tempcalibrated;
//                    $data['comments'] = $get_workorder_item->comments;
//                    $data['tech_signature'] = $get_workorder_item->techniciansignature;
//                    $data['tech_date'] = $get_workorder_item->technician_review_date;
//                    $data['admin_signature'] = $adminsignature;
//                    $data['admin_date'] = date('Y-m-d');
//
//                    $data['maintenance_workorder'] = $this->serviceModel->workorder_status($workOrderkey, 2);
//                    $maintenance_data = array();
//                    $maintenance_workorder_id = (isset($data['maintenance_workorder']->id)&&$data['maintenance_workorder']->id)?$data['maintenance_workorder']->id:'';
//                    if($maintenance_workorder_id)
//                    {
//                        $maintenance_data = DB::table('tbl_workorder_maintenance_log')->where('workorder_status_id', '=', $data['maintenance_workorder']->id)->first();
//                    }
//                    //maintenance data
//                    $maintenance = (isset($maintenance_data->workorder_checklists) && $maintenance_data->workorder_checklists) ? $maintenance_data->workorder_checklists : '';
//                    $performed = array();
//                    if ($maintenance) {
//                        $checklist_array = explode(',', $maintenance);
//                        if ($checklist_array) {
//                            foreach ($checklist_array as $key => $row) {
//                                $checklist_id = str_replace('~', '', $row);
//                                $checklist = DB::table('tbl_checklist_item')->where('id', '=', $checklist_id)->first();
//                                $performed[$key] = isset($checklist->title) ? $checklist->title : '';
//
//                            }
//                        }
//                    }
//
//                    $data['performed'] = implode(',', $performed);
//
//                    //parts data
//                    $parts = (isset($maintenance_data->workorder_spares) && $maintenance_data->workorder_spares) ? $maintenance_data->workorder_spares : '';
//                    $decoded_parts = json_decode($parts);
//                    $parts_replaced = array();
//
//                    if ($decoded_parts) {
//                        foreach ($decoded_parts as $key => $row) {
//                            $part_id = $row->id;
//                            $part = DB::table('tbl_equipment_model_spares')->where('id', '=', $part_id)->first();
//                            $parts_replaced[$key] = isset($part->part_name) ? $part->part_name : '';
//
//                        }
//                    } //print_r($parts_replaced);die;
//                    $data['parts_replaced'] = implode(',', $parts_replaced);
//
//                    $technicianpath = base_path() . '/public/report/technicianreview';
//                    $adminpath = base_path() . '/public/report/adminreview';
//                    $consolidatepath = base_path() . '/public/overallreport';
//                    $reportFile = 'report-' . uniqid();
//
//                    view()->share($data);
//
//                    $pdf = PDF::loadView('report.adminreport')
//                        ->save($technicianpath . '/' . $reportFile . '.pdf', 'F');
//                    $pdf = PDF::loadView('report.adminreport')
//                        ->save($adminpath . '/' . $reportFile . '.pdf', 'F');
//
//                    $pdf = PDF::loadView('report.adminreport')
//                        ->save($consolidatepath . '/' . $reportFile . '.pdf', 'F');
//                    $pathToFile = base_path() . '/public/overallreport/' . $reportFile . '.pdf';
//
//
//                    $headers = array(
//                        'Content-Type: application/pdf',
//                    );
//
//                    $save_report['id'] = $data['equipment_details']->id;
//                    $save_report['report'] = $reportFile . '.pdf';
//                    $save_report['admin_review'] = $userId;
//                    $save_report['admin_review_date'] = date('Y-m-d');
//                    $this->workorder->saveWorkOrderItem($save_report);
//                    $saveReqItem['id'] = $data['equipment_details']->servicerequestItemId;
//                    $saveReqItem['is_calibrated'] = '1';
//
//                    DB::table('tbl_service_request_item')->where('id', '=', $data['equipment_details']->servicerequestItemId)
//                        ->update($saveReqItem);
//
//
//                    $saveConsolidate['id'] = false;
//                    $saveConsolidate['work_order_id'] = $get_workorder_item->work_order_id;
//                    $saveConsolidate['work_order_item_id'] = $get_workorder_item->id;
//                    $saveConsolidate['report'] = $reportFile . '.pdf';
//                    DB::table('tbl_conslidate_report')->insertGetId($saveConsolidate);
//
//                    $i++;
//
//                } else {
//                    continue;
//                }
//            }
//
//            if ($i > 0) {
//                die(json_encode(array('result' => true, 'msg' => 'Yes sucessfully ' . $i . ' items reviewed.')));
//            } else {
//                die(json_encode(array('result' => true, 'msg' => 'Sorry! Now we are not able to ready your report.Please wait for sometime.')));
//
//            }
//
//        }
//
//
//    }

    public
    function chosenQualityCheck(Request $request)
    {
        $input = Input::all();
        $workOrderItemIDs = $input['workOrderItemIDs'];
        $user = request()->user();
        if ($user) {
            $userId = $user->id;
            $adminsignature = $user->signature;
        } else {
            return redirect('admin/login');
        }
        $this->uid = $user->id;
        $workOrderId = $input['work_order_id'];
        $work_order = $this->workorder->getParticularWorkOrder($workOrderId);
        if (!$work_order->technician_review) {
            die(json_encode(array('result' => false, 'msg' => 'Under technician review')));
        }


        if (empty($work_order)) {
            die(json_encode(array('result' => false, 'msg' => 'Workorder not found')));
        }
        $technicianReview = DB::table('tbl_technician as t')
            ->join('tbl_users as u', 'u.id', '=', 't.user_id')
            ->where('u.id', '=', $work_order->technician_review)
            ->select('t.id')
            ->get()->first();
        if (empty($technicianReview)) {
            die(json_encode(array('result' => false, 'msg' => 'Technician not found for this workorder')));
        }

        $this->tid = $technicianReview->id;
        $work_order_items = $this->workorder->totalInstruments($workOrderId);
        $work_order_items_calibrated = $this->workorder->totalInstrumentsCalibrated($workOrderId);

        $service_request_id = (isset($work_order->request_id) && $work_order->request_id) ? $work_order->request_id : '';
//        if($service_request_id)
//        {
//
//        }

        $customer = DB::table('tbl_service_request')->join('tbl_customer', 'tbl_customer.id', '=', 'tbl_service_request.customer_id')->where('tbl_service_request.id', '=', $work_order->request_id)->get()->first();

        $technician = DB::table('tbl_technician as t')
            ->join('tbl_users as u', 'u.id', '=', 't.user_id')
            ->where('t.id', '=', $this->tid)->get()->first();

        $digital_barometer = $this->workorderitemmove->getTechnicianDeviceBarometer(2, $this->tid, $workOrderId);
        $digital_thermometer = $this->workorderitemmove->getTechnicianDeviceThermometer(3, $this->tid, $workOrderId);
        $digital_thermocouple = $this->workorderitemmove->getTechnicianDeviceThermocouple(4, $this->tid, $workOrderId);
        $digital_balance = $this->workorderitemmove->getTechnicianDeviceBalance(1, $this->tid, $workOrderId);
        $workorder_calibration = $this->workorderitemmove->getWorkorderCalibrationItems($workOrderId, $workOrderItemIDs);
        $workorder_calibrations = $this->workorderitemmove->getWorkorderCalibrations($workOrderId);
        $calibration_data = array();
        $multicalibration_data = array();
        $data = array();
        $reviewedReport = base_path() . '/public/report/consolidate/technicianreview/' . $work_order->report;
        //print_r($reviewedReport);die;
        if ($workorder_calibration) {

            foreach ($workorder_calibration as $key => $row) {
                $spares_used_array = array();

                $calibration_data[$key]['asset_no'] = $row->asset_no;
                $calibration_data[$key]['location'] = $row->location;
                $calibration_data[$key]['model'] = $row->model_description;
                $calibration_data[$key]['volume'] = $row->reading_mean_volume;
                $calibration_data[$key]['mean'] = $row->reading_mean;
                $calibration_data[$key]['sd'] = $row->reading_sd;
                $calibration_data[$key]['unc'] = $row->reading_unc;
                $calibration_data[$key]['actual_accuracy'] = $row->reading_accuracy;
                $calibration_data[$key]['specification_accuracy'] = "";
                $calibration_data[$key]['actual_precision'] = $row->reading_precision;
                $calibration_data[$key]['specification_precision'] = "";
                $calibration_data[$key]['status'] = $row->reading_status == 1 ? 'Passed' : 'Failed';
                $calibration_data[$key]['last_cal_date'] = $row->last_cal_date;
                $calibration_data[$key]['next_due_date'] = $row->next_due_date;
                $get_workorder_status = DB::table('tbl_workorder_status_move')->where('workorder_item_id', $row->work_order_item_id)
                    ->where('workorder_status', 2)->first();
                if ($get_workorder_status) {
                    $spares = DB::table('tbl_workorder_maintenance_log')->where('workorder_status_id', $get_workorder_status->id)->first();
                    if ($spares) {
                        if ($spares->workorder_spares) {
                            $sparesMaterial = json_decode($spares->workorder_spares);
                            if ($sparesMaterial) {

                                foreach ($sparesMaterial as $spareKey => $spareRow) {
                                    $spares_used_array[$spareKey] = $spareRow->number;
                                }

                            }
                        }

                    }
                }
                $calibration_data[$key]['spares'] = implode(',', $spares_used_array);
            }
        }
        if($workorder_calibrations){
            foreach ($workorder_calibrations as $key => $row) {
                $multicalibration_data[$key]['channel'] = $row->reading_channel;
                $multicalibration_data[$key]['volume'] = $row->reading_mean_volume;
                $multicalibration_data[$key]['mean'] = $row->reading_mean;
                $multicalibration_data[$key]['sd'] = $row->reading_sd;
                $multicalibration_data[$key]['unc'] = $row->reading_unc;
                $multicalibration_data[$key]['actual_accuracy'] = $row->reading_accuracy;
                $multicalibration_data[$key]['specification_accuracy'] = "";
                $multicalibration_data[$key]['actual_precision'] = $row->reading_precision;
                $multicalibration_data[$key]['specification_precision'] = "";
                $multicalibration_data[$key]['status'] = $row->reading_status == 1 ? 'Passed' : 'Failed';

            }
        }
        $data['last_cal_date'] = $calibration_data[0]['last_cal_date'];
        $data['next_due_date'] = $calibration_data[0]['next_due_date'];
        $data['asset_no'] = $calibration_data[0]['asset_no'];
        $data['digital_barometer'] = $digital_barometer;
        $data['digital_thermometer'] = $digital_thermometer;
        $data['digital_thermocouple'] = $digital_thermocouple;
        $data['digital_balance'] = $digital_balance;
        $data['customer'] = $customer;
        $data['technician'] = $technician;
        $data['workorder'] = $work_order;
        $data['calibrated_datas'] = $calibration_data;
        $data['multicalibrated_datas'] = $multicalibration_data;
        $data['digital_barometer_device'] = (isset($digital_barometer->serial_no)) ? $digital_barometer->serial_no : '';
        $data['digital_thermometer_device'] = (isset($digital_thermometer->serial_no)) ? $digital_thermometer->serial_no : '';
        $data['digital_thermocouple_device'] = (isset($digital_thermocouple->serial_no)) ? $digital_thermocouple->serial_no : '';
        $data['digital_balance_device'] = (isset($digital_balance->serial_no)) ? $digital_balance->serial_no : '';
        $data['uploaded_signature'] = $technician->signature;
        $data['admin_signature'] = $adminsignature;
        $data['tech_date'] = $work_order->technician_review_date;
        $data['admin_date'] = date('M-d-Y');
        $data['comments'] = $work_order->comments;
        $data['barometer_last_cal_date'] = (isset($digital_barometer->last_cal_date)) ? $digital_barometer->last_cal_date : '';
        $data['thermometer_last_cal_date'] = (isset($digital_thermometer->last_cal_date)) ? $digital_thermometer->last_cal_date : '';
        $data['thermocouple_last_cal_date'] = (isset($digital_thermocouple->last_cal_date)) ? $digital_thermocouple->last_cal_date : '';
        $data['balance_last_cal_date'] = (isset($digital_balance->last_cal_date)) ? $digital_balance->last_cal_date : '';

        $data['barometer_next_due_date'] = (isset($digital_barometer->next_due_date)) ? $digital_barometer->next_due_date : '';
        $data['thermometer_next_due_date'] = (isset($digital_thermometer->next_due_date)) ? $digital_thermometer->next_due_date : '';
        $data['thermocouple_next_due_date'] = (isset($digital_thermocouple->next_due_date)) ? $digital_thermocouple->next_due_date : '';
        $data['balance_next_due_date'] = (isset($digital_balance->next_due_date)) ? $digital_balance->next_due_date : '';

//        echo'<pre>';print_r($data);'</pre>';die;

        //$technicianpath = base_path() . '/public/report/consolidate/technicianreview';
        $reportpath = base_path() . '/public/overallreport';
        $reportFile = 'consolidate_report-' . uniqid();

        view()->share($data);
        $pdf = PDF::loadView('report.consolidaadminreport')
            ->save($reportpath . '/' . $reportFile . '.pdf', 'F');

        $pathToFile = base_path() . '/public/overallreport/' . $reportFile . '.pdf';

        $headers = array(
            'Content-Type: application/pdf',
        );
        $saveConsolidate['id'] = false;
        $saveConsolidate['work_order_id'] = $workOrderId;
        $saveConsolidate['work_order_item_id'] = json_encode($workOrderItemIDs);
        $saveConsolidate['report'] = $reportFile . '.pdf';
        $saveConsolidate['created_date'] = date('Y-m-d H:i:s');
        DB::table('tbl_conslidate_report')->insertGetId($saveConsolidate);

        $pdf->stream();
        $pdf->download();
        die(json_encode(array('result' => true, 'msg' => 'Pdf has been generated')));

    }


    public
    function getWorkOrderItemsforsendMail(Request $request)
    {

        $input = Input::all();
        $workorderId = $input['workOrderId'];
        $workorderReports = DB::table('tbl_conslidate_report as r')
            ->where('work_order_id', $workorderId)
            ->get();
        $reports = array();
        if (count($workorderReports)) {
            foreach ($workorderReports as $key => $row) {
                $itemIds = json_decode($row->work_order_item_id);
                $items = DB::table('tbl_work_order_items as oi')
                    ->join('tbl_service_request_item as ri', 'ri.id', '=', 'oi.request_item_id')
                    ->join('tbl_due_equipments as de', 'de.id', '=', 'ri.due_equipments_id')
                    ->join('tbl_equipment as e', 'e.id', '=', 'de.equipment_id')
                    ->join('tbl_equipment_model as em', 'em.id', '=', 'e.equipment_model_id')
                    ->select('asset_no', 'serial_no', 'model_name')
                    ->whereIn('oi.id', $itemIds)->get();
                $reports[$key]['id'] = $row->id;
                $reports[$key]['reports_items'] = $items;
                $reports[$key]['created_date'] = date('d-m-Y', strtotime(str_replace('-', '/', $row->created_date)));

            }
        }
        //echo'<pre>';print_r($reports);'</pre>';die;

        $view = view('calibration.workOrderItemsforSend', ['details' => $reports])->render();

        echo json_encode(array('result' => true, 'data' => $view));

    }

    public
    function sendReportToCustomer(Request $request)
    {
        $input = Input::all();
        $consolidate_id = $input['consolidate_id'];
        $report = DB::table('tbl_conslidate_report as cr')
            ->join('tbl_work_order as w', 'w.id', '=', 'cr.work_order_id')
            ->join('tbl_service_request as sr', 'sr.id', '=', 'w.request_id')
            ->join('tbl_customer_billing_address as ba', 'ba.id', '=', 'sr.billing_address_id')
            ->select('cr.report', 'ba.email', 'ba.billing_contact')
            ->where('cr.id', $consolidate_id)->first();
        $file_name = (isset($report->report) && $report->report) ? $report->report : '';
        $email = (isset($report->email) && $report->email) ? $report->email : '';
        if ($file_name && $email) {
            $pathToFile = base_path() . '/public/overallreport/' . $file_name;
            if (file_exists($pathToFile)) {

                $query = DB::table('tbl_email_template');
                $query->where('tplid', '=', 8);
                $result = $query->first();
                $result->tplmessage = str_replace('{name}', $report->billing_contact, $result->tplmessage);
                $param['message'] = $result->tplmessage;
                $param['name'] = $report->billing_contact;
                $param['title'] = $result->tplsubject;

                $data = array('data' => $param);

                Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param, $email, $pathToFile) {
                    $message->to($email)->subject
                    ($param['title']);
                    $message->attach($pathToFile, ['as' => 'Consolidate.pdf', 'mime' => 'pdf']);
                });
            }

        }
        echo json_encode(array('result' => true));

    }

    public
    function calibrationoutsidereview($data)
    {
        // echo '<pre>';print_r($data);exit;
        $user = request()->user();
        if ($user) {
            $userId = $user->id;
            $adminsignature = $user->signature;
        }
        $segments = explode('/', $data['file_path']);
        $reviewReport = base_path() . '/public/report/technicianreview/' .  $segments[7];

        if (file_exists($reviewReport)) {
            file_put_contents(base_path() . '/public/report/adminreview/'.$segments[7],file_get_contents($reviewReport));
            $reportFile = 'report-' . uniqid();
            $save_report['id'] = $data['equipment_details']->id;
            $save_report['report'] = $segments[7];
            $save_report['admin_review'] = $userId;
            $save_report['admin_review_date'] = date('Y-m-d');
//        echo '<pre>';print_r($save_report);exit;
            $this->workorder->saveWorkOrderItem($save_report);
            $saveReqItem['id'] = $data['equipment_details']->servicerequestItemId;
            $saveReqItem['is_calibrated'] = '1';
            DB::table('tbl_service_request_item')->where('id', '=', $data['equipment_details']->servicerequestItemId)
                ->update($saveReqItem);

            die(json_encode(array('result' => true, 'message' => 'The report is successfully verified.')));
        }else{
            return false;
            die(json_encode(array('result' => false, 'message' => 'The report is not Found')));
        }
    }


}



