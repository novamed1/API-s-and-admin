@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">
            <h2>View Report</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Calibration</a></li>
                <li><a href="#">Quality Check</a></li>

            </ol>


            @if($data['workorderItem']->admin_review != '')
                <div class="text-right div-rul">

                    <a href="javascript:void(0);"
                       class="btn btn-space btn-success right"
                    ><i class="fa fa-check"></i>
                        Verified</a>
                </div>

            @endif


        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <iframe id="ifrmaID" src="{{$data['path']}}"
                            style="width:100%;height: 600px;" frameborder="0"></iframe>
                    <input type="hidden" name="file_path" value="{{$data['path']}}" id="file_path">
                </div>

            </div>

            <div class="panel panel-default"
                 style='text-align: center;position: relative;top: 10px;'>
                <div class="panel-body">
                    <div class="text-center download">


                        @if($data['workorderItem']->admin_review != '')
                            <a href="javascript:void(0);"
                               class="btn btn-space btn-success right"
                            ><i class="fa fa-check"></i>
                                Verified</a>

                        @else



                            <a href="javascript:void(0);"
                               class="btn btn-space btn-primary right md-trigger"
                               data-modal="verify-alert"><i class="fa fa-check"></i>
                                Verify</a>

                        @endif



                        {{--<a href="javascript:void(0);" class="btn btn-space btn-primary right"><i class="fa fa-file"></i> Generate Invoice</a>--}}

                    </div>

                </div>

            </div>

        </div>

        <div id="verify-alert" class="modal-container modal-full-color modal-effect-8">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" data-dismiss="modal" aria-hidden="true" class="close modal-close"><i
                                class="icon s7-close"></i></button>
                    <h3 class="modal-title">Alert</h3>
                </div>
                <div class="modal-body text-alert">
                    <div class="text-center">
                        <div class="i-circle success"><i class="icon s7-check"></i></div>
                        <h4>Qc Review</h4>
                        <p>Do you want to review this report?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-primary btn-shade1 modal-close">No
                    </button>
                    {{--<a href="{{url('admin/qcIndividualReview/'.$data['workorderItemId'])}}" data-dismiss="modal"--}}
                       {{--class="btn btn-primary btn-shade1 consolidateQc" data-id="{{$data['workorderItemId']}}">Yes</a>--}}
                    <a href="javascript:void(0)" data-dismiss="modal"
                       class="btn btn-primary btn-shade1 consolidateQc" data-id="{{$data['workorderItemId']}}">Yes</a>
                    <span style="display: none;color:black;" id="spinLoader"><i
                                class="fa fa-spinner fa-spin inside-ico"></i></span>
                </div>
            </div>
        </div>


        <script src="{{asset('js/jquery.js')}}"></script>

        <link rel="stylesheet" type="text/css" media="screen" href="{{asset('css/jquery-confirm.css')}}">
        <script src="{{asset('js/jquery-confirm.js')}}"></script>


        {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">--}}
        {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>--}}
        <style>
            .panel-group.accordion.accordion-semi .panel .panel-heading a.collapsed {
                background-color: #ef6262 !important;
                color: white !important;
            }
        </style>


        <script>
            var url ="{{url('admin/qcIndividualReview/')}}";

            $('body').on('click', '.consolidateQc', function () {
                $(this).hide();
                $('#spinLoader').show();
                $('.modal-close').hide();
                var workOrderId = $(this).attr('data-id');
                console.log(workOrderId);
                var filePath = $('#file_path').val();

                console.log(filePath);
                $.ajax({
                    type: 'post',
                    url: url +"/" + workOrderId,
                    data: {
                        "_token": "{!! csrf_token() !!}",
                        file_path: filePath,
                    },
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        if (res.result == true) {
                            window.location = '{{url('admin/qualitycheck')}}';
                        }
                    }
                });

            });

        </script>



@stop
