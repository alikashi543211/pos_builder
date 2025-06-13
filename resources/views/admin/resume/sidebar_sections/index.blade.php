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
                <!--end::Header-->
                <!--begin::Body-->
                <div class="py-3 card-body">
                    <form name="resume_header_sections" enctype="multipart/form-data" id="resume_header_sections_form"
                        method="post" action="">
                        @csrf
                        <input type="hidden" name="job_position_id" value="{{ $activeJobPosition->id ?? 0 }}">
                        <div class="mb-8 row">
                            <label class="text-center col-lg-12 col-form-label fw-bold fs-6">Display Picture</label>
                            <div class="text-center col-lg-12">
                                <div class="image-input image-input-outline" data-kt-image-input="true"
                                    style="background-image: url('')">
                                    <div class="image-input-wrapper w-125px h-125px"
                                        style="background-image: url({{ $headerSection->display_picture ?? photo('empty.png', 'avatars') }})">
                                    </div>
                                    <label
                                        class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="display_picture" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="avatar_remove">
                                    </label>
                                    <span
                                        class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Cancel avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span
                                        class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" title=""
                                        data-bs-original-title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Facebook Link</label>
                                <input type="text" name="facebook_link" id="facebook_link"
                                    placeholder="Enter Facebook link" value="{{ $headerSection->facebook_link ?? '' }}"
                                    class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Instagram Link</label>
                                <input type="text" name="instagram_link" id="instagram_link"
                                    placeholder="Enter Instagram link" value="{{ $headerSection->instagram_link ?? '' }}"
                                    class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Skype Link</label>
                                <input type="text" name="skype_link" id="skype_link" placeholder="Enter Skype link"
                                    value="{{ $headerSection->skype_link ?? '' }}" class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Youtube Link</label>
                                <input type="text" name="youtube_link" id="youtube_link" placeholder="Enter Youtube link"
                                    value="{{ $headerSection->youtube_link ?? '' }}" class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">LinkedIn Link</label>
                                <input type="text" name="linkedin_link" id="linkedin_link"
                                    placeholder="Enter LinkedIn link" value="{{ $headerSection->linkedin_link ?? '' }}"
                                    class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">GitHub Link</label>
                                <input type="text" name="github_link" id="github_link" placeholder="Enter GitHub link"
                                    value="{{ $headerSection->github_link ?? '' }}" class="form-control" required />
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-8 col-md-12">
                                <h3 class="alert alert-info">Job Section</h3>
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Facebook Link</label>
                                <input type="text" name="job_facebook_link" id="facebook_link"
                                    placeholder="Enter Facebook link"
                                    value="{{ $headerSection->job_facebook_link ?? '' }}" class="form-control"
                                    required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Instagram Link</label>
                                <input type="text" name="job_instagram_link" id="instagram_link"
                                    placeholder="Enter Instagram link"
                                    value="{{ $headerSection->job_instagram_link ?? '' }}" class="form-control"
                                    required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Skype Link</label>
                                <input type="text" name="job_skype_link" id="skype_link"
                                    placeholder="Enter Skype link" value="{{ $headerSection->job_skype_link ?? '' }}"
                                    class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Youtube Link</label>
                                <input type="text" name="job_youtube_link" id="youtube_link"
                                    placeholder="Enter Youtube link" value="{{ $headerSection->job_youtube_link ?? '' }}"
                                    class="form-control" required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">LinkedIn Link</label>
                                <input type="text" name="job_linkedin_link" id="linkedin_link"
                                    placeholder="Enter LinkedIn link"
                                    value="{{ $headerSection->job_linkedin_link ?? '' }}" class="form-control"
                                    required />
                            </div>
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">GitHub Link</label>
                                <input type="text" name="job_github_link" id="job_github_link"
                                    placeholder="Enter GitHub link" value="{{ $headerSection->job_github_link ?? '' }}"
                                    class="form-control" required />
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
<script src="{{ asset('/assets/admin') }}/resume_sidebar_sections.js?v={{ time() }}"></script>
@stop
