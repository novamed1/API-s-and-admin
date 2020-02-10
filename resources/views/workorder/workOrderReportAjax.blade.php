<div class="sms-table-list" id="pageResult">

    <div class="table-responsive noSwipe">
        <table class="table table-striped table-fw-widget table-hover">

            <thead>
            <tr>

                <th>Work Order Name</th>
                <th>Plan Name</th>
                <th>As Found</th>
                <th>As Calibrate</th>
                <th>Request Number</th>
                <th>Maintanence To</th>
                <th>Calibrated To</th>
                <th>Work Order Date</th>
            </tr>
            </thead>
            @if($data)
                @foreach($data as $row)
                    <tbody>

                    <tr>

                        <td>{{$row['workOrderNumber']}}</td>
                        <td>{{$row['planName']}}</td>
                        <td>{{$row['workAsFound']}}</td>
                        <td>{{$row['workAsCalibrated']}}</td>
                        <td>{{$row['reqNumber']}}</td>
                        <td>{{$row['maintaainedBy']}}</td>
                        <td>{{$row['calibratedBy']}}</td>
                        <td>                                                           {{Carbon\Carbon::parse($row['workOrderDate'])->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('j-M-Y h:i A')}}
                        </td>

                        {{--<td>--}}
                        {{--<a href="{{url('admin/editWorkOrder/'.$row['workOrderId'])}}"--}}
                        {{--class=""><i--}}
                        {{--class="icon s7-pen"></i></a>--}}


                        {{--</td>--}}


                    </tr>
                    </tbody>
                @endforeach

        </table>
        @endif
    </div>
</div>
