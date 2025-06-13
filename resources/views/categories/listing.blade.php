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
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search Category" />
                    </div>
                    @if (validatePermissions('category/add'))
                        <div class="card-toolbar">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i
                                    class="fa-solid fa-plus"></i> Add Category</a>

                        </div>
                    @endif
                </div>
                <!--end::Header-->
                <!--begin::Body-->
                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table id="category-listing-table"
                            class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-150px">Name</th>
                                    {{-- <th class="min-w-150px">Title</th> --}}
                                    <th class="min-w-150px">Parent Category</th>
                                    <th class="min-w-100px">Meta Title</th>
                                    <th class="min-w-100px">Page URL</th>
                                    <th class="min-w-100px">Is Clickable</th>
                                    <th class="min-w-100px">Created by</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-150px">Created Date</th>
                                    <th class="min-w-80px text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($result)
                                    @foreach ($result as $row)
                                        <tr>

                                            <td> {{ $row->category_name ?? '---' }}</td>
                                            {{-- <td> {{ $row->category_title ?? '---' }}</td> --}}
                                            <td> {{ $row->ParentCategoryEnglish->category_name ?? '---' }}</td>
                                            <td> {{ $row->meta_title ?? '--' }}</td>
                                            <td> {{ $row->page_url ?? '--' }}</td>
                                            <td class="min-w-100px">
                                                @if($row->is_clickable == 1)
                                                    <span class="badge badge-pill badge-light-success">Yes</span>
                                                @else
                                                    <span class="badge  badge-pill badge-light-danger">No</span>
                                                @endif
                                            </td>
                                            <td> {{ $row->category->employee->first_name ?? '---' }} {{ $row->category->employee->last_name ??  '---' }} </td>
                                            <td>
                                                @if ($row->category->status == 1)
                                                    <div class="badge badge-success btn-status-change "
                                                        data-id="{{ base64_encode($row->category_id) }}">
                                                        <a title="Change Status" class="badge-success"
                                                            href="javascript:void(0)">Active
                                                        </a>
                                                    </div>
                                                @else
                                                    <div style="color: white !important"
                                                        class="badge btn-status-change badge-primary"
                                                        data-id="{{ base64_encode($row->category_id) }}">
                                                        <a title="Change Status" class="badge-primary"
                                                            href="javascript:void(0)"> In Active
                                                        </a>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                {{ dateFormat($row->created_at, 'd-M-Y H:i') }}
                                            </td>
                                            <td class="text-center">

                                                @if (validatePermissions('category/edit/{id}'))
                                                <a href="javascript:void(0)" data-id="{{ base64_encode($row->category_id) }}"
                                                    class="btn-edit btn-active-color-primary btn-sm me-1">
                                                    <i class="ki-duotone ki-pencil fs-2" style="color: #007bff">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                                @endif
                                                @if (validatePermissions('category/destroy/{id}'))
                                                    <a href="javascript:void(0)" data-id="{{ base64_encode($row->category_id) }}"
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
<script src="{{ asset('/assets/admin') }}/categories.js?v={{ time() }}"></script>

@stop
