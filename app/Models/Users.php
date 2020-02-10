<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Users extends Model
{
    protected $table = 'tbl_users';

    public function getAuthor($id)
    {
        $user = DB::table('tbl_users')->where('id', $id)->first();
        return $user;

    }

    public function getRoleAuthor($id)
    {
        $user = DB::table('tbl_user_group')->where('id', $id)->first();
        return $user;

    }

public function getRole($id)
    {
        $user = DB::table('tbl_roles')->where('id', $id)->first();
        return $user;

    }


    public function AllUsers($limit = 0, $offset = 0, $order_by = 'tu.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_users as tu');

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
                $likeArrayFields = array('tsp.id');
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
                            $like .= " OR " . $value . " LIKE '%" . trim($cond['search']['keyword']) . "%' ";
//                       print_r($like);die;

                        }
                        $flag = false;
                    }
//                    DB::enableQueryLog();
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }


        if (isset($cond['role']) && $cond['role'] != '') {
            $query->where('tgu.user_group_id', '=', $cond['role']);
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }


        $query->join('tbl_group_user as tgu', 'tgu.users_id', '=', 'tu.id');
        $query->leftjoin('tbl_roles as tr', 'tr.user_group_id', '=', 'tgu.id');
        $query->join('tbl_user_group as tug', 'tug.id', '=', 'tgu.user_group_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }


        return $result;
    }

    public function AllUsersGrid($limit = 0, $offset = 0, $order_by = 'tu.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {


        DB::enableQueryLog();
        $query = DB::table('tbl_users as tu');

        $query->orderBy($order_by, $direction);

        if ($limit > 0) {
            $query->limit($limit);
            $query->offset($offset);
        }


        if (isset($cond['search']) && $cond['search'] != '') {


            if (!empty($cond['search'])) {

                $flag = true;
                $like = '';
                foreach ($cond['search'] as $key => $value) {
                    if ($value) {
                        if ($flag) {

                            $like .= $key . " LIKE '%" . trim($value) . "%' ";
                        } else {
                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
                        }
                        $flag = false;
                    }
                }
                if (array_filter($cond['search'])) {
                    $query->whereRaw('(' . $like . ')');
                }
            }
        }

        if (isset($cond['select']) && $cond['select'] != '') {
            $query->select($cond['select']);
        } else {
            $query->select('*');
        }


        if (isset($cond['role']) && $cond['role'] != '') {
            $query->where('tgu.user_group_id', '=', $cond['role']);
        }


        $query->join('tbl_group_user as tgu', 'tgu.users_id', '=', 'tu.id');
        $query->leftjoin('tbl_roles as tr', 'tr.id', '=', 'tgu.role_id');

        $query->join('tbl_user_group as tug', 'tug.id', '=', 'tgu.user_group_id');

        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }
//
//        $query = DB::getQueryLog();
//        print_r($query);die;


        return $result;
    }


    public function getGroupUser($usergroupId)
    {
        $query = DB::table('tbl_group_user')->where('users_id', '=', $usergroupId)->select('*')->first();
        return $query;
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
}
