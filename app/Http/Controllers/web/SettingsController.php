<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Users;
use App\Models\Settings;
use Illuminate\Http\Request;
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

class SettingsController extends Controller
{
    protected $mail;

    public function __construct()
    {
        $this->users = new Users();
        $this->customer = new Customer();
        $this->mail = new PHPMailer();
        $this->settings = new Settings();

    }

    public function sitesettings(Request $request)
    {

        $title = 'Novamed - Site Settings';
        $contents = DB::table('tbl_site_content')->select('*')->first();
        $data['as_found'] = (isset($contents->as_found)&&$contents->as_found)?$contents->as_found:'';
        $data['as_calibrated'] = (isset($contents->as_calibrated)&&$contents->as_calibrated)?$contents->as_calibrated:'';
        $data['test_points'] = (isset($contents->test_points)&&$contents->test_points)?$contents->test_points:'';
        $data['readings'] = (isset($contents->readings)&&$contents->readings)?$contents->readings:'';
        $data['certificate'] = (isset($contents->certificate)&&$contents->certificate)?$contents->certificate:'';

        $data['cleaning'] = (isset($contents->cleaning)&&$contents->cleaning)?$contents->cleaning:'';
        $data['calibrated_test_point'] = (isset($contents->calibrated_test_point)&&$contents->calibrated_test_point)?$contents->calibrated_test_point:'';
        $data['calibrated_readings'] = (isset($contents->calibrated_readings)&&$contents->calibrated_readings)?$contents->calibrated_readings:'';
        $data['due_date'] = (isset($contents->due_date)&&$contents->due_date)?$contents->due_date:'';
        $data['warranty'] = (isset($contents->warranty)&&$contents->warranty)?$contents->warranty:'';
        $data['repairs'] = (isset($contents->repairs)&&$contents->repairs)?$contents->repairs:'';
        $data['parts'] = (isset($contents->parts)&&$contents->parts)?$contents->parts:'';
        return view('setting.sitesetting')->with('title', $title)
            ->with('input',$data);
    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
       // echo'<pre>';print_r($input);'</pre>';die;
        $title = 'Novamed-Service Site settings';
        $data = [
            'id' => $id,
            'as_found' => isset($input['as_found']) ? $input['as_found'] : '',
            'test_points' => isset($input['test_points']) ? $input['test_points'] : '',
            'readings' => isset($input['readings']) ? $input['readings'] : '',
            'cleaning' => isset($input['readings']) ? $input['cleaning'] : '',
            'as_calibrated' => isset($input['as_calibrated']) ? $input['as_calibrated'] : '',
            'calibrated_test_point' => isset($input['calibrated_test_point']) ? $input['calibrated_test_point'] : '',
            'calibrated_readings' => isset($input['calibrated_readings']) ? $input['calibrated_readings'] : '',
            'due_date' => isset($input['due_date']) ? $input['due_date'] : '',
            'certificate' => isset($input['certificate']) ? $input['certificate'] : '',
            'warranty' => isset($input['warranty']) ? $input['warranty'] : '',
            'repairs' => isset($input['repairs']) ? $input['repairs'] : '',
            'parts' => isset($input['parts']) ? $input['parts'] : '',

        ];

        $rules = [
            'as_found' => 'required'
        ];
        $error = array();

        $checkStatus = false;
        if ($request->isMethod('post')) {
            $checkValid = Validator::make($data, $rules);
            if ($checkValid->fails()) {
                $checkStatus = true;
                $error = $checkValid->errors()->all();
            }
        } elseif ($request->isMethod('get')) {
            $checkStatus = true;
        }
        if ($checkStatus) {
            return view('setting.sitesetting')->with('title', $title)->with('input', $data)->with('errors', $error);
        } else {
            $data = Input::all();
            $save = array();

            $save['id'] = 1;
            $save['as_found'] = $data['as_found'];
            $save['as_calibrated'] = $data['as_calibrated'];
            $save['test_points'] = $data['test_points'];
            $save['readings'] = $data['readings'];
            $save['certificate'] = $data['certificate'];

            $save['cleaning'] = $data['cleaning'];
            $save['calibrated_test_point'] = $data['calibrated_test_point'];
            $save['calibrated_readings'] = $data['calibrated_readings'];
            $save['due_date'] = $data['due_date'];
            $save['warranty'] = $data['warranty'];
            $save['repairs'] = $data['repairs'];
            $save['parts'] = $data['parts'];


            $Saveresult = $this->settings->saveSiteSettings($save);

                return redirect('admin/dashboard')->with('message', 'Added Successfully!');

        }
    }


}

