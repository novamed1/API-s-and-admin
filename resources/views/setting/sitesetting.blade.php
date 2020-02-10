@include('layout/header')
@section('content')

    <script src="{{asset('lib/jquery/jquery.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.textEditors();
        });
        </script>

    <div class="am-content">
        <div class="page-head">

            <h2>Site Settings</h2>

            <ol class="breadcrumb">

                <li><a href="{{url('admin/dashboard')}}">Home</a></li>
                <li><a href="#">Site Configuration</a></li>
                <li><a href="#">Site Settings</a></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="col-sm-12">
                <div class="tab-container">


                    <div id="home" class="tab-pane cont">
                        @if(isset($input['id']))

                            {!! Form::model($input, array('url' => 'admin/editwebsettings', $input['id'], 'files' => true)) !!}
                        @else

                            {!! Form::open(array('url' => 'admin/addwebsettings', 'class' => 'form','method'=>'post')) !!}
                        @endif
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="panel-title">As Found Test</div>
                                            </div>
                                            <div class="panel-body">
                                                <textarea class="summernote" name="as_found">{{$input['as_found']}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Test Points (As Found Test)</div>
                                        </div>
                                        <div class="panel-body">

                                            <textarea class="summernote" name="test_points">{{$input['test_points']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Reading (As Found Test)</div>
                                        </div>
                                        <div class="panel-body">

                                            <textarea class="summernote" name="readings">{{$input['readings']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Cleaning & Maintenance</div>
                                        </div>
                                        <div class="panel-body">

                                            <textarea class="summernote" name="cleaning">{{$input['cleaning']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title">As Calibrated Test</div>
                                    </div>
                                    <div class="panel-body">

                                        <textarea class="summernote" name="as_calibrated">{{$input['as_calibrated']}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Test Points (As Calibrated Test)</div>
                                        </div>
                                        <div class="panel-body">

                                            <textarea class="summernote" name="calibrated_test_point">{{$input['calibrated_test_point']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Readings (As Calibrated Test)</div>
                                        </div>
                                        <div class="panel-body">

                                            <textarea class="summernote" name="calibrated_readings">{{$input['calibrated_readings']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Due Date Labels</div>
                                        </div>
                                        <div class="panel-body">

                                            <textarea class="summernote" name="due_date">{{$input['due_date']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>





                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="panel-title">Certificate</div>
                                    </div>
                                    <div class="panel-body">
                                        <textarea class="summernote" name="certificate">{{$input['certificate']}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Warranty</div>
                                        </div>
                                        <div class="panel-body">
                                            <textarea class="summernote" name="warranty">{{$input['warranty']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Repairs</div>
                                        </div>
                                        <div class="panel-body">
                                            <textarea class="summernote" name="repairs">{{$input['repairs']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Parts</div>
                                        </div>
                                        <div class="panel-body">
                                            <textarea class="summernote" name="parts">{{$input['parts']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>

                    <div class="panel panel-default" style='text-align: center;'>

                        <div class="panel-body">

                            <div class="text-center">
                                <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                <a href="{{url('admin/dashboard')}}"
                                   class="btn btn-space btn-default">Cancel</a>
                            </div>

                        </div>

                    </div>
                    </form>

                </div>
            </div>


        </div>
    </div>

    <link rel="stylesheet" type="text/css" href="{{asset('lib/summernote/summernote.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('lib/font-awesome/css/font-awesome.min.css')}}"/>


    <script src="{{asset('lib/jquery.nanoscroller/javascripts/jquery.nanoscroller.min.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('js/main.js')}}" type="text/javascript"></script>
    <script src="{{asset('lib/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>

    <script src="{{asset('lib/summernote/summernote.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('lib/summernote/summernote-ext-amaretti.js')}}" type="text/javascript"></script>
    {{--<script src="{{asset('js/app-form-wysiwyg.js')}}" type="text/javascript"></script>--}}


    <style>
        .serviccetabs li {
            width: 48%;
        }

        .serviccetabs > li.active a {
            background-color: #ef6262 !important;
            color: white;
        }

        .serviccetabs a:hover a:active {
            color: red;
            background-color: #ef6262;
        }

    </style>


    <script type="text/javascript">
        $(document).ready(function () {
            //initialize the javascript
            App.init();
            App.textEditors();
        });

        var App = (function () {
            'use strict';



            App.textEditors = function( ){

                //Summernote


                $('.summernote').summernote({

                    height: 300
                });

//                $('.editor_as_calibrated').summernote({
//                    height: 300
//                });
//                $('.editor_test_points').summernote({
//                    height: 300
//                });
//                $('.editor_reading').summernote({
//                    height: 300
//                });
//                $('.editor_certificate').summernote({
//                    height: 300
//                });

            };


            return App;
        })(App || {});

    </script>
