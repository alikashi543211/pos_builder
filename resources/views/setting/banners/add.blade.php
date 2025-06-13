<form name="sform" id="sform" method="post" action="{{ url('banners/add') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="act" value="add" id="act">
    <input type="hidden" name="eid" id="eid" value="">

    <div class="card-body drawer-body" id="kt_drawer_chat_messenger_body">
        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
            data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">

            <div class="row">

                <div class="col-12 mb-8">
                    <div class="row">
                        <div class="col-md-12 d-flex flex-column justify-center align-items-center">
                            <div>
                                <img data-type="display-file" src="{{ url(env('STORAGEPATH') . '/blank-gallery.png') }}"
                                style="border-radius:5px; width:450px;height:180px;margin-top:28px;object-fit:cover;"></img>
                            </div>

                        </div>
                        <div class="col-md-12">
                            <label class="fw-semibold fs-6 mb-2" for="category_icon">Banner Image</label>
                            <input type="file" data-type="input-file" class="form-control" name="banner_path" id="banner_path" accept="image/*">
                        </div>

                    </div>
                </div>
                 <!-- Category Name -->
                 <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Banner Name</label>
                    <input type="text" name="banner_name" required data-err="Banner Name" class="form-control " id="banner_name" placeholder="Banner Name">
                </div>

                <!-- Category Name -->
                <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Banner Used In</label>
                    <select class="form-select form-select-solid" name="banner_used_in" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                        <option value="">--Select Option--</option>
                        <option value="slider">Slider</option>
                        <option value="in-page">Page</option>
                    </select>
                </div>

                 <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Banner Url</label>
                    <input type="text" name="page_url" required data-err="Banner Page Url" class="form-control " id="page_url" placeholder="Banner Page Url">
                </div>
                <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Website Url</label>
                    <input type="text" name="website_url" required data-err="Website Url" class="form-control " id="website_url" placeholder="Website Url">
                </div>
                <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Website Page Section</label>
                    <input type="text" name="web_page_section" required data-err="Website Url" class="form-control " id="web_page_section" placeholder="Website Page Section">
                </div>

                <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Section Columns</label>
                    <input type="number" name="web_page_section_colums" required data-err="Website Section columns" class="form-control " id="web_page_section_columns" placeholder="Section Columns">
                </div>

                <!-- Parent Category -->
                <div class="col-12 mb-6">
                    <label class="fw-semibold fs-6 mb-2">Select Device</label>
                    <select class="form-select form-select-solid" name="device" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                        <option value="">--Select Device--</option>
                        <option value="Web">Web</option>
                        <option value="Mobile">Mobile</option>
                        <option value="All" selected>All</option>
                    </select>
                </div>
                <!-- Category Name -->
                <div class="col-12 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Sort Order</label>
                    <input type="number" name="order_number" required data-err="Order Number" class="form-control " id="order_number" placeholder="Order Number">
                </div>



            </div>
        </div>
    </div>

    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
        <div class="d-flex flex-stack float-end">
            <button class="btn btn-light me-5" id="kt_drawer_chat_close" type="button" data-kt-element="cancel">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>

<!-- TinyMCE Integration -->
<script src="{{ asset('/') }}assets/plugins/custom/tinymce/tinymce.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[data-control="select2"]').select2();

        tinymce.init({
            selector: '#detailsDescription',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: 300
        });

              // Generate slug from title
        $("#category_name").keyup(function() {
                var Text = $(this).val();
                Text = Text.toLowerCase();
                Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
                $("#page_url").val(Text);
            });
    });



</script>
