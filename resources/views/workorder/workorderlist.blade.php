@extends('layout.header')
@section('content')
    <style>
        .div-rul a {
            margin-top: -40px;

        }

        #dt_basic tr th {
            font-size: 13px;
        }

        #dt_basic tr td {
            font-size: 11px;
        }
        .remove
        {
            color: #EF6262;
        }

        .remove:hover, .remove:focus {
             color: #EF6262;
        }
    </style>
    <div class="am-content">
        <div class="page-head">

            <h2> Work Orders</h2>

            <ol class="breadcrumb">

                <li><a href="{{url('admin/dashboard')}}">Home</a></li>
                <li><a href="#">Services & Workorders</a></li>
                <li><a href="#">Work Orders</a></li>

            </ol>


            {{--<div class="text-right div-rul">--}}

            {{--<a href="{{url('admin/addview')}}" class="btn btn-space btn-primary">Create Instrument</a>--}}
            {{--<a href="{{url('admin/customerExport/'.$customerId)}}" class="btn btn-space btn-primary">Export</a>--}}
            {{--<a href="#" class="btn btn-space btn-primary">Export</a>--}}


            {{--</div>--}}
        </div>
        <div class="main-content">
            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="col-sm-18">
                            <div class="widget widget-fullwidth widget-small">

                                <div class="flash-message">
                                    @include('notification/notification')
                                </div>


                                <div class="view-all-service-req">
                                    <div class="service-req-tbl" id="first-datatable-output">
                                        <table class="table table-bordere table-striped display" id="listTable">

                                            <thead>
                                            <tr>

                                                <th></th>
                                                <th>Customer</th>
                                                <th>Request#</th>
                                                <th>Work Order#</th>
                                                <th>Total Instruments</th>
                                                <th>Date Assigned</th>
                                                <th>Plan</th>
                                                <th>Maintanence To</th>
                                                <th>Calibrated To</th>
                                                <th>View Details</th>
                                                <th>Delete</th>


                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>

                                                <th></th>
                                                <th>Customer</th>
                                                <th>Request#</th>
                                                <th>Work Order#</th>
                                                <th>Total Instruments</th>
                                                <th>Date Assigned</th>
                                                <th>Plan</th>
                                                <th>Maintanence To</th>
                                                <th>Calibrated To</th>
                                                <th></th>
                                                <th></th>


                                            </tr>
                                            </tfoot>
                                        </table>


                                    </div>
                                </div>
                                <div class="panel panel-default">

                                    <div class="panel-body">
                                        <div class="text-right" id="paging-first-datatable">


                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="{{asset('js/jquery.js')}}"></script>
        <script src="{{asset('js/underscore/underscore.js')}}"></script>
        <link rel="stylesheet" type="text/css" href="{{asset('datatable/datatable.min.css')}}" media="screen">
        <!-- If you are using bootstrap: -->
        <link rel="stylesheet" type="text/css" href="{{asset('datatable/jquery.dataTables.min.css')}}" media="screen">


        <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
        <script type="text/javascript" src="{{asset('datatable/jquery.dataTables.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('datatable/datatable.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('datatable/datatable.jquery.min.js')}}"></script>

        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/jquery-confirm.css')}}">
        <script src="{{asset('js/jquery-confirm.js')}}"></script>
        <script>

            $(function ($) {
                $('body').on("click", '.delete', function () {
                    var deleteUrl = $(this).attr('data-src');
                    console.log(deleteUrl)
                    $.confirm({
                        title: "Delete confirmation",
                        text: "Do you want to delete this record ?",
                        confirm: function () {
                            window.location = deleteUrl
                        },
                        cancel: function () {
                        },
                        confirmButton: "Yes",
                        cancelButton: "No"
                    });
                });
            });</script>


        <script>
            function format(d) {
                console.log(d);
                return d;
            }


            $('#listTable tfoot th').each(function (index) {
                if (index != 0 && index != 2 && index != 9 && index != 6 && index != 10) {
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search ' + title + '" />');
                }
                if (index == 2) {
                    $(this).html('{!!Form::select("request",$requests,'',array('class'=>''))!!}');
                }
                if (index == 6) {
                    $(this).html('{!!Form::select("plans",$plans,'',array('class'=>''))!!}');
                }

            });

            var table = $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "{{url('admin/workorderlistdata')}}",
                "sServerMethod": "POST",
                "iDisplayLength": 10,
                "searching": true,
                "processing": true,
                "columns": [
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "aaData": 0,
                        "defaultContent": ''
                    },
                    {"aaData": "1"},
                    {"aaData": "2"},
                    {"aaData": "3"},
                    {"aaData": "4"},
                    {"aaData": "5"},
                    {"aaData": "6"},
                    {"aaData": "7"},
                    {"aaData": "8"},
                    {"aaData": "9"},
                    {"aaData": "10"}
                ],
                initComplete: function () {
                    var api = this.api();

                    // Apply the search
                    api.columns().every(function () {
                        var that = this;

                        $('input', this.footer()).on('keyup change', function () {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });

                        $('select', this.footer()).on('change', function () {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    });
                }

            });

            $('#listTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var id = $(this).find('span').attr('data-id');

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    var datastring = {id: id, "_token": "{!! csrf_token() !!}"};
                    $.ajax({
                        type: 'post',
                        url: "{{url("admin/workordersublists")}}",
                        data: datastring,
                        dataType: "json",
                        success: function (json) {
                            if (json) {
                                if (json.result) {
                                    row.child(format(json.data)).show();
                                    tr.addClass('shown');
                                }
                            }
                        }
                    });

                }
            });


        </script>


        <script>
            $('body').on('click', '.fa-plus-circle', function () {

                var thisattr = $(this).attr('data-attr');
                $(this).removeClass('fa-plus-circle');
                $(this).addClass('fa-minus-circle');

                $('.fa-minus-circle').each(function () {
                    if (thisattr == $(this).attr('data-attr')) {
                        $(this).removeClass('fa-plus-circle');
                        $(this).addClass('fa-minus-circle');
                    }
                    else {
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

            $(function ($) {
                $('body').on("click", '.remove', function () {
                    var workoderItemId = $(this).attr('item-id');
                    $.confirm({
                        title: "Delete confirmation",
                        text: "Do you want to delete this record ?",
                        confirm: function () {
                            var datastring = {workoderItemId:workoderItemId,"_token":"{!! csrf_token() !!}"};
                            $.ajax({
                               type:"POST",
                                url:"{{url('admin/deleteworkorderiem')}}",
                                data:datastring,
                                dataType:"JSON",
                                success:function(json){
                                  if(json.result)
                                  {
                                      var workOrderId = json.workOrderId;
                                      var totalItemsBeforeDelete = $('#'+workOrderId+'_count').text();
                                      var totalItemsAfterDelete = (parseInt(totalItemsBeforeDelete)-1);
                                      $('#'+workOrderId+'_count').text(totalItemsAfterDelete);
                                      $('.sub_list_'+workoderItemId).remove();
                                      toast();
                                  }
                                }
                            });
                        },
                        cancel: function () {
                        },
                        confirmButton: "Yes",
                        cancelButton: "No"
                    });
                });
            });

            function toast()
            {
                $.toast({
                    heading: 'Removed',
                    text: "Workorder item has been removed",
                    //position: 'top-left',
                    showHideTransition: 'slide',
                    icon: 'success',
                    loader: false
                });
            }
        </script>

        <script type="text/html" id="workOrderItemDetails">
            <%
            _.each(data, function(referredbymembers , index) { %>
            <tr id="<%= referredbymembers['request_item_id'] %>" class="product-list index">


        <td class="hidden-phone"><%=referredbymembers['assetNumber']%>

        </td>
        <td class="hidden-phone"><%= referredbymembers['serialNumber'] %>

        </td>
         <td class="hidden-phone"><%= referredbymembers['modelName'] %>

        </td>
        <td class="hidden-phone"><%= referredbymembers['location'] %>

        </td>
        <td class="hidden-phone"><%= referredbymembers['contact'] %>

        </td>
        <td class="hidden-phone"><%= referredbymembers['tel'] %>

        </td>

        <% if (referredbymembers['status'] == "completed") { %>
            <td class="hidden-phone"><span class="label label-success">completed</span>

        </td>
        <% } else { %>
        <td class="hidden-phone"><span class="label label-danger">pending</span>

        </td>
         <% }%>

    </tr>
    <%
    {{--i++;--}}
    }); %>
</script>
@stop

