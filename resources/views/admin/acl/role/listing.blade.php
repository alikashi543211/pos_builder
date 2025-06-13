@extends('layouts.admin')
@push('title')
{{$pageTitle}} - {{Config::get('global.SITE_NAME') }}
 @endpush
@section('header')
    @include('includes.adminHeader_nav')
@stop
@section('toolbar')
    @include('includes.toolbar')
@stop
@section('content')

    <div class="row add-btn-div">
        @if (validatePermissions('acl/role/add'))
            <div class="col-6 add-new-btn">
                <a href="javascript:void(0)" class="btn btn-primary btn-sm btn-add border-anchor">
                    <i class="ki-duotone ki-plus fs-2"></i>Add New Role
                </a>
            </div>
        @endif
        @if (validatePermissions('acl/role/search'))
            <div class="col-6">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-vendor-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search Roles">
                </div>
            </div>
        @endif
    </div>

    <div class="card-body py-4 ">
        @if (Session::has('flash_message_error'))
            <div class="notice d-flex bg-light-danger rounded border-warning border border-dashed mb-9 p-6">
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-bold">
                        <p class="text-gray-900 fw-bolder">{{ Session::get('flash_message_error') }}</p>
                    </div>
                </div>
            </div>
            @endif @if (Session::has('flash_message_success'))
                <div class="notice d-flex bg-light-success rounded border-success border border-dashed mb-9 p-6">
                    <div class="d-flex flex-stack flex-grow-1">
                        <div class="fw-bold">
                            <p class="text-gray-900 fw-bolder">{{ Session::get('flash_message_success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9 container-fluid" id="cards-container">
                @if ($result)
                    @foreach ($result as $row)
                        <div class="col-md-4">
                            <!--begin::Card-->
                            <div class="card card-flush h-md-100">
                                <!--begin::Card header-->
                                <div class="card-header">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>{{ $row->role_name }}</h2>
                                    </div>
                                    <!--end::Card title-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-1">
                                    <!--begin::Users-->
                                    <div class="fw-bolder text-gray-600 mb-5">Total modules assign to this role:
                                        {{ @$row->modules->count() }}</div>

                                    <div class="d-flex flex-column text-gray-600">
                                        <div class="d-flex align-items-center py-2">
                                            <strong>Modules:</strong>
                                        </div>
                                        @if ($row->modules)
                                            @foreach ($row->modules->take(4) as $modules)
                                                <div class="d-flex align-items-center py-2">
                                                    <span
                                                        class="bullet bg-primary me-3"></span>{{ $modules->module->module_name }}
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <!--end::Permissions-->
                                </div>
                                <!--end::Card body-->
                                <!--begin::Card footer-->
                                <div class="card-footer flex-wrap pt-0">
                                    <a href="javascript:void(0)">
                                        <button type="button" data-id="{{ $row->ID }}"
                                            class="btn btn-light btn-edit  btn-active-light-primary my-1">Edit
                                            Role</button>
                                    </a>

                                    <button type="button" class="btn btn-light btn-active-light-primary my-1 btn-del"
                                        data-id="{{ $row->ID }}">Delete Role</button>

                                </div>
                                <!--end::Card footer-->
                            </div>
                            <!--end::Card-->
                        </div>
                    @endforeach
                @endif
            </div>
            <!--begin::Modal - Add role-->

            <!--end::Modal - Add role-->


    </div> <!-- end page content-->
@stop
@section('models')
    <div id="kt_activities" class="bg-body" data-kt-drawer-permanent="true" data-kt-drawer="true" data-kt-drawer-permanent="true"
        data-kt-drawer-name="activities" data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'lg': '900px'}" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">
        <div class="card shadow-none border-0 rounded-0 w-100">
            <!--begin::Header-->
            <div class="card-header" id="kt_activities_header">
                <h3 class="card-title drawer-title fw-bold text-gray-900">Edit Role</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5"
                        id="kt_activities_close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </button>
                </div>
            </div>

            <div class="drawer-body">

            </div>



        </div>
    </div>
@endsection
@section('footer')
    @include('includes.adminFooter')
@stop

@section('script')
    @include('includes.adminScripts')
    <script type="text/javascript" src="{{ asset('/assets/admin/js/role.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', '.btn-del', function(e) {
            var did = $(this).data("id");
            console.log('did', did)
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                animation: !1,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    window.location.href = admin_url + "/acl/role/delete/" + did;
                }
            })

        });

        $('input[data-kt-vendor-table-filter="search"]').on('input', function() {
            var searchQuery = $(this).val();

            $.ajax({
                url: admin_url + "/acl/role/search",
                type: 'GET',
                data: {
                    word: searchQuery
                },
                success: function(response) {
                    // Parse the JSON response
                    var data = JSON.parse(response);

                    if (data.responseCode === 1) {
                        // Replace the HTML of the target container with the new HTML
                        $('#cards-container').html(data.html);
                    } else {
                        console.log('No roles found.');
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr.responseText);
                }
            });
        });
    </script>
@stop
