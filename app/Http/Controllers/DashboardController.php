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
use App\Models\Servicerequest;
use App\Models\Dashboard;
use App\Models\Equipment as Equ;
use Validator;
use Carbon\Carbon;
class DashboardController extends Controller
{
    private $user;
    public $uid;
    public $cid;
    public $roleid;
    public function __construct(User $user){
        $this->user = $user;
        $this->equipment = new Equipment();
        $this->equipmentmodel = new Equ();
        $this->userModel = new User();
        $this->serviceModel = new Servicerequest();
        $this->dashboardModel = new Dashboard();
    }

    public function dashboardCounts(Request $request){

        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $this->roleid = $customer->role_id;
        $reqInputs = $request->json()->all();
        $totalInstruments = $this->dashboardModel->dashboardCounts(array('cus_id'=>$this->cid,'role_id'=>$this->roleid));
        $overdue = $this->dashboardModel->dashboardCounts(array('cus_id'=>$this->cid,'role_id'=>$this->roleid,'overdue'=>true));
        $upcoming = $this->dashboardModel->dashboardCounts(array('cus_id'=>$this->cid,'role_id'=>$this->roleid,'upcoming'=>true));
        $temp = array();
        $temp[0]['name']='totalInstruments';
        $temp[0]['value']=$totalInstruments;
        $temp[0]['title']='Instruments';
        $temp[1]['name']='overdue';
        $temp[1]['value']=$overdue;
        $temp[1]['title']='Overdue';
        $temp[2]['name']='upcoming';
        $temp[2]['value']=$upcoming;
        $temp[2]['title']='Upcoming';

        $totalAmount = $this->dashboardModel->customerPayment(array('cus_id'=>$this->cid,'payment'=>'all'));
        $totalAmountPaid = $this->dashboardModel->customerPayment(array('cus_id'=>$this->cid,'payment'=>'paid'));
        $pendingAmount = $totalAmount-$totalAmountPaid;
        $payment = array();
        $payment['totalAmount'] = $totalAmount;
        $payment['paidAmount'] = $totalAmountPaid;
        $payment['pendingAmount'] = $pendingAmount;


        return Response::json([
            'status' => 1,
            'data' => $temp,
            'calibration_payment_details'=>$payment
        ], 200);
    }


    public function dashboardDueLists(Request $request)
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
        $this->roleid = $customer->role_id;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
        $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $fParams['startCallDate'] = (isset($reqInputs['startCallDate']) && $reqInputs['startCallDate']) ? date('Y-m-d',strtotime(str_replace('/','-',$reqInputs['startCallDate']))) : '';
        $fParams['endCallDate'] = (isset($reqInputs['endCallDate']) && $reqInputs['endCallDate']) ? date('Y-m-d',strtotime(str_replace('/','-',$reqInputs['endCallDate']))): '';
        $fParams['monthYear'] = isset($reqInputs['monthYear']) ? $reqInputs['monthYear'] : '';
        $equipments = $this->dashboardModel->dueequipments($fParams['limit'], $fParams['offset'], array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'F.name as call_frequency', 'E.id', 'search' => $fParams['keyword'],'status' => $fParams['status'],'startCallDate' => $fParams['startCallDate'],'endCallDate' => $fParams['endCallDate'],'monthYear' => $fParams['monthYear'], 'cus_id' => $this->cid,'calibrate_process'=>1,'role_id'=>$this->roleid));
        //echo '<pre>';print_r($equipments);die;
        // $countequipments = $this->equipment->countequipments(array('select' => 'E.name', 'E.asset_no', 'E.serial_no', 'E.customer_id', 'E.pref_contact', 'E.pref_tel', 'E.pref_email', 'E.location', 'search' => $fParams['keyword']), $this->cid);
        $temp = array();
        if($equipments)
        {
            foreach ($equipments as $key=>$row)
            {
                $getModelId = $this->userModel->getEquipmet($row->equipment_id);
                $getModel = $this->equipmentmodel->getmodel($getModelId->equipment_model_id);
                $getplans = $this->userModel->customerSetups($this->cid);
                $explodePlans = (isset($getplans->plan_id) && $getplans->plan_id)?explode(',',$getplans->plan_id):array();
                $plan_id = (isset($explodePlans[0]) && $explodePlans[0])?$explodePlans[0]:1;
                $data['volume'] = $getModel->volume;
                $data['operation'] = $getModel->brand_operation;
                $data['channel'] = $getModel->channel;
                $data['channelnumber'] = $getModel->channel_number;
                $data['plan_id'] = $plan_id;
                $channel = $getModel->channel_number;
                //$pricing = $this->userModel->pricingcreteria($plan_id,$channel);
                $pricing = $this->userModel->pricingcreteriasingle($data);
                $temp[$key] = (array)$row;
                if($row->next_due_date > date('Y-m-d'))
                {
                    $temp[$key]['due_status'] = 'upcoming';
                }
                else
                {
                    $temp[$key]['due_status'] = 'overdue';
                }

                $temp[$key]['price'] =(isset($pricing->price)&&$pricing->price)?$pricing->price:0;
                $temp[$key]['plan_id'] =$plan_id;
                $temp[$key]['pricing_criteria_id'] =(isset($pricing->pricing_id)&&$pricing->pricing_id)?$pricing->pricing_id:0;
                $temp[$key]['plan_name'] =(isset($row->service_plan_name)&&$row->service_plan_name)?$row->service_plan_name:0;
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
            'data' => $temp

        ], 200);
    }
}