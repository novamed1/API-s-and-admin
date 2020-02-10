<div class="main-content">
    <form action="#" id="updatedCustomerForm"
          class="form-horizontal group-border-dashed" method="post"
          data-parsley-validate>
        <input type="hidden" name="id" value="{{$details['id']}}">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

                <div class="panel panel-default">
                    <div class="error">
                    </div>


                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-18">
                                    <div class="form-group">

                                        <label>Name</label>
                                        {!!Form::text('name',$details['name'], array( 'class'=>'form-control','id'=>'firstName','required'=>"required",'placeholder'=>'Please Enter First Name')) !!}

                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    <div class="form-group">
                                        <label>Tel#</label>

                                        {!!Form::text('tel',$details['phone'], array('class'=>'form-control','id'=>'lastName','placeholder'=>'Please Enter Telephone number')) !!}
                                    </div>

                                </div>
                            </div>

                        </div>


                        <div class="row">


                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    <div class="form-group">
                                        <label>Email</label>

                                        {{Form::text('email',$details['email'],array('class'=>'form-control','required'=>"required",'id'=>'email','placeholder'=>'Please Enter Email Id','readonly'))}}
                                    </div>

                                </div>
                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    <div class="form-group">
                                        <label>Address 1</label>

                                        {{Form::text('address1',$details['address1'],array('class'=>'form-control','required'=>'required','id'=>'phoneNumber','placeholder'=>'Please Enter Address1'))}}
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    <div class="form-group">
                                        <label>Address 2</label>

                                        {{Form::text('address2',$details['address2'],array('class'=>'form-control','required'=>"required",'id'=>'address','placeholder'=>'Please Enter Address2'))}}
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    <div class="form-group">
                                        <label>Primary contact</label>

                                        {{Form::text('primarycontact',$details['primarycontact'],array('class'=>'form-control','required'=>'required','id'=>'city','placeholder'=>'Please Enter Primary Contact'))}}
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    <div class="form-group">
                                        <label>City</label>

                                        {{Form::text('city',$details['city'],array('class'=>'form-control','required'=>"required",'id'=>'state','placeholder'=>'Please Enter city'))}}
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    <div class="form-group">
                                        <label>State</label>

                                        {{Form::text('state',$details['state'],array('class'=>'form-control','required'=>'required','id'=>'zipcode','placeholder'=>'Please Enter State'))}}
                                    </div>

                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    <div class="form-group">
                                        <label>Zip code</label>

                                        {{Form::text('zip',$details['zip'],array('class'=>'form-control','required'=>"required",'id'=>'password','placeholder'=>'Please Enter Zip Code'))}}
                                    </div>

                                </div>
                            </div>

                            <div class="col-sm-6 col-xs-12">
                                <div class="m-t-10">
                                    {{--<div class="form-group">--}}
                                    {{--<label>Zip Code</label>--}}

                                    {{--{{Form::text('zip_code',$input['zip_code'],array('class'=>'form-control','required'=>'required','id'=>'zipcode','placeholder'=>'Please Enter Zip Code'))}}--}}
                                    {{--</div>--}}

                                </div>
                            </div>

                        </div>

                    </div>


                </div>


            </div>


        </div>
    </form>
</div>