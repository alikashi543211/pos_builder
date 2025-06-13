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
            <div class="gap-3 d-flex align-items-center">

            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <div class="py-4 card-body" id="cards-container">

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

        <div class="row row-cols-12 g-5 container-fluid">
            <form class="w-100" name="fform" enctype="multipart/form-data" id="fform" method="post" action="">
                @csrf
                <input type="hidden" name="act" id="act" value="edit">
                <input type="hidden" name="eid" id="eid" value="{{ $row->ID }}">

                <div class="row g-5">
                    <div class="col-12">
                        <div class="card card-flush">
                            <div class="card-body">
                                <div class="mb-5">
                                    <label class="mb-2 required fs-6 fw-semibold">Role Name</label>
                                    <input type="text" name="role_name" placeholder="Role Name" id="role_name"
                                        value="{{ $row->role_name }}" class="form-control" required />
                                </div>

                                <label class="mb-4 fs-5 fw-bolder form-label">Role Permissions</label>
                                <div class="row g-4">
                                    @if ($catResult)
                                        @foreach ($catResult as $catRow)
                                            @php
                                                $result = App\Models\Acl\ModuleModel::where(
                                                    'module_category_ID',
                                                    $catRow->ID,
                                                )
                                                    ->orderBy('display_order')
                                                    ->get();
                                            @endphp

                                            @if (!$result->isEmpty())
                                                <div class="col-12">
                                                    <div class="shadow-sm card">
                                                        <div class="card-header bg-light-primary"
                                                            style="align-items:center;min-height:35px;">
                                                            <h5 class="mb-0 text-dark">{{ $catRow->category_name }}</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                @foreach ($result as $rowModule)
                                                                    @php
                                                                        $checked = '';
                                                                        if ($row->Permissions) {
                                                                            foreach (
                                                                                $row->Permissions
                                                                                as $rowPermission
                                                                            ) {
                                                                                if (
                                                                                    $rowPermission->module_ID ==
                                                                                    $rowModule->ID
                                                                                ) {
                                                                                    $checked = 'checked';
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <div class="mb-2 col-md-4">
                                                                        <div
                                                                            class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                name="access[{{ $rowModule->ID }}]"
                                                                                {{ $checked }}>
                                                                            <label class="form-check-label">
                                                                                {{ $rowModule->module_name }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <a href="{{ url('acl/role') }}">
                                    <button class="btn btn-light me-5" type="button" data-kt-element="cancel">Go
                                        Back</button>
                                </a>
                                <button type="submit" class="btn btn-primary btn-save">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('models')
    <div id="kt_activities" class="bg-body" data-kt-drawer-permanent="true" data-kt-drawer="true"
        data-kt-drawer-name="activities" data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
        data-kt-drawer-width="{default:'300px', 'lg': '900px'}" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_activities_toggle" data-kt-drawer-close="#kt_activities_close">

        <div class="border-0 shadow-none card rounded-0 w-100">
            <!--begin::Header-->
            <div class="card-header" id="kt_activities_header">
                <h3 class="text-gray-900 card-title drawer-title fw-bold">Edit Role</h3>
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
                {{-- Dynamic Content Loaded Here --}}
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
@stop
