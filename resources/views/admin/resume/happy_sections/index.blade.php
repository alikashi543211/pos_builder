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
                    <form name="resume_header_sections" enctype="multipart/form-data" id="resume_header_sections_form" method="post" action="">
                        @csrf
                        <input type="hidden" name="job_position_id" value="{{ $activeJobPosition->id ?? 0 }}">

                        <!-- Card Sections -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="py-5 mb-8 row" style="border:2px solid rgb(245, 245, 245);border-radius:10px;margin:3px;">
                                    <h3 class="mb-3 text-left">Section 1</h3>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Count</label>
                                        <input type="text" value="{{ $headerSection->one_count ?? '' }}" name="one_count" class="mb-2 form-control" placeholder="Enter count">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Heading</label>
                                        <input type="text" value="{{ $headerSection->one_heading ?? '' }}" name="one_heading" class="mb-2 form-control" placeholder="Enter heading">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Subheading</label>
                                        <input type="text" value="{{ $headerSection->one_subheading ?? '' }}" name="one_subheading" class="mb-2 form-control" placeholder="Enter subheading">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Icon</label>
                                        <input type="text" value="{{ $headerSection->one_icon ?? '' }}" name="one_icon" class="mb-2 form-control" placeholder="Enter icon class">
                                    </div>
                                </div>

                                <div class="py-5 mb-8 row" style="border:2px solid rgb(245, 245, 245);border-radius:10px;margin:3px;">
                                    <h3 class="mb-3 text-left">Section 3</h3>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Count</label>
                                        <input type="text" value="{{ $headerSection->three_count ?? '' }}" name="three_count" class="mb-2 form-control" placeholder="Enter count">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Heading</label>
                                        <input type="text" value="{{ $headerSection->three_heading ?? '' }}" name="three_heading" class="mb-2 form-control" placeholder="Enter heading">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Subheading</label>
                                        <input type="text" value="{{ $headerSection->three_subheading ?? '' }}" name="three_subheading" class="mb-2 form-control" placeholder="Enter subheading">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Icon</label>
                                        <input type="text" value="{{ $headerSection->three_icon ?? '' }}" name="three_icon" class="mb-2 form-control" placeholder="Enter icon class">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="py-5 mb-8 row" style="border:2px solid rgb(245, 245, 245);border-radius:10px;margin:3px;">
                                    <h3 class="mb-3 text-left">Section 2</h3>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Count</label>
                                        <input type="text" value="{{ $headerSection->two_count ?? '' }}" name="two_count" class="mb-2 form-control" placeholder="Enter count">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Heading</label>
                                        <input type="text" value="{{ $headerSection->two_heading ?? '' }}" name="two_heading" class="mb-2 form-control" placeholder="Enter heading">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Subheading</label>
                                        <input type="text" value="{{ $headerSection->two_subheading ?? '' }}" name="two_subheading" class="mb-2 form-control" placeholder="Enter subheading">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Icon</label>
                                        <input type="text" value="{{ $headerSection->two_icon ?? '' }}" name="two_icon" class="mb-2 form-control" placeholder="Enter icon class">
                                    </div>
                                </div>

                                <div class="py-5 mb-8 row" style="border:2px solid rgb(245, 245, 245);border-radius:10px;margin:3px;">
                                    <h3 class="mb-3 text-left">Section 4</h3>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Count</label>
                                        <input type="text" value="{{ $headerSection->four_count ?? '' }}" name="four_count" class="mb-2 form-control" placeholder="Enter count">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Heading</label>
                                        <input type="text" value="{{ $headerSection->four_heading ?? '' }}" name="four_heading" class="mb-2 form-control" placeholder="Enter heading">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Subheading</label>
                                        <input type="text" value="{{ $headerSection->four_subheading ?? '' }}" name="four_subheading" class="mb-2 form-control" placeholder="Enter subheading">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="mb-2 fs-6 fw-semibold required">Icon</label>
                                        <input type="text" value="{{ $headerSection->four_icon ?? '' }}" name="four_icon" class="mb-2 form-control" placeholder="Enter icon class">
                                    </div>
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
<script src="{{ asset('/assets/admin') }}/resume_happy_sections.js?v={{ time() }}"></script>
@stop
