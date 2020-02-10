<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Carbon\Carbon;

class Permissionsettings extends Authenticatable
{
    protected $table = 'tbl_roles';

    public function AllGrid($limit = 0, $offset = 0, $order_by = 'p.id', $direction = 'ASC', $cond = array(), $count = false, $likeArray = '', $dateField = '', $filter = array())
    {
        $query = DB::table('tbl_roles');

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
                            $like .= " OR " . $key . " LIKE '%" . trim($value) . "%' ";
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
        $query->where('user_group_id',1);
        if (!$count) {
            $result = $query->get();
        } else {
            $result = $query->count();
        }

        return $result;
    }

    function getRolePermissions($id)
    {
        $query = DB::table('tbl_permission_group');
        $query->where('role_id',$id);
        $result = $query->get();
        return $result;
    }

    public function saveRolePermission($input)
    {

        if ($input['id']) {
            $result = DB::table('tbl_permission_group')->where('id', $input['id'])->update($input);
            return $input['id'];
        } else {
            $result = DB::table('tbl_permission_group')->insertGetId($input);
            return $result;
        }
    }

    function deleteRolePermission($roleId)
    {
        $delete = DB::table('tbl_permission_group')->where('role_id', $roleId)->delete();
        if ($delete == 1) {


//            $result = DB::table('tbl_manufacturer')->where('manufacturer_id', $id)->update($input);
            return true;

        } else {
            return false;
        }
    }

}