@extends('layouts.admin')
@push('title')
    {{ $pageTitle }} - {{ Config::get('global.SITE_NAME') }}
@endpush
@section('header')
    @include('includes.adminHeader_nav')
    <style>
        .parsley-errors-list {
            list-style-type: none;
            color: red;
        }

        .resize-none-custom {
            resize: none;
        }

        textarea {
            /* resize: none; */
        }
    </style>
@stop
@section('toolbar')
    @include('includes.toolbar')
@stop
@section('content')
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-fluid">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            @include('admin.resume.includes.resume_for_position')
            <div class="mb-5 card mb-xl-8">
                <!--begin::Header-->
                <div class="py-3 card-body">
                    <form name="resume_header_sections" enctype="multipart/form-data" id="resume_header_sections_form"
                        method="post" action="">
                        @csrf
                        <div class="pt-3 mb-8 col-md-12">
                            <h3>Service Section</h3>
                        </div>
                        <div class="mb-8 col-md-12">
                            <label class="mb-2 required fs-6 fw-semibold">Services Section Text</label>
                            <textarea type="text" name="services_section_text" id="services_section_text"
                                placeholder="Enter resume section description" class="form-control" required>{{ $headerSection->services_section_text ?? '' }}</textarea>
                        </div>
                    </form>
                    <!--end::Table container-->
                </div>
                <div class="card-footer">
                    <div class="d-flex flex-stack float-end">
                        <button class="btn btn-light me-5" id="kt_drawer_chat_close" type="button"
                            data-kt-element="cancel">Close</button>
                        <button type="submit" class="btn btn-primary resume_header_section_save_btn">Save</button>
                    </div>
                </div>
                <!--begin::Body-->
            </div>

        </div>

    </div>

@section('models')
    <div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true"
        data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
        <div class="border-0 card w-100 rounded-0" id="kt_drawer_chat_messenger">
            <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#"
                            class="mb-2 text-gray-900 fs-4 fw-bold drawer-title text-hover-primary me-1 lh-1">Add New
                            Employee</a>
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
<script src="{{ asset('/assets/admin') }}/resume_portfolio_settings.js?v={{ time() }}"></script>
@stop
