<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th>Asset#</th>
    <th>Serial#</th>
    <th>Instrument</th>
    <th>Location</th>
    <th>Pref Contact</th>
    <th>Tel#</th>
    <th>Calib status</th>
    <th></th>
    </thead>
    <tbody>
    @if($items)
        @foreach($items as $key=>$row)
    <tr id="{{$row['request_item_id']}}" class="product-list index sub_list_{{$row['id']}}">


        <td class="hidden-phone">{{$row['assetNumber']}}

        </td>
        <td class="hidden-phone">{{$row['serialNumber']}}

        </td>
         <td class="hidden-phone">{{$row['modelName']}}

        </td>
        <td class="hidden-phone">{{$row['location']}}

        </td>
        <td class="hidden-phone">{{$row['contact']}}

        </td>
        <td class="hidden-phone">{{$row['tel']}}

        </td>

        @if ($row['status'] == "completed")
            <td class="hidden-phone"><span class="label label-success">completed</span>

        </td>
        @else
        <td class="hidden-phone"><span class="label label-danger">pending</span>

        </td>
            <td class="hidden-phone"><a href='javascript:void(0)' title="Remove from workorder"
                                        class='remove' item-id="{{$row['id']}}">
                    <i class='fa fa-close'
                       aria-hidden='true'></i></a>

            </td>
         @endif

    </tr>
        @endforeach
        @endif

    </tbody>

</table>