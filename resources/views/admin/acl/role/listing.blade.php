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
                        <a href="{{ url('/') }}" class="text-gray-600 text-hover-primary">Dashboard</a>
                    </li>
                    <li class="text-gray-600 breadcrumb-item">{{ $pageTitle }}</li>
                    <li class="text-gray-500 breadcrumb-item">{{ @$subTitle }}</li>
                </ul>
            </div>

            <div class="gap-3 d-flex align-items-center">
                @if (validatePermissions('acl/role/search'))
                    <div class="my-1 d-flex align-items-center position-relative">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input type="text" data-kt-vendor-table-filter="search"
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search Role">
                    </div>
                @endif

                @if (validatePermissions('acl/role/add'))
                    <a href="javascript:void(0)" class="btn btn-sm btn-add btn-primary me-3">
                        <i class="fa-solid fa-plus"></i> Add New Role
                    </a>
                @endif
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!-- Flash Messages -->
    <div class="py-4 card-body" id="cards-container">
        @if (Session::has('flash_message_error'))
            <div class="p-6 border-dashed rounded notice d-flex bg-light-danger border-warning mb-9">
                <div class="text-gray-900 fw-bold fw-bolder">{{ Session::get('flash_message_error') }}</div>
            </div>
        @endif

        @if (Session::has('flash_message_success'))
            <div class="p-6 border-dashed rounded notice d-flex bg-light-success border-success mb-9">
                <div class="text-gray-900 fw-bold fw-bolder">{{ Session::get('flash_message_success') }}</div>
            </div>
        @endif

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9 container-fluid">
            @foreach ($result as $row)
                <div class="col-md-4">
                    <div class="card card-flush h-md-100">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{ $row->role_name }}</h2>
                            </div>
                        </div>

                        <div class="pt-1 card-body">
                            <div class="mb-5 text-gray-600 fw-bolder">
                                Total users with this role: {{ @$row->modules->count() }}
                            </div>

                            <div class="text-gray-600 d-flex flex-column">
                                <div class="py-2 d-flex align-items-center"><strong>Modules:</strong></div>
                                @foreach ($row->modules->take(4) as $modules)
                                    <div class="py-2 d-flex align-items-center">
                                        <span class="bullet bg-primary me-3"></span>{{ $modules->module->module_name }}
                                    </div>
                                @endforeach
                                @if ($row->modules->count() > 4)
                                    <div class="py-2 d-flex align-items-center">
                                        <span class="bullet bg-primary me-3"></span><em>and more...</em>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex-wrap pt-0 card-footer">
                            <a href="{{ url('acl/role/edit/' . $row->ID) }}">
                                <button type="button" data-id="{{ $row->ID }}"
                                    class="my-1 btn btn-light btn-active-light-primary">
                                    Edit Role
                                </button>
                            </a>
                            <button type="button" class="my-1 btn btn-light btn-active-light-primary btn-del"
                                data-id="{{ $row->ID }}">
                                Delete Role
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop

@section('models')
    <!-- Drawer -->
    <div id="kt_activities" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="activities"
        data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'lg': '900px'}"
        data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_activities_toggle"
        data-kt-drawer-close="#kt_activities_close">

        <div class="border-0 shadow-none card rounded-0 w-100">
            <div class="card-header" id="kt_activities_header">
                <h3 class="text-gray-900 card-title drawer-title fw-bold">Edit Role</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5"
                        id="kt_activities_close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </button>
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
    <script src="{{ asset('/assets/admin/js/role.js') }}"></script>

    <script>
        // Delete Role
        $(document).on('click', '.btn-del', function() {
            var did = $(this).data("id");

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = admin_url + "/acl/role/delete/" + did;
                }
            });
        });

        // Search Role
        $('input[data-kt-vendor-table-filter="search"]').on('input', function() {
            var searchQuery = $(this).val();

            $.ajax({
                url: admin_url + "/acl/role/search",
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
