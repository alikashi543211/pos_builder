
<form name="fform" enctype="multipart/form-data" id="project_section_add_images_form" data-project-id="{{ $row->id }}" method="post" action="">
    @csrf
    <div class="card-body drawer-body"  id="kt_drawer_chat_messenger_body">
    <input type="hidden" name="act" id="act" value="add">
    <input type="hidden" name="eid" id="eid" value="">
    <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
        data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
        data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
        <div class="fv-row mb-8">
            <label class="fs-6 fw-semibold mb-2">Project Name: <span style="color: gray;">{{ $row->project_name ?? "" }}</span></label>
            {{-- <input type="text" name="project_name" readonly id="project_name" value="{{ $row->project_name ?? "" }}"
                class="form-control" /> --}}
        </div>
        <div class="fv-row mb-8">
            <label class="required fs-6 fw-semibold mb-2">Upload Project Images</label>
            <input data-type="project-images-file" type="file" name="project_images[]" id="project_images"
                class="form-control" required multiple accept=".png, .jpg, .jpeg" />
        </div>
        <div class="fv-row mb-8">
            <div class="row p-0" id="project_images_gallery">
                @foreach ($projectImages as $pImage)
                    <div class="col-md-4 p-1 position-relative">
                        <img src="{{ $pImage->image_path }}" style="object-fit: cover;" width="150" height="100" alt="">
                        <button type="button" class="btn btn-danger btn-sm delete-image-btn"
                                data-image-id="{{ $pImage->id }}"
                                style="position: absolute; top: 5px; right: 5px; z-index: 10;">
                            &times;
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
    <div class="d-flex flex-stack float-end">
        <button class="btn btn-light me-5" id="kt_drawer_chat_close" type="button" data-kt-element="cancel">Close</button>
        <button type="submit" class="btn btn-primary btn-save">Upload</button>
    </div>
    </div>

</form>

<script>
    $(document).ready(function() {
       $('select[data-control="select2"]').select2();
   });
</script>
