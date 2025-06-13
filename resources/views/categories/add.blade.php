<form name="sform" id="sform" method="post" action="" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="act" value="add" id="act">
    <input type="hidden" name="eid" id="eid" value="">

    <div class="card-body drawer-body" id="kt_drawer_chat_messenger_body">
        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
            data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">

            <div class="row">
                <div class="col-6 mb-6">
                    <label class="fw-semibold fs-6 mb-2">Select Language</label>
                    <select class="form-select form-select-solid" name="language" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                        @foreach($languages as $language)
                        <option value="{{$language->id}}">{{$language->name}}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Parent Category -->
                <div class="col-6 mb-6">
                    <label class="fw-semibold fs-6 mb-2">Select Parent Category</label>
                    <select class="form-select form-select-solid" name="parent_id" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                        <option value="0">--Select Parent Category--</option>
                        @foreach($Categories as $category)
                        <option value="{{$category->id}}">{{@$category->categoriesLang->category_name}}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Category Name -->
                <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Category Name</label>
                    <input type="text" name="category_name" required data-err="Category Name" class="form-control " id="category_name" placeholder="Category Name">
                </div>
                <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Category Title</label>
                    <input type="text" name="category_title" required data-err="Category Title" class="form-control " id="category_title" placeholder="Category Title">
                </div>
                   <!-- Page URL -->
                   <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Page URL</label>
                    <input type="text" name="page_url" required data-err="Page URL" class="form-control " id="page_url" placeholder="Page URL">
                </div>

                <div class="col-6 mb-8">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="fw-semibold fs-6 mb-2" for="category_icon">Category Icon</label>
                            <input type="file" data-type="input-file-1" class="form-control" name="category_icon" id="category_icon" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <img data-type="display-file-1" src="{{ photo('empty.png','avatars') }}" height="40"
                                style="border-radius:5px; margin-top:28px;"></img>
                        </div>
                    </div>
                </div>

                <div class="col-6 mb-8">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="fw-semibold fs-6 mb-2" for="category_image">Category Image</label>
                            <input type="file" data-type="input-file-2" class="form-control" name="category_image" id="category_image" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <img data-type="display-file-2" src="{{ photo('empty.png','avatars') }}" height="40"
                                style="border-radius:5px; margin-top:28px;"></img>
                        </div>
                    </div>
                </div>

                <div class="col-6 mb-8">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="fw-semibold fs-6 mb-2" for="category_banner">Category Banner</label>
                            <input type="file" data-type="input-file-3" class="form-control" name="category_banner" id="category_banner" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <img data-type="display-file-3" src="{{ photo('empty.png','avatars') }}" height="40"
                                style="border-radius:5px; margin-top:28px;"></img>
                        </div>
                    </div>
                </div>

                <!-- Meta Title -->
                <div class="col-6 mb-6">
                    <label class="fw-semibold fs-6 mb-2">Meta Title</label>
                    <input type="text" name="meta_title" data-err="Meta Title" class="form-control " id="meta_title" placeholder="Meta Title">
                </div>

                <!-- Meta Keywords -->
                <div class="col-6 mb-6">
                    <label class=" fw-semibold fs-6 mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" data-err="Meta Keywords" class="form-control " id="meta_keywords" placeholder="Meta Keywords">
                </div>



                <!-- Canonical URL -->
                <div class="col-6 mb-6">
                    <label class=" fw-semibold fs-6 mb-2">Canonical URL</label>
                    <input type="text" name="canonical_tag" data-err="Canonical URL" class="form-control " id="canonical_tag" placeholder="Canonical URL">
                </div>

                <!-- Meta Description -->
                <div class="col-6 mb-6">
                    <label class=" fw-semibold fs-6 mb-2">Meta Description</label>
                    <input type="text" name="meta_description" data-err="Meta Description" class="form-control " id="meta_description" placeholder="Meta Description">
                </div>


                <!-- Key Information -->
                <div class="col-6 mb-6">
                    <label class=" fw-semibold fs-6 mb-2">Key Information</label>
                    <input type="text" name="key_information"data-err="Key Information" class="form-control " id="key_information" placeholder="Key Information">
                </div>

                <!-- Sort Order -->
                <div class="col-6 mb-6">
                    <label class=" fw-semibold fs-6 mb-2">Sort Order</label>
                    <input type="number" name="sort_order" data-err="Sort Order" class="form-control " id="sort_order" placeholder="Sort Order">
                </div>
                   <!-- Product -->
                   <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Is Product</label>
                    <select class="form-select" name="is_product" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                       <option value="">---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Is Clickable</label>
                    <select class="form-select" name="is_clickable" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                       <option value="">---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>


                <!-- Featured -->
                <div class="col-4 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Featured</label>
                    <select class="form-select" name="featured" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                       <option value="">---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>
             
                <!-- Top Navigation Link -->
                <div class="col-4 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Top Navigation Link</label>
                    <select class="form-select" name="top_navigation" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                      <option value="">---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Footer Navigation Link -->
                <div class="col-4 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Footer Navigation Link</label>
                    <select class="form-select" name="footer_navigation" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                       <option value="">---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Left Navigation Link -->
                <div class="col-4 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Left Navigation Link</label>
                    <select class="form-select" name="left_navigation" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                        <option value="">---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Right Navigation Link -->
                <div class="col-4 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Right Navigation Link</label>
                    <select class="form-select" name="right_navigation" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                    <option value="">---</option>
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </div>

                <!-- Details / Overview -->
                <div class="col-12 mb-8">

                    <label class="fs-6 fw-semibold mb-2">Short Summary</label>
                    <textarea name="short_summary" placeholder="Enter Short Summary" id="short_summary" class="form-control tinymce-editor"></textarea>

                </div>

                <!-- Details / Overview -->
                <div class="col-12 mb-8">

                    <label class="fs-6 fw-semibold mb-2">Details / Overview</label>
                    <textarea name="full_description" placeholder="Enter details" id="detailsDescription" class="form-control tinymce-editor"></textarea>

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

<!-- TinyMCE Integration -->
<script src="{{ asset('/') }}assets/plugins/custom/tinymce/tinymce.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

              // Generate slug from title
        $("#category_name").keyup(function() {
                var Text = $(this).val();
                Text = Text.toLowerCase();
                Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
                $("#page_url").val(Text);
            });
    });



</script>
