@extends('layout.header')
@section('content')

    <div class="am-content">
        <div class="page-head">

            <h2>Contacts List</h2>

            <ol class="breadcrumb">

                <li><a href="#">Home</a></li>
                <li><a href="#">Website Management</a></li>
                <li><a href="#">Contacts list</a></li>

                <!--                    <li class="active"></li>-->

            </ol>

            <div role="alert" class="alert alert-success alert-dismissible" style="display: none" id="alert">
                <span class="icon s7-check" id="alertMessage"></span>
            </div>
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
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email </th>
                                                <th>Comment</th>
                                                <th>Reply</th>
                                                <th>Delete</th>

                                            </tr>
                                            </thead>

                                            <tfoot>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email </th>
                                                <th>Comment</th>
                                                <th></th>
                                                <th></th>

                                            </tr>
                                            </tfoot>


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
                        <div id='dialog-confirm' title='Send Reply Message' style='display:none;'>
                            <textarea id='replymessage' placeholder='Type Message here...' rows="5" cols="60"></textarea>
                            <span style="display: none;color: red" id="errorReplyMessage">This field is required</span>
                            <i class="fa fa-spinner fa-spin inside-ico" id="spinner" style="display:none;"></i>
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

        <!--Jquery Dialog function Link-->
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script>
            $('#listTable tfoot th').each(function (index) {
                console.log(index);
                if(index == 0 ||index == 1 || index ==2 || index ==3){
                    var title = $(this).text();
                    $(this).html('<input type="text" placeholder="Search' + title + '" />');
                }

            });


            $('#listTable').DataTable({
                "bServerSide": true,
                "sAjaxSource": "{{url('admin/contactuslist')}}",
                "sServerMethod": "POST",
                "iDisplayLength": 10,
                "searching": true,
                initComplete: function () {
                    var api = this.api();

                    // Apply the search
                    api.columns().every(function () {
                        var that = this;

                        $('input', this.footer()).on('keyup change', function () {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });

                    });
                }

            });

        </script>

        <script>

            $(function ($) {
                $('body').on("click", '.reply', function () {
                        var Id = $(this).attr('data-attr');


                        $("#dialog-confirm").dialog({
                            resizable: false,
                            height: 250,
                            width: 500,
                            modal: true,
                            buttons: {
                                "Reply": function () {

                                    var replymessage = $('#replymessage').val();
                                    if(replymessage != ''){
                                        $('#spinner').show();
                                        $.ajax({
                                            type: 'POST',
                                            headers: {
                                                'X-CSRF-Token': "{{ csrf_token() }}"
                                            },
                                            url: "{{url("admin/replycontactus")}}",
                                            data: {replymessage:replymessage,id:Id},
                                            dataType: "json",

                                            success: function (data) {
                                                console.log(data);
                                                $('#spinner').hide();
                                                $('#dialog-confirm').dialog("close");
                                                $('#alertMessage').text(data.message);
                                                $('#alert').show();

                                                setTimeout(function(){
                                                    $('#alert').fadeOut();
                                                    $('#replymessage').val('');
                                                },2000);
                                            }
                                        });

                                    }else{
                                     $('#errorReplyMessage').show();
                                        setTimeout(function(){
                                            $('#errorReplyMessage').fadeOut();
                                        },3000);
                                    }

                                },
                                Cancel: function () {
                                    $(this).dialog("close");
                                }
                             }
                            });
                        });

            });
        </script>
    <script>

        $(function ($) {
            $('body').on("click", '.delete', function () {
                var deleteUrl = $(this).attr('data-src');
                $.confirm({
                    title: "Delete confirmation",
                    text: "Do you want to delete this record ?",
                    confirm: function () {
                        window.location = deleteUrl
                    },
                    cancel: function () {
                    },
                    confirmButton: "Yes",
                    cancelButton: "No"
                });
            });
        });</script>

@stop
