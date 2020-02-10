@extends('layout.header')
@section('content')
    <style>
        .div-rul a {
            margin-top: -40px;
        }

    </style>
    <div class="am-content">
        <div class="page-head">

            <h2> Work Orders</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Services & Workorders</a></li>
                <li><a href="#">Work Order Report</a></li>

            </ol>


            <div class="text-right div-rul">
                {{--<a href="{{url('admin/addview')}}" class="btn btn-space btn-primary">Create Instrument</a>--}}
                {{--<a href="{{url('admin/customerExport/'.$customerId)}}" class="btn btn-space btn-primary">Export</a>--}}
                <a href="#" class="btn btn-space"></a>


            </div>
        </div>
        <div class="main-content">
            <div class="row styleforsearch">
                <div class="panel panel-default keywordsearchpanel">

                    <div class="panel-body">


                        <form action="#">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group input-group date datetimepicker" style="margin-top: -4px;">
                                        <input type="text" name="startdate" value="{!! $startdate !!}"
                                               class="form-control" id="startdate" placeholder="Start Date">
                                        <span class="input-group-addon btn btn-primary"><i class="icon-th s7-date"></i></span>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="form-group input-group date datetimepicker" style="margin-top: -4px;">
                                        <input type="text" name="enddate" value="{!! $enddate !!}"
                                               class="form-control" id="enddate" placeholder="End Date">
                                        <span class="input-group-addon btn btn-primary"><i class="icon-th s7-date"></i></span>
                                    </div>


                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">

                                        {!!Form::select("maintananceTo",$maintananceTo,$maintanto,array('class'=>'form-control','id'=>'maintananceto'))!!}
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">

                                        {!!Form::select("status",$statuses,$status,array('class'=>'form-control','id'=>'status','Placeholder'=>'End date'))!!}
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">

                                        <input type="text" id="keywordsearch" name="keyword"
                                               placeholder="Enter Keyword "
                                               class="form-control" value="{!! $keyword !!}"
                                        >
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">

                                        <input type="button" id="searchbtn" value="Search"
                                               class="btn btn-space btn-primary">
                                        <input type="button" id="reset" value="Reset"
                                               class="btn btn-space btn-primary">
                                    </div>

                                </div>



                            </div>



                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="panel panel-default" id="holediv" style="display: none;">

                    <div class="panel-body">
                        <div class="col-sm-18">
                            <div class="widget widget-fullwidth widget-small">

                                <div class="flash-message">
                                    @include('notification/notification')
                                </div>
                                <div class="col-md-12 col-sm-12" id="Result"
                                     style='text-align: center; color: red; font-weight: bold; font-size: 20px;'>

                                    <span>No result found</span>
                                </div>
                                <div class="sms-table-list" id="pageResult">
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

    <div>
        <button data-modal="colored-warning" style="display:none;"
                class="btn btn-space btn-warning md-trigger colored-warning popUp">Warning
        </button>
    </div>
    <div id="saving_container" style="display:none;">
        <div id="saving"
             style="background-color:#000; position:fixed; width:100%; height:100%; top:0px; left:0px;z-index:100000"></div>
        <img id="saving_animation" src="{{asset('img/load.gif')}}" alt="saving"
             style="z-index:100001;     margin-left: -42px;margin-top: -86px; position:fixed; left:50%; top:50%"/>

        <div id="saving_text"
             style="text-align:center; width:100%; position:fixed; left:0px; top:50%; margin-top:40px; color:#fff; z-index:100001">
            <br>
        </div>
    </div>
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{asset('js/underscore/underscore.js')}}"></script>
    <script>
        $(function () {
            $("#startdate").datepicker({maxDate: new Date()});
        });
        $(function () {
            $("#enddate").datepicker({maxDate: new Date()});
        });
    </script>
    <script>
        $('body').on('click', '#reset', function () {

            $('#keywordsearch').val('');
            $('#maintananceto').val('');
            $('#calibrationto').val('');
            $('#startdate').val('');
            $('#enddate').val('');
            $('#status').val('');
            $('#holediv').hide();
        });
    </script>

    <script>
        $('body').on('click', '#searchbtn', function () {
            show_animation();

            var keyword = $('#keywordsearch').val();
            var maintananceto = $('#maintananceto').val();
            var startdate = $('#startdate').val();
            var enddate = $('#enddate').val();
            var status = $('#status').val();


            $.ajax({
                type: 'post',
                url: "{{url("admin/workOrderSearchAjax")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    keyword: keyword,
                    maintananceTo: maintananceto,
                    startdate: startdate,
                    enddate: enddate,
                    status: status,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {
                        hide_animation();
                        if (data.result == true) {
                            hide_animation();
                            $('#holediv').show();
                            $('#Result').hide();
                            $('#pageResult').html(data.formData);
                        } else {
                            $('#holediv').show();
                            $('#pageResult').remove();
                            $('#Result').show();
                        }

                    }
                }

            });


        });
    </script>

    <div id="colored-warning" class="modal-container modal-colored-header modal-colored-header-warning modal-effect-10">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                            class="icon s7-close"></i></button>
                <h3 class="modal-title">Warning</h3>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="i-circle text-warning"><i class="icon s7-attention"></i></div>
                    <h4>Warning!</h4>
                    <p>Please fill any one of the fields</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning modal-close">OK</button>
            </div>
        </div>
    </div>


@stop

