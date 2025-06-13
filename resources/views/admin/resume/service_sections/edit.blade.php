<script>
    $('select[data-control="select2"]').select2();
    $(document).ready(function() {
        $('select[data-control="select2"]').select2();
    });
</script>
<form name="fform" enctype="multipart/form-data" id="fform" method="post" action="">
    @csrf
    <div class="card-body drawer-body" id="kt_drawer_chat_messenger_body">
        <input type="hidden" name="act" id="act" value="edit">
        <input type="hidden" name="eid" id="eid" value="{{ $row->id }}">
        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
            data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
            <div class="mb-8 row">
                <label class="text-center col-lg-12 col-form-label fw-bold fs-6">Service Thumbnail</label>
                <div class="text-center col-lg-12">
                    <div class="image-input image-input-outline" data-kt-image-input="true"
                        style="background-image: url('')">
                        <div class="image-input-wrapper w-125px h-125px" data-type="link-link-thumbnail"
                            style="background-image: url({{ $row->service_thumbnail ?? gallery_photo('empty.png', 'avatars') }})">
                        </div>
                        <label class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                            data-kt-image-input-action="change" data-bs-toggle="tooltip" title=""
                            data-bs-original-title="Change avatar">
                            <i class="bi bi-pencil-fill fs-7"></i>
                            <input data-type="file" type="file" name="service_thumbnail" accept=".png, .jpg, .jpeg">
                            <input type="hidden" name="avatar_remove">
                        </label>
                        <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title=""
                            data-bs-original-title="Cancel avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                        <span class="shadow btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body"
                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title=""
                            data-bs-original-title="Remove avatar">
                            <i class="bi bi-x fs-2"></i>
                        </span>
                    </div>
                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                </div>
            </div>
            <div class="mb-8 fv-row">
                <label class="mb-2 required fs-6 fw-semibold">Name</label>
                <input type="text" name="service_name" id="project_name" placeholder="Enter project name"
                    value="{{ $row->service_name ?? '' }}" class="form-control" required />
            </div>
            <div class="mb-8 fv-row">
                <label class="mb-2 required fs-6 fw-semibold">Slug</label>
                <input type="text" name="service_slug" id="service_slug" placeholder="Enter service slug"
                    value="{{ $row->service_slug ?? '' }}" class="form-control" required />
            </div>
            <div class="mb-8 fv-row">
                <label class="mb-2 required fs-6 fw-semibold">Icon Class</label>
                <input type="text" name="service_icon_class" id="service_icon_class" placeholder="Enter project name"
                    value="{{ $row->service_icon_class ?? '' }}" class="form-control" required />
            </div>
            <div class="mb-8 fv-row">
                <label class="mb-2 required fs-6 fw-semibold">Description</label>
                <textarea cols="30" rows="5" maxlength="500" name="description" id="description"
                    placeholder="Enter short description" value="" class="form-control" style="resize: none;">{{ $row->description ?? '' }}</textarea>
            </div>
            <div class="mb-8 fv-row">
                <label class="mb-2 fs-6 fw-semibold">Sidebar Title</label>
                <input type="text" name="service_sidebar_title" id="service_sidebar_title"
                    placeholder="Enter service sidebar title" value="{{ $row->service_sidebar_title ?? '' }}"
                    class="form-control" />
            </div>
            <div class="mb-8 fv-row">
                <label class="mb-2 fs-6 fw-semibold">Sidebar Description</label>
                <textarea cols="30" rows="5" maxlength="500" name="service_sidebar_description" id="sidebar_description"
                    placeholder="Enter sidebar description" value="" class="form-control" style="resize: none;">{{ $row->service_sidebar_description ?? '' }}</textarea>
            </div>
        </div>
    </div>
    <div class="pt-4 card-footer drawer-footer" id="kt_drawer_chat_messenger_footer">
        <div class="d-flex flex-stack float-end">
            <button class="btn btn-light me-5" id="kt_drawer_chat_close" type="button"
                data-kt-element="cancel">Close</button>
            <button type="submit" class="btn btn-primary btn-save">update</button>
        </div>
    </div>


</form>
<script>
    $('select[data-control="select2"]').select2();
    $(document).ready(function() {
        $('select[data-control="select2"]').select2();
    });
</script>
