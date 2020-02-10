<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use DB;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable,EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];
    protected $table = 'tbl_users';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getCustomer($id){
//print_r("fdas");die;

        $query= DB::table('tbl_customer')->where('user_id',$id)->select('tbl_customer.*','tbl_customer_setups.cal_frequnecy')->join('tbl_customer_setups','tbl_customer_setups.customer_id','=','tbl_customer.id','left')->first();

        return $query;
    }

    public function getUserCustomer($id){
//print_r("fdas");die;
        DB::enableQueryLog();

        $query= DB::table('tbl_users as U')
            ->where('U.id',$id)
            ->select('C.id','U.id as user_id','C.customer_name','C.customer_type_id','C.address1','C.address2','C.city','C.state','C.zip_code','C.billing_address','C.shipping_address','C.title','C.customer_telephone','C.customer_email','C.customer_main_telephone','C.customer_main_fax','U.name as user_name','U.email as user_email','CS.cal_frequnecy','G.role_id','CS.shipping')
            ->join('tbl_customer as C','C.id','=','U.customer_id')
            ->join('tbl_group_user as G','U.id','=','G.users_id')
            ->join('tbl_customer_setups as CS','CS.customer_id','=','C.id','left')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    public function getUserCustomermail($id){
//print_r("fdas");die;
        DB::enableQueryLog();

        $query= DB::table('tbl_users as U')
            ->where('U.email',$id)
            ->select('C.id','U.id as user_id','C.customer_name','C.customer_type_id','C.address1','C.address2','C.city','C.state','C.zip_code','C.billing_address','C.shipping_address','C.title','C.customer_telephone','C.customer_email','C.customer_main_telephone','C.customer_main_fax','U.name as user_name','U.email as user_email','CS.cal_frequnecy','G.role_id')
            ->join('tbl_customer as C','C.id','=','U.customer_id')
            ->join('tbl_group_user as G','U.id','=','G.users_id')
            ->join('tbl_customer_setups as CS','CS.customer_id','=','C.id','left')->first();
        //print_r(DB::getQueryLog()); die;
        return $query;
    }

    public function getUser($id){
//print_r("fdas");die;

        $query= DB::table('tbl_users')
            ->select('tbl_users.*','tbl_group_user.*','tbl_users.id as user_id')
            ->join('tbl_group_user','tbl_group_user.users_id','=','tbl_users.id')->where('tbl_users.id',$id)->first();

        return $query;
    }

    function updateUser($data)
    {
        $table = DB::table('tbl_users');;
        if(isset($data['id']) && $data['id'])
        {
            $table->where('id',$data['id'])
                ->update($data);

            return $data['id'];

        }
        else
        {
            $insertId =   $table->insertGetId($data);
            return $insertId;

        }

    }

    public function getUserDetail($id){
//print_r("fdas");die;

        $query= DB::table('tbl_customer')->select('tbl_customer.*','tbl_users.*','tbl_customer.id as customer_id')->join('tbl_users', 'tbl_users.id', '=', 'tbl_customer.user_id')->where('tbl_users.id',$id)->first();

        return $query;
    }

    public function getUserDetailTechnician($id){
//print_r("fdas");die;

        $query= DB::table('tbl_technician')->select('tbl_technician.*','tbl_users.*','tbl_technician.id as technician_id','tbl_technician.email as temail','tbl_technician.first_name as tfname','tbl_technician.last_name as lfname','tbl_technician.city as tcity','tbl_technician.state as tstate','tbl_technician.zip_code as tzip_code','tbl_technician.address as taddress')->join('tbl_users', 'tbl_users.id', '=', 'tbl_technician.user_id')->where('tbl_users.id',$id)->first();

        return $query;
    }

    public function getUserDetailData($id){
//print_r("fdas");die;

        $query= DB::table('tbl_users')->select('tbl_users.*','tbl_group_user.role_id','tbl_users.id as user_id')->join('tbl_group_user', 'tbl_group_user.users_id', '=', 'tbl_users.id')->where('tbl_users.id',$id)->first();

        return $query;
    }

    public function checkRole($id){
//print_r("fdas");die;

        $query= DB::table('tbl_group_user')->where('users_id',$id)->where('user_group_id',2)->first();

        return $query;
    }

    public function servicePlans($cond=array())
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_customer_setups');
        $query->join('tbl_customer','tbl_customer.id','=','tbl_customer_setups.customer_id');
        if(isset($cond['user_id']) && $cond['user_id'])
        {
            $query->where('tbl_customer.user_id',$cond['user_id']);
        }
        $query->select('tbl_customer_setups.plan_id');
        $result = $query->get()->first();
        //print_r(DB::getQueryLog()); die;
        if($result)
        {
           if($result->plan_id)
           {
               $plan_array = explode(',',$result->plan_id);
               $query1 = DB::table('tbl_service_plan');
               $query1->whereIn('id',$plan_array);
               $query1->select('id','service_plan_name','plan_description');
               $result1 = $query1->get();
           }
           else
           {
               $result1 = array();
           }
        }
        else
        {
            $result1 = array();
        }
        return $result1;

    }

    public function frequency($id){
        $query= DB::table('tbl_frequency')->select('id','name')->get();
        return $query;
    }

    public function manufacturer(){
        $query= DB::table('tbl_manufacturer')->select('manufacturer_id','manufacturer_name')->get();
        return $query;
    }
    public function brands($manufacturer_id){
        $query= DB::table('tbl_brand');
        if($manufacturer_id)
        {
            $query->where('manufacturer_id',$manufacturer_id);
        }
        $query->select('brand_id','brand_name');
        $result = $query->get();
        return $result;
    }

    public function getgroup($id)
    {
        $query= DB::table('tbl_group_user')
            ->select('id')
            ->where('tbl_group_user.users_id','=',$id)
            ->first();
        return $query;
    }

    public function saveGroup($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $input['created_by'];
            $result = DB::table('tbl_group_user')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_group_user')->insertGetId($input);
            return $result;
        }
    }

    public function saveUser($input)
    {
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_users')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $result = DB::table('tbl_users')->insertGetId($input);
            return $result;
        }
    }

    public function saveTechnician($input)
    {
        if ($input['id']) {
            $result = DB::table('tbl_technician')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_technician')->insertGetId($input);
            return $result;
        }
    }

    public function allUsers($limit = 0, $offset = 0, $cond = array())
    {


        DB::enableQueryLog();

        $query = DB::table('tbl_users as U');
        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }
        if (isset($cond['search']) && $cond['search'] != '') {

            $likeArrayFields = array('U.name','U.email','U.city','U.state','U.zipcode','U.telephone');
            if (!empty($likeArrayFields)) {

                $flag = true;
                $like = '';
                foreach ($likeArrayFields as $value) {
                    if ($flag) {

                        $like .= $value . " LIKE '%" . trim($cond['search']) . "%' ";
                    } else {
                        $like .= " OR " . $value . " LIKE '%" . trim($cond['search']) . "%' ";
                    }
                    $flag = false;
                }
                $query->whereRaw('(' . $like . ')');
            }
        }
        if (isset($cond['cus_id']) && $cond['cus_id'] != '') {
            $query->where('U.customer_id', $cond['cus_id']);
        }
        if (isset($cond['user_id']) && $cond['user_id'] != '') {
            $query->where('U.id','!=' ,$cond['user_id']);
        }
        $query->join('tbl_customer as C', 'C.id', '=', 'U.customer_id');
        $query->join('tbl_group_user as G', 'U.id', '=', 'G.users_id');
        $query->join('tbl_roles as R', 'R.id', '=', 'G.role_id');
        $query->orderBy('U.id', 'DESC');
        $query->select($cond['select']);

        $result = $query->get();
        //print_r(DB::getQueryLog());die;
        return $result;
    }

    function userListCounts($cond=array())
    {
        $query = DB::table('tbl_users as U');
        if(isset($cond['cus_id']) && $cond['cus_id'] != '')
        {
            $query->where('U.customer_id','=',$cond['cus_id']);
        }
        if (isset($cond['user_id']) && $cond['user_id'] != '') {
            $query->where('U.id','!=' ,$cond['user_id']);
        }
        $result = $query->count();
        return $result;

    }
    public function getUserData($id,$portalUser=false){
//print_r("fdas");die;
        $select = array('tbl_users.id','tbl_users.name','tbl_users.email','tbl_users.address1','tbl_users.address2','tbl_users.city','tbl_users.state','tbl_users.telephone','tbl_group_user.role_id','tbl_users.photo','tbl_users.zipcode','tbl_users.signature','tbl_users.location');

        $query= DB::table('tbl_users');
        $query->select($select);
        $query->join('tbl_group_user','tbl_group_user.users_id','=','tbl_users.id');
        $query->where('tbl_users.id',$id);
        if($portalUser)
        {
            $query->where('tbl_group_user.user_group_id',2);
        }
        $result = $query ->first();
        return $result;
    }

    public function equipmentmodel($id){
        $query= DB::table('tbl_equipment_model')->select('id','model_name')->get();
        return $query;
    }

    public function equipmentmodelmaster($brand_id){
        $query= DB::table('tbl_equipment_model');
        if($brand_id)
        {
            $query->where('tbl_equipment_model.brand_id',$brand_id);
        }
        $query->select('id','model_description as model_name');
        $result = $query->get();
        return $result;
    }

    public function pricingcreteria($planId, $channel)
    {

        $query = DB::table('tbl_service_pricing')->join('tbl_operations','tbl_operations.id','=','tbl_service_pricing.operation')->join('tbl_channel_numbers','tbl_channel_numbers.id','=','tbl_service_pricing.channel')->where([['plan_id', '=', $planId], ['channel', '=', $channel]])->select('tbl_service_pricing.id as pricing_id','tbl_service_pricing.price','tbl_channel_numbers.channel_number as channels','tbl_operations.operation_name')->get();

        return $query;
    }

    public function pricingcreteriasingle($data)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_service_pricing')
            ->join('tbl_operations','tbl_operations.id','=','tbl_service_pricing.operation')
            ->join('tbl_channel_points','tbl_channel_points.id','=','tbl_service_pricing.channel_point')
            ->join('tbl_channel_numbers','tbl_channel_numbers.id','=','tbl_channel_points.point_name')
            ->join('tbl_service_plan','tbl_service_plan.id','=','tbl_service_pricing.plan_id')
            ->where([['tbl_service_pricing.plan_id', '=', $data['plan_id']],
                ['tbl_service_pricing.volume', '=', $data['volume']],
                ['tbl_service_pricing.channel', '=', $data['channel']],
                ['tbl_service_pricing.operation', '=', $data['operation']],
                ['tbl_channel_numbers.id', '=', $data['channelnumber']]])
            ->select('tbl_service_pricing.id as pricing_id','tbl_service_pricing.price','tbl_channel_numbers.channel_number as channels','tbl_operations.operation_name','tbl_service_plan.service_plan_name')
            ->first();
       // print_r(DB::getQueryLog()); die;

        return $query;
    }

    function getShipping($cus_id)
    {
        $query = DB::table('tbl_customer_shipping_address')->where('customer_id',$cus_id)->get();
        return $query;
    }
    function getBilling($cus_id)
    {
        $query = DB::table('tbl_customer_billing_address')->where('customer_id', $cus_id)->get();
        return $query;
    }


        public function getEquipmet($id){
        $query= DB::table('tbl_equipment')->select('equipment_model_id','plan_id')->where('id','=',$id)->first();
        return $query;
    }

    function customerSetups($cus_id)
    {
        $query= DB::table('tbl_customer_setups')->select('plan_id')->where('customer_id','=',$cus_id)->first();
        return $query;
    }
}
