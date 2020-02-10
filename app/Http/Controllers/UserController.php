<?php
namespace App\Http\Controllers;
use App\Permission;
use App\Role;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\User;
use JWTAuthException;
use DB;
use Response;
use Validator;
use Illuminate\Support\Facades\Hash;
use Image;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedExceptionException;
use App\Models\Equipment;
class UserController extends Controller
{
    private $user;
    public function __construct(User $user){
        $this->user = $user;
        $this->userModel = new User();
        $this->equipment = new Equipment();
    }

    public function index()
    {
        //print_r($this->user);die;
        return response()->json(['auth'=>Auth::user(), 'users'=>User::all()]);
    }
    public function register(Request $request){
        $user = $this->user->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);
        return response()->json(['status'=>true,'message'=>'User created successfully','data'=>$user]);
    }

    public function login(Request $request){



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
        $proxy = array();
        //print_r($user['id']);die;
        if($user)
        {
            $checkRole = $this->userModel->checkRole($user['id']);
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
                $customer =  $this->userModel->getUserCustomer($user['id']);
                $role = DB::table('tbl_group_user')
                    ->where('tbl_group_user.users_id',$user['id'])->where('user_group_id',2)->first();
                $customer->role_id = (isset($role->role_id)&&$role->role_id)?$role->role_id:'';

            }
            $proxy[0] = $user->id;
            $proxy[1] = $user->email;
            $proxy_token = base64_encode(serialize($proxy));
        }
        else
        {
            $customer=array();
            $proxy_token = '';
        } //print_r($customer);die;
        return Response::json([
            'status' => 1,
            'token' => $token,
            'user' => $customer,
            'proxy_url' => env('url_path').'/proxy/'.$proxy_token

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
            $save['id'] = $user->user_id;
            $save['password'] = Hash::make($reqInputs['newPassword']);// print_r($save);die;
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

    public function changepassworduser(Request $request)
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
            'newpassword' => $reqInputs['newpassword'],
            'cnfpassword' => $reqInputs['cnfpassword']
        ];
        $rules = array(

            'newpassword' => 'required',
            'cnfpassword' => 'required|same:newpassword'
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
        $user = $this->userModel->getUser($reqInputs['id']);
        if($user)
        {
            $save['id'] = $user->user_id;
            $save['password'] = Hash::make($reqInputs['newpassword']);
            //print_r($save);die;
            $id = $this->userModel->updateUser($save);
            if($id)
            {
                return Response::json([
                    'status' => 1,
                    'message'   => "Password has been changed",
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
           $getPlans = $this->userModel->servicePlans(array('user_id'=>$user->user_id));
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

    public function manufacturer(Request $request)
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
            $manufacturer = $this->userModel->manufacturer();
            return Response::json([
                'status' => 1,
                'data' => $manufacturer
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

    public function brands(Request $request)
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
        $manufacturer_id = (isset($reqInputs['manufacturer_id'])&&$reqInputs['manufacturer_id'])?$reqInputs['manufacturer_id']:'';
        $user = $this->userModel->getUser($user['id']);
        if($user)
        {
            $brands = $this->userModel->brands($manufacturer_id);
            return Response::json([
                'status' => 1,
                'data' => $brands
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

    public function equipmentmodel(Request $request)
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
        $brand_id = (isset($reqInputs['brand_id'])&&$reqInputs['brand_id'])?$reqInputs['brand_id']:'';
        $user = $this->userModel->getUser($user['id']);
        if($user)
        {
            $model = $this->userModel->equipmentmodelmaster($brand_id);
            return Response::json([
                'status' => 1,
                'data' => $model
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

    public function pricingcreteria(Request $request)
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
        $plan_id = isset($reqInputs['plan_id']) ? $reqInputs['plan_id'] : '';
        $model_id = isset($reqInputs['equipment_id']) ? $reqInputs['equipment_id'] : '';
        $getModelId = $this->userModel->getEquipmet($model_id);
        $model_id_pricing = isset($reqInputs['model_id']) ? $reqInputs['model_id'] : $getModelId->equipment_model_id;
        $user = $this->userModel->getUser($user['id']);
        if($user)
        {

            $getModel = $this->equipment->getmodel($model_id_pricing);
            $data['volume'] = $getModel->volume;
            $data['operation'] = $getModel->brand_operation;
            $data['channel'] = $getModel->channel;
            $data['channelnumber'] = $getModel->channel_number;
            $data['plan_id'] = $plan_id;
            $channel = $getModel->channel_number;
            //$pricing = $this->userModel->pricingcreteria($plan_id,$channel);
            $pricing = $this->userModel->pricingcreteriasingle($data);
            if($pricing)
            {
                return Response::json([
                    'status' => 1,
                    'data' => $pricing
                ], 200);
            }
            else
            {
                return Response::json([
                    'status' => 0,
                    'data' => array()
                ], 200);
            }

        }
        else
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 404);
        }

    }

    public function calfoundstatus(Request $request)
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
            $status[0] = ['id'=>1,'value'=>'Passed'];
            $status[1] = ['id'=>2,'value'=>'Fail'];
            $status[2] = ['id'=>3,'value'=>'New'];

            return Response::json([
                'status' => 1,
                'data' => $status
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

        $user = $this->userModel->getUserDetailData($user['id']);
        $role = DB::table('tbl_group_user')
               ->where('tbl_group_user.users_id',$user->id)->where('user_group_id',2)->first();
        $customer = $this->userModel->getUserDetail($user->id);
        $customerDetail = DB::table('tbl_customer')->select('customer_name')->where('id',$user->customer_id)->first();
        if($user)
        {
            $gallery = $_SERVER['DOCUMENT_ROOT'] . '/novamed//public/users/'.$user->photo;
            // print_r($gallery);die;
            $temp = array();
            if(file_exists($gallery) && $user->photo)
            {
                $filePath = 'public/users';
                $path = env('file_path') . '/' . $filePath;

                $temp['default'] = $path . '/small/' . $user->photo;
                $temp['large'] = $path . '/large/' . $user->photo;
                $temp['medium'] = $path . '/medium/' . $user->photo;
                $temp['small'] = $path . '/small/' . $user->photo;
                $temp['thumb'] = $path . '/thumbnails/' . $user->photo;
            }
            else
            {
                $filePath = 'public/users/default';
                $path = env('file_path') . '/' . $filePath;
                $temp['default'] = $path.'/Unknown.png';
            }
            $userdetails = array('user_id'=>$user->user_id,
                'customer_name'=>$customerDetail->customer_name,
                'name'=>$user->name,
                'address1'=>"",
                'address2'=>"",
                'city'=>$user->city,
                'state'=>$user->state,
                'zip_code'=>$user->zipcode,
                'billing_address'=>"",
                'shipping_address'=>"",
                'title'=>"",
                'customer_telephone'=>"",
                'customer_email'=>"",
                'customer_main_telephone'=>"",
                'customer_main_fax'=>"",
                'role_id'=>(isset($role->role_id)&&$role->role_id)?$role->role_id:'',
                'role'=>$role->role_id==1?'Admin':'User',
                'photo'=>$temp);


            return Response::json([
                'status' => 1,
                'data'   => $userdetails,
//                'OTP'    => $user->OTP,
            ], 200);
        }

    }

    public function userDetailTechnician(Request $request)
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

        $user = $this->userModel->getUserDetailData($user['id']);
        $technician = $this->userModel->getUserDetailTechnician($user->id);
        if($user)
        {

            // print_r($gallery);die;
            $temp = array();
           // $gallery = env('file_path') . '/public/users/'.$user->photo;
            $gallery = $_SERVER['DOCUMENT_ROOT'] . '/novamed//public/users/'.$user->photo;
            // print_r($gallery);die;
            $temp = array();
            if(file_exists($gallery) && $user->photo)
            {
                $filePath = 'public/users';
                $path = env('file_path') . '/' . $filePath;

                $temp['default'] = $path . '/small/' . $user->photo;
                $temp['large'] = $path . '/large/' . $user->photo;
                $temp['medium'] = $path . '/medium/' . $user->photo;
                $temp['small'] = $path . '/small/' . $user->photo;
                $temp['thumb'] = $path . '/thumbnails/' . $user->photo;
            }
            else
            {
                $filePath = 'public/users/default';
                $path = env('file_path') . '/' . $filePath;
                $temp['default'] = $path.'/Unknown.png';
            }

            $userdetails = array('user_id'=>$user->user_id,
                'first_name'=>$technician->tfname,
                'last_name'=>$technician->lfname,
                'phone_number'=>$technician->phone_number,
                'email'=>$technician->temail,
                'address'=>$technician->taddress,
                'city'=>$technician->tcity,
                'state'=>$technician->tstate,
                'zip_code'=>$technician->tzip_code,
                'photo'=>$temp
                );


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

        $reqInputs = $request->input(); //echo '<pre>';print_r($reqInputs);die;
        $input = [
            'name' => $reqInputs['name'],
            'email' => $reqInputs['email'],
            'password' => isset($reqInputs['password'])?$reqInputs['password']:''
        ];
        if(!$reqInputs['id'])
        {
            $rules = array(

                'name' => 'required',
                'email' => 'required|email|max:255|unique:tbl_users',
                'password' => 'required'
            );
        }
        else
        {
            $rules = array(

                'name' => 'required',
                'email' => 'required|email|max:255'
            );
        }

        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {
            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::json([
                'status' => 0,
                'message' => $checkValid->errors()->all()
            ], 400);
        }
        if(!$reqInputs['id'])
        {
            $newuser = $this->user->create([
                'name' => $reqInputs['name'],
                'email' => $reqInputs['email'],
                'password' => bcrypt($reqInputs['password'])
            ]);
            $userId = $newuser['id'];
            $msg = 'User has been created';
        }
        else
        {

            $newuser = DB::table('tbl_users')->where('id',$reqInputs['id'])->first();
            $userId = $newuser->id;
            $msg = 'User has been updated';
        }

        //$newuser = 1;
        if($newuser)
        {

            $mainPath = public_path() . '/users/';
            $location = $mainPath;
            $trimmedLocation = str_replace('\\', '/', $location);
            if (isset($reqInputs['photo']) && $reqInputs['photo']) {
                $photo = $reqInputs['photo'];
                $offset1 = strpos($photo, ',');
                $tmp = base64_decode(substr($photo, $offset1));
                $memType = $this->_file_mime_type($tmp);
                $fileType = explode('/', $memType);
                $fileType = $fileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
                $imageName = 'user' . '-' . uniqid() . '.' . $fileType;


                //    image upload
                //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

                $filepath = $trimmedLocation;
                if (!is_dir($filepath)) {
                    return Response::json([
                        'status' => 0,
                        'message' => 'The path you given was invalid'
                    ], 400);
                }
                $uploadedFile = file_put_contents($filepath . '/' . $imageName, $tmp);

                if ($uploadedFile) {
                    if (is_file($filepath . '/' . $imageName)) {
                        $imagesize = getimagesize($filepath . '/' . $imageName);
                        $width = $imagesize[0];
                        $height = $imagesize[1];

                        $largeWidth = $width;
                        $mediumWidth = $width;
                        $smallWidth = $width;
                        $extralargeWidth = $width;
                        $iconWidth = $width;
                        $thumbnailWidth = $width;
                        if ($width > 425) {
                            $largeWidth = 425;
                        }
                        $destinationLargePath = public_path('users/large');
                        Image::make($filepath . '/' . $imageName)->resize(548, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationLargePath . '/' . $imageName);
                        if ($width > 375) {
                            $mediumWidth = 425;

                        }
                        $destinationMediumPath = public_path('users/medium');
                        Image::make($filepath . '/' . $imageName)->resize($mediumWidth, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationMediumPath . '/' . $imageName);
                        if ($width > 320) {

                            $smallWidth = 320;
                        }
                        $destinationSmallPath = public_path('users/small');
                        Image::make($filepath . '/' . $imageName)->resize($smallWidth, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationSmallPath . '/' . $imageName);
                        if ($width > 200) {

                            $thumbnailWidth = 200;
                        }
                        $destinationThumbPath = public_path('users/thumb');
                        Image::make($filepath . '/' . $imageName)->resize($thumbnailWidth, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationThumbPath . '/' . $imageName);

                        if ($width > 64) {
                            $iconWidth = 64;
                        }
                        $destinationIconPath = public_path('users/icon');
                        Image::make($filepath . '/' . $imageName)->resize($iconWidth, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationIconPath . '/' . $imageName);

                    }

                    $photo =$imageName;
                }
                else
                {
                    $photo = '';
                }

                $userDetail['photo'] = $photo;

            }

            if (isset($reqInputs['signature']) && $reqInputs['signature']) {
                $signaturemainPath = public_path() . '/users/signature/default';
                $signaturelocation = $signaturemainPath;
                $signaturetrimmedLocation = str_replace('\\', '/', $signaturelocation);

                $signature = $reqInputs['signature'];
                $signatureoffset1 = strpos($signature, ',');
                $signaturetmp = base64_decode(substr($signature, $signatureoffset1));
                $signaturememType = $this->_file_mime_type($signaturetmp);
                $signaturefileType = explode('/', $signaturememType);
                $signaturefileType = $signaturefileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
                $signatureimageName = 'usersignature' . '-' . uniqid() . '.' . $signaturefileType;


                //    image upload
                //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

                $signaturefilepath = $signaturetrimmedLocation;
                if (!is_dir($signaturefilepath)) {
                    return Response::json([
                        'status' => 0,
                        'message' => 'The path you given was invalid'
                    ], 400);
                }
                $signatureuploadedFile = file_put_contents($signaturefilepath . '/' . $signatureimageName, $signaturetmp);

                if ($signatureuploadedFile) {
                    if (is_file($signaturefilepath . '/' . $signatureimageName)) {
                        $signatureimagesize = getimagesize($signaturefilepath . '/' . $signatureimageName);
                        $signaturewidth = $signatureimagesize[0];
                        $signatureheight = $signatureimagesize[1];

                        $signaturelargeWidth = $signaturewidth;
                        $signaturemediumWidth = $signaturewidth;
                        $signaturesmallWidth = $signaturewidth;
                        $signatureextralargeWidth = $signaturewidth;
                        $signatureiconWidth = $signaturewidth;
                        $signaturethumbnailWidth = $signaturewidth;
                        $signaturedestinationLargePath = public_path('users/signature/large');
                        $signaturewidth = 760;
                        $signatureheight = 480;
                        $canvas = Image::canvas($signaturewidth, $signatureheight,'000000');
                        $image = Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signaturewidth, $signatureheight,
                            function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        $canvas->insert($image, 'center');
                        // pass the full path. Canvas overwrites initial image with a logo
                        $canvas->save($signaturedestinationLargePath . '/' . $signatureimageName);

                        if ($signaturewidth > 375) {
                            $signauremediumWidth = 425;

                        }
                        $signaturedestinationMediumPath = public_path('users/signature/medium');
                        Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signauremediumWidth, null, function ($constraint) use ($signatureimageName) {
                            $constraint->aspectRatio();

                        })->save($signaturedestinationMediumPath . '/' . $signatureimageName);

                        if ($signaturewidth > 320) {

                            $signaturesmallWidth = 320;
                        }
                        $signaturedestinationSmallPath = public_path('users/signature/small');
                        Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signaturesmallWidth, null, function ($constraint) use ($signatureimageName) {
                            $constraint->aspectRatio();

                        })->save($signaturedestinationSmallPath . '/' . $signatureimageName);



                        if ($signaturewidth > 200) {

                            $signaturethumbnailWidth = 200;
                        }
                        $signaturedestinationThumbPath = public_path('users/signature/thumb');
                        Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signaturethumbnailWidth, null, function ($constraint) use ($signatureimageName) {
                            $constraint->aspectRatio();

                        })->save($signaturedestinationThumbPath . '/' . $signatureimageName);

                        if ($signaturewidth > 64) {
                            $signatureiconWidth = 64;
                        }
                        $signaturedestinationIconPath = public_path('users/signature/icon');
                        Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signatureiconWidth, null, function ($constraint) use ($signatureimageName) {
                            $constraint->aspectRatio();

                        })->save($signaturedestinationIconPath . '/' . $signatureimageName);

                    }

                    $signaturephoto =$signatureimageName;
                }
                else
                {
                    $signaturephoto = '';
                }

                $userDetail['signature'] = $signaturephoto;
            }



           if(!$reqInputs['id'])
           {
               $saveGroup['id']='';
               $saveGroup['user_group_id']=2;
               $saveGroup['role_id']=$reqInputs['role'];
               $saveGroup['users_id']=$userId;
               $saveGroup['created_by']=$user['id'];
               $this->userModel->saveGroup($saveGroup);
           }
           else
           {
               $group = $this->userModel->getgroup($userId);
               $saveGroup['id']=$group->id;
               //$saveGroup['user_group_id']=2;
               $saveGroup['role_id']=$reqInputs['role'];
               $saveGroup['users_id']=$userId;
               $saveGroup['created_by']=$user['id'];
               $this->userModel->saveGroup($saveGroup);
           }


            $userDetail['id'] = $userId;
            $userDetail['customer_id'] = $user['customer_id'];
            $userDetail['name'] = $reqInputs['name'];
            $userDetail['address1'] = $reqInputs['address1'];
            $userDetail['address2'] = $reqInputs['address2'];
            $userDetail['city'] = $reqInputs['city'];
            $userDetail['state'] = $reqInputs['state'];
            $userDetail['zipcode'] = $reqInputs['zipcode'];
            $userDetail['telephone'] = $reqInputs['telephone'];
            $userDetail['location'] = isset($reqInputs['location'])?$reqInputs['location']:'';

            $userDetail['created_by'] = $user['id'];
            $userDetail['modified_by'] = $user['id'];
            //echo'<pre>';print_r($userDetail);'</pre>';die;
            $this->userModel->saveUser($userDetail);
        }

        return Response::json([
            'status' => 1,
            'message'   => $msg,
//                'OTP'    => $user->OTP,
        ], 200);


    }


    public function edittechnician(Request $request)
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
            'first_name' => $reqInputs['first_name']
        ];
        $rules = array(

                'first_name' => 'required'
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

            $save['id'] = $user['id'];
            $newuser = $this->userModel->saveUser($save);
            $userId = $newuser;
            $msg = 'Profile has been updated';



        //$newuser = 1;
        if($newuser)
        {

            $mainPath = public_path() . '/users/';
            $location = $mainPath;
            $trimmedLocation = str_replace('\\', '/', $location);
            if (isset($reqInputs['photo']) && $reqInputs['photo']) {
                $photo = $reqInputs['photo'];
                $offset1 = strpos($photo, ',');
                $tmp = base64_decode(substr($photo, $offset1));
                $memType = $this->_file_mime_type($tmp);
                $fileType = explode('/', $memType);
                $fileType = $fileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
                $imageName = 'user' . '-' . uniqid() . '.' . $fileType;


                //    image upload
                //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

                $filepath = $trimmedLocation;
                if (!is_dir($filepath)) {
                    return Response::json([
                        'status' => 0,
                        'message' => 'The path you given was invalid'
                    ], 400);
                }
                $uploadedFile = file_put_contents($filepath . '/' . $imageName, $tmp);

                if ($uploadedFile) {
                    if (is_file($filepath . '/' . $imageName)) {
                        $imagesize = getimagesize($filepath . '/' . $imageName);
                        $width = $imagesize[0];
                        $height = $imagesize[1];

                        $largeWidth = $width;
                        $mediumWidth = $width;
                        $smallWidth = $width;
                        $extralargeWidth = $width;
                        $iconWidth = $width;
                        $thumbnailWidth = $width;
                        if ($width > 425) {
                            $largeWidth = 425;
                        }
                        $destinationLargePath = public_path('users/large');
                        Image::make($filepath . '/' . $imageName)->resize(548, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationLargePath . '/' . $imageName);
                        if ($width > 375) {
                            $mediumWidth = 425;

                        }
                        $destinationMediumPath = public_path('users/medium');
                        Image::make($filepath . '/' . $imageName)->resize($mediumWidth, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationMediumPath . '/' . $imageName);
                        if ($width > 320) {

                            $smallWidth = 320;
                        }
                        $destinationSmallPath = public_path('users/small');
                        Image::make($filepath . '/' . $imageName)->resize($smallWidth, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationSmallPath . '/' . $imageName);
                        if ($width > 200) {

                            $thumbnailWidth = 200;
                        }
                        $destinationThumbPath = public_path('users/thumb');
                        Image::make($filepath . '/' . $imageName)->resize($thumbnailWidth, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationThumbPath . '/' . $imageName);

                        if ($width > 64) {
                            $iconWidth = 64;
                        }
                        $destinationIconPath = public_path('users/icon');
                        Image::make($filepath . '/' . $imageName)->resize($iconWidth, null, function ($constraint) use ($imageName) {
                            $constraint->aspectRatio();

                        })->save($destinationIconPath . '/' . $imageName);

                    }

                    $photo =$imageName;
                }
                else
                {
                    $photo = '';
                }

                $userUpdate['id'] = $userId;

                $userUpdate['photo'] = $photo;
                $this->userModel->saveUser($userUpdate);

            }

            if (isset($reqInputs['signature']) && $reqInputs['signature']) {
                $signaturemainPath = public_path() . '/users/signature/default';
                $signaturelocation = $signaturemainPath;
                $signaturetrimmedLocation = str_replace('\\', '/', $signaturelocation);

                $signature = $reqInputs['signature'];
                $signatureoffset1 = strpos($signature, ',');
                $signaturetmp = base64_decode(substr($signature, $signatureoffset1));
                $signaturememType = $this->_file_mime_type($signaturetmp);
                $signaturefileType = explode('/', $signaturememType);
                $signaturefileType = $signaturefileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
                $signatureimageName = 'usersignature' . '-' . uniqid() . '.' . $signaturefileType;


                //    image upload
                //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

                $signaturefilepath = $signaturetrimmedLocation;
                if (!is_dir($signaturefilepath)) {
                    return Response::json([
                        'status' => 0,
                        'message' => 'The path you given was invalid'
                    ], 400);
                }
                $signatureuploadedFile = file_put_contents($signaturefilepath . '/' . $signatureimageName, $signaturetmp);
                //print_r($signatureuploadedFile);die;
                if ($signatureuploadedFile) {
                    if (is_file($signaturefilepath . '/' . $signatureimageName)) {
                        $signatureimagesize = getimagesize($signaturefilepath . '/' . $signatureimageName);
                        $signaturewidth = $signatureimagesize[0];
                        $signatureheight = $signatureimagesize[1];

                        $signaturelargeWidth = $signaturewidth;
                        $signaturemediumWidth = $signaturewidth;
                        $signaturesmallWidth = $signaturewidth;
                        $signatureextralargeWidth = $signaturewidth;
                        $signatureiconWidth = $signaturewidth;
                        $signaturethumbnailWidth = $signaturewidth;
                        $signaturedestinationLargePath = public_path('users/signature/large');
                        $signaturewidth = 760;
                        $signatureheight = 480;
                        $canvas = Image::canvas($signaturewidth, $signatureheight,'000000');
                        $image = Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signaturewidth, $signatureheight,
                            function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        $canvas->insert($image, 'center');
                        // pass the full path. Canvas overwrites initial image with a logo
                        $canvas->save($signaturedestinationLargePath . '/' . $signatureimageName);

                        if ($signaturewidth > 375) {
                            $signauremediumWidth = 425;

                        }
                        $signaturedestinationMediumPath = public_path('users/signature/medium');
                        Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signauremediumWidth, null, function ($constraint) use ($signatureimageName) {
                            $constraint->aspectRatio();

                        })->save($signaturedestinationMediumPath . '/' . $signatureimageName);

                        if ($signaturewidth > 320) {

                            $signaturesmallWidth = 320;
                        }
                        $signaturedestinationSmallPath = public_path('users/signature/small');
                        Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signaturesmallWidth, null, function ($constraint) use ($signatureimageName) {
                            $constraint->aspectRatio();

                        })->save($signaturedestinationSmallPath . '/' . $signatureimageName);



                        if ($signaturewidth > 200) {

                            $signaturethumbnailWidth = 200;
                        }
                        $signaturedestinationThumbPath = public_path('users/signature/thumb');
                        Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signaturethumbnailWidth, null, function ($constraint) use ($signatureimageName) {
                            $constraint->aspectRatio();

                        })->save($signaturedestinationThumbPath . '/' . $signatureimageName);

                        if ($signaturewidth > 64) {
                            $signatureiconWidth = 64;
                        }
                        $signaturedestinationIconPath = public_path('users/signature/icon');
                        Image::make($signaturefilepath . '/' . $signatureimageName)->resize($signatureiconWidth, null, function ($constraint) use ($signatureimageName) {
                            $constraint->aspectRatio();

                        })->save($signaturedestinationIconPath . '/' . $signatureimageName);

                    }

                    $signaturephoto =$signatureimageName;
                }
                else
                {
                    $signaturephoto = '';
                }

                $userUpdate['id'] = $userId;
                $userUpdate['signature'] = $signaturephoto;
                $this->userModel->saveUser($userUpdate);

            }

            $getTechnician = DB::table('tbl_technician')->select('id')->where('user_id',$userId)->get()->first();



            $userDetail['id'] = $getTechnician->id;
            $userDetail['first_name'] = $reqInputs['first_name'];
            $userDetail['last_name'] = $reqInputs['last_name'];
            $userDetail['phone_number'] = $reqInputs['phone_number'];
            $userDetail['address'] = $reqInputs['address'];
            $userDetail['city'] = $reqInputs['city'];
            $userDetail['state'] = $reqInputs['state'];
            $userDetail['zip_code'] = $reqInputs['zip_code'];
            $this->userModel->saveTechnician($userDetail);


        }

        return Response::json([
            'status' => 1,
            'message'   => $msg,
//                'OTP'    => $user->OTP,
        ], 200);


    }

    protected function _file_mime_type($file)
    {
        // We'll need this to validate the MIME info string (e.g. text/plain; charset=us-ascii)
        $regexp = '/^([a-z\-]+\/[a-z0-9\-\.\+]+)(;\s.+)?$/';

        /* Fileinfo extension - most reliable method
         *
         * Unfortunately, prior to PHP 5.3 - it's only available as a PECL extension and the
         * more convenient FILEINFO_MIME_TYPE flag doesn't exist.
         */
        if (function_exists('finfo_buffer')) {

            $finfo = @finfo_open(FILEINFO_MIME);
            if (is_resource($finfo)) // It is possible that a FALSE value is returned, if there is no magic MIME database file found on the system
            {
                $mime = @finfo_buffer($finfo, $file);
                finfo_close($finfo);

                /* According to the comments section of the PHP manual page,
                 * it is possible that this function returns an empty string
                 * for some files (e.g. if they don't exist in the magic MIME database)
                 */
                if (is_string($mime) && preg_match($regexp, $mime, $matches)) {
                    $file_type = $matches[1];
                    return $file_type;
                }
            }
        }

    }

    public function userList(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $this->roleid = $customer->role_id;
        $reqInputs = $request->json()->all();
        $fParams = array();
        $fParams['limit'] = isset($reqInputs['limit']) ? $reqInputs['limit'] : '';
        $fParams['offset'] = isset($reqInputs['offset']) ? $reqInputs['offset'] : '';
        $fParams['keyword'] = isset($reqInputs['keyword']) ? $reqInputs['keyword'] : '';
       // $fParams['status'] = isset($reqInputs['status']) ? $reqInputs['status'] : '';
        $select = array('U.name', 'U.email', 'U.address1', 'U.address2', 'U.city', 'U.state', 'U.zipcode', 'U.telephone', 'U.photo', 'U.id','U.customer_id','R.name as role');
        $users = $this->userModel->allUsers($fParams['limit'], $fParams['offset'], array('select' => $select, 'search' => $fParams['keyword'], 'cus_id' => $this->cid,'role_id'=>$this->roleid,'user_id'=>$this->uid));
        $totalUsers = $this->userModel->userListCounts(array('cus_id'=>$this->cid,'role_id'=>$this->roleid,'user_id'=>$this->uid));
        $temp = array();
        if($users)
        {
            foreach ($users as $key=>$row)
            {
                $temp[$key] = (array)$row;
                $gallery = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/users/'.$row->photo;
               // print_r($gallery);die;
                if(file_exists($gallery) && $row->photo)
                {
                    $filePath = 'public/users';
                    $path = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/' . $filePath;

                    $temp[$key]['default'] = $path . '/' . $row->photo;
                    $temp[$key]['large'] = $path . '/large/' . $row->photo;
                    $temp[$key]['medium'] = $path . '/medium/' . $row->photo;
                    $temp[$key]['small'] = $path . '/small/' . $row->photo;
                    $temp[$key]['thumb'] = $path . '/thumbnails/' . $row->photo;
                }
                else
                {
                    $filePath = 'public/users/default';
                    $path = 'http://' . $_SERVER['SERVER_NAME'] . '/novamed/' . $filePath;
                    $temp[$key]['default'] = $path.'/Unknown.png';
                }

            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'counts' => $totalUsers

        ], 200);


    }

    function getuser(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $reqInputs = $request->json()->all();
        $input = [
            'id' => $reqInputs['id']
        ];
        $rules = array(

            'id' => 'required'
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
        $authuser = $this->userModel->getUserData($user['id'],1);
       // print_r($authuser);die;
        if($authuser)
        {
            if($authuser->id!=$user['id'])
            {
                if($authuser->role_id!=1)
                {
                    return Response::json([
                        'status' => 0,
                        'message' => 'You not having permission'
                    ], 550);
                }
            }

            $user = $this->userModel->getUserData($reqInputs['id']);
            $temp = array();
            $temp['id'] = $user->id;
            $temp['name'] = $user->name;
            $temp['email'] = $user->email;
            $temp['address1'] = $user->address1;
            $temp['address2'] = $user->address2;
            $temp['city'] = $user->city;
            $temp['state'] = $user->state;
            $temp['phone'] = $user->telephone;
            $temp['zip_code'] = $user->zipcode;
            $temp['role'] = $user->role_id;
            $temp['location'] = $user->location;
            $gallery = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/users/'.$user->photo;
            // print_r($gallery);die;
            if(file_exists($gallery) && $user->photo)
            {
                $filePath = 'novamed/public/users';
                $path = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $filePath;

                $temp['default'] = $path . '/' . $user->photo;
                $temp['large'] = $path . '/large/' . $user->photo;
                $temp['medium'] = $path . '/medium/' . $user->photo;
                $temp['small'] = $path . '/small/' . $user->photo;
                $temp['thumb'] = $path . '/thumbnails/' . $user->photo;
            }
            else
            {
                $filePath = 'novamed/public/users/default';
                $path = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $filePath;
                $temp['default'] = $path.'/unknown.png';
            }

            $signature = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/users/signature/default/'.$user->signature;
            if(file_exists($signature) && $user->signature)
            {
                $filePathSignature = 'novamed/public/users/signature';
                $pathsignature = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $filePathSignature;

                $temp['default_signature'] = $pathsignature . '/default' . $user->signature;
                $temp['large_signature'] = $pathsignature . '/large/' . $user->signature;
                $temp['medium_signature'] = $pathsignature . '/medium/' . $user->signature;
                $temp['small_signature'] = $pathsignature . '/small/' . $user->signature;
                $temp['thumb_signature'] = $pathsignature . '/thumbnails/' . $user->signature;
            }
            else
            {
                $filePath = 'novamed/public/users/default';
                $path = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $filePath;
                $temp['default_signature'] = $path.'/unknown.png';
            }
            return Response::json([
                'status' => 1,
                'data' => $temp,


            ], 200);

        }
        else
        {
            return Response::json([
                'status' => 0,
                'message' => 'Not found'
            ], 503);
        }

    }

    function getusertechnician(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if (count($user) < 0) {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $reqInputs = $request->json()->all();
        $input = [
            'id' => $reqInputs['id']
        ];
        $rules = array(

            'id' => 'required'
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
        $authuser = $this->userModel->getUserData($user['id']);
        if($authuser)
        {

            $user = $this->userModel->getUserDetailTechnician($user['id']);
            $temp = array();
            $temp['id'] = $user->id;
            $temp['first_name'] = $user->tfname;
            $temp['last_name'] = $user->lfname;
            $temp['phone_number'] = $user->phone_number;
            $temp['email'] = $user->temail;
            $temp['address'] = $user->taddress;
            $temp['city'] = $user->tcity;
            $temp['state'] = $user->tstate;
            $temp['zip_code'] = $user->tzip_code;
            $gallery = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/users/'.$user->photo;
            // print_r($gallery);die;
            if(file_exists($gallery) && $user->photo)
            {
                $filePath = 'novamed/public/users';
                $path = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $filePath;

                $temp['default'] = $path . '/' . $user->photo;
                $temp['large'] = $path . '/large/' . $user->photo;
                $temp['medium'] = $path . '/medium/' . $user->photo;
                $temp['small'] = $path . '/small/' . $user->photo;
                $temp['thumb'] = $path . '/thumbnails/' . $user->photo;
            }
            else
            {
                $filePath = 'novamed/public/users/default';
                $path = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $filePath;
                $temp['default'] = $path.'/unknown.png';
            }

            $signature = $_SERVER['DOCUMENT_ROOT'] . '/novamed/public/users/signature/default/'.$user->signature;
            if(file_exists($signature) && $user->signature)
            {
                $signaturefilePath = 'novamed/public/users/signature';
                $signaturepath = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $signaturefilePath;

                $temp['signaturedefault'] = $signaturepath . '/default' . $user->signature;
                $temp['signaturelarge'] = $signaturepath . '/large/' . $user->signature;
                $temp['signaturemedium'] = $signaturepath . '/medium/' . $user->signature;
                $temp['signaturesmall'] = $signaturepath . '/small/' . $user->signature;
                $temp['signaturethumb'] = $signaturepath . '/thumbnails/' . $user->signature;
            }
            else
            {
                $signaturefilePath = 'novamed/public/users/default';
                $signaturepath = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $signaturefilePath;
                $temp['signaturedefault'] = $signaturepath.'/signaturenoimg.png';
            }
            return Response::json([
                'status' => 1,
                'data' => $temp,

            ], 200);

        }
        else
        {
            return Response::json([
                'status' => 0,
                'message' => 'Not found'
            ], 503);
        }

    }

    public function shippingbillingmaster(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        $customer = $this->userModel->getUserCustomer($user['id']);
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }
        $reqInputs = $request->input();
        $key = (isset($reqInputs['key']) && $reqInputs['key'])?$reqInputs['key']:'';
        if(!$key)
        {
            return Response::json([
                'status' => 0,
                'message' => 'Key is required 1 for shipping and 2 for billing'
            ], 400);
        }
        $user = $this->userModel->getUser($user['id']);
        if($user)
        {
            $temp = array();
            if($key==1)
            {
                $data = $this->userModel->getShipping($this->cid);
                foreach ($data as $key=>$row)
                {
                    $temp[$key]['id'] =   $row->id;
                    $temp[$key]['name'] =   $row->customer_name;
                    $temp[$key]['address1'] =   $row->address1;
                    $temp[$key]['address2'] =   $row->address2;
                    $temp[$key]['city'] =   $row->city;
                    $temp[$key]['state'] =   $row->state;
                    $temp[$key]['zip_code'] =   $row->zip_code;
                    $temp[$key]['phone'] =   $row->phone_num;
                    $temp[$key]['fax'] =   $row->fax;
                    $temp[$key]['email'] =   $row->email;
                }
            }
            else
            {
                $data = $this->userModel->getBilling($this->cid);
                foreach ($data as $key=>$row)
                {
                    $temp[$key]['id'] =   $row->id;
                    $temp[$key]['name'] =   $row->billing_contact;
                    $temp[$key]['address1'] =   $row->address1;
                    $temp[$key]['address2'] =   $row->address2;
                    $temp[$key]['city'] =   $row->city;
                    $temp[$key]['state'] =   $row->state;
                    $temp[$key]['zip_code'] =   $row->zip_code;
                    $temp[$key]['phone'] =   $row->phone;
                    $temp[$key]['fax'] =   $row->fax;
                    $temp[$key]['email'] =   $row->email;
                }
            }




            return Response::json([
                'status' => 1,
                'data' => $temp
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

    public function proxylogin(Request $request)
    {

        header('Access-Control-Allow-Origin: *');
        $token = $request['proxy_token'];
        $proxy_credentials = array();
        if(!$token)
        {
            return Response::json([
                'status' => 0,
                'message' => 'Proxy token not getting'
            ], 422);
        }
        $decrypt_token = unserialize(base64_decode($token));
        if($decrypt_token)
        {
            $id = $decrypt_token[0];
            $email = $decrypt_token[1];
            $user = DB::table('tbl_users')->where('email',$email)->select('email','id')->first();
            //print_r($id);die;
            if(!$user)
            {
                return Response::json([
                    'status' => 0,
                    'message' => 'Not found'
                ], 404);
            }
            if (!$userToken=JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }

             $user_token = compact('userToken')['userToken'];
            $proxy[0] = $user->id;
            $proxy[1] = $user->email;
            $proxy_token = base64_encode(serialize($proxy));
            if(isset($user->email) && $user->email != '')
            {
                // $customer =  $this->userModel->getCustomer($user['customer_id']);
                $customer =  $this->userModel->getUserCustomermail($user->email);


            }
            else
            {
                $customer = array();
            }
            return Response::json([
                'status' => 1,
                'token' => $user_token,
                'user' => $customer,
                'proxy_url' =>  env('url_path').'/proxy/'.$proxy_token

            ], 200);

        }






    }
}