<div class="sms-table-list" id="pageResult">

    <div class="table-responsive noSwipe">
        {{--<a href="{{url('admin/exportWorkOrderSearch')}}" id="exportWorkOrder" class="btn btn-primary" style="float: right;">Export</a>--}}
        {{--<a href="javascript:void(0)" id="exportWorkOrder" class="btn btn-primary" style="float: right;">Export</a>--}}

        <a id="exportWorkOrder" style="float: right;" class="btn btn-primary"  onclick="window.location.href = '{{url('admin/exportWorkOrderSearch')}}?keyword={{isset($search['keyword']) ? $search['keyword'] : ''}}&startdate={{isset($search['startdate']) ? $search['startdate'] : ''}}&enddate={{isset($search['enddate']) ? $search['enddate'] : ''}}&maintananceTo={{isset( $search['maintananceTo'])? $search['maintananceTo']:''}}&status={{isset($search['status']) ? $search['status'] : ''}}'">Export</a>

        <table class="table table-striped table-fw-widget table-hover">

            <thead>
            <tr>

                <th>Work Order Number</th>
                <th>Plan Name</th>
                <th>Request Number</th>
                <th>Maintanence To</th>
                <th>Calibrated To</th>
                <th>Status</th>
                <th>Work Order Date</th>
            </tr>
            </thead>
            @if($data)
                @foreach($data as $row)
                    <tbody>

                    <tr>

                        <td>{{$row['workOrderNumber']}}</td>
                        <td>{{$row['planName']}}</td>
                        <td>{{$row['reqNumber']}}</td>
                        <td>{{$row['maintaainedBy']}}</td>
                        <td>{{$row['calibratedBy']}}</td>
                        <td>
                            @if($row['status'] == '1')
                                <span class="label label-success"
                                style="background-color: orange;">As Found</span>

                            @elseif($row['status'] == 2)
                                <span class="label label-default labelCursor approve"
                                      style="background-color: #cf9bcf;">Maintenance</span>
                                @elseif($row['status'] == 3)
                                <span class="label label-default labelCursor approve"
                                     style="background-color: #89c389;" >Calibration</span>
                                @else
                                <span class="label label-default labelCursor approve"
                                      style="background-color: #67afcd;">Dispatched</span>

                            @endif
                        </td>
                        <td>




                            {{Carbon\Carbon::parse($row['workOrderDate'])->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('j-M-Y h:i A')}}
                        </td>
                    </tr>
                    </tbody>
                @endforeach

        </table>
        @endif
    </div>
</div>
