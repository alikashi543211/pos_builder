
<form name="fform" enctype="multipart/form-data" id="fform" method="post" action="">
    @csrf
    <div class="card-body drawer-body"  id="kt_drawer_chat_messenger_body">
    <input type="hidden" name="act" id="act" value="add">
    <input type="hidden" name="eid" id="eid" value="">
    <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
        data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
        data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
        <div class="row mb-6">
            <label class="col-lg-4 col-form-label fw-bold fs-6">Avatar</label>
            <div class="col-lg-8">
                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('')">
                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{photo('empty.png','avatars')}})"></div>
                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                        data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
                        data-bs-original-title="Change avatar">
                        <i class="bi bi-pencil-fill fs-7"></i>
                        <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
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
        <div class="fv-row mb-8">
            <label class="required fs-6 fw-semibold mb-2">Employee ID</label>
            <input type="text" name="employee_ad_id" placeholder="Employee Ad ID" id="employee_ad_id" value=""
                class="form-control" required />
        </div>
        <div class="fv-row mb-8">
            <label class="required fs-6 fw-semibold mb-2">Full Name</label>
            <input type="text" name="full_name" id="full_name" placeholder="Enter full name" value=""
                class="form-control" required />
        </div>
        <div class="fv-row mb-8">
            <label class="required fs-6 fw-semibold mb-2">Employee Email</label>
            <input type="text" name="email_address" placeholder="Enter email" id="email_address" value=""
                class="form-control" required />
        </div>
        <div class="fv-row mb-8">
            <label class="required fs-6 fw-semibold mb-2">Department</label>
            <select class="form-select form-select-solid" data-control="select2"
                            data-hide-search="false" data-placeholder=" Choose Department "
                            name="department">
                <option value="" selected> Choose Department</option>
                @if ($departments)
                    @foreach ($departments as $dep)
                        <option value="{{ $dep->id }}">{{ $dep->department_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>

    </div>
    </div>
    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
    <div class="d-flex flex-stack float-end">
        <button class="btn btn-light me-5" id="kt_drawer_chat_close" type="button" data-kt-element="cancel">Close</button>
        <button type="submit" class="btn btn-primary btn-save">Save</button>
    </div>
    </div>


</form>
<script>
    $(document).ready(function() {
       $('select[data-control="select2"]').select2();
   });
</script>
