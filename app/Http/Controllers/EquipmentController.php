<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\User;
use JWTAuthException;
use DB;
use Response;
use Illuminate\Support\Facades\Hash;
use App\Equipment;
use App\Models\Equipment as Equ;
use App\Models\DueEquipments;
use Validator;
use Image;
use App\Models\Workorder;

class EquipmentController extends Controller
{
    private $user;
    public $uid;
    public $cid;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->equipment = new Equipment();
        $this->equipmentmodel = new Equ();
        $this->userModel = new User();
        $this->dueequipments = new DueEquipments();
        $this->workorder = new Workorder();
    }

    public function index()
    {
        //print_r($this->user);die;
        return response()->json(['auth' => Auth::user(), 'users' => User::all()]);
    }


    public function createEquipment(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        //$customer = $this->userModel->getCustomer($user['id']);
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();  //echo'<pre>';print_r($reqInputs);'</pre>';die;
        if($reqInputs['asset_num'])
        {
            if($reqInputs['equipment_id'])
            {
                $checkExistAsset = DB::table('tbl_equipment')->where('asset_no',$reqInputs['asset_num'])->where('customer_id',$this->cid)->where('id','!=',$reqInputs['equipment_id'])->first();
            }
            else
            {
                $checkExistAsset = DB::table('tbl_equipment')->where('asset_no',$reqInputs['asset_num'])->where('customer_id',$this->cid)->first();
            }

            if($checkExistAsset)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Asset number is already exist'
                ], 422);
            }
        }
        if($reqInputs['serial_num'])
        {
            if($reqInputs['equipment_id'])
            {
                $checkExistSerial = DB::table('tbl_equipment')->where('serial_no',$reqInputs['serial_num'])->where('customer_id',$this->cid)->where('id','!=',$reqInputs['equipment_id'])->first();
            }
            else
            {
                $checkExistSerial = DB::table('tbl_equipment')->where('serial_no',$reqInputs['serial_num'])->where('customer_id',$this->cid)->first();
            }

            if($checkExistSerial)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Serial number is already exist'
                ], 422);
            }
        }
        $save = array();
        $mainPath = public_path() . '/instruments/';
        $location = $mainPath;
        $trimmedLocation = str_replace('\\', '/', $location);
        if (isset($reqInputs['photo']) && $reqInputs['photo']) {
            $photo = $reqInputs['photo'];
            $offset1 = strpos($photo, ',');
            $tmp = base64_decode(substr($photo, $offset1));
            $memType = $this->_file_mime_type($tmp);
            $fileType = explode('/', $memType);
            $fileType = $fileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
            $imageName = 'Instrument' . '-' . uniqid() . '.' . $fileType;


            //    image upload
            //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

            $filepath = $trimmedLocation;
            if (!is_dir($filepath)) {
                return Response::json([
                    'status' => 0,
                    'message' => 'The path you given was invalid'
                ], 400);
            }
            $uploadedFile = file_put_contents($filepath . '/' . $imageName, $tmp);

            if ($uploadedFile) {
                if (is_file($filepath . '/' . $imageName)) {
                    $imagesize = getimagesize($filepath . '/' . $imageName);
                    $width = $imagesize[0];
                    $height = $imagesize[1];

                    $largeWidth = $width;
                    $mediumWidth = $width;
                    $smallWidth = $width;
                    $extralargeWidth = $width;
                    $iconWidth = $width;
                    $thumbnailWidth = $width;
                    if ($width > 425) {
                        $largeWidth = 425;
                    }
                    $destinationLargePath = public_path('instruments/large');
                    Image::make($filepath . '/' . $imageName)->resize(548, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save($destinationLargePath . '/' . $imageName);
                    if ($width > 375) {
                        $mediumWidth = 425;

                    }
                    $destinationMediumPath = public_path('instruments/medium');
                    Image::make($filepath . '/' . $imageName)->resize($mediumWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save($destinationMediumPath . '/' . $imageName);
                    if ($width > 320) {

                        $smallWidth = 320;
                    }
                    $destinationSmallPath = public_path('instruments/small');
                    Image::make($filepath . '/' . $imageName)->resize($smallWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save($destinationSmallPath . '/' . $imageName);
                    if ($width > 200) {

                        $thumbnailWidth = 200;
                    }
                    $destinationThumbPath = public_path('instruments/thumb');
                    Image::make($filepath . '/' . $imageName)->resize($thumbnailWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save($destinationThumbPath . '/' . $imageName);

                    if ($width > 64) {
                        $iconWidth = 64;
                    }
                    $destinationIconPath = public_path('instruments/icon');
                    Image::make($filepath . '/' . $imageName)->resize($iconWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save($destinationIconPath . '/' . $imageName);

                }

                $photo =$imageName;
            }
            else
            {
                $photo = '';
            }

            $save['photo'] = $photo;

        }


        if(isset($reqInputs['pref_contact_id'])&&$reqInputs['pref_contact_id'])
        {

            $pref_contact_id = $reqInputs['pref_contact_id'];
            $pref_contact_user = $this->user->getUserDetailData($pref_contact_id);
            if($pref_contact_user)
            {
                $pref_contact_name = isset($pref_contact_user->name)?$pref_contact_user->name:'';
            }
            else
            {
                $pref_contact_name = '';
            }

        }
        else
        {
            $pref_contact_name = isset($reqInputs['pref_contact']) ? $reqInputs['pref_contact'] : '';
        }


        $save['id'] = isset($reqInputs['equipment_id']) ? $reqInputs['equipment_id'] : '';
        $save['name'] = isset($reqInputs['equipment_name']) ? $reqInputs['equipment_name'] : '';
        $save['description'] = isset($reqInputs['equipment_id']) ? $reqInputs['equipment_id'] : '';
        $save['asset_no'] = isset($reqInputs['asset_num']) ? $reqInputs['asset_num'] : '';
        $save['serial_no'] = isset($reqInputs['serial_num']) ? $reqInputs['serial_num'] : '';
        $save['equipment_model_id'] = isset($reqInputs['equipment_model']) ? $reqInputs['equipment_model'] : '';
        $save['customer_id'] = $this->cid;
        $save['location'] = isset($reqInputs['pref_location']) ? $reqInputs['pref_location'] : '';
        $save['pref_tel'] = isset($reqInputs['pref_telephone']) ? $reqInputs['pref_telephone'] : '';
        $save['plan_id'] = isset($reqInputs['plan_id']) ? $reqInputs['plan_id'] : '';
        $save['pref_email'] = isset($reqInputs['pref_email']) ? $reqInputs['pref_email'] : '';
        $save['pref_contact'] = $pref_contact_name;
        $save['pref_contact_id'] = isset($reqInputs['pref_contact_id']) ? $reqInputs['pref_contact_id'] : '';
        $save['is_active'] = 1;
        //print_r($save);die;
        $Saveresult = $this->dueequipments->saveEquipments($save);
        if($reqInputs['frequency_id'])
        {
            $inputsave['frequency_id'] = $reqInputs['frequency_id'];
            $inputsave['exact_date'] = null;

        }
        else
        {
            $exactDate = str_replace('/', '-', $reqInputs['exact_date']);
            $inputsave['exact_date'] = date('Y-m-d', strtotime($exactDate));
            $inputsave['frequency_id'] = null;
        }

        if (!$reqInputs['equipment_id']) {
            $inputsave['id'] = false;
        } else {
            $getDueEquipment = $this->dueequipments->getvalues($reqInputs['equipment_id']);
//                    echo '<pre>';print_r($getDueEquipment);die;
            if ($getDueEquipment) {
                $inputsave['id'] = $getDueEquipment->id;
            } else {
                $inputsave['id'] = false;
            }
        }

        $startdates = str_replace('/', '-', $reqInputs['last_date']);
        $enddates = str_replace('/', '-', $reqInputs['next_due_date']);
        $inputsave['last_cal_date'] = date('Y-m-d', strtotime($startdates));
        $inputsave['next_due_date'] = date('Y-m-d', strtotime($enddates));
        $inputsave['equipment_id'] = $Saveresult;
        $inputsave['as_found'] = $reqInputs['found_status'];
        $inputsave['as_calibrate'] = $reqInputs['cal_status'];
        $inputresult = $this->dueequipments->saveDueequipments($inputsave);
        $deleteEquipment = $this->equipmentmodel->deleteInstrumentDetails($Saveresult);

        $servicePlan = DB::table('tbl_service_plan');
        $servicePlan->where('tbl_service_plan.id',$reqInputs['plan_id']);
        $resultserviceplan = $servicePlan->first(); //print_r($resultserviceplan);die;

        $channel = $this->equipmentmodel->getchannelnumber($save['equipment_model_id']);
        $pricing_id = isset($reqInputs['pricing_id']) ? $reqInputs['pricing_id'] : '';
        $pricing_value = $this->equipmentmodel->getpricing($pricing_id);


        $instrumentDetail['id'] = false;
        $instrumentDetail['equipment_id'] = $Saveresult;
        $instrumentDetail['plan_name'] = $resultserviceplan->service_plan_name;
        $instrumentDetail['no_of_channels'] = isset($channel->channel_number) ? $channel->channel_number : '';
        $instrumentDetail['operation_method'] = isset($channel->brand_operation) ? $channel->brand_operation : '';
        $instrumentDetail['price'] = isset($pricing_value->price) ? $pricing_value->price : '';
        $instrumentDetail['plan_id'] = $save['plan_id'];
        $instrumentDetail['pricing_criteria_id'] = isset($reqInputs['pricing_id']) ? $reqInputs['pricing_id'] : '';
        $instrumentDetail['created_by'] = $this->uid;

        $deleteEquipment = $this->equipmentmodel->saveInstrumentDetails($instrumentDetail);

        $instrumentDetailLog['id'] = false;
        $instrumentDetailLog['equipment_id'] = $Saveresult;
        $instrumentDetailLog['plan_name'] = $resultserviceplan->service_plan_name;
        $instrumentDetailLog['no_of_channels'] = isset($channel->channel_number) ? $channel->channel_number : '';
        $instrumentDetailLog['operation_method'] = isset($channel->brand_operation) ? $channel->brand_operation : '';
        $instrumentDetailLog['price'] = isset($pricing_value->price) ? $pricing_value->price : '';
        $instrumentDetailLog['plan_id'] = $save['plan_id'];
        $instrumentDetailLog['pricing_criteria_id'] = isset($reqInputs['pricing_id']) ? $reqInputs['pricing_id'] : '';

        $instrumentDetailLog['created_by'] = $this->uid;

        $deleteEquipment = $this->equipmentmodel->saveInstrumentLogDetails($instrumentDetailLog);


        return Response::json([
            'status' => 1,
            'msg' => "Instrument has been added"

        ], 200);


    }

    protected function _file_mime_type($file)
    {
        // We'll need this to validate the MIME info string (e.g. text/plain; charset=us-ascii)
        $regexp = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';

        /* Fileinfo extension - most reliable method
         *
         * Unfortunately, prior to PHP 5.3 - it's only available as a PECL extension and the
         * more convenient FILEINFO_MIME_TYPE flag doesn't exist.
         */
        if (function_exists('finfo_buffer')) {

            $finfo = @finfo_open(FILEINFO_MIME);
            if (is_resource($finfo)) // It is possible that a FALSE value is returned, if there is no magic MIME database file found on the system
            {
                $mime = @finfo_buffer($finfo, $file);
                finfo_close($finfo);

                /* According to the comments section of the PHP manual page,
                 * it is possible that this function returns an empty string
                 * for some files (e.g. if they don't exist in the magic MIME database)
                 */
                if (is_string($mime) && preg_match($regexp, $mime, $matches)) {
                    $file_type = $matches[1];
                    return $file_type;
                }
            }
        }

    }


    public function allEquipments(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        //$customer = $this->userModel->getCustomer($user['id']);
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $fParams['startCallDate'] = (isset($reqInputs['startCallDate']) && $reqInputs['startCallDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['startCallDate']))) : '';
        $fParams['endCallDate'] = (isset($reqInputs['endCallDate']) && $reqInputs['endCallDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['endCallDate']))) : '';

        $fParams['asset_num'] = isset($reqInputs['asset_num']) ? $reqInputs['asset_num'] : '';
        $fParams['serial_num'] = isset($reqInputs['serial_num']) ? $reqInputs['serial_num'] : '';
        $fParams['instrument'] = isset($reqInputs['instrument']) ? $reqInputs['instrument'] : '';
        $fParams['location'] = isset($reqInputs['location']) ? $reqInputs['location'] : '';
        $fParams['service_plan'] = isset($reqInputs['service_plan']) ? $reqInputs['service_plan'] : '';
        $fParams['cal_frequency'] = isset($reqInputs['cal_frequency']) ? $reqInputs['cal_frequency'] : '';
        $fParams['last_cal_date'] = isset($reqInputs['last_cal_date']) ? $reqInputs['last_cal_date'] : '';
        $fParams['next_due'] = isset($reqInputs['next_due']) ? $reqInputs['next_due'] : '';
        $fParams['statusHeader'] = isset($reqInputs['statusHeader']) ? $reqInputs['statusHeader'] : '';
        $fParams['pref_contact'] = isset($reqInputs['pref_contact']) ? $reqInputs['pref_contact'] : '';


        $equipments = $this->equipment->equipments($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => $fParams['keyword'], 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'cus_id' => $this->cid,
            'asset_num' => $fParams['asset_num'],'serial_num' => $fParams['serial_num'],'instrument' => $fParams['instrument'],
            'location' => $fParams['location'],'service_plan' => $fParams['service_plan'],'cal_frequency' => $fParams['cal_frequency'],
            'last_cal_date' => $fParams['last_cal_date'],'next_due' => $fParams['next_due'],'statusHeader' => $fParams['statusHeader'],
            'pref_contact' => $fParams['pref_contact']

            ));
        //print_r($equipments);die;
        $countequipments = $this->equipment->countequipments(array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'search' => $fParams['keyword'], 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate']), $this->cid);
        $temp = array();
        if ($equipments) {
            foreach ($equipments as $key => $row) {
                $temp[$key] = (array)$row;
                $last = $this->equipment->getDueequipments($row->id);
                if (isset($last->last_cal_date) && $last->last_cal_date != '') {
                    $lastCall = date('m/d/Y', strtotime(str_replace('/', '-', $last->last_cal_date)));
                } else {
                    $lastCall = '';
                }
                if (isset($last->next_due_date) && $last->next_due_date != '') {
                    $nextDue = date('m/d/Y', strtotime(str_replace('/', '-', $last->next_due_date)));
                } else {
                    $nextDue = '';
                }
                if ($last->next_due_date > date('Y-m-d')) {
                    $temp[$key]['status'] = 'Upcoming';
                } else {
                    $temp[$key]['status'] = 'Overdue';
                }
                $temp[$key]['last_call_date'] = $lastCall;
                $temp[$key]['next_due_date'] = $nextDue;

                $equipment_id = (isset($row->id)&&$row->id)?$row->id:'';
                $temp_request = array();
                $temp_request_dates = array();
                if($equipment_id)
                {
                    $service_request = $this->workorder->workorderItemsServiceRequest($equipment_id); //print_r($service_request);die;
                    if($service_request)
                    {

                        foreach($service_request as $skey=>$srow)
                        {
                            if(!in_array($temp_request,array_column($temp_request, 'request_date')))
                            {
                                $temp_request[$skey] = (array)$srow;
                                $temp_request[$skey]['request_date'] = date('Y-m-d',strtotime($srow->service_schedule_date));
                            }

                        }
                        $temp_request_dates = array_unique(array_column($temp_request, 'request_date'));



                    }
                }

                $temp[$key]['history_dates'] = $temp_request_dates;
                if($row->no_of_days==3)
                {

                    $temp[$key]['call_frequency'] = '3 Months';
                }
                elseif($row->no_of_days==6)
                {
                    $temp[$key]['call_frequency'] = '6 Months';

                }
                elseif($row->no_of_days==12)
                {
                    $temp[$key]['call_frequency'] = '12 Months';

                }
                else
                {
                    $temp[$key]['call_frequency'] = 'Pick up Date';
                }



            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'count' => count($countequipments)

        ], 200);
    }

    public function allEquipmentsCount(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $fParams['startCallDate'] = (isset($reqInputs['startCallDate']) && $reqInputs['startCallDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['startCallDate']))) : '';
        $fParams['endCallDate'] = (isset($reqInputs['endCallDate']) && $reqInputs['endCallDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['endCallDate']))) : '';

        $equipmentsCount = $this->equipmentmodel->equipmentsCount($fParams['limit'], $fParams['offset'], array('search' => $fParams['keyword'], 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'customer_id' => $this->cid));

        return Response::json([
            'status' => 1,
            'data' => count($equipmentsCount)

        ], 200);


    }

    public function equipmentDetail(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();
        $requiredFields = [
            'equipment_id' => isset($reqInputs['equipment_id']) ? $reqInputs['equipment_id'] : ''

        ];

        $rules = [
            'equipment_id' => 'required'
        ];
        $checkValid = Validator::make($requiredFields, $rules);

        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 422);
        }
        $equipment_id = $reqInputs['equipment_id'];

        $equipment = $this->equipment->equipmentDetail($equipment_id);

        $temp = array();
        if ($equipment) {
            $last = $this->equipment->getDueequipments($equipment->id);
            $temp['asset_no'] = $equipment->asset_no;
            $temp['serial_no'] = $equipment->serial_no;
            $temp['pref_contact'] = $equipment->pref_contact;
            $temp['pref_tel'] = $equipment->pref_tel;
            $temp['pref_email'] = $equipment->pref_email;
            $temp['location'] = $equipment->location;
            $temp['model_name'] = $equipment->model_name;
            $temp['unit'] = $equipment->unit;
            $temp['channel_name'] = $equipment->channel_name;
            $temp['operation_name'] = $equipment->operation_name;

            $temp['name'] = $equipment->name;
            $temp['description'] = $equipment->description;
            $temp['model_id'] = $equipment->model_id;
            $temp['plan_id'] = $equipment->plan_id;
            $temp['pricing_id'] = $equipment->pricing_criteria_id;
            $temp['last_cal_date'] = $equipment->last_cal_date;
            $temp['next_due_date'] = $equipment->next_due_date;
            $temp['cal_status'] = $equipment->as_calibrate;
            $temp['found_status'] = $equipment->as_found;
            $temp['frequency_id'] = $equipment->frequency_id;
            $temp['brand_id'] = $equipment->brand_id;
            $temp['manufacturer_id'] = $equipment->manufacturer_id;
            $temp['pref_contact_id'] = $equipment->pref_contact_id;
            $temp['model_description'] = $equipment->model_description;
            if($equipment->exact_date)
            {
                $temp['exact_date'] = date('Y-m-d',strtotime($equipment->exact_date));
            }
            else
            {
                $temp['exact_date'] = '';
            }



            $temp['created_date'] = date('m/d/Y', strtotime(str_replace('/', '-', $equipment->created_date)));
            $temp['range'] = $equipment->volume_value;
            if ($last->next_due_date > date('Y-m-d')) {
                $temp['status'] = 'Upcoming';
            } else {
                $temp['status'] = 'Overdue';
            }
            // print_r($_SERVER['DOCUMENT_ROOT']);die;
            if ($equipment->model_image) {
                $filePath = 'public/equipment_models/images/';
                $extralarge = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/equipment_models/images/extralarge/' . $equipment->model_image;
                $large = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/equipment_models/images/large/' . $equipment->model_image;
                $original = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/equipment_models/images/original/' . $equipment->model_image;
                $small = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/equipment_models/images/small/' . $equipment->model_image;
                $thumbnail = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/equipment_models/images/thumbnail/' . $equipment->model_image;
                $extralargePath =  env('file_path') . '/' . $filePath . 'extralarge/' . $equipment->model_image;
                $largePath =  env('file_path') . '/' . $filePath . 'large/' . $equipment->model_image;
                $originalPath =  env('file_path') . '/' . $filePath . 'original/' . $equipment->model_image;
                $smallPath =  env('file_path') . '/' . $filePath . 'small/' . $equipment->model_image;
                $thumbnailPath =  env('file_path') . '/' . $filePath . 'thumbnail/' . $equipment->model_image;
                if (file_exists($extralarge) && file_exists($large) && file_exists($original) && file_exists($small) && file_exists($thumbnail)) {
                    $image = array('extralarge' => $extralargePath, 'large' => $largePath, 'original' => $originalPath, 'small' => $smallPath, 'thumbnail' => $thumbnailPath);
                } else {
                    $filePath = 'public/equipment_models/images/';
                    $originalPath = env('file_path')  . '/' . $filePath . 'original/default.png';
                    $image = array('defaultImage' => $originalPath);
                }

            } else {
                $filePath = 'public/equipment_models/images/';
                $originalPath = env('file_path') . '/' . $filePath . 'original/default.png';
                $image = array('defaultImage' => $originalPath);
            }

            if ($equipment->photo) {
                $filePath = 'public/instruments/';
                $large = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/instruments/large/' . $equipment->photo;
                $original = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/instruments/' . $equipment->photo;
                $small = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/instruments/small/' . $equipment->photo;
                $thumbnail = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/instruments/thumb/' . $equipment->photo;
                $largePath = env('file_path') . '/' . $filePath . 'large/' . $equipment->photo;
                $originalPath =  env('file_path') . '/' . $filePath . 'original/' . $equipment->photo;
                $smallPath = env('file_path') . '/' . $filePath . 'small/' . $equipment->photo;
                $thumbnailPath = env('file_path') . '/' . $filePath . 'thumb/' . $equipment->photo;
                if (file_exists($large) && file_exists($original) && file_exists($small) && file_exists($thumbnail)) {
                    $ins_image = array('large' => $largePath, 'original' => $originalPath, 'small' => $smallPath, 'thumbnail' => $thumbnailPath);
                } else {
                    $filePath = 'public/equipment_models/images/';
                    $originalPath = env('file_path') . '/' . $filePath . 'original/default.png';
                    $ins_image = array('defaultImage' => $originalPath);
                }

            } else {
                $filePath = 'public/equipment_models/images/';
                $originalPath = env('file_path') . '/' . $filePath . 'original/default.png';
                $ins_image = array('defaultImage' => $originalPath);
            }
            $temp['image'] = $image;
            $temp['instrument_image'] = $ins_image;
        }

        return Response::json([
            'status' => 1,
            'data' => $temp

        ], 200);
    }

    public function dueEquipments(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();  //echo'<pre>';print_r($reqInputs);'</pre>';die;
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $fParams['startCallDate'] = (isset($reqInputs['startCallDate']) && $reqInputs['startCallDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['startCallDate']))) : '';
        $fParams['endCallDate'] = (isset($reqInputs['endCallDate']) && $reqInputs['endCallDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['endCallDate']))) : '';

        $fParams['startNextDate'] = (isset($reqInputs['startNextDate']) && $reqInputs['startNextDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['startNextDate']))) : '';
        $fParams['endNextDate'] = (isset($reqInputs['endNextDate']) && $reqInputs['endNextDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['endNextDate']))) : '';
        $fParams['monthYear'] = isset($reqInputs['monthYear']) ? $reqInputs['monthYear'] : '';
//        $equipments = $this->equipmentmodel->dueequipments($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'],'startNextDate' => $fParams['startNextDate'], 'endNextDate' => $fParams['endNextDate'], 'monthYear' => '', 'cus_id' => $this->cid, 'calibrate_process' => 1));
//        $equipments = $this->equipmentmodel->dueequipments($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'],'startNextDate' => $fParams['startNextDate'], 'endNextDate' => $fParams['endNextDate'], 'monthYear' => '', 'cus_id' => $this->cid,'calibrate_process' => 1));
        $equipments = $this->equipmentmodel->dueequipmentsByDate($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'],'startNextDate' => $fParams['startNextDate'], 'endNextDate' => $fParams['endNextDate'], 'monthYear' => '', 'cus_id' => $this->cid,'calibrate_process' => 1));
        //echo '<pre>';print_r($equipments);die;
        // $countequipments = $this->equipment->countequipments(array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'search' => $fParams['keyword']), $this->cid);
        $limitdatas = array();
        $sourceData = array();
        $monthData = array();
        $counts = array();
        if ($equipments) {
            $currentMonthYear = date('Y-m');
            foreach ($equipments as $key=>$row) {
//                $monthData = $this->equipmentmodel->monthWiseDueEquipment('4', '0', array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => $fParams['keyword'], 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'],'startNextDate' => $fParams['startNextDate'], 'endNextDate' => $fParams['endNextDate'], 'monthYear' => $row->day, 'cus_id' => $this->cid, 'calibrate_process' => 1));
                $monthData = $this->equipmentmodel->monthWiseDueEquipment('4', '0', array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => $fParams['keyword'], 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'],'startNextDate' => $fParams['startNextDate'], 'endNextDate' => $fParams['endNextDate'], 'monthYear' => $row->day, 'cus_id' => $this->cid,'calibrate_process' => 1));
                   if($monthData)
                   {
                       foreach ($monthData as $key1 => $row1) {
                          //print_r($row1);die;
                           $date = date('Y-m',strtotime(str_replace('/','-',$row1->d)));
                           $getModelId = $this->userModel->getEquipmet($row1->equipment_id);
                           $getModel = $this->equipmentmodel->getmodel($getModelId->equipment_model_id);
                           $getplans = $this->userModel->customerSetups($this->cid);
                           $explodePlans = (isset($getplans->plan_id) && $getplans->plan_id)?explode(',',$getplans->plan_id):array();
                           $plan_id = (isset($explodePlans[0]) && $explodePlans[0])?$explodePlans[0]:1;
                           $data['volume'] = $getModel->volume;
                           $data['operation'] = $getModel->brand_operation;
                           $data['channel'] = $getModel->channel;
                           $data['channelnumber'] = $getModel->channel_number;
                           $data['plan_id'] = $getModelId->plan_id?$getModelId->plan_id:$plan_id;
                           $channel = $getModel->channel_number;
                           //$pricing = $this->userModel->pricingcreteria($plan_id,$channel);
                           $pricing = $this->userModel->pricingcreteriasingle($data);

                           $limitdatas[$row->day][$key1] = (array)$row1;
                           if($row1->next_due_date>date('Y-m-d'))
                           {
                             $status = 'upcoming';
                           }
                           else
                           {
                               $status = 'overdue';
                           }
                           $limitdatas[$row->day][$key1]['due_status'] = $status;
                           $limitdatas[$row->day][$key1]['price'] =(isset($pricing->price))?$pricing->price:0;
                           $limitdatas[$row->day][$key1]['plan_id'] = $getModelId->plan_id?$getModelId->plan_id:$plan_id;
                           $limitdatas[$row->day][$key1]['pricing_criteria_id'] =(isset($pricing->pricing_id))?$pricing->pricing_id:0;
                           $limitdatas[$row->day][$key1]['plan_name'] =(isset($pricing->service_plan_name))?$pricing->service_plan_name:0;
                           if($row1->nOfDays==3)
                           {

                               $limitdatas[$row->day][$key1]['call_frequency'] ='3 Months';
                           }
                           elseif($row1->nOfDays==6)
                           {
                               $limitdatas[$row->day][$key1]['call_frequency'] ='6 Months';

                           }
                           elseif($row1->nOfDays==12)
                           {
                               $limitdatas[$row->day][$key1]['call_frequency'] ='12 Months';

                           }
                           else
                           {
                               $limitdatas[$row->day][$key1]['call_frequency'] ='Pick up Date';
                           }


                       }
                   }


            } //print_r($limitdatas);die;
            //echo'<pre>';print_r($limitdatas);'</pre>';die;
            if($limitdatas)
            {
                foreach ($limitdatas as $source=>$value)
                {
                    if($currentMonthYear>=$source)
                    {

                        foreach ($value as $col=>$arr)
                        { //print_r($arr);die;
                            if($currentMonthYear>=$arr['d'])
                            {
                                $sourceData[$currentMonthYear.'-01'][$col]=$arr;
                            }

                        }
                        $countEquipments = $this->equipmentmodel->viewAlldueEquipments('', '', array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['keyword'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'monthYear' => $currentMonthYear, 'cus_id' => $this->cid, 'calibrate_process' => 1,'YearAndMonth'=>1),$count=1);
                        //$sourceData[$currentMonthYear.'-01']['count']= $countEquipments;
                    }
                    else
                    {
                        $sourceData[$source.'-01'] = (array)$value;
                        $countEquipments = $this->equipmentmodel->viewAlldueEquipments('', '', array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['keyword'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'monthYear' => $source, 'cus_id' => $this->cid, 'calibrate_process' => 1,'YearAndMonth'=>1),$count=1);
                       // $sourceData[$source.'-01']['count']= $countEquipments;
                    }
                }  //echo'<pre>';print_r($sourceData);'</pre>';die;
                if($sourceData)
                {
                    $i=0;
                       foreach ($sourceData as $keySource=>$rowvalue)
                  {
                      $mY = date('m-Y',strtotime(str_replace('-','/',$keySource)));
                      $overDueCount=0;
                      if($mY==date('m-Y'))
                      {
                          $due = $this->equipmentmodel->viewAlldueEquipments('', '', array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['status'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'monthYear' => $mY, 'cus_id' => $this->cid, 'calibrate_process' => 1,'monthAndYear'=>1),$count=0);

                          foreach ($due as $coldue=>$duevalue)
                          {
                              if(date('Y-m-d')>$duevalue->next_due_date)
                              {
                                  $overDueCount++;
                              }
                          }
                      }
                      else
                      {
                          $due = array();
                      }

                      $counts[$keySource]['counts'] = $this->equipmentmodel->viewAlldueEquipments('', '', array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['keyword'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'monthYear' => $mY, 'cus_id' => $this->cid, 'calibrate_process' => 1,'monthAndYear'=>1),$count=1);
                      $counts[$keySource]['overdue'] = $overDueCount;
                      $i++;
                  }
                }
            }



        }


        return Response::json([
            'status' => 1,
            'data' => $limitdatas,
            'counts' => $counts,
            'totalEquipments'=>count($equipments)

        ], 200);


    }

    public function viewAlldueEquipments(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $reqInputs = $request->json()->all();

        $input = [
            'monthYear' => $reqInputs['monthYear']
        ];
        $rules = array(

            'monthYear' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }


        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $fParams['startCallDate'] = (isset($reqInputs['startCallDate']) && $reqInputs['startCallDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['startCallDate']))) : '';
        $fParams['endCallDate'] = (isset($reqInputs['endCallDate']) && $reqInputs['endCallDate']) ? date('Y-m-d', strtotime(str_replace('/', '-', $reqInputs['endCallDate']))) : '';
        $fParams['monthYear'] = isset($reqInputs['monthYear']) ? $reqInputs['monthYear'] : '';
//        $equipments = $this->equipmentmodel->viewAlldueEquipments($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['keyword'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'monthYear' => $fParams['monthYear'], 'cus_id' => $this->cid, 'calibrate_process' => 1,'monthAndYear'=>1),$count=0);
//        $countEquipments = $this->equipmentmodel->viewAlldueEquipments('', '', array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['keyword'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'monthYear' => $fParams['monthYear'], 'cus_id' => $this->cid, 'calibrate_process' => 1,'monthAndYear'=>1),$count=1);
        $equipments = $this->equipmentmodel->viewAlldueEquipments($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['keyword'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'monthYear' => $fParams['monthYear'], 'cus_id' => $this->cid, 'monthAndYear'=>1),$count=0);
        $countEquipments = $this->equipmentmodel->viewAlldueEquipments('', '', array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => '', 'status' => $fParams['keyword'], 'startCallDate' => $fParams['startCallDate'], 'endCallDate' => $fParams['endCallDate'], 'monthYear' => $fParams['monthYear'], 'cus_id' => $this->cid, 'monthAndYear'=>1),$count=1);
        // print_r($equipments);die;
        // $countequipments = $this->equipment->countequipments(array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'search' => $fParams['keyword']), $this->cid);
        $temp = array();
        if ($equipments) {
            foreach ($equipments as $key=>$row) {


                $temp[$key] = (array)$row;
                $getModelId = $this->userModel->getEquipmet($row->equipment_id);
                $getModel = $this->equipmentmodel->getmodel($getModelId->equipment_model_id);
                $getplans = $this->userModel->customerSetups($this->cid);
                $explodePlans = (isset($getplans->plan_id) && $getplans->plan_id)?explode(',',$getplans->plan_id):array();
                $plan_id = (isset($explodePlans[0]) && $explodePlans[0])?$explodePlans[0]:1;
                $data['volume'] = $getModel->volume;
                $data['operation'] = $getModel->brand_operation;
                $data['channel'] = $getModel->channel;
                $data['channelnumber'] = $getModel->channel_number;
                $data['plan_id'] = $getModelId->plan_id?$getModelId->plan_id:$plan_id;
                $channel = $getModel->channel_number;
                //$pricing = $this->userModel->pricingcreteria($plan_id,$channel);
                $pricing = $this->userModel->pricingcreteriasingle($data);

                if($row->next_due_date>date('Y-m-d'))
                {
                    $status = 'upcoming';
                }
                else
                {
                    $status = 'overdue';
                }
                $temp[$key]['due_status'] = $status;
                $temp[$key]['price'] =(isset($pricing->price))?$pricing->price:0;
                $temp[$key]['plan_id'] = $getModelId->plan_id?$getModelId->plan_id:$plan_id;
                $temp[$key]['pricing_criteria_id'] =(isset($pricing->pricing_id))?$pricing->pricing_id:0;
                $temp[$key]['plan_name'] =(isset($pricing->service_plan_name))?$pricing->service_plan_name:0;
                if($row->no_of_days==3)
                {

                    $temp[$key]['call_frequency'] ='3 Months';
                }
                elseif($row->no_of_days==6)
                {
                    $temp[$key]['call_frequency'] ='6 Months';

                }
                elseif($row->no_of_days==12)
                {
                    $temp[$key]['call_frequency'] ='12 Months';

                }
                else
                {
                    $temp[$key]['call_frequency'] ='Pick up Date';
                }



            }


        }


        return Response::json([
            'status' => 1,
            'data' => $temp,
            'count'=> $countEquipments

        ], 200);


    }


}