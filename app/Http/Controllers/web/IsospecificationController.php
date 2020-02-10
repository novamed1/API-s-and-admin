<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;

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

//use Request;

class IsospecificationController extends Controller
{

    public function __construct()
    {
        $this->iso = new Isospecification();

    }

    public function index(Request $request)

    {

        $input = Input::all();

        $title = 'Iso Specifications';
        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        $postvalue = isset($input['postvalue']) ? $input['postvalue'] : '';
        $postisoid = isset($input['postisoid']) ? $input['postisoid'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('v.volume_name', 'o.operation_name', 'c.channel_name','u.unit','is.volume_value','is.id');
            $data = $this->iso->AllIsoSpecifications('', '', 'is.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('v.volume_name', 'o.operation_name', 'c.channel_name','u.unit','is.volume_value'));

        } else {
            $select = array('v.volume_name', 'o.operation_name', 'c.channel_name','u.unit','is.volume_value','is.id');
            $data = $this->iso->AllIsoSpecifications('', '', 'is.id', 'DESC', array('select' => $select));

        }
        if($data)
        {
            $temp = array();
           foreach ($data as $key=>$row)
           {
               $temp[$key] = $row;
               $tolerance = $this->iso->isotolerances($row->id);
               $temp[$key]->tolerances = $tolerance;
           }
        }
       // echo'<pre>';print_r($temp);'</pre>';die;

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($temp, count($temp), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('iso.isospecificationlist')->with('data', $paginatedItems)->with('keyword', $keyword)->with('title', $title)->with('postvalue', $postvalue)->with('postisoid', $postisoid)->with('datavalues',$paginatedItems);
    }

    function listData(Request $request)
    {
        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['channel_name'] = isset($input['sSearch_1'])?$input['sSearch_1']:'';
        $search['operation_name'] = isset($input['sSearch_2'])?$input['sSearch_2']:'';
        $search['volume_name'] = isset($input['sSearch_3'])?$input['sSearch_3']:'';
        $search['ch.channel_number'] = isset($input['sSearch_4'])?$input['sSearch_4']:'';
        $search['volume_value'] = isset($input['sSearch_5'])?$input['sSearch_5']:'';
        //echo'<pre>';print_r($search);'</pre>';die;
        $select = array('v.volume_name', 'o.operation_name', 'c.channel_name','u.unit','is.volume_value','is.id','ch.channel_number');
        $data = $this->iso->AllIsoSpecificationsGrid($param['limit'], $param['offset'], 'is.id', 'DESC', array('select' => $select, 'search' => $search),
            false);

        $count = $this->iso->AllIsoSpecificationsGrid($param['limit'], $param['offset'], 'is.id', 'DESC', array('select' => $select, 'search' => $search,'count'=>true),
            true);
        if($data)
        { $values = array();
            $i = 0;
            foreach ($data as $key=>$row)
            {
                $values[$key]['0'] = '<span class="lead_numbers" data-id='.$row->id.'>
                                                   <a href="javascript:void(0)"
                                                      id="isotolerances"
                                                      rel='.$row->id.'
                                                      data-toggle="collapse"
                                                      data-target="#isotolerances'.$row->id.'"
                                                      data-id='.$row->id.'
                                                      data-parent="#tagcollapse"
                                                      class="accordion-toggle hov"
                                                   ><i
                                                               class="fa fa-plus-circle ordericon collapseico"
                                                               data-widget-collapsed="true" data-attr='.$row->id.'></i> <span
                                                               class="menu-item-parent"></span></a>
                                                      </span>';
                $values[$key]['1'] = $row->channel_name;
                $values[$key]['2'] = $row->operation_name;
                $values[$key]['3'] = $row->volume_name;
                $values[$key]['4'] = $row->channel_number;
                $values[$key]['5'] = $row->volume_value;
                $values[$key]['6'] = "<a href=".url("admin/editisospecification/".$row->id)."><i class='fa fa-pencil'></a>";
                $i++;
            }

        }
       // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho'=>$input['sEcho'],'iTotalRecords'=>$count,'iTotalDisplayRecords'=>$count,'aaData'=>$values));

    }

    function isosublists(Request $request)
    {
        $input = Input::all();
        $isoId = $input['id'];
        $tolerances = $this->iso->isotolerances($isoId);

        $view = view('iso.isoSublistAjax', ['data' => $tolerances])->render();

        echo json_encode(array('result'=>true,'data'=>$view));
    }



    public function form(Request $request, $id = false)
    {

        $input = Input::all();

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

//        select the

        $units = DB::table('tbl_units')->pluck('unit', 'id');
        $units->prepend('Select Unit', '');
        $testpoints = DB::table('tbl_test_points')->select('name', 'id')->get();


        $data = [
            'id' => $id,
            'channel_id' => isset($input['channel_id']) ? $input['channel_id'] : '',
            'channel_number' => isset($input['channel_number']) ? $input['channel_number'] : '',
            'operation_id' => isset($input['operation_id']) ? $input['operation_id'] : '',
            'volume_id' => isset($input['volume_id']) ? $input['volume_id'] : '',
            'volume_value' => isset($input['volume_value']) ? $input['volume_value'] : '',
            'unit' => isset($input['unit']) ? $input['unit'] : '',
            'volume_value_from' => '',
            'volume_value_to' => '',
            'tolerances'=>array()
        ];

        if ($id) {
            $getvalue = $this->iso->getiso($data['id']);
            $gettolerancevalue = $this->iso->isotolerances($data['id']);
            if (!$getvalue) {
                return redirect('admin/isospecificationlist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['channel_id'] = $getvalue->channel_id;
                $data['channel_number'] = $getvalue->channel_number;
                $data['operation_id'] = $getvalue->operation_id;
                $data['volume_id'] = $getvalue->volume_id;
                $data['volume_value'] = $getvalue->volume_value;
                $data['volume_value_array'] = explode('-',$getvalue->volume_value);
                $data['volume_value_from'] = (isset($data['volume_value_array'][0]) && $data['volume_value_array'][0])?$data['volume_value_array'][0]:'';
                $data['volume_value_to'] = (isset($data['volume_value_array'][1]) && $data['volume_value_array'][1])?$data['volume_value_array'][1]:'';
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
           // print_r($data);die;
            return view('iso.isospecificationform')->with('input', $data)->with('channels', $channels)
                ->with('operations', $operationSelect)
                ->with('volumes', $volumeSelect)
                ->with('units', $units)
                ->with('title', $title)->with('errors', $error)
                ->with('channelNumberSelect',$channelNumberSelect)
                ->with('testpoints',$testpoints);
        } else {
            $data = Input::all();

            $save = array();

            $save['id'] = $id;
            $save['channel_id'] = $input['channel_id'];
            $save['channel_number'] = $input['channel_number'];
            $save['operation_id'] = $input['operation_id'];
            $save['volume_id'] = $input['volume_id'];
            if($input['volume_id']==1)
            {
               $volume_value = $input['volume_from'].'-'.$input['volume_to'];
            }
            else
            {
                $volume_value = $input['volume_from'];
            }
            $save['volume_value'] = $volume_value;
            $save['unit'] = $input['unit'];
            $Saveresult = $this->iso->savespec($save,$loginuserId);
            if($Saveresult)
            {
                $query = DB::table('tbl_iso_limit_tolerance');
                $query->where('specification_id',$Saveresult);
                $query->delete();
                foreach ($input['toleranceArray'] as $row)
                {
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
            if ($id) {
                return redirect('admin/isospecificationlist')->with('message', 'Updated Successfully');

            } else {
                return redirect('admin/isospecificationlist')->with('message', 'Added Successfully');

            }
        }
    }


    function saveajaxtolerance()
    {
        $post = Input::all();
        if($post)
        {
            $gettol = $this->iso->gettolerancechange($post);
            $savetol['id'] = $post['id'];
            $savetol['target_value'] = $post['target'];
            $savetol['accuracy'] = $post['accuracy'];
            $savetol['precision'] = $post['precision'];
            $savetol['accuracy_ul'] = $post['accuracy_ul'];
            $savetol['precesion_ul'] = $post['precesion_ul'];
            $id = $this->iso->savetolerance($savetol);
            if(empty($gettol))
            {
                die(json_encode(array('result' => true, 'message' => 'Tolerance values are updated','updated'=>true)));
            }
            else
            {
                die(json_encode(array('result' => true, 'message' => 'Tolerance values are updated','updated'=>false)));
            }


        }
    }

    function checktolerancecombination()
    {
        $post = Input::all();
        //echo'<pre>';print_r($post);'</pre>';die;
        if($post)
        {
           $checkcombination = $this->iso->checkcombination($post);
           if(count($checkcombination))
           {
               die(json_encode(array('result' => true, 'message' => 'These combinations Channel,Operation and Volume are already exist')));
           }
           else
           {
               die(json_encode(array('result' => false)));
           }
        }

    }

}
