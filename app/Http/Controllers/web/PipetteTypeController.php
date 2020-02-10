<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\PipetteType;
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

class PipetteTypeController extends Controller
{
    public function __Construct(){
        $this->pipettetype =new PipetteType();
    }
    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-PipetteType';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tpt.*');
            $data = $this->pipettetype->AllPipetteType('', '', 'tpt.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tpt.pipette_name'));
        } else {
            $select = array('tpt.*');
            $data = $this->pipettetype->AllPipetteType('', '', 'tpt.id', 'DESC', array('select' => $select));
        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.pipettetype.pipettetypelist')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    public function listData(Request $request)
    {
        $input = Input::all();

        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart']; //echo'<pre>';print_r($input);'</pre>';die;
        $search['pipette_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['alias_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';

        $data = $this->pipettetype->AllPipetteTypeGrid($param['limit'], $param['offset'], 'tpt.id', 'DESC', array('search' => $search), false, array('tpt.pipette_name','tpt.alias_name'));
        $count = $this->pipettetype->AllPipetteTypeGrid('', '', 'tpt.id', 'DESC', array('search' => $search, 'count' => true), true, array('tpt.pipette_name','tpt.alias_name'));

        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->pipette_name;
                $values[$key]['1'] = $row->alias_name;
                if ($row->is_active == 1) {

                    $values[$key]['2'] = "<span class='label label-success'>Active</span>";
                } else {
                    $values[$key]['2'] = "<span class='label label-warning'>InActive</span>";
                }

                $values[$key]['3'] = "<a href=" . url("admin/editpipettetype/" . $row->id) . "><i class='fa fa-pencil'></a>";

                $values[$key]['4'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletepipettetype/' . $row->id) . "
                                                                       class='delete'>
                                                                        <i class='fa fa-trash'
                                                                           aria-hidden='true'></i></a>";
                $i++;
            }

        }
        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }


    public function form(Request $request, $id = false)
    {
        $input = Input::all();
        $title = 'Novamed-PipetteType';
        $data = [
            'id' => $id,
            'pipette_name' => isset($input['pipette_name']) ? $input['pipette_name'] : '',
            'alias_name' => isset($input['alias_name']) ? $input['alias_name'] : '',
            'is_active' => isset($input['is_active']) ? $input['is_active'] : '',
        ];

        if ($id) {
            $getvalue = $this->pipettetype->getpipettetype($data['id']);
            if (!$getvalue) {
                return redirect('admin/pipettetypelist')->with('message', 'Sorry! Details are not found.');
            } else {
                $data['id'] = $getvalue->id;
                $data['pipette_name'] = $getvalue->pipette_name;
                $data['alias_name'] = $getvalue->alias_name;
                $data['is_active'] = $getvalue->is_active;
            }
        }
        $rules = [
            'pipette_name' => 'required',
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
            return view('master.pipettetype.pipettetypeForm')->with('input', $data)
                ->with('title', $title)
                ->with('errors', $error);
        } else {
            $data = Input::all();

//            echo '<pre>';print_R($data['alias_name']);exit;
            $save = array();

            $save['id'] = $id;
            $save['pipette_name'] = $data['pipette_name'];
            $save['alias_name'] = ($data['alias_name'] != '') ? $data['alias_name'] :NULL;

            if (isset($data['is_active']) ? $data['is_active'] : '0') {
                $save['is_active'] = 1;
            } else {
                $save['is_active'] = 0;
            }
//            echo '<pre>';print_R($save);exit;
            $Saveresult = $this->pipettetype->savePipetteType($save);
            if($id){
                return redirect('admin/pipettetypelist')->with('message', 'Updated Successfully');
            }else{
                return redirect('admin/pipettetypelist')->with('message', 'Added Successfully');
            }

        }
    }


    public
    function delete($id)
    {

        $getpipettetype = $this->pipettetype->getpipettetype($id);

        if ($getpipettetype) {

            $message = Session::flash('message', 'Deleted Successfully!');
            $delete = $this->pipettetype->deletepipettetype($id);

            return redirect('admin/pipettetypelist')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/pipettetypelist')->with('data', $error);
        }
    }

}
