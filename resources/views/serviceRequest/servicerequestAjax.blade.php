<div class="sms-table-list" id="pageResult">

    <div class="table-responsive noSwipe">
        <table class="table table-striped table-fw-widget">

            <thead>
            <tr>
                <th></th>
                <th>Request Number</th>
                <th>Customer Name</th>
                <th>Service Schedule Date</th>
                <th>Created Date</th>

            </tr>
            </thead>

            @foreach($data as $value)
                <tbody>

                <tr data-toggle="collapse"
                    data-target="#reqDetail{{$value->id}}"
                    class="accordion-toggle hov">
                    <td>
                                                      <span class="lead_numbers">
                                                   <a href="javascript:void(0)" title="Service Request"
                                                      id="RequestItems"
                                                      rel="{{$value->id}}"><i
                                                               class="fa fa-plus-circle ordericon"
                                                               data-widget-collapsed="true"></i> <span
                                                               class="menu-item-parent"></span></a>
                                                      </span>
                    </td>

                    <td>{{$value->request_no}}</td>
                    <td>{{$value->customer_name}}</td>
                    <td>
                        {{Carbon\Carbon::parse($value->service_schedule_date)->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('j-M-Y')}}

                    </td>

                    <td>
                        {{--{{$value->created_date}}--}}
                        {{Carbon\Carbon::parse($value->created_date)->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('j-M-Y')}}

                    </td>


                </tr>
                <tr>
                    <td colspan="12" class="hiddenRow">
                        <div class="accordian-body collapse"
                             id="reqDetail{{$value->id}}">
                            <table id="dt_basic"
                                   class="table table-striped table-bordered table-hover"
                                   width="100%">
                                <thead>
                                <th>Equipment Name</th>
                                <th>Plan Name</th>
                                <th>Frequency</th>
                                <th>Service Price</th>
                                </thead>
                                <tbody class="requestItemBody-{{$value->id}}">
                                </tbody>
                            </table>

                        </div>
                    </td>
                </tr>


                </tbody>
            @endforeach


        </table>
        @endif
    </div>
</div>
