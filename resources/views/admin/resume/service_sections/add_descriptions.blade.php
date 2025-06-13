<form name="fform" data-project-id="{{ $row->id }}" enctype="multipart/form-data"
    id="project_section_add_descriptions_form" method="post" action="">
    @csrf
    <div class="card-body drawer-body" id="kt_drawer_chat_messenger_body">
        <input type="hidden" name="act" id="act" value="add">
        <input type="hidden" name="eid" id="eid" value="">
        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
            data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
            <div class="mb-8 row">
                <div class="p-3 mb-8 fv-row">
                    {{-- <label class="mb-2 fs-6 fw-bold">Description Points</label> --}}
                    @foreach ($projectDescriptions as $desKey => $description)
                        <div class="mb-2 fv-row">
                            <input type="hidden" name="description_points[{{ $desKey + 1 }}][id]"
                                value="{{ $description->id }}">
                            <label class="mb-2 fs-6 fw-semibold">Sort Number</label>
                            <input type="text" name="description_points[{{ $desKey + 1 }}][sort_number]"
                                id="sort_number" placeholder="Enter sort number" value="{{ $description->sort_number }}"
                                class="form-control" required />
                        </div>
                        <div class="mb-8 fv-row">
                            <label class="mb-2 fs-6 fw-semibold">Description </label>
                            <textarea cols="30" rows="5" name="description_points[{{ $desKey + 1 }}][description]"
                                id="description-{{ $desKey + 1 }}" placeholder="Enter description" class="form-control" style="resize: none;">{{ $description->description }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    <div class="pt-4 card-footer drawer-footer" id="kt_drawer_chat_messenger_footer">
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
