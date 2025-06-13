<form name="sform" id="sform" method="post" action="{{ url('policies/edit/' . base64_encode($row->id)) }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="act" value="edit" id="act">

    <div class="card-body drawer-body" id="kt_drawer_chat_messenger_body">
        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
            data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">

            <div class="row">

                 <!-- Category Name -->
                 <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Policy Title</label>
                    <input type="text" name="policy_title" required data-err="Policy Title" value="{{ $row->policy_title ?? '' }}" class="form-control " id="policy_title" placeholder="Policy Title">
                </div>
                <!-- Category Name -->
                <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Sort Order</label>
                    <input type="number" name="order_number" required data-err="Order Number" value="{{ $row->order_number ?? '' }}" class="form-control " id="order_number" placeholder="Order Number">
                </div>
               <!-- Details / Overview -->
               <div class="col-12 mb-8">

                    <label class="fs-6 fw-semibold mb-2">Short Description</label>
                    <textarea name="short_description" placeholder="Enter Short Summary" id="shortDescription" class="form-control tinymce-editor">{{ @$row->short_description }}</textarea>

                </div>

                <!-- Details / Overview -->
               <div class="col-12 mb-8">

                    <label class="fs-6 fw-semibold mb-2">Long Description</label>
                    <textarea name="long_description" placeholder="Enter Long Description" id="detailsDescription" class="form-control tinymce-editor">{{ @$row->long_description }}</textarea>

                </div>



            </div>
        </div>
    </div>

    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
        <div class="d-flex flex-stack float-end">
            <button class="btn btn-light me-5" id="kt_activities_close" type="button" data-kt-element="cancel">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>

