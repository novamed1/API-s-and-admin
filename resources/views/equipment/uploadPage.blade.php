@extends('layout.header')
@section('content')

    <div class="am-content gridLayout">
        <div class="page-head">

            <h2>Customer's Instrument Upload</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Instrument Management</a></li>
                <li><a href="#">Upload Instruments</a></li>

            </ol>
            <div class="text-right div-rul">
                <a href="{{url('admin/downloadSampleCsv')}}" class="btn btn-space btn-primary"><i class="fa fa-download"></i> Download Format</a>
            </div>

        </div>

        <div class="main-content">
            <div class="row">
                <div class="flash-message">
                    @include('notification/notification')
                </div>
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        {!! Form::open(array('url' => 'admin/import_parse', 'class' => 'form','method'=>'post','enctype'=>"multipart/form-data")) !!}
                        {{ csrf_field() }}
                    <div class="col-md-7">
                            <div class="col-md-5" style="float: right;margin-bottom: -15px;">
                                <label>Choose Customer <span style="color: red">*</span></label>
                                <div class="form-group">

                                    {!!Form::select("customer",$customer,'',array('class'=>'customerList form-control','attr'=>'due_status','required'))!!}
                                </div>
                                <label>Excel File <span style="color: red">*</span></label>
                                <div class="form-group">

                                    <input id="csv_file" type="file" class="form-control" name="csv_file" required>
                                    @if($message)
                                        <span style="color:red">{{$message}}</span>
                                        @endif
                                </div>

                                <div class="panel panel-default" style='text-align: center;'>

                                    <div class="panel-body">

                                        <div class="text-center">

                                            <button type="submit" class="btn btn-primary">
                                                Upload
                                            </button>

                                        </div>

                                    </div>
                                    <div class="">
                                        <p><mark>Note:</mark> In order to upload the excel sheet, you need follow a stipulated format, which can be got by clicking on the download option above (Download Format). Also, when you are mentioning the date, kindly mention in the mm/dd/YYYY (eg. 02/15/2018) format only.
                                        </p>
                                    </div>

                                </div>


                            </div>



                        </div>

                </form>
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
