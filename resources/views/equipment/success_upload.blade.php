@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Uploaded Instrument Summary</h2><h4>Total Instruments - {{count($total)}}, Uploaded - {{count($sData)}}, Not Uploaded - {{count($data)}}</h4>

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
                                        @if(count($data))
                                        <table class="table table-bordere table-striped display" id="listTable">

                                            <thead>
                                            <tr>
                                                <th>Asset No</th>
                                                <th>Serial No</th>
                                                <th>Model</th>
                                                <th>Contact</th>
                                                <th>Location</th>
                                                <th>Service Plan</th>
                                                <th>Reason</th>

                                            </tr>
                                            </thead>

                                               @foreach($data as $key=>$row)
                                                   <tr>
                                                       <td>{{$row['asset_no']}}</td>
                                                       <td>{{$row['serial_no']}}</td>
                                                       <td>{{$row['model']}}</td>
                                                       <td>{{$row['contact']}}</td>
                                                       <td>{{$row['location']}}</td>
                                                       <td>{{$row['service_plan']}}</td>
                                                       <td style="color: red">{{$row['failure_reason']}}</td>
                                                   </tr>
                                                   @endforeach


                                        </table>
                                            @else
                                            All Instruments are uploaded
                                        @endif

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
