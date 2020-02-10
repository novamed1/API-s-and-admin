<table class="table table-bordere" id="">
    <thead>
    <th width="5%"></th>
    <th width="10%">Name</th>
    <th width="10%">Telephone</th>
    <th width="10%">Email</th>
    <th width="20%">Address1</th>
    <th width="20%">Address2</th>
    <th width="10%">City</th>
    <th width="10%">State</th>
    <th width="5%">Zipcode</th>
    <th></th>
    </thead>
    <tbody>
    @if($data['customer_properties'])
        @foreach($data['customer_properties'] as $key=>$row)

            @if($data['curr_id']==$row['id'])
                <?php $checked='checked' ?>
                @else
                <?php $checked=''; ?>
                @endif
    <tr id="{{$row['id']}}" class="datavalues">
        <td class="">
            <div class="am-checkbox">
                <input type="checkbox" {{$checked}} name="property" id="valuefor{{$row['id']}}" value="{{$row['id']}}">
                <label for="valuefor{{$row['id']}}"></label>
            </div>

        </td>
        <td class="edit_{{$row['id']}}" attr="name">{{$row['name']}}
        </td>
        <td class="edit_{{$row['id']}}" attr="phone">{{$row['phone']}}
        </td>
         <td class="edit_{{$row['id']}}" attr="email">{{$row['email']}}
        </td>
        <td class="edit_{{$row['id']}}" attr="address1">{{$row['address1']}}
        </td>
        <td class="edit_{{$row['id']}}" attr="address2">{{$row['address2']}}
        </td>
        <td class="edit_{{$row['id']}}" attr="city">{{$row['city']}}
        </td>
        <td class="edit_{{$row['id']}}" attr="state">{{$row['state']}}
        </td>
        <td class="edit_{{$row['id']}}" attr="zip">{{$row['zip']}}
       </td>

        <td class="">

            <a href="javascript:void(0);" data-id="{{$row['id']}}" id="editpropertylist_{{$row['id']}}" class="propertyDataEdit"><i class="fa fa-pencil"></i> </a>
            <a href="javascript:void(0);" class="propertyDataSave" id="saveproperty_{{$row['id']}}" style="display:none;color: #3CB371;" data-attr="{{$row['id']}}"><i class="fa fa-check inside-ico"></i></a>
            <i class="fa fa-spinner fa-spin inside-ico" id="spinner_{{$row['id']}}" style="display:none;"></i>
        </td>
    </tr>
        @endforeach
        @endif

    </tbody>

</table>



