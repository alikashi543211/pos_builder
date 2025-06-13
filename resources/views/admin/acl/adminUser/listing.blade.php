@extends('layouts.admin')
@section('header')
@include('includes.adminHeader_nav')
@stop
@push('title')
    {{ @$pageTitle }}
@endpush
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
                    <input type="text" data-user-listing-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search User" />
                </div>
                <div class="card-toolbar">
                    @if (validatePermissions('acl/users/add'))
                    <a href="javascript:void(0)" class="btn btn-sm btn-primary float-end me-3 btn-add"><i class="fa-solid fa-plus"></i>Add New User</a>
                    @endif

                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table id="user-listing-table" class="table table-hover table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-150px">Name</th>
                                <th class="min-w-150px">User Name</th>
                                <th class="min-w-150px">Email Address</th>
                                <th class="min-w-150px">Mobile Number</th>
                                <th class="min-w-100px">Department</th>
                                <th class="min-w-100px">Designation</th>
                                <th class="min-w-100px">User Role </th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-80px text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($result)
                            @foreach ($result as $row)
                            <tr>

                                <td>
                                    {{ $row->employee->FullName ?? '' }}
                                </td>
                                <td>
                                    {{ $row->employee->user_name ?? '' }}
                                </td>
                                <td>
                                    {{ $row->employee->email_address ?? '' }}
                                </td>
                                <td>
                                    {{ $row->employee->mobile_number ?? '' }}
                                </td>
                                <td>
                                    {{ @$row->employee->department->department_name }}
                                </td>
                                <td>
                                    {{ @$row->employee->designation->designation_name }}
                                </td>

                                <td>
                                    {{ @$row->userroles[0]->role->role_name }}
                                </td>

                                <td>
                                    <span title="Change User Status" class="badge {{ $row->is_active  == 1 ? 'badge-success active-user' : 'badge-danger inactive-user' }}" data-uid="{{ $row->id }}" style="cursor: pointer;">
                                        {{ $row->is_active  == 1 ? 'Active' : 'Inactive' }}
                                    </span>

                                </td>
                                <td class="text-center">

                                    @if (validatePermissions('acl/users/edit/{id}'))
                                    <a href="javascript:void(0)" data-id="{{ $row->id }}" class=" btn-edit btn-active-color-primary btn-sm me-1">
                                        <i class="ki-duotone ki-pencil fs-2" style="color: #007bff">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </a>
                                    @endif
                                    @if (validatePermissions('acl/users/delete/{id}'))
                                    <a href="javascript:void(0)" data-id="{{ $row->id }}" class="btn-del  btn-active-color-primary btn-sm">
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
    <!--end::Post-->
</div>

@section('models')
<div id="kt_activities" style="width:80% !important" class="bg-body" data-kt-drawer-permanent="true" data-kt-drawer="true"
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
@stop
@section('footer')
@include('includes.adminFooter')
@stop
@section('script')
@include('includes.adminScripts')
<script src="{{ asset('/assets/admin') }}/js/admin.users.js?v=11"></script>
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
                window.location.href = admin_url + "/acl/users/delete/" + did;
            }
        })

    });

    //update status
    $(document).on('click', '.active-user, .inactive-user', function(e) {
        var status = $(this).hasClass('active-user') ? 0 : 1;
        var did = $(this).data("uid");
        var action = status == 1 ? 'Active' : 'Inactive';
        var confirmText = 'Are you sure you want to ' + action.toLowerCase() + ' this User?';
        var $badge = $(this);
        Swal.fire({
            title: 'Confirm',
            text: confirmText,
            icon: 'warning',
            animation: false,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, ' + action
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    type: 'GET',
                    url: admin_url + "/acl/users/change/" + did,
                    success: function(response) {
                        if(response.responseCode == 1)
                        {
                            if (status == 1) {
                                $badge.removeClass('badge-light-danger').addClass('badge-light-success').text('Active');

                                $badge.removeClass('inactive-user').addClass('active-user');
                            } else {
                                $badge.removeClass('badge-light-success').addClass('badge-light-danger').text('Inactive');
                                $badge.removeClass('active-user').addClass('inactive-user');

                            }
                            Swal.fire(
                                'Updated!',
                                'User status has been updated.',
                                'success'
                            ).then((result) => {
                                window.location.reload();
                            });

                        }else if(response.responseCode == 0)
                        {
                            Swal.fire(
                                'Error!',
                                response.msg,
                                'error'
                            );
                        }

                    },
                    error: function(xhr, status, error) {
                        console.log(error)
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deleting the user.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });

            }
        });
    });
</script>

@stop
