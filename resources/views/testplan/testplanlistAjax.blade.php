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
                                

                                <div class="sms-table-list" id="pageResult">
 @if($data)
                                    <div class="table-responsive noSwipe">
                                        <table class="table table-striped table-fw-widget table-hover">

                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                               
                                                <th>Action</th>

                                            </tr>
                                            </thead>

                                @foreach($data as $row)
                                            <tbody>
                                            
                                            <tr class="gradeA">

                                                <td >{{$row->name}}</td>

                                                <td>{{$row->description}}</td>
                                                <td>{{$row->unit}}</td>
                                                
                                                <td>
                                                    <a href="{{url('admin/edittestplan/'.$row->id)}}"class="btn btn-alt1"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    
                                                </td>
                                          
                                           
                                            </tbody>
                                              @endforeach


                                        </table>
                                        @endif
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
