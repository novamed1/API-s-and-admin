<?php

namespace App\Http\Controllers\web\technician;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Device;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Sentinel\User;
use App\Models\Technician;
use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Mail;
use GuzzleHttp\Client;
use App\PHPMailer;


class TechnicianController extends Controller
{
    protected $mail;

    public function __construct()
    {
        $this->technician = new Technician();
        $this->customer = new Customer();
        $this->mail = new PHPMailer();
        $this->device = new Device();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Technician';
        //echo'<pre>';print_r($input);die;
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $postvalue = isset($input['postvalue']) ? $input['postvalue'] : '';
        $posttestplanid = isset($input['postTestPlanId']) ? $input['postTestPlanId'] : '';
        $data['search']['keyword'] = $keyword;

        if ($keyword) {
            $select = array('tt.*');
            $data = $this->technician->AllTechnicians('', '', 'tt.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tt.first_name', 'tt.last_name', 'tt.phone_number', 'tt.email', 'tt.address', 'tt.city', 'tt.zip_code'));

        } else {
            $select = array('tt.*');
            $data = $this->technician->AllTechnicians('', '', 'tt.id', 'DESC', array('select' => $select), false);

        }

//        echo '<pre>';print_r($data);die;

//        if ($data) {
//            foreach ($data as $key => $value) {
//                $temp[$key]['id'] = $value->id;
//                $temp[$key]['device_model_id'] = $value->device_model_id;
//                $temp[$key]['id'] = $value->id;
//            }
//        }


        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );

        $userDetail->setPath($request->url());

        if ($request->ajax()) {
            $view = view('technician.technicianlistajax', ['data' => $paginatedItems])->render();

        }

        return view('technician.technicianlist')->with('title', $title)->with('data', $paginatedItems)->with('postvalue', $postvalue)->with('posttestplanid', $posttestplanid);


    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['first_name'] = isset($input['sSearch_0'])?$input['sSearch_0']:'';
        $search['phone_number'] = isset($input['sSearch_1'])?$input['sSearch_1']:'';
        $search['email'] = isset($input['sSearch_2'])?$input['sSearch_2']:'';
        $search['address'] = isset($input['sSearch_3'])?$input['sSearch_3']:'';
        $search['city'] = isset($input['sSearch_4'])?$input['sSearch_4']:'';
        $search['state'] = isset($input['sSearch_5'])?$input['sSearch_5']:'';
        $search['zip_code'] = isset($input['sSearch_6'])?$input['sSearch_6']:'';
        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('tt.*');
        $data = $this->technician->AllTechniciansGrid($param['limit'], $param['offset'], 'tt.id', 'DESC', array('select' => $select, 'search' => $search),
            false);

        $count = $this->technician->AllTechniciansGrid($param['limit'], $param['offset'], 'tt.id', 'DESC', array('select' => $select, 'search' => $search,'count'=>true),
            true);
        if($data)
        { $values = array();
            $i = 0;
            foreach ($data as $key=>$row)
            {
                $values[$key]['0'] = $row->first_name.' '.$row->last_name;
                $values[$key]['1'] = $row->phone_number;
                $values[$key]['2'] = $row->email;
                $values[$key]['3'] = $row->address;
                $values[$key]['4'] = $row->city;
                $values[$key]['5'] = $row->state;
                $values[$key]['6'] = $row->zip_code;
                $values[$key]['7'] = "<a href=".url('admin/editTechnician/'.$row->id)." class=''><i class='fa fa-pencil'></a>";
                $values[$key]['8'] = " <a href='javascript:void(0)' data-src=" . url('admin/deleteTechnician/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";

                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho'=>$input['sEcho'],'iTotalRecords'=>$count,'iTotalDisplayRecords'=>$count,'aaData'=>$values));

    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Technician Creation';


        $data = [
            'id' => $id,
            'firstName' => isset($input['firstName']) ? $input['firstName'] : '',
            'lastName' => isset($input['lastName']) ? $input['lastName'] : '',
            'email' => isset($input['email']) ? $input['email'] : '',
            'phoneNumber' => isset($input['phoneNumber']) ? $input['phoneNumber'] : '',
            'address' => isset($input['address']) ? $input['address'] : '',
            'city' => isset($input['city']) ? $input['city'] : '',
            'state' => isset($input['state']) ? $input['state'] : '',
            'zip_code' => isset($input['zip_code']) ? $input['zip_code'] : '',
            'password' => isset($input['password']) ? $input['password'] : '',

        ];


        if ($id) {
            $gettechnician = $this->technician->getTechnician($data['id']);
            $user = $gettechnician->user_id;
            $data['id'] = $gettechnician->id;
            $data['user_id'] = $gettechnician->user_id;
            $data['firstName'] = $gettechnician->first_name;
            $data['lastName'] = $gettechnician->last_name;
            $data['email'] = $gettechnician->email;
            $data['phoneNumber'] = $gettechnician->phone_number;
            $data['address'] = $gettechnician->address;
            $data['city'] = $gettechnician->city;
            $data['state'] = $gettechnician->state;
            $data['zip_code'] = $gettechnician->zip_code;
            $data['password'] = $gettechnician->password;

        }
        $requiredFields = [
            'firstName' => isset($input['firstName']) ? $input['firstName'] : '',
            'email' => isset($input['email']) ? $input['email'] : '',
            'phoneNumber' => isset($input['phoneNumber']) ? $input['phoneNumber'] : '',
            'address' => isset($input['address']) ? $input['address'] : '',
            'zip_code' => isset($input['zip_code']) ? $input['zip_code'] : '',
            'password' => isset($input['password']) ? $input['password'] : '',

        ];
        if ($id) {
            $rules = [
                'firstName' => 'required',
                'phoneNumber' => 'required|numeric|digits:10|unique:tbl_technician,phone_number,' . $data['id'] . ',id',
//                'zip_code' => 'required|numeric|digits:6',
                'address' => 'required',
//                'password' => 'required',
            ];
        } else {
            $rules = [
                'firstName' => 'required',
                'email' => 'required|email|unique:tbl_users,email',
                'phoneNumber' => 'required|numeric|digits:10|unique:tbl_technician,phone_number,' . $data['id'] . ',id',
//                'zip_code' => 'required|numeric|digits:6',
                'address' => 'required',
                'password' => 'required',
            ];
        }


        $error = array();
        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($requiredFields, $rules);

            if ($checkValid->fails()) {
                $checkStatus = true;
                $error = $checkValid->errors()->all();
            }
        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
        }
        $passwordGeneration = str_random(8);
        if ($checkStatus) {

            return view('technician.technicianForm')->with('title', $title)->with('input', $data)->with('errors', $error);
        } else {
            $post = Input::all();

            if (!$id) {
                $userId = Sentinel::register(array(
                    'name' => $post['firstName'],
                    'email' => $post['email'],
                    'first_name' => $post['firstName'],
                    'last_name' => $post['lastName'],
                    'password' => $post['password'],
                ));
                if ($userId['id']) {
                    $saveGroup = array();
                    $saveGroup['id'] = false;
                    $saveGroup['user_group_id'] = 3;
                    $saveGroup['users_id'] = $userId['id'];
                    $groupUser = $this->customer->saveUserGroup($saveGroup);
                }
            } else {
                $userId = array(
                    'name' => $post['firstName'],
//                    'email' => $post['email'],
                    'first_name' => $post['firstName'],
                    'last_name' => $post['lastName'],
                    'password' => $post['password'],
                );
                $users = Sentinel::update($user, $userId);
            }


            $saveCustomer['id'] = $id;
            if (!$id) {
                $saveCustomer['user_id'] = $userId['id'];
            } else {
                $saveCustomer['user_id'] = $user;
            }

            $saveCustomer['first_name'] = $post['firstName'];
            $saveCustomer['last_name'] = $post['lastName'];
            $saveCustomer['email'] = $post['email'];
            $saveCustomer['phone_number'] = $post['phoneNumber'];
            $saveCustomer['address'] = $post['address'];
            $saveCustomer['city'] = $post['city'];
            $saveCustomer['state'] = $post['state'];
            $saveCustomer['zip_code'] = $post['zip_code'];
//            $saveCustomer['password'] = $post['password'];

            $saveTechnician = $this->technician->saveTechnician($saveCustomer);

//            if (!$id) {
//                $mailMessage = 'Dear Technician,<br /><br />
//Your Password for Novamed Customer Panel<br /><br />
//Username : ' . $post['email'] . '<br /><br />
//Password : ' . $post['password'] . '<br /><br />
//Regards,<br /><br />
//The Team @ Novamed<br /><br />';
////
////
//                $mailMessages = str_replace('{username}', $post['email'], $mailMessage);
//                $mailMessages = str_replace('{password}', $post['password'], $mailMessage);
//                $subject = 'Login for Technician Portal';
//
//
//                \Mail::send(['html' => 'email'], ['temp' => $mailMessages], function ($message) use ($mailMessage, $subject, $post) {
//
//                    $message->to($post['email'])->subject($subject);
//
//                });
//            }

            if (!$id) {
                $query = DB::table('tbl_email_template');
                $query->where('tplid', '=', 15);
                $result = $query->first();
                $result->tplmessage = str_replace('{SITE_PATH}', "http://54.188.82.17/technician", $result->tplmessage);
                $result->tplmessage = str_replace('{username}', $post['email'], $result->tplmessage);
                $result->tplmessage = str_replace('{password}', $post['password'], $result->tplmessage);
                $result->tplmessage = str_replace('{name}', $post['firstName'], $result->tplmessage);

                $param['message'] = $result->tplmessage;
                $param['name'] = $post['firstName'] . ' ' . $post['lastName'];
                $param['email'] = $post['email'];
                $param['title'] = $result->tplsubject;

                $view = View::make('email/template', ['data' => $param]);

                $formData = $view->render();

                Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param) {
                    $message->to($param['email'])->subject
                    ($param['title']);
                });
            }

            if (!$id) {
                $message = Session::flash('message', 'Technician has been created');

            } else {
                $message = Session::flash('message', 'Technician has been updated');

            }

            return redirect('admin/technicianlist')->with(['data', $message], ['message', $message]);


        }
    }

    public function assignTechnicianforModel(Request $request)
    {

        $input = Input::all(); //print_r($input);die;

        if (!$input) {
            die(json_encode(array('result' => false)));
        }

        $techinicians = isset($input['technicians'])?$input['technicians']:array();
        $deviceId = $input['deviceId'];
        $deleteTechinicians = $this->technician->reassignTechnician($deviceId);
        if ($techinicians) {
            foreach ($techinicians as $teckkey) {

                $save['id'] = false;
                $save['device_id'] = $deviceId;
                $save['technician_id'] = $teckkey;

                $assignTechnician = $this->technician->assignTechnicianforDevice($save);
            }

        }

        die(json_encode(array('result' => true)));

    }

    public function checkTechnicianforDevice(Request $request)
    {

        $input = Input::all();

        if (!$input) {
            die(json_encode(array('result' => false)));
        }

        $deviceId = $input['deviceId'];

        if ($deviceId) {

//            get device details
            $getDevice = $this->device->getdevice($deviceId);

            if (!$getDevice) {
                die(json_encode(array('result' => false)));
            }
            $deviceModelId = $getDevice->device_model_id;

//            already assigned technician for this device
            $getTechnicians = $this->technician->getTechnicianforDevice($deviceId);


//            unassigned technicians to assign the new technician
            $technician = $this->technician->unassignedTechnician($deviceId, $deviceModelId);

//            echo '<pre>';
//            print_r($technician);
//            die;

            $temp = array();
            if ($getTechnicians) {
                foreach ($getTechnicians as $techkry => $techval) {
                    $temp[$techkry]['technicianId'] = $techval->technician_id;
                }
            }
            $alreadyAssignedTechinician = array_column($temp, 'technicianId');
//            echo '<pre>';
//            print_r($alreadyAssignedTechinician);
//            die;


            $view = View::make('master.device.technicianList', ['alreadyTech' => $alreadyAssignedTechinician, 'technician' => $technician]);
            $formData = $view->render();
            if(count($technician))
            {
                die(json_encode(array("result" => true, "formData" => trim($formData))));
            }
            else{
                die(json_encode(array("result" => false)));
            }


        }

        die(json_encode(array('result' => true)));

    }
    public
    function delete($id)
    {

        $getdetail = $this->technician->getTechnician($id);


        if ($getdetail) {

            $getSubdetail = DB::table('tbl_work_order')->where('maintanence_to', '=', $id)->select('*')->first();
            $getSub = DB::table('tbl_work_order')->where('calibration_to', '=', $id)->select('*')->first();
            if ($getSubdetail) {
                $message = Session::flash('error', "You can't able to delete this technician");
                return redirect('admin/technicianlist')->with(['data', $message], ['message', $message]);
            }
            if ($getSub) {
                $message = Session::flash('error', "You can't able to delete this technician");
                return redirect('admin/technicianlist')->with(['data', $message], ['message', $message]);
            }
//            echo '<pre>';print_R($getdetail);exit;
            $message = Session::flash('message', 'Deleted Successfully!');
            $member = $this->technician->deleteTechnician($id);
            $member = $this->customer->deleteGroupUser($getdetail->user_id);

            return redirect('admin/technicianlist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/technicianlist')->with('data', $error);
        }
    }

}
