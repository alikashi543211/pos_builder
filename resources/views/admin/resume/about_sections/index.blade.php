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

                        <!-- Display Picture -->
                        <div class="mb-8 row">
                            <label class="text-center col-lg-12 col-form-label fw-bold fs-6">Display Picture</label>
                            <div class="text-center col-lg-12">
                                <div class="image-input image-input-outline" data-kt-image-input="true">
                                    <div class="image-input-wrapper w-125px h-125px"
                                        style="background-image: url({{ $headerSection->display_picture ?? photo('empty.png', 'avatars') }})">
                                    </div>
                                    <label
                                        class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="display_picture" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="avatar_remove">
                                    </label>
                                    <span
                                        class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                                        data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            </div>
                        </div>

                        <div class="row">

                            <!-- Position Display Title -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Position Display Title</label>
                                <input type="text" name="display_position_title" id="display_position_title"
                                    placeholder="Enter Position Display Title"
                                    value="{{ $headerSection->display_position_title ?? '' }}" class="form-control"
                                    required />
                            </div>

                            <!-- Website -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Website</label>
                                <input type="url" name="website" placeholder="Enter website URL"
                                    value="{{ $headerSection->website ?? '' }}" class="form-control" />
                            </div>

                            <!-- Email Address -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Email Address</label>
                                <input type="email" name="email_address" placeholder="Enter email"
                                    value="{{ $headerSection->email_address ?? '' }}" class="form-control" required />
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Phone Number</label>
                                <input type="tel" name="phone_no" placeholder="Enter phone number"
                                    value="{{ $headerSection->phone_no ?? '' }}" class="form-control" />
                            </div>

                            <!-- Birthday -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Birthday</label>
                                <input type="date" name="birthday" placeholder="Enter birthday"
                                    value="{{ $headerSection->birthday ?? '' }}" class="form-control" />
                            </div>

                            <!-- Age -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Age</label>
                                <input type="number" name="age" placeholder="Enter age"
                                    value="{{ $headerSection->age ?? '' }}" class="form-control" />
                            </div>

                            <!-- City -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">City</label>
                                <input type="text" name="city" placeholder="Enter city"
                                    value="{{ $headerSection->city ?? '' }}" class="form-control" />
                            </div>

                            <!-- Degree -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Degree</label>
                                <input type="text" name="degree" placeholder="Enter degree"
                                    value="{{ $headerSection->degree ?? '' }}" class="form-control" />
                            </div>

                            <!-- Freelance -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Freelance</label>
                                <select name="freelance" class="form-control">
                                    <option value="Available"
                                        {{ $headerSection->freelance == 'Available' ? 'selected' : '' }}>Available</option>
                                    <option value="Not Available"
                                        {{ $headerSection->freelance == 'Not Available' ? 'selected' : '' }}>Not Available
                                    </option>
                                </select>
                            </div>
                            <!-- Footer Short Description -->
                            <div class="mb-8 col-md-12">
                                <label class="mb-2 fs-6 fw-semibold">Heading Short Description</label>
                                <textarea name="heading_short_description" rows="3" class="form-control"
                                    placeholder="Enter heading short description">{{ $headerSection->heading_short_description ?? '' }}</textarea>
                            </div>
                            <!-- Footer Short Description -->
                            <div class="mb-8 col-md-12">
                                <label class="mb-2 fs-6 fw-semibold">Footer Short Description</label>
                                <textarea name="footer_short_description" rows="3" class="form-control"
                                    placeholder="Enter footer short description">{{ $headerSection->footer_short_description ?? '' }}</textarea>
                            </div>

                        </div>
                        <div class="row">
                            <div class="mb-8 col-md-12">
                                <h3 class="alert alert-info">Job Section</h3>
                            </div>
                            <!-- Position Display Title -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 required fs-6 fw-semibold">Position Display Title</label>
                                <input type="text" name="job_display_position_title" id="job_display_position_title"
                                    placeholder="Enter Position Display Title"
                                    value="{{ $headerSection->job_display_position_title ?? '' }}" class="form-control"
                                    required />
                            </div>

                            <!-- Website -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Website</label>
                                <input type="url" name="job_website" placeholder="Enter website URL"
                                    value="{{ $headerSection->job_website ?? '' }}" class="form-control" />
                            </div>

                            <!-- Email Address -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Email Address</label>
                                <input type="email" name="job_email_address" placeholder="Enter email"
                                    value="{{ $headerSection->job_email_address ?? '' }}" class="form-control"
                                    required />
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Phone Number</label>
                                <input type="tel" name="job_phone_no" placeholder="Enter phone number"
                                    value="{{ $headerSection->job_phone_no ?? '' }}" class="form-control" />
                            </div>

                            <!-- Birthday -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Birthday</label>
                                <input type="date" name="job_birthday" placeholder="Enter birthday"
                                    value="{{ $headerSection->job_birthday ?? '' }}" class="form-control" />
                            </div>

                            <!-- Age -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Age</label>
                                <input type="number" name="job_age" placeholder="Enter age"
                                    value="{{ $headerSection->job_age ?? '' }}" class="form-control" />
                            </div>

                            <!-- City -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">City</label>
                                <input type="text" name="job_city" placeholder="Enter city"
                                    value="{{ $headerSection->job_city ?? '' }}" class="form-control" />
                            </div>

                            <!-- Degree -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Degree</label>
                                <input type="text" name="job_degree" placeholder="Enter degree"
                                    value="{{ $headerSection->job_degree ?? '' }}" class="form-control" />
                            </div>

                            <!-- Freelance -->
                            <div class="mb-8 col-md-6">
                                <label class="mb-2 fs-6 fw-semibold">Freelance</label>
                                <select name="job_freelance" class="form-control">
                                    <option value="Available"
                                        {{ $headerSection->job_freelance == 'Available' ? 'selected' : '' }}>Available
                                    </option>
                                    <option value="Not Available"
                                        {{ $headerSection->job_freelance == 'Not Available' ? 'selected' : '' }}>Not
                                        Available</option>
                                </select>
                            </div>
                            <!-- Footer Short Description -->
                            <div class="mb-8 col-md-12">
                                <label class="mb-2 fs-6 fw-semibold">Heading Short Description</label>
                                <textarea name="job_heading_short_description" rows="3" class="form-control"
                                    placeholder="Enter heading short description">{{ $headerSection->job_heading_short_description ?? '' }}</textarea>
                            </div>
                            <!-- Footer Short Description -->
                            <div class="mb-8 col-md-12">
                                <label class="mb-2 fs-6 fw-semibold">Footer Short Description</label>
                                <textarea name="job_footer_short_description" rows="3" class="form-control"
                                    placeholder="Enter footer short description">{{ $headerSection->job_footer_short_description ?? '' }}</textarea>
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
<script src="{{ asset('/assets/admin') }}/resume_about_sections.js?v={{ time() }}"></script>
@stop
