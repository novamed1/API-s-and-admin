@extends('layout.header')
@section('content')
    <style>

        .error {
            color: red;
        }

    </style>
    <div class="am-content">
        <div class="page-head">

            <h2>Permission Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Site Configuration</li>
                <li>Add Permission</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="error">
                        @include('notification/notification')
                    </div>
                    <form role="form" id="myform" data-parsley-validate="" method="post" enctype="multipart/form-data">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Permission</h3>
                            </div>

                            <div class="panel-body">
                                @if(isset($input['id']))
                                    {!! Form::model($input, array('url' => 'admin/editPermission', $input['id'], 'files' => true)) !!}
                                @else

                                    {!! Form::open(array('url' => 'admin/addpermission', 'class' => 'form','method'=>'post')) !!}
                                @endif

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-10">
                                            <div class="form-group">
                                                <label>Menu</label>

                                                {{Form::select('menu',$menus,$input['menu'],array('class'=>'form-control'))}}
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label class="col-sm-3 control-label">Name</label>


                                                {!!Form::text('name',$input['name'], array( 'placeholder' => 'Enter the Name','class'=>'form-control','id'=>'name','required'=>"required")) !!}

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label class="col-sm-3 control-label">Alias name</label>


                                                {!!Form::text('alias_name',$input['alias_name'], array( 'placeholder' => 'Enter the alias name','class'=>'form-control','id'=>'alias_name','required'=>"required")) !!}

                                            </div>

                                        </div>
                                    </div>

                                </div>
                                    <div class="form-group">
                                        <div class="am-checkbox">
                                            @if($input['is_active'] == 1)
                                                @php($chk = 'checked=checked')

                                            @else
                                                @php($chk = '0')

                                            @endif

                                            <input id="check2" type="checkbox" name="is_active"
                                                   class="needsclick" {{$chk}}>
                                            <label for="check2" class="activebottom div-active">is active</label>
                                        </div>
                                    </div>
                            </div>

                        </div>
                        <div class="panel panel-default" style='text-align: center;'>

                            <div class="panel-body">

                                <div class="text-center">
                                    <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                    <a href="{{url('admin/permissionlist')}}"
                                       class="btn btn-space btn-default">Cancel</a>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
