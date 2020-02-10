<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;

use App\Models\Equipment;
use App\Models\Sentinel\User;
use App\Models\ServicePlan;
use App\Models\Servicerequest;

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

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->servicePlan = new ServicePlan();
        $this->equipment = new Equipment();
        $this->servicerequest = new Servicerequest();


    }

    public function index(Request $request)
    {

        $input = Input::all();

        $title = 'Novamed - Service Plan';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tsp.id as planId', 'tsp.service_plan_name as planName', 'tsp.product_id as productId', 'tsp.issue_certificate as issueCertificate', 'tsp.as_found as asFound', 'tsp.as_calibrate as asCalibrate',
                'tsp.as_found_readings as foundReadings'
            , 'as_calibrate_readings as calibrateReading');
            $data = $this->servicePlan->AllServicePlan('', '', 'tsp.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tsp.service_plan_name'));

        } else {
            $select = array('tsp.id as planId', 'tsp.service_plan_name as planName', 'tsp.product_id as productId', 'tsp.issue_certificate as issueCertificate', 'tsp.as_found as asFound', 'tsp.as_calibrate as asCalibrate',
                'tsp.as_found_readings as foundReadings'
            , 'as_calibrate_readings as calibrateReading');
            $data = $this->servicePlan->AllServicePlan('', '', 'tsp.id', 'DESC', array('select' => $select));

        }
//        echo '<pre>';print_r($data);die;
        $asFoundDrops = array('' => '', '1' => 'Yes', '2' => 'N/A');
        $asCalibrateDrops = array('' => '', '1' => 'Yes', '2' => 'N/A');
        $temp = array();


        if (!$data->isEmpty()) {
            foreach ($data as $key => $value) {

                $temp[$key]['planId'] = $value->planId;
                $temp[$key]['planName'] = $value->planName;
                $temp[$key]['productId'] = $value->productId;
                $getProduct = $this->equipment->getProduct($temp[$key]['productId']);
                $temp[$key]['productType'] = $getProduct->product_type_name;
                $asFound = $value->asFound;
                if ($asFound == '1') {
                    $temp[$key]['asFoundStatus'] = 'Yes';
                } else {
                    $temp[$key]['asFoundStatus'] = 'N/A';
                }
                $asCalibrate = $value->asCalibrate;
                if ($asCalibrate == '1') {
                    $temp[$key]['asCalibrateStatus'] = 'Yes';
                } else {
                    $temp[$key]['asCalibrateStatus'] = 'N/A';
                }

            }
        }

//        echo '<pre>';print_r($temp);die;

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($temp, count($temp), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());

        return view('serviceplan.serviceplanlist')->with('title', $title)->with('asCalibrate', $asCalibrateDrops)->with('asFound', $asFoundDrops)->with('data', $paginatedItems);


    }

    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart'];


        $search['tsp.service_plan_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['tpt.product_type_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['tsp.as_found'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['tsp.as_calibrate'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
//echo '<pre>';print_r($search);die;
        $asFound = array('' => '', '1' => 'Yes', '2' => 'N/A');
        $asCalibrate = array('' => '', '1' => 'Yes', '2' => 'N/A');

        $select = array('tsp.id as planId', 'tsp.service_plan_name as planName', 'tsp.product_id as productId',
            'tsp.issue_certificate as issueCertificate', 'tsp.as_found as asFound', 'tsp.as_calibrate as asCalibrate',
            'tsp.as_found_readings as foundReadings'
        , 'as_calibrate_readings as calibrateReading', 'tpt.product_type_name as productType');

        $data = $this->servicePlan->AllServicePlanGrid($param['limit'], $param['offset'], 'tsp.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tsp.as_found', 'tsp.as_calibrate'));
        $count = $this->servicePlan->AllServicePlanGrid('', '', 'tsp.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tsp.as_found', 'tsp.as_calibrate'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->planName;
                $values[$key]['1'] = $row->productType;
//                $values[$key]['2'] = $row->asFound;
//                $values[$key]['3'] = $row->asCalibrate;


                if ($row->asFound == '1') {
                    $values[$key]['2'] = 'Yes';
                } else {
                    $values[$key]['2'] = 'N/A';
                }
                if ($row->asCalibrate == '1') {
                    $values[$key]['3'] = 'Yes';
                } else {
                    $values[$key]['3'] = 'N/A';
                }
//                <a href="" data-toggle="modal" data-target="#form-bp1"
//                                                               id="view" data-id="{{$value['planId']}}" class=" btn-lg"><i
//                                                                        class="fa fa-eye"></i></a>
                $values[$key]['4'] = "<a href=" . '#' . " data-toggle=" . 'modal' . " data-target=" . '#form-bp1' . " 
                id=" . 'view' . " data-id=" . $row->planId . " class=" . 'btn-lg' . "\"><i class='fa fa-eye'></a>";
                $values[$key]['5'] = "<a href=" . url("admin/editServicePlan/" . $row->planId) . "><i class='fa fa-pencil'></a>";


                $values[$key]['6'] = " <a href='javascript:void(0)' data-src=" . url('admin/deleteplan/' . $row->planId) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";

                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'],'asFound'=>$asFound,'asCalibrate'=>$asCalibrate, 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function form(Request $request, $id = false)
    {

        $input = Input::all();
        $title = 'Novamed-Service Plan Creation';

//        echo '<pre>';print_r($input);exit;
//        select the product type

        $product = DB::table('tbl_product_type')->pluck('product_type_name', 'product_type_id');
        $product->prepend('Please Select Product Type', '');


        //echo '<pre>';print_r($product);die;

//        select the manufacturer

        $volume = DB::table('tbl_volume')->pluck('volume_name', 'id');
     $volume->prepend('Select Volume', '');



                $selectTestPoints = DB::table('tbl_test_points')->select('name', 'id')->get();

        $servicePlanType_drop = array();
        $servicePlanType = DB::table('tbl_customer_type')->select('name', 'id')->where('is_active','=',1)->get();
        $servicePlanType_drop[0] = 'Select Type';
        if(count($servicePlanType))
        {
            foreach($servicePlanType as $key => $row){
                $servicePlanType_drop[$row->id] = $row->name;
            }
        }
        //$servicePlanType = array('' => 'Please Select', '1' => 'Basic', '2' => 'Pharamatical','3'=>'Clinical');
//        $servicePlanType = DB::table('tbl_customer_type')->pluck('name', 'id');
//        $servicePlanType->prepend('Select Type', '');


//        select the samples

        $reading = DB::table('tbl_samples')->orderby('id','ASC')->pluck('name', 'id');
        $reading->prepend('Select reading', '');


//        select the channels

        $channels = DB::table('tbl_channels')->pluck('channel_name', 'id');
        $channels->prepend('Select Channel', '');

//        select the channel numbers

//        $channelNumbers = DB::table('tbl_channel_numbers')->pluck('channel_number', 'id');
//        $channelNumbers->prepend('Select Channel Number', '');

        $channelNumbers = array(''=>"Select Channel Numbers");

//        select the operation

        $operationSelect = DB::table('tbl_operations')->pluck('operation_name', 'id');
        $operationSelect->prepend('Select Operation', '');


//        select channel points numbers

//        $channelCrops = DB::table('tbl_channel_points')->select(
//            DB::raw("CONCAT(point_name,'/',point_channel) AS name"), 'id')
//            ->pluck('name', 'id');
//        $channelCrops->prepend('Select channel points', '');
        $channelCrops = array(''=>"Select Channel Points");


//        echo '<pre>';print_r($channelCrops);die;


        $priceDetail = array();

//        select spare mode

        $spareSelect = DB::table('tbl_spare_mode')->pluck('mode_name', 'id');
        $spareSelect->prepend('Please Select Mode', '');


        $data = [

            'name' => isset($input['name']) ? $input['name'] : '',
            'id' => isset($input['id']) ? $input['id'] : false,
            'description' => isset($input['description']) ? $input['description'] : '',
            'producttype' => isset($input['producttype']) ? $input['producttype'] : '',
            'servicePlanType' => isset($input['servicePlanType']) ? $input['servicePlanType'] : '',
            'issue_certificate' => isset($input['issue_certificate']) ? $input['issue_certificate'] : '',
            'as_found' => isset($input['as_found']) ? $input['as_found'] : '',
            'as_calibrate' => isset($input['as_calibrate']) ? $input['as_calibrate'] : '',
            'foundReading' => isset($input['foundReading']) ? $input['foundReading'] : '',
            'foundReading1' => isset($input['foundReading1']) ? $input['foundReading1'] : '',
            'is_calibration_outside' => isset($input['is_calibration_outside']) ? $input['is_calibration_outside'] : '',
            'i' => 0
        ];

        $totalPriceDetails = array();
        $asFoundDetails = array();
        $asCalibrateDetails = array();


        if ($id) {
            $getPlan = $this->servicePlan->getPlan($id);

            if (!$getPlan) {
                return redirect('admin/servicelist')->with('message', 'Sorry! Details are not found.');
            } else {

                $getServicePricing = $this->servicePlan->getServicePricing($id);
                $data['id'] = $getPlan->id;
                $data['name'] = $getPlan->service_plan_name;
                $data['description'] = $getPlan->plan_description;
                $data['producttype'] = $getPlan->product_id;
                $data['issue_certificate'] = $getPlan->issue_certificate;
                $data['as_found'] = $getPlan->as_found;
                $data['as_calibrate'] = $getPlan->as_calibrate;
                $data['foundReading'] = $getPlan->as_found_readings;
                $asFoundTP = explode(',', $getPlan->as_found_TP);
                $asFoundValue = explode(',', $getPlan->as_found_value);
                $asFoundDetails = array_combine($asFoundTP, $asFoundValue);
                $asCalibrateTP = explode(',', $getPlan->as_calibrate_TP);
                $asCalibrateValue = explode(',', $getPlan->as_calibrate_value);
                $asCalibrateDetails = array_combine($asCalibrateTP, $asCalibrateValue);
//                echo '<pre>';print_r($asCalibrateDetails);die;
                $data['foundReading1'] = $getPlan->as_calibrate_readings;
                $data['servicePlanType'] = $getPlan->service_plan_type;
                $data['is_calibration_outside'] = $getPlan->calibration_outside;
                //echo '<pre>';print_r($getServicePricing);die;
                if (!$getServicePricing->isEmpty()) {

                    foreach ($getServicePricing as $pricekey => $pricevalue) {
                        $priceDetail[$pricekey]['Id'] = $pricevalue->id;


//                        get volume

                        $priceDetail[$pricekey]['volumeId'] = $pricevalue->volume;
                        $getVolume = $this->servicePlan->getVolume($priceDetail[$pricekey]['volumeId']);
                        $priceDetail[$pricekey]['volume'] = (isset($getVolume->volume_name)&&$getVolume->volume_name)?$getVolume->volume_name:'';

//                        get operation

                        $priceDetail[$pricekey]['operationId'] = $pricevalue->operation;
                        $getOperation = $this->servicePlan->getOperations($priceDetail[$pricekey]['operationId']);
                        //print_r($getOperation);die;
                        $priceDetail[$pricekey]['operation'] = (isset($getOperation->operation_name)&&$getOperation->operation_name)?$getOperation->operation_name:'';

//                        get channel

                        $priceDetail[$pricekey]['channelId'] = $pricevalue->channel;
                        $getChannel = $this->servicePlan->getChannel($priceDetail[$pricekey]['channelId']);
                        $priceDetail[$pricekey]['channel'] = (isset($getChannel->channel_name)&&$getChannel->channel_name)?$getChannel->channel_name:'';

                        //channel point

                        $priceDetail[$pricekey]['pointId'] = (isset($pricevalue->channel_point)&&$pricevalue->channel_point)?$pricevalue->channel_point:'';

                        $getChaanelPoint = $this->servicePlan->getChannelPoint($priceDetail[$pricekey]['pointId']);
                        if ($getChaanelPoint) {
                            $priceDetail[$pricekey]['channelnumber'] = (isset($getChaanelPoint->channel_number)&&$getChaanelPoint->channel_number)?$getChaanelPoint->channel_number:'';
                            $priceDetail[$pricekey]['point'] = (isset($getChaanelPoint->point_channel)&&$getChaanelPoint->point_channel)?$getChaanelPoint->point_channel:'';

                        } else {
                            $priceDetail[$pricekey]['channelnumber'] = '';
                            $priceDetail[$pricekey]['point'] = '';

                        }
                        $priceDetail[$pricekey]['channelNumberId'] = (isset($pricevalue->channel_number)&&$pricevalue->channel_number)?$pricevalue->channel_number:'';

                        $priceDetail[$pricekey]['price'] = (isset($pricevalue->price)&&$pricevalue->price)?$pricevalue->price:'';;
                    }
//                    echo '<pre>';
//print_r($priceDetail);exit;
                    $totalPriceDetails = count($getServicePricing);
                } else {
                    $totalPriceDetails = '';
                }


            }

//            echo '<pre>';
//            print_r($data);
//            die;
        }
        $rules = [
            'name' => 'required',
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
//DD($checkStatus);exit;
        if ($checkStatus) {
            return view('serviceplan.serviceplanForm')->with('input', $data)->with('priceDetail', $priceDetail)->with('operationSelect', $operationSelect)
                ->with('totalPriceDetails', $totalPriceDetails)->with('title', $title)->with('channelNumbers', $channelNumbers)->with('servicePlanType', $servicePlanType_drop)
                ->with('volume', $volume)->with('channels', $channels)->with('channelCrops', $channelCrops)->with('producttype', $product)->with('selectTestPoints', $selectTestPoints)
                ->with('errors', $error)->with('product', $product)->with('reading', $reading)->with('asFoundDetails', $asFoundDetails)->with('asCalibrateDetails', $asCalibrateDetails);

        } else {
            $data = Input::all();

//            echo '<pre>';print_r($data);die;


            $loginuserId = Sentinel::getUser()->id;

            $save = array();

            $save['id'] = $data['id'];
            $save['service_plan_name'] = $data['name'];
           // $save['plan_description'] = $data['description'];
            $save['product_id'] = $data['producttype'];
            $save['issue_certificate'] = $data['issueCertificate'];
            $save['service_plan_type'] = $data['servicePlanType'];
            $save['as_found'] = $data['asFound'];
            $save['as_calibrate'] = $data['asCalibrated'];
            if ($data['asFound'] == 1) {
                if (isset($data['asFoundTestPoint']) && $data['asFoundTestPoint']) {

                    $param = array();
                    $tempvalue = array();
                    foreach ($data['asFoundTestPoint'] as $foundkey => $foundrow) {

                        $param[] = $foundkey;
                        $tempvalue[] = $foundrow;
                    }
                    $asFoundName = implode(',', $param);
                    $asFoundValue = implode(',', $tempvalue);
                    $save['as_found_TP'] = $asFoundName;
                    $save['as_found_value'] = $asFoundValue;

                }

                $save['as_found_readings'] = $data['foundReading'];
                $asFoundTestPoint = isset($data['asFoundTestPoint']) ? $data['asFoundTestPoint'] : '' ;
                if($data['foundReading'] == '' ||  $asFoundTestPoint == '' ){
                    die(json_encode(array('result' => false, 'message' => 'Test points & No of Readings are required for As Found Requirements')));
                }

            } else {
                $save['as_found_TP'] = '';
                $save['as_found_value'] = '';
                $save['as_found_readings'] = '';

            }
            if ($data['asCalibrated'] == 1) {

                if (isset($data['asCalibratedTestPoints']) && $data['asCalibratedTestPoints']) {

                    $calibrateparam = array();
                    $caalibratetempvalue = array();
                    foreach ($data['asCalibratedTestPoints'] as $calkey => $calrow) {

                        $calibrateparam[] = $calkey;
                        $caalibratetempvalue[] = $calrow;
                    }
                    $asCalibrateName = implode(',', $calibrateparam);
                    $asCalibrateValue = implode(',', $caalibratetempvalue);
                    $save['as_calibrate_TP'] = $asCalibrateName;
                    $save['as_calibrate_value'] = $asCalibrateValue;

                }

                $save['as_calibrate_readings'] = $data['foundReading1'];
                $asCalibaratedTestPoint = isset($data['asCalibratedTestPoints']) ? $data['asCalibratedTestPoints'] : '' ;

                if($data['foundReading1'] == '' ||  $asCalibaratedTestPoint = '' ){
                    die(json_encode(array('result' => false, 'message' => 'Test points & No of Readings are required for As Calibrated Requirements')));
                }
            } else {
                $save['as_calibrate_TP'] = '';
                $save['as_calibrate_value'] = '';
                $save['as_calibrate_readings'] = '';

            }

            if ($id) {
                $save['modified_by'] = $loginuserId;
            } else {
                $save['created_by'] = $loginuserId;
            }

            $save['is_active'] = 1;
            $save['calibration_outside'] = isset($data['is_calibration_outside'])  ? 1 : 0;

//            echo '<pre>';print_r($data);die;
            $savePlan = $this->servicePlan->saveServicePlan($save);


            if (isset($data['priceDetail']) && $data['priceDetail']) {

                foreach ($data['priceDetail'] as $pricekey => $priceval) {
                    $priceID = isset($priceval['Id']) ? $priceval['Id'] : false;
                    $temp['id'] = $priceID;
                    $temp['plan_id'] = $savePlan;
                    $temp['volume'] = $priceval['volume'];
                    $temp['operation'] = $priceval['operation'];
                    $temp['channel'] = $priceval['channel'];
                    $temp['channel_number'] = $priceval['channelNumber'];
                    $temp['channel_point'] = $priceval['point'];
                    $temp['price'] = $priceval['price'];
                    $temp['created_by'] = $loginuserId;
                    $savePlanPricing = $this->servicePlan->savePlanPricing($temp);
                }

            }
            if ($id) {
                die(json_encode(array('result' => true, 'message' => 'Updated Successfully')));

//                return redirect('admin/servicelist')->with('message', 'Updated Successfully');
            }else{
                die(json_encode(array('result' => true, 'message' => 'Added Successfully')));

//                return redirect('admin/servicelist')->with('message', 'Added Successfully');

            }
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

    public function viewServicePlan()
    {

        $input = Input::all();

        $Id = $input['Id'];

        $plandetails = $this->servicePlan->getServicePricing($Id);
        $temp = array();

        if ($plandetails) {
            foreach ($plandetails as $key => $value) {
                $volumeId = $value->volume;

                $getVolume = $this->servicePlan->getVolume($volumeId);
                $temp[$key]['volume'] = $getVolume->volume_name;

//                        get operation

                $operationId = $value->operation;
                $getOperation = $this->servicePlan->getOperations($operationId);
                $temp[$key]['operation'] = $getOperation->operation_name;

//                        get channel

                $channelId = $value->channel;
                $getChannel = $this->servicePlan->getChannel($channelId);
                $temp[$key]['channel'] = $getChannel->channel_name;

                //channel point

                $pointId = $value->channel_point;
                $getChannelPoint = $this->servicePlan->getChannelPoint($pointId);
                if ($getChannelPoint) {
                    $temp[$key]['point'] = $getChannelPoint->point_name . '/' . $getChannelPoint->point_channel;

                } else {
                    $temp[$key]['point'] = '';

                }


                $temp[$key]['price'] = $value->price;

            }
        }

//        echo '<pre>';print_r($temp);die;

        $view = View::make('serviceplan.servicepricingAjax', ['data' => $temp]);
        $formData = $view->render();
        die(json_encode(array("result" => true, "formData" => trim($formData))));
    }

    public function deleteServicePricing(Request $request)
    {
        $input = Input::all();
        $servicePriceId = $input['Id'];

        if ($servicePriceId) {
            $items = $this->servicerequest->serviceItemEquipments(0, 0, array('servicePriceId' => $servicePriceId));
//            echo '<pre>';print_r($items);die;
            if ($items->isEmpty()) {
                $deleteServicePriceId = $this->servicePlan->deleteServicePricingByPriceId($servicePriceId);
                die(json_encode(array('result' => true, 'message' => 'Successfully deleted')));
            } else {
                die(json_encode(array('result' => false, 'value' => 1, 'message' => 'Deleted not Successfully')));
            }

        } else {
            die(json_encode(array('result' => false, 'value' => 0, 'message' => 'Deleted not Successfully')));
        }

    }

    public
    function delete($id)
    {

        $getdetail = $this->servicePlan->getPlan($id);

        if ($getdetail) {

            $getSubdetail = DB::table('tbl_customer_setups')->where('plan_id','=',$id)->select('*')->first();

            if($getSubdetail){
                $message = Session::flash('error', "You can't able to delete this plan");
                return redirect('admin/servicelist')->with(['data', $message], ['message', $message]);
            }

            $message = Session::flash('message', 'Deleted Successfully!');
            $deletePlan = $this->servicePlan->deletePlan($id);
            $deleteServicePricing = $this->servicePlan->deletePlanPricing($id);

            return redirect('admin/servicelist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/servicelist')->with('data', $error);
        }
    }


    function saveajaxVolume()
    {

        $post = Input::all();
        if($post)
        {
            $getvolume = $this->servicePlan->getVolumeChange($post);
            $savevolume['id'] = $post['id'];
            $savevolume['volume'] =$post['volumePrice'];
            $savevolume['operation'] = $post['operationPrice'];
            $savevolume['channel'] = $post['channelPrice'];
            $savevolume['channel_number'] = $post['channelnumber'];
            $savevolume['channel_point'] = $post['pointsPrice'];
            $savevolume['price'] = $post['pricePrice'];
            $id = $this->servicePlan->savePlanPricing($savevolume);
            if(empty($getvolume))
            {
                die(json_encode(array('result' => true, 'message' => 'Volume values are updated','updated'=>true)));
            }
            else
            {
                die(json_encode(array('result' => true, 'message' => 'Volume values are updated','updated'=>false)));
            }


        }
    }
}
