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
            <div class="mb-5 card mb-xl-8">

                <!--begin::Header-->
                <div class="pt-5 border-0 card-header">
                    <div class="my-1 d-flex align-items-center position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-user-listing-table-filter="search"
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search service" />
                    </div>
                    <div class="card-toolbar">
                        @if (validatePermissions('resume/service-sections/add'))
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i
                                    class="fa-solid fa-plus"></i> Add New Service</a>
                        @endif
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="py-3 card-body">
                    <div class="table-responsive">
                        <table id="datatableusr"
                            class="table align-middle table-hover table-row-bordered table-row-gray-100 gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-120px">Thumbnail</th>
                                    <th class="min-w-120px">Service Name</th>
                                    <th class="min-w-120px">Service Slug</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-120px">Sorting</th>
                                    <th class="text-center min-w-80px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($projects)
                                    @foreach ($projects as $row)
                                        <tr>
                                            <td>
                                                <img src="{{ $row->service_thumbnail }}" width="60" height="50"
                                                    alt="">
                                            </td>
                                            <td>
                                                {{ $row->service_name ?? '-----' }}
                                            </td>
                                            <td>
                                                {{ $row->service_slug ?? '-----' }}
                                            </td>

                                            <td>
                                                <span title="Change project Status"
                                                    class="badge {{ $row->is_active == 1 ? 'badge-light-success active-status' : 'badge-light-danger inactive-status' }}"
                                                    data-uid="{{ $row->id }}" style="cursor: pointer;">
                                                    {{ $row->is_active == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <input data-project-id="{{ $row->id }}" type="text"
                                                    class="text-center form-control number_input_only sort_number_input"
                                                    style="width: 50px;" value="{{ $row->sort_number ?? '' }}">
                                            </td>
                                            <td class="text-center">
                                                @if (validatePermissions('resume/service-sections/descriptions/add/{id}'))
                                                    <a title="Add Multiple Descriptions" href="javascript:void(0)"
                                                        data-id="{{ $row->id }}"
                                                        class="btn-add-descriptions btn-active-color-primary btn-sm me-1">
                                                        <i class="ki-duotone ki-file fs-2" style="color: purple;">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </a>
                                                @endif
                                                @if (validatePermissions('resume/service-sections/images/add/{id}'))
                                                    <a title="Add Multiple Images" href="javascript:void(0)"
                                                        data-id="{{ $row->id }}"
                                                        class="btn-add-images btn-active-color-primary btn-sm me-1">
                                                        <i class="ki-duotone ki-book-square fs-2" style="color:brown;">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                        </i>
                                                    </a>
                                                @endif

                                                {{-- @if (validatePermissions('resume/service-sections/detail/{id}'))
                                                <a  href="javascript:void(0)" data-id="{{ $row->id }}" data-url="/suppliers/product-detail/{{ $row->id }}" title="View Project Detail"
                                                    class="btn-edit btn-active-color-primary btn-sm me-1" >
                                                    <i class="ki-duotone fs-2 ki-eye " style="color:#003242">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    </i>
                                                </a>
                                            @endif --}}
                                                @if (validatePermissions('resume/service-sections/edit/{id}'))
                                                    <a href="javascript:void(0)" data-id="{{ $row->id }}"
                                                        class=" btn-edit btn-active-color-primary btn-sm me-1">
                                                        <i class="ki-duotone ki-pencil fs-2" style="color: #007bff">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </a>
                                                @endif

                                                @if (validatePermissions('resume/service-sections/delete/{id}'))
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
        <div class="border-0 card w-100 rounded-0" id="kt_drawer_chat_messenger">
            <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#"
                            class="mb-2 text-gray-900 fs-4 fw-bold drawer-title text-hover-primary me-1 lh-1">Add New
                            project</a>
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
<script src="{{ asset('/assets/admin') }}/resume_service_sections.js?v={{ time() }}"></script>
@stop
