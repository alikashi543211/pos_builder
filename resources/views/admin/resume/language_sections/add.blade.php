
<form name="fform" enctype="multipart/form-data" id="fform" method="post" action="">
    @csrf
    <div class="card-body drawer-body"  id="kt_drawer_chat_messenger_body">
    <input type="hidden" name="act" id="act" value="add">
    <input type="hidden" name="eid" id="eid" value="">
    <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
        data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
        data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">
        <div class="fv-row mb-8">
            <label class="required fs-6 fw-semibold mb-2">Language</label>
            <input type="text" name="language_title" id="language_title" placeholder="Enter language name" value=""
                class="form-control" required />
        </div>
        <div class="fv-row mb-8">
            <label class="required fs-6 fw-semibold mb-2">Rating</label>
            <select class="form-select form-select-solid" data-control="select2"
                            data-hide-search="false" data-placeholder=" Choose Rating "
                            name="language_rating">
                <option value="" selected> Choose Rating</option>
                @for ($k = 1; $k <= 5; $k++)
                    <option value="{{ $k }}">{{ $k }}</option>
                @endfor
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
