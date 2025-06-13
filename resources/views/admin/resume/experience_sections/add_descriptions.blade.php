
<form name="fform" data-experience-id="{{ $row->id }}" enctype="multipart/form-data" id="experience_section_add_descriptions_form" method="post" action="">
    @csrf
    <div class="card-body drawer-body"  id="kt_drawer_chat_messenger_body">
    <input type="hidden" name="act" id="act" value="add">
    <input type="hidden" name="eid" id="eid" value="">
    <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
        data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
        data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
        <div class="row mb-8">
            <div class="fv-row mb-8 p-3">
                {{-- <label class="fs-6 fw-bold mb-2">Description Points</label> --}}
                @foreach($experienceDescriptions as $desKey => $description)
                    <div class="fv-row mb-2">
                        <input type="hidden" name="description_points[{{ $desKey+1 }}][id]" value="{{ $description->id }}">
                        <label class="fs-6 fw-semibold mb-2">Sort Number</label>
                        <input type="text" name="description_points[{{ $desKey+1 }}][sort_number]" id="sort_number" placeholder="Enter sort number" value="{{ $description->sort_number }}"
                            class="form-control" required />
                    </div>
                    <div class="fv-row mb-8">
                        <label class="fs-6 fw-semibold mb-2">Description </label>
                        <textarea cols="30" rows="5" maxlength="250" name="description_points[{{ $desKey+1 }}][description]" id="description-{{ $desKey+1 }}" placeholder="Enter description"
                            class="form-control" style="resize: none;">{{ $description->description }}</textarea>
                    </div>

                @endforeach
            </div>
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
