<div class="main-content">
    <form action="#" id="updatedPaymentForm"
          class="form-horizontal group-border-dashed" method="post"
          data-parsley-validate>


        <input type="hidden" name="id" value="{{$details['details']['customer_id']}}">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

                <div class="panel panel-default">
                    <div class="error">
                    </div>


                    <div class="panel-body">
                        {{--<div class="col-lg-12" style="">--}}

                        {{--<div class="col-lg-6" style="border-right: 1px solid #eee;;">--}}

                        <h5 class="heading" style="margin-bottom: 22px;">Mode Of Payment</h5>

                        <div class="col-lg-3">


                            @if($details['paymethods'])
                                @foreach($details['paymethods'] as $paykey=>$payval)


                                    @if($payval->id == $details['details']['pay_method'])

                                        @php($chk1 = 'checked=checked')
                                        {{--@php($value='1')--}}

                                    @else
                                        @php($chk1 = '0')
                                        {{--@php($value='0')--}}

                                    @endif

                                    <div class="col-sm-12">
                                        <div class="am-radio inline">
                                            <input type="radio" name="payMethod"
                                                   class="{{$payval->name}}"
                                                   id="pay-{{$payval->id}}"
                                                   value="{{$payval->id}}" {{$chk1}}>
                                            <label for="pay-{{$payval->id}}">{{$payval->name}}</label>
                                        </div>
                                    </div>

                                @endforeach
                            @endif
                        </div>


                        <div class="form-group" style="margin-top: 134px;">
                            <label style="font-size: 15px">Payment Terms:</label>
                            {!!Form::textarea('paymentTerms',$details['details']['pay_terms'], array('class'=>'form-control','id'=>'paymentTerms','cols'=>30,'rows'=>'5','required'=>"")) !!}

                        </div>


                        {{--</div>--}}
                        {{--</div>--}}


                    </div>


                </div>


            </div>
    </form>
</div>