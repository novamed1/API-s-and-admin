<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Customer;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use Image;
use Mail;
use App\PHPMailer;

class UserController extends Controller
{
    protected $mail;

    public function __construct()
    {
        $this->users = new Users();
        $this->customer = new Customer();

        $this->mail = new PHPMailer();

    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Users';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tu.*','tr.name as roleName');
            $data = $this->users->AllUsers('', '', 'tu.id', 'DESC', array('select' => $select, 'role' => '1', 'search' => $data['search']), false, array('tu.first_name'));
        } else {
            $select = array('tu.*');
            $data = $this->users->AllUsers('', '', 'tu.id', 'DESC', array('select' => $select, 'role' => '1'));
        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('users.user')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['first_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['email'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['tr.name'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';

        $select = array('tu.*','tr.name as roleName');
        $data = $this->users->AllUsersGrid($param['limit'], $param['offset'], 'tu.id', 'DESC', array('select' => $select, 'role' => '1', 'search' => $search), false, array('tu.first_name'));
        $count = $this->users->AllUsersGrid('', '', 'tu.id', 'DESC', array('select' => $select, 'search' => $search, 'role' => '1', 'count' => true), true, array('tu.first_name'));
//        echo'<pre>';print_r($data);'</pre>';die;




        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->first_name;
                $values[$key]['1'] = $row->email;
                $values[$key]['2'] = $row->roleName;
                $values[$key]['3'] = "<a href=" . url("admin/edituser/" . $row->id) . "><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-User Creaation';

        $roles = DB::table('tbl_roles')->where('user_group_id', '=', 1)->pluck('name', 'id');
        $roles->prepend('Please select the role', '');
        $data = [
            'id' => $id,
            'firstName' => isset($input['first_name']) ? $input['first_name'] : '',
            'emailId' => isset($input['email']) ? $input['email'] : '',
            'password' => isset($input['password']) ? $input['password'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
            'role' => isset($input['role']) ? $input['role'] : '',
        ];


        if ($id) {
            $getAuthor = $this->users->getAuthor($id);

            $getUserGroup = $this->users->getGroupUser($id);

//            echo '<pre>';print_R($getUserGroup);die;

            if (!$getAuthor && !$getUserGroup) {
                return redirect('admin/userlist')->with('message', 'Sorry! Details are not found.');
            } else {

                $data['firstName'] = $getAuthor->first_name;
                $data['password'] = $getAuthor->password;
                $data['role'] = $getUserGroup->role_id;
                $data['emailId'] = $getAuthor->email;
                $data['is_active'] = $getAuthor->is_active;
            }
        }


        $rules = [
            'first_name' => 'required',
            'email' => 'required|email|unique:tbl_users,email,' . $id . ',id',
            'role' => 'required',

        ];


        $messgaes = [
            'name.required' => 'Please give name',
            'email.required' => 'Please give Email Id.',
            'email.email' => 'Please give valid Email Id.',
        ];
        $error = array();

        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($input, $rules, $messgaes);
            if ($checkValid->fails()) {
                $checkStatus = true;
                if (!$id) {
                    $data['image'] = '';
                }

                $error = $checkValid->errors()->all();
            }
        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
        }
        if ($checkStatus) {
            return view('users.addForm')->with('input', $data)->with('roles', $roles)->with('title', $title)->with('errors', $error);
        } else {
            $data = Input::all();





            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $is_active = 1;
            } else {
                $is_active = 0;
            }
            if (!$id) {

                if ($data['password']) {
                    $savearray['password'] = $data['password'];
                } else {
                    $savearray['password'] = str_random(6);/**/

                }
                $userId = Sentinel::register(array(
                    'name' => $data['first_name'],
                    'first_name' => $data['first_name'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'is_active' => $is_active,
                ));
                $saveGroup = array();
                $saveGroup['id'] = false;
                $saveGroup['user_group_id'] = 1;
                $saveGroup['role_id'] = $data['role'];
                $saveGroup['users_id'] = $userId['id'];
                $groupUser = $this->customer->saveUserGroup($saveGroup);

//                echo '<pre>';print_r($input);exit;



            } else {

                $getUserGroup = $this->users->getGroupUser($id);

                $savearray = array();
                $savearray['id'] = $id;
                $savearray['name'] = $data['first_name'];
                $savearray['first_name'] = $data['first_name'];
                $savearray['email'] = $data['email'];
                if ($data['password']) {
                    $savearray['password'] = Hash::make($data['password']);
                }

//                $value = Hash::check('test', $savearray['password']);
////                print_r($value);exit;
                $savearray['is_active'] = $is_active;

                DB::table('tbl_users')->where('id', '=', $id)->update($savearray);

                if($getUserGroup){
                    $saveGroup = array();
                    $saveGroup['id'] = $getUserGroup->id;
                    $saveGroup['user_group_id'] = 1;
                    $saveGroup['role_id'] = $data['role'];
                    $saveGroup['users_id'] = $id;
                    $groupUser = $this->customer->saveUserGroup($saveGroup);
                }


            }
            if ($is_active == 1) {
                $query = DB::table('tbl_email_template');
                $query->where('tplid', '=', 1);
                $result = $query->first();
                $result->tplmessage = str_replace('{SITE_PATH}', "http://54.188.82.17/novamed/admin/login", $result->tplmessage);
                $result->tplmessage = str_replace('{username}', $data['email'], $result->tplmessage);
                $result->tplmessage = str_replace('{password}', $data['password'], $result->tplmessage);
                $result->tplmessage = str_replace('{name}', $data['first_name'], $result->tplmessage);

                $param['message'] = $result->tplmessage;
                $param['email'] =$data['email'];
                $param['name'] = $data['first_name'];
                $param['title'] = $result->tplsubject;

                Mail::send(['html' => 'email/template'], ['data' => $param], function ($message) use ($param) {
                    $message->to($param['email'])->subject
                    ($param['title']);
                });
            }


            if ($id) {
                return redirect('admin/userlist')->with('message', 'Updated Successfully!');
            } else {
                return redirect('admin/userlist')->with('message', 'Added Successfully!');
            }

        }
    }


}

