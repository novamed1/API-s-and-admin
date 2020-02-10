
<div class="table-responsive noSwipe">
    <table class="table table-striped table-fw-widget table-hover">
        <thead>
        <tr>
            <th width="25%">Description</th>
            <th width="20%">Target</th>
            <th width="20%">Accuracy</th>
            <th width="20%">Precision</th>
            <th width="10%"></th>
        </tr>
        </thead>
        <tbody class="no-border-x">
        @foreach ($data as $row)
        <tr class="toleranceRow" id="limitTolerenceRow_{!! $row->id !!}">
            <td> <div class="col-md-6">
                        <div class="form-group">
                            {!!Form::text('testtolerence['.$row->id.'][description]',$row->description, array('class'=>'form-control','id'=>'description','readonly')) !!}
                        </div>

                </div></td>
            <td><div class="col-md-4">
                        <div class="form-group">

                            {!!Form::text('testtolerence['.$row->id.'][target]',$row->target_value, array('class'=>'form-control changeValue numeric','id'=>'name','data-attr'=>$row->target_value)) !!}

                        </div>

                </div></td>
            <td><div class="col-md-4">
                        <div class="form-group">


                            {!!Form::text('testtolerence['.$row->id.'][accuracy]',$row->accuracy, array('class'=>'form-control changeValue numeric','id'=>'name','data-attr'=>$row->accuracy)) !!}

                        </div>

                </div></td>
            <td><div class="col-sm-4">

                        <div class="form-group">

                            {!!Form::text('testtolerence['.$row->id.'][precision]',$row->precision, array('class'=>'form-control changeValue numeric','id'=>'name','data-attr'=>$row->precision)) !!}

                        </div>

                </div></td>
            <td><div class="col-sm-4">
                    <div class="form-group closeBottom">

                            <a href="javascript:void(0)" class="removeTolerence btn btn-space btn-primary" data-attr="{!! $row->id !!}"><i class="icon s7-close" aria-hidden="true"></i></a>

                    </div>
                </div></td>
        </tr>

        @endforeach
        </tbody>
    </table>
</div>


<script>
    $(".numeric").keypress(function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 0 && (e.which < 8 || e.which > 57)) {

            return false;
        }
    });
</script>