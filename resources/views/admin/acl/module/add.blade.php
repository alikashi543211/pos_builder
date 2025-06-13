
<form name="fform" enctype="multipart/form-data" id="fform" method="post" action="">
    @csrf
    <div class="card-body drawer-body"  id="kt_drawer_chat_messenger_body">
        <input type="hidden" name="act" id="act" value="add">
        <input type="hidden" name="eid" id="eid" value="">
        <input type="hidden" name="show_in_menu" value="0" id="show_in_menu">
<div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
    data-kt-scroll-height="auto"
    data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
    data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">

    <div class="fv-row mb-8">
        <label class="required fs-6 fw-semibold mb-2">Module Category</label>
        <select class="form-select form-select-solid" data-control="select2" data-hide-search="false"
            data-placeholder="Select a Team Member" name="module_category_id">
            <option value="" selected> Choose Category</option>
            @if($catResult)
                @foreach($catResult as $rowCat)
                <option value="{{$rowCat->ID}}">{{$rowCat->category_name}}</option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="fv-row mb-8">
        <label class="required fs-6 fw-semibold mb-2">Module Name</label>
        <input type="text" name="module_name" placeholder="Module Name" id="module_name" value=""
            class="form-control" required />
    </div>
    <div class="fv-row mb-8">
        <label class="required fs-6 fw-semibold mb-2">Slug</label>
        <input type="text" name="route" id="route" placeholder="Enter Slug" value=""
            class="form-control" required />
    </div>
    <div class="fv-row mb-8">
        <label class="fs-6 fw-semibold mb-2">CSS Class</label>
        <input type="text" name="css_class" placeholder="Enter CSS Class" id="css_class" value=""
            class="form-control"  />
    </div>
    <div class="fv-row mb-8">
        <label class="fs-6 fw-semibold mb-2">Show in Header</label>
        <input type="checkbox" name="show_in_menu_checkbox" id="show_in_menu_checkbox" value=""
            class="form-control form-check-input header-module" onChange="updateShowInMenuValue()"/>
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
    function updateShowInMenuValue() {
        if (document.getElementById('show_in_menu_checkbox').checked) {
            document.getElementById('show_in_menu').value = '1';
        } else {
            document.getElementById('show_in_menu').value = '0';
        }
    }

</script>
