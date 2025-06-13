<div class="row">
    <div class="pt-3 mb-8 col-md-12">
        <h3>Website Section</h3>
    </div>
    <div class="mb-8 col-md-12 images-block">
        <div class="row">
            <div class="mb-8 col-md-6">
                <label class="mb-2 fs-6 fw-semibold" for="website_fav_icon">Website Fav Icon</label>
                <input type="file" name="website_fav_icon" id="website_fav_icon" placeholder="Upload website fav icon"
                    class="form-control portfolio_setting_image_file" accept="image/*">
            </div>
            <div class="mb-8 col-md-6 d-flex justify-content-start align-items-end">
                <img src="{{ isset($headerSection->website_fav_icon) ? ($headerSection->website_base_media_url ?? '') . $headerSection->website_fav_icon : gallery_photo('empty.png', 'backgrounds') }}"
                    alt="Website Fav Icon" width="50" height="50">
            </div>
        </div>
    </div>
    <div class="mb-8 col-md-12 images-block">
        <div class="row">
            <div class="mb-8 col-md-6">
                <label class="mb-2 fs-6 fw-semibold" for="website_apple_touch_icon">Website Apple
                    Touch Icon</label>
                <input type="file" name="website_apple_touch_icon" id="website_apple_touch_icon"
                    placeholder="Upload website fav icon" class="form-control portfolio_setting_image_file"
                    accept="image/*">
            </div>
            <div class="mb-8 col-md-6 d-flex justify-content-start align-items-end">
                <img src="{{ isset($headerSection->website_apple_touch_icon) ? ($headerSection->website_base_media_url ?? '') . $headerSection->website_apple_touch_icon : gallery_photo('empty.png', 'backgrounds') }}"
                    alt="Website Apple Touch Icon" width="50" height="50">
            </div>
        </div>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Images Show/Hide</label>
        <select name="website_images_show_hide_toggle" id="website_images_show_hide_toggle" class="form-select"
            required>
            <option value="" disabled selected>Select Option</option>
            <option value="show"
                {{ ($headerSection->website_images_show_hide_toggle ?? '') == 'show' ? 'selected' : '' }}>
                Show</option>
            <option value="hide"
                {{ ($headerSection->website_images_show_hide_toggle ?? '') == 'hide' ? 'selected' : '' }}>
                Hide</option>
        </select>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Testimonial Show/Hide</label>
        <select name="job_website_testimonial_show_hide" id="job_website_testimonial_show_hide" class="form-select"
            required>
            <option value="" disabled selected>Select Option</option>
            <option value="show"
                {{ ($headerSection->job_website_testimonial_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                Show</option>
            <option value="hide"
                {{ ($headerSection->job_website_testimonial_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                Hide</option>
        </select>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Type (Business/Job)</label>
        <select name="website_business_or_job_selection" id="website_business_or_job_selection" class="form-select"
            required>
            <option value="" disabled selected>Select Option</option>
            <option value="business"
                {{ ($headerSection->website_business_or_job_selection ?? '') == 'business' ? 'selected' : '' }}>
                Business</option>
            <option value="job"
                {{ ($headerSection->website_business_or_job_selection ?? '') == 'job' ? 'selected' : '' }}>
                Job</option>
        </select>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Services Show/Hide</label>
        <select name="job_website_services_show_hide" id="job_website_services_show_hide" class="form-select" required>
            <option value="" disabled selected>Select Option</option>
            <option value="show"
                {{ ($headerSection->job_website_services_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                Show</option>
            <option value="hide"
                {{ ($headerSection->job_website_services_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                Hide</option>
        </select>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Experience Read More - Show/Hide</label>
        <select name="website_experience_read_more_show_hide_toggle" id="website_experience_read_more_show_hide_toggle"
            class="form-select" required>
            <option value="" disabled selected>Select Option</option>
            <option value="show"
                {{ ($headerSection->website_experience_read_more_show_hide_toggle ?? '') == 'show' ? 'selected' : '' }}>
                Show</option>
            <option value="hide"
                {{ ($headerSection->website_experience_read_more_show_hide_toggle ?? '') == 'hide' ? 'selected' : '' }}>
                Hide</option>
        </select>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Title</label>
        <textarea type="text" name="website_title" id="website_title" placeholder="Enter resume section description"
            class="form-control" required>{{ $headerSection->website_title ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Meta Description</label>
        <textarea type="text" name="website_meta_description" id="website_meta_description"
            placeholder="Enter resume section description" class="form-control" required>{{ $headerSection->website_meta_description ?? '' }}</textarea>
    </div>

    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Meta Keywords</label>
        <textarea type="text" name="website_meta_keywords" id="website_meta_keywords"
            placeholder="Enter resume section description" class="form-control" required>{{ $headerSection->website_meta_keywords ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Base Media URL</label>
        <textarea type="text" name="website_base_media_url" id="website_base_media_url"
            placeholder="Enter website base media url" class="form-control" required>{{ $headerSection->website_base_media_url ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Base Media PATH</label>
        <textarea type="text" name="website_base_media_path" id="website_base_media_path"
            placeholder="Enter website base media url" class="form-control" required>{{ $headerSection->website_base_media_path ?? '' }}</textarea>
    </div>
    <div class="md-8 col-md-12">
        <h4>
            Sections Show/Hide
        </h4>
        <div class="row">
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">About Section Show/Hide</label>
                <select name="website_about_section_show_hide" id="website_about_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->website_about_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>Show
                    </option>
                    <option value="hide"
                        {{ ($headerSection->website_about_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>Hide
                    </option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Stats Section Show/Hide</label>
                <select name="website_stats_section_show_hide" id="website_stats_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->website_stats_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>Show
                    </option>
                    <option value="hide"
                        {{ ($headerSection->website_stats_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>Hide
                    </option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Skills Section Show/Hide</label>
                <select name="website_skills_section_show_hide" id="website_skills_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->website_skills_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>Show
                    </option>
                    <option value="hide"
                        {{ ($headerSection->website_skills_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>Hide
                    </option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Resume Section Show/Hide</label>
                <select name="website_resume_section_show_hide" id="website_resume_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->website_resume_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>Show
                    </option>
                    <option value="hide"
                        {{ ($headerSection->website_resume_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>Hide
                    </option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Portfolio Section Show/Hide</label>
                <select name="website_portfolio_section_show_hide" id="website_portfolio_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->website_portfolio_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show
                    </option>
                    <option value="hide"
                        {{ ($headerSection->website_portfolio_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide
                    </option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Services Section Show/Hide</label>
                <select name="website_services_section_show_hide" id="website_services_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->website_services_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show
                    </option>
                    <option value="hide"
                        {{ ($headerSection->website_services_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide
                    </option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Testimonial Section Show/Hide</label>
                <select name="website_testimonial_section_show_hide" id="website_testimonial_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->website_testimonial_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show
                    </option>
                    <option value="hide"
                        {{ ($headerSection->website_testimonial_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide
                    </option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Contact Section Show/Hide</label>
                <select name="website_contact_section_show_hide" id="website_contact_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->website_contact_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show
                    </option>
                    <option value="hide"
                        {{ ($headerSection->website_contact_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide
                    </option>
                </select>
            </div>
        </div>
    </div>

    <!-- Whatsapp Section -->
    <div class="mb-8 col-md-12">
        <h3>Whatsapp Section</h3>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Whatsapp Show/Hide</label>
        <select name="website_whatsapp_show_hide" id="website_whatsapp_show_hide" class="form-select" required>
            <option value="" disabled selected>Select Whatsapp</option>
            <option value="show"
                {{ ($headerSection->website_whatsapp_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                Show</option>
            <option value="hide"
                {{ ($headerSection->website_whatsapp_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                Hide</option>
        </select>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Whatsapp Number <small>i.e 923056504512</small></label>
        <textarea type="text" name="website_whatsapp_number" id="website_whatsapp_number"
            placeholder="Enter website base media url" class="form-control" required>{{ $headerSection->website_whatsapp_number ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Whatsapp Auto Message</label>
        <textarea type="text" name="website_whatsapp_auto_message" id="website_whatsapp_auto_message"
            placeholder="Enter website base media url" class="form-control" required>{{ $headerSection->website_whatsapp_auto_message ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Footer Section Text</label>
        <textarea type="text" name="footer_section_text" id="footer_section_text"
            placeholder="Enter resume section description" class="form-control" required>{{ $headerSection->footer_section_text ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <h3 class="alert alert-info">Job Section</h3>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Title</label>
        <textarea type="text" name="job_website_title" id="job_website_title"
            placeholder="Enter resume section description" class="form-control" required>{{ $headerSection->job_website_title ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Meta Description</label>
        <textarea type="text" name="job_website_meta_description" id="job_website_meta_description"
            placeholder="Enter resume section description" class="form-control" required>{{ $headerSection->job_website_meta_description ?? '' }}</textarea>
    </div>

    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Meta Keywords</label>
        <textarea type="text" name="job_website_meta_keywords" id="job_website_meta_keywords"
            placeholder="Enter resume section description" class="form-control" required>{{ $headerSection->job_website_meta_keywords ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Whatsapp Number (JOB) <small>i.e
                923056504512</small></label>
        <textarea type="text" name="job_website_whatsapp_number" id="job_website_whatsapp_number"
            placeholder="Enter website base media url" class="form-control" required>{{ $headerSection->job_website_whatsapp_number ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Whatsapp Auto Message (JOB)</label>
        <textarea type="text" name="job_website_whatsapp_auto_message" id="job_website_whatsapp_auto_message"
            placeholder="Enter website base media url" class="form-control" required>{{ $headerSection->job_website_whatsapp_auto_message ?? '' }}</textarea>
    </div>
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Footer Section Text For Job</label>
        <textarea type="text" name="job_footer_section_text" id="job_footer_section_text"
            placeholder="Enter resume section description" class="form-control" required>{{ $headerSection->job_footer_section_text ?? '' }}</textarea>
    </div>
    <div class="md-8 col-md-12">
        <h4>
            Sections Show/Hide
        </h4>
        <div class="row">
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">About Section Show/Hide</label>
                <select name="job_website_about_section_show_hide" id="job_website_about_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->job_website_about_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show</option>
                    <option value="hide"
                        {{ ($headerSection->job_website_about_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide</option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Stats Section Show/Hide</label>
                <select name="job_website_stats_section_show_hide" id="job_website_stats_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->job_website_stats_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show</option>
                    <option value="hide"
                        {{ ($headerSection->job_website_stats_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide</option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Skills Section Show/Hide</label>
                <select name="job_website_skills_section_show_hide" id="job_website_skills_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->job_website_skills_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show</option>
                    <option value="hide"
                        {{ ($headerSection->job_website_skills_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide</option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Resume Section Show/Hide</label>
                <select name="job_website_resume_section_show_hide" id="job_website_resume_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->job_website_resume_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show</option>
                    <option value="hide"
                        {{ ($headerSection->job_website_resume_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide</option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Portfolio Section Show/Hide</label>
                <select name="job_website_portfolio_section_show_hide" id="job_website_portfolio_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->job_website_portfolio_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show</option>
                    <option value="hide"
                        {{ ($headerSection->job_website_portfolio_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide</option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Services Section Show/Hide</label>
                <select name="job_website_services_section_show_hide" id="job_website_services_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->job_website_services_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show</option>
                    <option value="hide"
                        {{ ($headerSection->job_website_services_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide</option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Testimonial Section Show/Hide</label>
                <select name="job_website_testimonial_section_show_hide"
                    id="job_website_testimonial_section_show_hide" class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->job_website_testimonial_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show</option>
                    <option value="hide"
                        {{ ($headerSection->job_website_testimonial_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide</option>
                </select>
            </div>

            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Contact Section Show/Hide</label>
                <select name="job_website_contact_section_show_hide" id="job_website_contact_section_show_hide"
                    class="form-select" required>
                    <option value="" disabled selected>Select Option</option>
                    <option value="show"
                        {{ ($headerSection->job_website_contact_section_show_hide ?? '') == 'show' ? 'selected' : '' }}>
                        Show</option>
                    <option value="hide"
                        {{ ($headerSection->job_website_contact_section_show_hide ?? '') == 'hide' ? 'selected' : '' }}>
                        Hide</option>
                </select>
            </div>

        </div>
    </div>
</div>
