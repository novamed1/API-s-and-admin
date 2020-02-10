<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>

    <th>Description</th>
    <th>μl</th>
    <th>Accuracy %</th>
    <th>Accuracy μl</th>
    <th>Precision %</th>
    <th>Precision μl</th>
    <th></th>
    </thead>
    @foreach($data as $tol)
        @if($tol->target_value)
        <tr>
            <td>{{$tol->description}}</td>
            <td class="edit_{{$tol->id}}" attr="target">{{$tol->target_value}}</td>
            <td class="edit_{{$tol->id}}" attr="accuracy">{{$tol->accuracy}}</td>
            <td class="edit_{{$tol->id}}" attr="accuracy_ul">{{$tol->accuracy_ul}}</td>
            <td class="edit_{{$tol->id}}" attr="precision">{{$tol->precision}}</td>
            <td class="edit_{{$tol->id}}" attr="precesion_ul">{{$tol->precesion_ul}}</td>
            <td>
                <a href="javascript:void(0);" class="toleranceEdit" id="edittol_{{$tol->id}}" data-attr="{{$tol->id}}"><i class="fa fa-pencil"></i></a>
                <a href="javascript:void(0);" class="toleranceSave" id="savetol_{{$tol->id}}" style="display:none;color: #3CB371;" data-attr="{{$tol->id}}"><i class="fa fa-check inside-ico"></i></a>
                <i class="fa fa-spinner fa-spin inside-ico" id="spinner_{{$tol->id}}" style="display:none;"></i>
            </td>
        </tr>
        @endif
    @endforeach
</table>