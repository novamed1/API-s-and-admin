<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Manufacturer;
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

class ManufacturerController extends Controller
{

    public function __construct()
    {
        $this->manufacturer = new Manufacturer();
    }

    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Manufacturer';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tm.*');
            $data = $this->manufacturer->AllManufacturer('', '', 'tm.manufacturer_id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tm.manufacturer_name', 'tm.manufacturer_phone', 'tm.manufacturer_email', 'tm.manufacturer_address'));
        } else {
            $select = array('tm.*');
            $data = $this->manufacturer->AllManufacturer('', '', 'tm.manufacturer_id', 'DESC', array('select' => $select));
        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.manufacturer.manufacturerlist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;

        $search['serial_no'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['manufacturer_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['city'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['state'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['manufacturer_phone'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';
        $search['manufacturer_email'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';


//        echo '<pre>';print_r($param);die;

        $select = array('tm.*');
        $data = $this->manufacturer->AllManufacturerGrid($param['limit'], $param['offset'], 'tm.manufacturer_id', 'DESC', array('select' => $select, 'search' => $search), false, array('tm.manufacturer_name', 'tm.manufacturer_phone', 'tm.manufacturer_email', 'tm.manufacturer_address'));
        $count = $this->manufacturer->AllManufacturerGrid('', '', 'tm.manufacturer_id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tm.manufacturer_name', 'tm.manufacturer_phone', 'tm.manufacturer_email', 'tm.manufacturer_address'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->serial_no;
                $values[$key]['1'] = $row->manufacturer_name;
                $values[$key]['2'] = $row->city;
                $values[$key]['3'] = $row->state;
                $values[$key]['4'] = $row->manufacturer_phone;
                $values[$key]['5'] = $row->manufacturer_email;
                $values[$key]['6'] = "<a href=" . url("admin/editmanufacturer/" . $row->manufacturer_id) . "><i class='fa fa-pencil'></a>";
                $values[$key]['7'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletemanufacturer/' . $row->manufacturer_id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();

        $title = 'Novamed-Manufacturer Creaation';
        $data = [
            'manufacturer_id' => $id,
            'name' => isset($input['name']) ? $input['name'] : '',
            'serialNo' => isset($input['serialNo']) ? $input['serialNo'] : '',
            'phoneNo' => isset($input['phoneNo']) ? $input['phoneNo'] : '',
            'email' => isset($input['email']) ? $input['email'] : '',
            'address' => isset($input['address']) ? $input['address'] : '',
            'fax' => isset($input['fax']) ? $input['fax'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '1',
            'state' => isset($input['state']) ? $input['state'] : '',
            'address2' => isset($input['address2']) ? $input['address2'] : '',
            'zipcode' => isset($input['zipcode']) ? $input['zipcode'] : '',
            'city' => isset($input['city']) ? $input['city'] : '',
            'website' => isset($input['website']) ? $input['website'] : '',
            'image' => isset($input['image']) ? $input['image'] : '',

        ];

        $states = DB::table('tbl_state')
        ->orderBy('state_name','ASC')
        ->pluck('state_name','state_name');
        $states->prepend('-Select State','');

        $city = DB::table('tbl_city')
        ->orderBy('city_name','ASC')
        ->pluck('city_name','city_name');

        $city->prepend('-Select City','');


        if ($id) {
            $getvalue = $this->manufacturer->getmanufacturer($data['manufacturer_id']);
            if (!$getvalue) {
                return redirect('admin/manufacturerlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['manufacturer_id'] = $getvalue->manufacturer_id;
                $data['name'] = $getvalue->manufacturer_name;
                $data['phoneNo'] = $getvalue->manufacturer_phone;
                $data['address2'] = $getvalue->address2;
                $data['zipcode'] = $getvalue->zipcode;
                $data['city'] = $getvalue->city;
                $data['state'] = $getvalue->state;
                $data['image'] = $getvalue->manufacturer_logo;
                $data['serialNo'] = $getvalue->serial_no;
                $data['email'] = $getvalue->manufacturer_email;
                $data['fax'] = $getvalue->manufacturer_fax;
                $data['address'] = $getvalue->manufacturer_address;
                $data['website'] = $getvalue->website;
                $data['is_active'] = $getvalue->is_active;
            }
        }
        $rules = [
            'name' => 'required',
            'email' => 'required|email',


        ];
        if (!$id) {
            $rules = [
                'image' => 'image|max:2048'
            ];
        }

        $messgaes = [
            'name.required' => 'Please give manufacturer name.',
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
//        echo "<pre>";print_r($data);die;
        if ($checkStatus) {
            return view('master.manufacturer.addForm')->with('input', $data)->with('title', $title)->with('errors', $error)->with('states',$states)->with('city',$city);
        } else {
            $data = Input::all();
            // echo '<pre>';print_r($data);die;

            if ($request->hasFile('image')) {
                if (isset($data['image']) ? $data['image'] : '') {

                    $imagesize = getimagesize($data['image']);

                    $width = $imagesize[0];
                    $height = $imagesize[1];
                    $image = $request->file('image')->getClientOriginalExtension();
                    $imageName = 'manufacturer' . '-' . uniqid() . '.' . $image;
                    $imagePath = $request->file('image')->move(base_path() . '/public/images/manufacturer/original', $imageName);
                    $img = Image::make($imagePath->getRealPath());
                    $largeWidth = $width;
                    $mediumWidth = $width;
                    $smallWidth = $width;
                    $extralargeWidth = $width;
                    $iconWidth = $width;
                    $thumbnailWidth = $width;
                    if ($width > 425) {
                        $largeWidth = 425;
                    }
                    Image::make($imagePath)->resize($largeWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save(base_path() . '/public/images/manufacturer/large/' . $imageName);

                    if ($width > 375) {
                        $mediumWidth = 425;

                    }
                    Image::make($imagePath)->resize($mediumWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save(base_path() . '/public/images/manufacturer/medium/' . $imageName);
                    if ($width > 320) {

                        $smallWidth = 320;
                    }
                    Image::make($imagePath)->resize($smallWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save(base_path() . '/public/images/manufacturer/small/' . $imageName);
                    if ($width > 200) {

                        $thumbnailWidth = 200;
                    }
                    Image::make($imagePath)->resize($thumbnailWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save(base_path() . '/public/images/manufacturer/thumbnail/' . $imageName);
                    if ($width > 768) {

                        $extralargeWidth = 768;
                    }
                    Image::make($imagePath)->resize($extralargeWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save(base_path() . '/public/images/manufacturer/extraLarge/' . $imageName);
                    if ($width > 64) {
                        $iconWidth = 64;
                    }

                    Image::make($imagePath)->resize($iconWidth, null, function ($constraint) use ($imageName) {
                        $constraint->aspectRatio();

                    })->save(base_path() . '/public/images/manufacturer/icon/' . $imageName);

                }
            } else {
                if (!$id) {
                    $imageName = '';
                } else {
                    $imageName = $data['imagehidden'];
                }

            }


            $save = array();

            $save['manufacturer_id'] = $id;
            $save['manufacturer_name'] = $data['name'];
            $save['manufacturer_phone'] = $data['phoneNo'];
            $save['address2'] = $data['address2'];
            $save['zipcode'] = $data['zipcode'];
            $save['city'] = $data['city'];
            $save['state'] = $data['state'];
            $save['website'] = $data['website'];

            $save['manufacturer_email'] = $data['email'];
            $save['manufacturer_fax'] = $data['fax'];
            $save['manufacturer_logo'] = $imageName;
            $save['manufacturer_address'] = $data['address'];
            //echo '<pre>';print_r($save);die;


            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
            $Saveresult = $this->manufacturer->saveManufacturer($save);
            $last_manu = DB::table('tbl_manufacturer')->where('manufacturer_id', '=', $Saveresult)->select('manufacturer_id')->get()->first();
            $last_manu_id = (isset($last_manu->manufacturer_id) && $last_manu->manufacturer_id) ? $last_manu->manufacturer_id : '';

            $serialNo = 'NOVMAN' . str_pad($last_manu_id, 3, '0', STR_PAD_LEFT);
            if ($Saveresult) {
                $savemanu['manufacturer_id'] = $Saveresult;
                $savemanu['serial_no'] = $serialNo;
                $this->manufacturer->saveManufacturer($savemanu);
            }

            if ($id) {
                return redirect('admin/manufacturerlist')->with('message', 'Updated Successfully');
            } else {
                return redirect('admin/manufacturerlist')->with('message', 'Added Successfully');
            }

        }
    }

    public function addmanu(Request $request, $id = false)
    {
        $input = Input::all();


        $data = [
            'name' => isset($input['manufacturer']) ? $input['manufacturer'] : '',
            'serialNo' => isset($input['serialnumber']) ? $input['serialnumber'] : '',
        ];

        $save = array();

        $save['manufacturer_id'] = false;
        $save['manufacturer_name'] = $data['name'];
        $save['serial_no'] = $data['serialNo'];
        $save['is_active'] = 1;

        $Saveresult = $this->manufacturer->saveManufacturer($save);


        $getManufacturers = DB::table('tbl_manufacturer as tm')->get();


        $element = '<option value="">Select Channel Points</option>';
        foreach ($getManufacturers as $val) {
            $element .= '<option value="' . $val->serial_no . '">' . $val->manufacturer_name . '</option>';
        }

        $data = $element;


        die(json_encode(array('result' => true, 'getManufacturer' => $data)));

//        die(json_encode(array('result' => true, 'message' => 'Manufacturer added successfully')));

    }


    public function updatephoto(Request $request)
    {

        $input = Input::all();
        if(!$input){
            die(json_encode(array('result' => false, 'message' => 'Values are not get properly')));

        }

        $save['manufacturer_logo'] = '';
        $save['manufacturer_id'] = $input['Id'];
        $Saveresult = $this->manufacturer->saveManufacturer($save);
        die(json_encode(array('result' => true, 'message' => 'Properly saved')));


    }


    public
    function delete($id)
    {

        $getmanufacturer = $this->manufacturer->getmanufacturer($id);

        if ($getmanufacturer) {

            $getModel = DB::table('tbl_equipment_model')->where('manufacturer_ids', '=', $id)->select('*')->first();
            if ($getModel) {
                $message = Session::flash('message', "You can't able to delete this manufatcurer");
                return redirect('admin/manufacturerlist')->with(['data', $message], ['message', $message]);
            }

            $message = Session::flash('message', 'Deleted Successfully');
            $member = $this->manufacturer->deletemanufacturer($id);

            return redirect('admin/manufacturerlist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully');
            return redirect('admin/manufacturerlist')->with('data', $error);
        }
    }

  
  public function getCity(Request $request)
  {
   
   $input = Input::all();
   if(isset($input['state']))
   {

    $stateId = DB::table('tbl_state')->where('state_name',$input['state'])->select('id')->first();
    
    $getcity = DB::table('tbl_city')->where('state_id',$stateId->id)->select('*')->get();

    $element = '<option value="">Select City</option>';
        foreach ($getcity as $val) {
            $element .= '<option value="' . $val->city_name . '">' . $val->city_name . '</option>';
        }

        $data = $element;

        die(json_encode(array('result' => true, 'city' => $data)));

   }
   else
   {
       die(json_encode(array('result' => false)));
   }


  }




}

