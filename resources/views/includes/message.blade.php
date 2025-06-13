@if(Session::has('flash_message_success'))
    <div class="alert alert-success bg-success text-white" role="alert"> {{ Session::get('flash_message_success') }}</div>
@endif

@if(Session::has('flash_message_error'))
    <div class="alert alert-danger bg-danger text-white" role="alert"> {{ Session::get('flash_message_error') }}</div>
@endif
