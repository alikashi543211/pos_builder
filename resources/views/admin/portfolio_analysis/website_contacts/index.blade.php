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
            <div class="card mb-5 mb-xl-8">

                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-user-listing-table-filter="search"
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search Contacts" />
                    </div>
                    <div class="card-toolbar">
                        @if (validatePermissions('portfolio-analysis/website-contacts'))
                            {{-- <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i
                                    class="fa-solid fa-plus"></i> Add New Testimonial</a> --}}
                        @endif
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table id="datatableusr"
                            class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="no-wrap">Date</th>
                                    <th class="no-wrap">Name</th>
                                    <th class="no-wrap">Email</th>
                                    <th class="no-wrap">Subject </th>
                                    <th class="no-wrap">Service </th>
                                    <th class="min-w-100px text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($websiteContacts) && count($websiteContacts) > 0)
                                    @foreach ($websiteContacts as $row)
                                        <tr>
                                            <td>
                                                {{ date('d-F-Y g:i A', strtotime($row->created_at)) ?? '-----' }}
                                            </td>
                                            <td>
                                                {{ $row->contact_name ?? '-----' }}
                                            </td>
                                            <td>
                                                {{ $row->websiteContactEmail->contact_email ?? '-----' }}
                                            </td>
                                            <td>
                                                {{ $row->contact_subject ?? '-----' }}
                                            </td>
                                            <td>
                                                {{ $row->contactService->service_name ?? '-----' }}
                                            </td>
                                            <td class="text-center">
                                                @if (validatePermissions('portfolio-analysis/website-contacts/detail/{id}'))
                                                    <a href="javascript:void(0)" data-id="{{ $row->id }}"
                                                        title="View Contact Message"
                                                        class="btn-active-color-primary btn-sm me-1 btn-detail">

                                                        <i class="ki-duotone fs-2 ki-eye" style="color: #003242;">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                            <!--end::Table body-->
                        </table>
                        <!--end::Table-->
                    </div>
                    <!--end::Table container-->
                </div>
                <!--begin::Body-->
            </div>

        </div>

    </div>

@section('models')
    <div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true"
        data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-permanent="true"
        data-kt-drawer-close="#kt_drawer_chat_close">
        <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
            <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#"
                            class="fs-4 fw-bold  drawer-title text-gray-900 text-hover-primary me-1 mb-2 lh-1">Add New
                            Skill</a>
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
<script src="{{ asset('/assets/admin') }}/resume_website_contacts.js?v={{ time() }}"></script>
@stop
