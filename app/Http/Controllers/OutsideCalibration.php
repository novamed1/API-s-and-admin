<?php
namespace App\Http\Controllers;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\Technicianuser;
use App\User;
use JWTAuthException;
use DB;
use Response;
use Validator;
use Illuminate\Support\Facades\Hash;
use Image;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedExceptionException;
use App\Workorderprocessupdate;

class OutsideCalibration extends TechnicianProcessUpdationController
{
    public $user;

    public function __construct($user){
       $this->workorderProcess = new Workorderprocessupdate();
       $this->workorderoutside;
    }

    public function saveasfoundoutside($user)
    {
        $savestatus['id'] = false;
        $savestatus['workorder_item_id'] = parent::$workorder_item_id;
        $savestatus['workorder_status'] = 1;
        $save_status_updation = $this->workorderProcess->save_workorder_status_updation($savestatus,parent::$user_id);


    }

    public function saveascalibratedoutside($user)
    {
        $savestatus['id'] = false;
        $savestatus['workorder_item_id'] = parent::$workorder_item_id;
        $savestatus['workorder_status'] = 3;
        $save_status_updation = $this->workorderProcess->save_workorder_status_updation($savestatus,parent::$user_id);

    }

    public function saveasdespatchoutside($user)
    {
        $despatch_items = parent::$despatch_items;

        foreach ($despatch_items as $key=>$row)
        {
            $getStatusDespatch = $this->workorderProcess->getWorkOrderProcessStatus($row['id'],5);
            if(!$getStatusDespatch)
            {
                $document = (isset($row['pdfreport']) && $row['pdfreport'])?$row['pdfreport']:'';
                if ($document) {
                    $offset1 = strpos($document, ',');
                    $tmp = base64_decode(substr($document, $offset1));
                    $memType = $this->_file_mime_type($tmp);
                    $fileType = explode('/', $memType);
                    $fileType = $fileType[1];
                    $reportFile = 'report-' . uniqid();
                    $path = base_path() . '/public/report/technicianreview';

                    if (!is_dir($path)) {
                        return Response::json([
                            'status' => 0,
                            'message' => 'The path you given was invalid'
                        ], 400);
                    }
                    $uploadedFile = file_put_contents($path . '/' . $reportFile.'.'.$fileType, $tmp);

                    if ($uploadedFile) {
                        $document_name = $reportFile.'.'.$fileType;
                    } else {
                        $document_name = '';
                    }

                    $saveworkorderitem['report'] = $document_name;
                }
                $save['id'] = '';
                $save['workorder_item_id'] = $row['id'];
                $save['workorder_status'] = 5;
                $save['water_temperature'] = '';
                $save['relevent_humidity'] = '';
                $save['barometric_pressure'] = '';
                $save['air_dencity'] = '';
                $save['z_factor'] = '';
                $save['liquid_dencity'] = '';
                $workorder_status_id = $this->workorderProcess->save_workorder_status_updation($save,parent::$user_id);
                $savelog['id'] = '';
                $savelog['workorder_status_id'] = $workorder_status_id;
                $savelog['external_cleaning'] = $row['external_cleaning'];
                $savelog['labels'] = $row['labels'];
                $this->workorderProcess->save_asdespatch_log($savelog,parent::$user_id);

                $serice_request_items = DB::table('tbl_work_order_items as oi')
                    ->select('oi.request_item_id')
                    ->where('oi.id','=',$row['id'])->get()->first();
                $saveworkorderitem['id'] = $row['id'];
                $saveworkorderitem['despatched_status'] = 'completed';
                $saveworkorderitem['technician_review'] = parent::$user_id;
                $saveworkorderitem['technician_review_date'] = date('Y-m-d');
                $this->workorderoutside->saveWorkOrderItemStatus($saveworkorderitem);

            }
        }
    }

}