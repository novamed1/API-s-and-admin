@extends('layout.header')
@section('content')
    <div class="am-content">
        <div class="page-head">

            <h2>Customer Model Orders</h2>
            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Customer Setup</a></li>
                <li><a href="#">Customer Orders</a></li>

            </ol>


            <div class="text-right div-rul">
                <a href="#" class="btn btn-space"></a>

            </div>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">


                        <form action="#" id='trucktype'>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">

                                        <input type="text" id="keywordsearch" name="keyword" placeholder="Enter Keyword"
                                               value='{{isset($keyword) ? $keyword : ''}}' class="form-control"
                                               style="margin:  auto;margin-top: 6px;">
                                    </div>

                                </div>
                            <div class="col-md-3">
                                <div class="form-group input-group date datetimepicker">
                                    <input type="text" name="startdate" value="{!! isset($startdate)?$startdate:'' !!}"
                                           class="form-control" id="startdate" placeholder="Start Date">
                                    <span class="input-group-addon btn btn-primary"><i class="icon-th s7-date"></i></span>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="form-group input-group date datetimepicker">
                                    <input type="text" name="enddate" value="{!! isset($enddate)?$enddate:'' !!}"
                                           class="form-control" id="enddate" placeholder="End Date">
                                    <span class="input-group-addon btn btn-primary"><i class="icon-th s7-date"></i></span>
                                </div>


                            </div>


                            <div class="col-md-3" style="margin:auto; margin-top: 8px;">
                                <input type="submit" id="searchbtn" value="Search"
                                       class="sms-submit-btn apply-btn btn btn-alt1">
                                <a href="{{url('admin/customerpartorders')}}" class="btn btn-default">Cancel</a>


                            </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="widget widget-fullwidth widget-small">

                                <div class="flash-message">
                                    @include('notification/notification')
                                </div>
                                <div class="col-md-9 col-sm-9" id="Result" style='display:none;text-align: center;'>

                                    <span>No result found</span>
                                </div>
                                {{--@if($data)--}}

                                <div class="sms-table-list" id="pageResult">

                                    <div class="table-responsive noSwipe">
                                        <table class="table table-striped table-fw-widget table-hover">

                                            <thead>
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Customer Name</th>
                                                {{--<th>Total Products</th>--}}
                                                <th>Total Price (<i class="fa fa-inr" aria-hidden="true"></i>)</th>

                                                <th>Order Date</th>
                                                <th>Order Status</th>
                                                <th>View Details</th>

                                            </tr>
                                            </thead>


                                            <tbody>
                                            @if($data)
                                                @foreach($data as $row)
                                                    <tr class="gradeA">

                                                        <td>{{$row['orderNumber']}}</td>

                                                        <td>{{$row['customerName']}}</td>
                                                         <td><i class="fa fa-inr" aria-hidden="true"></i>
                                                            {{$row['totalCost']}}</td>

                                                        <td>
                                                            {{Carbon\Carbon::parse($row['orderDate'])->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('j-M-Y h:i A')}}

                                                        </td>
                                                        <td>
                                                            @if($row['paymentStatus'] = 1)

                                                                <span class="label label-success">Paid</span>
                                                            @endif
                                                        </td>

                                                        <td><a href="{{url('admin/customerpartOrderViewDetails/'.$row['orderId'].'/'.$row['customerId'])}}"
                                                               class="btn btn-space btn-primary"
                                                               id="viewDetails"><i class=''
                                                                                   aria-hidden="true">View
                                                                    Detail</i></a></td>


                                                    </tr>
                                                @endforeach

                                            @endif


                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">

                                <div class="panel-body">
                                    <div class="text-right">
                                        {{$data->links()}}

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
    <script>
        $(function () {
            $("#startdate").datepicker({maxDate: new Date()});
        });
        $(function () {
            $("#enddate").datepicker({maxDate: new Date()});
        });
    </script>
    <script type="text/javascript">
        $('body').on('click', '#referredlimit', function () {

            var id = $(this).attr('rel');

            $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "{{url("admin/referredmodelList")}}",
                dataType: "JSON",
                success: function (json) {

                    if (json.result) {
                        var template = jQuery("#referredModelDetails").html();
                        var test = $('#modelBody-' + id).html(_.template(template, {data: json.referredDetails}));
//console.log(test)
                    } else {
                        alert('Something went wrong');
                    }
                }
            });


        });

    </script>
    <script type="text/html" id="referredModelDetails">
        <%
        _.each(data, function(referredDetails , index) { %>
        <tr id="<%= referredDetails['Id'] %>" class="product-list index">
        

        <td class="hidden-phone">
            <div class="col-sm-16"><%=referredDetails['target_value']%>
                <input type="hidden" name="referredDetails[<%=referredDetails['Id']%>][target_value]" value="<%=referredDetails['target_value']%>" id="foodPrice-<%=referredDetails['Id']%>"/>
            </div>
        </td>
         <td class="hidde-phone"><%=referredDetails['description']%>
            <div class="col-sm-16">

                <input type="hidden" name="referredDetails[<%=referredDetails['Id']%>][description]" value="<%=referredDetails['description']%>" id="foodname-<%=referredDetails['Id']%>"/>

            </div>
        </td>
         <td class="hidde-phone"><%=referredDetails['accuracy']%>
            <div class="col-sm-16">

                <input type="hidden" name="referredDetails[<%=referredDetails['Id']%>][accuracy]" value="<%=referredDetails['accuracy']%>" id="foodname-<%=referredDetails['Id']%>"/>

            </div>
        </td>
        <td class="hidden-phone">
            <div class="col-sm-16"><%= referredDetails['precision'] %>
                <input type="hidden" name="referredDetails[<%=referredDetails['Id']%>][precision]" value="<%=referredDetails['precision']%>" id="vendorPrice-<%=referredDetails['Id']%>"/>
            </div>
        </td>

    </tr>
    <%
    {{--i++;--}}
    }); %>
</script>

@stop