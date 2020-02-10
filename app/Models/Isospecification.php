<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException as QueryException;
use DB;
use Input;
use Carbon\Carbon;
use DateTime;

class Isospecification extends Model
{

    public function AllIsoSpecifications($limit = 0, $offset = 0, $order_by = 'is.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_iso_specifications as is');

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
                $likeArrayFields = array('te.id');
            }
            if (isset($cond['search']['keyword']) && $cond['search']['keyword'] != '') {
                if (!empty($likeArray)) {

                    $flag = true;
                    $like = '';
                    foreach ($likeArray as $value) {
                        if ($flag) {

                            $like .= $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;
                        } else {
                            $like .= " AND " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;

                        }
                        $flag = false;
                    }
//                    DB::enableQueryLog();
                    $query->whereRaw('(' . $like . ')');
                }
            }
            if (isset($cond['search']['keyword']) && $cond['search']['keyword'] != '') {
                if (!empty($likeArray)) {

                    $flag = true;
                    $like = '';
                    foreach ($likeArray as $value) {
                        if ($flag) {

                            $like .= $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;
                        } else {
                            $like .= " AND " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;

                        }
                        $flag = false;
                    }
//                    DB::enableQueryLog();
                    $query->whereRaw('(' . $like . ')');
                }
            }

        }


        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }
        $query->join('tbl_channels as c', 'c.id', '=', 'is.channel_id');
        $query->join('tbl_operations as o', 'o.id', '=', 'is.operation_id');
        $query->join('tbl_volume as v', 'v.id', '=', 'is.volume_id');
        $query->join('tbl_units as u', 'u.id', '=', 'is.unit');

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

    public function AllIsoSpecificationsGrid($limit = 0, $offset = 0, $order_by = 'te.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    { DB::enableQueryLog();

       // $likeArray = array('model_name','channel','operation_name','manufacturer_name','model_price');
        $query = DB::table('tbl_iso_specifications as is');

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
//                       print_r($like);die;
                            } else {
                                $like .= " AND " . $key . " LIKE '%" . trim($value) . "%' ";
//                       print_r($like);die;

                            }
                            $flag = false;
                        }
                    }
//                    DB::enableQueryLog();
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
        $query->join('tbl_channels as c', 'c.id', '=', 'is.channel_id');
        $query->join('tbl_channel_numbers as ch', 'ch.id', '=', 'is.channel_number');
        $query->join('tbl_operations as o', 'o.id', '=', 'is.operation_id');
        $query->join('tbl_volume as v', 'v.id', '=', 'is.volume_id');
        $query->join('tbl_units as u', 'u.id', '=', 'is.unit');

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


    public function savespec($input,$user_id)
    {

        if ($input['id']) {
            $input['modified_on'] = Carbon::now()->toDateTimeString();
            $input['modified_by'] = $user_id;
            $result = DB::table('tbl_iso_specifications')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $input['created_on'] = Carbon::now()->toDateTimeString();
            $input['created_by'] = $user_id;

            $result = DB::table('tbl_iso_specifications')->insertGetId($input);
            return $result;
        }
    }

    public function savetolerance($input)
    {

        if ($input['id']) {

            $result = DB::table('tbl_iso_limit_tolerance')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_iso_limit_tolerance')->insertGetId($input);
            return $result;
        }
    }

    public function getiso($equipmentId)
    {
        $query = DB::table('tbl_iso_specifications')->where('id', '=', $equipmentId)->select('*')->first();
        return $query;
    }
    function isotolerances($spec_id)
    {
        $query = DB::table('tbl_iso_limit_tolerance')->where('specification_id', '=', $spec_id)->select('*')->get();
        return $query;
    }

    function gettolerancechange($post)
    {
        $query = DB::table('tbl_iso_limit_tolerance');
        $query->where([
            ['id', '=', $post['id']],
            ['target_value', '=', $post['target']],
            ['accuracy', '=', $post['accuracy']],
            ['precision', '=', $post['precision']],
            ['accuracy_ul', '=', $post['accuracy_ul']],
            ['precesion_ul', '=', $post['precesion_ul']]
        ]);
        return $query->first();
    }

    function checkcombination($post)
    {
        $query = DB::table('tbl_iso_specifications');
        if($post['volumeRange'])
        {
            $query->where([
                ['channel_id', '=', $post['channel']],
                ['operation_id', '=', $post['operation']],
                ['volume_id', '=', $post['volume']],
                ['volume_value', '=', $post['volumeRange']]
            ]);
        }
        else
        {
            $query->where([
                ['channel_id', '=', $post['channel']],
                ['operation_id', '=', $post['operation']],
                ['volume_id', '=', $post['volume']],
                ['volume_value', '=', $post['volumeFrom']]
            ]);
        }

        $query->where('id','!=',$post['id']);
        return $query->get();
    }

}

    
   




