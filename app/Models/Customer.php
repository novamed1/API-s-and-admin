<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Customer extends Model
{
    protected $table = 'tbl_equipment_model';


    public function AllCustomer($limit = 0, $offset = 0, $order_by = 'c.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer as c');

//        print_r($cond);die;
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }


        if (isset($cond['search']) && $cond['search'] != '') {

            if (isset($likeArray) && $likeArray != '') {
                $likeArrayFields = $likeArray;
            } else {
                $likeArrayFields = array('');
            }


            if (!empty($likeArray)) {

                $flag = true;
                $like = '';
                foreach ($likeArray as $value) {
                    if ($flag) {

                        $like .= $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                    } else {
                        $like .= " AND " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                    }
                    $flag = false;
                }
                $query->whereRaw('(' . $like . ')');
            }
        }

        if (isset($cond['customerId']) && $cond['customerId'] != '') {
            $query->where('c.id', '=', $cond['customerId']);
        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_customer_type as t', 't.id', '=', 'c.customer_type_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
//          $query = DB::getQueryLog();
//        print_r($query);
//        die;


//
        return $result;
    }

    public function AllCustomerGrid($limit = 0, $offset = 0, $order_by = 'c.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_customer as c');

//        print_r($cond);die;
        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            if(!isset($cond['count']))
            {
                $query->limit($limit);
                $query->offset($offset);
            }
        }


        if (isset($cond['search']) && $cond['search'] != '') {


            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key=>$value) {
                    if($value) {
                        if ($flag) {

                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
                        } else {
                            $like .= " AND " . $key . " LIKE '%" . trim($value) . "%' ";
                        }
                        $flag = false;
                    }
                }
                if(array_filter($cond['search'])) {
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }

        if (isset($cond['customerId']) && $cond['customerId'] != '') {
            $query->where('c.id', '=', $cond['customerId']);
        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_customer_type as t', 't.id', '=', 'c.customer_type_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
//          $query = DB::getQueryLog();
//        print_r($query);
//        die;


//
        return $result;
    }

    public function saveUserGroup($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $userId;
            $result = DB::table('tbl_group_user')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;

            $result = DB::table('tbl_group_user')->insertGetId($input);
            return $result;
        }
    }

    public function saveUser($input)
    {
        $userId = Sentinel::getUser()->id;

        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = isset($input['customer_id']) ? $input['customer_id'] : $userId;
            $result = DB::table('tbl_users')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $input['customer_id'];

            $result = DB::table('tbl_users')->insertGetId($input);
            return $result;
        }
    }

    public function saveCustomer($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $userId;
            $result = DB::table('tbl_customer')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;

            $result = DB::table('tbl_customer')->insertGetId($input);
            return $result;
        }
    }

    public function saveCustomerProfile($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
          //  $input['modified_by'] = $userId;
            $result = DB::table('tbl_customer')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            //$input['created_by'] = $userId;

            $result = DB::table('tbl_customer')->insertGetId($input);
            return $result;
        }
    }

    public function saveContact($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $userId;
            $result = DB::table('tbl_customer_contacts')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;

            $result = DB::table('tbl_customer_contacts')->insertGetId($input);
            return $result;
        }
    }

    public function saveBilling($input)
    {
        if ($input['id']) {

            $result = DB::table('tbl_customer_billing_address')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {

            $result = DB::table('tbl_customer_billing_address')->insertGetId($input);
            return $result;
        }
    }

    public function saveShipping($input)
    {
        if ($input['id']) {

            $result = DB::table('tbl_customer_shipping_address')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {

            $result = DB::table('tbl_customer_shipping_address')->insertGetId($input);
            return $result;
        }
    }

    public function getLimitTolerences($modelId)
    {
        $query = DB::table('tbl_limit_tolerance')->where('model_id', $modelId)->get();
        return $query;
    }

    public function getTestTolerences($id)
    {
        $query = DB::table('tbl_test_tolerance')->where('test_plan_id', $id)->get();
        return $query;
    }
    public function getCustomer($id, $select = false)
    {
        if ($select) {
            $query = DB::table('tbl_customer as tc')->where('tc.id', '=', $id)
                ->join('tbl_customer_type as tct', 'tct.id', '=', 'tc.customer_type_id')->
                select('tc.id', 'tc.customer_name', 'tct.name', 'tc.customer_type_id', 'tc.customer_email',
                    'tc.customer_main_telephone', 'tc.address1', 'tc.state', 'tc.city', 'tc.customer_email','tc.unique_id',
                    'tc.customer_telephone','tc.address2','tc.primary_contact')->first();
            return $query;
        } else {
            $query = DB::table('tbl_customer')->where('id', '=', $id)->select('*')->first();
            return $query;
        }

    }
    public function getCustomerBilling($customerId){

        $query = DB::table('tbl_customer_billing_address')->where('customer_id','=',$customerId)->select('*')->first();
        return $query;
    }

    public function getCustomerBillingAll($customerId){

        $query = DB::table('tbl_customer_billing_address')->where('customer_id','=',$customerId)->select('*')->get();
        return $query;
    }

    public function getBilling($Id){

        $query = DB::table('tbl_customer_billing_address')->where('id','=',$Id)->select('*')->first();
        return $query;
    }
    public function getShipping($Id){

        $query = DB::table('tbl_customer_shipping_address')->where('id','=',$Id)->select('*')->first();
        return $query;
    }


    public function getCustomerShipping($customerId){

        $query = DB::table('tbl_customer_shipping_address')->where('customer_id','=',$customerId)->select('*')->first();
        return $query;
    }

    public function getCustomerShippingAll($customerId){

        $query = DB::table('tbl_customer_shipping_address')->where('customer_id','=',$customerId)->select('*')->get();
        return $query;
    }

    public function saveCustomerSetup($input)
    {

        if ($input['id']) {

            $result = DB::table('tbl_customer_setups')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
//            print_r('hi');die;

            $result = DB::table('tbl_customer_setups')->insertGetId($input);

            return $result;
        }
    }
    public function saveCustomerSetupbyCustomer($input)
    {

        $getcustomer = DB::table('tbl_customer_setups')->where('customer_id', '=', $input['customer_id'])->select('*')->first();

        if ($getcustomer) {
            $result = DB::table('tbl_customer_setups')->where('customer_id', $input['customer_id'])->update($input);
            return $getcustomer->id;
        } else {
            $result = DB::table('tbl_customer_setups')->insertGetId($input);
            return $result;
        }
    }


    public function saveContactProfile($input)
    {

        if ($input['id']) {

            $result = DB::table('tbl_customer_contacts')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
//            print_r('hi');die;

            $result = DB::table('tbl_customer_contacts')->insertGetId($input);

            return $result;
        }
    }

    function getCompanyProfile($id)
    {
        DB::enableQueryLog();

        $result = DB::table('tbl_customer as c')
            ->join('tbl_customer_shipping_address as s', 's.customer_id', '=', 'c.id')
            ->join('tbl_customer_billing_address as b', 'b.customer_id', '=', 'c.id')
            ->join('tbl_customer_type as t', 't.id', '=', 'c.customer_type_id')
            ->select('c.customer_name','t.name as type','c.title','c.customer_telephone','c.customer_main_fax','c.customer_email','b.billing_contact','b.address1','b.city as bcity','b.state as bstate','b.zip_code as bzipcode','b.phone as bphone','b.fax as bfax','s.customer_name as sname','s.address1 as saddress','s.city as scity','s.state as sstate','s.zip_code as szipcode','s.phone_num as sphone','s.fax as sfax','c.id as customer_id','s.same_as_company','c.address1','c.address2','c.city','c.state','c.zip_code','b.same_as_company as same_as_billing','c.primary_contact','t.id as customer_type_id')
            ->where('c.user_id', $id)->first();
       // print_r(DB::getQueryLog()); die;
        return $result;
    }

    function getBillingAddress($id)
    {
        DB::enableQueryLog();

        $result = DB::table('tbl_customer_billing_address as b')
            ->select('b.*')
            ->where('b.customer_id', $id)->get();
        // print_r(DB::getQueryLog()); die;
        return $result;
    }

    function getShippingAddress($id)
    {
        DB::enableQueryLog();

        $result = DB::table('tbl_customer_shipping_address as s')
            ->select('s.*')
            ->where('s.customer_id', $id)->get();
        // print_r(DB::getQueryLog()); die;
        return $result;
    }

    function getCompanyContact($id)
    {
        DB::enableQueryLog();

        $result = DB::table('tbl_customer_contacts as c')
            ->select('c.*')
            ->where('c.customer_id', $id)->first();
        // print_r(DB::getQueryLog()); die;
        return $result;
    }

    function getContact($id)
    {
        DB::enableQueryLog();

        $result = DB::table('tbl_customer_contacts as c')
            ->select('c.*')
            ->where('c.id', $id)->first();
        // print_r(DB::getQueryLog()); die;
        return $result;
    }
    function getUserByCustomer($customerId)
    {
        DB::enableQueryLog();

        $result = DB::table('tbl_users')
            ->select('*')
            ->where('customer_id', $customerId)->first();
        return $result;
    }
    function getCompanyTypes()
    {
        DB::enableQueryLog();

        $result = DB::table('tbl_customer_type as c')
            ->select('c.*')
            ->get();
        // print_r(DB::getQueryLog()); die;
        return $result;
    }

    public function updatePlans($customerId, $data)
    {
//        print_r($data);die;
        $subquery = DB::table('tbl_customer_setups');

        $query = $subquery->where('customer_id', $customerId)->select('plan_id')->first();

        if ($query) {

            $myArray = explode(',', $query->plan_id);


            if (in_array($data->plan_id, $myArray)) {

                return true;
            } else {
                $plans = $data->plan_id . ',' . $query->plan_id;
                $data = array(
                    'plan_id' => rtrim($plans, ',')
                );
                DB::table('tbl_customer_setups')->where('customer_id', $customerId)->update($data);
                return true;
            }

        }
    }

    public function getgroupUser($userId){
        $query = DB::table('tbl_group_user')->where('users_id','=',$userId)->select('*')->get();
        return $query;
    }

    public function getUser($userId){
        $result = DB::table('tbl_users')->where('id','=',$userId)->first();
        return $result;
    }
    //for deleting user management
    public function deleteCustomer($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;



        $delete = DB::table('tbl_customer')->where('id', $id)->delete();

        if ($delete == 1) {


//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }


    public function getCustomerSetup($customerId)
    {

        $query = DB::table('tbl_customer_setups as ts')
            ->join('tbl_pay_method as tpm', 'tpm.id', '=', 'ts.pay_method')->where('customer_id', '=', $customerId)->select('*')->first();
        return $query;
    }
    //for deleting user management
    public function deleteCustomerContact($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;



        $delete = DB::table('tbl_customer_contacts')->where('customer_id', $id)->delete();

        if ($delete == 1) {


//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }
    //for deleting user management
    public function deleteCustomerBilling($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;



        $delete = DB::table('tbl_customer_billing_address')->where('customer_id', $id)->delete();

        if ($delete == 1) {


//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }
    //for deleting user management
    public function deleteCustomerShipping($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;



        $delete = DB::table('tbl_customer_shipping_address')->where('customer_id', $id)->delete();

        if ($delete == 1) {


//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }



    //for deleting user management
    public function deleteUser($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;



        $delete = DB::table('tbl_users')->where('customer_id', $id)->delete();

        if ($delete == 1) {


//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }


    public function deleteGroupUser($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;



        $delete = DB::table('tbl_group_user')->where('users_id', $id)->delete();

        if ($delete == 1) {


//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }

    function getCustomerUsers($customer_id)
    {

        $query = DB::table('tbl_users')->where('customer_id',$customer_id)->select('telephone','email','location','id','name')
            ->get();
        return $query;
    }


}



