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
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search Banner" />
                    </div>
                    @if (validatePermissions('banners/add'))
                        <div class="card-toolbar">
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i
                                    class="fa-solid fa-plus"></i> Add New Banner</a>

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
                                    <th class="min-w-150px">Banner</th>
                                    <th class="min-w-150px">Banner Type</th>
                                    <th class="min-w-100px">Banner Url</th>
                                    <th class="min-w-100px">Website Url</th>
                                    <th class="min-w-100px">Web Page Section</th>
                                    <th class="min-w-100px">Section Columns</th>
                                    <th class="min-w-100px">Device</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-150px">Created Date</th>
                                    <th class="min-w-80px text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($result)
                                    @foreach ($result as $row)
                                        <tr>
                                            <td class="p-5">
                                                <a style="color:black;" target="_blank" href="{{ $row->banner_path }}"
                                                    class=" text-hover-primary fs-6 d-flex align-items-center">
                                                    <img data-type="supplier-banner-path" src="{{ $row->banner_path }}" width="50" height="50"
                                                        style="border-radius:5px;object-fit:cover;"></img>
                                                    <span class="mx-2">{{ $row->banner_name ?? 'N/A' }}</span>

                                                </a>
                                            </td>

                                            <td> {{ $row->banner_used_in ?? '---' }}</td>
                                            <td> {{ $row->page_url ?? '---' }}</td>
                                            <td> {{ $row->website_url ?? '---' }}</td>
                                            <td> {{ $row->device ?? '--' }}</td>
                                            <td> {{ $row->web_page_section ?? '--' }}</td>
                                            <td> {{ $row->web_page_section_colums ?? '--' }}</td>
                                            <td>
                                                <a title="Change  Status" data-id="{{ base64_encode($row->id) }}" class="btn-status-change" href="javascript:void(0)">
                                                    {!!$row->status=='Active' ?
                                                    '<span class="badge badge-pill badge-success">Active</span>' :
                                                    '<span class="badge  badge-pill badge-danger">In-Active</span>'!!}
                                                </a>
                                            </td>
                                            <td>
                                                {{ dateFormat($row->created_at, 'd-M-Y H:i') }}
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
    <div id="kt_drawer_chat" data-kt-drawer-permanent="true" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true"
    data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
    data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-close="#kt_drawer_chat_close">
        <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
            <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
                <div class="card-title">
                    <div class="d-flex justify-content-center flex-column me-3">
                        <a class="fs-4 fw-bold text-gray-900 drawer-title  me-1 mb-2 lh-1"></a>
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
            <div class="card-body drawer-body">

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
<script src="{{ asset('/assets/admin') }}/banners.js?v={{time()}}"></script>

@stop
