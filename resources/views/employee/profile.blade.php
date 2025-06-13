@extends('layouts.admin')
@section('header')
@include('includes.adminHeader_nav')
@stop
@section('toolbar')
    @include('includes.toolbar')
@stop
@section('content')


<!-- end row -->
<div id="kt_content_container" class="container-fluid">
    <div class="row">
        <div class="col-12">
            @if(Session::has('flash_message_error'))
            <div class="alert alert-danger bg-danger text-white" role="alert"><i class="fa fa-times"></i>
            {{ Session::get('flash_message_error') }}</div>
            @endif
            @if(Session::has('flash_message_success'))
            <div class="alert alert-success bg-success text-white" role="alert"><i class="fa fa-check"></i>
            {{ Session::get('flash_message_success') }}</div>
            @endif

            <div class="card m-b-20">
                <div class="card-body">
                    <h4 class="mt-0 header-title card-heading-simple">User Profile</h4>
                <div>
                    <table class="profile-table table table-bordered table-striped">
                        <tr>
                            <td>Company</td>
                            <td> Day Out  Dubai</td>
                        </tr>
                        <tr>
                            <td>Full Name</td>
                            <td>{{$row->full_name}}</td>
                        </tr>
                        <tr>
                            <td>Email Address</td>
                            <td>{{$row->email_address}}</td>
                        </tr>
                        
                    </table>

                </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end page content-->
@stop

@section('footer')
@include('includes.adminFooter')
@stop

@section('script')
@include('includes.adminScripts')
    <script type="text/javascript" src="{{asset('/assets/admin/js/admin.users.js?v=1.2')}}"></script>
@stop