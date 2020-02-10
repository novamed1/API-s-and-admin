<?php

namespace App\Http\Controllers\web;

use App\ContactUs;
use App\Http\Controllers\Controller;
use App\Models\Sentinel\User;
use App\Models\Device;
use Illuminate\Http\Request;
use net\authorize\api\contract\v1\ReturnedItemType;
use Psy\Util\Json;
use Session;
use DB;
use Input;
use Response;
use Validator;
use View;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Pagination\LengthAwarePaginator;
use Mail;

//use Request;

class ContactUsController extends Controller
{
    public function __construct()
    {
        $this->contact = new ContactUs();

    }

    /**
     * Display a index of ContactUs page
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $input = Input::all();
        $title = 'Novamed-Contact Us';

        $keyword = isset($input['keyword']) ? $input['keyword'] : '';
        if ($keyword != "") {

            $data['search']['keyword'] = $keyword;
            $select = array('tcu.*');
            $data = $this->contact->AllContacts('', '', 'tcu.id', 'DESC', array('select' => $select, 'search' => $data['search']), false, array('tcu.first_name', 'tcu.last_name', 'tcu.email_id', 'tcu.comment'));
        } else {
            $select = array('tcu.*');
            $data = $this->contact->AllContacts('', '', 'tcu.id', 'DESC', array('select' => $select));

        }
        $perPage = 10;
        $paginatedItems = new LengthAwarePaginator($data, count($data), $perPage);
        $items = $paginatedItems->getCollection();
        $userDetail = $paginatedItems->setCollection(
            $items->forPage($paginatedItems->currentPage(), $perPage)
        );
        $userDetail->setPath($request->url());
        return view('master.contactus')->with('data', $paginatedItems)->with('title', $title)->with('keyword', $keyword);
    }

    /**
     * Display a listing of all ContactUs
     *
     * @param Request $request
     *
     * @return Json
     */

    public function listData(Request $request)
    {

        $input = Input::all();
        $param = array();
        $param['limit'] = $input['iDisplayLength'];
        $param['offset'] = $input['iDisplayStart'];
        $search['first_name'] = isset($input['sSearch_0']) ? $input['sSearch_0'] : '';
        $search['last_name'] = isset($input['sSearch_1']) ? $input['sSearch_1'] : '';
        $search['email_id'] = isset($input['sSearch_2']) ? $input['sSearch_2'] : '';
        $search['comment'] = isset($input['sSearch_3']) ? $input['sSearch_3'] : '';

        $select = array('tcu.*');
        $data = $this->contact->AllContactsGrid($param['limit'], $param['offset'], 'tcu.id', 'DESC', array('select' => $select, 'search' => $search), false, array('tcu.firstname', 'tcu.email_id'));
        $count = $this->contact->AllContactsGrid('', '', 'tcu.id', 'DESC', array('select' => $select, 'search' => $search, 'count' => true), true, array('tcu.firstname', 'tcu.email_id'));

        if ($data) {
            $values = array();
            $i = 0;
            foreach ($data as $key => $row) {
                $values[$key]['0'] = $row->first_name;
                $values[$key]['1'] = $row->last_name;
                $values[$key]['2'] = $row->email_id;
                $values[$key]['3'] = $row->comment;
                $values[$key]['4'] = "<a href='javascript:void(0)' title='Reply'   class='reply' data-attr='$row->id'><i class='fa fa-mail-reply'
                                                                           aria-hidden='true'></i></a>";
                $values[$key]['5'] = " <a href='javascript:void(0)' data-src=" . url('admin/deletecontact/' . $row->id) . " class='delete'>
                                                                        <i class='fa fa-trash' aria-hidden='true'></i></a>";
                $i++;
            }

        }

        echo json_encode(array('sEcho' => $input['sEcho'], 'iTotalRecords' => $count, 'iTotalDisplayRecords' => $count, 'aaData' => $values));

    }
    /**
     * Send Reply Message
     *@param Request $request
     *
     * @return Json
     */
    public function replycontactus(Request $request)
    {
        $input = Input::all();
        $contactDetails = $this->contact->getContactDetails($input['id']);
        $param['message'] = $input['replymessage'];
        $param['name'] = $contactDetails->first_name;
        $param['email'] = $contactDetails->email_id;
        $param['title'] = 'Response Message for Contacting Novamed';
        if ($contactDetails->email_id) {
            Mail::send(['html' => 'email/contactreplytemplate'], ['data' => $param], function ($message) use ($param, $contactDetails) {
                $message->to($contactDetails->email_id)->subject
                ($param['title']);

            });
            die(json_encode(array('result' => true, 'message' => 'Message Send Successfully')));


        } else {
            die(json_encode(array('result' => false)));

        }
    }
    /**
     * Remove the specified ConatctUs from storage.
     *
     * @param  int  $id
     */
    public function deleteContact($contactId)
    {
        $deleteContact = $this->contact->deleteContact($contactId);
        if ($deleteContact == 'true') {
            $message = Session::flash('message', 'Deleted Successfully!');
            return redirect('admin/contactus')->with(['data', $message], ['message', $message]);
        } else {
            $error = Session::flash('message', 'Deleted not successfully!');
            return redirect('admin/contactus')->with('data', $error);
        }
    }


}



