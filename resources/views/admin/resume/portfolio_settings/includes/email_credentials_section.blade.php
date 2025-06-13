<div class="row">
    <div class="pt-3 mb-8 col-md-12">
        <h3>Email Credentials Section</h3>
    </div>

    <!-- Website Email Send (Yes/No) -->
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Email Send (Yes/No)</label>
        <select name="resume_website_send_email_selection" id="resume_website_send_email_selection" class="form-control"
            required>
            <option value="" disabled selected>Select Option</option>
            <option value="yes"
                {{ ($headerSection->resume_website_send_email_selection ?? '') == 'yes' ? 'selected' : '' }}>
                Yes
            </option>
            <option value="no"
                {{ ($headerSection->resume_website_send_email_selection ?? '') == 'no' ? 'selected' : '' }}>
                No
            </option>
        </select>
    </div>

    <!-- Website Email Send (Yes/No) -->
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">Website Email Send Mode (Local/Live)</label>
        <select name="resume_website_send_email_mode_selection" id="resume_website_send_email_mode_selection"
            class="form-control" required>
            <option value="" disabled selected>Select Option</option>
            <option value="local"
                {{ ($headerSection->resume_website_send_email_mode_selection ?? '') == 'local' ? 'selected' : '' }}>
                Local
            </option>
            <option value="live"
                {{ ($headerSection->resume_website_send_email_mode_selection ?? '') == 'live' ? 'selected' : '' }}>
                Live
            </option>
        </select>
    </div>

    <!-- SMTP Host -->
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">From Address</label>
        <input type="text" name="email_credentials_section_email_from_address"
            id="email_credentials_section_email_from_address"
            value="{{ $headerSection->email_credentials_section_email_from_address ?? '' }}"
            placeholder="Enter From Address" class="form-control" required autocomplete="off" />
    </div>

    <!-- SMTP Host -->
    <div class="mb-8 col-md-12">
        <label class="mb-2 required fs-6 fw-semibold">From Name</label>
        <input type="text" name="email_credentials_section_email_from_name"
            id="email_credentials_section_email_from_name"
            value="{{ $headerSection->email_credentials_section_email_from_name ?? '' }}" placeholder="Enter From Name"
            class="form-control" required autocomplete="off" />
    </div>

    <div class="mb-8 col-md-12">
        <div class="p-3 row bg-secondary">
            <div class="mb-8 col-md-12">
                <h4>Local</h4>
            </div>
            <!-- Transport -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Transport</label>
                <input type="text" name="email_credentials_section_email_transport"
                    id="email_credentials_section_email_transport"
                    value="{{ $headerSection->email_credentials_section_email_transport ?? '' }}"
                    placeholder="Enter SMTP host" class="form-control" required autocomplete="off" />
            </div>
            <!-- SMTP Host -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">SMTP Host</label>
                <input type="text" name="email_credentials_section_email_host"
                    id="email_credentials_section_email_host"
                    value="{{ $headerSection->email_credentials_section_email_host ?? '' }}"
                    placeholder="Enter SMTP host" class="form-control" required autocomplete="off" />
            </div>

            <!-- SMTP Port -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">SMTP Port</label>
                <input type="number" name="email_credentials_section_email_port"
                    id="email_credentials_section_email_port"
                    value="{{ $headerSection->email_credentials_section_email_port ?? '' }}"
                    placeholder="Enter SMTP port" class="form-control" required />
            </div>

            <!-- Email Username -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Email Username</label>
                <input type="text" name="email_credentials_section_email_username"
                    id="email_credentials_section_email_username"
                    value="{{ $headerSection->email_credentials_section_email_username ?? '' }}"
                    placeholder="Enter email username" class="form-control" required autocomplete="off" />
            </div>

            <!-- Email Password -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Email Password</label>
                <input type="text" name="email_credentials_section_email_password"
                    id="email_credentials_section_email_password"
                    value="{{ $headerSection->email_credentials_section_email_password ?? '' }}"
                    placeholder="Enter email password" class="form-control" required autocomplete="new-password" />
            </div>

            <!-- Encryption Type -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Encryption Type</label>
                <select name="email_credentials_section_email_encryption"
                    id="email_credentials_section_email_encryption" class="form-control" required>
                    <option value="" disabled selected>Select Encryption</option>
                    <option value="tls"
                        {{ ($headerSection->email_credentials_section_email_encryption ?? '') == 'tls' ? 'selected' : '' }}>
                        TLS</option>
                    <option value="ssl"
                        {{ ($headerSection->email_credentials_section_email_encryption ?? '') == 'ssl' ? 'selected' : '' }}>
                        SSL</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mb-8 col-md-12">
        <div class="p-3 row bg-secondary">
            <div class="mb-8 col-md-12">
                <h4>Live</h4>
            </div>
            <!-- Transport -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Transport</label>
                <input type="text" name="email_credentials_section_live_email_transport"
                    id="email_credentials_section_live_email_transport"
                    value="{{ $headerSection->email_credentials_section_live_email_transport ?? '' }}"
                    placeholder="Enter SMTP host" class="form-control" required autocomplete="off" />
            </div>
            <!-- SMTP Host -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">SMTP Host</label>
                <input type="text" name="email_credentials_section_live_email_host"
                    id="email_credentials_section_live_email_host"
                    value="{{ $headerSection->email_credentials_section_live_email_host ?? '' }}"
                    placeholder="Enter SMTP host" class="form-control" required autocomplete="off" />
            </div>

            <!-- SMTP Port -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">SMTP Port</label>
                <input type="number" name="email_credentials_section_live_email_port"
                    id="email_credentials_section_live_email_port"
                    value="{{ $headerSection->email_credentials_section_live_email_port ?? '' }}"
                    placeholder="Enter SMTP port" class="form-control" required />
            </div>

            <!-- Email Username -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Email Username</label>
                <input type="text" name="email_credentials_section_live_email_username"
                    id="email_credentials_section_live_email_username"
                    value="{{ $headerSection->email_credentials_section_live_email_username ?? '' }}"
                    placeholder="Enter email username" class="form-control" required autocomplete="off" />
            </div>

            <!-- Email Password -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Email Password</label>
                <input type="text" name="email_credentials_section_live_email_password"
                    id="email_credentials_section_live_email_password"
                    value="{{ $headerSection->email_credentials_section_live_email_password ?? '' }}"
                    placeholder="Enter email password" class="form-control" required autocomplete="new-password" />
            </div>

            <!-- Encryption Type -->
            <div class="mb-8 col-md-12">
                <label class="mb-2 required fs-6 fw-semibold">Encryption Type</label>
                <select name="email_credentials_section_live_email_encryption"
                    id="email_credentials_section_live_email_encryption" class="form-control" required>
                    <option value="" disabled selected>Select Encryption</option>
                    <option value="tls"
                        {{ ($headerSection->email_credentials_section_live_email_encryption ?? '') == 'tls' ? 'selected' : '' }}>
                        TLS</option>
                    <option value="ssl"
                        {{ ($headerSection->email_credentials_section_live_email_encryption ?? '') == 'ssl' ? 'selected' : '' }}>
                        SSL</option>
                </select>
            </div>
        </div>
    </div>

</div>
