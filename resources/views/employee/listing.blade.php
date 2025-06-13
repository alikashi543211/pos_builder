@extends('layouts.admin')
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
                <div class="card-toolbar">

                    @if (validatePermissions('team/add'))
                    <a id="assign-teams" href="javascript:void(0)" style="display: none;" class="btn-add-team btn btn-sm btn-info float-end me-3"><i class="fa-solid fa-plus"></i>Assign Team</a>
                    @endif

                    @if (validatePermissions('employee/add'))
                    <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i class="fa-solid fa-plus"></i>Add New Employee</a>
                    @endif
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table id="kt_table_users" class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="w-10px pe-2">
                                    {{-- <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true" data-kt-check-target="#kt_table_users .form-check-input" value="1" />
                                        </div> --}}
                                </th>
                                <th class="min-w-150px">Employee Name</th>
                                <th class="min-w-120px">Employee Ad ID</th>
                                <th class="min-w-100px">Employee Email</th>
                                <th class="min-w-100px">Employee Department</th>
                                <th class="min-w-100px">Employee Team</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-80px text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($result)
                            @foreach ($result as $row)

                            <tr>
                                <td>
                                @if (validatePermissions('team/add'))
                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                        <input class="form-check-input checkbox" type="checkbox" value="{{ $row->employee_ad_id }}" />
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    {{ $row->full_name }}
                                </td>
                                <td>
                                    {{ $row->employee_ad_id }}
                                </td>
                                <td>
                                    {{ $row->email_address }}
                                </td>
                                <td>
                                    {{ $row->department->department_name }}
                                </td>
                                <td>
                                    @if($row->teams)
                                    @foreach($row->teams as $emTeam)
                                    @if(isset($emTeam->team))
                                    <span class="badge badge-pill badge-light-warning">{{ $emTeam->team->team_name }}</span>
                                    @endif
                                    @endforeach

                                    @else
                                    -
                                    @endif

                                </td>

                                <td>
                                    <!-- <a title="Change Employee Status" data-uid="{{ $row->id }}" class="btn-status-change" href="javascript:void(0)">
                                        {!! $row->is_active == '1'
                                        ? '<span class="badge badge-pill badge-success">Active</span>'
                                        : '<span class="badge  badge-pill badge-danger">In-Active</span>' !!}
                                    </a> -->

                                    <span title="Change Employee Status" class="badge {{ $row->is_active  == 1 ? 'badge-light-success active-status' : 'badge-light-danger inactive-status' }}" data-uid="{{ $row->id }}" style="cursor: pointer;">
                                        {{ $row->is_active  == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if (validatePermissions('employee/edit/{id}'))
                                    <a href="javascript:void(0)" data-id="{{ $row->id }}" class=" btn-edit btn-active-color-primary btn-sm me-1">
                                        <i class="ki-duotone ki-pencil fs-2" style="color: #007bff">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    @endif
                                    @if (validatePermissions('employee/delete/{id}'))
                                    <a href="javascript:void(0)" data-id="{{ $row->id }}" class="btn-delete btn-active-color-primary btn-sm">
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
<div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle" data-kt-drawer-permanent="true" data-kt-drawer-close="#kt_drawer_chat_close">
    <div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
        <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
            <div class="card-title">
                <div class="d-flex justify-content-center flex-column me-3">
                    <a href="#" class="fs-4 fw-bold  drawer-title text-gray-900 text-hover-primary me-1 mb-2 lh-1">Add New
                        Employee</a>
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
<script src="{{ asset('/assets/admin') }}/employee.js?v=252"></script>
<script>
    $(document).ready(function() {
        $('select[data-control="select2"]').select2();
        $('#checkAll').change(function() {
            $('.checkbox').prop('checked', $(this).prop('checked'));
            toggleUpdateBtnVisibility();
        });

        $('.checkbox').change(function() {
            toggleUpdateBtnVisibility();
        });

        function toggleUpdateBtnVisibility() {
            if ($('.checkbox:checked').length > 0) {
                $('#assign-teams').show();
            } else {
                $('#assign-teams').hide();
            }
        }
    });
</script>
@stop
