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
    <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-fluid">
        <!--begin::Post-->
        <div class="content flex-row-fluid" id="kt_content">
            <div class="card mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold fs-3 mb-1">Line Items</span>
                        <span class="text-muted mt-1 fw-semibold fs-7">Over {{ $result->count() }} items</span>
                    </h3>
                    @if (validatePermissions('language/add'))
                        <div class="card-toolbar">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i
                                    class="fa-solid fa-plus"></i>Add Language</a>

                        </div>
                    @endif
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">Created Date</th>
                                    <th class="min-w-150px">language Title</th>
                                    <th class="min-w-150px">language Short Code</th>
                                    <th class="min-w-100px">Created By</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-80px text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($result && count($result) > 0)
                                    @foreach ($result as $row)
                                        <tr>
                                            <td>
                                                {{ dateFormat($row->created_at, 'd-M-Y') }}
                                            </td>
                                            <td>
                                                {{ $row->name }}
                                            </td>
                                            <td>
                                                {{ $row->short_code }}
                                            </td>

                                            <td>
                                                {{ @$row->employee->first_name }} {{ @$row->employee->last_name }}
                                            </td>

                                            <td>
                                                @if ($row->status == 'Active')
                                                    <div class="badge badge-success btn-status-change "
                                                        data-id="{{ base64_encode($row->id) }}">
                                                        <a title="Change Status" class="badge-success"
                                                            href="javascript:void(0)">Active
                                                        </a>
                                                    </div>
                                                @else
                                                    <div style="color: white !important"
                                                        class="badge btn-status-change badge-primary"
                                                        data-id="{{ base64_encode($row->id) }}">
                                                        <a title="Change Status" class="badge-primary"
                                                            href="javascript:void(0)"> In Active
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center">

                                                @if (validatePermissions('language/edit/{id}'))
                                                    <a href="javascript:void(0)" data-id="{{ base64_encode($row->id) }}"
                                                        class="btn-edit btn-active-color-primary btn-sm me-1">
                                                        <i class="ki-duotone ki-pencil fs-2" style="color: #007bff">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </a>
                                                @endif
                                                @if (validatePermissions('language/delete'))
                                                    <a href="javascript:void(0)" data-id="{{ base64_encode($row->id) }}"
                                                        class=" btn-del btn-active-color-primary btn-sm">
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
                                @else
                                    <tr>

                                        <td colspan="8" class="text-center">
                                            @if (validatePermissions('language/add'))
                                                <a href="javascript:void(0)" class="btn btn-sm  me-3 btn-add"><i
                                                        class="fa-solid fa-plus"></i>Add language</a>
                                            @else
                                                No data available
                                            @endif
                                        </td>

                                    </tr>
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
        <!--end::Post-->
    </div>

@section('models')
    <div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true"
        data-kt-drawer-overlay="true" data-kt-drawer-permanent="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
        <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
            <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a href="#"
                            class="fs-4 fw-bold  drawer-title text-gray-900 text-hover-primary me-1 mb-2 lh-1">Add New
                            User</a>
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

<script src="{{ asset('/assets/admin') }}/language.js"></script>


@stop
