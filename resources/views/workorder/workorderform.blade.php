@extends('layout.header')
@section('content')

    <style>
        .error {
            color: red;
        }

        .cart-type {
            width: 98% !important;
        }
        .table > thead > tr > th {
            border-bottom-width: 1px;
            font-weight: bold;
            font-size: 13px;
        }
        td{
            border-bottom: thin solid #ddd;
        }
        .title-count
        {
            float: right;
        }


    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Workorder Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                {{--<li>Equipment Management</li>--}}
                <li>Create Work Order</li>

                {{--<li class="active"></li>--}}

            </ol>
            @if($postvalue)
                @if($posttestplanid)
                    <?php $msg = 'Work Order has been updated'; ?><div role="alert" class="alert alert-success alert-dismissible">
                        <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"
                                                                                                          class="s7-close"></span>
                        </button>
                        <span class="icon s7-check"></span>{{ $msg }}
                    </div>

                @endif

            @endif
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        @include('notification/notification')
                    </div>

                    <form role="form" id="workOrderForm" method="post" data-parsley-validate>
                        <div class="panel panel-default">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 style="font-weight: bolder;font-size: 16px;">Create new workorder</h3>
                                        </div>
                                        <div class="cart-type">
                                            <div class="panel-heading">
                                                <h3 style="font-weight: 500;">Create new workorder</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-6 col-xs-12">
                                                        <div class="m-t-18">
                                                            <div class="form-group">
                                                                <label>Choose customer</label>

                                                                {!!Form::select("customerId",$customer,$input['customerId'],array('class'=>'form-control','required'=>'','id'=>'customerId'))!!}
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-12">
                                                        <div class="m-t-20">

                                                            <div class="form-group">
                                                                <label>Choose request#</label>

                                                                {!!Form::select("requestNum",$requests,$input['requestNum'],array('class'=>'form-control','required'=>'','id'=>'requestId'))!!}
                                                            </div>


                                                        </div>


                                                    </div>


                                                </div>
                                            </div>

                                        </div>

                                        <div class="cart-type cart-type-items" style="display:none">
                                            <div class="row">
                                                <!--Responsive table-->
                                                <div class="col-sm-12">
                                                    <div class="widget widget-fullwidth widget-small">
                                                        <div class="widget-head">

                                                            <div class="title title-count">Total Selected Items (<span id="countNum">0</span>)</div>
                                                            <div class="title">Request Items</div>
                                                        </div>


                                                        <div class="table-responsive noSwipe">
                                                            <table class="table table-fw-widget table-hover">
                                                                <thead>
                                                                <tr>
                                                                    <th><div class="am-checkbox">
                                                                            <input id="check1" type="checkbox" class="needsclick selectAll">
                                                                            <label for="check1"></label>
                                                                        </div></th>
                                                                    <th width="">Instrument Info</th>
                                                                    <th width="25%">Calibration Info</th>
                                                                    <th width="10%">Comments</th>
                                                                    <th width="20%">Contact Person & Info</th>

                                                                </tr>
                                                                </thead>
                                                                <tbody class="no-border-x" id="itemsAppend">

                                                                @php($j=0)
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cart-type">
                                            <div class="panel-heading">
                                                <h3 style="font-weight: 500;">Technician Allocation</h3>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-sm-6 col-xs-12">
                                                        <div class="m-t-18">
                                                            <div class="form-group">
                                                                <label>Maintanence To</label>

                                                                {!!Form::select("maintanenceTo",$technician,$input['maintanenceTo'],array('class'=>'form-control','required'=>'required','id'=>'maintanenceTo'))!!}
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-xs-12">
                                                        <div class="m-t-20">

                                                            <div class="form-group">
                                                                <label>Calibration To</label>

                                                                {!!Form::select("calibrationTo",$technician,$input['calibrationTo'],array('class'=>'form-control','required'=>'required','id'=>'calibrationTo'))!!}
                                                            </div>


                                                        </div>


                                                    </div>


                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="panel-body">



                            </div>
                        </div>


                        <div class="panel panel-default" style='text-align: center;'>

                            <div class="panel-body">

                                <div class="text-center">
                                    <a href="javascript:void(0)" id="WorkOrderCreation"
                                       class="btn btn-space btn-primary">Submit</a>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>
    <div style="display:none;">
        <form action="{{url("admin/workordercreation")}}" method="post" id="formSubmission">
            <input type="text" value="1" name="postvalue">
            <input type="text" value="{!! $input['id'] !!}" name="customerSetUpId">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="submit" id="submitForm">
        </form>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/main.js')}}"></script>
    <script src="{{asset('js/underscore/underscore.js')}}"></script>
    <script src="{{asset('js/bootstrap-slider.js')}}"></script>

    <script src="{{asset('js/datetimepicker/js/bootstrap-datetimepicker.min.js')}}" type="text/javascript"></script>

    <script>

        $('body').on('click', '#WorkOrderCreation', function () {

            var data = $('#workOrderForm').serialize();


            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: data,
                url: "{{url("admin/workordercreation")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {
                        $('#submitForm').trigger('click');
                        //window.location = "{{url("admin/workordercreation")}}";
                    }
                }
            });
        });

    </script>



     <script>
        $('body').on('change', '#customerId', function () {
            $.ajax({
                type: 'post',
                url: "{{url("admin/getServiceRequestCustomer")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    customerId: $(this).val()
                },
                dataType: "json",
                success: function (res) {

                    if (res.result == true) {
                        $('#requestId').html(res.data);
                    }
                }
            });
        });

        var index = {!! $j + 1!!};

        $('body').on('change', '#requestId', function () {
            var temp = index;
            index = parseInt(index) + 1;
            $.ajax({
                type: 'post',
                url: "{{url("admin/getServiceRequestItems")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    requestNum: $(this).val()
                },
                dataType: "json",
                success: function (res) {

                    if (res.result) {
                        $(".selectAll").prop("checked",false);
                        $('#countNum').text(0);
                        $('.cart-type-items').show();
                        var tableSourceData = jQuery("#scriptItems").html();
                        $('#itemsAppend').html(_.template(tableSourceData, {data: res.data},{Id : index}));


                    }
                }
            });
        });
        $('body').on('click', '.selectCheck', function () {
            checkboxeach();
        });

        $('body').on('click', '.selectAll', function () {
            checkboxselectall();
        });

        function checkboxeach()
        {
            var i =0;
            var totalItems = $('.selectCheck').length;
            $('.selectCheck').each(function () {
               if(this.checked)
               {
                   i+=1;
               }
               else {
                   $(".selectAll").prop("checked",false);
               }
            });
            if(i==totalItems)
            {
                $(".selectAll").prop("checked",true);
            }
            $('#countNum').text(i);
        }

        function checkboxselectall()
        {
            var i =0;
            if($(".selectAll").prop('checked') == true){
                $('.selectCheck').each(function () {
                    $(this).prop( "checked", true );
                    i+=1;
                });
            }
            else
            {
                $('.selectCheck').each(function () {
                    $(this).prop( "checked", false);

                });
            }
            console.log(i);
            $('#countNum').text(i);
        }

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
                    <p>Please fill all the required fields</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>



    <script type="text/html" id="scriptItems">
        <%   _.each(data, function(ele , index) { %>
        <tr id="<%= index %>">
            <td>
                <div class="am-checkbox">
                    <input id="check<%= ele['request_item_id']%>" value="<%= ele['request_item_id']%>" type="checkbox" name="requestItemDetail[<%=index%>][reuestItemId]" class="needsclick selectCheck">
                    <label for="check<%= ele['request_item_id']%>"></label>
                </div>
            </td>
            <td><h4>S.N : <%= ele['serial_no']%> / As.N : <%= ele['asset_no']%></h4><h4><%= ele['model_name']%></h4></td>
            <td><p>
                    <span>Plan</span> : <span><%= ele['service_plan_name']%></span>
                </p>

                <p>
                    <span>Frequency</span> : <span><%= ele['frequency_name']%></span>
                </p>
                <p>
                    <span>Service Cost</span> : <span>$<%= ele['price']%></span>
                </p></td>
            <td><%= ele['comments']%></td>
            <td><ul><li><img src="{{asset('img/user-ico.png')}}">
                        <%= ele['pref_contact']%>
                    </li>
                    <li><img src="{{asset('img//mail-ico.png')}}">
                        <%= ele['pref_email']%>
                    </li>
                    <li><img src="{{asset('img/phone-ico.png')}}">
                        <%= ele['pref_tel']%>
                    </li></ul></td>

        </tr>
        <% }); %>
    </script>


@stop

