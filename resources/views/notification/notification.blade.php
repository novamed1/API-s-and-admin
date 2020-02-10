@if(Session::has('message'))
    <div role="alert" class="alert alert-success alert-dismissible">
        <button type="button" data-dismiss="alert" aria-label="Close" class="close"><span aria-hidden="true" class="s7-close"></span></button><span class="icon s7-check"></span>{{ Session::get('message') }}
    </div>
@endif
@if(Session::has('error'))
    <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p></div>
@endif
@foreach ($errors as $error)
    <div>{{ $error }}</div>
@endforeach


