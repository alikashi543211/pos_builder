<form name="sform" enctype="multipart/form-data" id="sform" method="post" action="" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="act" id="act" value="edit">
    <input type="hidden" name="eid" id="eid"  value="{{ base64_encode($row->category_id) }}">

    <div class="card-body drawer-body" id="kt_drawer_chat_messenger_body">
        <div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true"
            data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer"
            data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body" data-kt-scroll-offset="0px">

            <div class="row">
                <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Select Language</label>
                    <select id="language" class="form-select form-select-solid" name="language" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                        @foreach($languages as $language)
                        <option value="{{$language->id}}" {{ (isset($page) && $page->lang_id == $language->id) ? 'selected' : '' }} @if(@$row->lang_id == $language->id) selected @endif>{{$language->name}}</option>
                        @endforeach
                    </select>
                </div>
                   <!-- Parent Category -->
                   <div class="col-6 mb-6">
                    <label class="fw-semibold fs-6 mb-2">Select Parent Category</label>
                    <select class="form-select form-select-solid" name="parent_id" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                        <option value="0">--Select Parent Category--</option>
                        @foreach($Categories as $category)
                        <option value="{{$category->id}}" @if(@$row->parent_id == $category->id) selected @endif>{{@$category->categoriesLang->category_name}}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Category Name -->
                <div class="col-6 mb-6">
                    <label class=" required fs-6 fw-semibold mb-2">Category Name</label>
                    <input type="text" name="category_name" placeholder="Enter Category Name" id="category_name"
                        value="{{ @$row->category_name }}" class="form-control"  />
                </div>

                <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Category Title</label>
                    <input type="text" value="{{ @$row->category_title }}" name="category_title" required data-err="Category Title" class="form-control " id="category_title" placeholder="Category Title">
                </div>

                <!-- Meta Title -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Meta Title</label>
                    <input type="text" name="meta_title" placeholder="Enter Meta Title" id="meta_title"
                        value="{{ @$row->meta_title }}" class="form-control" />
                </div>

                <!-- Meta Keywords -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Meta Keywords</label>
                    <input type="text" name="meta_keywords" placeholder="Enter Meta Keywords" id="meta_keywords"
                        value="{{ @$row->meta_keywords }}" class="form-control" />
                </div>

                <!-- Page URL -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Page URL</label>
                    <input type="text" name="page_url" placeholder="Enter Page URL" id="page_url"
                        value="{{ @$row->page_url }}" class="form-control" />
                </div>

                {{-- Category ICON --}}
                <div class="col-6 mb-8">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="fw-semibold fs-6 mb-2" for="category_icon">Category Icon</label>
                            <input type="file" data-type="input-file-1" class="form-control" name="category_icon" id="category_icon" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <img data-type="display-file-1" src="{{ @$row->category->category_icon }}" height="40"
                                style="border-radius:5px; margin-top:28px;"></img>
                        </div>
                    </div>
                </div>

                {{-- Category ICON --}}
                <div class="col-6 mb-8">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="fw-semibold fs-6 mb-2" for="category_image">Category Image</label>
                            <input type="file" data-type="input-file-2" class="form-control" name="category_image" id="category_image" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <img data-type="display-file-2" src="{{ @$row->category->category_image }}" height="40"
                                style="border-radius:5px; margin-top:28px;"></img>
                        </div>
                    </div>
                </div>

                {{-- Category ICON --}}
                <div class="col-6 mb-8">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="fw-semibold fs-6 mb-2" for="category_banner">Category Banner</label>
                            <input type="file" data-type="input-file-3" class="form-control" name="category_banner" id="category_banner" accept="image/*">
                        </div>
                        <div class="col-md-4">
                            <img data-type="display-file-3" src="{{ @$row->category->category_banner }}" height="40"
                                style="border-radius:5px; margin-top:28px;"></img>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Is Product</label>
                    <select class="form-select" name="is_product" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                       <option value="">---</option>
                       <option value="1" {{ @$row->is_product == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ @$row->is_product == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-6 mb-6">
                    <label class="required fw-semibold fs-6 mb-2">Is Clickable</label>
                    <select class="form-select" name="is_clickable" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                       <option value="">---</option>
                       <option value="1" {{ @$row->is_clickable == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ @$row->is_clickable == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Canonical URL -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Canonical URL</label>
                    <input type="text" name="canonical_tag" placeholder="Enter Canonical URL" id="canonical_tag"
                        value="{{ @$row->canonical_tag }}" class="form-control" />
                </div>

                 <!-- Key Information -->
                 <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Key Information</label>
                    <input type="text" name="key_information" placeholder="Enter Key Information" id="key_information"
                        value="{{ @$row->key_information }}" class="form-control" />
                </div>
                <!-- Meta Description -->
                <div class="col-12 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Meta Description</label>
                    <input type="text" name="meta_description" placeholder="Enter Meta Description" id="meta_description"
                        value="{{ @$row->meta_description }}" class="form-control" />
                </div>

                <!-- Status -->




                <!-- Sort Order -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Sort Order</label>
                    <input type="number" name="sort_order" placeholder="Enter Sort Order" id="sort_order"
                        value="{{ @$row->sort_order }}" class="form-control" />
                </div>

                <!-- Featured -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Featured</label>
                    <select class="form-select" name="featured" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                     <option value="1" {{ @$row->featured == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ @$row->featured == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Top Navigation Link -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Top Navigation Link</label>
                    <select class="form-select" name="top_navigation" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">

                        <option value="1" {{ @$row->top_navigation == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ @$row->top_navigation == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Footer Navigation Link -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Footer Navigation Link</label>
                    <select class="form-select" name="footer_navigation" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">
                        <option value="1" {{ @$row->footer_navigation == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ @$row->footer_navigation == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Left Navigation Link -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Left Navigation Link</label>
                    <select class="form-select" name="left_navigation" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">

                        <option value="1" {{ @$row->left_navigation == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ @$row->left_navigation == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>

                <!-- Right Navigation Link -->
                <div class="col-6 mb-6">
                    <label class=" fs-6 fw-semibold mb-2">Right Navigation Link</label>
                    <select  class="form-select" name="right_navigation" data-control="select2" data-close-on-select="false" data-placeholder="Select an option" data-allow-clear="true">

                        <option value="1" {{ @$row->right_navigation == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ @$row->right_navigation == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <!-- Details / Overview -->
                <div class="col-12 mb-8">

                    <label class="fs-6 fw-semibold mb-2">Short Summary</label>
                    <textarea name="short_summary" placeholder="Enter Short Summary" id="short_summary" class="form-control tinymce-editor">{{ @$row->short_summary }}</textarea>

                </div>
                <!-- Details / Overview -->
                <div class="col-12 mb-6">
                    <label class="fs-6 fw-semibold mb-2">Details / Overview</label>
                    <textarea name="full_description" placeholder="Enter Details" id="detailsDescription"
                        class="form-control ">{{ @$row->full_description }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer pt-4 drawer-footer" id="kt_drawer_chat_messenger_footer">
        <div class="d-flex flex-stack float-end">
            <a class="btn btn-light me-5" href="{{ url('categories') }}" id="kt_activities_close" type="button">Cancel</a>
            <button type="submit" class="btn btn-primary btn-save">Update</button>
        </div>
    </div>
</form>

<!-- TinyMCE Integration -->
<script src="{{asset('/')}}assets/plugins/custom/tinymce/tinymce.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('select[data-control="select2"]').select2();

        tinymce.init({
            selector: '#detailsDescription', '#short_summary'
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            toolbar_mode: 'floating',
            height: 300
        });

         // Fetch and update content on language change
         $('#language').change(function(e) {
        var langId = $(this).val();
        var categoryId = $('#eid').val();

        $.ajax({
            url: '{{ route('fetchCategoryContentByLanguage') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                lang_id: langId,
                categoryId: categoryId
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Populate form fields with the response data
                    $('#category_name').val(response.data.category_name);
                    $('#meta_title').val(response.data.meta_title);
                    $('#meta_keywords').val(response.data.meta_keywords);
                    $('#page_url').val(response.data.page_url);
                    $('#canonical_tag').val(response.data.canonical_tag);
                    $('#key_information').val(response.data.key_information);
                    $('#meta_description').val(response.data.meta_description);
                    $('#sort_order').val(response.data.sort_order);

                    // Select the appropriate options for select fields
                    $('select[name="featured"]').val(response.data.featured).trigger('change');
                    $('select[name="top_navigation"]').val(response.data.top_navigation).trigger('change');
                    $('select[name="footer_navigation"]').val(response.data.footer_navigation).trigger('change');
                    $('select[name="left_navigation"]').val(response.data.left_navigation).trigger('change');
                    $('select[name="right_navigation"]').val(response.data.right_navigation).trigger('change');

                    // Update the TinyMCE editor content
                    tinymce.get('detailsDescription').setContent(response.data.full_description);
                } else {
                    $('#category_name').val('');
                    // alert('Failed to fetch content');
                }
            },
            error: function() {
                alert('Error fetching content');
            }
        });
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
