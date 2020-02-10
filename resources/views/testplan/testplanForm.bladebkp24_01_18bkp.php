@extends('layout.header')
@section('content')

    <style>
        .div-txt {
            width: 80px;
        }
        .toleranceRow
        {
            margin-left: 300px;
        }
        .closeBottom
        {
            margin-top: 10px;
        }
        .activebottom
        {
            margin-top: 42px;
        }
        .text-center
        {
            text-align: center;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Test plan Creation</h2>
            <ol class="breadcrumb">
                <li>Equipment Management</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">

                    <form role="form" id="testPlanForm" action="javascript:void(0);" method="post" data-parsley-validate="" novalidate="">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Test plan</h3>
                            </div>

                            <div class="panel-body">

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label class="col-sm-3 control-label">Name</label>
                                                {!!Form::text('testPlanName','', array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','parsley-trigger'=>"change",'required'=>'')) !!}
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Unit</label>
                                                {!!Form::select("testPlanUnit",$modelunits,'',array('class'=>'form-control','id'=>'unit','required'=>""))!!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-20">
                                            <div class="form-group">
                                                <label class="col-sm-3 control-label">Description</label>

                                                {!!Form::text('testPlanDescription','', array('class'=>'form-control','id'=>'description','required'=>'')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-7">
                                            <div class="am-checkbox">
                                                <input id="check2" type="checkbox" name="is_active" class="needsclick">
                                                <label for="check2" class="activebottom">is active</label>
                                            </div>
                                            <div class="text-inverse">

                                            </div>
                                        </div>
                                    </div>

                                </div>

                                    <div class="panel-body">
                                    <div class="row col-sm-offset-2">
                                        <div class="m-t-20">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Equipment model</label>
                                            <div class="col-sm-6">
                                                {!!Form::select("testPlanEquipmentModel",$equipmentModels,'',array('class'=>'form-control','id'=>'equipmentmodel','required'=>""))!!}
                                            </div>
                                        </div>

                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <a href="javascript:void(0)" class="loadTolerence btn btn-space btn-primary">Load</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                   <div id="getLimitTolerences">

                                   </div>
                                    </div>


                                <div class="panel panel-default">

                                    <div class="panel-body">

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-space btn-primary">Save</button>
                                            <button class="btn btn-space btn-default">Cancel</button>
                                        </div>

                                    </div>

                                </div>


                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <button data-modal="colored-warning" style="display:none;" class="btn btn-space btn-warning md-trigger colored-warning">Warning</button>
<div id="modelAlertBody">

</div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/underscore/underscore.js')}}"></script>
    <script>
        $(".numeric").keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 0 && (e.which < 8 || e.which > 57)) {

                return false;
            }
        });
        $('body').on('click', '.loadTolerence', function () {
             var modelId = $('#equipmentmodel').val();
             if(modelId)
             {
                 $.ajax({
                     headers: {
                         'X-CSRF-Token': "{{ csrf_token() }}"
                     },
                     type: "POST",
                     data: {modelId: modelId},
                     url: "{{url("admin/testplan/limittolerence")}}",
                     dataType: "JSON",
                     success: function (json) {
                         if (json.result) {
                           $('#getLimitTolerences').html(json.formData);
                         }
                     }
                 });
             }
        });


        $('body').on('click', '#addtolerance', function () {

            if ($("#traget").val() && $("#accuracy").val() && $("#precision").val()) {
                var target_value = $("#traget").val();

                var accuracy = $("#accuracy").val();

                var precision = $("#precision").val();
                var orderdetail = jQuery("#tolelarance").html();

                Id = $(".talerance-list").length;
                Id++;

                $('#toleranceBody').append(_.template(orderdetail, {
                    target_value: target_value,
                    accuracy: accuracy,
                    precision: precision,
                    Id: Id,

                }));

            } else {
                alert('Please enter the limits');
                return false;
            }

        });

        $('body').on('click', '.removeTolerence', function () {

            var limitTolerenceId = $(this).attr('data-attr');
            if(limitTolerenceId)
            {
                $('#limitTolerenceRow_'+limitTolerenceId).fadeOut("slow");
                setTimeout(function () {
                    $('#limitTolerenceRow_'+limitTolerenceId).remove();
                }, 1000);
            }

        });

        $('body').on('keyup', '.changeValue', function (event) {

            var defaultValue = $(this).attr('data-attr'); console.log(defaultValue);
            var value = $(this).val();
            var model = jQuery("#alertMessage").html();
            var msg = '';

            if(parseFloat(value) > parseFloat(defaultValue))
            {
                msg = 'Value should be lesser than the default value';
                    $('#modelAlertBody').append(_.template(model, {
                        msg:msg
                    }));

                 $('.colored-warning').trigger('click');
                 $(this).val(defaultValue);
            }
          });

        $("#testPlanForm").submit(function (event) {
            event.preventDefault();
            var data = $('#testPlanForm').serialize();
            $.ajax({
                headers: {
                    'X-CSRF-Token': "{{ csrf_token() }}"
                },
                type: "POST",
                data: data,
                url: "{{url("admin/testplan")}}",
                dataType: "JSON",
                success: function (json) {
                    if (json.result) {
                        window.location = "{{url("admin/testplan/lists")}}";
                    }
                }
            });

        });
    </script>

    <script type="text/html" id="tolelarance">
        <tr id="<%= Id %>" class="talerance-list index">


            <td class="hidde-phone"><lable>Target</lable>
       <input type='text' value='<%= target_value %>' class='div-txt'/>
       <label> Accuracy </label> 
       <input type='text' value='<%= accuracy %>' class='div-txt'/>
       <label>Precision </label>
          <input type='text' value= '<%= precision %>' class='div-txt' />
        
            <div class="col-sm-16">
                <input type="hidden" name="orderlimits[<%=Id%>][target_value]" id="foods-<%= Id %>" value='<%=target_value%>'/>
                <input type="hidden" name="orderlimits[<%=Id%>][accuracy]" id="foods-<%= Id %>" value='<%=accuracy%>'/>
                <input type="hidden" name="orderlimits[<%=Id%>][precision]" id="foods-<%= Id %>" value='<%=precision%>'/>
            </div>
          
            </td>

            </tr>
</script>

<script type="text/html" id="alertMessage">
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
                    <p><%=msg%></p>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>
</script>
@stop

