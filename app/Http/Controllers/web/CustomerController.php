<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Sentinel\User;

use App\Models\Customer;
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

//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;


//use Request;

class CustomerController extends Controller
{
    protected $mail;

    public function __construct()
    {
        $this->customer = new Customer();
        //  $this->mail = new PHPMailer();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Customer';
        //echo'<pre>';print_r($input);die;
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $postvalue = isset($input['postvalue']) ? $input['postvalue'] : '';
        $posttestplanid = isset($input['postTestPlanId']) ? $input['postTestPlanId'] : '';
        $data['search']['keyword'] = $keyword;

        if ($keyword) {
            $select = array('c.id as customerId', 'c.customer_name', 't.name', 'c.customer_type_id', 'c.state', 'c.city', 'c.customer_email');
            $data = $this->customer->AllCustomer('', '', 'c.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('c.customer_name', 'c.state', 'c.city', 'c.customer_email'));

        } else {
            $select = array('c.id as customerId', 'c.customer_name', 't.name', 'c.customer_type_id', 'c.state', 'c.city', 'c.customer_email');
            $data = $this->customer->AllCustomer('', '', 'c.id', 'DESC', array('select' => $select), false);

        }


        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );

        $userDetail->setPath($request->url());

        return view('customer.customerlist')->with('title', $title)->with('data', $paginatedItems)->with('postvalue', $postvalue)->with('posttestplanid', $posttestplanid);


    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $proxy = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;

        $search['customer_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['unique_id'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['name'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['customer_email'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['customer_telephone'] = isset($input['sSearch_6']) ? $input['sSearch_6'] : '';
        $search['state'] = isset($input['sSearch_7']) ? $input['sSearch_7'] : '';
        $search['city'] = isset($input['sSearch_8']) ? $input['sSearch_8'] : '';

        $select = array('c.id as customerId', 'c.unique_id', 'c.customer_name', 't.name', 'c.customer_type_id', 'c.state', 'c.city', 'c.customer_email', 'c.customer_telephone', 'c.address1', 'c.address2', 'c.user_id');
        $data = $this->customer->AllCustomerGrid($param['limit'], $param['offset'], 'c.id', 'DESC', array('select' => $select, 'search' => $search),
            false);

        $count = $this->customer->AllCustomerGrid($param['limit'], $param['offset'], 'c.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true),
            true);
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {

                $access = DB::table('tbl_group_user')->where('users_id', '=', $row->user_id)->where('user_group_id', '=', 2)->select('*')->first();
                $checkUser = DB::table('tbl_users')->where('id', '=', $row->user_id)->select('*')->first();
                $accessPortal = DB::table('tbl_group_user')->where('users_id', '=', $row->user_id)->where('user_group_id', '=', 2)->select('*')->first();
                if($checkUser)
                {
                    $mailVerified = $checkUser->is_email_verified==1?'Yes':'No';
                }
                else
                {
                    $mailVerified = 'No';
                }
//                print_r($row);exit;
                $proxy[0] = $row->user_id;
                $proxy[1] = $row->customer_email;
                $test = base64_encode(serialize($proxy));
                $values[$key]['0'] = $row->customer_name;
                $values[$key]['1'] = $row->unique_id;
                $values[$key]['2'] = $row->name;
                if ($access) {
                    $values[$key]['3'] = "<span class='Verify'>$row->customer_email</span>";
                } else {
                    $values[$key]['3'] = "<span class='notVerify' id='verify_{$row->user_id}'>$row->customer_email</span>&nbsp; <label class='switch' id='switch_{$row->user_id}'><input type='checkbox' name='email_verify' class='email_verify' id='email_verify' data-id='$row->user_id' title='Verify Email'><span class='slider round'></span></label>";

                }
                $row->customer_email;
                $values[$key]['4'] = $mailVerified;


                if ($access) {
                    $values[$key]['5'] = "<span class=''>Yes</span>";
                } else {
                    $values[$key]['5'] = "<label class='switch' id='accessportal_{$row->user_id}'><input type='checkbox' name='access_portal' class='access_portal' id='access_portal' data-id='$row->user_id' title='Access to customer portal'><span class='slider round'></span></label>";

                }

                $values[$key]['6'] = $row->customer_telephone;
                $values[$key]['7'] = $row->state;
                $values[$key]['8'] = $row->city;
                $values[$key]['9'] = "<a href='javascript:void(0);' data-id= " . $row->customerId . " class='viewCustomerdetails'><i class='fa fa-eye'></a>";
                $values[$key]['10'] = "<a href=" . url('admin/editcustomer/' . $row->customerId) . "><i class='fa fa-pencil'></a>";
                $values[$key]['11'] = "<a href=" . url('admin/customerSetup/' . $row->customerId) . " class=''><i class='fa fa-cog'></a>";
                $values[$key]['12'] = "<a href=" . url('customer/#/proxy/' . $test) . " target='_blank'>
                                                              
                                                                        <i class='fa fa-sign-in'
                                                                           aria-hidden='true'></i></a>";
                $values[$key]['13'] = "<a href='javascript:void(0)' data-src=" . url('admin/deletecustomer/' . $row->customerId) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
//                $values[$key]['13'] = " ";

                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


    public function form(Request $request, $id = false)
    {
//        $param['message'] = 'hai';
//        $param['title'] = 'Test email';


//        Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param) {
//            $message->to('bigtruck-noreply@bigtruck.in')->subject
//            ($param['title']);
//        }); die;
        $input = Input::all();
//        echo '<pre>';print_r($input);exit;
        $title = 'Novamed-Customer Creation';

        $customerType_drop = array();
        $customerType = DB::table('tbl_customer_type')->select('name', 'id')->where('is_active', '=', 1)->get();
        $customerType_drop[0] = 'Please Choose Customer';
        if (count($customerType)) {
            foreach ($customerType as $key => $row) {
                $customerType_drop[$row->id] = $row->name;
            }
        }
//        $customerType = DB::table('tbl_customer_type')->pluck('name', 'id');
//        $customerType->prepend('Please Choose Customer');

        $data = [
            'id' => $id,
            'prefer_contact' => isset($input['prefer_contact']) ? $input['prefer_contact'] : 0,
            'customerCompanyName' => isset($input['customerCompanyName']) ? $input['customerCompanyName'] : '',
            'customerCompanyType' => isset($input['customerCompanyType']) ? $input['customerCompanyType'] : '',
            'customerCompanyAddress1' => isset($input['customerCompanyAddress1']) ? $input['customerCompanyAddress1'] : '',
            'customerCompanyAddress2' => isset($input['customerCompanyAddress2']) ? $input['customerCompanyAddress2'] : '',
            'customerCompanyPrimaryContact' => isset($input['customerCompanyPrimaryContact']) ? $input['customerCompanyPrimaryContact'] : '',
            'customerCompanyCity' => isset($input['customerCompanyCity']) ? $input['customerCompanyCity'] : '',
            'customerCompanyTitle' => isset($input['customerCompanyTitle']) ? $input['customerCompanyTitle'] : '',
            'customerCompanyState' => isset($input['customerCompanyState']) ? $input['customerCompanyState'] : '',
            'customerCompanyTelNo' => isset($input['customerCompanyTelNo']) ? $input['customerCompanyTelNo'] : '',
            'customerCompanyZipCode' => isset($input['customerCompanyZipCode']) ? $input['customerCompanyZipCode'] : '',
            'customerCompanyEmail' => isset($input['customerCompanyEmail']) ? $input['customerCompanyEmail'] : '',
            'customerCompanyMainTelNo' => isset($input['customerCompanyMainTelNo']) ? $input['customerCompanyMainTelNo'] : '',
            'customerCompanyMainFaxNo' => isset($input['customerCompanyMainFaxNo']) ? $input['customerCompanyMainFaxNo'] : '',
            'website' => isset($input['website']) ? $input['website'] : '',
            'customerContactName' => isset($input['customerContactName']) ? $input['customerContactName'] : '',
            'customerContactFaxNo' => isset($input['customerContactFaxNo']) ? $input['customerContactFaxNo'] : '',
            'customerContactTitlePrefContact' => isset($input['customerContactTitlePrefContact']) ? $input['customerContactTitlePrefContact'] : '',
            'customerContactLocation' => isset($input['customerContactLocation']) ? $input['customerContactLocation'] : '',
            'customerDepartment' => isset($input['customerDepartment']) ? $input['customerDepartment'] : '',
            'customerContactEmail' => isset($input['customerContactEmail']) ? $input['customerContactEmail'] : '',
            'customerContactBuildingName' => isset($input['customerContactBuildingName']) ? $input['customerContactBuildingName'] : '',
            'customerContactTelNo' => isset($input['customerContactTelNo']) ? $input['customerContactTelNo'] : '',
            'customerContactRoomNo' => isset($input['customerContactRoomNo']) ? $input['customerContactRoomNo'] : '',
            'customerBillingContactName' => isset($input['customerBillingContactName']) ? $input['customerBillingContactName'] : '',
            'customerBillingCity' => isset($input['customerBillingCity']) ? $input['customerBillingCity'] : '',
            'customerBillingEmail' => isset($input['customerBillingEmail']) ? $input['customerBillingEmail'] : '',
            'customerBillingState' => isset($input['customerBillingState']) ? $input['customerBillingState'] : '',
            'customerBillingAddress1' => isset($input['customerBillingAddress1']) ? $input['customerBillingAddress1'] : '',
            'customerBillingZipCode' => isset($input['customerBillingZipCode']) ? $input['customerBillingZipCode'] : '',
            'customerBillingAddress2' => isset($input['customerBillingAddress2']) ? $input['customerBillingAddress2'] : '',
            'customerBillingTelNo' => isset($input['customerBillingTelNo']) ? $input['customerBillingTelNo'] : '',
            'customerBillingFaxNo' => isset($input['customerBillingFaxNo']) ? $input['customerBillingFaxNo'] : '',
            'customerBillingDepartment' => isset($input['customerBillingDepartment']) ? $input['customerBillingDepartment'] : '',
            'customerBillingDepartmentBuilding' => isset($input['customerBillingDepartmentBuilding']) ? $input['customerBillingDepartmentBuilding'] : '',
            'customerBillingRoom' => isset($input['customerBillingRoom']) ? $input['customerBillingRoom'] : '',
            'BillingMailCode' => isset($input['BillingMailCode']) ? $input['BillingMailCode'] : '',
            'customerShippingName' => isset($input['customerShippingName']) ? $input['customerShippingName'] : '',
            'customerShippingCity' => isset($input['customerShippingCity']) ? $input['customerShippingCity'] : '',
            'customerShippingEmail' => isset($input['customerShippingEmail']) ? $input['customerShippingEmail'] : '',
            'customerShippingState' => isset($input['customerShippingState']) ? $input['customerShippingState'] : '',
            'customerShippingAddress1' => isset($input['customerShippingAddress1']) ? $input['customerShippingAddress1'] : '',
            'customerShippingZipCode' => isset($input['customerShippingZipCode']) ? $input['customerShippingZipCode'] : '',
            'customerShippingAddress2' => isset($input['customerShippingAddress2']) ? $input['customerShippingAddress2'] : '',
            'customerShippingTelNo' => isset($input['customerShippingTelNo']) ? $input['customerShippingTelNo'] : '',
            'customerShippingFaxNo' => isset($input['customerShippingFaxNo']) ? $input['customerShippingFaxNo'] : '',
            'customerShippingDepartment' => isset($input['customerShippingDepartment']) ? $input['customerShippingDepartment'] : '',
            'customerShippingDepartmentBuilding' => isset($input['customerShippingDepartmentBuilding']) ? $input['customerShippingDepartmentBuilding'] : '',
            'customerShippingRoom' => isset($input['customerShippingRoom']) ? $input['customerShippingRoom'] : '',
            'billingSame' => isset($input['billingSame']) ? $input['billingSame'] : '',
            'shippingSame' => isset($input['shippingSame']) ? $input['shippingSame'] : '',
            'shippingMailCode' => isset($input['shippingMailCode']) ? $input['shippingMailCode'] : '',

        ];
//        echo'<pre>';print_r($data);'</pre>';die;
        $states = DB::table('tbl_state')
            ->orderBy('state_name', 'ASC')
            ->pluck('state_name', 'state_name');
        $states->prepend('-Select State', '');

        $city = DB::table('tbl_city')
            ->orderBy('city_name', 'ASC')
            ->pluck('city_name', 'city_name');

        $city->prepend('-Select City', '');


        if ($id) {

            $getCustomer = $this->customer->getCustomer($id);
            $getcontact = $this->customer->getCompanyContact($id);
            $getCustomerBilling = $this->customer->getCustomerBilling($id);
            $getCustomerShipping = $this->customer->getCustomerShipping($id);
//            echo '<pre>';print_r($getCustomerShipping);die;
            $data['prefer_contact'] = $getCustomer->prefer_contact;
            $data['customerCompanyName'] = $getCustomer->customer_name;
            $data['customerCompanyType'] = $getCustomer->customer_type_id;
            $data['customerCompanyAddress1'] = $getCustomer->address1;
            $data['customerCompanyAddress2'] = $getCustomer->address2;
            $data['customerCompanyCity'] = $getCustomer->city;
            $data['customerCompanyState'] = $getCustomer->state;
            $data['customerCompanyZipCode'] = $getCustomer->zip_code;
            $data['customerCompanyTitle'] = $getCustomer->title;
            $data['customerCompanyTelNo'] = $getCustomer->customer_telephone;
            $data['customerCompanyEmail'] = $getCustomer->customer_email;
            $data['customerCompanyMainTelNo'] = $getCustomer->customer_main_telephone;
            $data['customerCompanyMainFaxNo'] = $getCustomer->customer_main_fax;
            $data['customerCompanyPrimaryContact'] = $getCustomer->primary_contact;
            $data['website'] = $getCustomer->customer_website;


            $data['customerContactName'] = isset($getcontact->contact_name) ? $getcontact->contact_name : '';
            $data['customerContactTelNo'] = isset($getcontact->phone_no) ? $getcontact->phone_no : '';
            $data['customerContactEmail'] = isset($getcontact->email_id) ? $getcontact->email_id : '';

            $data['customerContactTitlePrefContact'] =  isset($getcontact->title) ? $getcontact->title : '';
            $data['customerContactLocation'] = isset($getcontact->location) ? $getcontact->location : '';
            $data['customerDepartment'] = isset($getcontact->department) ? $getcontact->department : '';
            $data['customerContactFaxNo'] = isset($getcontact->fax_number) ? $getcontact->fax_number : '';
            $data['customerContactBuildingName'] = isset($getcontact->building_name) ? $getcontact->building_name : '';
            $data['customerContactRoomNo'] = isset($getcontact->room_number) ? $getcontact->room_number : '';

            $data['customerBillingContactName'] = $getCustomerBilling->billing_contact;
            $data['customerBillingAddress1'] = $getCustomerBilling->address1;
            $data['customerBillingAddress2'] = $getCustomerBilling->address2;
            $data['customerBillingCity'] = $getCustomerBilling->city;
            $data['customerBillingState'] = $getCustomerBilling->state;
            $data['customerBillingZipCode'] = $getCustomerBilling->zip_code;
            $data['customerBillingTelNo'] = $getCustomerBilling->phone;
            $data['customerBillingFaxNo'] = $getCustomerBilling->fax;
            $data['customerBillingEmail'] = $getCustomerBilling->email;
            $data['billingSame'] = $getCustomerBilling->same_as_company;
            $data['customerBillingDepartment'] = $getCustomerBilling->department;
            $data['customerBillingDepartmentBuilding'] = $getCustomerBilling->building_name;
            $data['customerBillingRoom'] = $getCustomerBilling->room_no;
            $data['BillingMailCode'] = $getCustomerBilling->mail_code;


            $data['customerShippingName'] = $getCustomerShipping->customer_name;

            $data['customerShippingAddress1'] = $getCustomerShipping->address1;
            $data['customerShippingAddress2'] = $getCustomerShipping->address2;
            $data['customerShippingCity'] = $getCustomerShipping->city;
            $data['customerShippingState'] = $getCustomerShipping->state;
            $data['customerShippingZipCode'] = $getCustomerShipping->zip_code;
            $data['customerShippingTelNo'] = $getCustomerShipping->phone_num;
            $data['customerShippingFaxNo'] = $getCustomerShipping->fax;
            $data['customerShippingEmail'] = $getCustomerShipping->email;
            $data['shippingSame'] = $getCustomerShipping->same_as_company;
            $data['customerShippingDepartment'] = $getCustomerShipping->department;
            $data['customerShippingDepartmentBuilding'] = $getCustomerShipping->building_name;
            $data['customerShippingRoom'] = $getCustomerShipping->room_no;
            $data['shippingMailCode'] = $getCustomerShipping->mail_code;


        }

//        echo '<pre>';print_r($id);die;
        $requiredFields = [
            'customerCompanyName' => isset($input['customerCompanyName']) ? $input['customerCompanyName'] : '',
            'customerCompanyType' => isset($input['customerCompanyType']) ? $input['customerCompanyType'] : '',
            'email' => isset($input['customerCompanyEmail']) ? $input['customerCompanyEmail'] : '',
            'pref_email' => isset($input['customerContactEmail']) ? $input['customerContactEmail'] : ''

        ];
        if ($id) {
            $customerNotes = DB::table('tbl_users');
            $customerNotes->limit(1);
            $customerNotes->where('customer_id',$id);
            $customerNotes->orderBy('id', 'ASC');
            $customerNotes->select('id');
            $result = $customerNotes->get()->first();
            $user_id = (isset($result->id)&&$result->id)?$result->id:''; //print_r($user_id);die;

            $customerContacts = DB::table('tbl_customer_contacts');
            $customerContacts->limit(1);
            $customerContacts->where('customer_id',$id);
            $customerContacts->orderBy('id', 'ASC');
            $customerContacts->select('user_id');
            $result1 = $customerContacts->get()->first();
            $users_id = (isset($result1->user_id)&&$result1->user_id)?$result1->user_id:'';

            $rules = [
                'customerCompanyName' => 'required',
                'customerCompanyType' => 'required',
                'email' => 'required|max:255|unique:tbl_users,email,'.$user_id,
                'pref_email' => 'required|max:255|unique:tbl_users,email,'.$users_id,
            ];
        } else {
            $rules = [
                'customerCompanyName' => 'required',
                'customerCompanyType' => 'required',
                'email' => 'required|unique:tbl_users|max:255',
                'pref_email' => 'unique:tbl_users,email|max:255',
            ];
        }

        //echo '<pre>';print_r($data);exit;
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

            return view('customer.customerForm')->with('title', $title)->with('input', $data)->with('customerType', $customerType_drop)->with('errors', $error)->with('states', $states)->with('city', $city);
        } else {
            $post = Input::all();
            $usercontactUserId = null;

//            echo '<pre>';print_r($post);die;


            if (!$id) {
                $userId = Sentinel::register(array(
                    'name' => $post['customerCompanyPrimaryContact'],
                    'email' => $post['customerCompanyEmail'],
                    'password' => $passwordGeneration,
                ));
                $saveGroup = array();
                $saveGroup['id'] = false;
                $saveGroup['user_group_id'] = 2;
                $saveGroup['role_id'] = 1;
                $saveGroup['users_id'] = $userId['id'];
                $groupUser = $this->customer->saveUserGroup($saveGroup);

                if (isset($input['customerContactEmail']) && $input['customerContactEmail']) {
                    $contactpasswordGeneration = str_random(8);
                    $usercontactId = Sentinel::register(array(
                        'name' => $input['customerContactName'],
                        'email' => $input['customerContactEmail'],
                        'password' => $contactpasswordGeneration,
                    ));

                    $saveGroupContact = array();
                    $saveGroupContact['id'] = false;
                    $saveGroupContact['user_group_id'] = 2;
                    $saveGroupContact['role_id'] = 2;
                    $saveGroupContact['users_id'] = $usercontactId['id'];
                    $groupUserContact = $this->customer->saveUserGroup($saveGroupContact);

//                    $query = DB::table('tbl_email_template');
//                    $query->where('tplid', '=', 4);
//                    $result = $query->first();
//                    $result->tplmessage = str_replace('{name}', $post['customerCompanyName'], $result->tplmessage);
//                    $result->tplmessage = str_replace('{user_name}', $post['customerCompanyEmail'], $result->tplmessage);
//                    $result->tplmessage = str_replace('{password}', $passwordGeneration, $result->tplmessage);
//                    $result->tplmessage = str_replace('{link}', env('url_path'), $result->tplmessage);
//
//                    $param['message'] = $result->tplmessage;
//                    $param['title'] = $result->tplsubject;
//
//
//                    Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param, $post) {
//                        $message->to($post['customerCompanyEmail'])->subject
//                        ($param['title']);
//                    });


                    $usercontactUserId = $usercontactId['id'];
                }
            }


            if($id)
            {
                if(isset($input['customerCompanyEmail']) && $input['customerCompanyEmail'])
                {
                    $customerNotes = DB::table('tbl_users');
                    $customerNotes->limit(1);
                    $customerNotes->where('customer_id',$id);
                    $customerNotes->orderBy('id', 'ASC');
                    $customerNotes->select('id');
                    $result = $customerNotes->get()->first();
                    if($result)
                    {
                        $userUpdate['id'] = $result->id;
                        $userUpdate['name'] = $post['customerCompanyPrimaryContact'];
                        $userUpdate['email'] = $post['customerCompanyEmail'];
                        $userUpdate['telephone'] = $post['customerCompanyMainTelNo'];
                        $this->customer->saveUser($userUpdate);
                    }

                }

                if (isset($input['customerContactEmail']) && $input['customerContactEmail']) {
                    $customerContacts = DB::table('tbl_customer_contacts');
                    $customerContacts->limit(1);
                    $customerContacts->where('customer_id',$id);
                    $customerContacts->orderBy('id', 'ASC');
                    $customerContacts->select('user_id');
                    $result2 = $customerContacts->get()->first(); //print_r($result2);die;

                    if($result2)
                    {
                        $userUpdate1['id'] = $result2->user_id;
                        $userUpdate1['name'] = $post['customerContactName'];
                        $userUpdate1['email'] = $post['customerContactEmail'];
                        $userUpdate1['telephone'] = $post['customerContactTelNo'];
                        $this->customer->saveUser($userUpdate1);
                        $usercontactUserId = $result2->user_id;



                    }

                    else
                    {
                        $userUpdate1['id'] = false;
                        $userUpdate1['name'] = $post['customerContactName'];
                        $userUpdate1['email'] = $post['customerContactEmail'];
                        $userUpdate1['telephone'] = $post['customerContactTelNo'];
                        $userUpdate1['customer_id'] = $id;
                        $useradded = $this->customer->saveUser($userUpdate1);
                        $usercontactUserId = $useradded;

                        $saveGroupContact = array();
                        $saveGroupContact['id'] = false;
                        $saveGroupContact['user_group_id'] = 2;
                        $saveGroupContact['role_id'] = 2;
                        $saveGroupContact['users_id'] = $usercontactUserId;
                        $groupUserContact = $this->customer->saveUserGroup($saveGroupContact);
                    }

                    //need to add

                }
            }





            $saveCustomer['id'] = $id;
//            if ($id) {
//                $saveCustomer['user_id'] = $getCustomer->id;
//            } else {
//                $saveCustomer['user_id'] = $userId['id'];
//            }

            if (!$id) {
                $saveCustomer['user_id'] = $userId['id'];
            }


            $query = DB::table('tbl_customer');
            $query->limit(1);
            $query->orderBy('id', 'DESC');
            $query->select('id');
            $result = $query->get()->first();
            $typequery = DB::table('tbl_customer_type');
            $typequery->where('id', '=', $post['customerCompanyType']);
            $typequery->select('name');
            $company_type_result = $typequery->get()->first();
            $company_name = (isset($company_type_result->name) && $company_type_result->name) ? strtoupper(substr($company_type_result->name, 0, 3)) : '';
            $result_id = (isset($result->id) && ($result->id)) ? $result->id : 0;
            $customer_unique_id = 'CUS' . $company_name . str_pad($result_id + 1, 3, '0', STR_PAD_LEFT);
            if (!$id) {
                $saveCustomer['unique_id'] = $customer_unique_id;
            }
            if (!$id) {
                $saveCustomer['prefer_contact'] = 1;
            } else {
                $saveCustomer['prefer_contact'] = ($post['prefer_contact'] == 0) ? 1 : $post['prefer_contact'];

            }
            $saveCustomer['customer_name'] = $post['customerCompanyName'];
            $saveCustomer['customer_type_id'] = $post['customerCompanyType'];
            $saveCustomer['address1'] = $post['customerCompanyAddress1'];
            $saveCustomer['address2'] = $post['customerCompanyAddress2'];
            $saveCustomer['city'] = $post['customerCompanyCity'];
            $saveCustomer['state'] = $post['customerCompanyState'];
            $saveCustomer['zip_code'] = $post['customerCompanyZipCode'];
            $saveCustomer['title'] = $post['customerCompanyTitle'];
            $saveCustomer['customer_telephone'] = $post['customerCompanyTelNo'];
            $saveCustomer['customer_email'] = $post['customerCompanyEmail'];
            $saveCustomer['customer_main_telephone'] = $post['customerCompanyMainTelNo'];
            $saveCustomer['primary_contact'] = $post['customerCompanyPrimaryContact'];
            $saveCustomer['customer_main_fax'] = $post['customerCompanyMainFaxNo'];
            $saveCustomer['customer_website'] = $post['website'];
            $customerDetails = $this->customer->saveCustomer($saveCustomer);
            if ($customerDetails) {
                if ($id) {
                    $saveContact['id'] = (isset($getcontact->id) && ($getcontact->id)) ? $getcontact->id : false;
                } else {
                    $saveContact['id'] = false;
                }
                $saveContact['customer_id'] = $customerDetails;
                $saveContact['user_id'] = $usercontactUserId;
                $saveContact['contact_name'] = isset($post['customerContactName']) ? $post['customerContactName'] : '';
                $saveContact['phone_no'] = isset($post['customerContactTelNo']) ? $post['customerContactTelNo'] : '';
                $saveContact['email_id'] = isset($post['customerContactEmail']) ? $post['customerContactEmail'] : '';

                $saveContact['title'] = isset($post['customerContactTitlePrefContact']) ? $post['customerContactTitlePrefContact'] : '';
//                $saveContact['location'] = isset($post['customerContactLocation']) ? $post['customerContactLocation'] : '';
//                $saveContact['department'] = isset($post['customerDepartment']) ? $post['customerDepartment'] : '';
//                $saveContact['is_active'] = 1;
//                $saveContact['fax_number'] = isset($post['customerContactFaxNo']) ? $post['customerContactFaxNo'] : '';
//                $saveContact['building_name'] = isset($post['customerContactBuildingName']) ? $post['customerContactBuildingName'] : '';
//                $saveContact['room_number'] = isset($post['customerContactRoomNo']) ? $post['customerContactRoomNo'] : '';
                $customerContact = $this->customer->saveContact($saveContact);

                if ($id) {
                    $saveBilling['id'] = $getCustomerBilling->id;
                } else {
                    $saveBilling['id'] = false;
                }
                $saveBilling['customer_id'] = $customerDetails;
                $saveBilling['billing_contact'] = $post['customerBillingContactName'];
                $saveBilling['address1'] = $post['customerBillingAddress1'];
                $saveBilling['address2'] = $post['customerBillingAddress2'];
                $saveBilling['city'] = $post['customerBillingCity'];
                $saveBilling['state'] = $post['customerBillingState'];
                $saveBilling['zip_code'] = $post['customerBillingZipCode'];
                $saveBilling['phone'] = $post['customerBillingTelNo'];
                $saveBilling['fax'] = $post['customerBillingFaxNo'];
                $saveBilling['email'] = $post['customerBillingEmail'];
                $saveBilling['department'] = $post['customerBillingDepartment'];
                $saveBilling['building_name'] = $post['customerBillingDepartmentBuilding'];
                $saveBilling['room_no'] = $post['customerBillingRoom'];
                $saveBilling['mail_code'] = isset($post['BillingMailCode']) ? $post['BillingMailCode'] : '';
                $saveBilling['same_as_company'] = isset($post['billingSame']) ? $post['billingSame'] : '';

                $customerBilling = $this->customer->saveBilling($saveBilling);

                if ($id) {
                    $saveShipping['id'] = $getCustomerShipping->id;
                } else {
                    $saveShipping['id'] = false;
                }
                $saveShipping['customer_id'] = $customerDetails;
                $saveShipping['customer_name'] = $post['customerShippingName'];
                $saveShipping['address1'] = $post['customerShippingAddress1'];
                $saveShipping['address2'] = $post['customerShippingAddress2'];
                $saveShipping['department'] = $post['customerShippingDepartment'];
                $saveShipping['building_name'] = $post['customerShippingDepartmentBuilding'];
                $saveShipping['room_no'] = $post['customerShippingRoom'];
                $saveShipping['city'] = $post['customerShippingCity'];
                $saveShipping['state'] = $post['customerShippingState'];
                $saveShipping['zip_code'] = $post['customerShippingZipCode'];
                $saveShipping['phone_num'] = $post['customerShippingTelNo'];
                $saveShipping['fax'] = $post['customerShippingFaxNo'];
                $saveShipping['email'] = $post['customerShippingEmail'];
                $saveShipping['same_as_company'] = isset($post['shippingSame']) ? $post['shippingSame'] : '';
                $saveShipping['mail_code'] = isset($post['shippingMailCode']) ? $post['shippingMailCode'] : '';
                $customerShipping = $this->customer->saveShipping($saveShipping);

                if (!$id) {
                    $saveUser['id'] = $userId['id'];
                    $saveUser['customer_id'] = $customerDetails;
                    $saveUser['address1'] = $post['customerCompanyAddress1'];
                    $saveUser['address2'] = $post['customerCompanyAddress2'];
                    $saveUser['city'] = $post['customerCompanyCity'];
                    $saveUser['state'] = $post['customerCompanyState'];
                    $saveUser['zipcode'] = $post['customerCompanyZipCode'];
                    $saveUser['telephone'] = $post['customerCompanyTelNo'];

                    $userCustomer = $this->customer->saveUser($saveUser);

//                    $saveUserContact['id'] = $usercontactId['id'];
//                    $saveUserContact['customer_id'] = $customerDetails;
//
//                    $userContactCustomer = $this->customer->saveUser($saveUserContact);

                }

                if($id)
                {
                    $customerNotes = DB::table('tbl_users');
                    $customerNotes->limit(1);
                    $customerNotes->where('customer_id',$id);
                    $customerNotes->orderBy('id', 'ASC');
                    $customerNotes->select('id');
                    $result = $customerNotes->get()->first();
                    if($result)
                    {
                        $saveUser['id'] = $result->id;
                        $saveUser['address1'] = $post['customerCompanyAddress1'];
                        $saveUser['address2'] = $post['customerCompanyAddress2'];
                        $saveUser['city'] = $post['customerCompanyCity'];
                        $saveUser['state'] = $post['customerCompanyState'];
                        $saveUser['zipcode'] = $post['customerCompanyZipCode'];
                        $saveUser['telephone'] = $post['customerCompanyTelNo'];
                        $this->customer->saveUser($saveUser);
                    }

                }



                if ($id) {
                    $getCustomerDetails = $this->customer->getCustomer($id);
                    if ($getCustomerDetails) {
                        $user_id = (isset($getCustomerDetails->user_id) && $getCustomerDetails->user_id) ? $getCustomerDetails->user_id : '';
                        if ($user_id) {
                            $checkUserExists = DB::table('tbl_users')->where('email', $post['customerCompanyEmail'])->where('id', '!=', $user_id)->first();
                            if ($checkUserExists) {
                                $errors = ['Please change Your Contact Email. This Email is already in use'];
                                return view('customer.customerForm')->with('title', $title)->with('input', $data)->with('customerType', $customerType)->with('errors', $errors)->with('states', $states)->with('city', $city);
                            } else {
                                $updateUserEmail['id'] = $user_id;
                                $updateUserEmail['email'] = $post['customerCompanyEmail'];
                                $this->customer->saveUser($updateUserEmail);
                            }
                        }


                    }

                }

            }


            if (!$id) {
                $query = DB::table('tbl_email_template');
                $query->where('tplid', '=', 4);
                $result = $query->first();
                $result->tplmessage = str_replace('{name}', $post['customerCompanyName'], $result->tplmessage);
                $result->tplmessage = str_replace('{user_name}', $post['customerCompanyEmail'], $result->tplmessage);
                $result->tplmessage = str_replace('{password}', $passwordGeneration, $result->tplmessage);
                $result->tplmessage = str_replace('{link}', env('url_path'), $result->tplmessage);

                $param['message'] = $result->tplmessage;
                $param['title'] = $result->tplsubject;


                Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param, $post) {
                    $message->to($post['customerCompanyEmail'])->subject
                    ($param['title']);
                });
            }


            $message = Session::flash('message', 'Customer has been created');
            return redirect('admin/customerlists')->with('message', $message);


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


    public
    function delete($id)
    {

        $getdetail = $this->customer->getCustomer($id);
//echo '<pre>';print_r($getdetail->user_id);exit;
        if ($getdetail) {

//            $getSubdetail = DB::table('tbl_equipment_model')->where('brand_id','=',$id)->select('*')->first();
//            if($getModel){
//                $message = Session::flash('error', "You can't able to delete this brand");
//                return redirect('admin/devicelist')->with(['data', $message], ['message', $message]);
//            }
            $equipments = DB::table('tbl_equipment')->where('customer_id', '=', $id)->select('*')->first();
//            echo '<pre>';print_r($equipments);exit;
            if ($equipments) {
                $errormessage = Session::flash('error', 'This customer having equipments. You cannot delete');
                return redirect('admin/customerlists')->with(['data', $errormessage], ['message', $errormessage]);

            }


            $deletecontact = $this->customer->deleteCustomerContact($id);
            $deletebilling = $this->customer->deleteCustomerBilling($id);
            $deleteshipping = $this->customer->deleteCustomerShipping($id);
            $deletecustomer = $this->customer->deleteCustomer($id);
            $deletegroupuser = $this->customer->deleteGroupUser($getdetail->user_id);
//            $deleteuser = $this->customer->deleteUser($getdetail->user_id);
            $deleteuser = $this->customer->deleteUser($id);
            $message = Session::flash('message', 'Deleted Successfully!');
            return redirect('admin/customerlists')->with(['data', $message], ['message', $message]);

        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/customerlists')->with('data', $error);
        }
    }


    public function getCustomerInfo(Request $request)
    {

        $input = Input::all();
        $customerId = $input['customerId'];
        if (!$customerId) {
            die(json_encode(array('result' => false, 'message' => 'CustomerId not found.')));
        }

        $getCustomer = $this->customer->getCustomer($customerId, true);

        if (!$getCustomer) {
            die(json_encode(array('result' => false, 'message' => 'Customer Details not found.')));
        }

        $view = View::make('customer.customerview', ['data' => $getCustomer]);
        $formData = $view->render();
        die(json_encode(array('result' => true, "formData" => trim($formData))));


    }

    public function VerifyEmail(Request $request)
    {
        $input = Input::all();
//        echo '<pre>';print_r($input);exit;
        if ($input['customerId']) {
            $result = DB::table('tbl_group_user')->where('users_id', '=', $input['customerId'])->where('user_group_id', '=', 2)->first();
            if (($result) == '') {
                $save['id'] = '';
                $save['user_group_id'] = 2;
                $save['role_id'] = 1;
                $save['users_id'] = $input['customerId'];
                $group_user_id = $this->customer->saveUserGroup($save);
            }
        }
        if ($group_user_id) {
            die(json_encode(array('result' => true, "message" => 'Verified Successfully')));

        } else {
            die(json_encode(array('result' => false, "message" => 'Not Verified Successfully')));

        }
    }

    public function portalAccess(Request $request)
    {
        $input = Input::all();
//        echo '<pre>';print_r($input);exit;
        if ($input['customerId']) {
            $result = DB::table('tbl_group_user')->where('users_id', '=', $input['customerId'])->where('user_group_id', '=', 2)->first();
            if (($result) == '') {
                $save['id'] = '';
                $save['user_group_id'] = 2;
                $save['role_id'] = 1;
                $save['users_id'] = $input['customerId'];
                $group_user_id = $this->customer->saveUserGroup($save);
            }
        }
        if ($group_user_id) {
            die(json_encode(array('result' => true, "message" => 'Verified Successfully')));

        } else {
            die(json_encode(array('result' => false, "message" => 'Not Verified Successfully')));

        }
    }

}
