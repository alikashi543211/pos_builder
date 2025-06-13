<form name="fform" enctype="multipart/form-data" id="fform" method="post" action="">
    @csrf
    <div class="card-body drawer-body" id="kt_drawer_chat_messenger_body">
        <input type="hidden" name="act" id="act" value="edit">
        <input type="hidden" name="eid" id="eid" value="{{ $row->id }}">
        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
    data-kt-scroll-height="auto"
    data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
    data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
    <div class="row mb-6">
        <label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
        <div class="col-lg-4">
            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('')">
                <div data-display-image="true" class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('/assets/admin/media/avatars/empty.png') }}')">
                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url('{{ asset('/assets/admin/media/avatars/empty.png') }}')">
                        <img src="{{ @$row->employee->custom_photo }}" id="avatar-src" height="120px" width="120px" />
                    </div>
                </div>
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
                    data-bs-original-title="Change avatar">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <input type="file" data-type="file" name="avatar" accept=".png, .jpg, .jpeg">
                    <input type="hidden" name="avatar_remove">
                </label>
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title=""
                    data-bs-original-title="Cancel avatar">
                    <i class="bi bi-x fs-2"></i>
                </span>
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" title=""
                    data-bs-original-title="Remove avatar">
                    <i class="bi bi-x fs-2"></i>
                </span>
            </div>
            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card border-1">
                <div class="card-header">
                    <h2 class="card-title fw-bold">General</h2>
                </div>
                <div class="card-body">
                    <div class="fv-row mb-8">
                        <label class="required fs-6 fw-semibold mb-2">First Name</label>
                        <input type="text" name="first_name" placeholder="First Name" id="first_name" value="{{ $row->employee->first_name ?? '' }}"
                            class="form-control" required />
                    </div>
                    <div class="fv-row mb-8">
                        <label class="required fs-6 fw-semibold mb-2">Last Name</label>
                        <input type="text" name="last_name" id="last_name" placeholder="Last Name" value="{{ $row->employee->last_name ?? '' }}"
                            class="form-control" required />
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Email Address</label>
                        <input type="text" name="email_address" placeholder="Email Address" id="email_address" value="{{ @$row->employee->email_address ?? '' }}"
                            class="form-control" />
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Phone / Mobile</label>
                        <input type="text" name="mobile_number" placeholder="Phone / Mobile" id="mobile_number" value="{{ @$row->employee->mobile_number ?? '' }}"
                            class="form-control" />
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Department</label>
                        <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="false" data-placeholder=" Choose Department "
                                        name="department_id">
                            <option value="" selected> Choose Department</option>
                            @if ($departments)
                                @foreach ($departments as $dep)
                                    <option value="{{ $dep->id }}" @if(@$row->employee->department_id == $dep->id) selected @endif>{{ $dep->department_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Designation Title</label>
                        <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="false" data-placeholder=" Choose Designation"
                                        name="designation_id">
                            <option value="" selected> Choose Designation</option>
                            @if ($designations)
                                @foreach ($designations as $dep)
                                    <option value="{{ $dep->id }}" @if(@$row->employee->designation_id == $dep->id) selected @endif>{{ $dep->designation_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Country</label>
                        <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="false" data-placeholder=" Choose Country "
                                        name="country_id">
                            <option value="" selected> Choose Country</option>
                            @if ($countries)
                                @foreach ($countries as $dep)
                                    <option value="{{ $dep->country_id }}" @if(@$row->employee->country == $dep->country_id) selected @endif>{{ $dep->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">City</label>
                        <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="false" data-placeholder=" Choose City "
                                        name="city_id">
                            <option value="" selected> Choose Department</option>
                            @if ($cities)
                                @foreach ($cities as $dep)
                                    <option value="{{ $dep->city_id }}" @if(@$row->employee->city_id == $dep->city_id) selected @endif>{{ $dep->city_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-1">
                <div class="card-header">
                    <h2 class="card-title fw-bold">Security</h2>
                </div>
                <div class="card-body">
                    <div class="fv-row mb-8">
                        <label class="required fs-6 fw-semibold mb-2">User Name</label>
                        <input type="text" name="user_name" placeholder="User Name" id="user_name" value="{{ $row->employee->user_name }}"
                            class="form-control" required />
                    </div>
                    <div class="fv-row mb-8">
                        <label class="required fs-6 fw-semibold mb-2">Choose Role</label>
                        <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="false" data-placeholder=" Choose Role "
                                        name="role">
                            <option value="" selected> Choose Role</option>
                            @if ($rolesResult)
                                @foreach ($rolesResult as $role)
                                    <option value="{{ $role->ID }}" @if(@$userRoleId == $role->ID) selected @endif>{{ $role->role_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Password</label>
                        <input type="password" name="password" autocomplete="off" placeholder="Password" id="password" value=""
                            class="form-control" />
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Confirm Password</label>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" value=""
                            class="form-control" />
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Reports To</label>
                        <select class="form-select form-select-solid" data-control="select2"
                                        data-hide-search="false" data-placeholder=" Choose Reports To "
                                        name="report_to">
                            <option value="" selected>-- Reports To --</option>
                            @if ($reportsTo)
                                @foreach ($reportsTo as $dep)
                                    <option value="{{ $dep->user_name }}" @if(@$row->employee->report_to == $dep->user_name) selected @endif>{{ $dep->employee->FullName ?? '' }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
    </div>
    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
        <div class="d-flex flex-stack float-end">
            <button class="btn btn-light me-5" id="kt_drawer_chat_close" type="button"
                data-kt-element="cancel">Close</button>
            <button type="submit" class="btn btn-primary btn-save">Save</button>
        </div>
    </div>


</form>
<script>
    $(document).ready(function() {
       $('select[data-control="select2"]').select2();
   });
</script>
