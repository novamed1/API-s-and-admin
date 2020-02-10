<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Testplan extends Model
{
    protected $table = 'tbl_equipment_model';


    public function AllTestplan($limit = 0, $offset = 0, $order_by = 'tp.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_test_plan as tp');

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
                        $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
                    }
                    $flag = false;
                }
                $query->whereRaw('(' . $like . ')');
            }
        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_equipment_model', 'tbl_equipment_model.id', '=', 'tp.model_id');

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
     
    public function saveTestPlan($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $userId;
            $result = DB::table('tbl_test_plan')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;

            $result = DB::table('tbl_test_plan')->insertGetId($input);
            return $result;
        }
    }

    public function saveTestPlanTolerence($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $userId;
            $result = DB::table('tbl_test_tolerance')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;
            $result = DB::table('tbl_test_tolerance')->insertGetId($input);
            return $result;
        }
    }
    public function getTest($Id)
    {
        $result = DB::table('tbl_test_plan')->where('tbl_test_plan.id', '=', $Id)->join('tbl_equipment_model', 'tbl_equipment_model.id', '=', 'tbl_test_plan.model_id')->select('tbl_equipment_model.*', 'tbl_test_plan.*','tbl_test_plan.id as testPlanId')->first();
        return $result;
    }
    public function getLimitTolerences($modelId)
    {
        $query= DB::table('tbl_limit_tolerance')->where('model_id',$modelId)->get();
        return $query;
    }

    public function getTestTolerences($id)
    {
        $query= DB::table('tbl_test_tolerance')->where('test_plan_id',$id)->get();
        return $query;
    }
   
}



