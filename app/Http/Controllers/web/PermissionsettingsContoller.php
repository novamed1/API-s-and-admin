<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use DateInterval;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Permissionsettings;

class PermissionsettingsContoller extends Controller
{

    public function __construct()
    {
        $this->permission = new Permissionsettings();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Permisssion Settings';
        $temp = array();
        return view('permission.permission')->with('title', $title);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['name'] = isset($input['sSearch_0'])?$input['sSearch_0']:'';

        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('*');
        $data = $this->permission->AllGrid($param['limit'], $param['offset'], 'id', 'DESC', array('select' => $select, 'search' => $search),
            false);

        $count = $this->permission->AllGrid($param['limit'], $param['offset'], 'id', 'DESC', array('select' => $select, 'search' => $search,'count'=>true),
            true);
        if($data)
        { $values = array();
            $i = 0;
            foreach ($data as $key=>$row)
            {
                $values[$key]['0'] = $row->name;
                $values[$key]['1'] = "<a href=".url('admin/editPermissionSettings/'.$row->id)." class=''><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho'=>$input['sEcho'],'iTotalRecords'=>$count,'iTotalDisplayRecords'=>$count,'aaData'=>$values));

    }

    function editPermissionSettings(Request $request,$id)
    {
        $title = 'Novamed-Permission Settings';
        $rolequery = DB::table('tbl_roles');
        $rolequery->where('id',$id);
        $role = $rolequery->get()->first();
        $roleName = (isset($role->name) && $role->name)?$role->name:'';
        $menuquery = DB::table('tbl_menus');
        $menu = $menuquery->get();
        $get_role_permissions = $this->permission->getRolePermissions($id);
        $temp_role_permission = array();
        if(count($get_role_permissions))
        {
            foreach ($get_role_permissions as $key=>$row)
            {
                $temp_role_permission[$key] = $row->permission_id;
            }

        }
        $temp = array();
        if($menu)
        {
            foreach ($menu as $key=>$row)
            {

                $submenuquery = DB::table('tbl_permissions');
                $submenuquery->where('menu',$row->id)->where('is_active','=',1);
                $submenu = $submenuquery->get();
                $subtemp = array();
                if(count($submenu))
                {
                    $temp[$key]['menu'] = $row->name;

                    foreach ($submenu as $subkey=>$subrow)
                    {
                        $subtemp[$subkey]['id'] = $subrow->id;
                        $subtemp[$subkey]['name'] = $subrow->display_name;
                    }
                    $temp[$key]['submenu'] = $subtemp;

                }


            }

        }
        // echo'<pre>';print_r($temp);'</pre>';die;
        return view('permission.permissionsettings')->with('title', $title)->with('data',$temp)->with('role',$roleName)->with('id',$id)->with('rolePermissions',$temp_role_permission);

    }

    function updatePermissionSettings(Request $request)
    {
        $post = $request->input();
        $role_id = $post['role_id'];
        $get_role_permissions = $this->permission->getRolePermissions($role_id);
        $temp_role_permission = array();
        if(count($get_role_permissions))
        {
            $this->permission->deleteRolePermission($role_id);

        }
        $permissions_post = $post['permissions'];
        if($permissions_post)
        {
            foreach ($permissions_post as $key=>$row)
            {
                $save['id'] = false;
                $save['permission_id'] = $row;
                $save['role_id'] = $role_id;
                $this->permission->saveRolePermission($save);

            }
        }


        return redirect('admin/permissionsettings')->with('message', 'Permission settings updated');

        //echo'<pre>';print_r($permissions_post);'</pre>';die;




    }
}
