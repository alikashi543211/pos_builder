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
                <div class="pt-5 border-0 card-header">

                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="py-3 card-body">
                    <form name="resume_header_sections" enctype="multipart/form-data" id="resume_header_sections_form"
                        method="post" action="">
                        @csrf
                        <input type="hidden" name="job_position_id" value="{{ $activeJobPosition->id ?? 0 }}">
                        <div class="row">
                            <div class="mb-8 col-md-6">
                                <div class="form-group">
                                    <label class="mb-2 fs-6 fw-semibold">Background Image <div class="form-text">
                                            Allowed file types: png, jpg, jpeg. (1920&times;1280)</div></label>
                                    <input type="file" name="background_image" class="form-control">
                                </div>
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Uploaded Background Image</label>
                                <div id="backgroundImagePreviewWrapper" class="d-flex align-items-center">
                                    <img id="backgroundImagePreview"
                                        src="{{ $headerSection->background_image ?? photo('empty.png', 'backgrounds') }}"
                                        class="img-thumbnail"
                                        style="max-width: 200px; height: auto; display: {{ isset($headerSection->background_image) ? 'block' : 'none' }};">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">First Name</label>
                                <input type="text" name="first_name" id="first_name" placeholder="Enter first name"
                                    value="{{ $headerSection->first_name ?? '' }}" class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Last Name</label>
                                <input type="text" name="last_name" id="last_name" placeholder="Enter last name"
                                    value="{{ $headerSection->last_name ?? '' }}" class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Full Name</label>
                                <input type="text" name="full_name" id="full_name" placeholder="Enter full name"
                                    value="{{ $headerSection->full_name ?? '' }}" class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Position Display Title 1</label>
                                <input type="text" name="position_display_title_1" id="full_name"
                                    placeholder="Position Display Title"
                                    value="{{ $headerSection->position_display_title_1 ?? '' }}" class="form-control"
                                    required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Position Display Title 2</label>
                                <input type="text" name="position_display_title_2" id="full_name"
                                    placeholder="Position Display Title 2"
                                    value="{{ $headerSection->position_display_title_2 ?? '' }}" class="form-control"
                                    required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Position Display Title 3</label>
                                <input type="text" name="position_display_title_3" id="full_name"
                                    placeholder="Position Display Title 3"
                                    value="{{ $headerSection->position_display_title_3 ?? '' }}" class="form-control"
                                    required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Position Display Title 4</label>
                                <input type="text" name="position_display_title_4" id="full_name"
                                    placeholder="Position Display Title"
                                    value="{{ $headerSection->position_display_title_4 ?? '' }}" class="form-control"
                                    required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-8 col-md-12">
                                <h3 class="alert alert-info">Job Section</h3>
                            </div>
                            <div class="row">
                                <div class="mb-8 col-md-6">
                                    <label class="mb-2 required fs-6 fw-semibold">First Name</label>
                                    <input type="text" name="job_first_name" id="job_first_name"
                                        placeholder="Enter first name" value="{{ $headerSection->job_first_name ?? '' }}"
                                        class="form-control" required />
                                </div>
                                <div class="mb-8 col-md-6">
                                    <label class="mb-2 required fs-6 fw-semibold">Last Name</label>
                                    <input type="text" name="job_last_name" id="job_last_name"
                                        placeholder="Enter last name" value="{{ $headerSection->job_last_name ?? '' }}"
                                        class="form-control" required />
                                </div>
                                <div class="mb-8 col-md-6">
                                    <label class="mb-2 required fs-6 fw-semibold">Full Name</label>
                                    <input type="text" name="job_full_name" id="job_full_name"
                                        placeholder="Enter full name" value="{{ $headerSection->job_full_name ?? '' }}"
                                        class="form-control" required />
                                </div>
                                <div class="mb-8 col-md-6">
                                    <label class="mb-2 required fs-6 fw-semibold">Position Display Title 1</label>
                                    <input type="text" name="job_position_display_title_1"
                                        id="job_position_display_title_1" placeholder="Position Display Title"
                                        value="{{ $headerSection->job_position_display_title_1 ?? '' }}"
                                        class="form-control" required />
                                </div>
                                <div class="mb-8 col-md-6">
                                    <label class="mb-2 required fs-6 fw-semibold">Position Display Title 2</label>
                                    <input type="text" name="job_position_display_title_2"
                                        id="job_position_display_title_2" placeholder="Position Display Title 2"
                                        value="{{ $headerSection->job_position_display_title_2 ?? '' }}"
                                        class="form-control" required />
                                </div>
                                <div class="mb-8 col-md-6">
                                    <label class="mb-2 required fs-6 fw-semibold">Position Display Title 3</label>
                                    <input type="text" name="job_position_display_title_3"
                                        id="job_position_display_title_3" placeholder="Position Display Title 3"
                                        value="{{ $headerSection->job_position_display_title_3 ?? '' }}"
                                        class="form-control" required />
                                </div>
                                <div class="mb-8 col-md-6">
                                    <label class="mb-2 required fs-6 fw-semibold">Position Display Title 4</label>
                                    <input type="text" name="job_position_display_title_4"
                                        id="job_position_display_title_4" placeholder="Position Display Title"
                                        value="{{ $headerSection->job_position_display_title_4 ?? '' }}"
                                        class="form-control" required />
                                </div>
                            </div>
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
    <div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat"
        data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
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
<script src="{{ asset('/assets/admin') }}/resume_header_sections.js?v={{ time() }}"></script>
@stop
