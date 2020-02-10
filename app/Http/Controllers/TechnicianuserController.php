<?php
namespace App\Http\Controllers;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\Technicianuser;
use JWTAuthException;
use DB;
use Response;
use Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedExceptionException;
class TechnicianuserController extends Controller
{
    private $user;
    public function __construct(Technicianuser $user){
        $this->user = $user;
        $this->technicianModel = new Technicianuser();
    }

    public function index()
    {
        //print_r($this->user);die;
        return response()->json(['auth'=>Auth::user(), 'users'=>User::all()]);
    }


    public function technicianlogin(Request $request){

        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return Response::json([
                    'status' => 0,
                    'message' => 'Invalid username or password'

                ], 400);

            }
        } catch (JWTAuthException $e) {
            return Response::json([
                'status' => 0,
                'message' => 'Failed to create token'

            ], 500);
        }
        $token = compact('token')['token'];
        $user = JWTAuth::toUser($token);
        if($user)
        {
            $checkRole = $this->technicianModel->checkRole($user['id']);
            if(!$checkRole)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'You dont have permission to login'

                ], 422);
            }

            if(isset($user['id']) && $user['id'] != '')
            {
               // $customer =  $this->userModel->getCustomer($user['customer_id']);
                $technician =  $this->technicianModel->getUserTechnician($user['id']);


            }
        }
        else
        {
            $technician=array();
        } //print_r($customer);die;
        return Response::json([
            'status' => 1,
            'token' => $token,
            'user' => $technician

        ], 200);

    }

    public function refreshtoken(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $new_token = null;
        try {
            if (!$new_token = JWTAuth::refresh($token)) {
                return Response::json([
                    'status' => 0,
                    'message' => 'This token has been blacklisted'

                ], 500);

            }
        } catch (\Exception $e) {
            return Response::json([
                'status' => 0,
                'message' => $e->getMessage()

            ], 500);

        }
        return Response::json([
            'status' => 1,
            'token' => $new_token
        ], 200);

    }
    public function getAuthUser(Request $request){
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        return response()->json(['result' => $user]);
    }

    public function createRole(Request $request){

        $role = new Role();
        $role->name = $request->input('name');
        $role->save();

        return response()->json("created");

    }

    public function createPermission(Request $request){

        $viewUsers = new Permission();
        $viewUsers->name = $request->input('name');
        $viewUsers->save();

        return response()->json("created");

    }
    public function assignRole(Request $request){
        $user = User::where('email', '=', $request->input('email'))->first();
        $role = Role::where('name', '=', $request->input('role'))->first();
        //$user->attachRole($request->input('role'));
        $user->roles()->attach($role->id);

        return response()->json("created");
    }

    public function attachPermission(Request $request){
        $role = Role::where('name', '=', $request->input('role'))->first();
        $permission = Permission::where('name', '=', $request->input('name'))->first();
        $role->attachPermission($permission);

        return response()->json("created");
    }

    public function changePassword(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $reqInputs = $request->input();

        $input = [
            'currentPassword' => $reqInputs['currentPassword'],
            'newPassword' => $reqInputs['newPassword'],
            'confirmPassword' => $reqInputs['confirmPassword']
        ];
        $rules = array(

            'currentPassword' => 'required',
            'newPassword' => 'required',
            'confirmPassword' => 'required|same:newPassword'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }
        $user = $this->userModel->getUser($user['id']);
        if($user)
        {
            if(!Hash::check($reqInputs['currentPassword'], $user->password)){
                return Response::json([
                    'status' => 0,
                    'message' => "Your old password is wrong"
                ], 400);
            }
            $save['id'] = $user->id;
            $save['password'] = Hash::make($reqInputs['newPassword']);
            $id = $this->userModel->updateUser($save);
            if($id)
            {
                return Response::json([
                    'status' => 1,
                    'message'   => "Your password has been changed",
//                'OTP'    => $user->OTP,
                ], 200);
            }
        }

    }

    public function servicePlans(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        if($user)
        {
           $getPlans = $this->userModel->servicePlans(array('user_id'=>$user->id));
            return Response::json([
                'status' => 1,
                'data' => $getPlans
            ], 200);
        }
        else
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 404);
        }

    }

    public function frequency(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $reqInputs = $request->input();
        $user = $this->userModel->getUser($user['id']);
        if($user)
        {
            $frequency = $this->userModel->frequency(array('user_id'=>$user->id));
            return Response::json([
                'status' => 1,
                'data' => $frequency
            ], 200);
        }
        else
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 404);
        }

    }

    public function userLogout(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        JWTAuth::invalidate($token);

            return Response::json([
                'status' => 1,
                'message' => 'User has been logout'
            ], 200);




    }

    public function userDetail(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $user = $this->userModel->getUserDetail($user['id']);
        if($user)
        {
            $userdetails = array('user_id'=>$user->user_id,
                'customer_name'=>$user->customer_name,
                'address1'=>$user->address1,
                'address2'=>$user->address2,
                'city'=>$user->city,
                'state'=>$user->state,
                'zip_code'=>$user->zip_code,
                'billing_address'=>$user->billing_address,
                'shipping_address'=>$user->shipping_address,
                'title'=>$user->title,
                'customer_telephone'=>$user->customer_telephone,
                'customer_email'=>$user->customer_telephone,
                'customer_main_telephone'=>$user->customer_main_telephone,
                'customer_main_fax'=>$user->customer_main_fax);

            return Response::json([
                'status' => 1,
                'data'   => $userdetails,
//                'OTP'    => $user->OTP,
            ], 200);
        }

    }

    public function userCreation(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $reqInputs = $request->input();
        $input = [
            'name' => $reqInputs['name'],
            'email' => $reqInputs['email'],
            'password' => $reqInputs['password']
        ];
        $rules = array(

            'name' => 'required',
            'email' => 'required|email|max:255|unique:tbl_users',
            'password' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }

        $newuser = $this->user->create([
            'name' => $reqInputs['name'],
            'email' => $reqInputs['email'],
            'password' => bcrypt($reqInputs['password'])
        ]);
        if($newuser)
        {
           $saveGroup['id']='';
           $saveGroup['user_group_id']=2;
           $saveGroup['role_id']=$reqInputs['role'];
           $saveGroup['users_id']=$newuser['id'];
           $saveGroup['created_by']=$user['id'];
           $this->userModel->saveGroup($saveGroup);

            $userDetail['id'] = $newuser['id'];
            $userDetail['customer_id'] = $user['customer_id'];
            $userDetail['name'] = $reqInputs['name'];
            $userDetail['address1'] = $reqInputs['address1'];
            $userDetail['address2'] = $reqInputs['address2'];
            $userDetail['city'] = $reqInputs['city'];
            $userDetail['state'] = $reqInputs['state'];
            $userDetail['zipcode'] = $reqInputs['zipcode'];
            $userDetail['created_by'] = $user['id'];
            $userDetail['modified_by'] = $user['id'];
            $this->userModel->saveUser($userDetail);
        }

        return Response::json([
            'status' => 1,
            'message'   => 'User has been created',
//                'OTP'    => $user->OTP,
        ], 200);


    }
}