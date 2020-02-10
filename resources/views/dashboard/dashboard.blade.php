@extends('layout.header')
@section('content')


    <style>
        .badge
        {
            font-size: 14px;
        }
.nano-snippet
{
    position : relative;
    width    : 49%;
    height   : 250px;
    overflow : hidden;
}
        .right
        {
            float: right;
        }

    </style>

<div class="am-content">
      <div class="main-content">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-6">
                        <div class="widget widget-tile">
                          <div class="data-info">
                            <div data-toggle="counter" data-end="96" class="value">{{$data['service_request_count']}}</div>
                            <div class="desc">Requests received</div>
                          </div>
                          <div class="icon"><span class="s7-bookmarks"></span></div>
                        </div>
              </div>
              <div class="col-md-6">
                        <div class="widget widget-tile">
                          <div class="data-info">
                            <div data-toggle="counter" data-end="233" class="value">{{$data['request_from_potal_count']}}</div>

                          </div>
                          <div class="icon"><span class="s7-compass"></span></div>
                            <div class="desc">Request Received From Customer Portal (New Customer)</div>
                        </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                        <div class="widget widget-tile">
                          <div class="data-info">
                            <div data-toggle="counter" data-end="156" class="value">{{($data['work_order_count'])}}</div>
                            <div class="desc">Workorders</div>
                          </div>
                          <div class="icon"><span class="s7-airplay"></span></div>
                        </div>
              </div>
              <div class="col-md-6">
                        <div class="widget widget-tile">
                          <div class="data-info">
                            <div data-toggle="counter" data-end="85" class="value">{{($data['completed_work_order_count'])}}</div>
                            <div class="desc">Completed Workorders</div>
                          </div>
                          <div class="icon"><span class="s7-news-paper"></span></div>
                        </div>
              </div>
            </div>

          </div>
            <div class="col-sm-6 nano nano-snippet">
                <div class="widget widget-fullwidth widget-small nano-content">
                    <div class="widget-head">
                        <div class="tools"></div>
                        <div class="title">Recent Requests</div>
                    </div>
                    <table class="table table-fw-widget">
                        <thead>
                        <tr>
                            <th width="20%">Request#</th>
                            <th width="20%">Customer</th>
                            <th>Date</th>
                            <th>Total Instruments</th>
                            <th>View</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($data['service_request'])
                            @foreach($data['service_request'] as $request_key=>$request_row)

                        <tr>
                            <td>{{$request_row->request_no}}</td>
                            <td>{{$request_row->customer_name}}</td>
                            <td>{{date('d-M-Y',strtotime(str_replace('-','/',$request_row->service_schedule_date)))}}</td>
                            <td>{{$request_row->totalItems}}</td>
                            <td><a href="{{url('admin/requestViewDetails/'.$request_row->id)}}"><span class="badge badge-danger"><i class="icon s7-info"></i></span></a></td>
                        </tr>
                        @endforeach
                            @endif


                        </tbody>
                    </table>

                </div>
            </div>

        </div>

          <div class="row">

              <div class="col-sm-12">
                  <div class="widget widget-fullwidth widget-small nano-content">
                      <div class="widget-head">
                          <div class="tools"></div>
                          <div class="title">New Requests From Customer Portal</div>
                      </div>
                      <table class="table table-fw-widget">
                          <thead>
                          <tr>
                              <th width="20%">Name</th>
                              <th width="20%">Customer Type</th>
                              <th width="10%">Tel#</th>
                              <th>Email</th>
                              <th width="10%">Total Instruments</th>
                              <th width="10%">Requested Date</th>
                              <th>Status</th>
                              <th>View</th>
                          </tr>
                          </thead>
                          <tbody>
                          @if($data['request_from_potal'])
                              @foreach($data['request_from_potal'] as $portalkey=>$portalrow)
                                  <tr>
                                      <td>{{$portalrow->customer_name}}</td>
                                      <td>{{$portalrow->name}}</td>
                                      <td>{{$portalrow->customer_telephone}}</td>
                                      <td>{{$portalrow->customer_email}}</td>
                                      <td>{{$portalrow->total_models}}</td>
                                      <td>{{date('m-d-Y',strtotime(str_replace('/','-',$portalrow->created_date)))}}</td>
                                      @if($portalrow->is_created==1)
                                          <?php $status_class = 'label-success'; $status = 'Approved'; ?>
                                      @else
                                          <?php $status_class = 'label-danger'; $status = 'Not Approved'; ?>
                                      @endif
                                      <td><span class="label {{$status_class}}">{{$status}}</span></td>
                                      <td><a href="{{url('admin/serviceReqDetails/'.$portalrow->id)}}"><span class="badge badge-danger"><i class="icon s7-info"></i></span></a></td>
                                  </tr>
                              @endforeach
                          @endif
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
        <div class="row">

            <div class="col-sm-6 nano nano-snippet">
                <div class="widget widget-fullwidth widget-small nano-content">
                    <div class="widget-head">
                        <div class="tools"></div>
                        <div class="title">Job Assigned</div>
                    </div>
                    <table class="table table-fw-widget">
                        <thead>
                        <tr>
                            <th width="20%">Request#</th>
                            <th width="20%">Job#</th>
                            <th>Technician</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($data['work_order'])
                            @foreach($data['work_order'] as $workorder_key=>$workorder_row)
                        <tr>
                            <td>{{$workorder_row->reqNumber}}</td>
                            <td>{{$workorder_row->workOrderNumber}}</td>
                            <td>{{$workorder_row->technicianName}}</td>
                            <td>
                                @if($workorder_row->work_progress!=1)
                                <a href="javascript:void(0)"class="workorderStarted"><i class="icon s7-pen"></i></a>
                                    @else
                                    <a href="{{url('admin/workOrderDetails/'.$workorder_row->workOrderId.'/'.$workorder_row->customerId.'')}}"class=""><i class="icon s7-pen"></i></a>
                                    @endif

                            </td>
                            <td>
                                <a href="#"class="" style="font-size: 15px;"><i class="icon s7-close"></i></a>

                            </td>
                        </tr>
                        @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-6 nano nano-snippet">
                <div class="widget widget-fullwidth widget-small nano-content">
                    <div class="widget-head">
                        <div class="tools"></div>
                        <div class="title">Service Summary</div>
                    </div>
                    <table class="table table-fw-widget">
                        <thead>
                        <tr>
                            <th width="10%">Request#</th>
                            <th width="10%">Asset#</th>
                            <th>Serial#</th>
                            <th>Plan</th>
                            <th>Next Due</th>
                            <th>Technician</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($data['service_summary'])
                            @foreach($data['service_summary'] as $summary_key=>$summary_row)
                        <tr>
                            <td>{{$summary_row->request_no}}</td>
                            <td>{{$summary_row->asset_no}}</td>
                            <td>{{$summary_row->serial_no}}</td>
                            <td>{{$summary_row->service_plan_name}}</td>
                            <td>{{$summary_row->next_due_date?date('d-M-Y',strtotime(str_replace('-','/',$summary_row->next_due_date))):'---'}}</td>
                            <td>{{$summary_row->first_name}}</td>
                            @if($summary_row->is_calibrated==1)
                                <?php $status_class = 'label-success'; $status = 'calibrated'; ?>
                            @else
                                <?php $status_class = 'label-danger'; $status = 'pending'; ?>
                            @endif
                            <td><span class="label {{$status_class}}">{{$status}}</span></td>
                        </tr>
                        @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>

        </div>

      </div>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script>
        $('body').on('click', '.workorderStarted', function () {
            $.toast({
                heading: 'Alert',
                text: 'This work order has been started by technician. You cannot edit',
                //position: 'top-left',
                showHideTransition: 'slide',
                icon: 'error',

                loader: false
            });
        });
    </script>

@stop