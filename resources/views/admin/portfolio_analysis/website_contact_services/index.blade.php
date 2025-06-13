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
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search Contact Services" />
                    </div>
                    <div class="card-toolbar">
                        @if (validatePermissions('portfolio-analysis/website-contact-services/add'))
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i
                                    class="fa-solid fa-plus"></i> Add New Contact Service</a>
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
                                    <th class="no-wrap">Service Name</th>
                                    <th class="no-wrap">Total Messages</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-120px">Sorting</th>
                                    <th class="min-w-80px text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($contactServices) && count($contactServices) > 0)
                                    @foreach ($contactServices as $row)
                                        <tr>
                                            <td>
                                                {{ date('d-F-Y g:i A', strtotime($row->created_at)) ?? '-----' }}
                                            </td>
                                            <td>
                                                {{ $row->service_name ?? '-----' }}
                                            </td>
                                            <td>
                                                {{ count($row->contactMessages) ?? '-----' }}
                                            </td>

                                            <td>
                                                <span title="Change Service Status"
                                                    class="badge {{ $row->is_active == 1 ? 'badge-light-success active-status' : 'badge-light-danger inactive-status' }}"
                                                    data-uid="{{ $row->id }}" style="cursor: pointer;">
                                                    {{ $row->is_active == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <input data-live-link-id="{{ $row->id }}" type="text"
                                                    class="form-control text-center number_input_only sort_number_input"
                                                    style="width: 50px;" value="{{ $row->sort_number ?? '' }}">
                                            </td>
                                            <td class="text-center">
                                                @if (validatePermissions('portfolio-analysis/website-contact-services/edit/{id}'))
                                                    <a href="javascript:void(0)" data-id="{{ $row->id }}"
                                                        class=" btn-edit btn-active-color-primary btn-sm me-1">
                                                        <i class="ki-duotone ki-pencil fs-2" style="color: #007bff">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </a>
                                                @endif
                                                @if (validatePermissions('portfolio-analysis/website-contact-services/delete/{id}'))
                                                    <a href="javascript:void(0)" data-id="{{ $row->id }}"
                                                        class="btn-delete btn-active-color-primary btn-sm">
                                                        <i class="ki-duotone ki-trash fs-2" style="color: red">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
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
                            Service</a>
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
<script src="{{ asset('/assets/admin') }}/resume_website_contact_services.js?v={{ time() }}"></script>
@stop
