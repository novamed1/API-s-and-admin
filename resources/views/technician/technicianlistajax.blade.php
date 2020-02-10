<div class="sms-table-list" id="pageResult">

    <div class="table-responsive noSwipe">
        <table class="table table-striped table-fw-widget table-hover">

            <thead>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip Code</th>

            </tr>
            </thead>

            @foreach($data as $row)
                <tbody>

                <tr class="">

                    <td>{{$row->first_name}}</td>
                    <td>{{$row->phone_number}}</td>
                    <td>{{$row->email}}</td>
                    <td>{{$row->address}}</td>
                    <td>{{$row->city}}</td>
                    <td>{{$row->state}}</td>
                    <td>{{$row->zip_code}}</td>


                    <td>
                        <a href="{{url('admin/editTechnician/'.$row->id)}}" class=""><i
                                    class="icon s7-pen"></i></a>

                    </td>

                </tbody>
            @endforeach


        </table>


    </div>
    <div class="panel panel-default">

        <div class="panel-body">
            <div class="text-right">
                {{$data->links()}}

            </div>
        </div>
    </div>


</div>
