<form name="fform" enctype="multipart/form-data" id="fform" method="post" action="">
    @csrf
    <input type="hidden" name="act" id="act" value="add">
    <input type="hidden" name="eid" id="eid" value="">

    <div class="card-body " style="width: 100% !important" id="kt_activities_body">
        <!--begin::Content-->
        <div id="kt_activities_scroll" class="position-relative scroll-y me-n5 pe-5" data-kt-scroll="true"
            data-kt-scroll-height="auto" data-kt-scroll-wrappers="#kt_activities_body"
            data-kt-scroll-dependencies="#kt_activities_header, #kt_activities_footer" data-kt-scroll-offset="0px">
            <!--begin::Timeline items-->
            <div class="timeline timeline-border-dashed">
                <div class="fv-row">
                    <label class="required fs-6 fw-semibold mb-2">Role Name</label>
                    <input type="text" name="role_name" placeholder="Role Name" id="role_name"
                        class="form-control" required />
                </div>



            </div>
            <!--end::Timeline items-->
        </div>
        <!--end::Content-->
    </div>
    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
        <div class="d-flex flex-stack float-end">
            <button class="btn btn-light me-5" id="kt_activities_close" type="button"
                data-kt-element="cancel">Close</button>
            <button type="submit" class="btn btn-primary btn-save">Save</button>
        </div>
    </div>

</form>
