@extends('layouts.admin')

@section('header')
    @include('includes.adminHeader_nav')
@stop

@section('content')
    <!--begin::Toolbar-->
    <div class="py-3 toolbar py-lg-6" id="kt_toolbar">
        <div id="kt_toolbar_container" class="flex-wrap gap-2 container-fluid d-flex flex-stack">
            <div class="gap-2 py-2 page-title d-flex flex-column align-items-start me-3 py-lg-0">
                <h1 class="m-0 text-gray-900 d-flex fw-bold fs-3">{{ $pageTitle }}</h1>
                <ul class="text-gray-600 breadcrumb breadcrumb-dot fw-semibold fs-7">
                    <li class="text-gray-600 breadcrumb-item">
                        <a href="{{ '/' }}" class="text-gray-600 text-hover-primary">Dashboard</a>
                    </li>
                    <li class="text-gray-600 breadcrumb-item">{{ $pageTitle }}</li>
                    <li class="text-gray-500 breadcrumb-item">{{ @$subTitle }}</li>
                </ul>
            </div>
            <div class="gap-3 d-flex align-items-center"></div>
        </div>
    </div>
    <!--end::Toolbar-->

    <div class="py-4 card-body container-fluid" id="cards-container">

        @if (Session::has('flash_message_error'))
            <div class="p-6 border border-dashed rounded notice d-flex bg-light-danger border-warning mb-9">
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-bold">
                        <p class="text-gray-900 fw-bolder">{{ Session::get('flash_message_error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (Session::has('flash_message_success'))
            <div class="p-6 border border-dashed rounded notice d-flex bg-light-success border-success mb-9">
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-bold">
                        <p class="text-gray-900 fw-bolder">{{ Session::get('flash_message_success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="row row-cols-12 row-cols-md-12 row-cols-xl-12 g-5 g-xl-12">
            <div class="col-12">
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="pt-6 border-0 card-header">
                        <div class="card-title">
                            <div class="my-1 d-flex align-items-center position-relative">
                                <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                <input type="text" data-modules-listing-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-13" placeholder="Search Modules" />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end">
                                @if (validatePermissions('acl/module/add'))
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add">
                                        <i class="fa-solid fa-plus"></i> Add New Module
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="py-4 card-body">
                        <div class="table-responsive">
                            <table
                                class="table align-middle table-hover table-row-bordered table-striped table-row-gray-100 gs-0 gy-3"
                                id="data-modules-listing-table">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">Module Name</th>
                                        <th class="min-w-150px">Category</th>
                                        <th class="min-w-300px">Assigned Roles</th>
                                        <th class="text-center min-w-150px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($result)
                                        @foreach ($result as $row)
                                            <tr>
                                                <td>{{ $row->module_name }}</td>
                                                <td>{{ $row->category->category_name ?? '-' }}</td>
                                                <td>
                                                    @if ($row->roles && $row->roles->count())
                                                        @foreach ($row->roles as $role)
                                                            <span class="badge badge-light-success me-1">
                                                                {{ $role->role->role_name }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if (validatePermissions('acl/module/edit/{id}'))
                                                        <a href="javascript:void(0)" data-id="{{ $row->ID }}"
                                                            class="btn-edit btn-active-color-primary btn-sm me-1">
                                                            <i class="ki-duotone ki-pencil fs-2" style="color: #007bff">
                                                                <span class="path1"></span>
                                                                <span class="path2"></span>
                                                            </i>
                                                        </a>
                                                    @endif
                                                    @if (validatePermissions('acl/module/delete/{id}'))
                                                        <a href="javascript:void(0)" data-id="{{ $row->ID }}"
                                                            class="btn-del btn-active-color-primary btn-sm">
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
                            </table>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>
    </div>
@stop

@section('models')
    <div id="kt_drawer_chat" class="bg-body" data-kt-drawer-permanent="true" data-kt-drawer="true"
        data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">

        <div class="border-0 card w-100 rounded-0" id="kt_drawer_chat_messenger">
            <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#" class="mb-2 text-gray-900 fs-4 fw-bold drawer-title text-hover-primary me-1 lh-1">
                            Add New Module
                        </a>
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
            <div class="drawer-body"></div>
        </div>
    </div>
@endsection

@section('footer')
    @include('includes.adminFooter')
@stop

@section('script')
    @include('includes.adminScripts')

    <script type="text/javascript" src="{{ asset('/assets/admin/js/module.js') }}"></script>

    <script>
        $('input[data-kt-vendor-table-filter="search"]').on('input', function() {
            var searchQuery = $(this).val();
            $.ajax({
                url: admin_url + "/acl/module/search",
                type: 'GET',
                data: {
                    word: searchQuery
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.responseCode === 1) {
                        $('#cards-container').html(data.html);
                    } else {
                        console.log('No module found.');
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        });
    </script>
@stop
