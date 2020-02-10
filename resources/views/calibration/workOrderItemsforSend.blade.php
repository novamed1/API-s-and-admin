

<table class="table table-condensed table-striped table-fw-widget" id="" style="width: 100%">

    <thead>
    <tr>
        <th></th>
        <th>S no</th>
        <th>Pdf Date</th>
        <th>Total Items</th>

        <th></th>

    </tr>
    </thead>

    <tbody>
    @if(count($details))
        <?php $i=1; ?>
        @foreach($details as $row)
            <tr>
                <td>
                    <a class="acc-ico accordion-toggle subList subList<?=$row['id']?>" data-toggle = "collapse" data-parent = "#accordion" data-target = "#collapse<?=$row['id']?>"><i class="fa fa-plus-square-o" aria-hidden="true"></i></a>
                </td>
                <td>{{$i}}</td>
                <td>{{$row['created_date']}}</td>
                <td>{{count($row['reports_items'])}}</td>
                {{--<td><table>--}}
                        {{--@if(count($row['reports_items']))--}}
                            {{--@foreach($row['reports_items'] as $key1=>$row1)--}}
                                {{--<tr>--}}
                                    {{--<td>{{$row['reports_items'][$key1]->model_name.'/'.$row['reports_items'][$key1]->asset_no.'/'.$row['reports_items'][$key1]->serial_no}}</td>--}}
                                {{--</tr>--}}

                            {{--@endforeach--}}
                        {{--@endif--}}

                    {{--</table></td>--}}

                <td><a href="javascript:void(0);" data-attr="{{$row['id']}}" class="sendMailToCustomer"><i class="fa fa-envelope"></i> </a> </td>

    <tbody id="collapse<?=$row['id']?>" class = "panel-collapse collapse inside-tr" style="background: #e0e0e0;">
    <tr class="table table-condensed table-striped table-fw-widget" style="font-size: 12px;">
        <th></th>
        <th>Model</th>
        <th>Asset</th>
        <th>Serial</th>

    </tr>
    @if($row['reports_items'])
        @foreach($row['reports_items'] as $key1=>$row1)
            <tr style="font-size: 10px;">
                <td></td>
                <td>{{$row['reports_items'][$key1]->model_description}}</td>
                <td>{{$row['reports_items'][$key1]->asset_no}}</td>
                <td>{{$row['reports_items'][$key1]->serial_no}}</td>
            </tr>
        @endforeach
        @endif
    </tbody>
            </tr>
            <?php $i++; ?>
        @endforeach
    @endif
    </tbody>


</table>