<div class="modal-body service-details-modal" style="padding: 0px!important;">
    {{--<h5 class="text-center">Hello. Some text here.</h5>--}}
    <table class="table table-striped" id="tblGrid">
        <thead id="tblHead">
        <tr>
            <th>Volume</th>
            <th>Channel</th>
            <th>Operation</th>
            <th>Point</th>
            <th>Price($)</th>
        </tr>
        </thead>
        <tbody>
        @if($data)
            @foreach($data as $value)
                <tr>
                    <td>{{$value['volume']}}</td>
                    <td>{{$value['channel']}}</td>
                    <td>{{$value['operation']}}</td>
                    <td>{{$value['point']}}</td>
                    <td>$<span style="margin-left:2px;"></span>{{$value['price']}}</td>

                </tr>
            @endforeach
        @endif

        </tbody>
    </table>

</div>

<style type="text/css">

    .service-details-modal p.mod-service-img {
        position: absolute;
        padding: 0;
    }

    .service-details-modal h5 {

        font-size: 18px;
        margin-bottom: 5px;
    }

    .service-details-modal p {
        margin-bottom: 10px;
    }

</style>