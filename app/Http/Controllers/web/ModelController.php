<?php

namespace App\Http\Controllers\web;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\ProductType;
use App\Models\Sentinel\User;
use App\Models\Equipment;
use App\Models\ServicePlan;
use App\Models\Testplan;
use App\Workorderprocessupdate;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use File;
use Image;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;

//use Request;

class ModelController extends Controller
{

    public function __construct()
    {
        $this->equipment = new Equipment();
        $this->testplan = new Testplan();
        $this->brand = new Brand();
        $this->servicePlan = new ServicePlan();
        $this->producttype = new ProductType();
        $this->workorderProcess = new Workorderprocessupdate();

    }

    public function index(Request $request)

    {

        $input = Input::all();
        //echo'<pre>';print_r($input);'</pre>';die;
        $title = 'Novamed-Equipment Model';
        $postvalue = isset($input['postvalue']) ? $input['postvalue'] : '';
        $posttestplanid = isset($input['postTestPlanId']) ? $input['postTestPlanId'] : '';
        $manufacturer = DB::table('tbl_manufacturer')->pluck('manufacturer_name', 'manufacturer_name');
        $manufacturer->prepend('', '');
        return view('model.modellist')->with('title', $title)->with('postvalue', $postvalue)->with('posttestplanid', $posttestplanid)->with('manufacturer', $manufacturer);
    }

    function listData(Request $request)
    {
        $input = Input::all();
//        print_R($input);exit;
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['tm.manufacturer_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['tb.brand_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['te.model_description'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['tbo.operation_name'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['c.channel_name'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';
        $search['v.volume_name'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';
        $search['te.volume_from'] = isset($input['sSearch_6']) ? $input['sSearch_6'] : '';
        $search['te.volume_to'] = isset($input['sSearch_7']) ? $input['sSearch_7'] : '';
        $search['u.unit'] = isset($input['sSearch_8']) ? $input['sSearch_8'] : '';
        $search['cn.channel_number'] = isset($input['sSearch_9']) ? $input['sSearch_9'] : '';
        $search['te.model_price'] = isset($input['sSearch_10']) ? $input['sSearch_10'] : '';
        $search['te.sku_number '] = isset($input['sSearch_11']) ? $input['sSearch_11'] : '';//echo'<pre>';print_r($search);'</pre>';die;
//         echo'<pre>';print_r($search);'</pre>';die;
        $select = array('tm.manufacturer_name', 'tb.brand_name', 'tbo.operation_name', 'c.channel_name',
            'v.volume_name', 'te.volume_value', 'u.unit', 'cn.channel_number', 'te.model_price', 'te.id', 'te.model_name','te.model_description','te.sku_number');
        $data = $this->equipment->AllEquipmentGrid($param['limit'], $param['offset'], 'te.id', 'DESC', array('select' => $select, 'search' => $search),
            false, array('te.model_name', 'te.unit', 'te.channel',
                'tbo.operation_name', 'tb.brand_name', 'tm.manufacturer_name'));

        $count = $this->equipment->AllEquipmentGrid($param['limit'], $param['offset'], 'te.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true),
            true, array('te.model_name', 'te.unit', 'te.channel', 'tbo.operation_name', 'tb.brand_name', 'tm.manufacturer_name'));
//       print_R($data);exit;
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $fromTo[0] = '';
                $fromTo[1] = '';
                if ($row->volume_value) {
                    $fromTo = explode('-', $row->volume_value);

                }
                $values[$key]['0'] = $row->manufacturer_name;
                $values[$key]['1'] = $row->brand_name;
                $values[$key]['2'] = $row->model_description;
                $values[$key]['3'] = $row->operation_name;
                $values[$key]['4'] = $row->channel_name;

                $values[$key]['5'] = $row->volume_name;
                $values[$key]['6'] = isset($fromTo[0]) ? $fromTo[0] : '';
                $values[$key]['7'] = isset($fromTo[1]) ? $fromTo[1] : '';
                $values[$key]['8'] = $row->unit;
                $values[$key]['9'] = $row->channel_number . ' Ch';
                $values[$key]['10'] = $row->model_price;
                $values[$key]['11'] = $row->sku_number;
                $values[$key]['12'] = "<a href=" . url("admin/editmodel/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $values[$key]['13'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletemodel/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";

                $i++;
            }

        }
        //echo'<pre>';print_r(json_encode(array('sEcho'=>'','iTotalRecords'=>$count,'iTotalDisplayRecords'=>$param['limit'],'aaData'=>$values)));'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function modelListGrid(Request $request)
    {

        $input = Input::all();

        $title = 'Novamed-Equipment Model';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $postvalue = isset($input['postvalue']) ? $input['postvalue'] : '';
        $posttestplanid = isset($input['postTestPlanId']) ? $input['postTestPlanId'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('te.*');
            $data = $this->equipment->AllEquipment('', '', 'te.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('te.model_name', 'te.unit', 'te.channel'));

        } else {
            $select = array('te.*');
            $data = $this->equipment->AllEquipment('', '', 'te.id', 'DESC', array('select' => $select));

        }
//        echo '<pre>';print_r($data);die;


        $chartdata = array();
        foreach ($data as $chart) {

            $brandId = $chart->brand_id;
            $brandNameValue = $this->brand->getbrand($brandId);
            $brandName = $brandNameValue->brand_name;

            $channelId = $chart->channel;
            $getchannelValue = $this->servicePlan->getChannel($channelId);
            $channelValue = $getchannelValue->channel_name;

            $channelnumberId = $chart->channel_number;
            $getchannelNumberValue = $this->servicePlan->getChannelNumber($channelnumberId);
            if ($getchannelNumberValue) {
                $channelNumberValue = $getchannelNumberValue->channel_number;
            } else {
                $channelNumberValue = '';
            }


            $productId = $chart->product_id;
            $getproductValue = $this->producttype->getproduct($productId);
            $productValue = $getproductValue->product_type_name;


            $brandOperationId = $chart->brand_operation;
            $getOperationValue = $this->servicePlan->getOperations($brandOperationId);
            $operationValue = $getOperationValue->operation_name;

            $volumeId = $chart->volume;
            $getvolumeValue = $this->servicePlan->getVolume($volumeId);
            $volumeValue = $getvolumeValue->volume_name;

            $chartdata[] = array('name' => $chart->model_name, 'producttype' => $productValue, 'brand' => $brandName, 'brandoperation' => $operationValue, 'channel' => $channelValue, 'channelNo' => $channelNumberValue, 'volume' => $volumeValue);
        }

        if (!$chartdata) {
            $jsonvalue = '[{"y":"2017 - Aug","a":0},{"y":"2017 - Jul","a":0},{"y":"2017 - Jun","a":0},{"y":"2017 - Sep","a":0}]';
        } else {
            $jsonvalue = json_encode($chartdata);

        }


        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('model.modellistgrid')->with('data', $paginatedItems)->with('jsonvalue', $jsonvalue)->with('keyword', $keyword)->with('title', $title)->with('postvalue', $postvalue)->with('posttestplanid', $posttestplanid);

    }

    public function form(Request $request, $id = false)
    {

        $input = Input::all();

        $title = 'Novamed - Model Creation';

//        select the product type

        $product = DB::table('tbl_product_type')->pluck('product_type_name', 'product_type_id');
        $product->prepend('Select Product Type', '');


//        select the manufacturer

//        $manufacturer = DB::table('tbl_manufacturer')->pluck('manufacturer_name', 'manufacturer_id');
//        $manufacturer->prepend('Select Manufacturer', '');
        $manufacturer_drop = array();
        if($id)
        {
            $manufacturer = DB::table('tbl_manufacturer')->select('manufacturer_name', 'manufacturer_id')->get();
        }
        else
        {
            $manufacturer = DB::table('tbl_manufacturer')->select('manufacturer_name', 'manufacturer_id')->where('is_active','=',1)->get();
        }

        $manufacturer_drop[0] = 'Select Manufacturer';
        if(count($manufacturer))
        {
            foreach($manufacturer as $key => $row){
                $manufacturer_drop[$row->manufacturer_id] = $row->manufacturer_name;
            }
        }
//       echo '<pre>'; print_r($manufacturer_drop);exit;
        $testpoints = DB::table('tbl_test_points')->select('name', 'id')->get();


//        select the brand
        $brand_drop = array();

        $brand = DB::table('tbl_brand')
            ->select('tbl_brand.brand_id', 'tbl_brand.brand_name', 'tbl_manufacturer.manufacturer_name')
            ->join('tbl_manufacturer', 'tbl_manufacturer.manufacturer_id', '=', 'tbl_brand.manufacturer_id', 'left')->where('tbl_brand.is_active','=',1)->get();
        $brand_drop[0] = 'Select Brand';
        if (count($brand)) {
            foreach ($brand as $key => $row) {
                $brand_drop[$row->brand_id] = $row->brand_name;
            }
        }

//         echo'<pre>';print_r($brand_drop);'</pre>';die;

//        select the channels

        $channels = DB::table('tbl_channels')->pluck('channel_name', 'id');
        $channels->prepend('Select Channel', '');

//        select the operation
        $operationSelect = array();
        $operation = DB::table('tbl_operations')->select('operation_name', 'id')->where('is_active','=',1)->get();
        $operationSelect[0] = 'Select Operation';
        if(count($operation))
        {
            foreach($operation as $key => $row){
                $operationSelect[$row->id] = $row->operation_name;
            }
        }

//        $operationSelect = DB::table('tbl_operations')->pluck('operation_name', 'id');
//        $operationSelect->prepend('Select Operation', '');


//        select the volume
        $volumeSelect = DB::table('tbl_volume')->pluck('volume_name', 'id');
        $volumeSelect->prepend('Select Volume', '');

//        select channel numbers

        $channelNumberSelect = DB::table('tbl_channel_numbers')->pluck('channel_number', 'id');
        $channelNumberSelect->prepend('Select Channels', '');

//        select the

        $modelunits = DB::table('tbl_units')->pluck('unit', 'id');
        $modelunits->prepend('μl', 4);

//        select spare mode

        $spareSelect = DB::table('tbl_spare_mode')->pluck('mode_name', 'id');
        $spareSelect->prepend('Select Mode', '');


        $customerType = DB::table('tbl_customer_type')->pluck('name', 'id');
        $customerType->prepend('Pleace Choose Customer Type', '');

        $limit = array();


        $data = [
            'id' => $id,
            'Brand' => isset($input['Brand']) ? $input['Brand'] : '',
            'channels' => isset($input['channels']) ? $input['channels'] : '',
            'volume' => isset($input['volume']) ? $input['volume'] : '',
            'channelNo' => isset($input['channelNo']) ? $input['channelNo'] : '',
            'modeldescription' => isset($input['modeldescription']) ? $input['modeldescription'] : '',
            'manufacturer' => isset($input['manufacturer']) ? $input['manufacturer'] : '',
            'producttype' => isset($input['producttype']) ? $input['producttype'] : '',
            'model_name' => isset($input['model_name']) ? $input['model_name'] : '',
            'operation' => isset($input['operation']) ? $input['operation'] : '',
            'volume_from' => isset($input['volume_from']) ? $input['volume_from'] : '',
            'volume_to' => isset($input['volume_to']) ? $input['volume_to'] : '',
            'modelPrice' => isset($input['modelPrice']) ? $input['modelPrice'] : '',
            'unit' => isset($input['unit']) ? $input['unit'] : '',
            'operating_manual_name' => isset($input['operating_manual_name']) ? $input['operating_manual_name'] : '',
            'specification_name' => isset($input['specification_name']) ? $input['specification_name'] : '',
            'image' => isset($input['image']) ? $input['image'] : '',
            'broucher_name' => isset($input['broucher_name']) ? $input['broucher_name'] : '',
            'customerType' => isset($input['customerType']) ? $input['customerType'] : '',
            'modelBuy' => isset($input['modelBuy']) ? $input['modelBuy'] : '',
            'modelService' => isset($input['modelService']) ? $input['modelService'] : '',
            'model_buy' => isset($input['modelBuy']) ? $input['modelBuy'] : '',
            'model_service' => isset($input['modelService']) ? $input['modelService'] : '',
            'modelSku' => isset($input['modelSku']) ? $input['modelSku'] : '',
             'is_active' => isset($input['is_active']) ? $input['is_active'] : '1',
        ];

        $data['testplan'] = array();
        $spareDetail = array();
        $tipDetail = array();
        $limitDetail = array();
        $accessoryDetail = array();
        $getManualDetail = array();
        $getBroucherDetail = array();
        $getSpecDetail = array();
        $imageDetail = array();
        $totalAccessory = 0;
        $totalTips = 0;
        $totalspares = 0;
        $totalManualDocs = 0;
        $totalSpecificationDocs = 0;
        $totalBroucherDocs = 0;
        $totalimages = 0;

        if ($id) {
            $equipment = $this->equipment->getmodel($data['id']);
//            echo '<pre>';print_r($equipment);die;
            if (!$equipment) {
                return redirect('admin/modellist')->with('message', 'Sorry! Details are not found.');
            } else {

//                $manufacturergroup = explode(',', $equipment->manufacturer_ids);
//                $manufacturers = $manufacturergroup;
//                $data['manufacturer'] = $manufacturers;
//                if ($manufacturers) {
//                    $getmodeleitvalue = array();
//                    foreach ($manufacturers as $modelvalue) {
//                        $getmodeleitvalue = $modelvalue;
//                        $getModelValues = $this->equipment->getmanufacturerbyId($getmodeleitvalue);
//                        $data['manufacturer'][] = $getModelValues->serial_no;
//                    }
//                } else {
//                    $data['manufacturer'] = '';
//                }

                $data['producttype'] = $equipment->product_id;
                $manufacturergroup = $equipment->manufacturer_ids;
                $getModelValues = $this->equipment->getmanufacturerbyId($manufacturergroup);
//                echo '<pre>';print_r($getModelValues);die;
//                $data['manufacturer'] = $getModelValues->manufacturer_name;

                $data['manufacturer'] = $getModelValues->manufacturer_id;
                $data['Brand'] = $equipment->brand_id;
                $data['channels'] = $equipment->channel;
                $data['modelPrice'] = $equipment->model_price;
                $data['modelSku'] = $equipment->sku_number;
                $data['volume'] = $equipment->volume;
                $data['channelNo'] = $equipment->channel_number;
                $data['modeldescription'] = $equipment->model_description;
                $data['model_name'] = $equipment->model_name;
                $data['operation'] = $equipment->brand_operation;
                $data['model_name'] = $equipment->model_name;
                $data['operating_manual_name'] = $equipment->manual_name;
                $data['specification_name'] = $equipment->specification_name;
                $data['broucher_name'] = $equipment->broucher_name;
                $data['is_active'] = $equipment->is_active;
                $data['model_buy'] = $equipment->model_buy;
                $data['model_service'] = $equipment->model_service;
                // echo'<pre>';print_r($data);'</pre>';die;
//                $data['image'] = $equipment->model_image;
                $volumeValue = $equipment->volume_value;
                if ($data['volume'] == 1) {

                    $seperationVolume = explode('-', $volumeValue);
                    if ($seperationVolume) {
                        $data['volume_from'] = $seperationVolume[0];
                        $data['volume_to'] = $seperationVolume[1];
                    }
                } else {
                    $data['volume_from'] = $volumeValue;
                }


//                get limit tolerance

                $getlimits = $this->equipment->getlimits($id);

                $data['id'] = $equipment->id;
                $data['model_name'] = $equipment->model_name;
                $data['unit'] = $equipment->unit;
                $data['channel'] = $equipment->channel;
                $data['i'] = count($getlimits);

                if (!$getlimits->isEmpty()) {

                    foreach ($getlimits as $key => $value) {
                        $limitDetail[$key]['Id'] = $value->id;
                        $limitDetail[$key]['target_value'] = $value->target_value;
                        $limitDetail[$key]['description'] = $value->description;
                        $limitDetail[$key]['accuracy'] = $value->accuracy;
                        $limitDetail[$key]['precision'] = $value->precision;
                        $limitDetail[$key]['accuracy_ul'] = $value->accuracy_ul;
                        $limitDetail[$key]['precesion_ul'] = $value->precesion_ul;
                    }
                }


                if (empty($limitDetail)) {

                    $limitDetail[0]['target_value'] = '';
                    $limitDetail[0]['accuracy'] = '';
                    $limitDetail[0]['precision'] = '';
                    $limitDetail[0]['accuracy_ul'] = '';
                    $limitDetail[0]['precesion_ul'] = '';
                    $limitDetail[1]['target_value'] = '';
                    $limitDetail[1]['accuracy'] = '';
                    $limitDetail[1]['precision'] = '';
                    $limitDetail[1]['accuracy_ul'] = '';
                    $limitDetail[1]['precesion_ul'] = '';
                    $limitDetail[2]['target_value'] = '';
                    $limitDetail[2]['accuracy'] = '';
                    $limitDetail[2]['precision'] = '';
                    $limitDetail[2]['accuracy_ul'] = '';
                    $limitDetail[2]['precesion_ul'] = '';

                }


//                get spares
//DD($id);exit;
                $getSpares = $this->equipment->getspares($id);
                $totalspares = 0;


                $spareDetail = array();

                if (!$getSpares->isEmpty()) {
                    $totalspares = count($getSpares);

                    foreach ($getSpares as $sparekey => $sparevalue) {
                        $spareDetail[$sparekey]['spareId'] = $sparevalue->id;
                        $spareDetail[$sparekey]['sparemode'] = $sparevalue->mode_id;
                        $getMode = $this->equipment->getspareMode($spareDetail[$sparekey]['sparemode']);

                        $spareDetail[$sparekey]['sparemodeValue'] = $getMode->mode_name;
                        $spareDetail[$sparekey]['Price'] = $sparevalue->price;
                        $spareDetail[$sparekey]['servicePrice'] = $sparevalue->service_price;
                        $spareDetail[$sparekey]['number'] = $sparevalue->sku_number;
                        $spareDetail[$sparekey]['partname'] = $sparevalue->part_name;
                        $spareDetail[$sparekey]['image'] = $sparevalue->image;
                        $spareDetail[$sparekey]['part_buy'] = $sparevalue->part_buy;
                        $spareDetail[$sparekey]['part_sell'] = $sparevalue->part_sell;

                    }
                }


//                get images

                $getModelImages = $this->equipment->getmodelimages($id);

//                echo '<pre>';print_r($getModelImages);die;
                $totalimages = 0;


                $imageDetail = array();

                if (!$getModelImages->isEmpty()) {
                    $totalimages = count($getModelImages);


                    foreach ($getModelImages as $imagekey => $imageval) {
                        $imageDetail[$imagekey]['imageId'] = $imageval->id;
                        $imageDetail[$imagekey]['imagename'] = $imageval->image;
                        $imageDetail[$imagekey]['imageDocName'] = $imageval->image;


                    }
                }

                //                get spares

                $getAccessory = $this->equipment->getAccessory($id);

                $totalAccessory = 0;

                if (!$getAccessory->isEmpty()) {
                    $totalAccessory = count($getAccessory);

                    foreach ($getAccessory as $accessorykey => $accessoryvalue) {
                        $accessoryDetail[$accessorykey]['accessoryId'] = $accessoryvalue->id;
                        $accessoryDetail[$accessorykey]['AccessoryPrice'] = $accessoryvalue->price;
                        $accessoryDetail[$accessorykey]['AccessoryName'] = $accessoryvalue->accessories_name;
                        $accessoryDetail[$accessorykey]['AccessorySKUnumber'] = $accessoryvalue->sku_number;
                        $accessoryDetail[$accessorykey]['AccessoryImage'] = $accessoryvalue->image;
                        $accessoryDetail[$accessorykey]['accessories_buy'] = $accessoryvalue->accessories_buy;

                    }
                }


                //                get part tips

                $getTips = $this->equipment->getPartTips($id);

                $totalTips = 0;
                if (!$getTips->isEmpty()) {
                    $totalTips = count($getTips);

                    foreach ($getTips as $tipkey => $tipvalue) {
                        $tipDetail[$tipkey]['tipId'] = $tipvalue->id;
                        $tipDetail[$tipkey]['tipname'] = $tipvalue->tip_name;
                        $tipDetail[$tipkey]['tipNumber'] = $tipvalue->sku_number;
                        $tipDetail[$tipkey]['tipPrice'] = $tipvalue->price;
                        $tipDetail[$tipkey]['tipImage'] = $tipvalue->image;

                    }
                }


                //                get modelmanual doc

                $doctype = 'manual';
                $getManualDocs = $this->equipment->getModelDocs($id, $doctype);

                $totalManualDocs = 0;
                if (!$getManualDocs->isEmpty()) {
                    $totalManualDocs = count($getManualDocs);

                    foreach ($getManualDocs as $mandockey => $mandocvalue) {
                        $getManualDetail[$mandockey]['ManualID'] = $mandocvalue->id;
                        $getManualDetail[$mandockey]['ManualName'] = $mandocvalue->document_name;
                        $getManualDetail[$mandockey]['ManualDocName'] = $mandocvalue->document;
                        $getManualDetail[$mandockey]['manualdocumentname'] = $mandocvalue->document;
                    }
                }
//                print_r($getManualDetail);exit;
                //                get spec doc

                $doctype = 'specification';
                $getSpecificationDocs = $this->equipment->getModelDocs($id, $doctype);

                $totalSpecificationDocs = 0;
                if (!$getSpecificationDocs->isEmpty()) {
                    $totalSpecificationDocs = count($getSpecificationDocs);

                    foreach ($getSpecificationDocs as $specdockey => $specdocvalue) {
                        $getSpecDetail[$specdockey]['SpecID'] = $specdocvalue->id;
                        $getSpecDetail[$specdockey]['SpecName'] = $specdocvalue->document_name;
                        $getSpecDetail[$specdockey]['SpecDocName'] = $specdocvalue->document;
                        $getSpecDetail[$specdockey]['specdocumentName'] = $specdocvalue->document;


                    }
                }
//print_r($getSpecDetail);exit;
                //                get spec doc

                $doctype = 'broucher';
                $getBroucherDocs = $this->equipment->getModelDocs($id, $doctype);

                $totalBroucherDocs = 0;
                if (!$getBroucherDocs->isEmpty()) {
                    $totalBroucherDocs = count($getBroucherDocs);

                    foreach ($getBroucherDocs as $brocuherdockey => $broucherdocvalue) {
                        $getBroucherDetail[$brocuherdockey]['BroucherID'] = $broucherdocvalue->id;
                        $getBroucherDetail[$brocuherdockey]['BroucherName'] = $broucherdocvalue->document_name;
                        $getBroucherDetail[$brocuherdockey]['BroucherDocName'] = $broucherdocvalue->document;
                        $getBroucherDetail[$brocuherdockey]['broucherdocumentname'] = $broucherdocvalue->document;


                    }
                }


            }
        }

        $rules = [
            'model_name' => 'required',
            'capacity' => 'required',
            'channel' => 'required',
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
            return view('model.modelForm')->with('input', $data)->with('modelunits', $modelunits)->with('channels', $channels)->with('operationSelect', $operationSelect)
                ->with('volumeSelect', $volumeSelect)->with('channelNumberSelect', $channelNumberSelect)->with('spareSelect', $spareSelect)->with('totalAccessory', $totalAccessory)->with('accessory', $accessoryDetail)->with('tips', $tipDetail)->with('totalTips', $totalTips)
                ->with('limit', $limitDetail)->with('title', $title)->with('spares', $spareDetail)->with('totalspares', $totalspares)->with('errors', $error)->with('customerType', $customerType)
                ->with('product', $product)->with('manufacturer', $manufacturer_drop)->with('brand', $brand_drop)
                ->with('getManualDetail', $getManualDetail)->with('totalManualDocs', $totalManualDocs)
                ->with('getSpecDetail', $getSpecDetail)->with('totalSpecificationDocs', $totalSpecificationDocs)
                ->with('getBroucherDetail', $getBroucherDetail)->with('totalBroucherDocs', $totalBroucherDocs)
                ->with('getImageDetail', $imageDetail)->with('totalimages', $totalimages)
                ->with('testpoints', $testpoints);

        } else {
            $data = Input::all();
//echo '<pre>';print_r($data);exit;
            $save = array();

            $save['id'] = $id;
            $save['model_name'] = $data['model_name'];
            $save['capacity'] = $data['capacity'];
            $save['channel'] = $data['channel'];
            $save['unit'] = $data['unit'];
            if (isset($data['is_active']) ? $data['is_active'] : '0') {

                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }

            $Saveresult = $this->equipment->saveEquipment($save);

            if (isset($data['orderlimits']) && $data['orderlimits']) {
                if ($id) {
                    $this->equipment->deleteTolerance($id);
                }


                $temp = array();
                foreach ($data['orderlimits'] as $subkey => $subvalue) {

//                assign value to order item

                    $temp['id'] = false;
                    $temp['target_value'] = $subvalue['target_value'];
                    $temp['description'] = $subvalue['description'];
                    $temp['accuracy'] = $subvalue['accuracy'];
                    $temp['precision'] = $subvalue['precision'];
                    $temp['model_id'] = $Saveresult;
                    if (isset($data['is_active']) ? $data['is_active'] : '0') {

                        $temp['is_active'] = 1;
                    } else {
                        $temp['is_active'] = 0;
                    }
                    $temp['is_active'] = 1;

                    $limitsaveI = $this->equipment->saveTolerance($temp);
                }

            }
            return redirect('admin/modellist')->with('message', 'Added Successfully');
        }
    }

    public
    function referredmodelList()
    {
        $data = Input::all();
//       echo'<pre>';print_r($data);die;
        if (!$data) {
            die(json_encode(array("result" => false)));

        } else {
            $getreferred = $this->equipment->getlimits($data['id']);
            $temp = array();
            if ($getreferred) {
                foreach ($getreferred as $key => $value) {

                    $temp[$key]['Id'] = $value->model_id;
                    $temp[$key]['target_value'] = $value->target_value;
                    $temp[$key]['description'] = $value->description;
                    $temp[$key]['accuracy'] = $value->accuracy;
                    $temp[$key]['precision'] = $value->precision;
                }
            }
            die(json_encode(array("result" => true, "referredDetails" => $temp)));
        }
    }

    public function addEquipmentModel(Request $request)
    {
        $data = Input::all();

//        echo '<pre>';
//        print_r($data);
//        die;


        $loginuserId = Sentinel::getUser()->id;

        if (!$data) {
            die(json_encode(array('result' => false, 'message' => 'Values are not get Properly')));
        }

//        setting manufacturer Id

        $input = [

            'manufacturer' => isset($data['manufacturer']) ? $data['manufacturer'] : '',
            'manufacturerhidden' => isset($data['manufacturerhidden']) ? $data['manufacturerhidden'] : '',
            'volume_from' => isset($data['volume_from']) ? $data['volume_from'] : '',
            'volume_to' => isset($data['volume_to']) ? $data['volume_to'] : '',
            'modelimage' => isset($data['modelimage']) ? $data['modelimage'] : '',
            'modeldescription' => isset($data['modeldescription']) ? $data['modeldescription'] : '',
            'Id' => isset($data['Id']) ? $data['Id'] : '',
            'producttype' => isset($data['producttype']) ? $data['producttype'] : '',
            'model_name' => isset($data['model_name']) ? $data['model_name'] : '',
            'Brand' => isset($data['Brand']) ? $data['Brand'] : '',
            'channels' => isset($data['channels']) ? $data['channels'] : '',
            'channelNo' => isset($data['channelNo']) ? $data['channelNo'] : '',
            'unit' => isset($data['unit']) ? $data['unit'] : '',
            'volume' => isset($data['volume']) ? $data['volume'] : '',
            'modelPrice' => isset($data['modelPrice']) ? $data['modelPrice'] : '',
            'modelSku' => isset($data['modelSku']) ? $data['modelSku'] : '',
            'sparedetail' => isset($data['sparedetail']) ? $data['sparedetail'] : '',
            'toleranceArray' => isset($data['toleranceArray']) ? $data['toleranceArray'] : '',
            'accessorydetail' => isset($data['accessorydetail']) ? $data['accessorydetail'] : '',
            'tipdetail' => isset($data['tipdetail']) ? $data['tipdetail'] : '',
            'manualdocdetail' => isset($data['manualdocdetail']) ? $data['manualdocdetail'] : '',
            'specdocdetail' => isset($data['specdocdetail']) ? $data['specdocdetail'] : '',
            'broucherdocdetail' => isset($data['broucherdocdetail']) ? $data['broucherdocdetail'] : '',
            'imagedocdetail' => isset($data['imagedocdetail']) ? $data['imagedocdetail'] : '',
            'imagehidden' => isset($data['imagehidden']) ? $data['imagehidden'] : '',
            'modelBuy' => isset($data['modelBuy']) ? $data['modelBuy'] : '',
            'modelService' => isset($data['modelService']) ? $data['modelService'] : '',
            'operation' => isset($data['operation']) ? $data['operation'] : '',
            'is_active' => isset($data['is_active']) ? $data['is_active'] : '0',

        ];
        //echo '<pre>';print_r($input);exit;
//
//        $manufacturer = $input['manufacturer'];
//        if ($manufacturer) {
//            $param = array();
//            foreach ($manufacturer as $value) {
//                $param = $value;
//                $getManaging = $this->equipment->getmanufacturerbySerialNo($param);
//                $manufacturerIds[] = $getManaging->manufacturer_id;
//            }
//            $manufacturerId = implode(',', $manufacturerIds);
//        } else {
//            $manufacturerId = '';
//        }
        //        setting volume value

        if ($input['volume_from'] && $input['volume_to']) {
            $volumeValue = $input['volume_from'] . '-' . $input['volume_to'];
        } else if ($input['volume_from']) {
            $volumeValue = $input['volume_from'];
        } else {
            $volumeValue = '';
        }
//

        if ($input['Id']) {
            $savemodel['id'] = $input['Id'];
//            $manufacturer = $input['manufacturerhidden'];
        } else {
            $savemodel['id'] = false;
//            $manufacturer = $input['manufacturer'];
        }


//        if ($manufacturer) {
        $param = array();
//            foreach ($manufacturer as $value) {
//                $param = $value;
        // $getManaging = $this->equipment->getmanufacturerbySerialNo($input['manufacturer']);
        $getManaging = DB::table('tbl_brand')->join('tbl_manufacturer', 'tbl_manufacturer.manufacturer_id', 'tbl_brand.manufacturer_id')->select('tbl_manufacturer.manufacturer_id', 'tbl_manufacturer.manufacturer_name')->where('brand_id', $input['Brand'])->first();
        $manufacturerIds = (isset($getManaging->manufacturer_id) && $getManaging->manufacturer_id) ? $getManaging->manufacturer_id : null;
        $savemodel['product_id'] = $input['producttype'];
        $savemodel['manufacturer_ids'] = $input['manufacturer'];
        if (!$input['Id']) {
            $savemodel['model_name'] = $input['model_name'] . '-' . $getManaging->manufacturer_name;

        } else {
            $savemodel['model_name'] = $input['model_name'];
        }
        if (isset($input['is_active']) ? $input['is_active'] : '0') {

            $savemodel['is_active'] = 1;
        } else {
            $savemodel['is_active'] = 0;
        }
        $savemodel['model_price'] = $input['modelPrice'];
        $savemodel['model_description'] = $input['modeldescription'];
        $savemodel['brand_id'] = $input['Brand'];
        $savemodel['brand_operation'] = $input['operation'];


        $seoslug = str_slug($input['model_name'], '-');


        $savemodel['model_slug'] = $seoslug;
        $savemodel['channel'] = $input['channels'];
        $savemodel['channel_number'] = $input['channelNo'];
        $savemodel['volume'] = $input['volume'];
        $savemodel['volume_value'] = $volumeValue;
        $savemodel['unit'] = $input['unit'];
        $savemodel['model_buy'] = $input['modelBuy'];
        $savemodel['model_service'] = $input['modelService'];
        $savemodel['sku_number'] = $input['modelSku'];
        $savemodel['created_by'] = $loginuserId;


        $saveModel = $this->equipment->saveEquipment($savemodel);


        if ($saveModel) {

            $deletemodelImages = $this->equipment->deleteModelImages($saveModel);
            if ($input['imagedocdetail']) {
                foreach ($input['imagedocdetail'] as $imagekey => $imageval) {
                    $imagesave['id'] = false;
                    $imagesave['model_id'] = $saveModel;
                    $imagesave['image'] = $imageval['imagename'];
                    $imagesave['created_by'] = $loginuserId;
                    $saveImages = $this->equipment->saveModelImages($imagesave);
                }
            }


//            for saving tolerance

            $deleteSpares = $this->equipment->deleteTolerance($saveModel);

            if ($input['toleranceArray']) {
                foreach ($input['toleranceArray'] as $tolkey => $tolval) {
                    $tolsave['id'] = false;
                    $tolsave['model_id'] = $saveModel;
                    $tolsave['target_value'] = $tolval['target_value'];
                    $tolsave['description'] = $tolval['description'];
                    $tolsave['accuracy'] = $tolval['accuracy'];
                    $tolsave['precision'] = $tolval['precision'];
                    $tolsave['accuracy_ul'] = $tolval['accuracyul'];
                    $tolsave['precesion_ul'] = $tolval['precisionul'];
                    $tolsave['is_active'] = 1;
                    $tolsave['created_by'] = $loginuserId;
                    $saveSpares = $this->equipment->saveTolerance($tolsave);
                }
            }


//            for saving spares



            if ($input['sparedetail']) {
                foreach ($input['sparedetail'] as $sparekey => $spareval) {
                    $sparessave['id'] = $spareval['spareId'];
                    $sparessave['model_id'] = $saveModel;
                    $sparessave['mode_id'] = $spareval['sparemode'];
                    $sparessave['sku_number'] = $spareval['number'];

                    $sparessave['price'] = $spareval['Price'];
                    $sparessave['service_price'] = $spareval['servicePrice'];
                    $sparessave['image'] = $spareval['sparesdocimageName'];
                    $sparessave['part_name'] = $spareval['partname'];
                    $sparessave['part_buy'] = $spareval['buy'];
                    $sparessave['part_sell'] = $spareval['sell'];
                    $sparessave['created_by'] = $loginuserId;

                    $saveSpares = $this->equipment->saveSpares($sparessave);
                }
            }
//            for saving accessories

//            $deleteAccessories = $this->equipment->deleteAccessories($saveModel);

            if ($input['accessorydetail']) {
                foreach ($input['accessorydetail'] as $accesskey => $accessval) {
                    $accesssave['id'] = $accessval['accessoryId'];
                    $accesssave['accessories_name'] = $accessval['AccessoryName'];
                    $accesssave['sku_number'] = $accessval['AccessorySKUnumber'];
                    $accesssave['model_id'] = $saveModel;
                    $accesssave['price'] = $accessval['AccessoryPrice'];
                    $accesssave['image'] = $accessval['AccessoryImage'];
                    $accesssave['accessories_buy'] = $accessval['buy'];
                    $accesssave['created_by'] = $loginuserId;
                    $saveAccess = $this->equipment->saveAccessories($accesssave);
                }
            }


//            for svaing part tips


//            $deleteEquipTips = $this->equipment->deleteEquipTips($saveModel);


            if ($input['tipdetail']) {
                foreach ($input['tipdetail'] as $tipkey => $tipval) {
                    $tipsave['id'] = $tipval['tipId'];
                    $tipsave['tip_name'] = $tipval['tipname'];
                    $tipsave['sku_number'] = $tipval['tipNumber'];
                    $tipsave['model_id'] = $saveModel;
                    $tipsave['price'] = $tipval['tipPrice'];
                    $tipsave['image'] = $tipval['tipImage'];
                    $tipsave['created_by'] = $loginuserId;
                    $saveTip = $this->equipment->saveEquipTips($tipsave);
                }
            }

//            for saving manual Documents


            $doctype = 'manual';
            $deleteManualDocs = $this->equipment->deleteModelDocs($saveModel, $doctype);
//            echo '<prE>';print_r($deleteManualDocs);die;
            if ($input['manualdocdetail']) {
                foreach ($input['manualdocdetail'] as $manualdockey => $manualdocval) {
                    $manualdocsave['id'] = false;
                    $manualdocsave['model_id'] = $saveModel;
                    $manualdocsave['document_name'] = $manualdocval['ManualName'];
                    $manualdocsave['document'] = $manualdocval['manualdocumentname'];
                    $manualdocsave['document_type'] = 'manual';
                    $manualdocsave['created_by'] = $loginuserId;
                    $saveManualDoc = $this->equipment->saveDocs($manualdocsave);
                }
            }

            //            for saving specification Documents

            $doctype = 'specification';
            $deleteSpecificationDocs = $this->equipment->deleteModelDocs($saveModel, $doctype);

            if ($input['specdocdetail']) {
                foreach ($input['specdocdetail'] as $specdockey => $specdocval) {
                    $specdocsave['id'] = false;
                    $specdocsave['model_id'] = $saveModel;
                    $specdocsave['document_name'] = $specdocval['SpecName'];
                    $specdocsave['document'] = $specdocval['specdocumentName'];
                    $specdocsave['document_type'] = 'specification';
                    $specdocsave['created_by'] = $loginuserId;
                    $saveSpecDoc = $this->equipment->saveDocs($specdocsave);
                }
            }

            //            for saving broucher Documents

            $doctype = 'broucher';
            $deleteBroucherDocs = $this->equipment->deleteModelDocs($saveModel, $doctype);


            if ($input['broucherdocdetail']) {
                foreach ($input['broucherdocdetail'] as $broucherdockey => $broucherdocval) {
                    $broucherdocsave['id'] = false;
                    $broucherdocsave['model_id'] = $saveModel;
                    $broucherdocsave['document_name'] = $broucherdocval['BroucherName'];
                    $broucherdocsave['document'] = $broucherdocval['broucherdocumentname'];
                    $broucherdocsave['document_type'] = 'broucher';
                    $broucherdocsave['created_by'] = $loginuserId;
                    $saveBroucherDoc = $this->equipment->saveDocs($broucherdocsave);
                }
            }

        }
//            }

        die(json_encode(array('result' => true, 'message' => 'Successfully Added.')));


    }

    public function modelphotoUpdate(Request $request)
    {

        $input = Input::all();
        $Id = $input['Id'];
        $getModel = $this->equipment->getmodel($Id);

        $photo['id'] = $getModel->id;

        $photo['model_image'] = $input['photo'];
        $saveModel = $this->equipment->saveEquipment($photo);

    }

    public function modeldocupload(Request $request)
    {

        $data = Input::all();
        if (!$data) {
            die(json_encode(array('result' => true, 'message' => 'Successfully Added.')));
        }

//        echo '<prE>';print_r($request->file('manualfile'));die;

        //        get manual file

        if ($manualFile = $request->file('manualfile')) {

            if ($manualFile) {
                $fileType = $manualFile->getClientOriginalExtension();
                $FileMime = $manualFile->getMimeType();
                $filesize = filesize($manualFile);
                $manualFileName = "manualfile" . '-' . rand(0000, 9999) . '.' . $fileType;

                $request->file('manualfile')->move(
                    base_path() . '/public/equipment_model/documents/manual', $manualFileName
                );

            }
        } else {
            $manualFileName = '';
        }

        die(json_encode(array('result' => true, 'manualfileDocName' => $manualFileName)));


    }


    public function specdocupload(Request $request)
    {

        $data = Input::all();
        if (!$data) {
            die(json_encode(array('result' => false, 'message' => 'Datas are not found')));
        }


        //        get manual file

        if ($specificationFile = $request->file('specificationfile')) {
//            print_r('hi');die;
            if ($specificationFile) {
                $fileType = $specificationFile->getClientOriginalExtension();
                $FileMime = $specificationFile->getMimeType();
                $filesize = filesize($specificationFile);
                $specificationfileName = "specificationfile" . '-' . rand(0000, 9999) . '.' . $fileType;

                $request->file('specificationfile')->move(
                    base_path() . '/public/equipment_model/documents/specification', $specificationfileName
                );

            }
        } else {
            $specificationfileName = '';
        }
//        print_r($specificationfileName);die;


        die(json_encode(array('result' => true, 'specfileDocName' => $specificationfileName)));


    }

    public function broucherdocupload(Request $request)
    {

        $data = Input::all();
        if (!$data) {
            die(json_encode(array('result' => false, 'message' => 'Datas are not found')));
        }


//        get broucher file


        if ($broucherFile = $request->file('broucherfile')) {
            if ($broucherFile) {
                $fileType = $broucherFile->getClientOriginalExtension();
                $FileMime = $broucherFile->getMimeType();
                $filesize = filesize($broucherFile);
                $broucherfileName = "broucherfile" . '-' . rand(0000, 9999) . '.' . $fileType;
//                echo '<pre>';print_r($broucherfileName);die;
                $request->file('broucherfile')->move(
                    base_path() . '/public/equipment_model/documents/broucher/', $broucherfileName

                );

            }
        } else {
            $broucherfileName = '';
        }


        die(json_encode(array('result' => true, 'broucherfileDocName' => $broucherfileName)));


    }

    public function modelimageupload(Request $request)
    {

        $input = Input::all();
        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Datas are not found')));
        }


//        get broucher file
        if ($input['modelimagefile']) {
            $imagesize = getimagesize($input['modelimagefile']);
            $width = $imagesize[0];
            $height = $imagesize[1];


            $image = $request->file('modelimagefile')->getClientOriginalExtension();
            $imageName = 'equipModel' . '-' . uniqid() . '.' . $image;
            $imagePath = $request->file('modelimagefile')->move(base_path() . '/public/equipment_model/images/original', $imageName);
            $img = Image::make($imagePath->getRealPath());
            $largeWidth = $width;
            $mediumWidth = $width;
            $smallWidth = $width;
            $extralargeWidth = $width;
            $iconWidth = $width;
            $thumbnailWidth = $width;
            if ($width > 425) {
                $largeWidth = 425;
            }
            Image::make($imagePath)->resize($largeWidth, null, function ($constraint) use ($imageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/images/large/' . $imageName);

            if ($width > 375) {
                $mediumWidth = 425;
            }
            Image::make($imagePath)->resize($mediumWidth, null, function ($constraint) use ($imageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/images/medium/' . $imageName);
            if ($width > 320) {

                $smallWidth = 320;
            }
            Image::make($imagePath)->resize($smallWidth, null, function ($constraint) use ($imageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/images/small/' . $imageName);
            if ($width > 200) {

                $thumbnailWidth = 200;
            }
            Image::make($imagePath)->resize($thumbnailWidth, null, function ($constraint) use ($imageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/images/thumbnail/' . $imageName);
            if ($width > 768) {

                $extralargeWidth = 768;
            }
            Image::make($imagePath)->resize($extralargeWidth, null, function ($constraint) use ($imageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/images/extraLarge/' . $imageName);
            if ($width > 64) {
                $iconWidth = 64;
            }

            Image::make($imagePath)->resize($iconWidth, null, function ($constraint) use ($imageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/images/icon/' . $imageName);


        } else {
            if (!empty($input['imagehidden'])) {
                $imageName = $input['imagehidden'];

            } else {
                $imageName = '';
            }
        }
        $imgSrc = "<img src=" . asset('equipment_model/images/icon/' . $imageName) . ">";
        die(json_encode(array('result' => true, 'imagefileDocName' => $imageName, 'imgSrc' => $imgSrc)));
    }


    public function UnlinkManualDoc(Request $request)
    {

        $input = Input::all();
        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'datas are not found')));
        }
        $deleteTips = $this->equipment->deleteModelDocument($input['Id']);
//        $deleteDocument = $input['deleteDocument'];
//        $path = base_path() . '/public/equipment_model/documents/manual/';
////        echo '<pre>';print_r($path);die;
//        $value = $path . $deleteDocument;
////        File::delete($path . $deleteDocument);
//        if (file_exists($value)) {
//            unlink($value);
//        }

        die(json_encode(array('result' => true, 'message' => 'successfully deleted')));

    }

    public function UnlinkSpecDoc(Request $request)
    {

        $input = Input::all();
        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'datas are not found')));
        }
        $deleteTips = $this->equipment->deleteModelDocument($input['Id']);
//        $deleteDocument = $input['deleteDocument'];
//        $path = base_path() . '/public/equipment_model/documents/specification/';
//        $value = $path . $deleteDocument;
//        if (file_exists($value)) {
//            unlink($value);
//        }

        die(json_encode(array('result' => true, 'message' => 'successfully deleted')));

    }

    public function UnlinkBroucherDoc(Request $request)
    {

        $input = Input::all();
        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'datas are not found')));
        }
        $deleteTips = $this->equipment->deleteModelDocument($input['Id']);
//        $deleteDocument = $input['deleteDocument'];
//        $path = base_path() . '/public/equipment_model/documents/broucher/';
//        $value = $path . $deleteDocument;
//
//        if (file_exists($value)) {
//            unlink($value);
//        }

        die(json_encode(array('result' => true, 'message' => 'successfully deleted')));

    }

    public function unlinkimagedoc()
    {

        $data = Input::all();
        if (!$data) {
            die(json_encode(array('result' => false, 'message' => 'datas are not found')));
        }

        $deleteDocument = $data['deleteDocument'];
        $path1 = base_path() . '/public/equipment_model/images/extraLarge/';
        $value1 = $path1 . $deleteDocument;

        if (file_exists($value1)) {
            unlink($value1);
        }


        $path2 = base_path() . '/public/equipment_model/images/icon/';
        $value2 = $path2 . $deleteDocument;

        if (file_exists($value2)) {
            unlink($value2);
        }

        $path3 = base_path() . '/public/equipment_model/images/large/';
        $value3 = $path3 . $deleteDocument;

        if (file_exists($value3)) {
            unlink($value3);
        }

        $path4 = base_path() . '/public/equipment_model/images/medium/';
        $value4 = $path4 . $deleteDocument;

        if (file_exists($value4)) {
            unlink($value4);
        }

        $path5 = base_path() . '/public/equipment_model/images/original/';
        $value5 = $path5 . $deleteDocument;

        if (file_exists($value5)) {
            unlink($value5);
        }

        $path6 = base_path() . '/public/equipment_model/images/small/';
        $value6 = $path6 . $deleteDocument;

        if (file_exists($value6)) {
            unlink($value6);
        }

        $path7 = base_path() . '/public/equipment_model/images/thumbnail/';
        $value7 = $path7 . $deleteDocument;

        if (file_exists($value7)) {
            unlink($value7);
        }

        die(json_encode(array('result' => true, 'message' => 'successfully deleted')));

    }

    public function modelsparesimageUpload(Request $request)
    {

        $data = Input::all();

        if (!$data) {
            die(json_encode(array('result' => false, 'message' => 'Values are not get properly.')));
        }


        //        get manual file

        if ($manualFile = $request->file('sparesImage')) {

            $imagesize = getimagesize($data['sparesImage']);
            $width = $imagesize[0];
            $height = $imagesize[1];


            $image = $request->file('sparesImage')->getClientOriginalExtension();
            $sparesimageName = 'modelspares' . '-' . uniqid() . '.' . $image;
            $imagePath = $request->file('sparesImage')->move(base_path() . '/public/equipment_model/spares/original', $sparesimageName);
            $img = Image::make($imagePath->getRealPath());
            $largeWidth = $width;
            $mediumWidth = $width;
            $smallWidth = $width;
            $extralargeWidth = $width;
            $iconWidth = $width;
            $thumbnailWidth = $width;
            if ($width > 425) {
                $largeWidth = 425;
            }
            Image::make($imagePath)->resize($largeWidth, null, function ($constraint) use ($sparesimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/spares/large/' . $sparesimageName);

            if ($width > 375) {
                $mediumWidth = 425;
            }
            Image::make($imagePath)->resize($mediumWidth, null, function ($constraint) use ($sparesimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/spares/medium/' . $sparesimageName);
            if ($width > 320) {

                $smallWidth = 320;
            }
            Image::make($imagePath)->resize($smallWidth, null, function ($constraint) use ($sparesimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/spares/small/' . $sparesimageName);
            if ($width > 200) {

                $thumbnailWidth = 200;
            }
            Image::make($imagePath)->resize($thumbnailWidth, null, function ($constraint) use ($sparesimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/spares/thumbnail/' . $sparesimageName);
            if ($width > 768) {

                $extralargeWidth = 768;
            }
            Image::make($imagePath)->resize($extralargeWidth, null, function ($constraint) use ($sparesimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/spares/extraLarge/' . $sparesimageName);
            if ($width > 64) {
                $iconWidth = 64;
            }

            Image::make($imagePath)->resize($iconWidth, null, function ($constraint) use ($sparesimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/spares/icon/' . $sparesimageName);


        } else {
            $sparesimageName = '';
        }

        die(json_encode(array('result' => true, 'spareimageDocName' => $sparesimageName)));


    }

    public function modelaccessoryimageUpload(Request $request)
    {

        $data = Input::all();

        if (!$data) {
            die(json_encode(array('result' => false, 'message' => 'Values are not get properly.')));
        }

//        echo '<pre>';print_r($data['accessoryImage']);die;

        //        get manual file

        if ($manualFile = $request->file('accessoryImage')) {

            $imagesize = getimagesize($data['accessoryImage']);
            $width = $imagesize[0];
            $height = $imagesize[1];


            $image = $request->file('accessoryImage')->getClientOriginalExtension();
            $accessoryimageName = 'modelaccessory' . '-' . uniqid() . '.' . $image;
            $imagePath = $request->file('accessoryImage')->move(base_path() . '/public/equipment_model/accessory/original', $accessoryimageName);
            $img = Image::make($imagePath->getRealPath());
            $largeWidth = $width;
            $mediumWidth = $width;
            $smallWidth = $width;
            $extralargeWidth = $width;
            $iconWidth = $width;
            $thumbnailWidth = $width;
            if ($width > 425) {
                $largeWidth = 425;
            }
            Image::make($imagePath)->resize($largeWidth, null, function ($constraint) use ($accessoryimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/accessory/large/' . $accessoryimageName);

            if ($width > 375) {
                $mediumWidth = 425;
            }
            Image::make($imagePath)->resize($mediumWidth, null, function ($constraint) use ($accessoryimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/accessory/medium/' . $accessoryimageName);
            if ($width > 320) {

                $smallWidth = 320;
            }
            Image::make($imagePath)->resize($smallWidth, null, function ($constraint) use ($accessoryimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/accessory/small/' . $accessoryimageName);
            if ($width > 200) {

                $thumbnailWidth = 200;
            }
            Image::make($imagePath)->resize($thumbnailWidth, null, function ($constraint) use ($accessoryimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/accessory/thumbnail/' . $accessoryimageName);
            if ($width > 768) {

                $extralargeWidth = 768;
            }
            Image::make($imagePath)->resize($extralargeWidth, null, function ($constraint) use ($accessoryimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/accessory/extraLarge/' . $accessoryimageName);
            if ($width > 64) {
                $iconWidth = 64;
            }

            Image::make($imagePath)->resize($iconWidth, null, function ($constraint) use ($accessoryimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/accessory/icon/' . $accessoryimageName);


        } else {
            $accessoryimageName = '';
        }

        die(json_encode(array('result' => true, 'accessoryimageDocName' => $accessoryimageName)));


    }

    public function modeltipimageUpload(Request $request)
    {

        $data = Input::all();

        if (!$data) {
            die(json_encode(array('result' => false, 'message' => 'Values are not get properly.')));
        }

        //        get manual file

        if ($manualFile = $request->file('tipImage')) {

            $imagesize = getimagesize($data['tipImage']);
            $width = $imagesize[0];
            $height = $imagesize[1];


            $image = $request->file('tipImage')->getClientOriginalExtension();
            $tipimageName = 'modeltip' . '-' . uniqid() . '.' . $image;
            $imagePath = $request->file('tipImage')->move(base_path() . '/public/equipment_model/tips/original', $tipimageName);
            $img = Image::make($imagePath->getRealPath());
            $largeWidth = $width;
            $mediumWidth = $width;
            $smallWidth = $width;
            $extralargeWidth = $width;
            $iconWidth = $width;
            $thumbnailWidth = $width;
            if ($width > 425) {
                $largeWidth = 425;
            }
            Image::make($imagePath)->resize($largeWidth, null, function ($constraint) use ($tipimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/tips/large/' . $tipimageName);

            if ($width > 375) {
                $mediumWidth = 425;
            }
            Image::make($imagePath)->resize($mediumWidth, null, function ($constraint) use ($tipimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/tips/medium/' . $tipimageName);
            if ($width > 320) {

                $smallWidth = 320;
            }
            Image::make($imagePath)->resize($smallWidth, null, function ($constraint) use ($tipimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/tips/small/' . $tipimageName);
            if ($width > 200) {

                $thumbnailWidth = 200;
            }
            Image::make($imagePath)->resize($thumbnailWidth, null, function ($constraint) use ($tipimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/tips/thumbnail/' . $tipimageName);
            if ($width > 768) {

                $extralargeWidth = 768;
            }
            Image::make($imagePath)->resize($extralargeWidth, null, function ($constraint) use ($tipimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/tips/extraLarge/' . $tipimageName);
            if ($width > 64) {
                $iconWidth = 64;
            }

            Image::make($imagePath)->resize($iconWidth, null, function ($constraint) use ($tipimageName) {
                $constraint->aspectRatio();
            })->save(base_path() . '/public/equipment_model/tips/icon/' . $tipimageName);


        } else {
            $tipimageName = '';
        }

        die(json_encode(array('result' => true, 'tipimageDocName' => $tipimageName)));


    }

    public function deletedModelParts(Request $request)
    {

        $input = Input::all();

//        echo '<pre>';print_r($input);die;

        if (!$input) {
            die(json_encode(array('result' => false)));
        }

//        $getSpares = $this->workorderProcess->checkSparesProcess('~' . $input['Id'] . '~');
//
//        if (!$getSpares->isEmpty()) {
//            die(json_encode(array('result' => false)));
//        }

        $deleteSpares = $this->equipment->deleteSparesbyId($input['Id']);

        die(json_encode(array('result' => true)));

    }

    public function deletedModelAccessory(Request $request)
    {

        $input = Input::all();


        if (!$input) {
            die(json_encode(array('result' => false)));
        }

        $deleteAccessory = $this->equipment->deleteAccessoriesbyId($input['Id']);

        die(json_encode(array('result' => true)));

    }

    public function deletedModelTips(Request $request)
    {

        $input = Input::all();

//        echo '<pre>';print_r($input);die;


        if (!$input) {
            die(json_encode(array('result' => false)));
        }

        $deleteTips = $this->equipment->deleteEquipTipsbyId($input['Id']);

        die(json_encode(array('result' => true)));

    }

    public
    function delete($id)
    {

        $getdetail = $this->equipment->getmodel($id);


        if ($getdetail) {

            $getModel = DB::table('tbl_equipment')->where('equipment_model_id', '=', $id)->select('*')->first();
//            print_r($getModel);die;
            if ($getModel) {
                $message = Session::flash('error', "You can't able to delete this model");
                return redirect('admin/modellist')->with(['data', $message], ['message', $message]);
            }

            $message = Session::flash('message', 'Deleted Successfully');
            $deletespares = DB::table('tbl_equipment_model_spares')->where('model_id', $id)->delete();
            $deleteimages = DB::table('tbl_equipment_model_images')->where('model_id', $id)->delete();
            $deleteaccessories = DB::table('tbl_equipment_model_accessories')->where('model_id', $id)->delete();
            $deletetips = DB::table('tbl_equipment_model_tips')->where('model_id', $id)->delete();
            $deletetolerence = DB::table('tbl_limit_tolerance')->where('model_id', $id)->delete();
            $deletedocuments = DB::table('tbl_model_documents')->where('model_id', $id)->delete();
            $member = $this->equipment->deleteModel($id);

            return redirect('admin/modellist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully');
            return redirect('admin/modellist')->with('data', $error);
        }
    }

    public function getBrands(Request $request)
    {

        $input = Input::all();
        if (isset($input['manufacturer'])) {

            $getBrands = DB::table('tbl_brand')->where('manufacturer_id', $input['manufacturer'])->select('*')->get();
            // echo'<pre>';print_r($input['manufacturer']);'</pre>';die;
            $element = '<option value="">Select Brand</option>';
            foreach ($getBrands as $val) {
                $element .= '<option value="' . $val->brand_id . '">' . $val->brand_name . '</option>';
            }

            $data = $element;

            die(json_encode(array('result' => true, 'data' => $data)));

        } else {
            die(json_encode(array('result' => false)));
        }


    }

    function saveajaxSpare()
    {

        $post = Input::all();
        if ($post) {
            $getSpare = $this->equipment->getSpareChange($post);
            $sparessave['id'] = $post['id'];
            $sparessave['mode_id'] = $post['sparemode'];
            $sparessave['sku_number'] = $post['number'];
            $sparessave['price'] = $post['Price'];
            $sparessave['image'] = $post['image'];
            $sparessave['part_name'] = $post['partname'];

            $id = $this->equipment->saveSpares($sparessave);
            if (empty($getSpare)) {
                die(json_encode(array('result' => true, 'message' => 'Spare values are updated', 'updated' => true)));
            } else {
                die(json_encode(array('result' => true, 'message' => 'Spare values are updated', 'updated' => false)));
            }


        }
    }

    function saveajaxTips()
    {

        $post = Input::all();
        if ($post) {
            $getTips = $this->equipment->getTipsChange($post);
            $tipsave['id'] = $post['id'];
            $tipsave['tip_name'] = $post['tip_name'];
            $tipsave['sku_number'] = $post['tip_sku_number'];
            $tipsave['price'] = $post['tip_price'];
            $tipsave['image'] = $post['tipImage'];
            $id = $this->equipment->saveEquipTips($tipsave);
            if (empty($getTips)) {
                die(json_encode(array('result' => true, 'message' => 'Tips values are updated', 'updated' => true)));
            } else {
                die(json_encode(array('result' => true, 'message' => 'Tips values are updated', 'updated' => false)));
            }


        }
    }

    function saveajaxAccessory()
    {

        $post = Input::all();
        if ($post) {
            $getTips = $this->equipment->getAccessoryChange($post);
            $tipsave['id'] = $post['id'];
            $tipsave['accessories_name'] = $post['AccessoryName'];
            $tipsave['sku_number'] = $post['AccessorySKUnumber'];
            $tipsave['price'] = $post['AccessoryPrice'];
            $tipsave['image'] = $post['accessoryImage'];
            $id = $this->equipment->saveAccessories($tipsave);
            if (empty($getTips)) {
                die(json_encode(array('result' => true, 'message' => 'Tips values are updated', 'updated' => true)));
            } else {
                die(json_encode(array('result' => true, 'message' => 'Tips values are updated', 'updated' => false)));
            }


        }
    }

    function saveajaxManuals()
    {

        $post = Input::all();
        if ($post) {
            $getManual = $this->equipment->getManualChange($post);
            $manualdocsave['id'] = $post['id'];
            $manualdocsave['document_name'] = $post['ManualName'];
            $manualdocsave['document'] = $post['manualImage'];
            $id = $this->equipment->saveDocs($manualdocsave);
            if (empty($getManual)) {
                die(json_encode(array('result' => true, 'message' => 'Manuals values are updated', 'updated' => true)));
            } else {
                die(json_encode(array('result' => true, 'message' => 'Manuals values are updated', 'updated' => false)));
            }


        }
    }

    function saveajaxBroucher()
    {

        $post = Input::all();
        if ($post) {
            $getSpecification = $this->equipment->getManualChange($post);
            $manualdocsave['id'] = $post['id'];
            $manualdocsave['document_name'] = $post['BroucherName'];
            $manualdocsave['document'] = $post['broucherImage'];
            $id = $this->equipment->saveDocs($manualdocsave);
            if (empty($getSpecification)) {
                die(json_encode(array('result' => true, 'message' => 'Broucher values are updated', 'updated' => true)));
            } else {
                die(json_encode(array('result' => true, 'message' => 'Broucher values are updated', 'updated' => false)));
            }


        }
    }

    function saveajaxSpecification()
    {

        $post = Input::all();
        if ($post) {
            $getSpecification = $this->equipment->getManualChange($post);
            $manualdocsave['id'] = $post['id'];
            $manualdocsave['document_name'] = $post['SpecName'];
            $manualdocsave['document'] = $post['specificationImage'];
            $id = $this->equipment->saveDocs($manualdocsave);
            if (empty($getSpecification)) {
                die(json_encode(array('result' => true, 'message' => 'Specification values are updated', 'updated' => true)));
            } else {
                die(json_encode(array('result' => true, 'message' => 'Specification values are updated', 'updated' => false)));
            }


        }
    }

    //Get Instrument Model Upload Page
    public function instrumentModelUploadPage()
    {
        $input = Input::all();
        $title = 'Upload Instrument Model';
        return view('model.uploadPage')->with('title', $title);
    }

    //Import File
    public function fileImport(Request $request)
    {
        $title = 'Upload Instrument Model';
        $path = Input::file('csv_file');
        $fileformat = $path->getClientOriginalExtension();
        $realPath = $path->getRealPath();
        $data = \Excel::load($realPath)->get(); //echo '<pre>';print_r($data);exit;
        if ($fileformat == 'xlsx') {
            if ($data->count()) {
                foreach ($data as $key => $value)
                {
//                    foreach ($val as $value)
//                    {
                    if($value->model_name) {
                        $arr[] = [
                            'model_name' => $value->model_name,
                            'manufacturer' => $value->manufacturer,
                            'sku_no' => $value->sku_no,
                            'brand' => $value->brand,
                            'operation' => $value->operation,
                            'channel_type' => $value->channel_type,
                            'channel_number' => $value->channel_number,
                            'volume_type' => $value->volume_type,
                            'volume_from' => $value->volume_from,
                            'volume_to' => $value->volume_to,
                            'for_sale' => $value->for_sale,
                            'price' => $value->price,
                        ];
//                    }
                    }
                }
//                echo '<pre>';print_r($arr);exit;
                if (count($arr)) {
                    $insData = array();
                    $insFailData = array();
                    $i = 0;
                    foreach ($arr as $key => $row) {
                        if ($row['manufacturer']) {
                            $manufacturer = DB::table('tbl_manufacturer')->whereRaw('REPLACE(`manufacturer_name`," ","") = ?',str_replace(' ','',$row['manufacturer']))->select('manufacturer_id')->first();
                        }
                        if ($row['brand']) {
                            $brand = DB::table('tbl_brand')->whereRaw('REPLACE(`brand_name`," ","") = ?',str_replace(' ','',$row['brand']))->select('brand_id')->first();
                        }
                        if ($row['operation']) {
                            $operation = DB::table('tbl_operations')->whereRaw('REPLACE(`operation_name`," ","") = ?',str_replace(' ','',$row['operation']))->select('id')->first();
                        }
                        if ($row['channel_type']) {
                            $channel_type = DB::table('tbl_channels')->whereRaw('REPLACE(`channel_name`," ","") = ?',str_replace(' ','',$row['channel_type']))->select('id')->first();
                        }
                        if ($row['channel_number']) {
                            $channel_number = DB::table('tbl_channel_numbers')->whereRaw('REPLACE(`channel_number`," ","") = ?',str_replace(' ','',$row['channel_number']))->select('id')->first();
                        }
                        if ($row['volume_type']) {
                            $volume_type = DB::table('tbl_volume')->whereRaw('REPLACE(`volume_name`," ","") = ?',str_replace(' ','',$row['volume_type']))->select('id')->first();
                        }
                        if ( $manufacturer && $brand && $operation && $channel_type && $volume_type && $channel_number) {
                            if($row['volume_to'])
                            {
                                $modelDescriptionValue = $row['brand'] ."".$row['model_name']. "  (" . $row['volume_from']."-".$row['volume_to'] . ") μl "  . $row['volume_type'] . " " .$row['operation']. " " . " " . $row['channel_type']  . " " . "Pipette";
                            }
                            else
                            {
                                $modelDescriptionValue = $row['brand'] ."".$row['model_name']. "  (" . $row['volume_from'] .") μl "  . $row['volume_type'] . " " .$row['operation']. " " . " " . $row['channel_type']  . " " . "Pipette";
                            }

                            $modelDescription = str_replace(' ','',$modelDescriptionValue);
                            //Check model exist

                            $existModel = DB::table('tbl_equipment_model')->whereRaw('REPLACE(`model_description`," ","") = ?',$modelDescription)->select('id')->first();

                            if(!$existModel)
                            {
                                $existSku = array();
                                if(isset($row['sku_no'])&&$row['sku_no'])
                                {
                                   // $existSku = DB::table('tbl_equipment_model')->where('sku_number',$row['sku_no'])->select('id')->first();
                                }
                                if(!$existSku)
                                {
                                    $insData[$key]['product_id'] = 1 ;
                                    $insData[$key]['sku_no'] = $row['sku_no'];
                                    $insData[$key]['manufacturer_ids'] = $manufacturer->manufacturer_id;
                                    $insData[$key]['brand_id'] = $brand->brand_id;
                                    $insData[$key]['brand_operation'] = $operation->id;
                                    $insData[$key]['channel_type'] = $channel_type->id;
                                    $insData[$key]['channel_number'] = $channel_number->id;
                                    $insData[$key]['volume'] = $volume_type->id;
                                    if(isset($row['volume_to'])&&$row['volume_to'])
                                    {
                                        $insData[$key]['volume_value'] = $row['volume_from'] . '-' . $row['volume_to'];
                                    }
                                    else
                                    {
                                        $insData[$key]['volume_value'] = $row['volume_from'];
                                    }

                                    $insData[$key]['unit'] = 4;
                                    $insData[$key]['for_sale'] = $row['for_sale'];
                                    $insData[$key]['model_price'] = $row['price'];
                                    $insData[$key]['model_name'] = $row['model_name'];
                                    $insData[$key]['model_description'] = $row['brand'] ."".$row['model_name']. "  (" . $insData[$key]['volume_value'] . ") μl "  . $row['volume_type'] . " " .$row['operation']. " " . " " . $row['channel_type']  . " " . "Pipette";
                                }
                                else
                                {
                                    $insFailData[$key] = $row;
                                    $insFailData[$key]['failure_reason'] = 'This SKU Number is already in use';
                                }
                               // echo '<pre>';print_r($insData);exit;
//                            $insData[$key]['model_description'] = $row['brand'] . " " .$row['model_name'] ."  (". $insData[$key]['volume_value'] . ") μl " . $row['volume_type'] . " " . $row['channel_type'] . " Pipette" ;
                            }
                            else
                            {
                                $insFailData[$key] = $row;
                                $insFailData[$key]['failure_reason'] = 'This combination of Instrument Model already exists';
                            }

                        } else {
                            $insFailData[$key] = $row;
                            $insFailData[$key]['failure_reason'] = 'Master Data is Incorrect';
                        }
                        $i++;
                    }
//                    echo '<pre>';print_r(($insFailData));
//                    echo '<pre>';print_r(($insData));exit;
                    if (count($insData)) {
                        foreach ($insData as $key => $row) {
                            $save = array();
                            $save['id'] = '';
                            $save['product_id'] = $row['product_id'];
                            $save['sku_number'] = (isset($row['sku_no'])&&$row['sku_no'])?$row['sku_no']:'';
                            $save['manufacturer_ids'] = $row['manufacturer_ids'];
                            $save['model_name'] = $row['model_name'];
                            $save['model_description'] = $row['model_description'];
                            $save['brand_id'] = $row['brand_id'];
                            $save['brand_operation'] = $row['brand_operation'];
                            $save['channel'] = $row['channel_type'];
                            $save['channel_number'] = $row['channel_number'];
                            $save['volume'] = $row['volume'];
                            $save['volume_value'] = $row['volume_value'];
                            $save['unit'] = $row['unit'];
                            $save['model_price'] = $row['model_price'];
                            if(strtolower($row['for_sale'])=='yes')
                            {
                                $save['model_buy'] =1;
                            }
                            else
                            {
                                $save['model_buy'] = 0;
                            }
                            $save['model_service'] = 1;
                            $save['created_by'] = 3; //for Novamed Admin
                            //echo '<pre>';print_r($save);exit;
                            $save_equipment = $this->equipment->saveEquipment($save);

                        }

                    }

                }

            }
            //$csv_data = array_slice($data, 0, 2);
            // echo '<pre>';print_r($data);die;
            if (count($insData) > 0  && $save_equipment ) {
                $message = Session::flash('message', 'Successfully Uploaded ' . count($insData) . ' out of ' . count($arr));
            } else {
                $message = Session::flash('error', 'No Instruments are Uploaded out of ' . count($arr));
            }

            return view('model/success_upload')->with('data', $insFailData)->with('title', $title)->with('message', $message)->with('sData', $insData)->with('total', $arr);
        } else {
            $message = 'Unsupported File Format.Upload Only Excel File';
            return view('model/uploadPage')->with('title', $title)->with('message', $message);
        }
    }


    function downloadInstrumentSampleCsv()
    {
        $file = public_path() . "/format/SampleInstrumentModel.xlsx";
        $name = "sampleFormat.xlsx";
        $headers = array(
            'Content-Type: application/pdf',
        );

        return Response::download($file, $name, $headers);
    }

    function checkModelCombination()
    {

        $post = Input::all();
//        echo '<pre>';print_R($post);exit;
            if($post)
            {
                $checkcombination = $this->equipment->checkequipmentcombination($post);
                if(count($checkcombination))
                {
                    die(json_encode(array('result' => true, 'message' => 'These combinations Channel,Operation and Volume are already exist')));
                }
                else
                {
                    die(json_encode(array('result' => false)));
                }
            }

//        if($post)
//        {
//            $inputmodel = $post['data']; //print_r($inputmodel);die;
//            $model = DB::table('tbl_equipment_model')->whereRaw('REPLACE(`model_description`," ","") = ?',str_replace(' ','',$inputmodel))->select('id')->first();
//            if($model)
//            {
//                die(json_encode(array("result" => false, "msg" => 'This combination of instruments already exists')));
//            }
//            else
//            {
//                die(json_encode(array("result" => true)));
//            }
//        }
//        else
//        {
//            die(json_encode(array("result" => false, "msg" => 'Something goes wrong')));
//        }
    }

}
