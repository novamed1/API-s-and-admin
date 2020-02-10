@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Uploaded Instrument Model Summary</h2><h4>Total Instrument Model - {{count($total)}}, Uploaded - {{count($sData)}}</h4>

               {{--<div role="alert" class="alert alert-success alert-dismissible">--}}
                    {{--<button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true"--}}
                                                                                                      {{--class="s7-close"></span>--}}
                    {{--</button>--}}
                    {{--<span class="icon s7-check"></span>{{ '' }}--}}
                {{--</div>--}}

        </div>
        <div class="main-content">

            <div class="row">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="widget widget-fullwidth widget-small">

                                <div class="flash-message">
                                    @include('notification/notification')
                                </div>


                                <div class="view-all-service-req">

                                    <div class="service-req-tbl" id="first-datatable-output">
                                        <table class="table table-bordere table-striped display" id="listTable">

                                            <thead>
                                            <tr>
                                                <th>Model Name</th>
                                                <th>Manufacturer</th>
                                                <th>Brand</th>
                                                <th>Operation</th>
                                                <th>Channel Type</th>
                                                <th>Volume Type</th>
                                                <th>Volume From</th>
                                                <th>Volume To</th>
                                                <th>Price</th>

                                            </tr>
                                            </thead>
                                           @if(count($data))
                                               @foreach($data as $key=>$row)
                                                   <tr>
                                                       <td>{{$row['model_name']}}</td>
                                                       <td>{{$row['manufacturer']}}</td>
                                                       <td>{{$row['brand']}}</td>
                                                       <td>{{$row['operation']}}</td>
                                                       <td>{{$row['channel_type']}}</td>
                                                       <td>{{$row['volume_type']}}</td>
                                                       <td>{{$row['volume_from']}}</td>
                                                       <td>{{$row['volume_to']}}</td>
                                                       <td>{{$row['price']}}</td>
                                                       <td style="color: red">{{$row['failure_reason']}}</td>
                                                   </tr>
                                                   @endforeach
                                               @endif

                                        </table>

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
        </div>


        <script src="{{asset('js/jquery.js')}}"></script>

        <link rel="stylesheet" type="text/css" href="{{asset('datatable/datatable.min.css')}}" media="screen">
        <!-- If you are using bootstrap: -->
        <link rel="stylesheet" type="text/css" href="{{asset('datatable/jquery.dataTables.min.css')}}" media="screen">


        <!-- Add the following if you want to use the jQuery wrapper (you still need datatable.min.js): -->
        <script type="text/javascript" src="{{asset('datatable/jquery.dataTables.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('datatable/datatable.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('datatable/datatable.jquery.min.js')}}"></script>
        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/jquery-confirm.css')}}">
        <script src="{{asset('js/jquery-confirm.js')}}"></script>
@stop
