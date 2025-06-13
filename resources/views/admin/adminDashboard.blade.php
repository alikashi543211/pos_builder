@extends('layouts.admin')
@push('title')
{{$pageTitle}} - {{Config::get('global.SITE_NAME') }}
 @endpush
@section('header')
@include('includes.adminHeader_nav')
@stop
@section('content')

<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-fluid">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <!--begin::Row-->
        <div class="row gx-5 gx-xl-6 mb-xl-6">
            <!--begin::Col-->
            <div class="col-md-12 col-lg-12 col-xl-12 col-xxl-12 mb-6">
                <div class="alert alert-info">Welcome to Dashboard !</div>
            </div>


        </div>
        <!--end::Row-->

    </div>
    <!--end::Post-->
</div>


@stop
@section('footer')
@include('includes.adminFooter')
@stop
@section('script')
@include('includes.adminScripts')

@stop
