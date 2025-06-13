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
    <div class="row add-module-btn-div">
        @if (validatePermissions('acl/module/add'))
            <div class="col-6 add-new-btn">
                <a href="javascript:void(0)" class="btn btn-primary btn-sm btn-add border-anchor">
                    <i class="ki-duotone ki-plus fs-2"></i>Add New Module
                </a>
            </div>
        @endif

        @if (validatePermissions('acl/module/search'))
            <div class="col-6">
                <div class="d-flex align-items-center position-relative my-1">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    <input type="text" data-kt-vendor-table-filter="search" class="form-control form-control-solid w-250px ps-13"
                        placeholder="Search Modules">
                </div>
            </div>
        @endif
    </div>

    <div class="card-body py-4">
        @if (Session::has('flash_message_error'))
            <div class="notice d-flex bg-light-danger rounded border-warning border border-dashed mb-9 p-6">
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-bold">
                        <p class="text-gray-900 fw-bolder">{{ Session::get('flash_message_error') }}</p>
                    </div>
                </div>
            </div>
        @endif
        @if (Session::has('flash_message_success'))
            <div class="notice d-flex bg-light-success rounded border-success border border-dashed mb-9 p-6">
                <div class="d-flex flex-stack flex-grow-1">
                    <div class="fw-bold">
                        <p class="text-gray-900 fw-bolder">{{ Session::get('flash_message_success') }}</p>
                    </div>
                </div>
            </div>
        @endif
        {{-- <div class="fw-bolder fs-3 text-gray-600 text-hover-primary">Add New System Module</div> --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-6 g-5 g-xl-9 container-fluid" id="cards-container">
            @if ($result)
                @foreach ($result as $row)
                    <div class="col-md-3">
                        <!--begin::Card-->
                        <div class="card card-flush h-md-100">
                            <!--begin::Card header-->
                            <div class="card-header">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>{{ $row->module_name }}</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-1">
                                <!--begin::Users-->
                                <div class="fw-bolder text-gray-600 mb-5">Category: {{ $row->category->category_name }}

                                </div>
                                <div class="fw-bolder text-gray-600 mb-5">Slug: {{ $row->route }}

                                </div>
                                <!--end::Users-->
                                <!--begin::Permissions-->
                                <div class="d-flex flex-column text-gray-600">
                                    <div class="d-flex align-items-center py-2">
                                        <strong>Assgined To:</strong>
                                    </div>
                                    @if ($row->roles)
                                        @foreach ($row->roles->take(4) as $roles)
                                            <div class="d-flex align-items-center py-2">
                                                <span class="bullet bg-primary me-3"></span>{{ $roles->role->role_name }}
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
                                        class="btn btn-light btn-active-light-primary btn-edit my-1">Edit
                                        Module</button>
                                </a>

                                <button type="button" class="btn btn-light btn-active-light-primary my-1 btn-del"
                                    data-id="{{ $row->ID }}">Delete Module</button>

                            </div>
                            <!--end::Card footer-->
                        </div>
                        <!--end::Card-->
                    </div>
                @endforeach
            @endif
        </div>

    </div>

@stop
@section('models')
    <div id="kt_drawer_chat" class="bg-body" data-kt-drawer-permanent="true" data-kt-drawer="true"
        data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
        <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
            <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#"
                            class="fs-4 fw-bold  drawer-title text-gray-900 text-hover-primary me-1 mb-2 lh-1">Add New
                            Module</a>
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
                    // Parse the JSON response
                    var data = JSON.parse(response);

                    if (data.responseCode === 1) {
                        // Replace the HTML of the target container with the new HTML
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
