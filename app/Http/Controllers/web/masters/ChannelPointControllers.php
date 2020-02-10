<?php

namespace App\Http\Controllers\web\masters;

use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Channels;
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

class ChannelPointControllers extends Controller
{

    public function __construct()
    {
        $this->channel = new Channels();
    }

    public function ChannelPointList(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Channel Points';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tcn.id as channelNumberId', 'tcn.channel_number as channelNumber', 'tcp.id as channelPointId', 'tcp.point_name as pointName', 'tcp.point_channel as channelPoint');
            $data = $this->channel->AllChannelPoints('', '', 'tcn.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tcn.channel_number', 'tcp.point_name','tcp.point_channel'));
        } else {
            $select = array('tcn.id as channelNumberId', 'tcn.channel_number as channelNumber', 'tcp.id as channelPointId', 'tcp.point_name as pointName', 'tcp.point_channel as channelPoint');
            $data = $this->channel->AllChannelPoints('', '', 'tcn.id', 'DESC', array('select' => $select));
        }

        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.channelPoints.channelpointlist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }
    public function listData(Request $request)
    {


        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['point_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['point_channel'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';

//        echo '<pre>';print_r($search);die;



        $select = array('tcn.id as channelNumberId', 'tcn.channel_number as channelNumber', 'tcp.id as channelPointId', 'tcp.point_name as pointName', 'tcp.point_channel as channelPoint');
        $data = $this->channel->AllChannelPointsGrid($param['limit'], $param['offset'], 'tcn.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tc.channel_name','tcn.channel_number'));
        $count = $this->channel->AllChannelPointsGrid('', '', 'tcn.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tc.channel_name','tcn.channel_number'));


        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->channelNumber;
                $values[$key]['1'] = $row->channelPoint;
                $values[$key]['2'] = "<a href=" . url("admin/editchannelpoints/" . $row->channelPointId) . "><i class='fa fa-pencil'></a>";
                $values[$key]['3'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletechannelpointstype/' . $row->channelPointId) . " class='delete'>
                                                                        <i class='fa fa-trash' aria-hidden='true'></i></a>";
                $i++;
            }

        }
        // echo'<pre>';print_r($values);'</pre>';die;
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }

    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-Channel Points';

        $ChooseChannel = DB::table('tbl_channel_numbers')->pluck('channel_number', 'id');
        $ChooseChannel->prepend('Choose Channel Numbers', '');

        $data = [
            'id' => $id,
            'channel_point' => isset($input['channel_point']) ? $input['channel_point'] : '',
            'channel_number' => isset($input['channel_number']) ? $input['channel_number'] : '',
        ];

        if ($id) {
            $getvalue = $this->channel->getchannelPoint($data['id']);
            if (!$getvalue) {
                return redirect('admin/channelpointslist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['channel_point'] = $getvalue->point_channel;

                $data['channel_number'] = $getvalue->point_name;
            }
        }
        $rules = [
            'channel_number' => 'required',
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
            return view('master.channelPoints.channelPointsForm')->with('input', $data)->with('ChooseChannel', $ChooseChannel)->with('title', $title)->with('errors', $error);
        } else {
            $data = Input::all();

            $save = array();

            $save['id'] = $id;
            $save['point_name'] = $data['channel_number'];
            $save['point_channel'] = $data['channel_point'];


            $Saveresult = $this->channel->saveChannelPoints($save);
            if ($id) {
                return redirect('admin/channelpointslist')->with('message', 'Updated Successfully!');

            } else {
                return redirect('admin/channelpointslist')->with('message', 'Added Successfully!');

            }
        }
    }

    public function getChannelNumbers()
    {

        $input = Input::all();

        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Datas are not found')));
        }

        $channelId = $input['channel_id'];
        if (!$channelId) {
            die(json_encode(array('result' => false, 'message' => 'ChannelId not found')));
        }

        $getChannels = DB::table('tbl_channel_numbers')->where('channel_id', '=', $channelId)->select('channel_number', 'id')->get();
        $data = '';
        $element = '<option value="0">Select Channel Numbers</option>';
        foreach ($getChannels as $val) {
            $element .= '<option value="' . $val->id . '">' . $val->channel_number . '</option>';
        }

        $data = $element;

        die(json_encode(array('result' => true, 'getChannels' => $data)));
    }


//    gettig ch

    public function getchannelpoint()
    {

        $input = Input::all();


        if (!$input) {
            die(json_encode(array('result' => false, 'message' => 'Datas are not found')));
        }

        $channelnumberId = $input['channel_number'];
        if (!$channelnumberId) {
            die(json_encode(array('result' => false, 'message' => 'Channel Number not found')));
        }

        $getChannelNumbers = DB::table('tbl_channel_points as tcp')->where('point_name', '=', $channelnumberId)->join('tbl_channel_numbers as tcn', 'tcn.id', '=', 'tcp.point_name')->select(
            DB::raw("CONCAT(tcn.channel_number,'/',point_channel) AS name"), 'tcp.id')->get();

        $data = '';
        $element = '<option value="">Select Channel Numbers</option>';
        foreach ($getChannelNumbers as $val) {
            $element .= '<option value="' . $val->id . '">' . $val->name . '</option>';
        }

        $data = $element;


        die(json_encode(array('result' => true, 'getChannels' => $data)));
    }
    /**
     * Remove the specified Channel points Type from storage.
     *
     * @param  int  $id
     */
    public function deleteChannelPointsType($channelTypeId)
    {
        $getChannelPointsType = $this->channel->getchannelPoint($channelTypeId);
        if ($getChannelPointsType) {
            $getchannelpoint = DB::table('tbl_service_pricing')->where('channel_point', '=', $channelTypeId)->select('*')->first();
            if ($getchannelpoint) {
                $message = Session::flash('error', "You can't able to delete this Channel Point");
                return redirect('admin/channelpointslist')->with(['error', $message], ['error', $message]);
            }
            $delete = DB::table('tbl_channel_points')->where('id', '=', $channelTypeId)->delete();
            $message = Session::flash('message', 'Deleted Successfully!');
            return redirect('admin/channelpointslist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/channelpointslist')->with('data', $error);
        }
    }


}
