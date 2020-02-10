<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Users;
use App\Models\ImageResizer;
use App\Models\Customer;
use Carbon\Carbon;
use DB;
use Crypt;
use Input;
use Image;
use Response;
use Validator;
use App\Http\Requests;
use App\Models\Servicerequest;
use App\Models\Workorder;
use App\Models\Servicesummary;
use Illuminate\Support\Facades\Redirect;
use App\Models\Dashboard;

class RegisterController extends Controller
{

    public function __construct()
    {
        $this->servicerequest = new Servicerequest();
        $this->workorder = new Workorder();
        $this->servicesummary = new Servicesummary();
        $this->dashboard = new Dashboard();

    }

    public function index(Request $request)
    {

        return view('login.login');
    }

    public function dashboard(Request $request)
    {
        $title = 'Novamed-Dashboard';

        $select_service_request = array('tsr.*', 'tc.customer_name', DB::raw("(SELECT count(id) FROM tbl_service_request_item as ri where ri.service_request_id=tsr.id) as totalItems"));
        $serviceRequest = $this->servicerequest->AllServiceRequest(5, '', 'tsr.id', 'DESC', array('select' => $select_service_request));
        $serviceRequestCounts = $this->servicerequest->AllServiceRequest('', '', 'tsr.id', 'DESC', array('select' => $select_service_request));
        $selectWorkorder = array('W.id as workOrderId', 'W.work_order_number as workOrderNumber', 'W.as_found as workAsFound', 'W.as_calibrated as workAsCalibrated', 'S.service_plan_name as planName', 'S.as_found as planAsFound', 'S.as_calibrate as planAsCalibrate', 'SR.request_no as reqNumber',
            'SR.service_schedule_date as serviceSchedule', 'SR.customer_id as customerId', 'C.customer_name', 'W.maintanence_to as maintanenceTo', 'W.calibration_to as calibrationTo', 'W.status as workOrderStatus', 'W.workorder_date as workOrderDate', 'W.work_progress',
            DB::raw("(SELECT tech.first_name FROM tbl_technician as tech where tech.id=W.calibration_to) as technicianName"));
        $workOrder = $this->workorder->assignedworkorders('5', '', array('select' => $selectWorkorder), 'DESC', 'W.id');
        // $workOrderCounts = $this->workorder->assignedworkorders('', '', array('select' => $selectWorkorder), 'DESC', 'W.id');
        $selectServiceSummary = array('s.request_no', 'e.asset_no', 'e.serial_no', 'sp.service_plan_name', 'de.next_due_date', 't.first_name', 'sr.is_calibrated');
        $serviceSummary = $this->servicesummary->serviceSummary('5', '', array('select' => $selectServiceSummary), 'DESC', 'oi.id');
        $workOrderCounts = $this->dashboard->workorders(array('type' => 'all'));
        $completedworkOrderCounts = $this->dashboard->workorders(array('type' => 'complete'));
        $request_from_potal = $this->dashboard->portalRequests(array('type' => 'count'));
        $request_from_potal_list = $this->dashboard->portalRequests(array('type' => 'list'));
        $data['service_request'] = $serviceRequest;
        $data['service_request_count'] = count($serviceRequestCounts);
        $data['work_order'] = $workOrder;
        $data['work_order_count'] = count($workOrderCounts);
        $data['completed_work_order_count'] = count($completedworkOrderCounts);
        $data['service_summary'] = $serviceSummary;
        $data['request_from_potal_count'] = count($request_from_potal);
        $data['request_from_potal'] = $request_from_potal_list;
        // echo'<pre>';print_r($data);'</pre>';die;
        return view('dashboard.dashboard')->with('title', $title)->with('data', $data);
    }

    public function Login(Request $request)
    {
        try {
            $input = Input::all();

            $rules = [
                'email' => 'required',
                'password' => 'required',
            ];

            $validator = Validator::make($input, $rules);
//            print_r($validator);die;

            if ($validator->fails()) {

                return Redirect::back()
                    ->withInput()
                    ->withErrors($validator);
            }

            $loginuser = DB::table('tbl_users')->where('email', $input['email'])->first();
            if ($loginuser) {
                $activeuser = DB::table('tbl_users')->where('email', $input['email'])->where('is_active', '=', 1)->first();

                if ($activeuser) {
                    $userId = isset($loginuser->id) ? $loginuser->id : '';
                    $getuser = DB::table('tbl_group_user')->where('users_id', $userId)->first();
                    $groupId = isset($getuser->user_group_id) ? $getuser->user_group_id : '';

                    switch ($groupId) {
                        case '1':

                            $remember = (bool)Input::get('remember', false);
                            if (Sentinel::authenticate(Input::all(), $remember)) {
                                if($getuser->role_id){
                                    $getgrouppermission = DB::table('tbl_permission_group')->where('role_id', $getuser->role_id)->select('permission_id')->get()->toarray();
                                    $getgrouppermission = array_column($getgrouppermission, 'permission_id');
                                    $getpermission = DB::table('tbl_permissions')->where('name', 'dashboard')->first();

                                    if(in_array($getpermission->id,$getgrouppermission)){
                                       return redirect('admin/dashboard');
                                   }else{
                                       $getgrouppermission = DB::table('tbl_permission_group')->where('role_id', $getuser->role_id)->select('permission_id')->first();
                                       $getpermission = DB::table('tbl_permissions')->where('id', $getgrouppermission->permission_id)->select('name')->first();
                                       return redirect('admin/'.$getpermission->name);
                                   }
                                }

                            } else {
                                $errors = 'Invalid login or password.';
                            }

                    }
                } else {
                    $errors = 'Account is Blocked !';
                }
            } else {
                $errors = 'Invalid login or password.';
            }


        } catch (NotActivatedException $e) {
            $errors = 'Account is not activated!';

//            return Redirect::to('admin/category')->with('user', $e->getUser());
        } catch (ThrottlingException $e) {
            $delay = $e->getDelay();

            $errors = "Your account is blocked for {$delay} second(s).";
        }


        return Redirect::back()
            ->withInput(Input::all())
            ->withErrors($errors);
    }

    public function editProfile(Request $request, $id = false)
    {

        $this->customer = new Customer();
        $input = Input::all();
        $title = 'Novamed-Profile Edit';

        $loginuserId = Sentinel::getUser()->id;


        $datas = [
            'id' => $id,
//            'name' => isset($input['name']) ? $input['name'] : '',
            'email' => isset($input['email']) ? $input['email'] : '',
            'first_name' => isset($input['first_name']) ? $input['first_name'] : '',
            'last_name' => isset($input['last_name']) ? $input['last_name'] : '',
            'address1' => isset($input['address1']) ? $input['address1'] : '',
            'address2' => isset($input['address2']) ? $input['address2'] : '',
            'city' => isset($input['city']) ? $input['city'] : '',
            'state' => isset($input['state']) ? $input['state'] : '',
            'zipcode' => isset($input['zipcode']) ? $input['zipcode'] : '',
            'telephone' => isset($input['telephone']) ? $input['telephone'] : '',
            'photo' => isset($input['photo']) ? $input['photo'] : '',
            'signature' => isset($input['signature']) ? $input['signature'] : '',
        ];


        if ($id) {
            $getUser = DB::table('tbl_users')->where('id', $loginuserId)->first();
            if (!$getUser) {
                return redirect()->back();
            } else {
                $data['id'] = $getUser->id;
                $data['email'] = $getUser->email;
                $data['name'] = $getUser->name;
                $data['first_name'] = $getUser->first_name;
                $data['last_name'] = $getUser->last_name;
                $data['address1'] = $getUser->address1;
                $data['address2'] = $getUser->address2;
                $data['city'] = $getUser->city;
                $data['state'] = $getUser->state;
                $data['zipcode'] = $getUser->zipcode;
                $data['telephone'] = $getUser->telephone;
                $data['photo'] = $getUser->photo;
                $data['signature'] = $getUser->signature;
            }
        }
        $rules = [
            'email' => 'required|unique:tbl_users,email,'
                . $data['id'] . ',id',
            'first_name' => 'required',
            'address1' => 'required',
            'telephone' => 'required|unique:tbl_users,telephone,'
                . $data['id'] . ',id'
        ];
        $error = array();

        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($datas, $rules);
            if ($checkValid->fails()) {
                $checkStatus = true;
                $error = $checkValid->errors()->all();
            }

        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
        }
        if ($checkStatus) {
            return view('users.profile')->with('input', $data)->with('title', 'Profile edit')->with('errors', $error);;
        } else {
            $data = Input::all();

//            echo '<pre>';print_r($data);die;

            if ($manualFile = $request->file('signature')) {

                $signatureimagesize = getimagesize($data['signature']);

                $signaturemainPath = public_path() . '/users/signature/default';
                $signaturelocation = $signaturemainPath;
                $signaturetrimmedLocation = str_replace('\\', '/', $signaturelocation);


                $signaturefilepath = $signaturetrimmedLocation;

                $image = $request->file('signature')->getClientOriginalExtension();
                $signatureimageName = 'usersignature' . '-' . uniqid() . '.' . $image;
                $imagePath = $request->file('signature')->move(base_path() . '/public/users/signature/default', $signatureimageName);
                $img = Image::make($imagePath->getRealPath());


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
                $canvas = Image::canvas($signaturewidth, $signatureheight, '000000');
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


            } else {
                $signatureimageName = $getUser->signature;
            }


            if ($profilePhoto = $request->file('profilephoto')) {

                $profileimagesize = getimagesize($data['profilephoto']);

                $profilemainPath = public_path() . '/users/default';
                $profilelocation = $profilemainPath;
                $profiletrimmedLocation = str_replace('\\', '/', $profilelocation);


                $profilefilepath = $profiletrimmedLocation;

                $image = $request->file('profilephoto')->getClientOriginalExtension();
                $profileimageName = 'users' . '-' . uniqid() . '.' . $image;
                $imagePath = $request->file('profilephoto')->move(base_path() . '/public/users/default', $profileimageName);
                $img = Image::make($imagePath->getRealPath());


                $profilewidth = $profileimagesize[0];
                $profileheight = $profileimagesize[1];

                $profilelargeWidth = $profilewidth;
                $profilemediumWidth = $profilewidth;
                $profilesmallWidth = $profilewidth;
                $profileextralargeWidth = $profilewidth;
                $profileiconWidth = $profilewidth;
                $profilethumbnailWidth = $profilewidth;
                $profiledestinationLargePath = public_path('users/large');
                $profilewidth = 760;
                $profileheight = 480;
                $canvas = Image::canvas($profilewidth, $profileheight, '000000');
                $image = Image::make($profilefilepath . '/' . $profileimageName)->resize($profilewidth, $profileheight,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    });
                $canvas->insert($image, 'center');
                // pass the full path. Canvas overwrites initial image with a logo
                $canvas->save($profiledestinationLargePath . '/' . $profileimageName);

                if ($profilewidth > 375) {
                    $profilemediumWidth = 425;

                }
                $profiledestinationMediumPath = public_path('users/medium');
                Image::make($profilefilepath . '/' . $profileimageName)->resize($profilemediumWidth, null, function ($constraint) use ($profileimageName) {
                    $constraint->aspectRatio();

                })->save($profiledestinationMediumPath . '/' . $profileimageName);

                if ($profilewidth > 320) {

                    $profilesmallWidth = 320;
                }
                $profiledestinationSmallPath = public_path('users/small');
                Image::make($profilefilepath . '/' . $profileimageName)->resize($profilesmallWidth, null, function ($constraint) use ($profileimageName) {
                    $constraint->aspectRatio();

                })->save($profiledestinationSmallPath . '/' . $profileimageName);


                if ($profilewidth > 200) {

                    $profilethumbnailWidth = 200;
                }
                $profiledestinationThumbPath = public_path('users/thumb');
                Image::make($profilefilepath . '/' . $profileimageName)->resize($profilethumbnailWidth, null, function ($constraint) use ($profileimageName) {
                    $constraint->aspectRatio();

                })->save($profiledestinationThumbPath . '/' . $profileimageName);

                if ($profilewidth > 64) {
                    $profileiconWidth = 64;
                }
                $profiledestinationIconPath = public_path('users/icon');
                Image::make($profilefilepath . '/' . $profileimageName)->resize($profileiconWidth, null, function ($constraint) use ($profileimageName) {
                    $constraint->aspectRatio();

                })->save($profiledestinationIconPath . '/' . $profileimageName);


            } else {
                $profileimageName = $getUser->photo;
            }


            $save = array();

            $save['id'] = $id;
            $save['first_name'] = $data['first_name'];
            $save['last_name'] = $data['last_name'];
            $save['email'] = $data['email'];
            $save['telephone'] = $data['telephone'];
            $save['address1'] = $data['address1'];
            $save['address2'] = $data['address2'];
            $save['city'] = $data['city'];
            $save['state'] = $data['state'];
            $save['zipcode'] = $data['zipcode'];
            $save['signature'] = $signatureimageName;
            $save['photo'] = $profileimageName;
            $this->customer->saveUser($save);

            if ($id) {
                return redirect()->back()->with('message', 'Updated Successfully');

            } else {
                return redirect()->back()->with('message', 'Added Successfully');

            }
        }
    }

    //Admin change password

    public function changePassword(Request $request)
    {

        $title = "Change Password";


        if ($request->isMethod('post')) {

            $hasher = Sentinel::getHasher();

            $oldPassword = Input::get('currentPassword');
            $password = Input::get('newPassword');
            $passwordConf = Input::get('confirmNewPassword');

            $user = Sentinel::getUser();

            if (!$hasher->check($oldPassword, $user->password) || $password != $passwordConf) {

                $error = [
                    'Your current password is wrong'];
                return redirect()->back()->with('errors', $error);

            }
            if($hasher->check($oldPassword,$user->password)==$hasher->check($password,$user->password))
            {
                $error = [
                    'This new password is using currently. Please try different password'];
                return redirect()->back()->with('errors', $error);
            }

            $user = Sentinel::update($user, array('password' => $password));
            $message = 'Your Password update Successfully';


  return redirect()->back()->with('message', $message);
        } else {
            return view('login.changepassword')->with('title', $title);

        }

    }

    public function logout()
    {

        Sentinel::logout();

        return Redirect::to('admin/login');
    }

}
