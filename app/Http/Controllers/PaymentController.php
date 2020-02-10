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
use Illuminate\Support\Facades\Hash;
use App\Equipment;
use App\Models\Servicerequest;
use App\Models\Dashboard;
use App\Models\Payment;
use Validator;
use Carbon\Carbon;
use View;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use Mail;

class PaymentController extends Controller
{
    private $user;
    public $uid;
    public $cid;
    public $roleid;
    public function __construct(User $user){
        $this->user = $user;
        $this->equipment = new Equipment();
        $this->userModel = new User();
        $this->serviceModel = new Servicerequest();
        $this->dashboardModel = new Dashboard();
        $this->paymentModel = new Payment();
    }

    public function createpurchaseorder(Request $request){

        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
            return Response::json([
                'status' => 0,
                'message' => 'User not found'
            ], 422);
        }

        $customer = $this->userModel->getUserCustomer($user['id']);
        //echo'<pre>';print_r($customer);'</pre>';die;
        $this->uid = $user['id'];
        $this->cid = $customer->id;
        $this->roleid = $customer->role_id;
        $reqInputs = $request->json()->all();
        $input = [
            'request_id' => isset($reqInputs['request_id'])?$reqInputs['request_id']:'',
            'request_item_ids' => isset($reqInputs['request_item_ids'])?$reqInputs['request_item_ids']:''
        ];
        $rules = array(

            'request_id' => 'required',
            'request_item_ids' => 'required'
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

        $request_id = $input['request_id'];
        $request_item_ids = $input['request_item_ids'];
        $document = (isset($reqInputs['document']) && $reqInputs['document'])?$reqInputs['document']:'';
        $itemArray = explode(',',$request_item_ids);
        $amt = 0;
        $request = $this->paymentModel->getRequest($request_id);
        $mainPath = public_path() . '/purchaseorder/customerdocuments/';
        $location = $mainPath;
        $trimmedLocation = str_replace('\\', '/', $location);
        if ($document) {
            $offset1 = strpos($document, ',');
            $tmp = base64_decode(substr($document, $offset1));
            $memType = $this->_file_mime_type($tmp);
            $fileType = explode('/', $memType);
            $fileType = $fileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
            $docuName = 'PO' . '-' . uniqid() . '.' . $fileType;


            //    image upload
            //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

            $filepath = $trimmedLocation;
            if (!is_dir($filepath)) {
                return Response::json([
                    'status' => 0,
                    'message' => 'The path you given was invalid'
                ], 400);
            }
            $uploadedFile = file_put_contents($filepath . '/' . $docuName, $tmp);

            if ($uploadedFile) {
                $document_name =$docuName;
            }
            else
            {
                $document_name = '';
            }

            $save['po_document'] = $document_name;

        }
        else
        {
            $document_name = '';
            $uploadedFile='';
        }


        if($request)
        {
            if($itemArray)
            {
                $shippingCharge = 0;
                $discountCharge=0;
                $query = DB::table('tbl_purchase_order');
                $query->select('id');
                $query->orderBy('id','DESC');
                $result = $query->first();
                $count = (isset($result->id) && $result->id)?($result->id+1):1;
                $save['id'] = false;
                $save['request_id'] = $request->id;
                $save['total_items'] = count($itemArray);
                $save['invoice_generation'] = 0;
                $save['customer_id'] = $this->cid;
                $save['created_by'] = $this->uid;
                $save['order_flg'] = 1;
                $save['payment_flg'] = 0;
                $save['customer_po_num'] = $reqInputs['customer_po_num'];
               // print_r($save);die;
                $last_id = $this->paymentModel->saveorder($save);
                $saveup['id'] = $last_id;
                $saveup['order_number'] = 'PO' . str_pad($last_id, 3, '0', STR_PAD_LEFT);
                $last_id = $this->paymentModel->saveorder($saveup);
                $spare_cost = 0;
                foreach ($itemArray as $key=>$row)
                {
                    $price = $this->paymentModel->getPrice($row,$cond=array('select'=>'p.price'));
                    $pricevalue = isset($price->price)?$price->price:'0';

                    $spare_amount = 0;
                    $spares = DB::table('tbl_workorder_maintenance_log as log')->
                    join('tbl_workorder_status_move as sm','sm.id','=','log.workorder_status_id')->
                    join('tbl_work_order_items as oi','oi.id','=','sm.workorder_item_id')->
                    select('log.workorder_spares')->
                    where('oi.request_item_id',$row)->get()->first();
                    if($spares)
                    {
                        $spares_used = json_decode($spares->workorder_spares);
                        if($spares_used)
                        {
                            foreach ($spares_used as $sparekey=>$sparerow)
                            {
                                $spare_amount+=$sparerow->amount;
                            }

                        }
                    }
//                    $total_amount = ($pricevalue)+$spare_amount;
                    $total_amount = ($pricevalue);

                    $saveitems['id'] = false;
                    $saveitems['order_id'] = $last_id;
                    $saveitems['request_item_id'] = $row;
                    $saveitems['order_item_amt'] = $total_amount;
                    $saveitems['invoice_generation'] = 0;
                    $saveitems['created_by'] = $this->uid;
                    $this->paymentModel->saveitems($saveitems);
                    $savestatus['id'] = $row;
                    $savestatus['order_status'] = 1;
                    $savestatus['payment_status'] = 0;
                    $this->paymentModel->savestatus($savestatus);
                    $amt+=$total_amount;
                    $spare_cost+=$spare_amount;
                }

                if($customer)
                {
                    $shipping_id = (isset($customer->shipping)&&$customer->shipping)?$customer->shipping:'';
                    if($shipping_id)
                    {
                        $shipping_detail = DB::table('tbl_shipping as S')
                            ->where('S.id',$shipping_id)
                            ->select('S.*')
                            ->first();
                        if($request->on_site==2)
                        {
                            $shippingCharge = (isset($shipping_detail->shipping_charge)&&$shipping_detail->shipping_charge)?$shipping_detail->shipping_charge:'';
                        }
                        else
                        {
                            $shippingCharge = 0;
                        }

                    }

                    $serviceRequest = DB::table('tbl_service_request')
                        ->where('id',$request->id)
                        ->first();
                    $discountCharge = (isset($serviceRequest->discount)&&$serviceRequest->discount)?$serviceRequest->discount:0;
                }

                $saveamt['id'] = $last_id;
                $saveamt['order_amt'] = $amt;
                $saveamt['spares_amount'] = $spare_cost;
                $saveamt['discount'] = $discountCharge;
                $saveamt['total_amount'] = ($amt+$spare_cost)-($discountCharge);
                $saveamt['shipping_price']=$shippingCharge;
                $saveamt['grand_total'] = $saveamt['total_amount']+$shippingCharge;
               // echo '<pre>';print_r($saveamt);die;
                $last_id = $this->paymentModel->saveorder($saveamt);

                $pathToFile = base_path() . '/public/purchaseorder/customerdocuments/' . $document_name;
                $query = DB::table('tbl_email_template');
                $query->where('tplid', '=', 11);
                $result = $query->first();

                $result->tplmessage = str_replace('{name}', $customer->customer_name, $result->tplmessage);
                $result->tplmessage = str_replace('{requestnumber}', $request->request_no, $result->tplmessage);

                $param['message'] = $result->tplmessage;
                $param['name'] = $customer->customer_name;
                $param['title'] = $result->tplsubject;

                $data = array('data'=>$param);
                if($customer->customer_email)
                {
                    Mail::send(['html' => 'email/template'], ['data' => $param], function($message) use ($param,$uploadedFile,$pathToFile) {
                        $message->to(env('administration_mail'))->subject
                        ($param['title']);
                        if($uploadedFile)
                        {
                            $message->attach($pathToFile,['as' => 'Purchaseorder.pdf', 'mime' => 'pdf']);
                        }

                    });
                }

                return Response::json([
                    'status' => 1,
                    'msg' => "New purchase order has been generated",
                    "order_number"=>$saveup['order_number']
                ], 200);

            }
            else
            {
                return Response::json([
                    'status' => 0,
                    'msg' => "This service request items missing"
                ], 400);
            }
        }
        else
        {
            return Response::json([
                'status' => 0,
                'msg' => "This service request not found"
            ], 404);
        }

    }


    public function purchaseorderlists(Request $request)
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
        $fParams['invoice'] = isset($reqInputs['invoice']) ? $reqInputs['invoice'] : '';
        $select = array('PO.id', 'PO.order_number', 'PO.total_items',
            'PO.invoice_generation', 'PO.po_document', 'PO.order_amt as orderAmt','PO.spares_amount','PO.shipping_price','PO.grand_total',
            'SR.request_no', 'U.name','PO.created_on','PO.invoice_generation','PO.invoice_file','PO.payment_flg');
        $purchaseorders = $this->paymentModel->getAllPurchaseOrders($fParams['limit'], $fParams['offset'],'PO.id', 'DESC', array('select' => $select, 'search' => $fParams['keyword'],'invoice' => $fParams['invoice'], 'cus_id' => $this->cid,'role_id'=>$this->roleid,'user_id'=>$this->uid));
        $counteorders = $this->paymentModel->countpurchaseorders(false,false,array('select' => $select, 'search' => $fParams['keyword'],'invoice' => $fParams['invoice'], 'cus_id' => $this->cid,'role_id'=>$this->roleid,'user_id'=>$this->uid));
        $temp = array();
        if($purchaseorders)
        {
            foreach ($purchaseorders as $key=>$row)
            {

                $tempitems = array();
                $temp[$key] = (array)$row;
                //$temp[$key]['order_amt'] = $row->orderAmt+$row->spares_amount;
                $temp[$key]['order_amt'] = $row->grand_total;
                $select = array('OI.order_item_amt','E.asset_no', 'E.serial_no', 'E.pref_contact',
                    'E.pref_tel', 'E.pref_email', 'E.location',
                    'EM.model_description as model_name');
                $items = $this->paymentModel->orderItems($row->id,array('select'=>$select));
                foreach ($items as $ikey=>$irow)
                {
                    $tempitems[$ikey] = (array)$irow;


                }
                $temp[$key]['created_date'] = date('Y-m-d',strtotime($row->created_on));
                $temp[$key]['orderItems'] = $tempitems;
                if($row->invoice_generation==1)
                {
                    $filepath = base_path().'/public/purchaseorder/invoices/'.$row->invoice_file;
                    if(file_exists($filepath))
                    {
                        $path  = 'novamed/public/purchaseorder/invoices';
                        $pathToFile = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $path.'/'.$row->invoice_file;
                          //$pathToFile =   $path.'/'.$row->invoice_file;
                    }
                    else
                    {
                        $pathToFile = '';
                    }
                }
                else
                {
                    $pathToFile = '';
                }

                $temp[$key]['pathtofile'] = $pathToFile;


            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'count'=>count($counteorders)

        ], 200);
    }

    public function paymentwithoutorder(Request $request){

        $token = app('request')->header('token');
        $user = JWTAuth::toUser($token);
        if(count($user) < 0)
        {
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
        $input = [
            'card_number' => isset($reqInputs['card_number'])?$reqInputs['card_number']:'',
            'expiry_date' => isset($reqInputs['expiry_date'])?$reqInputs['expiry_date']:'',
            'cvv' => isset($reqInputs['request_id'])?$reqInputs['cvv']:'',
            'amount' => isset($reqInputs['amount'])?$reqInputs['amount']:'',
            'request_id' => isset($reqInputs['request_id'])?$reqInputs['request_id']:'',
            'request_item_ids' => isset($reqInputs['request_item_ids'])?$reqInputs['request_item_ids']:''
        ];//echo'<pre>';print_r($input);'</pre>';die;
        $rules = array(
            'card_number' => 'required',
            'expiry_date' => 'required',
            'cvv' => 'required',
            'amount' => 'required',

            'request_id' => 'required',
            'request_item_ids' => 'required'

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

        $request_id = $input['request_id'];
        $request_item_ids = $input['request_item_ids'];
        $document = (isset($reqInputs['document']) && $reqInputs['document'])?$reqInputs['document']:'';
        $itemArray = explode(',',$request_item_ids);
        $amt = 0;
        $requestcheck = $this->paymentModel->getRequest($request_id);
        if(empty($requestcheck))
        {
            return Response::json([
                'status' => 0,
                'msg' => "This service request is not found"
            ], 404);
        }
        if($requestcheck) {
            define("AUTHORIZENET_LOG_FILE", "phplog");
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
//            $merchantAuthentication->setName((string)env('payment_login_id'));
//            $merchantAuthentication->setTransactionKey((string)env('payment_transaction_key'));

            $merchantAuthentication->setName((string)'6kXbuv8H3Km');
            $merchantAuthentication->setTransactionKey((string)'98ZTC679zxs6u5ma');

            $refId = 'ref' . time();

            // Create the payment data for a credit card
            $cardNumber = str_replace(' ', '', $input['card_number']);
            $replace = ['/', ' '];
            $new = ['', ''];
            $expiryDate = str_replace($replace, $new, $input['expiry_date']);
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($cardNumber);
            $creditCard->setExpirationDate($expiryDate);
            $creditCard->setCardCode($input['cvv']);
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);

            // Create a transaction
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($input['amount']);
            $transactionRequestType->setPayment($paymentOne);
            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
            //echo'<pre>';print_r($response);'</pre>';die;
            if ($response != null) {
                $tresponse = $response->getTransactionResponse();

                if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {

                    $mainPath = public_path() . '/purchaseorder/customerdocuments/';
                    $location = $mainPath;
                    $trimmedLocation = str_replace('\\', '/', $location);
                    if ($document) {
                        $offset1 = strpos($document, ',');
                        $tmp = base64_decode(substr($document, $offset1));
                        $memType = $this->_file_mime_type($tmp);
                        $fileType = explode('/', $memType);
                        $fileType = $fileType[1];
//                $imagesize=getimagesize($tmp);
//                $width=$imagesize[0];
//                $height=$imagesize[1];
                        $docuName = 'PO' . '-' . uniqid() . '.' . $fileType;


                        //    image upload
                        //$image = $this->image->imageUpload($data['image'], '/images/commbuzz/original/', $imageName);

                        $filepath = $trimmedLocation;
                        if (!is_dir($filepath)) {
                            return Response::json([
                                'status' => 0,
                                'message' => 'The path you given was invalid'
                            ], 400);
                        }
                        $uploadedFile = file_put_contents($filepath . '/' . $docuName, $tmp);

                        if ($uploadedFile) {
                            $document_name = $docuName;
                        } else {
                            $document_name = '';
                        }

                        $save['po_document'] = $document_name;

                    }
                    if ($itemArray) {
                        $query = DB::table('tbl_purchase_order');
                        $query->select('id');
                        $query->orderBy('id', 'DESC');
                        $result = $query->first();
                        $count = (isset($result->id) && $result->id) ? ($result->id + 1) : 1;
                        $save['id'] = false;
                        $save['request_id'] = $requestcheck->id;
                        $save['total_items'] = count($itemArray);
                        $save['invoice_generation'] = 0;
                        $save['customer_id'] = $this->cid;
                        $save['created_by'] = $this->uid;
                        $save['order_flg'] = 2;
                        $save['payment_flg'] = 1;
                        // print_r($save);die;
                        $last_id = $this->paymentModel->saveorder($save);
                        $saveup['id'] = $last_id;
                        $saveup['order_number'] = 'PO' . str_pad($last_id, 3, '0', STR_PAD_LEFT);
                        $last_id = $this->paymentModel->saveorder($saveup);
                        $spare_cost = 0;
                        foreach ($itemArray as $key => $row) {
                            $price = $this->paymentModel->getPrice($row, $cond = array('select' => 'p.price'));
                            $pricevalue = isset($price->price) ? $price->price : '0';

                            $spare_amount = 0;
                            $spares = DB::table('tbl_workorder_maintenance_log as log')->
                            join('tbl_workorder_status_move as sm','sm.id','=','log.workorder_status_id')->
                            join('tbl_work_order_items as oi','oi.id','=','sm.workorder_item_id')->
                            select('log.workorder_spares')->
                            where('oi.request_item_id',$row)->get()->first();
                            if($spares)
                            {
                                $spares_used = json_decode($spares->workorder_spares);
                                if($spares_used)
                                {
                                    foreach ($spares_used as $sparekey=>$sparerow)
                                    {
                                        $spare_amount+=$sparerow->amount;
                                    }

                                }
                            }
                            $total_amount = ($pricevalue);

                            $saveitems['id'] = false;
                            $saveitems['order_id'] = $last_id;
                            $saveitems['request_item_id'] = $row;
                            $saveitems['order_item_amt'] = $total_amount;
                            $saveitems['invoice_generation'] = 0;
                            $saveitems['created_by'] = $this->uid;
                            $this->paymentModel->saveitems($saveitems);

                            $savestatus['id'] = $row;
                            $savestatus['order_status'] = 1;
                            $savestatus['payment_status'] = 0;
                            $this->paymentModel->savestatus($savestatus);
                            $amt+=$total_amount;
                            $spare_cost+=$spare_amount;
                        }

                        if($customer)
                        {
                            $shippingCharge = 0;
                            $shipping_id = (isset($customer->shipping)&&$customer->shipping)?$customer->shipping:'';
                            if($shipping_id)
                            {
                                $shipping_detail = DB::table('tbl_shipping as S')
                                    ->where('S.id',$shipping_id)
                                    ->select('S.*')
                                    ->first();
                                if($requestcheck->on_site==2)
                                {
                                    $shippingCharge = (isset($shipping_detail->shipping_charge)&&$shipping_detail->shipping_charge)?$shipping_detail->shipping_charge:'';
                                }
                                else
                                {
                                    $shippingCharge = 0;
                                }

                            }

                            $serviceRequest = DB::table('tbl_service_request')
                                ->where('id',$requestcheck->id)
                                ->first();
                            $discountCharge = (isset($serviceRequest->discount)&&$serviceRequest->discount)?$serviceRequest->discount:0;
                        }

                        $saveamt['id'] = $last_id;
                        $saveamt['order_amt'] = $amt;
                        $saveamt['spares_amount'] = $spare_cost;
                        $saveamt['discount'] = $discountCharge;
                        $saveamt['total_amount'] = ($amt+$spare_cost)-($discountCharge);
                        $saveamt['shipping_price']=$shippingCharge;
                        $saveamt['grand_total'] = $saveamt['total_amount']+$shippingCharge;

                        $last_id = $this->paymentModel->saveorder($saveamt);

                        return Response::json([
                            'status' => 1,
                            'msg' => "Payment has been successfully completed"
                        ], 200);

                    } else {
                        return Response::json([
                            'status' => 0,
                            'msg' => "This service request items missing"
                        ], 400);
                    }

                } else {
                    //echo'<pre>';print_r($tresponse);'</pre>';die;
                    if ($tresponse->getErrors()) {
                        return Response::json([
                            'status' => 0,
                            'msg' => $tresponse->getErrors()[0]->getErrorText()
                        ], 200);
                    } else {
                        return Response::json([
                            'status' => 0,
                            'msg' => "Unexpected error has been occurred"
                        ], 200);
                    }

                    //echo "Charge Credit Card ERROR :  Invalid response\n";
                }
            } else {
                return Response::json([
                    'status' => 0,
                    'msg' => "Payment not processing.Please try after sometime"
                ], 200);
            }
        }

    }

    public function paymentlists(Request $request)
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
        $fParams['invoice'] = isset($reqInputs['invoice']) ? $reqInputs['invoice'] : '';
        $select = array('PO.id', 'PO.order_number', 'PO.total_items',
            'PO.invoice_generation', 'PO.po_document', 'PO.order_amt as orderAmt','PO.spares_amount','PO.shipping_price','PO.grand_total',
            'SR.request_no', 'U.name','PO.created_on');
        $purchaseorders = $this->paymentModel->getAllPurchaseOrders($fParams['limit'], $fParams['offset'],'PO.id', 'DESC', array('select' => $select, 'search' => $fParams['keyword'],'invoice' => $fParams['invoice'], 'cus_id' => $this->cid,'role_id'=>$this->roleid,'user_id'=>$this->uid,'payment'=>1));
        $counteorders = $this->paymentModel->countpurchaseorders(false,false,array('select' => $select, 'search' => $fParams['keyword'],'invoice' => $fParams['invoice'], 'cus_id' => $this->cid,'role_id'=>$this->roleid,'user_id'=>$this->uid,'payment'=>1));
        $temp = array();
        if($purchaseorders)
        {
            foreach ($purchaseorders as $key=>$row)
            {
                $tempitems = array();
                $temp[$key] = (array)$row;
               // $temp[$key]['order_amt'] = $row->orderAmt+$row->spares_amount;
                $temp[$key]['order_amt'] = $row->grand_total;
                $select = array('OI.order_item_amt','E.asset_no', 'E.serial_no', 'E.pref_contact',
                    'E.pref_tel', 'E.pref_email', 'E.location',
                    'EM.model_name');
                $items = $this->paymentModel->orderItems($row->id,array('select'=>$select));
                foreach ($items as $ikey=>$irow)
                {
                    $tempitems[$ikey] = (array)$irow;

                }
                $temp[$key]['created_date'] = date('Y-m-d',strtotime($row->created_on));
                $temp[$key]['orderItems'] = $tempitems;

            }
        }

        return Response::json([
            'status' => 1,
            'data' => $temp,
            'count'=>count($counteorders)

        ], 200);
    }

    public function paymentwithinvoice(Request $request)
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
        $input = [
            'id' => isset($reqInputs['id'])?$reqInputs['id']:''
        ];
        $rules = array(

            'id' => 'required'
        );
        $validator = Validator::make($reqInputs, [
            'id' => 'required'
        ]);
        $checkValid = Validator::make($input, $rules);

        if ($validator->fails()) {

            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::make([
                'message'   => 'Validation Failed',
                'errors'        => $validator->errors()
            ]);
        }

        $id = $reqInputs['id'];
        if($id)
        {
            $order = $this->paymentModel->orderDetails($id);
            if($order)
            {
                $save['id'] = $id;
                $save['payment_flg'] = 1;
                $order_id = $this->paymentModel->saveorder($save);
                if($order_id)
                {
                    return Response::json([
                        'status' => 1,
                        'msg' => 'Payment has been successfully completed'

                    ], 200);
                }
                else
                {
                    return Response::json([
                        'status' => 0,
                        'msg' => 'Something going wrong'

                    ], 200);
                }
            }
            else
            {
                return Response::json([
                    'status' => 0,
                    'msg' => 'This order not found'

                ], 404);
            }

        }
        else
        {
            return Response::json([
                'status' => 0,
                'msg' => 'id is missing'

            ], 400);
        }



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

    function paymentapicall(Request $request)
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
        $input = [
            'card_number' => isset($reqInputs['card_number'])?$reqInputs['card_number']:'',
            'expiry_date' => isset($reqInputs['expiry_date'])?$reqInputs['expiry_date']:'',
            'cvv' => isset($reqInputs['cvv'])?$reqInputs['cvv']:'',
            'order_id' => isset($reqInputs['order_id'])?$reqInputs['order_id']:'',
            'amount' => isset($reqInputs['amount'])?$reqInputs['amount']:''
        ];
        $rules = array(

            'card_number' => 'required',
            'expiry_date' => 'required',
            'cvv' => 'required',
            'order_id' => 'required',
            'amount' => 'required'
        );
        $checkValid = Validator::make($input, $rules);
        if ($checkValid->fails()) {

            //$this->status = 0;
            // $this->message = $checkValid->errors()->all();
            return Response::make([
                'message'   => 'Validation Failed',
                'errors'        => $checkValid->errors()
            ]);
        }
        $order = $this->paymentModel->orderDetails($input['order_id']);
        if(empty($order))
        {
            return Response::json([
                'status' => 0,
                'msg' => 'This Order not found'

            ], 404);
        }
        else
        {
            if($order->payment_flg==1)
            {

                return Response::json([
                    'status' => 0,
                    'msg' => 'Payment already completed'

                ], 500);
            }
            define("AUTHORIZENET_LOG_FILE","phplog");
            $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
//            $merchantAuthentication->setName((string)env('payment_login_id'));
//            $merchantAuthentication->setTransactionKey((string)env('payment_transaction_key'));

            $merchantAuthentication->setName((string)'6kXbuv8H3Km');
            $merchantAuthentication->setTransactionKey((string)'98ZTC679zxs6u5ma');


            $refId = 'ref' . time();

            // Create the payment data for a credit card
            $cardNumber = str_replace(' ','',$input['card_number']);
            $replace = ['/',' '];
            $new = ['',''];
            $expiryDate = str_replace($replace, $new, $input['expiry_date']);
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($cardNumber);
            $creditCard->setExpirationDate($expiryDate);
            $creditCard->setCardCode($input['cvv']);
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);

            // Create a transaction
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("authCaptureTransaction");
            $transactionRequestType->setAmount($input['amount']);
            $transactionRequestType->setPayment($paymentOne);
            $request = new AnetAPI\CreateTransactionRequest();
            $request->setMerchantAuthentication($merchantAuthentication);
            $request->setRefId($refId);
            $request->setTransactionRequest($transactionRequestType);
            $controller = new AnetController\CreateTransactionController($request);
            $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::PRODUCTION);
            if ($response != null)
            {
                $tresponse = $response->getTransactionResponse();
                // echo'<pre>';print_r($tresponse);'</pre>';die;
                if (($tresponse != null) && ($tresponse->getResponseCode()=="1"))
                {

                    $save['id'] = $order->id;
                    $save['payment_flg'] = 1;
                    $this->paymentModel->saveorder($save);
                    return Response::json([
                        'status' => 1,
                        'msg' => 'Payment successful',
                        'auth_code'=>$tresponse->getAuthCode(),
                        'trans_id'=>$tresponse->getTransId()

                    ], 200);
                }
                else
                {
                    if($tresponse->getErrors())
                    {
                        return Response::json([
                            'status' => 0,
                            'msg' => $tresponse->getErrors()[0]->getErrorText()
                        ], 200);
                    }
                    else
                    {
                        return Response::json([
                            'status' => 0,
                            'msg' => "Unexpected error has been occurred"
                        ], 200);
                    }

                    //echo "Charge Credit Card ERROR :  Invalid response\n";
                }
            }
            else
            {
                return Response::json([
                    'status' => 0,
                    'msg' => "Payment not processing.Please try after sometime"
                ], 200);
            }
        }


    }
}