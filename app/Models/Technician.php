<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class Technician extends Model
{
    protected $table = 'tbl_technician';


    public function AllTechnicians($limit = 0, $offset = 0, $order_by = 'c.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_technician as tt');

//        DB::enableQueryLog();

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

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }


        return $result;
    }

    public function AllTechniciansGrid($limit = 0, $offset = 0, $order_by = 'tt.id', $direction = 'ASC', $cond = array(), $count = false)
    {
        $query = DB::table('tbl_technician as tt');

//        DB::enableQueryLog();

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
                            $like .= " OR " . $value . " LIKE '%" . trim($value) . "%' ";
                        }
                        $flag = false;
                    }
                }
                if(array_filter($cond['search'])) {
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }


        return $result;
    }

    public function getTechnician($techId){
        $query = DB::table('tbl_technician')->where('id','=',$techId)->select('*')->first();
        return $query;
    }

    public function saveTechnician($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $userId;
            $result = DB::table('tbl_technician')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;

            $result = DB::table('tbl_technician')->insertGetId($input);
            return $result;
        }
    }
    public function assignTechnicianforDevice($input)
    {
        $userId = Sentinel::getUser()->id;
        if ($input['id']) {
            $input['modified_date'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $userId;
            $result = DB::table('tbl_technician_fordevice')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_date'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $userId;

            $result = DB::table('tbl_technician_fordevice')->insertGetId($input);
            return $result;
        }
    }

    public function reassignTechnician($deviceId)
    {

        $query = DB::table('tbl_technician_fordevice')->where('device_id', '=', $deviceId)->delete();
        return $query;
    }

    public function getTechnicianforDevice($deviceId)
    {

        $query = DB::table('tbl_technician_fordevice')->where('device_id', '=', $deviceId)->get();
        return $query;
    }

    public function unassignedTechnician($deviceId, $deviceModelId)
    {


        if ($deviceModelId != 1) {
            $userquery = DB::table('tbl_technician as tt');


            $userList = $userquery
                ->whereNotIn('tt.id', (function ($query) use ($deviceId, $deviceModelId) {

                    $query->select('ttf.technician_id')->from('tbl_technician_fordevice as ttf')
                        ->join('tbl_device as td', 'td.id', '=', 'ttf.device_id')
                        ->where('td.device_model_id', '=', $deviceModelId)
                        ->where('td.id', '!=', $deviceId);
                }))
                ->get();
            return $userList;

        } else {

            $userquery = DB::table('tbl_technician as tt');

//            $userList = $userquery
//                ->whereNotIn('tt.id', (function ($query) use ($deviceId, $deviceModelId) {
//
//                    $query->select('ttf.technician_id')->from('tbl_technician_fordevice as ttf')
//                        ->join('tbl_device as td', 'td.id', '=', 'ttf.device_id')
//                        //->whereNotIn('td.device_model_id', [1,2,3,4])
//                        ->where('td.device_model_id', '=', $deviceModelId)
//                        ->where('td.id', '!=', $deviceId);
//
//                }))->get();
            $userList = $userquery
                ->get();
            //echo '<pre>';print_r($userList);die;
            return $userList;
        }
    }
    //for deleting user management
    public function deleteTechnician($id)
    {
//        $userId = Sentinel::getUser()->id;
//        $input['modified_date'] = Carbon::now()->toDateTimeString();
//        $input['modified_by'] = $userId;


        $delete = DB::table('tbl_technician')->where('id', $id)->delete();

        if ($delete == 1) {
//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }

    }



}



