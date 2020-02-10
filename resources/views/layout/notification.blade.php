@if(Session::has('message'))
    <div id="card-alert" class="card green">
        <div class="card-content white-text">
            {{ Session::get('alert-class', '') }}{{ Session::get('message') }}
            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    </div>
@endif
@if(Session::has('error'))
    <div id="card-alert" class="card red">
        <div class="card-content white-text">
            {{ Session::get('card-alert', '') }} {{ Session::get('error') }}</div></div>
    <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
@endif
@foreach ($errors as $error)
        {{--{{dd($error)}}--}}
        <div id="card-alert" class="card red">
            <div class="card-content white-text">
                {{ Session::get('card-alert', '') }}  {{ $error }}

        <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
            </div></div>
@endforeach


