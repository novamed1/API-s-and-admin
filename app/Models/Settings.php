<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Settings extends Model
{


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

    public function servicePlans($plan_ids)
    {
//print_r("fdas");die;

        $query = DB::table('tbl_service_plan')
            ->whereIn('id',$plan_ids)
            ->select('*')->get();

        return $query;
    }

    public function specefications($id)
    {
//print_r("fdas");die;

        $query = DB::table('tbl_cal_specification')
            ->where('id',$id)
            ->select('id','cal_specification')->get();

        return $query;
    }

    public function frequency($id)
    {
//print_r("fdas");die;

        $query = DB::table('tbl_frequency')
            ->where('id',$id)
            ->select('id','name')->get();

        return $query;
    }

    public function labelling($label_ids)
    {
//print_r("fdas");die;

        $query = DB::table('tbl_labeling')
            ->whereIn('id',$label_ids)

            ->select('id','name')->get();

        return $query;
    }

    public function paymethods($id)
    {
//print_r("fdas");die;


        $query = DB::table('tbl_pay_method')
            ->where('id',$id)
            ->select('id','name')->get();

        return $query;
    }

    public function shipping($id)
    {
//print_r("fdas");die;

        $query = DB::table('tbl_shipping')
            ->where('id',$id)
            ->select('id','name')->get();

        return $query;
    }

    public function setups($id)
    {
        DB::enableQueryLog();
        $query = DB::table('tbl_customer_setups');
        $query->join('tbl_customer','tbl_customer.id','=','tbl_customer_setups.customer_id');
        $query->join('tbl_pay_method','tbl_pay_method.id','=','tbl_customer_setups.pay_method','left');
        $query->join('tbl_cal_specification','tbl_cal_specification.id','=','tbl_customer_setups.cal_spec','left');
        $query->join('tbl_frequency','tbl_frequency.id','=','tbl_customer_setups.cal_frequnecy','left');
        $query->join('tbl_shipping','tbl_shipping.id','=','tbl_customer_setups.shipping','left');
        $query->where('tbl_customer.user_id',$id);
        $query->select('tbl_customer_setups.cal_spec','asset_label','plan_id','cal_frequnecy','exact_date','plan_id','pay_method','shipping','pay_terms','ship_comments','tbl_pay_method.name as pname','tbl_cal_specification.cal_specification','tbl_frequency.name as fname','tbl_shipping.name as sname');
        $result = $query->get()->first();
        return $result;

    }

    public function saveSetups($input)
    {

        if ($input['customer_id']) {

            $result = DB::table('tbl_customer_setups')->where('customer_id', $input['customer_id'])->update($input);
            return $input['customer_id'];
        }
    }

    public function getcustomer($id){
//print_r("fdas");die;

        $query= DB::table('tbl_customer')
            ->select('tbl_customer.*')
            ->where('tbl_customer.user_id',$id)->first();

        return $query;
    }

    public function saveSiteSettings($input)
    {

        if ($input['id']) {

            $result = DB::table('tbl_site_content')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
//            print_r('hi');die;

            $result = DB::table('tbl_site_content')->insertGetId($input);

            return $result;
        }
    }

}



