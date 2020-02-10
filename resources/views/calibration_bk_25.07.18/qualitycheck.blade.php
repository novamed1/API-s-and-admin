@extends('layout.header')
@section('content')

    <style>
        #dt_basic tr th {
            font-size: 13px;
        }

        #dt_basic tr td {
            font-size: 12px;
        }

        .inside-ico {
            font-size: 20px;
        }
    </style>

    <div class="am-content">
        <div class="page-head">

            <h2>Quality Check </h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Calibration</a></li>
                <li><a href="#">Quality Check</a></li>

            </ol>

            {{--<div class="text-right div-rul">--}}
                {{--<a href="{{url('admin/isospecification')}}" class="btn btn-space btn-primary">Create ISO--}}
                    {{--Specification</a>--}}
            {{--</div>--}}
        </div>
        <div class="main-content">
            <div class="row styleforsearch">

                <div class="panel panel-default keywordsearchpanel">

                    <div class="panel-body">


                        <form action="#" method="post">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <div class="col-md-4">
                                <div class="form-group">

                                    <input type="text" id="keywordsearch" name="keyword" placeholder="Enter Keyword "
                                           class="form-control" value="{!! $keyword !!}"
                                           style="margin:  auto;margin-top: 6px;">

                                </div>


                            </div>
                            <div class="col-md-4">
                                <div class="form-group" style='margin-top: 6px;'>

                                    {{Form::select('status',$status,$statuskeyword,array('placeholder'=>'Status','class'=>'form-control'))}}
                                </div>

                            </div>

                            <div class="col-md-3" style="margin:  auto; margin-top: 8px;">
                                <input type="submit" id="searchbtn" value="Search"
                                       class="btn btn-space btn-primary">
                                <a href="{{url('admin/qualitycheck')}}" id="reset"
                                   class="btn btn-space btn-primary">Reset</a>

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

                                @if($data)

                                    <div class="sms-table-list" id="pageResult">

                                        <div class="table-responsive noSwipe">
                                            <table class="table table-striped table-fw-widget" id="tagcollapse">

                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Work Order Number</th>
                                                    <th>Customer Name</th>
                                                    <th>Plan Name</th>
                                                    <th>Request Number</th>
                                                    <th>Maintanence To</th>
                                                    <th>Calibrated To</th>
                                                    <th>Status</th>
                                                    <th>Work Order Date</th>
                                                    <th></th>


                                                </tr>
                                                </thead>


                                                @foreach($data as $row)

                                                    <tbody>

                                                    <tr>
                                                        <td>
                                                      <span class="lead_numbers">
                                                   <a href="javascript:void(0)"
                                                      id="isotolerances"
                                                      rel="{{$row['workOrderId']}}"
                                                      data-toggle="collapse"
                                                      data-target="#isotolerances{{$row['workOrderId']}}"
                                                      data-id="{{$row['workOrderId']}}"
                                                      data-parent="#tagcollapse"
                                                      class="accordion-toggle hov aHrefCollapse"
                                                   ><i
                                                               class="fa fa-plus-circle ordericon collapseico"
                                                               data-widget-collapsed="true"
                                                               data-attr="{{$row['workOrderId']}}"></i> <span
                                                               class="menu-item-parent"></span></a>
                                                      </span>
                                                        </td>

                                                        <td>{{$row['workOrderNumber']}}</td>
                                                        <td>{{$row['customer_name']}}</td>
                                                        <td>{{$row['planName']}}</td>

                                                        <td>{{$row['reqNumber']}}</td>
                                                        <td>{{$row['maintaainedBy']}}</td>
                                                        <td>{{$row['calibratedBy']}}</td>
                                                        <td>

                                                            @if($row['workProgress'] == 3)
                                                                <span class="label label-success">Complete</span>
                                                            @elseif($row['workProgress'] == 2)
                                                                <span class="label label-danger">Progress</span>
                                                            @endif

                                                        </td>
                                                        <td>{{Carbon\Carbon::parse($row['workOrderDate'])->add(new DateInterval('PT5H'))->add(new DateInterval('PT30M'))->format('j-M-Y')}}</td>
                                                        @if($row['workProgress']==3)
                                                            @if($row['admin_review'])
                                                                <td>

                                                                    <a href="{{url('public/report/consolidate/adminreview/'.$row['report'])}}"
                                                                       class="btn btn-space btn-primary"
                                                                       id="viewDetails"><i class=''
                                                                                           aria-hidden="true">Qc
                                                                            review</i></a>
                                                                </td>
                                                            @else
                                                                <td>

                                                                    <a href="{{url('admin/qcReview/'.$row['workOrderId'])}}"
                                                                       class="btn btn-space btn-primary"
                                                                       id="viewDetails"><i class=''
                                                                                           aria-hidden="true">Qc
                                                                            review</i></a>
                                                                </td>
                                                            @endif
                                                        @endif

                                                    </tr>

                                                    <tr>
                                                        <td colspan="12" class="hiddenRow">
                                                            <div class="accordian-body collapse"
                                                                 id="isotolerances{{$row['workOrderId']}}">
                                                                <table id="dt_basic"
                                                                       class="table table-striped table-bordered table-hover"
                                                                       width="100%">
                                                                    <thead>

                                                                    <th>Asset Number</th>
                                                                    <th>Serial Number</th>
                                                                    <th>Reviewed Technician</th>
                                                                    {{--<th>View PDF</th>--}}
                                                                    <th>Review PDF</th>
                                                                    </thead>
                                                                    @foreach($row['workDetails'] as $tol)
                                                                        <tr>
                                                                            <td>{{$tol['assetNumber']}}</td>
                                                                            <td>{{$tol['serialNumber']}}</td>
                                                                            <td>{{$tol['reviewdTechnician']}}</td>
                                                                            {{--<td>--}}
                                                                                {{--@if($tol['report'])--}}
                                                                                    {{--<a download="{{$tol['report']}}"--}}
                                                                                       {{--href="{{$tol['doclink']}}"--}}
                                                                                       {{--class="btn btn-space btn-primary"--}}
                                                                                       {{--id="viewDetails"><i class=''--}}
                                                                                                           {{--aria-hidden="true">View--}}
                                                                                            {{--PDF</i></a>--}}
                                                                                {{--@endif--}}
                                                                            {{--</td>--}}
                                                                            <td>
                                                                                <a href="{{$tol['reviewlink']}}"
                                                                                   class="btn btn-space btn-primary"
                                                                                   id="viewDetails"><i class=''
                                                                                                       aria-hidden="true">QC Review</i></a>

                                                                            </td>


                                                                        </tr>
                                                                    @endforeach
                                                                </table>

                                                            </div>
                                                        </td>
                                                    </tr>


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
    <script src="{{asset('js/jquery.js')}}"></script>
    <script>
        $('body').on('click', '.toleranceEdit', function () {
            $(this).hide();
            var id = $(this).attr('data-attr');
            $('.edit_' + id).each(function () {
                var content = $(this).html();
                var name = $(this).attr('attr');
                $(this).html('<input type="text" attr=' + name + ' id="tol_' + name + '_' + id + '" class="form-control text_value_' + id + '" value=' + content + '>');
            });

            $('#savetol_' + id).show();
        });

        $('body').on('click', '.toleranceSave', function () {
            var id = $(this).attr('data-attr');
            var target = $('#tol_target_' + id).val();
            var accuracy = $('#tol_accuracy_' + id).val();
            var precision = $('#tol_precision_' + id).val();
            var accuracy_ul = $('#tol_accuracy_ul_' + id).val();
            var precesion_ul = $('#tol_precesion_ul_' + id).val();
            var datastring = {
                target: target,
                accuracy: accuracy,
                precision: precision,
                accuracy_ul: accuracy_ul,
                precesion_ul: precesion_ul,
                id: id,
                "_token": "{!! csrf_token() !!}"
            };
            $('#savetol_' + id).hide();
            $('#spinner_' + id).show();
            $.ajax({
                type: 'post',
                url: "{{url("admin/saveajaxtolerance")}}",
                data: datastring,
                dataType: "json",
                success: function (data) {
                    if (data) {
                        if (data.result) {
                            $('.edit_' + id).each(function () {
                                var attr = $(this).attr('attr');
                                var content = $('#tol_' + attr + '_' + id).val();
                                $(this).html(content);
                            });

                            $('#spinner_' + id).hide();
                            $('#edittol_' + id).show();
                            if (data.updated) {
                                $.toast({
                                    heading: 'Updated',
                                    text: data.message,
                                    //position: 'top-left',
                                    showHideTransition: 'slide',
                                    icon: 'success',

                                    loader: false
                                });
                            }

                        }

                    }
                }

            });

        });
        //        $("a").on("click", "i .fa-plus-circle", function () {
        $('body').on('click', '.fa-plus-circle', function () {

            var thisattr = $(this).attr('data-attr');
            $(this).removeClass('fa-plus-circle');
            $(this).addClass('fa-minus-circle');

            $('.fa-minus-circle').each(function () {
                if (thisattr == $(this).attr('data-attr')) {
                    $(this).removeClass('fa-plus-circle');
                    $(this).addClass('fa-minus-circle');
                }
                else {
                    $(this).addClass('fa-plus-circle');
                    $(this).removeClass('fa-minus-circle');
                }

            });

        });


        $('body').on('click', '.fa-minus-circle', function () {
            $(this).removeClass('fa-minus-circle');
            $(this).addClass('fa-plus-circle');

        });

        $(document).ready(function () {
            var $myGroup = $('#tagcollapse');
            $myGroup.on('show.bs.collapse', '.collapse', function () {
                $myGroup.find('.collapse.in').collapse('hide');
            });
        });


    </script>

    <style>
        #isotolerances a:hover, a:focus{
            color:red!important;
        }
    </style>
@stop


