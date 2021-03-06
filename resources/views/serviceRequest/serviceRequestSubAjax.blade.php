<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th>Asset#</th>
    <th>Serial#</th>
    <th>Instrument</th>
    <th>Location</th>
    <th>Pref Contact</th>
    <th>Plan</th>
    {{--<th>Next Due Date</th>--}}
    <th>Status</th>
    </thead>
    <tbody>
    @if($items)
        @foreach($items as $key=>$row)
    <tr id="{{$row['request_item_id']}}" class="product-list index">


        <td class="hidden-phone">{{$row['assetNo']}}

        </td>
        <td class="hidden-phone">{{$row['serialNo']}}

        </td>
        <td class="hidden-phone">{{$row['instrument']}}

        </td>
         <td class="hidden-phone">{{$row['location']}}

        </td>
        <td class="hidden-phone">{{$row['prefContact']}}

        </td>
        <td class="hidden-phone">
            <span class="plan_text_{{$row['request_item_id']}}"> {{$row['plan']}}</span>
            @if ($row['status'] != "1")
            <a href="javascript:void(0)" data-request-item-id="{{$row['request_item_id']}}" plan-id="{{$row['planId']}}" style="float: right;" class="planEdit plan_edit_{{$row['request_item_id']}}"><i class="fa fa-pencil"></i></a>
                <i class="fa fa-spinner fa-spin inside-ico plan_spinner_{{$row['request_item_id']}}" style="display: none;float: right;"></i>

                {!!Form::select("plan_select_".$row['request_item_id'],$service_plans,$row['planId'],array('data-request-item-id'=>$row['request_item_id'],'style'=>"display:none",'class'=>'plan_change plan_change_'.$row['request_item_id']))!!}

                <a href="javascript:void(0)" style="display: none;float: right;color: #EF6262" data-request-item-id="{{$row['request_item_id']}}" plan-id="{{$row['planId']}}" style="float: right;" class="planClose plan_close_{{$row['request_item_id']}}"><i class="fa fa-close"></i></a>
            @endif
        </td>
        {{--<td class="hidden-phone">{{$row['nextDueDate']}}--}}

        {{--</td>--}}


        @if ($row['status'] == "1")
            <td class="hidden-phone"><span class="label label-success">assigned</span>

        </td>
        @else
        <td class="hidden-phone"><span class="label label-danger">unassigned</span>

        </td>
         @endif

    </tr>
        @endforeach
        @endif

    </tbody>

</table>

