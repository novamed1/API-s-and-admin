<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerSpecification;
use App\Models\Isospecification;
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

class CustomerSpecificationController extends Controller
{
    public function __construct()
    {
        $this->customerspecifiction = new CustomerSpecification();
        $this->iso = new Isospecification();
        $this->customer = new Customer();

    }

    /**
     * Index view of Customer Specification
     *
     */
    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Customer';
        //echo'<pre>';print_r($input);die;
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $postvalue = isset($input['postvalue']) ? $input['postvalue'] : '';
        $posttestplanid = isset($input['postTestPlanId']) ? $input['postTestPlanId'] : '';
        $data['search']['keyword'] = $keyword;

        if ($keyword) {
            $select = array('c.id as customerId', 'c.customer_name', 't.name', 'c.customer_type_id', 'c.state', 'c.city', 'c.customer_email');
            $data = $this->customerspecifiction->AllCustomerSpecification('', '', 'c.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('c.customer_name', 'c.state', 'c.city', 'c.customer_email'));

        } else {
            $select = array('c.id as customerId', 'c.customer_name', 't.name', 'c.customer_type_id', 'c.state', 'c.city', 'c.customer_email');
            $data = $this->customerspecifiction->AllCustomerSpecification('', '', 'c.id', 'DESC', array('select' => $select), false);

        }

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
//
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );

        $userDetail->setPath($request->url());

        return view('customerspecifications.customerspecificationlist')->with('title', $title)->with('data', $paginatedItems)->with('postvalue', $postvalue)->with('posttestplanid', $posttestplanid);


    }

    /**
     * Display All Datas to index page of customer specification
     */
    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['customer_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['unique_id'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['name'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['customer_email'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';
        $search['customer_telephone'] = isset($input['sSearch_4']) ? $input['sSearch_4'] : '';
        $search['state'] = isset($input['sSearch_5']) ? $input['sSearch_5'] : '';
        $search['city'] = isset($input['sSearch_6']) ? $input['sSearch_6'] : '';

        $select = array('c.id as customerId', 'c.unique_id', 'c.customer_name', 't.name', 'c.customer_type_id', 'c.state', 'c.city', 'c.customer_email', 'c.customer_telephone', 'c.address1', 'c.address2', 'c.user_id');
        $data = $this->customerspecifiction->AllCustomerSpecificationGrid($param['limit'], $param['offset'], 'c.id', 'DESC', array('select' => $select, 'search' => $search),
            false);

        $count = $this->customerspecifiction->AllCustomerSpecificationGrid($param['limit'], $param['offset'], 'c.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true),
            true);
        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->customer_name;
                $values[$key]['1'] = $row->unique_id;
                $values[$key]['2'] = $row->name;
                $values[$key]['3'] = $row->customer_email;
                $values[$key]['4'] = $row->customer_telephone;
                $values[$key]['5'] = $row->state;
                $values[$key]['6'] = $row->city;
                $values[$key]['7'] = "<a href=" . url('admin/editcustomerspecification/' . $row->customerId) . "><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    /**
     * Display the selected customer details.
     */

    public function form(Request $request, $id = false)
    {

        $input = Input::all();

        $loginuserId = Sentinel::getUser()->id;

        $title = 'Customer Specification creation';

        $channels = DB::table('tbl_channels')->pluck('channel_name', 'id');
        $channels->prepend('Select Channel Type', '');

//        select the operation

        $operationSelect = DB::table('tbl_operations')->pluck('operation_name', 'id');
        $operationSelect->prepend('Select Operation', '');


//        select the volume
        $volumeSelect = DB::table('tbl_volume')->pluck('volume_name', 'id');
        $volumeSelect->prepend('Select Volume Type', '');

//        select channel numbers

        $channelNumberSelect = DB::table('tbl_channel_numbers')->pluck('channel_number', 'id');
        $channelNumberSelect->prepend('Select Channel Numbers', '');

//        select the

        $units = DB::table('tbl_units')->pluck('unit', 'id');
        $units->prepend('Select Unit', '');
        $testpoints = DB::table('tbl_test_points')->select('name', 'id')->get();


        $data = [
            'id' => $id,
            'tolerances' => array()
        ];

        if ($id) {
            $getvalue = $this->customerspecifiction->getcustomer($data['id']);
//            echo '<pre>';print_r($getvalue);exit;
            if ($getvalue) {
                $getcalspecificationvalue = $this->customerspecifiction->getcalspecification($data['id']);
//                echo '<pre>';print_r($getcalspecificationvalue);exit;
                foreach ($getcalspecificationvalue as $row) {
                    $gettolerancevalue = $this->iso->isotolerances($row->id);
                }
            }

            if (!$getvalue) {
                return redirect('admin/customerspecificationlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['customer_name'] = $getvalue->customer_name;
                $data['customer_type'] = $getvalue->name;
                $data['customer_email'] = $getvalue->customer_email;
                $data['customer_telephone'] = $getvalue->customer_telephone;
                $data['city'] = $getvalue->city;
                $data['state'] = $getvalue->state;
                $data['calspecification'] = isset($getcalspecificationvalue) ? $getcalspecificationvalue : '';
                $data['tolerances'] = isset($gettolerancevalue) ? $gettolerancevalue : '';
                //echo'<pre>';print_r($data['tolerances']);'</pre>';die;
            }
        }

        return view('customerspecifications.customerspecificationform')->with('input', $data)->with('channels', $channels)
            ->with('operations', $operationSelect)
            ->with('volumes', $volumeSelect)
            ->with('units', $units)
            ->with('title', $title)
            ->with('channelNumberSelect', $channelNumberSelect)
            ->with('testpoints', $testpoints);

    }

    /**
     * Display all customer specification of selected customer.
     */

    function callistData(Request $request)
    {
        $input = Input::all();
        $customer_id = $input['id'];
        $data = array();
        $param = array();
        $param['limit'] = isset($input['length']) ? $input['length'] : '';
        $param['offset'] = isset($input['start']) ? $input['start'] : ''; //echo'<pre>';print_r($input);'</pre>';die;
        $select = array('v.volume_name', 'o.operation_name', 'c.channel_name', 'u.unit', 'is.volume_value', 'is.id');;
        $data = $this->customerspecifiction->getcalspecification($customer_id, $param['limit'], $param['offset'], 'is.id', 'DESC', array('select' => $select),
            false);

        $count = $this->customerspecifiction->getcalspecification($customer_id, $param['limit'], $param['offset'], 'is.id', 'DESC', array('select' => $select, 'count' => true),
            true);
        if ($data) {
            $values = array();
            $i = 0;

            foreach ($data as $key => $row) {
//                echo '<pre>';print_r($row);die;
                $values[$key]['0'] = '<span class="lead_numbers" data-id=' . $row->id . '>
                                                   <a href="javascript:void(0)"
                                                      id="customertolerances"
                                                      rel=' . $row->id . '
                                                      data-toggle="collapse"
                                                      data-target="#isotolerances' . $row->id . '"
                                                      data-id=' . $row->id . '
                                                      data-parent="#tagcollapse"
                                                      class="accordion-toggle hov"
                                                   ><i
                                                               class="fa fa-plus-circle ordericon collapseico"
                                                               data-widget-collapsed="true" data-attr=' . $row->id . '></i> <span
                                                               class="menu-item-parent"></span></a>
                                                      </span>';

                $values[$key]['1'] = $row->channel_name;
                $values[$key]['2'] = $row->operation_name;
                $values[$key]['3'] = $row->volume_name;
                $values[$key]['4'] = $row->volume_value;
                $values[$key]['5'] = "<a href='javascript:void(0);' data-id= '$row->id' data-customerid='$customer_id' class='editCalSpecificationdetails btn btn-primary '><i class='fa fa-pencil'></a>";
                $i++;

            }

        }
        echo json_encode(array('iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }
    /**
     * Create/Update customer specification of selected customer.
     */
    public function createCustomerSpecification(Request $request)
    {
        $input = Input::all();
        $loginuserId = Sentinel::getUser()->id;
        $save = array();

        $save['id'] = isset($input['id']) ? $input['id'] : '';
        $save['channel_id'] = $input['channel_id'];
        $save['customer_id'] = $input['CustomerId'];
        $save['channel_number'] = $input['channel_number'];
        $save['operation_id'] = $input['operation_id'];
        $save['volume_id'] = $input['volume_id'];
        if ($input['volume_id'] == 1) {
            $volume_value = $input['volume_from'] . '-' . $input['volume_to'];
        } else {
            $volume_value = $input['volume_from'];
        }
        $save['volume_value'] = $volume_value;
        $save['unit'] = $input['unit'];
        $Saveresult = $this->iso->savespec($save, $loginuserId);
        if ($Saveresult) {
            $query = DB::table('tbl_iso_limit_tolerance');
            $query->where('specification_id', $Saveresult);
            $query->delete();
            if (isset($input['toleranceArray']) && $input['toleranceArray']) {
                foreach ($input['toleranceArray'] as $row) {
                    $savetol['id'] = '';
                    $savetol['specification_id'] = $Saveresult;
                    $savetol['target_value'] = $row['target_value'];
                    $savetol['description'] = $row['description'];
                    $savetol['accuracy'] = $row['accuracy'];
                    $savetol['precision'] = $row['precision'];
                    $savetol['accuracy_ul'] = $row['accuracyul'];
                    $savetol['precesion_ul'] = $row['precisionul'];
                    $this->iso->savetolerance($savetol);
                }
            }
        }
        if ($input['id']) {
            return redirect('admin/editcustomerspecification/' . $input['CustomerId'])->with('message', 'Updated Successfully');
        } else {
            return redirect('admin/editcustomerspecification/' . $input['CustomerId'])->with('message', 'Added Successfully');

        }

    }

    /**
     * Check created/updated customer specification of selected customer are already exits r not .
     */

    function checkcustomertolerancecombination()
    {
        $post = Input::all();
        if ($post) {
            $checkcustomercombination = $this->customerspecifiction->checkcustomercombination($post);
            if (count($checkcustomercombination)) {
                die(json_encode(array('result' => true, 'message' => 'These combinations Channel,Operation and Volume are already exist')));
            } else {
                die(json_encode(array('result' => false)));
            }
        }

    }
    /**
     * Inline edit of customer specification.
     */
    function customersublists(Request $request)
    {
        $input = Input::all();
        $isoId = $input['id'];
        $tolerances = $this->iso->isotolerances($isoId);
        $view = view('customerspecifications.calSpeSublistAjax', ['data' => $tolerances])->render();

        echo json_encode(array('result' => true, 'data' => $view));
    }
    /**
     * display customer specification form for both Create/Edit.
     */
    public function getCalSpecInfo(Request $request, $id = false)
    {
        $input = Input::all();
        $id = isset($input['id']) ? $input['id'] : '';
        $customer_id = $input['customerId'];
        $loginuserId = Sentinel::getUser()->id;

        $title = 'ISO Specification creation';

        $channels = DB::table('tbl_channels')->pluck('channel_name', 'id');
        $channels->prepend('Select Channel Type', '');

//        select the operation

        $operationSelect = DB::table('tbl_operations')->pluck('operation_name', 'id');
        $operationSelect->prepend('Select Operation', '');


//        select the volume
        $volumeSelect = DB::table('tbl_volume')->pluck('volume_name', 'id');
        $volumeSelect->prepend('Select Volume Type', '');

//        select channel numbers

        $channelNumberSelect = DB::table('tbl_channel_numbers')->pluck('channel_number', 'id');
        $channelNumberSelect->prepend('Select Channel Numbers', '');

//        select the units

        $units = DB::table('tbl_units')->pluck('unit', 'id');
        $units->prepend('Select Unit', '');
        $testpoints = DB::table('tbl_test_points')->select('name', 'id')->get();

        $data = [
            'id' => $id,
            'customer_id' => $customer_id,
            'channel_id' => isset($input['channel_id']) ? $input['channel_id'] : '',
            'channel_number' => isset($input['channel_number']) ? $input['channel_number'] : '',
            'operation_id' => isset($input['operation_id']) ? $input['operation_id'] : '',
            'volume_id' => isset($input['volume_id']) ? $input['volume_id'] : '',
            'volume_value' => isset($input['volume_value']) ? $input['volume_value'] : '',
            'unit' => isset($input['unit']) ? $input['unit'] : '',
            'volume_value_from' => '',
            'volume_value_to' => '',
            'tolerances' => array()
        ];

        if ($id) {
            $getvalue = $this->iso->getiso($data['id']);
            $gettolerancevalue = $this->iso->isotolerances($data['id']);
            if (!$getvalue) {
                return redirect('admin/isospecificationlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['customer_id'] = $customer_id;
                $data['channel_id'] = $getvalue->channel_id;
                $data['channel_number'] = $getvalue->channel_number;
                $data['operation_id'] = $getvalue->operation_id;
                $data['volume_id'] = $getvalue->volume_id;
                $data['volume_value'] = $getvalue->volume_value;
                $data['volume_value_array'] = explode('-', $getvalue->volume_value);
                $data['volume_value_from'] = (isset($data['volume_value_array'][0]) && $data['volume_value_array'][0]) ? $data['volume_value_array'][0] : '';
                $data['volume_value_to'] = (isset($data['volume_value_array'][1]) && $data['volume_value_array'][1]) ? $data['volume_value_array'][1] : '';
                $data['unit'] = $getvalue->unit;
                $data['tolerances'] = $gettolerancevalue;
                //echo'<pre>';print_r($data['tolerances']);'</pre>';die;
            }
        }
        $rules = [
            'channel_id' => 'required',
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
            $view = View::make('customerspecifications.customerspecificationview')->with('input', $data)->with('channels', $channels)
                ->with('operations', $operationSelect)
                ->with('volumes', $volumeSelect)
                ->with('units', $units)
                ->with('channelNumberSelect', $channelNumberSelect)
                ->with('testpoints', $testpoints);
            $formData = $view->render();
            die(json_encode(array('result' => true, "formData" => trim($formData))));
        }
    }


}
