<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


//Route::post("/", 'RegisterController@Login');
//Route::get("/", 'RegisterController@Login');
Route::group(['as' => 'admin::', 'prefix' => 'admin'], function () {
    Route::post("/", 'RegisterController@Login');
    Route::get("/", 'RegisterController@Login');
    Route::post("/model", 'web\ModelController@form');
    Route::get("/model", 'web\ModelController@form');
    Route::get("login", 'RegisterController@Login');
    Route::post("login", 'RegisterController@Login');
    Route::get("login", 'RegisterController@index');
    Route::get('logout', 'RegisterController@logout');

    Route::group(['middleware' => 'admin'], function () {

        Route::get("/dashboard", 'RegisterController@dashboard');
        Route::post("/dashboard", 'RegisterController@dashboard');

        //change password
        Route::get('changepasswordForm','RegisterController@changePassword');
        Route::post('changepasswordForm','RegisterController@changePassword');

        Route::post('modellist', ['middleware' => ['permission:modellist'], 'uses' => 'web\ModelController@index']);
        Route::get('modellist', ['middleware' => ['permission:modellist'], 'uses' => 'web\ModelController@index']);

        Route::post('modellistdata', 'web\ModelController@listData');
        Route::post("/testplan", 'web\TestplanController@form');
        Route::get("/testplan", 'web\TestplanController@form');
        Route::post("testplan/limittolerence", 'web\TestplanController@limittolerence');
        Route::post("/testplanlists", 'web\TestplanController@index');
        Route::get("/testplanlists", 'web\TestplanController@index');
        Route::get('edittestplan/{id}', 'web\TestplanController@form');
        Route::post('edittestplan/{id}', 'web\TestplanController@form');
        Route::post('/customerlists', ['middleware' => ['permission:customerlists'], 'uses' => 'web\CustomerController@index']);
        Route::get('/customerlists', ['middleware' => ['permission:customerlists'], 'uses' => 'web\CustomerController@index']);

        Route::post("/customerlistdata", 'web\CustomerController@listData');
        Route::post('/addcustomer', ['middleware' => ['permission:addcustomer'], 'uses' => 'web\CustomerController@form']);
        Route::get('/addcustomer', ['middleware' => ['permission:addcustomer'], 'uses' => 'web\CustomerController@form']);
        Route::post("editcustomer/{id}", 'web\CustomerController@form');
        Route::get("editcustomer/{id}", 'web\CustomerController@form');
        Route::get('services/category/{id}', 'web\services\ServicesController@categoryList');

        Route::get("addsample", 'web\SampleController@form');
        Route::post("addsample", 'web\SampleController@form');
        Route::get("samplelist", 'web\SampleController@index');
        Route::post("samplelist", 'web\SampleController@index');
        Route::post("samplelistdata", 'web\SampleController@listData');

        Route::post("editsample/{id}", 'web\SampleController@form');
        Route::get("editsample/{id}", 'web\SampleController@form');

        Route::get("addcustomertype", 'web\CustomertypeController@form');
        Route::post("addcustomertype", 'web\CustomertypeController@form');
        Route::get("customertypelist", ['uses' => 'web\CustomertypeController@index', 'middleware' => ['permission:customertypelist']]);
        Route::post("customertypelist", ['uses' => 'web\CustomertypeController@index', 'middleware' => ['permission:customertypelist']]);
        Route::post("customertypelistdata", 'web\CustomertypeController@listData');

        Route::post("editcustomertype/{id}", 'web\CustomertypeController@form');
        Route::get("editcustomertype/{id}", 'web\CustomertypeController@form');
        Route::get("addservice", 'web\ServiceController@form');
        Route::post("addservice", 'web\ServiceController@form');
        Route::get('editmodel/{id}', 'web\ModelController@form');
        Route::post('editmodel/{id}', 'web\ModelController@form');
        Route::post("saveajaxSpare", 'web\ModelController@saveajaxSpare');
        Route::post("saveajaxTips", 'web\ModelController@saveajaxTips');
        Route::post("saveajaxAccessory", 'web\ModelController@saveajaxAccessory');
        Route::post("saveajaxManuals", 'web\ModelController@saveajaxManuals');
        Route::post("saveajaxSpecification", 'web\ModelController@saveajaxSpecification');
        Route::post("saveajaxBroucher", 'web\ModelController@saveajaxBroucher');

        Route::get("adddevicemodel", 'web\DevicemodelController@form');
        Route::post("adddevicemodel", 'web\DevicemodelController@form');
        Route::post("devicemodellistdata", 'web\DevicemodelController@listData');

        Route::post('devicemodellist', ['middleware' => ['permission:devicemodellist'], 'uses' => 'web\DevicemodelController@index']);
        Route::get('devicemodellist', ['middleware' => ['permission:devicemodellist'], 'uses' => 'web\DevicemodelController@index']);

        Route::get('editdevicemodel/{id}', 'web\DevicemodelController@form');
        Route::post('editdevicemodel/{id}', 'web\DevicemodelController@form');

        Route::get("addfrequency", 'web\FrequencyController@form');
        Route::post("addfrequency", 'web\FrequencyController@form');
        Route::get("frequency", 'web\FrequencyController@index');
        Route::post("frequency", 'web\FrequencyController@index');
        Route::post("frequencylistdata", 'web\FrequencyController@listData');

        Route::get("editfrequency/{id}", 'web\FrequencyController@form');
        Route::post("editfrequency/{id}", 'web\FrequencyController@form');

        Route::get("adddevice", 'web\DeviceController@form');
        Route::post("adddevice", 'web\DeviceController@form');

        Route::post('devicelist', ['middleware' => ['permission:devicelist'], 'uses' => 'web\DeviceController@index']);
        Route::get('devicelist', ['middleware' => ['permission:devicelist'], 'uses' => 'web\DeviceController@index']);

        Route::post("devicelistdata", 'web\DeviceController@listData');
        Route::get("editdevice/{id}", 'web\DeviceController@form');
        Route::post("editdevice/{id}", 'web\DeviceController@form');

        Route::get("addview", 'web\EquipmentController@form');
        Route::post("addview", 'web\EquipmentController@form');

        Route::post('/viewlist', ['middleware' => ['permission:viewlist'], 'uses' => 'web\EquipmentController@index']);
        Route::get('/viewlist', ['middleware' => ['permission:viewlist'], 'uses' => 'web\EquipmentController@index']);

        Route::post("viewlistdata", 'web\EquipmentController@listData');
        Route::post("viewlistdata/{customer_id}", 'web\EquipmentController@listData');

        Route::post('/dueviewlist', ['middleware' => ['permission:dueviewlist'], 'uses' => 'web\DueEquipmentController@index']);
        Route::get('/dueviewlist', ['middleware' => ['permission:dueviewlist'], 'uses' => 'web\DueEquipmentController@index']);

        Route::post("duelistdata", 'web\DueEquipmentController@listData');

        Route::get("editview/{id}", 'web\EquipmentController@editEquipment');
        Route::post("editview/{id}", 'web\EquipmentController@editEquipment');
        Route::get("customerExport/{id}", 'web\EquipmentController@Excelsheet');

        Route::get("addparts", 'web\EquipmentpartsController@form');
        Route::post("addparts", 'web\EquipmentpartsController@form');
        Route::post("partslist", 'web\EquipmentpartsController@index');
        Route::get("partslist", 'web\EquipmentpartsController@index');
        Route::get("editparts/{id}", 'web\EquipmentpartsController@form');
        Route::post("editparts/{id}", 'web\EquipmentpartsController@form');

        Route::get("productlist", 'web\ProductTypeController@index');
        Route::post("productlist", 'web\ProductTypeController@index');
        Route::post("productlistdata", 'web\ProductTypeController@listData');

        Route::post("addproduct", 'web\ProductTypeController@form');
        Route::get("addproduct", 'web\ProductTypeController@form');
        Route::get("editproduct/{id}", 'web\ProductTypeController@form');
        Route::post("editproduct/{id}", 'web\ProductTypeController@form');

        Route::post('manufacturerlist', ['middleware' => ['permission:manufacturerlist'], 'uses' => 'web\ManufacturerController@index']);
        Route::get('manufacturerlist', ['middleware' => ['permission:manufacturerlist'], 'uses' => 'web\ManufacturerController@index']);

        Route::post("manufacturerlistdata", 'web\ManufacturerController@listData');

        Route::post("addmanufacturer", 'web\ManufacturerController@form');
        Route::get("addmanufacturer", 'web\ManufacturerController@form');
        Route::get("editmanufacturer/{id}", 'web\ManufacturerController@form');
        Route::post("editmanufacturer/{id}", 'web\ManufacturerController@form');

        Route::post('brandlist', ['middleware' => ['permission:brandlist'], 'uses' => 'web\BrandController@index']);
        Route::get('brandlist', ['middleware' => ['permission:brandlist'], 'uses' => 'web\BrandController@index']);

        Route::post("brandlistdata", 'web\BrandController@listData');

        Route::get("addbrand", 'web\BrandController@form');
        Route::post("addbrand", 'web\BrandController@form');
        Route::post("editbrand/{id}", 'web\BrandController@form');
        Route::get("editbrand/{id}", 'web\BrandController@form');

        Route::post('isospecificationlist', ['middleware' => ['permission:isospecificationlist'], 'uses' => 'web\IsospecificationController@index']);
        Route::get('isospecificationlist', ['middleware' => ['permission:isospecificationlist'], 'uses' => 'web\IsospecificationController@index']);

        Route::post("isospeceficationlistdata", 'web\IsospecificationController@listData');
        Route::post("isosublists", 'web\IsospecificationController@isosublists');
        Route::get("isospecification", 'web\IsospecificationController@form');
        Route::post("isospecification", 'web\IsospecificationController@form');
        Route::get("editisospecification/{id}", 'web\IsospecificationController@form');
        Route::post("editisospecification/{id}", 'web\IsospecificationController@form');
        Route::post("saveajaxtolerance", 'web\IsospecificationController@saveajaxtolerance');
        Route::post("checktolerancecombination", 'web\IsospecificationController@checktolerancecombination');

        Route::get("serviceplan", 'web\ServiceController@form');
        Route::post("serviceplan", 'web\ServiceController@form');
        Route::get("editServicePlan/{id}", 'web\ServiceController@form');
        Route::post("editServicePlan/{id}", 'web\ServiceController@form');
        Route::post("saveajaxVolume", 'web\ServiceController@saveajaxVolume');

        Route::post('servicelist', ['middleware' => ['permission:servicelist'], 'uses' => 'web\ServiceController@index']);
        Route::get('servicelist', ['middleware' => ['permission:servicelist'], 'uses' => 'web\ServiceController@index']);

        Route::post("servicelistdata", 'web\ServiceController@listData');

        Route::post("addModel", 'web\ModelController@addEquipmentModel');
        Route::get("modelphotoUpdate", 'web\ModelController@modelphotoUpdate');


        Route::get("customerSetup/{id}", 'web\customer\CustomerSetupControlller@form');
        Route::post("addCustomerSetup", 'web\customer\CustomerSetupControlller@addCustomerSetup');
        Route::get("getCustomer", 'web\customer\CustomerSetupControlller@getCustomer');
        Route::post("addcustomerEquipment", 'web\EquipmentController@addcustomerEquipment');
        Route::get("getCustomerEquipment", 'web\EquipmentController@getCustomerEquipment');
        Route::get("viewServicePlan", 'web\ServiceController@viewServicePlan');

        Route::get("modellistgrid", 'web\ModelController@modelListGrid');
        Route::post("manualdocupload", 'web\ModelController@modeldocupload');
        Route::post("specdocupload", 'web\ModelController@specdocupload');
        Route::post("broucherdocupload", 'web\ModelController@broucherdocupload');
        Route::post("unlinkmanualdoc", 'web\ModelController@UnlinkManualDoc');
        Route::post("unlinkspecdoc", 'web\ModelController@UnlinkSpecDoc');
        Route::post("unlinkbroucherdoc", 'web\ModelController@UnlinkBroucherDoc');
        //Channel Number (Part Type)
        Route::get('partlist', 'web\masters\ChannelNumberController@index');
        Route::get("channelnumberslist", 'web\masters\ChannelNumberController@ChannelNumberList');
        Route::post("channelnumberslist", 'web\masters\ChannelNumberController@ChannelNumberList');
        Route::post("channelnumberslistdata", 'web\masters\ChannelNumberController@listData');
        Route::get("deletechanneltype/{id}", 'web\masters\ChannelNumberController@deleteChannelType');
        Route::get("editchannelnumbers/{id}", 'web\masters\ChannelNumberController@form');
        Route::post("editchannelnumbers/{id}", 'web\masters\ChannelNumberController@form');
        Route::get("addchannelnumbers", 'web\masters\ChannelNumberController@form');
        Route::post("addchannelnumbers", 'web\masters\ChannelNumberController@form');

        Route::get("getchannelnumbers", 'web\masters\ChannelNumberController@getChannelNumbers');
        Route::post("modelimageupload", 'web\ModelController@modelimageupload');
        Route::post("unlinkimagedoc", 'web\ModelController@unlinkimagedoc');

        Route::get("getchannelpoint", 'web\masters\ChannelNumberController@getchannelpoint');
//channel points Type
        Route::get("channelpointslist", 'web\masters\ChannelPointControllers@ChannelPointList');
        Route::post("channelpointslist", 'web\masters\ChannelPointControllers@ChannelPointList');
        Route::post("channelpointslistdata", 'web\masters\ChannelPointControllers@listData');
        Route::get("addchannelpoints", 'web\masters\ChannelPointControllers@form');
        Route::post("addchannelpoints", 'web\masters\ChannelPointControllers@form');
        Route::get("editchannelpoints/{id}", 'web\masters\ChannelPointControllers@form');
        Route::post("editchannelpoints/{id}", 'web\masters\ChannelPointControllers@form');
        Route::get("deletechannelpointstype/{id}", 'web\masters\ChannelPointControllers@deleteChannelPointsType');

        Route::post('/servicerequest', ['middleware' => ['permission:servicerequest'], 'uses' => 'web\services\ServiceRequestController@index']);
        Route::get('/servicerequest', ['middleware' => ['permission:servicerequest'], 'uses' => 'web\services\ServiceRequestController@index']);


        Route::post("servicerequestsublist", 'web\services\ServiceRequestController@servicerequestsublist');
        Route::post("servicerequestlistdata", 'web\services\ServiceRequestController@listData');
        Route::get("getRequestItems", 'web\services\ServiceRequestController@getRequestItems');
//onsite change
        Route::get("onSiteChange", 'web\services\ServiceRequestController@onSiteChange');


        Route::post('addtechnician', ['middleware' => ['permission:addtechnician'], 'uses' => 'web\technician\TechnicianController@form']);
        Route::get('addtechnician', ['middleware' => ['permission:addtechnician'], 'uses' => 'web\technician\TechnicianController@form']);

        Route::get("editTechnician/{id}", 'web\technician\TechnicianController@form');
        Route::post("editTechnician/{id}", 'web\technician\TechnicianController@form');


        Route::post('technicianlist', ['middleware' => ['permission:technicianlist'], 'uses' => 'web\technician\TechnicianController@index']);
        Route::get('technicianlist', ['middleware' => ['permission:technicianlist'], 'uses' => 'web\technician\TechnicianController@index']);

        Route::post("technicianlistdata", 'web\technician\TechnicianController@listData');

        Route::get("detailPage/{id}", 'web\services\ServiceRequestController@detailPage');
        Route::get("workordercreation", 'web\services\WorkorderController@form');
        Route::post("workordercreation", 'web\services\WorkorderController@form');
        Route::post("getServiceRequestCustomer", 'web\services\WorkorderController@getServiceRequestCustomer');
        Route::post("getServiceRequestItems", 'web\services\WorkorderController@getServiceRequestItems');


        Route::get("editEquipment/{id}", 'web\EquipmentController@form');
        Route::post("requestViewDetails/{id}", 'web\services\ServiceRequestController@ViewDetail');
        Route::get("requestViewDetails/{id}", 'web\services\ServiceRequestController@ViewDetail');
 // view summary in Service request:
        Route::get("viewServiceRequestSummary/{id}",'web\services\ServiceRequestController@ViewSummary');


        Route::post("requestDetailsEdit", 'web\services\ServiceRequestController@requestDetailsEdit');
        Route::post("updateProperty", 'web\services\ServiceRequestController@updateProperty');
        Route::post("updateworkOrderProperty", 'web\services\WorkorderController@updateProperty');
        Route::post("saveajaxcustomerproperty", 'web\services\ServiceRequestController@saveajaxcustomerproperty');
        Route::post("mainDetailsEdit", 'web\services\ServiceRequestController@mainDetailsEdit');
        Route::post("paymentedit", 'web\services\ServiceRequestController@paymentedit');
        Route::post("customerDetailsSubmit", 'web\services\ServiceRequestController@customerDetailsSubmit');
        Route::post("paymentDetailsSubmit", 'web\services\ServiceRequestController@paymentDetailsSubmit');


//        work order
        Route::post("addWorkOrder", 'web\services\WorkorderController@addWorkOrder');

        Route::post('workorderlist', ['middleware' => ['permission:workorderlist'], 'uses' => 'web\services\WorkorderController@index']);
        Route::get('workorderlist', ['middleware' => ['permission:workorderlist'], 'uses' => 'web\services\WorkorderController@index']);

        Route::post("workorderlistdata", 'web\services\WorkorderController@listData');
        Route::post("workordersublists", 'web\services\WorkorderController@workordersublists');
        Route::get("exportWorkOrderSearch", 'web\services\WorkorderController@exportWorkOrderSearch');

//        for work order report
        Route::post("workorderReport", 'web\services\WorkorderController@workorderReport');
        Route::get("workorderReport", 'web\services\WorkorderController@workorderReport');
        Route::post("workOrderSearchAjax", 'web\services\WorkorderController@workOrderSearchAjax');

//        edit work order
        Route::get("editWorkOrder/{id}", 'web\services\WorkorderController@editWorkOrder');


//        service pricing

        Route::post("deleteServicePricing", 'web\ServiceController@deleteServicePricing');
        Route::post("addServicePricing", 'web\ServiceController@addServicePricing');
        Route::get("showPricing", 'web\EquipmentController@showPricing');


//        show workorder items

        Route::get("getWorkOrderItems", 'web\services\WorkorderController@getWorkOrderItems');
        Route::get("workOrderDetails/{workOrderId}/{customerId}", 'web\services\WorkorderController@workOrderItemDetails');

        Route::get("changeTechnician", 'web\services\WorkorderController@changeTechnician');

        Route::post('orderrequests', ['middleware' => ['permission:orderrequests'], 'uses' => 'web\paymentmanagement\OrderController@index']);
        Route::get('orderrequests', ['middleware' => ['permission:orderrequests'], 'uses' => 'web\paymentmanagement\OrderController@index']);

        Route::post("orderrequestslistdata", 'web\paymentmanagement\OrderController@listData');
        Route::get("orderrequestview/{id}", 'web\paymentmanagement\OrderController@orderRequestDetail');
        Route::get("generateinvoice/{id}", 'web\paymentmanagement\InvoiceController@index');

        Route::post('payment', ['middleware' => ['permission:payment'], 'uses' => 'web\paymentmanagement\OrderController@payment']);
        Route::get('payment', ['middleware' => ['permission:payment'], 'uses' => 'web\paymentmanagement\OrderController@payment']);

        Route::post("paymentlist", 'web\paymentmanagement\OrderController@paymentlistData');

        Route::get("paymentview/{id}", 'web\paymentmanagement\OrderController@paymentDetail');
        Route::get("openinvoice/{id}", 'web\paymentmanagement\InvoiceController@openinvoice');
        Route::post("addPay", 'web\paymentmanagement\OrderController@addPay');

        Route::get("updatephoto", 'web\ManufacturerController@updatephoto');

//        Route::get("contactuslist", 'web\SupportController@ContactUsList');
//        Route::post("contactuslist", 'web\SupportController@ContactUsList');

        Route::post("modelsparesimageUpload", 'web\ModelController@modelsparesimageUpload');
        Route::post("modelaccessoryimageUpload", 'web\ModelController@modelaccessoryimageUpload');
        Route::post("modeltipimageUpload", 'web\ModelController@modeltipimageUpload');


        Route::get("customerorders", 'web\orders\OrderController@customerOrders');
        Route::post("customerorders", 'web\orders\OrderController@customerOrders');

        Route::post('customerorders', ['middleware' => ['permission:customerorders'], 'uses' => 'web\orders\OrderController@customerOrders']);
        Route::get('customerorders', ['middleware' => ['permission:customerorders'], 'uses' => 'web\orders\OrderController@customerOrders']);

        Route::post("customerorderslistdata", 'web\orders\OrderController@listData');
        Route::get("customerOrderViewDetails/{orderId}/{customerId}", 'web\orders\OrderController@customerOrderViewDetails');
        Route::get("customerpartorders", 'web\orders\OrderController@customerpartOrders');
        Route::post("customerpartorders", 'web\orders\OrderController@customerpartOrders');
        Route::get("customerpartOrderViewDetails/{orderId}/{customerId}", 'web\orders\OrderController@customerpartOrderViewDetails');

        Route::get("customeraccessoryorders", 'web\orders\OrderController@customerAccessoryOrders');
        Route::get("customerAccOrderViewDetails/{orderId}/{customerId}", 'web\orders\OrderController@customerAccOrderViewDetails');


        Route::get("generateorderinvoice/{id}", 'web\orders\InvoiceController@index');

        Route::post('buyservice', ['middleware' => ['permission:buyservice'], 'uses' => 'web\services\ServicesController@index']);
        Route::get('buyservice', ['middleware' => ['permission:buyservice'], 'uses' => 'web\services\ServicesController@index']);

        Route::post("buyservicelistdata", 'web\services\ServicesController@listData');
        Route::get("serviceReqDetails/buyservice", 'web\services\ServicesController@index');
        Route::get("serviceReqDetails/{id}", 'web\services\ServicesController@buyServiceDetails');
        Route::post("deletedModelParts", 'web\ModelController@deletedModelParts');

        Route::get("orderinvoice/{id}", 'web\orders\InvoiceController@openinvoice');
        Route::post("deletedModelAccessory", 'web\ModelController@deletedModelAccessory');
        Route::post("deletedModelTips", 'web\ModelController@deletedModelTips');
        Route::get("assignTechnicianforModel", 'web\technician\TechnicianController@assignTechnicianforModel');
        Route::get("checkTechnicianforDevice", 'web\technician\TechnicianController@checkTechnicianforDevice');

        Route::post("pricingValue", 'web\services\ServicesController@pricingValue');
        Route::post("addToPortal", 'web\services\ServicesController@addToPortal');
        Route::post("editProfile/{id}", 'RegisterController@editProfile');
        Route::get("editProfile/{id}", 'RegisterController@editProfile');
        Route::get("updatesignature", 'RegisterController@updatesignature');
        Route::get("updatephoto", 'RegisterController@updatephoto');


        Route::post('qualitycheck', ['middleware' => ['permission:qualitycheck'], 'uses' => 'web\calibration\QualityController@qualityCheck']);
        Route::get('qualitycheck', ['middleware' => ['permission:qualitycheck'], 'uses' => 'web\calibration\QualityController@qualityCheck']);

        Route::post("qualitychecklistdata", 'web\calibration\QualityController@listData');
        Route::post("qcsublists", 'web\calibration\QualityController@qcsublists');
        Route::get("reviewWorkOrder/{id}", 'web\calibration\QualityController@adminReview');

        Route::get("getqualityWorkOrderItems", 'web\calibration\QualityController@getqualityWorkOrderItems');
        Route::get("qcReview/{id}", 'web\calibration\QualityController@qcReview');
        Route::post("checkWorkOrderProcess", 'web\services\WorkorderController@checkWorkOrderProcess');
        Route::get("qcConsolidateReview/{id}", 'web\calibration\QualityController@qcConsolidateReview');
        Route::get("qcItemReview/{id}", 'web\calibration\QualityController@qcItemReview');
//        Route::get("qcIndividualReview/{id}", 'web\calibration\QualityController@qcIndividualReview');
        Route::post("qcIndividualReview/{id}", 'web\calibration\QualityController@qcIndividualReview');

        Route::get("sendMail/{id}", 'web\calibration\QualityController@sendMail');
        Route::post("addmanu", 'web\ManufacturerController@addmanu');

        Route::post('userlistdata', 'web\UserController@listData');

        Route::get('userlist', ['middleware' => ['permission:userlist'], 'uses' => 'web\UserController@index']);


        Route::post('/adduser', ['middleware' => ['permission:adduser'], 'uses' => 'web\UserController@form']);
        Route::get('/adduser', ['middleware' => ['permission:adduser'], 'uses' => 'web\UserController@form']);

        Route::post("edituser/{id}", 'web\UserController@form');
        Route::get("edituser/{id}", 'web\UserController@form');
        Route::get('sitesettings', ['middleware' => ['permission:sitesettings'], 'uses' => 'web\SettingsController@sitesettings']);

        Route::get('deletemanufacturer/{id}', 'web\ManufacturerController@delete');
        Route::post('deletemanufacturer/{id}', 'web\ManufacturerController@delete');


        Route::get('deletebrand/{id}', 'web\BrandController@delete');
        Route::post('deletebrand/{id}', 'web\BrandController@delete');


        Route::get('deletemodel/{id}', 'web\ModelController@delete');
        Route::post('deletemodel/{id}', 'web\ModelController@delete');


        Route::get('deletedevice/{id}', 'web\DeviceController@delete');
        Route::post('deletedevice/{id}', 'web\DeviceController@delete');

        Route::get("deleteplan/{id}", 'web\ServiceController@delete');
        Route::post("deleteplan/{id}", 'web\ServiceController@delete');


        Route::post("/deletecustomer/{id}", 'web\CustomerController@delete');
        Route::get("/deletecustomer/{id}", 'web\CustomerController@delete');

        Route::post("/deleteInstrument/{id}", 'web\EquipmentController@delete');
        Route::get("/deleteInstrument/{id}", 'web\EquipmentController@delete');


        Route::post("/deleteWorkOrder/{id}", 'web\services\WorkorderController@delete');
        Route::get("/deleteWorkOrder/{id}", 'web\services\WorkorderController@delete');

        Route::post("/deleteTechnician/{id}", 'web\technician\TechnicianController@delete');
        Route::get("/deleteTechnician/{id}", 'web\technician\TechnicianController@delete');

        Route::post("deleteTechnician/{id}", 'web\technician\TechnicianController@delete');
        Route::get("permissionlist", 'web\developersettings\PermissionController@index');
        Route::post("permissionlistdata", 'web\developersettings\PermissionController@listData');
        Route::get("editPermission/{id}", 'web\developersettings\PermissionController@form');
        Route::get("addpermission", 'web\developersettings\PermissionController@form');
        Route::post("addpermission", 'web\developersettings\PermissionController@form');
        Route::post("editPermission/{id}", 'web\developersettings\PermissionController@form');

        Route::get("menulist", 'web\developersettings\MenuController@index');
        Route::post("menulistdata", 'web\developersettings\MenuController@listData');
        Route::get("editMenu/{id}", 'web\developersettings\MenuController@form');
        Route::get("addmenu", 'web\developersettings\MenuController@form');
        Route::post("addmenu", 'web\developersettings\MenuController@form');
        Route::post("editMenu/{id}", 'web\developersettings\MenuController@form');

        Route::get("permissionsettings", 'web\PermissionsettingsContoller@index');
        Route::post("permissionsettings", 'web\PermissionsettingsContoller@listData');
        Route::get("editPermissionSettings/{id}", 'web\PermissionsettingsContoller@editPermissionSettings');
        Route::post("updatePermissionSettings", 'web\PermissionsettingsContoller@updatePermissionSettings');
        Route::post("deleteworkorderiem", 'web\services\WorkorderController@deleteworkorderiem');
        Route::post("requestItemsPlanEdit", 'web\services\ServiceRequestController@requestItemsPlanEdit');

//ContactUs
        Route::get('contactus', ['middleware' => ['permission:contactus'], 'uses' => 'web\ContactUsController@index']);
        Route::post("contactuslist", 'web\ContactUsController@listData');
        Route::get("contactuslist", 'web\ContactUsController@listData');
        Route::post("replycontactus", 'web\ContactUsController@replycontactus');
        Route::get("deletecontact/{id}", 'web\ContactUsController@deleteContact');


        Route::get("getCustomerInfo", 'web\CustomerController@getCustomerInfo');
        Route::post("workOrderItems", 'web\calibration\QualityController@workOrderItems');
        Route::post("chosenQualityCheck", 'web\calibration\QualityController@chosenQualityCheck');
        Route::post("getWorkOrderItemsforsendMail", 'web\calibration\QualityController@getWorkOrderItemsforsendMail');
        Route::post("sendReportToCustomer", 'web\calibration\QualityController@sendReportToCustomer');
        Route::get("serviceType", 'web\ServiceplantypeController@index');
        Route::post("serviceplantypelistdata", 'web\ServiceplantypeController@listData');
        Route::get("editserviceplantype/{id}", 'web\ServiceplantypeController@form');
        Route::post("editserviceplantype/{id}", 'web\ServiceplantypeController@form');
        Route::post('/addserviceplantype', ['middleware' => ['permission:addserviceplantype'], 'uses' => 'web\ServiceplantypeController@form']);
        Route::post("addwebsettings", 'web\SettingsController@form');

        Route::get("statelist", 'web\StateController@index');
        Route::get("addState", 'web\StateController@form');
        Route::post("saveState", 'web\StateController@saveState');
        Route::post("statelistdata", 'web\StateController@listData');
        Route::get("editState/{id}", 'web\StateController@form');
        Route::put("updateState/{id}", 'web\StateController@saveState');
        Route::get('deletestate/{id}', 'web\StateController@delete');



        Route::get("citylist", 'web\CityController@index');
        Route::get("addCity", 'web\CityController@form');
        Route::post("saveCity", 'web\CityController@saveCity');
        Route::post("citylistdata", 'web\CityController@listData');
        Route::get("editCity/{id}", 'web\CityController@form');
        Route::put("updateCity/{id}", 'web\CityController@saveCity');
        Route::get('deletecity/{id}', 'web\CityController@delete');

        Route::post('getcity', 'web\ManufacturerController@getCity');
        Route::post('getBrands', 'web\ModelController@getBrands');

        Route::get("testpointlist", 'web\TestpointController@index');
        Route::get("addtestpoint", 'web\TestpointController@form');
        Route::post("savetestpoint", 'web\TestpointController@savetestpoint');
        Route::post("testpointlistdata", 'web\TestpointController@listData');
        Route::get("edittestpoint/{id}", 'web\TestpointController@form');
        Route::put("updatetestpoint/{id}", 'web\TestpointController@savetestpoint');
        Route::get('deletetestpoint/{id}', 'web\TestpointController@delete');
        Route::post("getUserContact", 'web\EquipmentController@getUserContact');
        Route::get("uploadPage", 'web\EquipmentController@uploadPage');
        Route::post("import_parse", 'web\EquipmentController@parseImport');
        Route::post("import_process", 'web\EquipmentController@parseImport');
        Route::get("downloadSampleCsv", 'web\EquipmentController@downloadSampleCsv');

        //Upload Instrument Model
        Route::get("instrumentModelUploadPage",'web\ModelController@instrumentModelUploadPage');
        Route::post("importfile", 'web\ModelController@fileImport');
        Route::get("downloadInstrumentSampleCsv", 'web\ModelController@downloadInstrumentSampleCsv');

        Route::get("uploadPageSeperate", 'web\EquipmentController@uploadPageSeperate');
        Route::get("downloadSampleCsvWithoutDescription", 'web\EquipmentController@downloadSampleCsvWithoutDescription');
        Route::post("import_parse_withoutdescription", 'web\EquipmentController@import_parse_withoutdescription');


//Operation
        Route::post('operationlist', ['middleware' => ['permission:operationlist'], 'uses' => 'web\OperationController@index']);
        Route::get('operationlist', ['middleware' => ['permission:operationlist'], 'uses' => 'web\OperationController@index']);
        Route::post("operationlistdata", 'web\OperationController@listData');
        Route::get("addoperation", 'web\OperationController@form');
        Route::post("addoperation", 'web\OperationController@form');
        Route::post("editoperation/{id}", 'web\OperationController@form');
        Route::get("editoperation/{id}", 'web\OperationController@form');

        Route::get('deleteoperation/{id}', 'web\OperationController@delete');
        Route::post('deleteoperation/{id}', 'web\OperationController@delete');


//Pipette Type
        Route::post('pipettetypelist', ['middleware' => ['permission:pipettetypelist'], 'uses' => 'web\PipetteTypeController@index']);
        Route::get('pipettetypelist', ['middleware' => ['permission:pipettetypelist'], 'uses' => 'web\PipetteTypeController@index']);
        Route::post("pipettetypelistdata", 'web\PipetteTypeController@listData');
        Route::get("addpipettetype", 'web\PipetteTypeController@form');
        Route::post("addpipettetype", 'web\PipetteTypeController@form');
        Route::post("editpipettetype/{id}", 'web\PipetteTypeController@form');
        Route::get("editpipettetype/{id}", 'web\PipetteTypeController@form');

        Route::get('deletepipettetype/{id}', 'web\PipetteTypeController@delete');
        Route::post('deletepipettetype/{id}', 'web\PipetteTypeController@delete');

//Customer Specifications
        Route::post('customerspecificationlist', ['middleware' => ['permission:customerspecificationlist'], 'uses' => 'web\CustomerSpecificationController@index']);
        Route::get('customerspecificationlist', ['middleware' => ['permission:customerspecificationlist'], 'uses' => 'web\CustomerSpecificationController@index']);

        Route::post("customerspecifictionlistdata", 'web\CustomerSpecificationController@listData');
        Route::post("calspeceficationlistdata", 'web\CustomerSpecificationController@callistData');
        Route::post("customersublists", 'web\CustomerSpecificationController@customersublists');
        Route::get("customerspecification", 'web\CustomerSpecificationController@form');
        Route::post("customerspecification", 'web\CustomerSpecificationController@createCustomerSpecification');
        Route::get("editcustomerspecification/{id}", 'web\CustomerSpecificationController@form');
        Route::post("editcustomerspecification/{id}", 'web\CustomerSpecificationController@form');
        Route::get("getCalSpecInfo", 'web\CustomerSpecificationController@getCalSpecInfo');
        Route::get("editCalSpecInfo", 'web\CustomerSpecificationController@getCalSpecInfo');
        Route::post("checkcustomertolerancecombination", 'web\CustomerSpecificationController@checkcustomertolerancecombination');

        //checkDuplicateModels
        Route::post("checkModelCombination", 'web\ModelController@checkModelCombination');

        //check Manufacturer serialnumber
        Route::post("checkSerialNumber", 'web\DeviceController@checkSerialNumber');

        //Outside report upload
        Route::post("uploadCaliDocument", 'web\calibration\QualityController@uploadCaliDocument');
        Route::post("uploadCaliDocumentAll", 'web\calibration\QualityController@uploadCaliDocumentAll');

        //check Manufacturer serialnumber
        Route::post("checkSerialNumber", 'web\DeviceController@checkSerialNumber');

        //master setup Shipping charge
        Route::post('shippinglist', ['middleware' => ['permission:shippinglist'], 'uses' => 'web\ShippingController@index']);
        Route::get('shippinglist', ['middleware' => ['permission:shippinglist'], 'uses' => 'web\ShippingController@index']);

        Route::post("shippinglistdata", 'web\ShippingController@listData');
//
        Route::get("addshipping", 'web\ShippingController@form');
        Route::post("addshipping", 'web\ShippingController@form');
        Route::post("editshipping/{id}", 'web\ShippingController@form');
        Route::get("editshipping/{id}", 'web\ShippingController@form');
        Route::get("deleteshipping/{id}", 'web\ShippingController@delete');
        Route::post("deleteshipping/{id}", 'web\ShippingController@delete');


        //download customer PO:
        Route::get('downloadCustomerPO/{ordernumber}','web\paymentmanagement\InvoiceController@downloadCustomerPO');

        //Save discount price:
        Route::post('savediscountprice','web\services\ServiceRequestController@saveDiscountPrice');





        //Admin verify Email
        Route::get("emailverify",'web\CustomerController@VerifyEmail');
        Route::post('portalAccess','web\CustomerController@portalAccess');

        Route::get('/clear-cache', function () {
            $exitCode = Artisan::call('cache:clear');
            // return what you want
            echo 'cleared';
        });


    });
});
