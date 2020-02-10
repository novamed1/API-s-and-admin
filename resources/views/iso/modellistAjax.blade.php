 <div class="col-sm-12">
                        <div class="widget widget-fullwidth widget-small">

                            <div class="flash-message">
                                @include('notification/notification')
                            </div>
                            <div class="col-md-9 col-sm-9" id="Result" style='display:none;text-align: center;'>

                                <span>No result found</span>
                            </div>
                            @if($data)

                            <div class="sms-table-list" id="pageResult">

                                <div class="table-responsive noSwipe">
                                    <table class="table table-striped table-fw-widget table-hover">

                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Name</th>
                                                <th>capacity</th>
                                                <th>Channel</th>
                                                <th>Unit</th>
                                                <th>Create Date</th>
                                                <th>Active</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>


                                        <tbody>
                                            @foreach($data as $value)
                                            <tr class="gradeA">
                                                <td>
                                                    <span class="order_number">
                                                        <a href="javascript:void(0)" title="Dashboard" id="referredlimit"
                                                           rel="{{$value->id}}" ><i
                                                                class="fa fa-plus-circle ordericon"
                                                                data-widget-collapsed="true"></i>+ <span
                                                                class="menu-item-parent"></span></a>

                                                    </span>
                                                </td>

                                                <td >{{$value->model_name}}</td>

                                                <td>{{$value->capacity}}</td>
                                                <td>{{$value->channel}}</td>
                                                <td>{{$value->unit}}</td>
                                                <td>{{date('d-m-Y', strtotime($value->created_date))}}</td>
                                                @if($value->is_active==1)
                                                <td><span class="label label-success">Active</span></td>
                                                @else
                                                <td><span class="label label-warning">InActive</span></td>
                                                @endif

                                                <td>
                                                   <a href="{{url('admin/editmodel/'.$value->id)}}"class="btn btn-alt1"><span class="glyphicon glyphicon-pencil"></span></a>
                                                </td>

                                            </tr>

                                            <tr>
  <td colspan="12" class="hiddenRow">
                                                    <div class="accordian-body collapse"
                                                         id="orderDetail{{$value->id}}">
                                                        <table id="dt_basic"
                                                               class="table table-striped table-bordered table-hover"
                                                               width="100%">
                                                            <thead>

                                                            <th>Description</th>
                                                            <th>Target</th>
                                                            <th>Accuracy</th>
                                                            <th>Precision</th>


                                                            </thead>
                                                            
                                                            <tbody id="referredBody-{{$value->id}}">

                                                        </tbody>
                                                        </table>

                                                    </div>
                                                </td>
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

