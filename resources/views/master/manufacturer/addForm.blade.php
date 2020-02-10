@extends('layout.header')
@section('content')
    <style>

        .error {
            color: red;
        }

        .div-active {
            margin-top: 38px;
        }
        .required
        {
            color: red;
        }

    </style>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyA0SLOL7qb7Ch3gVhP0yBCVYCp1_lVwF60"></script>
    <div class="am-content">
        <div class="page-head">

            <h2>Manufacturer Creation</h2>
            <ol class="breadcrumb">
                <li>Home</li>
                <li>Master Setup</li>
                <li>Add Manufacturer</li>

                <li class="active"></li>

            </ol>
        </div>
        <div class="main-content">
            <div class="row">
                <div class="col-md-12">

                    <div class="error">
                        @include('notification/notification')
                    </div>

                    <form role="form" id="myform" method="post" enctype="multipart/form-data" data-parsley-validate=""
                          novalidate="">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Add Manufacturer</h3>
                            </div>

                            <div class="panel-body">
                                @if(isset($input['manufacturer_id']))
                                    {!! Form::model($input, array('url' => 'admin/editmanufacturer','class'=>'form', $input['manufacturer_id'], 'files' => true)) !!}
                                @else

                                    {!! Form::open(array('url' => 'admin/addmanufacturer', 'class' => 'form','method'=>'post')) !!}
                                @endif

                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
                                        <div class="m-t-18">
                                            <div class="form-group">

                                                <label>Manufacturer Name <span class="required">*</span></label>
                                                {!!Form::text('name',$input['name'], array( 'placeholder' => 'Enter the name','class'=>'form-control','id'=>'name','required'=>"required")) !!}

                                            </div>

                                            <div class="form-group">
                                                <label>Address2</label>

                                                {!!Form::text('address2',$input['address2'], array( 'placeholder' => '','class'=>'form-control')) !!}
                                            </div>

                                            <div class="form-group">

                                                <label>City <span class="required">*</span></label>
                                                {!!Form::text('city',$input['city'], array( 'placeholder' => 'Enter the city','class'=>'form-control city','required'=>"required",'data-geocomplete'=>'city')) !!}

                                            </div>
                                            <div class="form-group">
                                                <label>Tel# <span class="required">*</span></label>

                                            <!--{!!Form::text('phoneNo',$input['phoneNo'], array( 'placeholder' => 'Enter the telephone number','data-parsley-type'=>"",'class'=>'form-control phone simple-field-data-mask-selectonfocus','id'=>'phone','required'=>"required",'data-mask'=>'(000) 000-0000')) !!}-->
                                                <input data-parsley-type="number" type="text" name='phoneNo'
                                                       placeholder="Enter only numbers" required=""
                                                       class="form-control simple-field-data-mask-selectonfocus" data-mask="(000) 000-0000" value={{$input['phoneNo']}} >
                                            </div>

                                            <div class="form-group">
                                                <label>Website</label>

                                                {!!Form::text('website',$input['website'], array( 'placeholder' => 'Enter the website','class'=>'form-control')) !!}
                                            </div>



                                            <div class="form-group" style="    margin-left: -10px;">


                                                <input type="hidden" name="imagehidden" value={{isset($input['image']) ? $input['image'] : ''}}>
                                                @if($input['image'])
                                                    <div class="form-group" id="imageshow">
                                                        <label for="categoryDescription" class="input"></label>
                                                        <div class="col-sm-2 txt-img">
                                                            <a class="thumbnail" href="javascript:void(0);">
                                                                <button type="button" class="close"
                                                                        data-id="{{$input['manufacturer_id']}}"
                                                                        id="image">×
                                                                </button>
                                                                <img class="form-control"
                                                                     src="{{asset('images/manufacturer/extraLarge/'.$input['image'])}}"
                                                                     width="250" height="250">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if($input['image'])
                                                    <div class="imageupload" id="imageupload" style="display:none">
                                                        @else
                                                            <div class="" id="imageupload">
                                                                @endif
                                                                <label class="input imageDesign"
                                                                       style="margin-left: 15px;">Upload
                                                                    Logo</label><br>

                                                                <div class="col-sm-6">
                                                                    <input type="file" name="image" value="{{$input['image']}}" class="form-control textTransform">
                                                                    {{--{!!Form::file('image',$input['image'], array( 'class'=>'form-control textTransform')) !!}--}}
                                                                </div>
                                                            </div>
                                                    </div>

                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="m-t-20">
                                                {{--<div class="form-group">--}}

                                                    {{--<label class="col-sm-3 control-label">Serial Number</label>--}}
                                                    {{--{!!Form::text('serialNo',$input['serialNo'], array( 'placeholder' => 'Enter the serialNo','class'=>'form-control','id'=>'serialNo','required'=>"required")) !!}--}}

                                                {{--</div>--}}

                                                <div class="form-group">
                                                    <label>Address1</label>

                                                    {!!Form::text('address',$input['address'], array( 'placeholder' => '','class'=>'form-control txtAddress1','data-geocomplete'=>'street address')) !!}
                                                </div>
                                                <div class="form-group">

                                                    <label>State <span class="required">*</span></label>
                                                    {!!Form::text('state',$input['state'], array( 'placeholder' => 'Enter the state','class'=>'form-control state','required'=>"required",'data-geocomplete'=>'state')) !!}

                                                </div>

                                                <div class="form-group">

                                                    <label>Zip Code</label>
                                                    {!!Form::text('zipcode',$input['zipcode'], array( 'placeholder' => 'Enter the zip code','class'=>'form-control','data-geocomplete'=>'zipcode')) !!}

                                                </div>

                                                <div class="form-group">
                                                    <label>Fax# </label>

                                                    {!!Form::text('fax',$input['fax'], array( 'placeholder' => 'Enter the fax','data-parsley-type'=>"",'class'=>'form-control simple-field-data-mask-selectonfocus','data-mask'=>'(000) 000-0000')) !!}
                                                </div>

                                                <div class="form-group">

                                                    <label>Email <span class="required">*</span></label>
                                                    {!!Form::text('email',$input['email'], array( 'placeholder' => 'Enter the email','class'=>'form-control','data-parsley-type'=>"email",'id'=>'email','required'=>"required")) !!}

                                                </div>


                                                <div class="form-group">
                                                    <div class="row">
                                                        <section class="col-md-3">
                                                            <div class="am-checkbox">
                                                                @if($input['is_active'] == 1)
                                                                    @php($chk = 'checked=checked')

                                                                @else
                                                                    @php($chk = '0')

                                                                @endif


                                                                <input id="check2" type="checkbox" name="is_active"
                                                                       class="needsclick" {{$chk}}>
                                                                <label for="check2" class="activebottom div-active">is
                                                                    active</label>
                                                            </div>
                                                        </section>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                            <div class="panel panel-default" style='text-align: center;'>

                                <div class="panel-body">

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-space btn-primary">Submit</button>
                                        <a href="{{url('admin/manufacturerlist')}}" class="btn btn-space btn-default">Cancel</a>
                                    </div>

                                </div>

                            </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script src="http://code.jquery.com/qunit/qunit-1.11.0.js"></script>

    <script type="text/javascript" src="{{asset('js/jquery.js')}}"></script>

    <script type="text/javascript" src="{{asset('js/jquery.geocomplete.js')}}"></script>



    <script src="{{asset('js/sinon-1.10.3.js')}}"></script>
    <script src="{{asset('js/sinon-qunit-1.0.0.js')}}"></script>
    <script src="{{asset('js/jquery.mask.js')}}"></script>
    <script src="{{asset('js/jquery.mask.test.js')}}"></script>

    <script>




            $(".txtAddress1").geocomplete({
                fields: "#myform"
            });

        $('body').on('click', '#image', function (event) {
            event.preventDefault()

            var photo = '';
            console.log(photo)
            var Id = $(this).attr('data-id');
            console.log(Id)

//            console.log(Id)
            $('#imageshow').hide();
            $('#imageupload').show();
            $.ajax({
                type: 'get',
                url: "{{url("admin/updatephoto")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    photo: photo,
                    Id: Id,
                },
                dataType: "json",
                success: function (data) {
                    if (data) {

                    }


                }
            });
        });


            $('body').on('change', '.state', function (event) {
            
             var state = $(this).val();
             $.ajax({
                type: 'POST',
                url: "{{url("admin/getcity")}}",
                data: {
                    "_token": "{!! csrf_token() !!}",
                    state: state,
                },
                dataType: "json",
                success: function (data) {
                    if (data.result) {
                        console.log(data.city);
                        $('.city').html(data.city);
                    }


                }
            });


          });


        </script>

@stop

