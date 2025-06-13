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
            <div class="fv-row mb-8">
                <label class="required fs-6 fw-semibold mb-2">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="{{ $row->start_date ?? '' }}"
                    class="form-control" required />
            </div>
            <div class="fv-row mb-8">
                <label class="fs-6 fw-semibold mb-2">End Date</label>
                <input type="date" name="end_date" id="end_date" value="{{ $row->start_date ?? '' }}"
                    class="form-control" />
            </div>
            <div class="fv-row mb-8">
                <label class="required fs-6 fw-semibold mb-2">Degree Name</label>
                <input type="text" name="degree_name" id="degree_name" placeholder="Enter degree name" value="{{ $row->degree_name ?? '' }}"
                    class="form-control" required />
            </div>
            <div class="fv-row mb-8">
                <label class="required fs-6 fw-semibold mb-2">Institute Name</label>
                <input type="text" name="institute_name" id="institute_name" placeholder="Enter institute name" value="{{ $row->institute_name ?? '' }}"
                    class="form-control" required />
            </div>
            <div class="fv-row mb-8">
                <label class="required fs-6 fw-semibold mb-2">Country</label>
                <input type="text" name="country" id="country" placeholder="Enter country name i.e Pakistan" value="{{ $row->country ?? '' }}"
                    class="form-control" required />
            </div>
            <div class="fv-row mb-8">
                <label class="required fs-6 fw-semibold mb-2">City</label>
                <input type="text" name="city" id="city" placeholder="Enter city name i.e Islamabad" value="{{ $row->city ?? '' }}"
                    class="form-control" required />
            </div>
            <div class="fv-row mb-8">
                <label class="required fs-6 fw-semibold mb-2">Grades/CGPA</label>
                <input type="text" name="grades" id="grades" placeholder="Enter grades i.e A+ or 900/1100 etc..." value="{{ $row->grades ?? '' }}"
                    class="form-control" required />
            </div>
            <div class="fv-row mb-8">
                <label class="required fs-6 fw-semibold mb-2">Description</label>
                <textarea cols="30" rows="3" maxlength="150" name="description" id="description" placeholder="Enter description" value="{{ $row->description ?? '' }}"
                    class="form-control" style="resize: none;">{{ $row->description ?? '' }}</textarea>
            </div>

        </div>
    </div>
    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
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
