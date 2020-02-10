<?php $__env->startSection('content'); ?>
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap-slider.css')); ?>">
    <style>
        .modal-content { max-width: 1030px !important;}
        .location-list {
            border-top: 1px solid #FFFFFF !important;
        }

        .table > thead > tr > th {
            font-weight: bold;
        }

        .div-sec td {
            width: 18%;

        }

        .error {
            color: red;
        }

        .customerdesign {
            margin-top: 20px;
        }

        .customerSpan {
            display: inline-block;
            min-width: 149px;
            font-weight: bold;

        }

        .customerText {
            height: 20px;
            color: rgba(0, 0, 0, 0.6);
            font-size: 14px;
            border: 1px solid #fff;
        }
    </style>
    <div class="am-content">
        <div class="page-head">
            <h2>Customers Specification</h2>
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li><a href="#">Customer Management </a></li>
                <li class="active">Customers Specification</li>
            </ol>
        </div>
        <div class="main-content">
            
                

            

                
            
            <input type="hidden" name="id" value="<?php echo e($input['id']); ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        <?php echo $__env->make('notification/notification', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 style="font-weight:600;">Customer Information</h3>
                        </div>

                        <div class="cart-type">
                            <input type="hidden" name="CustomerId" id="Id" value="<?php echo e($input['id']); ?>">


                            <div class="form-group">

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20 customerdesign">


                                            <p class="form_text1"><span class="customerSpan">Name</span>:
                                                <input type="text"
                                                       value="<?php echo e(isset($input['customer_name']) ? $input['customer_name'] :''); ?>"
                                                       class="myforminput customerText"
                                                       id="customerName" readonly
                                                       name="customer_name"
                                                /></p>

                                        </div>

                                    </div>

                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20 customerdesign">

                                            <p class="form_text1"><span class="customerSpan">Type</span>:
                                                <input type="text" readonly
                                                       id="customerType"
                                                       value="<?php echo e(isset($input['customer_type']) ? $input['customer_type'] :''); ?>"
                                                       name="customer_type"
                                                       class="myforminput customerText"
                                                /></p>
                                        </div>

                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20 customerdesign">


                                            <p class="form_text1"><span class="customerSpan">EmailId</span>:
                                                <input
                                                        type="text" class="myforminput customerText"
                                                        value="<?php echo e(isset($input['customer_email']) ? $input['customer_email'] :''); ?>"
                                                        readonly
                                                        id="customerMail" name="customer_email"
                                                /></p>
                                        </div>

                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20 customerdesign">


                                            <p class="form_text1"><span
                                                        class="customerSpan">Tel#</span>:
                                                <input
                                                        type="text" id="customerTelephone" readonly
                                                        value="<?php echo e(isset($input['customer_telephone']) ? $input['customer_telephone'] :''); ?>"
                                                        name="customerTelephone" class="myforminput customerText"
                                                        parsley-type="email"
                                                /></p>


                                        </div>

                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20 customerdesign">


                                            <p class="form_text1"><span class="customerSpan">State</span>:
                                                <input type="text"
                                                       value="<?php echo e(isset($input['state']) ? $input['state'] :''); ?>"
                                                       id="customerState" readonly
                                                       name="state"
                                                       class="myforminput customerText"
                                                /></p>


                                        </div>

                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20 customerdesign">


                                            <p class="form_text1"><span class="customerSpan">City</span>:
                                                <input type="text"
                                                       value="<?php echo e(isset($input['city']) ? $input['city'] :''); ?>"
                                                       id="customerCity" readonly
                                                       name="city"
                                                       class="myforminput customerText"
                                                /></p>


                                        </div>

                                    </div>


                                </div>


                            </div>

                        </div>
                        
                        <a href='javascript:void(0);' data-id= "<?php echo e($input['id']); ?>" class='viewCalSpecificationdetails btn btn-primary pull-right'>Create New Specification</a>

                        <div class="panel-heading">
                            <h3 style="font-weight:600;">Cal Specification List</h3>
                        </div>
                        <div class="service-req-tbl" id="first-datatable-output">
                            <table class="table table-bordere table-striped display" id="listTable">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Channel</th>
                                    <th>Operation</th>
                                    <th>Volume</th>
                                    <th>Values</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                            </table>

                        </div>
                        <hr>
                        
                            
                                
                            

                            

                                
                                    
                                        
                                            

                                                
                                                

                                            

                                        
                                    
                                    
                                        
                                            
                                                
                                                

                                            

                                        
                                    
                                    
                                        
                                            
                                                
                                                

                                            

                                        
                                    
                                    
                                        
                                            
                                                

                                                
                                            

                                        
                                    

                                
                                

                                

                                

                                


                                

                                
                                    

                                        
                                        

                                    
                                    


                                        

                                            
                                            

                                        
                                    
                                        

                                            
                                            

                                        

                                    

                                    

                                        

                                        

                                    


                                


                            

                            

                                
                                    
                                        
                                        
                                            
                                                
                                                    
                                                        

                                                            
                                                                

                                                            

                                                            

                                                        


                                                        

                                                            

                                                                

                                                            

                                                        


                                                        
                                                            

                                                                

                                                            

                                                        

                                                        
                                                            

                                                                

                                                            

                                                        

                                                        
                                                            

                                                                

                                                            

                                                        

                                                        
                                                            

                                                                

                                                            
                                                        

                                                    
                                                
                                            
                                        

                                    


                                


                                

                                    

                                        
                                            
                                            
                                               
                                        

                                    

                                

                            

                        
                    
                </div>

            </div>


            <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
            <script src="<?php echo e(asset('js/jquery.validate.js')); ?>"></script>

            <script src="<?php echo e(asset('js/main.js')); ?>"></script>
            <script src="<?php echo e(asset('js/wizard.js')); ?>"></script>
            <script src="<?php echo e(asset('js/bootstrap-slider.js')); ?>"></script>
            <script src="<?php echo e(asset('js/underscore/underscore.js')); ?>"></script>
            <script src="<?php echo e(asset('css/lib/select2/js/select2.min.js')); ?>"></script>

            
            <script src="<?php echo e(asset('js/app-form-wizard.js')); ?>"></script>
                <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/datatable.min.css')); ?>" media="screen">
                <!-- If you are using bootstrap: -->
                <link rel="stylesheet" type="text/css" href="<?php echo e(asset('datatable/jquery.dataTables.min.css')); ?>" media="screen">




                <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
                <script type="text/javascript" src="<?php echo e(asset('datatable/jquery.dataTables.min.js')); ?>"></script>
                <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.min.js')); ?>"></script>
                <script type="text/javascript" src="<?php echo e(asset('datatable/datatable.jquery.min.js')); ?>"></script>
            

                <a data-modal="full-info" class="btn btn-space btn-info md-trigger popUp" style="display: none;"></a>
                <div id="full-info" class="modal-container modal-colored-header modal-effect-1">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                            <h3 class="modal-title">Customer Specification Form</h3>
                        </div>
                        <div id="pageAppend">
                            <div class="modal-body mainbody">

                            </div>
                        </div>
                        <div class="modal-footer">
                        </div>
                    </div>
                </div>
            <script>
                function format (d) {
                    console.log(d);
                    return d;
                }
                var ID= $('#Id').val();

                var table = $('#listTable').DataTable( {
                    "ajax": {
                        "url": "<?php echo e(url('admin/calspeceficationlistdata')); ?>",
                        "data": {
                            "id": ID
                        },
                    },
                    "bServerSide": true,
                    "sServerMethod": "POST",
                    "iDisplayLength":10,
                    // "searching": true,
                    "columns": [
                        {
                            "className":      'detail-control',
                            "orderable":      false,
                            "aaData":           0,
                            "defaultContent": ''
                        },
                        { "aaData": "1" },
                        { "aaData": "2" },
                        { "aaData": "3" },
                        { "aaData": "4" },
                        { "aaData": "5" }
                    ],
                    initComplete: function() {
                        var api = this.api();

                        // Apply the search
                        api.columns().every(function() {
                            var that = this;

                            $('input', this.footer()).on('keyup change', function() {
                                if (that.search() !== this.value) {
                                    that
                                        .search(this.value)
                                        .draw();
                                }
                            });
                        });
                    }

                } );

                $('#listTable tbody').on('click', 'td.detail-control', function () {
                    var tr = $(this).closest('tr');
                    console.log(tr);

                    var row = table.row( tr );
                    var id =  $(this).find('span').attr('data-id');

                    if ( row.child.isShown() ) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    }
                    else {
                        var datastring = {id:id,"_token": "<?php echo csrf_token(); ?>"};
                        $.ajax({
                            type: 'post',
                            url: "<?php echo e(url("admin/customersublists")); ?>",
                            data: datastring,
                            dataType: "json",
                            success: function (json) {
                                if (json) {
                                    if (json.result) {
                                        row.child( format(json.data) ).show();
                                        tr.addClass('shown');
                                    }

                                }
                            }
                        });

                    }
                } );

                $('body').on('click', '#createnewButton', function () {
                    $('.CreateNewSpecification').show();
                });

                $('body').on('click', '.removeeditprice', function (event) {
                    event.preventDefault()

                    var deleteId = $(this).attr('data-id');
                    var deleteIndex = $(this).attr('data-index');


                    $.ajax({
                        type: 'post',
                        url: "<?php echo e(url("admin/deleteServicePricing")); ?>",
                        data: {
                            "_token": "<?php echo csrf_token(); ?>",
                            Id: deleteId,
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data) {
                                if (data.result == false) {
                                    if (data.value == 1) {
                                        $('.deletePopUp').trigger('click');
                                        return false;
                                    }

                                } else {
                                    $('#price-' + deleteIndex).fadeOut("slow");

                                    setTimeout(function () {
                                        $('#price-' + deleteIndex).remove();
                                    }, 500);
                                }

                            }
                        }

                    });


                });

                $('#volumeFrom,#volumeToRange').bind('keyup paste', function () {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });

                //Decimal Number Validation

                $("#accuracy,.precision_numeric,.precesion_ul_numeric,.accuracy_ul_numeric").keydown(function (event) {
                    if (event.shiftKey == true) {
                        event.preventDefault();
                    }
                    if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {
                    } else {
                        event.preventDefault();
                        $.toast({
                            heading: 'Warning',
                            text: 'Accept only Numbers and Decimal Values',
                            //position: 'top-left',
                            showHideTransition: 'slide',
                            icon: 'error',

                            loader: false
                        });
                    }
                    if ($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                        event.preventDefault();
                });


                $('body').on('click', '.remove_price', function (event) {
                    event.preventDefault()
                    var deleteIndex = $(this).attr('data-index');
                    $('#price-' + deleteIndex).fadeOut("slow");

                    setTimeout(function () {
                        $('#price-' + deleteIndex).remove();
                    }, 500);
                });

                $('body').on('click', '.volumeChange', function (event) {
                    event.preventDefault();
                    var val = $(this).val();
                    if (val == 1) {
                        $('#volumeTo').show();
                    } else {
                        $('#volumeTo').hide();
                    }
                });

                $("form").submit(function (e) {
                    e.preventDefault();
                    var channel = $('#channels').val();
                    var operation = $('#operation').val();
                    var volume = $('#volume').val();
                    var id = "<?php echo e($input['id']); ?>";
                    var volumeFrom = $('#volumeFrom').val();
                    var volumeTo = $('#volumeToRange').val();
                    var volumeRange = '';
                    if (volumeFrom && volumeTo) {
                        volumeRange = volumeFrom + '-' + volumeTo;
                    }
                    $.ajax({
                        type: 'post',
                        url: "<?php echo e(url("admin/checktolerancecombination")); ?>",
                        data: {
                            channel: channel,
                            operation: operation,
                            volume: volume,
                            volumeRange: volumeRange,
                            id: id,
                            "_token": "<?php echo csrf_token(); ?>"
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data) {
                                if (data.result) {

                                    $.toast({
                                        heading: 'Warning',
                                        text: data.message,
                                        //position: 'top-left',
                                        showHideTransition: 'slide',
                                        icon: 'error',

                                        loader: false
                                    });
                                } else {
                                    $("form").submit();
                                }

                            }

                        }

                    });

                });
            </script>

            <script>

                $('body').on('change', '#channel', function () {
                    var checkchannels = $('#channel').val();
                    $.ajax({

                        type: "get",
                        url: "<?php echo e(url("admin/getchannelnumbers")); ?>",
                        data: {
                            "_token": "<?php echo csrf_token(); ?>",
                            channel_id: checkchannels,
                        },
                        dataType: "JSON",
                        success: function (json) {


                            if (json.result == true) {

//                        jQuery("#equipmentunderscore").html();
                                jQuery("#channel_number").html(json.getChannels);

                            }
                        }
                    });
                });

            </script>
                <script>
                    $('body').on('click', '.toleranceEdit', function () {
                        $(this).hide();
                        var id = $(this).attr('data-attr');
                        $('.edit_'+id).each(function(){
                            var content = $(this).html();
                            var name = $(this).attr('attr');
                            $(this).html('<input type="text" attr='+name+' id="tol_'+name+'_'+id+'" class="form-control text_value_'+id+'" value='+content+'>');
                        });

                        $('#savetol_'+id).show();
                    });

                    $('body').on('click', '.toleranceSave', function () {
                        var id = $(this).attr('data-attr');
                        var target = $('#tol_target_'+id).val();
                        var accuracy = $('#tol_accuracy_'+id).val();
                        var precision = $('#tol_precision_'+id).val();
                        var accuracy_ul = $('#tol_accuracy_ul_'+id).val();
                        var precesion_ul = $('#tol_precesion_ul_'+id).val();
                        if(accuracy || precision || accuracy_ul ||precesion_ul){
                            var decimalNumber =   /^\d{0,4}(\.\d{0,6})?$/;
                            if(!(accuracy.match(decimalNumber)) || !(precision).match(decimalNumber) || !(precesion_ul).match(decimalNumber) || !(accuracy_ul).match(decimalNumber))
                            {

                                $.toast({
                                    heading: 'Error',
                                    text: 'Accuracy & Precision Accept only Numbers & Decimal Numbers',
                                    showHideTransition: 'slide',
                                    icon: 'error',
                                    position:'top-right'
                                });
                                return false;
                            }
                        }
                        var datastring = {target:target,accuracy:accuracy,precision:precision,accuracy_ul:accuracy_ul,precesion_ul:precesion_ul,id:id,"_token": "<?php echo csrf_token(); ?>"};
                        $('#savetol_'+id).hide();
                        $('#spinner_'+id).show();
                        $.ajax({
                            type: 'post',
                            url: "<?php echo e(url("admin/saveajaxtolerance")); ?>",
                            data: datastring,
                            dataType: "json",
                            success: function (data) {
                                if (data) {
                                    if (data.result) {
                                        $('.edit_'+id).each(function(){
                                            var attr = $(this).attr('attr');
                                            var content = $('#tol_'+attr+'_'+id).val();
                                            $(this).html(content);
                                        });

                                        $('#spinner_'+id).hide();
                                        $('#edittol_'+id).show();
                                        if(data.updated)
                                        {
                                            $.toast({
                                                heading: 'Updated',
                                                text: data.message,
                                                //position: 'top-left',
                                                showHideTransition: 'slide',
                                                icon: 'success',

                                                loader: false
                                            });
                                        }

                                    }

                                }
                            }

                        });

                    });

                $('body').on('click', '.fa-plus-circle', function () {

                    var thisattr = $(this).attr('data-attr');
                    $(this).removeClass('fa-plus-circle');
                    $(this).addClass('fa-minus-circle');

                    $('.fa-minus-circle').each(function () {
                        if (thisattr == $(this).attr('data-attr')) {
                            $(this).removeClass('fa-plus-circle');
                            $(this).addClass('fa-minus-circle');
                        } else {
                            $(this).addClass('fa-plus-circle');
                            $(this).removeClass('fa-minus-circle');
                        }

                    });

                });

                $('body').on('click', '.fa-minus-circle', function () {
                    $(this).removeClass('fa-minus-circle');
                    $(this).addClass('fa-plus-circle');

                });

                $(document).ready(function () {
                    var $myGroup = $('#tagcollapse');
                    $myGroup.on('show.bs.collapse', '.collapse', function () {
                        $myGroup.find('.collapse.in').collapse('hide');
                    });
                });


            </script>
                <script>

                    $('body').on("click", '.viewCalSpecificationdetails', function () {
                        var customerId = $(this).attr('data-id');
                        console.log(customerId)
                        $.ajax({
                            type: 'get',
                            url: "<?php echo e(url("admin/getCalSpecInfo")); ?>",
                            data: {
                                "_token": "<?php echo csrf_token(); ?>",
                                customerId: customerId,
                                id: '',
                            },
                            dataType: "json",
                            success: function (res) {

                                if (res.result == true) {

                                    $('#pageAppend').find('.mainbody').html(res.formData);
                                    $('.popUp').trigger('click');
                                }
                            }
                        });
                    });

                    $('body').on("click", '.editCalSpecificationdetails', function () {
                        var calId = $(this).attr('data-id');
                        var customerId = $(this).attr('data-customerid');
                        console.log(calId);
                        $.ajax({
                            type: 'get',
                            url: "<?php echo e(url("admin/editCalSpecInfo")); ?>",
                            data: {
                                "_token": "<?php echo csrf_token(); ?>",
                                customerId:customerId,
                                id: calId,
                            },
                            dataType: "json",
                            success: function (res) {

                                if (res.result == true) {

                                    $('#pageAppend').find('.mainbody').html(res.formData);
                                    $('.popUp').trigger('click');
                                    // $('#full-info').data-modal('toggle');
                                }
                            }
                        });
                    });


                </script>

            <script type="text/html" id="priceunderscore">
                <tr class="testexist" volume-attr="<%= volumeText %>"
        operation-attr="<%= operationText %>"
        channel-attr="<%= channelText %>"
        channelnumber-attr="<%= channelNumberText %>"
        point-attr="<%= pointText %>"

         id="price-<%= index %>" class="spares-list  div-lits">
           <td style="" class='' attr='volume'><%= volumeText %>
                <input type="hidden" name="priceDetail[<%=index%>][Id]" id="price-[<%=index%>]" value=''/>
                <input type="hidden" name="priceDetail[<%=index%>][volume]" id="price-[<%=index%>]" value='<%=volumeValue%>'/>
            </td>


             <td style="" class='' attr='operation'><%=operationText %>
                <input type="hidden" name="priceDetail[<%=index%>][operation]" id="price-[<%=index%>]" value='<%=operationValue%>'/>

            </td>
              <td style="" class='' attr='channel'><%=channelText %>
                <input type="hidden" name="priceDetail[<%=index%>][channel]" id="price-[<%=index%>]" value='<%=channelValue%>'/>
            </td>
            <td style="" class='' attr='channelnumber'><%=channelNumberText %>
                <input type="hidden" name="priceDetail[<%=index%>][channelNumber]" id="price-[<%=index%>]" value='<%=channelNumberValue%>'/>
            </td>
            <td style="" class='' attr='points'><%=pointText %>
                <input type="hidden" name="priceDetail[<%=index%>][point]" id="price-[<%=index%>]" value='<%=pointValue%>'/>
            </td>
            <td style="" class='' attr='price'><%=priceValue %>
                <input type="hidden" name="priceDetail[<%=index%>][price]" id="price-[<%=index%>]" value='<%=priceValue%>'/>
            </td>
            <td style=""> <a href="javascript:void(0)"
              data-index="<%= index %>"
                    class = "remove_price"
                    data-original-title="Delete"><i class="s7-close td-cross"
                                                aria-hidden="true"></i></a></td>

        </tr>
    </script>


<div id="colored-warning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>All fields are required</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>

    <div id="exist-warning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>These combination already added</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>


<div id="colored-remove" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Sorry!</h4>
                    <p>You can't able to delete this pricing.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>