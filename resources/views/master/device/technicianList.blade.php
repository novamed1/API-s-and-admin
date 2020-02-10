<div class="modal-body form" style="padding-top: 10px;">

    <div class="form-group"><br>
        <label>Technician List</label>


        @if($technician)
            @foreach($technician as $teckkey)
                @if (in_array($teckkey->id, $alreadyTech))

                    @php($chk1 = 'checked=checked')

                @else
                    @php($chk1 = '0')

                @endif
                <div class="am-checkbox">
                    <input id="check-{{$teckkey->id}}" type="checkbox" name="tecchnicians"
                           data-attr="" value="{{$teckkey->id}}"/{{$chk1}}>
                    <label for="check-{{$teckkey->id}}"> {{$teckkey->first_name}}</label>
                </div>

            @endforeach
        @endif

    </div>
    @if(count($technician))
    <div class="modal-footer">
        <button type="button" data-dismiss="modal"
                data-target="#form-bp1"
                class="btn btn-default md-close">
            Cancel
        </button>

        <button type="button" id="submitTech" data-dismiss="modal"
                class="btn btn-primary">
            Proceed
        </button>
    </div>
        @endif

</div>