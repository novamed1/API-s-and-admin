


<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th></th>
    <th>Model</th>
    <th>Asset Number</th>
    <th>Serial Number</th>

    </thead>
    @if($details['details'])
        @foreach($details['details'] as $teckkey)
        <tr>
            <td><div class="am-checkbox">
                    <input id="check-{{$teckkey['workOrderItemId']}}" type="checkbox" class="messageCheckbox"
                           name="workorderitems"
                           data-attr="" value="{{$teckkey['workOrderItemId']}}"/>
                    <label for="check-{{$teckkey['workOrderItemId']}}"></label>
                </div></td>
            <td>{{$teckkey['instrumentModel']}}</td>
            <td>{{$teckkey['asset_no']}}</td>
            <td>{{$teckkey['serial_no']}}</td>
            </tr>
    @endforeach
        @endif
</table>


<input type="hidden" value="{{$details['workOrderId']}}" id="work_order_id_value">
<div class="modal-footer">
    @if($details['details'])
    <a href="javascript:void(0);" class="btn btn-primary right" id="updateCustomerDetailClick">OK,Generate
        PDF</a>
        <i class="fa fa-spinner fa-spin pdfSpinner" style="display: none"></i>
        @else
        <button type="button" data-dismiss="modal" aria-hidden="true"
                class="close md-close">Close</button>
    @endif
    <span style="display: none" id="spinLoader"><i
                class="fa fa-spinner fa-spin inside-ico"></i></span>
</div>