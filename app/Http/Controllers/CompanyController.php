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
use Validator;
use Illuminate\Support\Facades\Hash;
use Image;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedExceptionException;
use App\Models\Customer;

class CompanyController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->userModel = new User();
        $this->company = new Customer();
    }

    public function index()
    {
        //print_r($this->user);die;
        return response()->json(['auth' => Auth::user(), 'users' => User::all()]);
    }

    public function getCompanyTypes(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        if($user)
        {
            $types = $this->company->getCompanyTypes();
            $typesarr = array();
            if($types)
            {
               foreach ($types as $key=>$row)
               {
                   $typesarr[$key]['id'] = $row->id;
                   $typesarr[$key]['name'] = $row->name;
               }
            }
            return Response::json([
                'status' => 1,
                'data' => $typesarr
            ], 200);
        }
        else
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 404);
        }

    }

    public function companyProfile(Request $request)
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

//        $equipmentModels = DB::table('tbl_equipment_model')->select('id','model_description','manufacturer_ids','manufacturer_name')->join('tbl_manufacturer as m','m.manufacturer_id','=','tbl_equipment_model.manufacturer_ids')->get();
//
//        foreach ($equipmentModels as $key=>$row)
//        {
//            $save['id'] = $row->id;
//            $save['model_description'] = $row->manufacturer_name.' '.$row->model_description;
//            DB::table('tbl_equipment_model')->where('id', $save['id'])->update($save);
//        }
//        echo 'Saved';die;


        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        $role = DB::table('tbl_group_user')
            ->where('tbl_group_user.users_id',$user->user_id)->where('user_group_id',2)->first();
        $role_id = (isset($role->role_id)&&$role->role_id)?$role->role_id:'';
        if ($user) {
            if($role_id!=1)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'You dont have permission'

                ], 403);
            }
            $company = $this->company->getCompanyProfile($user->user_id); //print_r($company);die;
            $companyarr = array();
            $contactarr = array();
            $billingarr = array();
            $shippingarr = array();
            if ($company) {
                $companyarr['id'] = $company->customer_id;
                $companyarr['company_name'] = $company->customer_name;
                $companyarr['company_type'] = $company->type;
                $companyarr['company_type_id'] = $company->customer_type_id;
                $companyarr['contact_person'] = $company->primary_contact;
                $companyarr['title'] = $company->title;
                $companyarr['company_telephone'] = $company->customer_telephone;
                $companyarr['company_fax'] = $company->customer_main_fax;
                $companyarr['company_email'] = $company->customer_email;
                $companyarr['address1'] = $company->address1;
                $companyarr['address2'] = $company->address2;
                $companyarr['city'] = $company->city;
                $companyarr['state'] = $company->state;
                $companyarr['zip_code'] = $company->zip_code;
                $companyarr['company_email'] = $company->customer_email;

                $billingAddress = $this->company->getBillingAddress($company->customer_id);
                if($billingAddress)
                {
                    foreach ($billingAddress as $bkey=>$brow)
                    {
                        $billingarr[$bkey]['id'] = $brow->id;
                        $billingarr[$bkey]['billing_contact_name'] = $brow->billing_contact;
                        $billingarr[$bkey]['billing_building_name'] = $brow->address2;
                        $billingarr[$bkey]['billing_street'] = $brow->address1;;
                        $billingarr[$bkey]['billing_city'] = $brow->city;
                        $billingarr[$bkey]['billing_state'] = $brow->state;
                        $billingarr[$bkey]['billing_zipcode'] = $brow->zip_code;
                        $billingarr[$bkey]['billing_telephone'] = $brow->phone;
                        $billingarr[$bkey]['billing_fax'] = $brow->fax;
                        $billingarr[$bkey]['address1'] = $brow->address1;
                        $billingarr[$bkey]['address2'] = $brow->address2;
                        if($company->customer_type_id)
                        {
                            $billingarr[$bkey]['mail_code'] = $brow->mail_code;
                            $billingarr[$bkey]['building_name'] = $brow->building_name;
                            $billingarr[$bkey]['room_no'] = $brow->room_no;
                        }
                        else
                        {
                            $billingarr[$bkey]['mail_code'] = '';
                            $billingarr[$bkey]['building_name'] = '';
                            $billingarr[$bkey]['room_no'] = '';
                        }
                        if($brow->same_as_company)
                        {
                            $billingarr[$bkey]['same_as'] = 1;
                        }
                        else
                        {
                            $billingarr[$bkey]['same_as'] = 0;
                        }
                        $billingarr[$bkey]['status'] = true;
                    }


                }



                $shippingAddress = $this->company->getShippingAddress($company->customer_id);
                if($shippingAddress)
                {
                   foreach ($shippingAddress as $skey=>$srow)
                   {
                       $shippingarr[$skey]['id'] = $srow->id;
                       $shippingarr[$skey]['shipping_contact_name'] = $srow->customer_name;
                       $shippingarr[$skey]['shipping_building_name'] = $srow->address2;;
                       $shippingarr[$skey]['shipping_street'] = $srow->address1;
                       $shippingarr[$skey]['shipping_city'] = $srow->city;
                       $shippingarr[$skey]['shipping_state'] = $srow->state;
                       $shippingarr[$skey]['shipping_zipcode'] = $srow->zip_code;
                       $shippingarr[$skey]['shipping_telephone'] = $srow->phone_num;
                       $shippingarr[$skey]['shipping_fax'] = $srow->fax;
                       $shippingarr[$skey]['address1'] = $srow->address1;
                       $shippingarr[$skey]['address2'] = $srow->address2;
                       if($company->customer_type_id==2)
                       {
                           $shippingarr[$skey]['mail_code'] = $srow->mail_code;
                           $shippingarr[$skey]['building_name'] = $srow->building_name;
                           $shippingarr[$skey]['room_no'] = $srow->room_no;
                       }
                       else
                       {
                           $shippingarr[$skey]['mail_code'] = '';
                           $shippingarr[$skey]['building_name'] = '';
                           $shippingarr[$skey]['room_no'] = '';
                       }
                       if($srow->same_as_company)
                       {
                           $shippingarr[$skey]['same_as_billing'] = 1;
                       }
                       else
                       {
                           $shippingarr[$skey]['same_as_billing'] = 0;
                       }
                       $shippingarr[$skey]['status'] = true;
                   }
                }

                $contact = $this->company->getCompanyContact($company->customer_id);
                if ($contact) {
                    $contactarr['id'] = $contact->id;
                    $contactarr['contact_name'] = $contact->contact_name;
                    $contactarr['title'] = $contact->title;
                    $contactarr['location'] = $contact->location;
                    $contactarr['room_number'] = $contact->room_number;
                    $contactarr['phone'] = $contact->phone_no;
                    $contactarr['email'] = $contact->email_id;
                    $contactarr['fax'] = $contact->fax_number;
                }

            }

            return Response::json([
                'status' => 1,
                'data' => $companyarr,
                'contact' => $contactarr,
                'billing' => $billingarr,
                'shipping' => $shippingarr
            ], 200);
        } else {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 404);
        }

    }

    public function editcompanyProfile(Request $request)
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
        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        if ($user) {
            $company = $this->company->getCompanyProfile($user->user_id);
            if ($company) {
                $savecompany['id'] = $reqInputs['id'];
                $savecompany['customer_name'] = $reqInputs['company_name'];
                $savecompany['customer_type_id'] = $reqInputs['company_type'];
                $savecompany['title'] = $reqInputs['company_title'];
                $savecompany['customer_telephone'] = $reqInputs['company_telephone'];
                $savecompany['customer_main_fax'] = $reqInputs['company_fax'];
                $savecompany['customer_email'] = $reqInputs['company_email'];

                $savecompany['address1'] = isset($reqInputs['address1'])?$reqInputs['address1']:'';
                $savecompany['address2'] = isset($reqInputs['address2'])?$reqInputs['address2']:'';
                $savecompany['city'] = isset($reqInputs['city'])?$reqInputs['city']:'';
                $savecompany['state'] = isset($reqInputs['state'])?$reqInputs['state']:'';
                $savecompany['zip_code'] = isset($reqInputs['zipcode'])?$reqInputs['zipcode']:'';
                $savecompany['primary_contact'] = isset($reqInputs['contact_person'])?$reqInputs['contact_person']:'';

                $customerDetails = $this->company->saveCustomerProfile($savecompany);
//                $getBilling = $this->company->getCustomerBilling($reqInputs['id']);
//                $savebilling['id'] = $getBilling->id;
//                $savebilling['billing_contact'] = $reqInputs['billing_contact_name'];
//                $savebilling['address1'] = $reqInputs['billing_street'];
//                $savebilling['city'] = $reqInputs['billing_city'];
//                $savebilling['state'] = $reqInputs['billing_state'];
//                $savebilling['zip_code'] = $reqInputs['billing_zipcode'];
//                $savebilling['phone'] = $reqInputs['billing_telephone'];
//                $savebilling['fax'] = $reqInputs['billing_fax'];
//                $savebilling['same_as_company'] = isset($reqInputs['same_as_company'])?$reqInputs['same_as_company']:'';
//
//
//                $customerBilling = $this->company->saveBilling($savebilling);
//                $getShipping = $this->company->getCustomerShipping($reqInputs['id']);
//                $saveshipping['id'] = $getShipping->id;
//                $saveshipping['customer_name'] = $reqInputs['shipping_contact_name'];
//                $saveshipping['address1'] = $reqInputs['shipping_street'];
//                $saveshipping['city'] = $reqInputs['shipping_city'];
//                $saveshipping['state'] = $reqInputs['shipping_state'];
//                $saveshipping['zip_code'] = $reqInputs['shipping_zipcode'];
//                $saveshipping['phone_num'] = $reqInputs['shipping_telephone'];
//                $saveshipping['fax'] = $reqInputs['shipping_fax'];
//                $savebilling['same_as_company'] = isset($reqInputs['same_as_billing'])?$reqInputs['same_as_billing']:'';
//                $customerShipping = $this->company->saveShipping($saveshipping);

            }
        }
        return Response::json([
            'status' => 1,
            'message' => 'Company profile has been updated'
        ], 200);

    }

    public function editcontact(Request $request)
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
        $reqInputs = $request->input();

        $input = [
            'contact_name' => $reqInputs['contact_name'],
            'title' => $reqInputs['title'],
            'location' => $reqInputs['location']
        ];
        $rules = array(

            'contact_name' => 'required',
            'title' => 'required',
            'location' => 'required'
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

        $user = $this->userModel->getUser($user['id']);
        if ($user) {
            $company = $this->company->getCompanyProfile($user->user_id);
            $save['id'] = $reqInputs['id'];
            $save['contact_name'] = $reqInputs['contact_name'];
            $save['title'] = $reqInputs['title'];
            $save['location'] = $reqInputs['location'];
            $save['room_number'] = $reqInputs['room_number'];
            $save['phone_no'] = $reqInputs['phone'];
            $save['email_id'] = $reqInputs['email'];
            $save['fax_number'] = $reqInputs['fax'];
            $id = $this->company->saveContactProfile($save);
            $last_contact = $this->company->getContact($id);
            $contactarr = array();
            if ($last_contact) {
                $contactarr['id'] = $last_contact->id;
                $contactarr['contact_name'] = $last_contact->contact_name;
                $contactarr['title'] = $last_contact->title;
                $contactarr['location'] = $last_contact->location;
                $contactarr['room_number'] = $last_contact->room_number;
                $contactarr['phone'] = $last_contact->phone_no;
                $contactarr['email'] = $last_contact->email_id;
                $contactarr['fax'] = $last_contact->fax_number;
            }

        }
        return Response::json([
            'status' => 1,
            'message' => 'Contact has been updated',
            'last_contact' => $contactarr
        ], 200);

    }

    public function shippingbilling(Request $request)
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
        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        $customer_id = $user->customer_id;
        $msg='';
        if ($user) {

               $ref_key = (isset($reqInputs['ref_key']) && $reqInputs['ref_key'])?$reqInputs['ref_key']:'';
               $id = (isset($reqInputs['id']) && $reqInputs['id'])?$reqInputs['id']:'';
             if(!$ref_key)
             {
                 return Response::json([
                     'status' => 0,
                     'message' => 'Please provide the ref_key 1 is for billing and 2 is for shipping'
                 ], 400);
             }
             if($ref_key==1)
             {
                 $savebilling['id'] = $id;
                 $savebilling['customer_id'] = $customer_id;
                 $savebilling['billing_contact'] = $reqInputs['name'];
                 $savebilling['address1'] = $reqInputs['street'];
                 $savebilling['address2'] = (isset($reqInputs['buildingname']) && $reqInputs['buildingname'])?$reqInputs['buildingname']:'';
                 $savebilling['city'] = $reqInputs['city'];
                 $savebilling['state'] = $reqInputs['state'];
                 $savebilling['zip_code'] = $reqInputs['zipcode'];
                 $savebilling['phone'] = $reqInputs['telephone'];
                 $savebilling['fax'] = $reqInputs['fax'];

                 $savebilling['mail_code'] = (isset($reqInputs['mail_code']) && $reqInputs['mail_code'])?$reqInputs['mail_code']:'';
                 $savebilling['room_no'] = (isset($reqInputs['room_no']) && $reqInputs['room_no'])?$reqInputs['room_no']:'';
                 $savebilling['building_name'] = (isset($reqInputs['building_name']) && $reqInputs['building_name'])?$reqInputs['building_name']:'';

                 $savebilling['same_as_company'] = isset($reqInputs['same_as_company'])?$reqInputs['same_as_company']:'';


                 $customerBilling = $this->company->saveBilling($savebilling);
                 $lastdata = $this->company->getBilling($customerBilling);
                 $lastarr = array();
                 if($lastdata)
                 {
                     $lastarr['id'] = $lastdata->id;
                     $lastarr['billing_contact_name'] = $lastdata->billing_contact;
                     $lastarr['billing_building_name'] = $lastdata->address2;
                     $lastarr['billing_street'] = $lastdata->address1;
                     $lastarr['billing_city'] = $lastdata->city;
                     $lastarr['billing_state'] = $lastdata->state;
                     $lastarr['billing_zipcode'] = $lastdata->zip_code;
                     $lastarr['billing_telephone'] = $lastdata->phone;
                     $lastarr['billing_fax'] = $lastdata->fax;

                     $lastarr['mail_code'] = $lastdata->mail_code;
                     $lastarr['room_no'] = $lastdata->room_no;
                     $lastarr['building_name'] = $lastdata->building_name;

                     $lastarr['status'] = true;
                 }
                 if($id)
                 {
                   $msg='Billing address has been updated';
                 }
                 else
                 {
                     $msg='Billing address has been added';
                 }

             }
             elseif($ref_key==2)
             {
                 $saveshipping['id'] = $id;
                 $saveshipping['customer_id'] = $customer_id;
                 $saveshipping['customer_name'] = $reqInputs['name'];
                 $saveshipping['address1'] = $reqInputs['street'];
                 $saveshipping['address2'] = (isset($reqInputs['buildingname']) && $reqInputs['buildingname'])?$reqInputs['buildingname']:'';
                 $saveshipping['city'] = $reqInputs['city'];
                 $saveshipping['state'] = $reqInputs['state'];
                 $saveshipping['zip_code'] = $reqInputs['zipcode'];
                 $saveshipping['phone_num'] = $reqInputs['telephone'];
                 $saveshipping['fax'] = $reqInputs['fax'];

                 $saveshipping['mail_code'] = (isset($reqInputs['mail_code']) && $reqInputs['mail_code'])?$reqInputs['mail_code']:'';
                 $saveshipping['room_no'] = (isset($reqInputs['room_no']) && $reqInputs['room_no'])?$reqInputs['room_no']:'';
                 $saveshipping['building_name'] = (isset($reqInputs['building_name']) && $reqInputs['building_name'])?$reqInputs['building_name']:'';

                 $savebilling['same_as_company'] = isset($reqInputs['same_as_billing'])?$reqInputs['same_as_billing']:'';
                 $customerShipping = $this->company->saveShipping($saveshipping);
                 $lastdata = $this->company->getShipping($customerShipping);
                 $lastarr = array();
                 if($lastdata)
                 {
                     $lastarr['id'] = $lastdata->id;
                     $lastarr['shipping_contact_name'] = $lastdata->customer_name;
                     $lastarr['shipping_building_name'] = $lastdata->address2;
                     $lastarr['shipping_street'] = $lastdata->address1;
                     $lastarr['shipping_city'] = $lastdata->city;
                     $lastarr['shipping_state'] = $lastdata->state;
                     $lastarr['shipping_zipcode'] = $lastdata->zip_code;
                     $lastarr['shipping_telephone'] = $lastdata->phone_num;
                     $lastarr['shipping_fax'] = $lastdata->fax;

                     $lastarr['mail_code'] = $lastdata->mail_code;
                     $lastarr['room_no'] = $lastdata->room_no;
                     $lastarr['building_name'] = $lastdata->building_name;

                     $lastarr['status'] = true;
                 }
                 if($id)
                 {
                     $msg='Shipping address has been updated';
                 }
                 else
                 {
                     $msg='Shipping address has been added';
                 }
             }
             else
             {
                 return Response::json([
                     'status' => 0,
                     'message' => 'ref_key value should be 1 or 2'
                 ], 400);
             }



            }
        return Response::json([
            'status' => 1,
            'message' => $msg,
            'data' => $lastarr
        ], 200);

    }

    public function getshippingbilling(Request $request)
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
        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        if ($user) {

            $ref_key = (isset($reqInputs['ref_key']) && $reqInputs['ref_key'])?$reqInputs['ref_key']:'';
            $id = (isset($reqInputs['id']) && $reqInputs['id'])?$reqInputs['id']:'';
            if(!$ref_key)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Please provide the ref_key 1 is for billing and 2 is for shipping'
                ], 400);
            }
            if(!$id)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Please provide the id'
                ], 400);
            }
            if($ref_key==1)
            {
                $billing = $this->company->getBilling($id);
                if($billing)
                {
                    $data['name'] = $billing->billing_contact;
                    $data['street'] = $billing->address1;
                    $data['building'] = $billing->address2;
                    $data['city'] = $billing->city;
                    $data['state'] = $billing->state;
                    $data['zipcode'] = $billing->zip_code;
                    $data['telephone'] = $billing->phone;
                    $data['fax'] = $billing->fax;
                    if($billing->same_as_company)
                    {
                        $data['same_as'] = 1;
                    }
                    else
                    {
                        $data['same_as'] = 0;
                    }
                }
                else
                {
                    return Response::json([
                        'status' => 0,
                        'message' => 'This billing address not found'
                    ], 404);
                }
            }
            elseif($ref_key==2)
            {
                $shipping = $this->company->getShipping($id);
                if($shipping)
                {
                    $data['name'] = $shipping->customer_name;
                    $data['street'] = $shipping->address1;
                    $data['building'] = $shipping->address2;
                    $data['city'] = $shipping->city;
                    $data['state'] = $shipping->state;
                    $data['zipcode'] = $shipping->zip_code;
                    $data['telephone'] = $shipping->phone_num;
                    $data['fax'] = $shipping->fax;
                    if($shipping->same_as_company)
                    {
                        $data['same_as'] = 1;
                    }
                    else
                    {
                        $data['same_as'] = 0;
                    }
                }
                else
                {
                    return Response::json([
                        'status' => 0,
                        'message' => 'This shipping address not found'
                    ], 404);
                }
            }
            else
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'ref_key value should be 1 or 2'
                ], 400);
            }





            return Response::json([
                'status' => 1,
                'data' => $data
            ], 200);
        } else {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 404);
        }

    }

    public function getallshippingbilling(Request $request)
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
        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        $customer_id = $user->customer_id;
        $data = array();

        if ($user) {

            $ref_key = (isset($reqInputs['ref_key']) && $reqInputs['ref_key'])?$reqInputs['ref_key']:'';
            if(!$ref_key)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Please provide the ref_key 1 is for billing and 2 is for shipping'
                ], 400);
            }
            if(!$customer_id)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Customer not exist'
                ], 400);
            }
            if($ref_key==1)
            {
                $billing = $this->company->getCustomerBillingAll($customer_id);
                if($billing)
                {
                    foreach($billing as $bkey=>$brow)
                    {
                        $data[$bkey]['id'] = $brow->id;
                        $data[$bkey]['name'] = $brow->billing_contact;
                        $data[$bkey]['street'] = $brow->address1;
                        $data[$bkey]['building'] = $brow->address2;
                        $data[$bkey]['city'] = $brow->city;
                        $data[$bkey]['state'] = $brow->state;
                        $data[$bkey]['zipcode'] = $brow->zip_code;
                        $data[$bkey]['telephone'] = $brow->phone;
                        $data[$bkey]['fax'] = $brow->fax;
                        if($brow->same_as_company)
                        {
                            $data[$bkey]['same_as'] = 1;
                        }
                        else
                        {
                            $data[$bkey]['same_as'] = 0;
                        }
                        $data[$bkey]['status'] = true;
                    }

                }
                else
                {
                    return Response::json([
                        'status' => 0,
                        'message' => 'No data'
                    ], 404);
                }
            }
            elseif($ref_key==2)
            {
                $shipping = $this->company->getCustomerShippingAll($customer_id);
                if($shipping)
                {
                    foreach ($shipping as $skey=>$srow)
                    {
                        $data[$skey]['id'] = $srow->id;
                        $data[$skey]['name'] = $srow->customer_name;
                        $data[$skey]['street'] = $srow->address1;
                        $data[$skey]['building'] = $srow->address2;
                        $data[$skey]['city'] = $srow->city;
                        $data[$skey]['state'] = $srow->state;
                        $data[$skey]['zipcode'] = $srow->zip_code;
                        $data[$skey]['telephone'] = $srow->phone_num;
                        $data[$skey]['fax'] = $srow->fax;
                        if($srow->same_as_company)
                        {
                            $data[$skey]['same_as_billing'] = 1;
                        }
                        else
                        {
                            $data[$skey]['same_as_billing'] = 0;
                        }
                        $data[$skey]['status'] = true;
                    }
                }
                else
                {
                    return Response::json([
                        'status' => 0,
                        'message' => 'This shipping address not found'
                    ], 404);
                }
            }
            else
            {
                return Response::json([
                    'status' => 0,
                    'msg' => "Provide ref_key 1 or 2"
                ], 400);
            }





            return Response::json([
                'status' => 1,
                'data' => $data
            ], 200);
        } else {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 404);
        }

    }


}