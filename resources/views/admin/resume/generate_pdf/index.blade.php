@extends('layouts.admin')
@push('title')
    {{ $pageTitle }} - {{ Config::get('global.SITE_NAME') }}
@endpush
@section('header')
@include('includes.adminHeader_nav')
@stop
@section('toolbar')
@include('includes.toolbar')
@stop
@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-fluid">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        @include('admin.resume.includes.resume_for_position')
        <div class="card mb-5 mb-xl-8 p-5">
            <form action="{{ url('resume/pdf/generate') }}" method="POST">
                @csrf
                <button href="javascript:void(0)" class="btn btn-primary btn-block me-3"><i class="fa-solid fa-download"></i> Click to Generate and Download</button>
            </form>
        </div>

    </div>

</div>

@section('models')
<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-permanent="true" data-kt-drawer-close="#kt_drawer_chat_close">
    <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
            <div class="card-title">
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bold  drawer-title text-gray-900 text-hover-primary me-1 mb-2 lh-1">Add New
                        interest</a>
                </div>
            </div>
            <div class="card-toolbar">
                <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_chat_close">
                    <i class="ki-duotone ki-cross-square fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
        </div>
        <div class=" drawer-body">

        </div>

    </div>
</div>
@endsection
@stop
@section('footer')
@include('includes.adminFooter')
@stop
@section('script')
@include('includes.adminScripts')
<script src="{{ asset('/assets/admin') }}/resume_generate_pdf.js?v={{ time() }}"></script>
@stop
