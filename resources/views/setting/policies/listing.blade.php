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
            <div class="card mb-5 mb-xl-8">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">

                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" data-category-listing-table-filter="search"
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search Policies" />
                    </div>
                    @if (validatePermissions('policies/add'))
                        <div class="card-toolbar">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i
                                    class="fa-solid fa-plus"></i> Add New Policy</a>

                        </div>
                    @endif
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table id="policy-listing-table"
                            class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">Policy Title</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-150px">Created Date</th>
                                    <th class="min-w-80px text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($result)
                                    @foreach ($result as $row)
                                        <tr>

                                            <td> {{ $row->policy_title ?? '---' }}</td>
                                            <td>
                                                <a title="Change  Status" data-id="{{ base64_encode($row->id) }}" class="btn-status-change" href="javascript:void(0)">
                                                    {!!$row->status=='Active' ?
                                                    '<span class="badge badge-pill badge-success">Active</span>' :
                                                    '<span class="badge  badge-pill badge-danger">In-Active</span>'!!}
                                                </a>
                                            </td>
                                            <td>
                                                {{ dateFormat($row->created_at, 'd-M-Y H:i') ?? '---' }}
                                            </td>



                                            <td class="text-center">

                                                @if (validatePermissions('banners/edit/{id}'))
                                                <a href="javascript:void(0)" data-id="{{ base64_encode($row->id) }}"
                                                    class="btn-edit btn-active-color-primary btn-sm me-1">
                                                    <i class="ki-duotone ki-pencil fs-2" style="color: #007bff">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                                @endif
                                                @if (validatePermissions('banners/delete/{id}'))
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
    <div id="kt_activities" style="width:80% !important" data-kt-drawer-permanent="true"  class="bg-body" data-kt-drawer="true"
    data-kt-drawer-name="activities" data-kt-drawer-activate="true" data-kt-drawer-overlay="true"
    data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_activities_toggle"
        data-kt-drawer-close="#kt_activities_close">


        <div class="card shadow-none border-0 rounded-0 w-100">
            <!--begin::Header-->
            <div class="card-header" id="kt_activities_header">
                <h3 class="card-title drawer-title fw-bold text-gray-900"></h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-icon btn-active-light-primary me-n5"
                        id="kt_activities_close">
                        <i class="ki-duotone ki-cross-square fs-2">
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
@stop
@section('footer')
@include('includes.adminFooter')
@stop
@section('script')
@include('includes.adminScripts')
<script src="{{asset('/')}}assets/plugins/custom/tinymce/tinymce.min.js"></script>
<script src="{{ asset('/assets/admin') }}/policies.js?v={{ time() }}"></script>

@stop
