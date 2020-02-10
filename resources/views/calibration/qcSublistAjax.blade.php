<table id="dt_basic"
       class="table table-striped table-bordered table-hover"
       width="100%">
    <thead>
    <th>Asset#</th>
    <th>Serial#</th>
    <th>Instrument</th>
    <th>Location</th>
    <th>Pref Contact</th>
    <th>Reviewed Technician</th>
    <th>Review PDF</th>
    </thead>
    @foreach($items as $tol)
        <tr>
            <td>{{$tol['assetNumber']}}</td>
            <td>{{$tol['serialNumber']}}</td>
            <td>{{$tol['instrumentModel']}}</td>
            <td>{{$tol['location']}}</td>
            <td>{{$tol['preferredContact']}}</td>
            <td>{{$tol['reviewdTechnician']}}</td>

            <td id="changedReview{{$tol['workOrderItemId']}}">

                @if($tol['report'])


                    <a href="{{url('admin/qcItemReview/'.$tol['workOrderItemId'])}}"
                       class=""
                       id="viewDetails"> <i
                                class="fa fa-search review"></i></a>
                    @else

                    Report not generated

                @endif

            </td>

        </tr>
    @endforeach
</table>

<a style="display:none;" data-toggle="modal" data-target="#singlereportUpload"
   class="modalSinglepopUpreportUpload" data-icon="&#xe0be;" data-keyboard="false"
   data-backdrop="static"></a>

<div id="singlereportUpload" tabindex="-1" role="dialog" class="modal fade modal-colored-header">
    <div class="modal-dialog custom-width">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" data-dismiss="modal" aria-hidden="true" class="close md-close"><i class="icon s7-close"></i></button>
                <h3 class="modal-title">Upload Report</h3>
            </div>
            <div class="modal-body form">
                <div class="form-group">
                    <label>Calibration Report</label>
                    <input type="file" class="form-control" id="calibrationSingleReportFile">
                    <span style="color:red;" id="caliReportError"></span>
                    <span>(File should be in pdf format)</span>
                </div>

                {{--<p>--}}
                    {{--<input type="checkbox" name="complete" id="calibrationCompleteValue" value="1">  <label for="calibrationCompleteValue">Complete this calibration</label>--}}
                {{--</p>--}}
            </div>
            <input type="hidden" id="calibrationReportWorkorderItemId">
            <div class="modal-footer">
                <i class="fa fa-spinner fa-spin" aria-hidden="true" id="calibrationSingleReportSubmitLoader" style="display: none"></i>
                <button type="button" data-dismiss="modal" class="btn btn-default md-close">Cancel</button>
                <button type="button"  class="btn btn-primary" id="calibrationSingleReportSubmit">Submit</button>
            </div>
        </div>
    </div>
</div>