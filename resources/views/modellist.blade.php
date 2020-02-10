@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Equipment Model list </h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Equipment management</a></li>
                <li><a href="#">Equipment model</a></li>

            </ol>
            @if($postvalue)
                @if($posttestplanid)
                    <?php $msg = 'Model has been updated'; ?>
                @else
                    <?php $msg = 'Model has been created'; ?>
                @endif
                <div role="alert" class="alert alert-success alert-dismissible">
                    <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"
                                                                                                      class="s7-close"></span>
                    </button>
                    <span class="icon s7-check"></span>{{ $msg }}
                </div>
            @endif

            <div class="text-right">
                <a href="{{url('admin/model')}}" class="btn btn-space btn-primary">Create Model</a>
            </div>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">


                        <form action="{{url('admin/modellist')}}" method="post">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <div class="col-md-9">
                                <div class="form-group">

                                    <input type="text" id="keywordsearch" name="keyword" placeholder="Enter Keyword "
                                           class="form-control" value="{!! $keyword !!}"
                                           style="margin:  auto;margin-top: 6px;">
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
                                            <table class="table table-striped table-fw-widget">

                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    {{--<th>capacity</th>--}}
                                                    <th>Channel</th>


                                                    <th>Action</th>

                                                </tr>
                                                </thead>

                                                @foreach($data as $value)
                                                    <tbody>

                                                    <tr class="">

                                                        <td>{{$value->model_name}}</td>

                                                        {{--<td>{{$value->capacity}}</td>--}}
                                                        <td>{{$value->channel}}</td>


                                                        <td>
                                                            {{--<span class="glyphicon glyphicon-pencil"></span>--}}
                                                            <a href="{{url('admin/editmodel/'.$value->id)}}" class=""><i
                                                                        class="icon s7-pen"></i></a>

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
