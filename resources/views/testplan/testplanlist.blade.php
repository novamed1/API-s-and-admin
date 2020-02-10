@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">
           
            <h2>Testplan list</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Equipment management</a></li>
                <li><a href="#">Test plan</a></li>

                <!--                    <li class="active"></li>-->

            </ol>
            @if($postvalue)
                @if($posttestplanid)
                    <?php $msg = 'Test plan hase been updated'; ?>
                    @else
                    <?php $msg = 'Test plan hase been saved'; ?>
                    @endif
            <div role="alert" class="alert alert-success alert-dismissible">
                <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="s7-close"></span></button><span class="icon s7-check"></span>{{ $msg }}
            </div>
            @endif

                <div class="text-right">
            <a href="{{url('admin/testplan')}}" class="btn btn-space btn-primary">Add Test Plan</a>
                </div>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">


                        <form  action="{{url('admin/testplanlists')}}" method="post">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <div class="col-md-9">
                                <div class="form-group">

                                    <input type="text" id="keywordsearch" name="keyword" placeholder="Enter Keyword " class="form-control" value="{!! $keyword !!}" style="margin:  auto;margin-top: 6px;">
                                </div>

                            </div>

                            <div class="col-md-3" style="margin:  auto; margin-top: 8px;">
                                <input type="submit" id="searchbtn" value="Search"
                                       class="btn btn-space btn-primary">



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
                                @if($data)

                                <div class="sms-table-list" id="pageResult">

                                    <div class="table-responsive noSwipe">
                                        <table class="table table-striped table-fw-widget table-hover">

                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th>Equipment model</th>

                                                <th>Action</th>

                                            </tr>
                                            </thead>

                                @foreach($data as $row)
                                            <tbody>
                                            
                                            <tr class="">

                                                <td >{{$row->name}}</td>
                                                <td>{{$row->description}}</td>
                                                <td>{{$row->model_name}}</td>

                                                
                                                <td>
                                                    <a href="{{url('admin/edittestplan/'.$row->id)}}"class=""><i class="icon s7-pen"></i></a>
                                                    
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
        </div>

    </div>
    </div>

    @stop


    
